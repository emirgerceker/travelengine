<?php

$priority = 9;
$section  = 'page_title';
$prefix   = 'page_title_';

// Page Title
\Togo\Kirki::add_section($section, array(
    'title'    => esc_html__('Page Title', 'togo'),
    'priority' => $priority++,
));

\Togo\Kirki::add_field('theme', [
    'type'     => 'select',
    'settings' => $prefix . 'layout',
    'label'    => esc_html__('Page Title Type', 'togo'),
    'section'  => $section,
    'priority' => $priority++,
    'default'  => $default[$prefix . 'type'],
    'choices'  => Togo_Page_Title::get_list(),
]);

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_home_title',
    'label'       => esc_attr__('Home Heading', 'togo'),
    'description' => esc_attr__('Enter text that displays on front latest posts page.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Blog', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_archive_category_title',
    'label'       => esc_attr__('Archive Category Heading', 'togo'),
    'description' => esc_attr__('Enter text prefix that displays on archive category page.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Category: ', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_archive_tag_title',
    'label'       => esc_attr__('Archive Tag Heading', 'togo'),
    'description' => esc_attr__('Enter text prefix that displays on archive tag page.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Tag: ', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_archive_author_title',
    'label'       => esc_attr__('Archive Author Heading', 'togo'),
    'description' => esc_attr__('Enter text prefix that displays on archive author page.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Author: ', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_archive_year_title',
    'label'       => esc_attr__('Archive Year Heading', 'togo'),
    'description' => esc_attr__('Enter text prefix that displays on archive year page.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Year: ', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_archive_month_title',
    'label'       => esc_attr__('Archive Month Heading', 'togo'),
    'description' => esc_attr__('Enter text prefix that displays on archive month page.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Month: ', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_archive_day_title',
    'label'       => esc_attr__('Archive Day Heading', 'togo'),
    'description' => esc_attr__('Enter text prefix that displays on archive day page.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Day: ', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_single_blog_title',
    'label'       => esc_attr__('Single Blog Heading', 'togo'),
    'description' => esc_attr__('Enter text that displays on single blog posts. Leave blank to use post title.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Blog', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_archive_portfolio_title',
    'label'       => esc_attr__('Archive Portfolio Heading', 'togo'),
    'description' => esc_attr__('Enter text that displays on archive portfolio pages.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Portfolios', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_single_portfolio_title',
    'label'       => esc_attr__('Single Portfolio Heading', 'togo'),
    'description' => esc_attr__('Enter text that displays on single portfolio pages. Leave blank to use portfolio title.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Portfolio', 'togo'),
));

\Togo\Kirki::add_field('theme', array(
    'type'        => 'text',
    'settings'    => 'page_title_single_product_title',
    'label'       => esc_attr__('Single Product Heading', 'togo'),
    'description' => esc_attr__('Enter text that displays on single product pages. Leave blank to use product title.', 'togo'),
    'section'     => $section,
    'priority'    => $priority++,
    'default'     => esc_attr__('Our Shop', 'togo'),
));
