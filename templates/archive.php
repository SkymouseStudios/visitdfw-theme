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
	'title' => get_the_archive_title(),
);
if ( is_category() OR is_tax() ) {
	$term = get_queried_object();
	if ( $term ) {
		$taxonomy = $term->taxonomy;
		$term = $term->term_id;
	}
    $description = get_term_field( 'description', $term, $taxonomy );
}
us_load_template( 'templates/custom_titlebar', $titlebar_vars);
if ( is_category() OR is_tax() ) {
    $term = get_queried_object();
    if ( $term ) {
        $taxonomy = $term->taxonomy;
        $term_id = $term->term_id;
        $query = new WP_Query( array(
            'cat' => $term_id,
            'post_type' => 'post',
            'meta_key' => '_is_ns_featured_post',
            'meta_value' => 'yes',
            'posts_per_page' => is_category( 'dfw-weekends' ) ? 1 : 3,
            'orderby' => 'date',
            'order'   => 'DESC' )
        );
        $posts = $query->get_posts(); ?>
        <div class="l-main">
            <div class="l-main-h i-cf">
                <main class="l-content" itemprop="mainContentOfPage">
                    <section class="l-section custom_block_featured">
                        <div class="l-section-h i-cf">
                            <div class="w-blog layout_smallsquare cols_1" itemscope="itemscope" itemtype="https://schema.org/Blog">
                                <div class="w-blog-list">
                                    <div class="featued_section">
                                        <?php if ( $description ) {
                                            echo '<div class="archive-description">' . $description . '</div>';
                                        } ?>
                                        <?php foreach ($posts as $key=> $value) {
                                            //pr($value); ?>
                                            <div class="featued_section_main <?php if($key == 0){ echo " full_section "; }else{ echo "half_section "; } ?>">
                                                <div class="featued_section_image">
                                                    <?php if ($key==0 ) {
                                                        $img_main= get_the_post_thumbnail_url($value->ID,"single_post");
                                                    } else {
                                                        $img_main = get_the_post_thumbnail_url($value->ID,"category_image2");
                                                    }
                                                    if(isset($img_main) && $img_main != "") {
                                                        $dis_img = $img_main;
                                                    } else {
                                                        if ($key == 0) {
                                                            $dis_img = get_stylesheet_directory_uri()."/img/not-found1200_400.jpg";
                                                        } else {
                                                            $dis_img = get_stylesheet_directory_uri()."/img/not-found590_355.jpg";
                                                        }
                                                    } ?>

                                                    <a style="background: url('<?php echo $dis_img; ?>'); background-size: cover;" href="<?php echo get_permalink($value->ID); ?>"><img class="fix-image" src="<?php echo $dis_img; ?>" style="visibility: hidden;" alt=""/></a>
                                                </div>
                                                <div class="featued_section_content">
                                                    <div class="featued_section_fe">FEATURED</div>
                                                    <div class="featued_section_title">
                                                        <a href="<?php echo get_permalink($value->ID); ?>">
                                                            <?php echo $value->post_title; ?></a>
                                                    </div>
                                                    <?php /* <div class="featued_section_date">
                                                    <?php echo date( "m/d/Y", strtotime($value->post_date)); ?></div> */ ?>
                                                    <?php /* <div class="featued_desc">
                                                    <?php if($key==0 ){ echo wp_trim_words($value->post_excerpt, 30, "..."); }else{ echo wp_trim_words($value->post_excerpt, 20, "..."); } ?></div> */ ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="l-section sort_by_block">
                        <div class="l-section-h i-cf">
                            <form class='post-filters'>
                                <div class="sort_by">Sort By</div>
                                <select name="orderby" class="order_by">
                                    <option value="">Select Any One</option>
                                    <?php $orderby_options = array(
                                        'most_popular'=> 'Popular',
                                        'most_recent' => 'Recent',
                                        'by_location' => 'By Location (A-Z)'
                                    );
                                    foreach( $orderby_options as $value => $label ) {
                                        echo "<option ".selected( $_GET['orderby'], $value )." value='$value'>$label</option>";
                                    } ?>
                                </select>
                                <input type='submit' value='Filter!' style="display:none">
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <?php }
} ?>

<?php $template_vars = array(
    'layout_type' => us_get_option( 'archive_layout', 'smallcircle' ),
    'masonry' => us_get_option( 'archive_masonry', 0 ),
    'columns' => us_get_option( 'archive_cols', 1 ),
    'metas' => (array) us_get_option( 'archive_meta', array() ),
    'content_type' => us_get_option( 'archive_content_type', 'excerpt' ),
    'show_read_more' => in_array( 'read_more', us_get_option( 'archive_meta', array() ) ),
    'pagination' => us_get_option( 'archive_pagination', 'regular' ), ); ?>
<!-- MAIN -->
<div class="l-main">
    <div class="l-main-h i-cf">
        <main class="l-content" itemprop="mainContentOfPage">
            <section class="l-section">
                <div class="l-section-h i-cf">

                    <?php do_action( 'us_before_archive' ) ?>

                    <?php us_load_template( 'templates/blog/listing', $template_vars ) ?>

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
<?php get_footer();