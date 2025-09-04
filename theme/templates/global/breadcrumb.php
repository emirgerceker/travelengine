<?php
// DON'T render breadcrumb if the current page is the front latest posts.
if (is_home() && is_front_page()) {
	return;
}
?>
<div id="page-breadcrumb" class="page-breadcrumb">
	<div class="page-breadcrumb-inner">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<?php echo Togo_Breadcrumb::breadcrumb(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
