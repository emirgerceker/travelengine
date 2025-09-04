<?php

namespace Togo_Framework\Elementor;

use Elementor\Widget_Base;

defined('ABSPATH') || exit;

abstract class Base extends Widget_Base
{

    /**
     * Get the icon part.
     *
     * Retrieve the icon part for the widget.
     *
     * @since 1.0.0
     * @access protected
     *
     * @return string Icon part.
     */
    protected function get_icon_part()
    {
        // The icon part is the class name for the icon.
        // This function should be overridden in the child class.
        //
        // Default value is 'eicon-elementor-square'.

        return 'eicon-elementor-square';
    }

    /**
     * Get the icon for the widget.
     *
     * Retrieve the CSS class for the widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string The CSS class for the widget icon.
     */
    public function get_icon()
    {
        // The icon part is the class name for the icon.
        // The 'togo-badge' class is added to all Togo Elementor widgets
        // to provide a consistent styling for their badges.
        //
        // The child class should override the get_icon_part() method
        // to return the specific class name for its icon.

        return 'togo-badge ' . $this->get_icon_part();
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the button widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @since  2.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['togo'];
    }

    /**
     * Print the attributes string.
     *
     * Prints the attributes string for the widget.
     *
     * @since 1.0.0
     * @access protected
     *
     * @param array $attr The attributes array.
     * @return void
     */
    protected function print_attributes_string($attr)
    {
        // Print the attributes string.
        // The get_render_attribute_string() method is used to generate the attributes string.
        // The attributes string is then printed using echo.

        echo '' . $this->get_render_attribute_string($attr);
    }

    /**
     * Print the pagination for the given query.
     *
     * @param WP_Query $query The query object.
     * @param array    $settings The widget settings.
     */
    protected function print_pagination($query, $settings)
    {
        // Get the number of posts to display per page from the settings or the default value.
        $number = !empty($settings['query_number']) ? $settings['query_number'] : get_option('posts_per_page');

        // Get the pagination type from the settings or use the default value.
        $pagination_type = !empty($settings['pagination_type']) ? $settings['pagination_type'] : 'pagination';

        // If there is only one page, do not print the pagination.
        if ($query->max_num_pages <= 1) {
            return;
        }

        // Set up paginated links.
        $links = paginate_links(array(
            'total'     => $query->max_num_pages,  // Total number of pages.
            'current'   => max(1, get_query_var('paged', 1)),  // Current page number.
            'prev_text' => __('<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'),
            'next_text' => __('<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>'),
            'type'      => 'list',  // Display as a list.
        ));

        // Set the pagination classes.
        $pagination_classes = array('togo-pagination', $pagination_type, 'center');

        // If the query has more posts than the number per page, print the pagination.
        if ($pagination_type !== '' && $query->found_posts > $number) {
?>
            <div class="<?php echo join(' ', $pagination_classes); ?>">
                <?php
                // Directly echo the links without applying wp_kses to the output.
                // This assumes that the output is trusted and does not contain any harmful code.
                echo $links;
                ?>
            </div><!-- .pagination -->
<?php
        }
    }

    /**
     * Retrieves all categories along with their names and IDs.
     *
     * @return array An associative array containing category IDs as keys and category names as values.
     */
    protected function get_all_categories()
    {
        // Get all categories
        $categories = get_categories(array(
            'orderby' => 'name', // Order categories by name in ascending order.
            'order' => 'ASC',
            'hide_empty' => false, // Set to true to hide categories without posts
        ));

        // Check if any categories were found
        if (empty($categories)) {
            return array(); // Return an empty array if no categories are found.
        }

        // Initialize an empty array to hold the category data
        $category_data = array();

        // Loop through each category and add their data to the array
        foreach ($categories as $category) {
            $category_data[$category->term_id] = $category->name; // Add the category ID as the key and the category name as the value.
        }

        return $category_data; // Return the array containing category IDs and names.
    }

    /**
     * Retrieves all tags along with their names and IDs.
     *
     * @return array An associative array containing tag IDs as keys and tag names as values.
     */
    protected function get_all_tags()
    {
        // Get all tags
        $tags = get_tags(array(
            'orderby' => 'name', // Order tags by name in ascending order.
            'order' => 'ASC',
            'hide_empty' => false, // Set to true to hide tags without posts
        ));

        // Check if any tags were found
        if (empty($tags)) {
            return array(); // Return an empty array if no tags are found.
        }

        // Initialize an empty array to hold the tag data
        $tag_data = array();

        // Loop through each tag and add their data to the array
        foreach ($tags as $tag) {
            $tag_data[$tag->term_id] = $tag->name; // Add the tag ID as the key and the tag name as the value.
        }

        return $tag_data; // Return the array containing tag IDs and names.
    }
}
