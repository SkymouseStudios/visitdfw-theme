<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * The template for displaying archive pages
 */
$us_layout = US_Layout::instance();
// Needed for canvas class
$us_layout->titlebar = ( us_get_option( 'titlebar_archive_content', 'all' ) == 'hide' ) ? 'none' : 'default' ;
get_header();

// Creating .l-titlebar
$titlebar_vars = array(
	'title' => 'City',
);

us_load_template( 'templates/custom_titlebar', $titlebar_vars);
if(isset($_GET['city_name']) && $_GET['city_name'] != ""){
	$city_name = $_GET['city_name'];
}else{
	$city_name = "";
}
if($city_name != ""){
?>
<div class="l-titlebar size_medium color_alternate">
	<div class="l-titlebar-h">
		<div class="l-titlebar-content">
			<h1 itemprop="headline">Destination: <span class="vcard"><?php echo $city_name; ?></span></h1>
		</div>		
	</div>
</div>
<?php
}
$template_vars = array(
	'layout_type' => us_get_option( 'archive_layout', 'smallcircle' ),
	'masonry' => us_get_option( 'archive_masonry', 0 ),
	'columns' => us_get_option( 'archive_cols', 1 ),
	'metas' => (array) us_get_option( 'archive_meta', array() ),
	'content_type' => us_get_option( 'archive_content_type', 'excerpt' ),
	'show_read_more' => in_array( 'read_more', us_get_option( 'archive_meta', array() ) ),
	'pagination' => us_get_option( 'archive_pagination', 'regular' ),
);
?>
<div class="l-main">
                <div class="l-main-h i-cf">
                    <main class="l-content" itemprop="mainContentOfPage">			
                        <div class="l-section sort_by_block city_block">
                            <div class="l-section-h i-cf">
                                <form class='post-filters'>
                                    <div class="sort_by">Sort By</div>
                                    <select name="orderby" class="order_by">
                                        <option value="">Select Any One</option>
                                        <?php
                                                $orderby_options = array(
                                                        'most_popular' => 'Popular',
														'most_recent' => 'Recent');												
                                                foreach( $orderby_options as $value => $label ) {
                                                        echo "<option ".selected($_GET['orderby'], $value )." value='$value'>$label</option>";
                                                }
                                        ?>
                                    </select>
									<select name="city_name" class="order_by" style="display:none">
										<option value="<?php echo $city_name; ?>"><?php echo $city_name; ?></option>
									</select>
                                    <input type='submit' value='Filter!' style="display:none">
                                </form>
                            </div>
                        </div>
					</main>
            </div>
        </div>
<!-- MAIN -->
<div class="l-main">
	<div class="l-main-h i-cf">

		<main class="l-content" itemprop="mainContentOfPage">
			<section class="l-section">
				<div class="l-section-h i-cf">

					<?php do_action( 'us_before_archive' ) ?>

					<?php us_load_template( 'templates/blog/listing-city', $template_vars ) ?>

					<?php do_action( 'us_after_archive' ) ?>

				</div>
			</section>
		</main>

<?php if ( $us_layout->sidebar_pos == 'left' OR $us_layout->sidebar_pos == 'right' ): ?>
		<aside class="l-sidebar at_<?php echo $us_layout->sidebar_pos ?>" itemscope="itemscope" itemtype="https://schema.org/WPSideBar">
			<?php dynamic_sidebar( 'default_sidebar' ) ?>
		</aside>
<?php endif; ?>

	</div>
</div>


<?php
get_footer();
