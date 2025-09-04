<?php
defined('ABSPATH') || exit;

if (!class_exists('Togo_Metabox')) {
	class Togo_Metabox
	{

		protected static $instance = null;

		public static function instance()
		{
			if (null === self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize()
		{
			add_filter('uxper_meta_box_config', array($this, 'register_meta_boxes'));
		}

		/**
		 * Register Metabox
		 *
		 * @param $meta_boxes
		 *
		 * @return array
		 */
		public function register_meta_boxes($configs)
		{
			$page_registered_sidebars = \Togo\Helper::get_registered_sidebars(true);

			$configs['togo_post_meta_boxes'] = apply_filters('togo_post_meta_boxes', array(
				'id'        => 'togo_page_options',
				'name'      => esc_html__('Page Settings', 'togo'),
				'post_type' => array('post', 'page'),
				'section'   => array_merge(
					apply_filters('togo_post_meta_boxes_top', array()),
					apply_filters(
						'togo_post_meta_boxes_main',
						array_merge(
							array(
								array(
									'id'     => "post_layout_tabs",
									'title'  => esc_html__('Layout', 'togo'),
									'icon'   => 'dashicons-align-left',
									'fields' => array(
										array(
											'id'      => 'site_layout',
											'type'    => 'select',
											'title'   => esc_html__('Layout', 'togo'),
											'desc'    => esc_html__('Controls the layout of this page.', 'togo'),
											'options' => array(
												''          => esc_attr__('Default', 'togo'),
												'boxed'     => esc_attr__('Boxed', 'togo'),
												'fullwidth' => esc_attr__('Full Width', 'togo'),
											),
											'default' => '',
											'col'   => 12,
										),
										array(
											'id'    => 'content_top_spacing',
											'type'  => 'text',
											'title' => esc_html__('Content Top Spacing', 'togo'),
											'desc'  => esc_html__('Controls the top spacing of content page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.', 'togo'),
											'default' => '80px',
											'col'   => 12,
										),
										array(
											'id'    => 'content_bottom_spacing',
											'type'  => 'text',
											'title' => esc_html__('Content Bottom Spacing', 'togo'),
											'desc'  => esc_html__('Controls the bottom spacing of content page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.', 'togo'),
											'default' => '80px',
											'col'   => 12,
										),
										array(
											'id'    => 'content_top_spacing_tablet',
											'type'  => 'text',
											'title' => esc_html__('Content Top Spacing Tablet', 'togo'),
											'desc'  => esc_html__('Controls the top spacing of content page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.', 'togo'),
											'default' => '60px',
											'col'   => 12,
										),
										array(
											'id'    => 'content_bottom_spacing_tablet',
											'type'  => 'text',
											'title' => esc_html__('Content Bottom Spacing Tablet', 'togo'),
											'desc'  => esc_html__('Controls the bottom spacing of content page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.', 'togo'),
											'default' => '60px',
											'col'   => 12,
										),
										array(
											'id'    => 'content_top_spacing_mobile',
											'type'  => 'text',
											'title' => esc_html__('Content Top Spacing Mobile', 'togo'),
											'desc'  => esc_html__('Controls the top spacing of content page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.', 'togo'),
											'default' => '40px',
											'col'   => 12,
										),
										array(
											'id'    => 'content_bottom_spacing_mobile',
											'type'  => 'text',
											'title' => esc_html__('Content Bottom Spacing Mobile', 'togo'),
											'desc'  => esc_html__('Controls the bottom spacing of content page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.', 'togo'),
											'default' => '40px',
											'col'   => 12,
										),
										array(
											'id'      => 'content_overflow_hidden',
											'type'    => 'select',
											'title'   => esc_html__('Overflow Hidden', 'togo'),
											'default' => 'inherit',
											'options' => array(
												'hidden'     => esc_html__('Yes', 'togo'),
												'inherit' => esc_html__('No', 'togo'),
											),
											'col'   => 12,
										),
									)
								),
								array(
									'id'     => "post_header_tabs",
									'title'  => esc_html__('Header', 'togo'),
									'icon'   => 'dashicons-table-row-after',
									'fields' => array(
										array(
											'id'      => 'top_bar_type',
											'type'    => 'select',
											'title'   => esc_html__('Top Bar Type', 'togo'),
											'desc'    => wp_kses(
												sprintf(
													__('Select top bar that displays on this page. When you choose Default, the value in %s will be used.', 'togo'),
													'<a href="' . admin_url('/customize.php?autofocus[section]=header') . '">Customize</a>'
												),
												'togo-a'
											),
											'default' => '',
											'options' => \Togo\Theme::get_list_templates(true, 'togo_top_bar', null, true),
											'col'   => 12,
										),
										array(
											'id'    => 'header_type',
											'type'  => 'select',
											'title' => esc_attr__('Header Type', 'togo'),
											'desc'  => wp_kses(
												sprintf(
													__('Select header type that displays on this page. When you choose Default, the value in %s will be used.', 'togo'),
													'<a href="' . admin_url('/customize.php?autofocus[section]=header') . '">Customize</a>'
												),
												'togo-a'
											),
											'default' => '',
											'options' => \Togo\Theme::get_list_templates(true, 'togo_header'),
											'col'   => 12,
										),
										array(
											'id'      => 'header_overlay',
											'type'    => 'select',
											'title'   => esc_attr__('Header Overlay', 'togo'),
											'default' => '',
											'options' => array(
												''  => esc_html__('Default', 'togo'),
												'0' => esc_html__('No', 'togo'),
												'1' => esc_html__('Yes', 'togo'),
											),
											'col'   => 12,
										),
										array(
											'id'      => 'header_float',
											'type'    => 'select',
											'title'   => esc_attr__('Header Float', 'togo'),
											'default' => '',
											'options' => array(
												''  => esc_html__('Default', 'togo'),
												'0' => esc_html__('No', 'togo'),
												'1' => esc_html__('Yes', 'togo'),
											),
											'col'   => 12,
										),
									)
								),
								array(
									'id'     => "post_page_title_tab",
									'title'  => esc_html__('Page Title', 'togo'),
									'icon'   => 'dashicons-editor-textcolor',
									'fields' => array(
										array(
											'id'      => 'page_page_title_layout',
											'type'    => 'select',
											'title'   => esc_html__('Layout', 'togo'),
											'default' => '',
											'options' => Togo_Page_Title::instance()->get_list(true),
											'col'   => 12,
										),
									)
								),
								array(
									'id' => "post_sidebar_tab",
									'title' => esc_html__('Sidebar', 'togo'),
									'icon' => 'dashicons-align-pull-right',
									'fields' => array(
										array(
											'id'      => 'active_sidebar',
											'type'    => 'select',
											'title'   => esc_html__('Sidebar', 'togo'),
											'desc'    => esc_html__('Select sidebar that will display on this page.', 'togo'),
											'default' => 'default',
											'options' => $page_registered_sidebars,
											'col'   => 12,
										),
										array(
											'id'    => 'sidebar_position',
											'type'  => 'select',
											'title' => esc_html__('Sidebar Position', 'togo'),
											'desc'  => esc_html__('Select position of Sidebar for this page.', 'togo'),
											'default' => 'default',
											'options' => array(
												'left'    => esc_html__('Left', 'togo'),
												'right'   => esc_html__('Right', 'togo'),
												'default' => esc_html__('Default', 'togo'),
											),
											'col'   => 12,
										),
									)
								),
								array(
									'id' => "post_footer_tab",
									'title' => esc_html__('Footer', 'togo'),
									'icon' => 'dashicons-table-row-before',
									'fields' => array(
										array(
											'id'      => 'footer_enable',
											'type'    => 'select',
											'title'   => esc_html__('Footer Enable', 'togo'),
											'default' => 'yes',
											'options' => array(
												'yes'     => esc_html__('Yes', 'togo'),
												'no' => esc_html__('No', 'togo'),
											),
											'col'   => 12,
										),
										array(
											'id'    => 'footer_type',
											'type'  => 'select',
											'title' => esc_attr__('Footer Type', 'togo'),
											'desc'  => '',
											'default' => '',
											'options' => Togo\Theme::get_list_templates(true, 'togo_footer'),
											'col'   => 12,
										),
									)
								),
							)
						)
					),
					apply_filters('togo_post_meta_boxes_bottom', array())
				),
			));

			return apply_filters('togo_post_register_meta_boxes', $configs);
		}
	}

	Togo_Metabox::instance()->initialize();
}
