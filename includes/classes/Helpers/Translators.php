<?php
/**
 * Translation helper class.
 *
 * @since 6.5.1
 * @package WPTravelEngine
 */

namespace WPTravelEngine\Helpers;

class Translators {

	public function __construct() {
		add_filter( 'wpml_pre_save_pro_translation', function ( $postarr, $job ) {

			if ( WP_TRAVEL_ENGINE_POST_TYPE !== ( $postarr[ 'post_type' ] ?? false ) ) {
				return $postarr;
			}

			return static::wpml_pre_save_pro_translation( $postarr, $job );

		}, 10, 2 );
	}

	public static function wpml_pre_save_pro_translation( $postarr, $job ) {

		$original_trip_id = $job->original_doc_id;
		$post_metas       = get_post_meta( $original_trip_id );

		$meta_input = $postarr[ 'meta_input' ] ?? array();
		foreach ( $post_metas as $key => $value ) {
			if ( preg_match( "#^_wpml_#", $key ) ) {
				continue;
			}

			$meta_input[ $key ] = maybe_unserialize( $value[ 0 ] );
		}

		$postarr[ 'meta_input' ] = $meta_input;

		return $postarr;
	}

	/**
	 * @return array[]|false|mixed|null
	 *
	 */
	public static function get_current_language() {
		if ( function_exists( 'pll_current_language' ) ) {
			$language = pll_current_language();

			return $language ? [ $language => array() ] : false;
		}

		return apply_filters( 'wpml_active_languages', null, [] );
	}

}
