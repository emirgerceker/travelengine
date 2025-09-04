<div id="page-title" <?php Togo_Page_Title::instance()->the_wrapper_class(); ?>>
    <div class="container">
        <div class="page-title-inner">
            <?php Togo_Page_Title::instance()->render_title(); ?>
        </div>
    </div>
    <?php get_template_part('templates/global/breadcrumb'); ?>
</div>