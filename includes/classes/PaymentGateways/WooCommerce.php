<?php
/**
 * WooCommerce Payment Gateway.
 *
 * @package WPTravelEngine\PaymentGateways
 */

namespace WPTravelEngine\PaymentGateways;

use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Core\Booking\BookingProcess;

/**
 * WooCommerce Payment Gateway integration.
 *
 * Redirects WTE bookings to WooCommerce checkout and syncs order statuses.
 *
 * @since 6.0.0
 */
class WooCommerce extends BaseGateway {

    /**
     * Setup hooks for WooCommerce integration.
     */
    public function __construct() {
        if ( class_exists( '\\WooCommerce' ) ) {
            add_action( 'init', [ $this, 'maybe_boot_wc_checkout' ] );
            add_action( 'woocommerce_checkout_create_order', [ $this, 'attach_booking_to_order' ], 10, 2 );
            add_action( 'woocommerce_order_status_changed', [ $this, 'sync_order_status' ], 10, 4 );
            add_action( 'updated_post_meta', [ $this, 'sync_booking_status_to_order' ], 10, 4 );
        }
    }

    /** @inheritDoc */
    public function get_gateway_id(): string {
        return 'woocommerce';
    }

    /** @inheritDoc */
    public function get_label(): string {
        return __( 'WooCommerce', 'wp-travel-engine' );
    }

    /** @inheritDoc */
    public function get_public_label(): string {
        return __( 'Pay with WooCommerce', 'wp-travel-engine' );
    }

    /** @inheritDoc */
    public function get_info(): string {
        return __( 'Checkout securely using WooCommerce payment gateways.', 'wp-travel-engine' );
    }

    /** @inheritDoc */
    public function get_description(): string {
        return __( 'Redirect to WooCommerce checkout to complete the booking payment.', 'wp-travel-engine' );
    }

    /**
     * Process the payment by redirecting to WooCommerce checkout.
     */
    public function process_payment( Booking $booking, Payment $payment, BookingProcess $booking_instance ): void {
        $payable = $payment->get_meta( 'payable' );
        $payload = [
            'booking_id' => $booking->ID,
            'payment_id' => $payment->get_id(),
            'amount'     => $payable['amount'] ?? 0,
        ];

        $token = wp_generate_password( 12, false );
        set_transient( "wte_wc_{$token}", $payload, MINUTE_IN_SECONDS * 30 );

        $checkout_url = add_query_arg( [ 'wte_wc_token' => $token ], wc_get_checkout_url() );
        wp_redirect( $checkout_url );
        exit;
    }

    /**
     * Prepare WooCommerce checkout with booking data.
     */
    public function maybe_boot_wc_checkout() {
        if ( ! function_exists( 'WC' ) || ! is_checkout() ) {
            return;
        }

        $token = isset( $_GET['wte_wc_token'] ) ? sanitize_text_field( wp_unslash( $_GET['wte_wc_token'] ) ) : '';
        if ( empty( $token ) ) {
            return;
        }

        $payload = get_transient( "wte_wc_{$token}" );
        if ( ! $payload ) {
            return;
        }

        WC()->cart->empty_cart();
        WC()->cart->add_fee( __( 'Trip Payment', 'wp-travel-engine' ), (float) $payload['amount'], false );
        WC()->session->set( 'wte_wc_payload', $payload );
    }

    /**
     * Attach booking information to the WooCommerce order.
     *
     * @param \WC_Order $order Order object.
     */
    public function attach_booking_to_order( $order, $data ) {
        $payload = WC()->session->get( 'wte_wc_payload' );
        if ( empty( $payload ) ) {
            return;
        }

        $order->update_meta_data( '_wte_booking_id', $payload['booking_id'] );
        $order->update_meta_data( '_wte_payment_id', $payload['payment_id'] );

        $booking = Booking::make( $payload['booking_id'] );
        $booking->set_meta( '_wte_wc_order_id', $order->get_id() )->save();
    }

    /**
     * Sync WooCommerce order status changes to booking status.
     */
    public function sync_order_status( $order_id, $old_status, $new_status, $order ) {
        $booking_id = $order->get_meta( '_wte_booking_id' );
        if ( ! $booking_id ) {
            return;
        }

        $map = [
            'completed'  => 'completed',
            'processing' => 'booked',
            'failed'     => 'failed',
            'cancelled'  => 'canceled',
            'refunded'   => 'refunded',
        ];

        if ( isset( $map[ $new_status ] ) ) {
            Booking::make( $booking_id )->update_status( $map[ $new_status ] );
        }
    }

    /**
     * Sync booking status updates back to WooCommerce order.
     */
    public function sync_booking_status_to_order( $meta_id, $object_id, $meta_key, $_meta_value ) {
        if ( 'wp_travel_engine_booking_status' !== $meta_key ) {
            return;
        }

        $order_id = get_post_meta( $object_id, '_wte_wc_order_id', true );
        if ( ! $order_id ) {
            return;
        }

        $map = [
            'completed' => 'completed',
            'booked'    => 'processing',
            'failed'    => 'failed',
            'canceled'  => 'cancelled',
            'refunded'  => 'refunded',
        ];

        $status = get_post_meta( $object_id, 'wp_travel_engine_booking_status', true );
        if ( isset( $map[ $status ] ) ) {
            $order = wc_get_order( $order_id );
            if ( $order && $order->get_status() !== $map[ $status ] ) {
                $order->update_status( $map[ $status ] );
            }
        }
    }
}

