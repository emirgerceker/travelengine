<?php

namespace Togo_Framework\Elementor;

use Elementor\Controls_Manager;

/**
 * Modern Posts widget.
 *
 * A widget that displays a list of posts in a modern style.
 *
 * @package Togo_Elementor
 */
class Togo_Posts_Widget extends Base
{

    /**
     * Returns the widget name.
     *
     * @return string The widget name.
     */
    public function get_name()
    {
        return 'togo-posts';
    }

    /**
     * Returns the widget title.
     *
     * @return string The widget title.
     */
    public function get_title()
    {
        return __('Modern Posts', 'togo-framework');
    }

    /**
     * Returns the widget icon part.
     *
     * @return string The widget icon part.
     */
    public function get_icon_part()
    {
        return 'eicon-post-list';
    }

    /**
     * Registers the controls for the widget.
     *
     * This function creates a new section in the controls section with the label "Content" and tab "Content".
     * Inside this section, it adds a control for selecting the number of columns in the grid.
     *
     * @return void
     */
    protected function _register_controls()
    {
        $this->add_content_section();
        $this->add_content_style_section();
        $this->add_query_section();
    }

    /**
     * Adds a grid section to the controls section.
     *
     * This function creates a new section in the controls section with the label "Content" and tab "Content".
     * Inside this section, it adds a control for selecting the number of columns in the grid.
     *
     * @return void
     */
    protected function add_content_section()
    {
        $this->start_controls_section(
            'grid_section',
            [
                'label' => __('Content', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control('layout', [
            'label'       => esc_html__('Layout', 'togo-framework'),
            'type'        => Controls_Manager::SELECT,
            'options'     => [
                'list' => esc_html__('List', 'togo-framework'),
                'grid' => esc_html__('Grid', 'togo-framework'),
                'grid-2' => esc_html__('Grid 2', 'togo-framework'),
                'big-first' => esc_html__('Big First', 'togo-framework'),
            ],
            'default'     => 'list',
            'label_block' => true,
        ]);

        $this->add_responsive_control('grid_columns', [
            'label'       => esc_html__('Columns', 'togo-framework'),
            'type'        => Controls_Manager::SELECT,
            'options'     => [
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
            ],
            'default'     => '1',
            'label_block' => true,
            'condition'   => ['layout' => 'grid'],
        ]);

        $this->end_controls_section();
    }

    protected function add_content_style_section()
    {
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style', 'togo-framework'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control('hide_description', [
            'label'       => esc_html__('Hide Description', 'togo-framework'),
            'type'        => Controls_Manager::SWITCHER,
            'label_on'    => esc_html__('Show', 'togo-framework'),
            'label_off'   => esc_html__('Hide', 'togo-framework'),
            'default'     => 'no',
            'return_value' => 'yes',
            'selectors' => [
                '{{WRAPPER}} .post-excerpt' => '{{VALUE}}',
            ],
            'selectors_dictionary'    => [
                'yes' => 'display: none;',
                'no'  => 'display: block;',
            ],
        ]);

        $this->add_control('hide_read_more', [
            'label'       => esc_html__('Hide Read More', 'togo-framework'),
            'type'        => Controls_Manager::SWITCHER,
            'label_on'    => esc_html__('Show', 'togo-framework'),
            'label_off'   => esc_html__('Hide', 'togo-framework'),
            'default'     => 'no',
            'return_value' => 'yes',
            'selectors' => [
                '{{WRAPPER}} .btn-readmore' => '{{VALUE}}',
            ],
            'selectors_dictionary'    => [
                'yes' => 'display: none;',
                'no'  => 'display: block;',
            ],
        ]);

        $this->add_control('hide_pagination', [
            'label'       => esc_html__('Hide Pagination', 'togo-framework'),
            'type'        => Controls_Manager::SWITCHER,
            'label_on'    => esc_html__('Show', 'togo-framework'),
            'label_off'   => esc_html__('Hide', 'togo-framework'),
            'default'     => 'no',
            'return_value' => 'yes',
            'selectors' => [
                '{{WRAPPER}} .togo-pagination' => '{{VALUE}}',
            ],
            'selectors_dictionary'    => [
                'yes' => 'display: none;',
                'no'  => 'display: block;',
            ],
        ]);

        $this->add_control('pagination_align', [
            'label'       => esc_html__('Pagination Align', 'togo-framework'),
            'type'        => Controls_Manager::SELECT,
            'options'     => [
                'flex-start'  => esc_html__('Left', 'togo-framework'),
                'center' => esc_html__('Center', 'togo-framework'),
                'flex-end' => esc_html__('Right', 'togo-framework'),
            ],
            'default'     => 'flex-start',
            'label_block' => true,
            'selectors' => [
                '{{WRAPPER}} .togo-pagination' => 'justify-content: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();
    }

    /**
     * Adds a query section to the controls section.
     *
     * This function creates a new section in the controls section with the label "Query" and tab "Content".
     * Inside this section, it adds controls for selecting the number of posts to display, the categories and tags to display, and the order of the posts.
     *
     * @return void
     */
    protected function add_query_section()
    {
        $this->start_controls_section(
            'query_section',
            [
                'label' => __('Query', 'togo-framework'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control('posts_per_page', [
            'label'       => esc_html__('Posts Per Page', 'togo'),
            'description' => esc_html__('Enter the number of posts to display', 'togo-framework'),
            'type'        => Controls_Manager::NUMBER,
            'default'     => 8,
            'min'         => 1,
            'max'         => 20,
        ]);

        $this->add_control('categories', [
            'label'       => esc_html__('Categories', 'togo'),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $this->get_all_categories(),
            'label_block' => true,
            'multiple'    => true,
        ]);

        $this->add_control('tags', [
            'label'       => esc_html__('Tags', 'togo'),
            'type'        => Controls_Manager::SELECT2,
            'options'     => $this->get_all_tags(),
            'label_block' => true,
            'multiple'    => true,
        ]);

        $this->add_control('orderby', [
            'label'       => esc_html__('Order By', 'togo'),
            'type'        => Controls_Manager::SELECT,
            'options'     => [
                'date'    => esc_html__('Date', 'togo-framework'),
                'title'   => esc_html__('Title', 'togo-framework'),
                'rand'    => esc_html__('Random', 'togo-framework'),
                'comment' => esc_html__('Comment Count', 'togo-framework'),
            ],
            'default'     => 'date',
            'label_block' => true,
        ]);

        $this->add_control('order', [
            'label'       => esc_html__('Order', 'togo'),
            'type'        => Controls_Manager::SELECT,
            'options'     => [
                'ASC'  => esc_html__('ASC', 'togo-framework'),
                'DESC' => esc_html__('DESC', 'togo-framework'),
            ],
            'default'     => 'DESC',
            'label_block' => true,
        ]);

        $this->end_controls_section();
    }

    /**
     * Renders the widget content.
     *
     * This function renders the content of the widget based on the user's settings.
     *
     * @return void
     */
    protected function render()
    {
        $settings = $this->get_settings();
        $layout   = $settings['layout'];
        $classes = array('togo-posts-wrapper', 'layout-' . $layout);
        if ($layout == 'grid') {
            $grid_columns = 3;
            $grid_columns_tablet = 2;
            $grid_columns_mobile = 2;
            if (array_key_exists('grid_columns', $settings)) {
                $grid_columns = $settings['grid_columns'];
            }
            if (array_key_exists('grid_columns_tablet', $settings)) {
                $grid_columns_tablet = $settings['grid_columns_tablet'];
            }
            if (array_key_exists('grid_columns_mobile', $settings)) {
                $grid_columns_mobile = $settings['grid_columns_mobile'];
            }
            if (!empty($grid_columns)) {
                $classes[] = 'columns-' . $grid_columns;
            }
            if (!empty($grid_columns_tablet)) {
                $classes[] = 'columns-tablet-' . $grid_columns_tablet;
            }
            if (!empty($grid_columns_mobile)) {
                $classes[] = 'columns-mobile-' . $grid_columns_mobile;
            }
        }
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page'],
            'paged' => get_query_var('paged'),
            'ignore_sticky_posts' => true
        );

        if (!empty($settings['categories'])) {
            $args['category__in'] = $settings['categories'];
        }

        if (!empty($settings['tags'])) {
            $args['tag__in'] = $settings['tags'];
        }

        if (!empty($settings['orderby'])) {
            $args['orderby'] = $settings['orderby'];
        }

        if (!empty($settings['order'])) {
            $args['order'] = $settings['order'];
        }

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            echo '<div class="' . implode(' ', $classes) . '">';
            while ($query->have_posts()) {
                $query->the_post();
                get_template_part('templates/content', get_post_type());
            }
            wp_reset_postdata(); // Reset the global post object
            do_action('togo_content_blog_close_main_content');
            echo '</div>';
            $this->print_pagination($query, $settings);
        } else {
            get_template_part('templates/content', 'none');
        }
    }
}
