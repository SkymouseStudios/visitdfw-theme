<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * The template for displaying archive pages
 */

global $us_blog_img_ratio;

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

// Editor's Pick Section

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

                <!-- Editors Pick Section -->
                    <?php 
                        $args = array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'posts_per_page' => 4,
                            'order' => 'ASC',
                            'tag' => 'editors-pick'
                            ); ?>
        
                    <?php $editors_pick = new WP_Query( $args ); 
                         
                    if ( $editors_pick->have_posts() ) { ?>
                        <h2>Editor's Picks</h2>
                        <p>Some of the best weekend events and things to do, hand-picked by the team at Visit DFW.</p>

                        <div class="w-blog layout_smallsquare cols_1">
                            <div class="w-blog-list">
                    <?php while ( $editors_pick->have_posts() ) : $editors_pick->the_post(); ?>


                    <article class="w-blog-post dfw-weekends" style="background-color: #f6f6dd; margin-bottom: 20px; border-left: solid 7px yellow">
                        <div class="w-blog-post-h">
                            <a href="<?php echo get_permalink(); ?>">
                                <div class="w-blog-post-preview">
                                    <div class="thumbnail-link__thumb" style="background-size: cover; background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(),'thumbnail'); ?>');"></div>
                                </div>
                            </a>
                            
                            <div class="w-blog-post-body">
                                <h2 class="w-blog-post-title">
                                <a class="entry-title" rel="bookmark" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                
                                <div class="w-blog-post-content">
                                    <?php 
                                    // Retreiving post format
                                    $the_content = apply_filters( 'the_excerpt', get_the_excerpt() );
                                    echo $the_content;

                                    ?>
                                 </div>
                                
                                <div class="learn_more"><a href="<?php echo get_permalink(); ?>">Learn More</a></div>
                            </div>
                        </div>
                    </article>
                            
                    <?php 
                        endwhile;  
                        wp_reset_postdata(); 
                        }

                    ?>
                    </div>
                    </div>

                <!-- End Editors pick -->

                    <h2>Other Events</h2>

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