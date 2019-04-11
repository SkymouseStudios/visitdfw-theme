<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * The template for displaying pages
 */
$us_layout = US_Layout::instance();
get_header();
us_load_template( 'templates/titlebar' );
?>
<!-- MAIN -->
<div class="l-main">
	<div class="l-main-h i-cf">

		<main class="l-content" itemprop="mainContentOfPage">

			<?php do_action( 'us_before_page' ) ?>

			<?php
			while ( have_posts() ){
				the_post();

				$the_content = apply_filters( 'the_content', get_the_content() );

				// The page may be paginated itself via <!--nextpage--> tags
				$pagination = us_wp_link_pages( array(
					'before' => '<div class="w-blog-pagination"><nav class="navigation pagination">',
					'after' => '</nav></div>',
					'next_or_number' => 'next_and_number',
					'nextpagelink' => '>',
					'previouspagelink' => '<',
					'link_before' => '<span>',
					'link_after' => '</span>',
					'echo' => 0,
				) );

				// If content has no sections, we'll create them manually
				$has_own_sections = ( strpos( $the_content, ' class="l-section' ) !== FALSE );
				if ( ! $has_own_sections ) {
					$the_content = '<section class="l-section"><div class="l-section-h i-cf">' . $the_content . $pagination . '</div></section>';
				} elseif ( ! empty( $pagination ) ) {
					$the_content .= '<section class="l-section"><div class="l-section-h i-cf">' . $pagination . '</div></section>';
				}

				echo $the_content;

				// Post comments
				if ( comments_open() OR get_comments_number() != '0' ) {
					// Hotfix for events calendar plugin
					if ( ! is_post_type_archive( 'tribe_events' ) ) {
						?>
						<section class="l-section for_comments">
						<div class="l-section-h i-cf"><?php
							wp_enqueue_script( 'comment-reply' );
							comments_template();
							?></div>
						</section><?php
					}
				}
			}
			?>

			<?php do_action( 'us_after_page' ) ?>

			<!-- City Agregate -->

			<section class="city-section">
				<h2 class="city-section-heading"><?php the_field('city_section_heading'); ?></h2>
				<p class="city-section-subhead"><?php the_field('city_section_subhead'); ?></p>

				<?php
				if( have_rows('city_repeater') ): ?>

				<div class="city-grid">
				
				<?php while( have_rows('city_repeater') ): the_row(); 
					$city_name = get_sub_field('city_name');
					$city_tagline = get_sub_field('city_tagline');
					$city_cta_text = get_sub_field('city_cta_text');
					$city_image = get_sub_field('city_image');
					$city_color = get_sub_field('city_color');
					
				 	?>

				 	<div class="city-section-card" style="background-image: url(<?php echo $city_image['url'] ?>)">
						<h3><?php echo $city_name; ?></h3>
						<h4><?php echo $city_tagline; ?></h4>
						<a style="background-color: <?php echo $city_color; ?>" href="/city/?city_name=<?php echo $city_name; ?>"><?php echo $city_cta_text;?></a>
					</div>

				<?php 
					endwhile; ?>
					</div>
					<?php endif; 
				?>

			</section>
		</main>

		<?php if ( $us_layout->sidebar_pos == 'left' OR $us_layout->sidebar_pos == 'right' ): ?>
			<aside class="l-sidebar at_<?php echo $us_layout->sidebar_pos . ' ' . us_dynamic_sidebar_id(); ?>" itemscope="itemscope" itemtype="https://schema.org/WPSideBar">
				<?php us_dynamic_sidebar(); ?>
			</aside>
		<?php endif; ?>

	</div>
</div>

<?php get_footer() ?>
