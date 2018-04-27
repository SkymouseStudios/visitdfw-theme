<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Index template (used for front page blog listing)
 */
$us_layout = US_Layout::instance();
get_header();

$template_vars = array(
	'layout_type' => us_get_option( 'blog_layout', 'classic' ),
	'masonry' => us_get_option( 'blog_masonry', 0 ),
	'columns' => us_get_option( 'blog_cols', 1 ),
	'metas' =>  us_get_option( 'blog_meta', array() ),
	'content_type' => us_get_option( 'blog_content_type', 'excerpt' ),
	'show_read_more' => in_array( 'read_more', us_get_option( 'blog_meta', array() ) ),
	'pagination' => us_get_option( 'blog_pagination', 'regular' ),
);

?>
<!-- MAIN -->
<div class="l-main latest_stories">
	<div class="l-main-h i-cf">
		<main class="l-content" itemprop="mainContentOfPage">
			<section class="l-section">
				<div class="l-section-h i-cf">
                                        <div class="g-cols wpb_row offset_small vc_inner cont-title ">
                                            <div class="vc_col-sm-12 wpb_column vc_column_container">
                                                <div class="vc_column-inner">
                                                    <div class="wpb_text_column ">
                                                        <div class="wpb_wrapper">
                                                            <h2 class="main-title">Latest <span class="">Stories</span></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
					<?php do_action( 'us_before_index' ) ?>

					<?php us_load_template( 'templates/blog/listing', $template_vars ) ?>

					<?php do_action( 'us_after_index' ) ?>

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
