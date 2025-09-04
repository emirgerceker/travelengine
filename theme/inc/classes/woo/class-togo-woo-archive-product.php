<?php

namespace Togo\Woo;

defined('ABSPATH') || exit;

class ArchiveProduct
{

    protected static $instance = null;

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct()
    {
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);

        add_action('woocommerce_before_main_content', array($this, 'archive_product_open_wrapper'), 20);
        add_action('woocommerce_after_main_content', array($this, 'archive_product_close_wrapper'), 20);
        add_action('woocommerce_before_shop_loop', array($this, 'archive_product_open_top'), 10);
        add_action('woocommerce_before_shop_loop', array($this, 'woocommerce_layout'), 40);
        add_action('woocommerce_before_shop_loop', array($this, 'archive_product_close_top'), 50);
        add_action('woocommerce_before_shop_loop_item', array($this, 'woocommerce_product_card_open_thumbnail'), 5);
        add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15);
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'woocommerce_product_card_close_thumbnail'), 20);
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'woocommerce_product_card_open_info'), 30);
        add_action('woocommerce_after_shop_loop_item_title', array($this, 'woocommerce_product_card_close_info'), 30);

        add_filter('woocommerce_sale_flash', array($this, 'woocommerce_sale_flash_percent'), 10, 3);
    }

    public function archive_product_open_wrapper()
    {
        echo '<div class="archive-product-wrapper">';
    }

    public function archive_product_close_wrapper()
    {
        echo '</div>';
    }

    public function archive_product_open_top()
    {
        echo '<div class="archive-product-top">';
    }

    public function woocommerce_layout()
    {
        $columns = apply_filters('loop_shop_columns', 4);
        echo '<div class="woocommerce-layout">';
        echo '<a class="woocommerce-layout-item list ' . ($columns === 1 ? 'is-active' : '') . '" href="#">';
        echo \Togo\Icon::get_svg('2col-horizontal');
        echo '</a>';
        echo '<a class="woocommerce-layout-item grid-2col ' . ($columns === 2 ? 'is-active' : '') . '" href="#">';
        echo \Togo\Icon::get_svg('2col');
        echo '</a>';
        echo '<a class="woocommerce-layout-item grid-3col ' . ($columns === 3 ? 'is-active' : '') . '" href="#">';
        echo \Togo\Icon::get_svg('3col');
        echo '</a>';
        echo '<a class="woocommerce-layout-item grid-4col ' . ($columns === 4 ? 'is-active' : '') . '" href="#">';
        echo \Togo\Icon::get_svg('4col');
        echo '</a>';
        echo '</div>';
    }

    public function archive_product_close_top()
    {
        echo '</div>';
    }

    public function woocommerce_product_card_open_thumbnail()
    {
        echo '<div class="product-thumbnail">';
    }

    public function woocommerce_product_card_close_thumbnail()
    {
        echo '</div>';
    }

    public function woocommerce_product_card_open_info()
    {
        echo '<div class="product-info">';
    }

    public function woocommerce_product_card_close_info()
    {
        echo '</div>';
    }

    public function woocommerce_sale_flash_percent($html, $post, $product)
    {
        if ($product->is_type('simple')) {
            // Get the regular price and sale price
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();

            if ($regular_price && $sale_price && $regular_price > $sale_price) {
                // Calculate the percentage discount
                $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);

                // Customize the Sale badge text with the percentage
                $html = '<span class="onsale">-' . $percentage . '%</span>';
            }
        }

        return $html;
    }
}
