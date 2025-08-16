<?php
/**
 * Single Trip Display Settings.
 *
 * @since 6.2.0
 */
$taxonomies      = get_taxonomies( array( 'object_type' => array( WP_TRAVEL_ENGINE_POST_TYPE ) ), 'objects' );
$trip_taxonomies = array();

foreach ( $taxonomies as $taxonomy ) {
	$trip_taxonomies[] = array(
		'label' => $taxonomy->labels->singular_name,
		'value' => $taxonomy->name,
	);
}

foreach ( array(
	'layout-1' => 'Default',
	'layout-2' => 'Compact',
	'layout-3' => 'Breeze',
) as $pricing_layout => $pricing_layout_label
) {
	$pricing_widget_layouts[] = array(
		'label' => $pricing_layout_label,
		'value' => $pricing_layout,
		'image' => esc_url( WP_TRAVEL_ENGINE_FILE_URL . '/public/images/' . esc_attr( $pricing_layout ) . '.gif' ),
	);
}

//Banner Layouts
foreach ( array(
	'banner-default',
	'banner-layout-1',
	'banner-layout-2',
	'banner-layout-3',
	'banner-layout-4',
	'banner-layout-5',
	'banner-layout-6',
) as $layout => $value) {
	$trip_banner_layouts[] = array(
		'value' => $value,
		'image' => esc_url( WP_TRAVEL_ENGINE_FILE_URL . '/public/images/trip-banner/' . esc_attr( $value ) . '.svg' ),
	);
}

$related_difficulty_tax_url = get_site_url() . '/wp-admin/edit-tags.php?taxonomy=difficulty&post_type=trip';
$related_tag_tax_url        = get_site_url() . '/wp-admin/edit-tags.php?taxonomy=trip_tag&post_type=trip';
$is_trip_reviews_active     = defined( 'WTE_TRIP_REVIEW_FILE_PATH' );

