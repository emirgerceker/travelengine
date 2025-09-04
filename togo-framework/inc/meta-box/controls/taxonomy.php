<?php
/**
 * Register Taxonomy For Post Type
 *
 */
if (!function_exists('uxper_register_taxonomy')) {

	function uxper_register_taxonomy()
	{
		$GLOBALS['is_taxonomy'] = array();
		$custom_tax = apply_filters('uxper_register_taxonomy', array());
		foreach ($custom_tax as $tax => $args) {
			if (!is_array($args)) {
				return;
			}
			if (!isset($args['post_type'])) {
				return;
			}

			$post_type = array_unique((array)$args['post_type']);
			$label = isset($args['label']) ? $args['label'] : $tax;
			$singular_name = isset($args['singular_name']) ? $args['singular_name'] : $label;

			foreach ($post_type as $value) {
				if (!empty($value)) {
					$GLOBALS['is_taxonomy'][$value] = $tax;
				}
			}

			$default = array(
				'hierarchical' => true,
				'label'        => $label,
				'query_var'    => true,
				'rewrite'      => array(
					'slug'       => $tax, // This controls the base slug that will display before each term
					'with_front' => false // Don't display the category base before
				),
				'labels'       => array(
					'singular_name'              => $singular_name,
					'search_items'               => sprintf(__('Search %s', 'uxper-booking'), $label),
					'popular_items'              => sprintf(__('Popular %s', 'uxper-booking'), $label),
					'all_items'                  => sprintf(__('All %s', 'uxper-booking'), $label),
					'parent_item'                => sprintf(__('Parent %s', 'uxper-booking'), $singular_name),
					'parent_item_colon'          => sprintf(__('Parent %s:', 'uxper-booking'), $singular_name),
					'edit_item'                  => sprintf(__('Edit %s', 'uxper-booking'), $singular_name),
					'view_item'                  => sprintf(__('View %s', 'uxper-booking'), $singular_name),
					'update_item'                => sprintf(__('Update %s', 'uxper-booking'), $singular_name),
					'add_new_item'               => sprintf(__('Add New %s', 'uxper-booking'), $singular_name),
					'new_item_name'              => sprintf(__('New %s New', 'uxper-booking'), $singular_name),
					'separate_items_with_commas' => sprintf(__('Separate %s with commas', 'uxper-booking'), strtolower($label)),
					'add_or_remove_items'        => sprintf(__('Add or remove %s', 'uxper-booking'), strtolower($label)),
					'choose_from_most_used'      => sprintf(__('Choose from the most used %s', 'uxper-booking'), strtolower($label)),
					'not_found'                  => sprintf(__('No %s found.', 'uxper-booking'), strtolower($label)),
					'no_terms'                   => sprintf(__('No %s', 'uxper-booking'), strtolower($label)),
					'items_list_navigation'      => sprintf(__('%s list navigation', 'uxper-booking'), $label),
					'items_list'                 => sprintf(__('%s list', 'uxper-booking'), $label),
				)
			);

			$args = wp_parse_args($args, $default);
			$args['labels'] = wp_parse_args($args['labels'], $default['labels']);
			register_taxonomy(
				$tax,       //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
				$post_type, //post type name
				$args
			);

		}
	}

	add_action('init', 'uxper_register_taxonomy', 0);
}