return apply_filters(
	'display-single-trip',
	array(
		'title'  => __( 'Single Trip', 'wp-travel-engine' ),
		'order'  => 10,
		'id'     => 'display-single-trip',
		'fields' => array(
			array(
				'visibility' => ! $is_trip_reviews_active,
				'field_type' => 'ALERT',
				'content'    => sprintf(
					__( '<strong>Note: </strong>Looking to enhance your website with genuine trip reviews? Trip Reviews is the perfect extension for WP Travel Engine users. %1$sGet Trip Reviews extension now%2$s.', 'wp-travel-engine' ),
					'<a target="_blank" href="https://wptravelengine.com/plugins/trip-reviews/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro">',
					'</a>'
				),
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Banner Layouts', 'wp-travel-engine' ),
			),

			array(
				'divider'     => true,
				'field_type'  => 'IMAGE_SELECTOR',
				'name'        => 'trip_banner_layout',
				'options'     => $trip_banner_layouts,
				'label'       => __( 'Select The Banner Layout', 'wp-travel-engine'),
				'isNew' 	  => WP_TRAVEL_ENGINE_VERSION === '6.3.3',
				'direction'   => 'vertical'
			),
			array(
				'label'      => __( 'Display Fullwidth', 'wp-travel-engine'),
				'help'       => __( 'Stretch banner image to fit the screen width.', 'wp-travel-engine'),
				'field_type' => 'SWITCH',
				'name'       => 'display_banner_fullwidth',
				'condition'  => 'trip_banner_layout === banner-layout-1',
				'divider'    => true,
			),
			array(
				'label'      => __( 'Show Booking Form', 'wp-travel-engine' ),
				'help'       => __( 'If checked, booking form in the trip detail page will be enabled.', 'wp-travel-engine' ),
				'field_type' => 'SWITCH',
				'name'       => 'enable_booking_form',
				'divider'    => true,
			),
			array(
				'field_type' => 'TITLE',
				'title'      => __( 'Pricing Widget Layouts (For smaller devices)', 'wp-travel-engine' ),
			),
			array(
				'description' => '',
				'field_type'  => 'IMAGE_SELECTOR',
				'name'        => 'pricing_section_layout',
				'divider'     => true,
				'options'     => $pricing_widget_layouts,
			),
			array(
				'label'       => __( 'Compact Layout', 'wp-travel-engine' ),
				'description' => __( 'When you enable this option, it will make the above models compact on mobile devices, taking up less space on the screen.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enable_compact_layout',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Inquiry Info', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'description' => __( 'Enable this option if you want to display the inquiry form. By default, it is linked to the inquiry form section on your single trip page.', 'wp-travel-engine' ),
				'name'        => 'inquiry_form.enable',
				'divider'     => true,
			),
			array(
				'field_type' => 'GROUP',
				'condition'  => 'inquiry_form.enable === true',
				'fields'     => array(
					array(
						'label'       => __( 'Inquiry Form', 'wp-travel-engine' ),
						'description' => '',
						'field_type'  => 'SELECT_BUTTON',
						'name'        => 'inquiry_form.link_type',
						'divider'     => true,
						'options'     => array(
							array(
								'label' => __( 'Default', 'wp-travel-engine' ),
								'value' => 'default',
							),
							array(
								'label' => __( 'Custom', 'wp-travel-engine' ),
								'value' => 'custom',
							),
						),
					),
					array(
						'label'      => __( 'Inquiry Form Link', 'wp-travel-engine' ),
						'field_type' => 'TEXT',
						'condition'  => 'inquiry_form.link_type === custom',
						'name'       => 'inquiry_form.link',
						'divider'    => true,
					),
				),
			),
			array(
				'label'       => __( 'Show WhatsApp', 'wp-travel-engine' ),
				'description' => __( 'Enable this option if you want to link your WhatsApp number.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'whatsapp.enable',
				'divider'     => true,
			),
			array(
				'field_type' => 'GROUP',
				'condition'  => 'whatsapp.enable === true',
				'fields'     => array(
					array(
						'label'       => __( 'WhatsApp Number', 'wp-travel-engine' ),
						'description' => __( 'Add your WhatsApp number along with the country code.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'whatsapp.number',
						'placeholder' => '+1-234-567-890',
						'divider'     => true,
					),
				),
			),
			array(
				'label'       => __( 'Make Tabs Sticky and Scrollable', 'wp-travel-engine' ),
				'description' => __( 'If checked, the trip content will be scrollable.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enable_tabs_sticky',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Make Booking Widget Sticky and Scrollable', 'wp-travel-engine' ),
				'description' => __( 'If enabled, the booking widget will be scrollable.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enable_booking_widget_sticky',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Show Related Trips', 'wp-travel-engine' ),
				'description' => __( 'Enable to display related trips in the trip pages.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'related_trips.enable',
				'divider'     => true,
			),
			array(
				'field_type' => 'GROUP',
				'condition'  => 'related_trips.enable === true',
				'fields'     => array(
					array(
						'label'       => __( 'Related Section Title', 'wp-travel-engine' ),
						'description' => '',
						'field_type'  => 'TEXT',
						'default'     => 'Related trips you might interested in',
						'name'        => 'related_trips.title',
						'divider'     => true,
					),
					array(
						'label'       => __( 'No. of Related Trips to display', 'wp-travel-engine' ),
						'description' => '',
						'field_type'  => 'NUMBER',
						'default'     => '3',
						'name'        => 'related_trips.number',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Enable Related Trips By', 'wp-travel-engine' ),
						'description' => '',
						'field_type'  => 'SELECT',
						'name'        => 'related_trips.show_by',
						'options'     => $trip_taxonomies,
						'divider'     => true,
					),
				),
			),
			array(
				'label'       => __( 'New Layout for Related Trips', 'wp-travel-engine' ),
				'description' => __( 'Enable to display new design in related trip section.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'related_trip_new_layout.enable',
				'divider'     => true,
			),
			array(
				'field_type' => 'GROUP',
				'condition'  => 'related_trip_new_layout.enable === true',
				'fields'     => array(
					array(
						'label'       => __( 'Enable Slider', 'wp-travel-engine' ),
						'description' => '',
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_slider',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Enable Featured Tag on Card', 'wp-travel-engine' ),
						'description' => __( 'Enable to show featured tag on card.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_featured_tag',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Enable Wishlist', 'wp-travel-engine' ),
						'description' => '',
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_wishlist',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Enable Map', 'wp-travel-engine' ),
						'description' => '',
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_map',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Show Excerpt', 'wp-travel-engine' ),
						'description' => __( 'Enable to display related trip excerpt.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_excerpt',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Enable Difficulty', 'wp-travel-engine' ),
						'description' => sprintf( __( 'Click <a href="%s" target="_blank">here</a> to add difficulty level.', 'wp-travel-engine' ), esc_url( $related_difficulty_tax_url ) ),
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_difficulty',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Enable Tag', 'wp-travel-engine' ),
						'description' => sprintf( __( 'Click <a href="%s" target="_blank">here</a> to add a tag.', 'wp-travel-engine' ), esc_url( $related_tag_tax_url ) ),
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_tags',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Enable Next Departure Dates', 'wp-travel-engine' ),
						'description' => __( 'Enable to show next departure dates.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_fsd',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Enable Available Months', 'wp-travel-engine' ),
						'description' => __( 'Enable to show available months on card.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_available_months',
						'divider'     => true,
					),
					array(
						'visibility'  => 'related_trip_new_layout.enable_available_months === true',
						'label'       => __( 'Enable Available Dates', 'wp-travel-engine' ),
						'description' => __( 'Enable to show available dates on hover.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'related_trip_new_layout.enable_available_dates',
						'divider'     => true,
					),
				),
			),
			array(
				'label'       => __( 'Show Trip Info', 'wp-travel-engine' ),
				'description' => __( 'Check to display the trip info section in the trip single sidebar.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enable_trip_info',
				'divider'     => true,
			),
			array(
				'field_type' => 'GROUP',
				'condition'  => 'enable_trip_info === true',
				'fields'     => array(
					array(
						'label'       => __( 'Show Trip Infos on Sidebar', 'wp-travel-engine' ),
						'description' => __( 'Check to display the trip info section in the trip single sidebar.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'enable_trip_info_on_sidebar',
						'divider'     => true,
					),
					array(
						'label'       => __( 'Show Trip Infos on Main Content Area', 'wp-travel-engine' ),
						'description' => __( 'Check to display the trip info section in the trip content area.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'enable_trip_info_on_main_content',
						'divider'     => true,
					),
				),
			),
			array(
				'label'       => __( 'Image Gallery Autoplay', 'wp-travel-engine' ),
				'description' => __( 'Default: Disabled. Activate this option to enable the auto-slide feature for gallery images.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enable_image_autoplay',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Trip Duration', 'wp-travel-engine' ),
				'description' => __( 'Show the preferred duration format on your single trip.', 'wp-travel-engine' ),
				'field_type'  => 'SELECT_BUTTON',
				'name'        => 'trip_duration_format',
				'divider'     => true,
				'options'     => array(
					array(
						'label' => __( 'Days', 'wp-travel-engine' ),
						'value' => 'days',
					),
					array(
						'label' => __( 'Days and Nights', 'wp-travel-engine' ),
						'value' => 'days_and_nights',
					),
				),
			),
			array(
				'label'       => __( 'Show Trip Featured Image', 'wp-travel-engine' ),
				'description' => __( 'Only works for a legacy trip gallery. If checked, the featured image in the trip detail page will be enabled.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enable_featured_image',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Show Featured Image in Image Gallery', 'wp-travel-engine' ),
				'description' => __( 'Only works for a legacy trip page. If checked, featured image will be added to image gallery in single trip page and trip card.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enable_image_in_gallery',
				'divider'     => true,
			),
			array(
				'label'       => __( 'FSE Template', 'wp-travel-engine' ),
				'description' => sprintf( __( 'Enable to use the new Full Site Editing (FSE) templates provided by WP Travel Engine. For more details, refer to the %1$s documentation%2$s which guides you on how to set up these templates.', 'wp-travel-engine' ), '<a target="_blank" href="https://docs.wptravelengine.com/article/wp-travel-engine-blocks-patterns-templates/?utm_source=free_plugin&utm_medium=dashboard&utm_campaign=docs">','</a>' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enable_fse',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Show Enquiry Form', 'wp-travel-engine' ),
				'description' => __( 'Turn this off to hide the enquiry form on Trip Page.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enquiry_enable',
				'divider'     => true,
			),
			array(
				'label'       => __( 'GDPR Message', 'wp-travel-engine' ),
				'description' => __( 'Enter the GDPR message for the trip enquiry form.', 'wp-travel-engine' ),
				'field_type'  => 'TEXTAREA',
				'name'        => 'gdpr_msg',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Custom Enquiry Form', 'wp-travel-engine' ),
				'description' => __( 'Enable this feature to utilize a custom form. Please ensure that a notification email is included within the custom form.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enquiry_custom_form.enable',
				'divider'     => true,
			),
			array(
				'field_type' => 'GROUP',
				'condition'  => 'enquiry_custom_form.enable === true',
				'fields'     => array(
					array(
						'label'       => __( 'Form Shortcode', 'wp-travel-engine' ),
						'description' => __( 'Add the custom form shortcode. We have made WP Travel Engine compatible with popular forms likes Gravity Form, Ninja Forms and WPForms.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'enquiry_custom_form.shortcode',
						'divider'     => true,
					),
				),
			),
		),
	),
);
