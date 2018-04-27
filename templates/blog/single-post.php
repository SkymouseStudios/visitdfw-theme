<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Outputs one single post.
 *
 * (!) Should be called after the current $wp_query is already defined
 *
 * @var $metas array Meta data that should be shown: array('date', 'author', 'categories', 'comments')
 * @var $show_tags boolean Should we show tags?
 *
 * @action Before the template: 'us_before_template:templates/blog/single-post'
 * @action After the template: 'us_after_template:templates/blog/single-post'
 * @filter Template variables: 'us_template_vars:templates/blog/single-post'
 */
?>

<?php
$us_layout = US_Layout::instance();

// Filling and filtering parameters
$default_metas = array( 'date', 'author', 'categories', 'comments' );
$metas = ( isset( $metas ) AND is_array( $metas ) ) ? array_intersect( $metas, $default_metas ) : $default_metas;
if ( ! isset( $show_tags ) ) {
	$show_tags = TRUE;
}

$post_format = get_post_format() ? get_post_format() : 'standard';

// Note: it should be filtered by 'the_content' before processing to output
$the_content = get_the_content();

$preview_type = usof_meta( 'us_post_preview_layout' );
if ( $preview_type == '' ) {
	$preview_type = us_get_option( 'post_preview_layout', 'basic' );
}

$preview_html = '';
$preview_bg = '';
if ( $preview_type != 'none' AND ! post_password_required() ) {
	$post_thumbnail_id = get_post_thumbnail_id();
	if ( $preview_type == 'basic' ) {
		if ( in_array( $post_format, array( 'video', 'gallery', 'audio' ) ) ) {
			$preview_html = us_get_post_preview( $the_content, TRUE );
			if ( $preview_html == '' AND $post_thumbnail_id ) {
				$image = wp_get_attachment_image_src( $post_thumbnail_id, 'single_post' );
				//$preview_html = wp_get_attachment_image( $post_thumbnail_id, 'large' );
				$preview_html = '<img class="fix-image" src="'.$image[0].'" alt="" >';
			}
		} else {
			if ( $post_thumbnail_id ) {
				$image = wp_get_attachment_image_src( $post_thumbnail_id, 'single_post' );
				//$preview_html = wp_get_attachment_image( $post_thumbnail_id, 'large' );
				$preview_html = '<img class="fix-image" src="'.$image[0].'" alt="" >';
			} else {
				// Retreiving preview HTML from the post content
				$preview_html = us_get_post_preview( $the_content, TRUE );
			}
		}
	} elseif ( $preview_type == 'modern' OR 'trendy' ) {
		if ( $post_thumbnail_id ) {
			$image = wp_get_attachment_image_src( $post_thumbnail_id, 'single_post' );
			$preview_bg = $image[0];
		} elseif ( $post_format == 'image' ) {
			// Retreiving image from post content to use it as preview background
			$preview_bg_html = us_get_post_preview( $the_content, TRUE );
			if ( preg_match( '~src=\"([^\"]+)\"~u', $preview_bg_html, $matches ) ) {
				$preview_bg = $matches[1];
			}
		}
	}
}

if ( ! post_password_required() ) {
	$the_content = apply_filters( 'the_content', $the_content );
}

// The post itself may be paginated via <!--nextpage--> tags
$pagination = us_wp_link_pages( array(
	'before' => '<div class="g-pagination"><nav class="navigation pagination">',
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
	$the_content = '<section class="l-section"><div class="l-section-h i-cf" itemprop="text">' . $the_content . $pagination . '</div></section>';
} elseif ( ! empty( $pagination ) ) {
	$the_content .= '<section class="l-section"><div class="l-section-h i-cf" itemprop="text">' . $pagination . '</div></section>';
}

// Meta => certain html in a proper order
$meta_html = array_fill_keys( $metas, '' );

// Preparing post metas separately because we might want to order them inside the .w-blog-post-meta in future
$meta_html['author'] = '<span class="w-blog-post-meta-author vcard author';
if ( ! in_array( 'author', $metas ) ) {
	$meta_html['author'] .= ' hidden';
}
$meta_html['author'] .= '">';
$meta_html['author'] .= '<div class="auther_img">'.get_avatar( get_the_author_meta( 'ID' ), 32 ).'</div><a href="' . get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) . '" class="fn">' . get_the_author() . '</a>';
$meta_html['author'] .= '</span>';
/*$meta_html['author'] .= '<time class="w-blog-post-meta-date date updated';
if ( ! in_array( 'date', $metas ) ) {
	// Hiding from users but not from search engines
	$meta_html['author'] .= ' hidden';
}
$meta_html['author'] .= '" itemprop="datePublished">' . get_the_date() . '</time>';*/

/*if ( in_array( 'categories', $metas ) ) {
	$meta_html['categories'] = get_the_category_list( ', ' );
	if ( ! empty( $meta_html['categories'] ) ) {
		$meta_html['categories'] = '<span class="w-blog-post-meta-category">' . $meta_html['categories'] . '</span>';
	}
}*/

$meta_html['comments'] .= '<span class="w-blog-post-meta-comments">';
$meta_html['comments'] .= '<fb:comments-count href="'.get_permalink($post->ID).'"></fb:comments-count> Comments';
$meta_html['comments'] .= '</span>';
//$meta_html['comments'] .= '<span class="share_count">15 Shares</span>';

/*$comments_number = get_comments_number();
if ( in_array( 'comments', $metas ) AND ! ( $comments_number == 0 AND ! comments_open() ) ) {
	$meta_html['comments'] .= '<span class="w-blog-post-meta-comments">';
	// TODO Replace with get_comments_popup_link() when https://core.trac.wordpress.org/ticket/17763 is resolved
	ob_start();
	$comments_label = sprintf( _n( '%s comment', '%s comments', $comments_number, 'us' ), $comments_number );
	comments_popup_link( us_translate_with_external_domain( 'No Comments' ), $comments_label, $comments_label );
	$meta_html['comments'] .= ob_get_clean();
	$meta_html['comments'] .= '</span>';
}*/

if ( us_get_option( 'post_nav' ) ) {
	$prevnext = us_get_post_prevnext();
}

if ( $show_tags ) {
	$the_tags = get_the_tag_list( '', ', ', '' );
}

$meta_html = apply_filters( 'us_single_post_meta_html', $meta_html, get_the_ID() );
?>
<?php
	$post_categories = get_the_category();
	$city = get_post_meta(get_the_ID(),'city',true);
?>
<?php/* if($preview_type == "modern"){ ?>
<div class="l-titlebar size_medium color_alternate single_custom_title <?php echo $preview_type; ?> <?php echo $post_categories[0]->slug; ?> ">
	<div class="l-titlebar-h">
		<div class="l-titlebar-content">
			<h1 class="w-blog-post-title entry-title" itemprop="headline">
				<?php the_title() ?>
				<div class="map_icon">
					<?php
						$cities = get_post_meta(get_the_ID(),'city',true);
						$cities_array = explode(", ",$cities);
						//pr($cities_array);
						foreach($cities_array as $key => $value){
							$genre_url = add_query_arg('city_name',$value, get_permalink( get_page_by_path( 'city' ) ));
						?>
							<a href="<?php echo $genre_url; ?>">
								<div class="city_name">
									<?php echo $value; ?>
								</div>
							</a>
						<?php
						}
					?>
				</div>
			</h1>
		</div>
	</div>
</div>
<?php } */?>
<?php
	/*$preview_type_class = "";
	if($preview_type == "modern"){
		$preview_type_class = "basic custom_modern";
	}else{
		$preview_type_class = $preview_type;
	}*/
?>
<?php
if($preview_type == "basic"){
?>
<div class="post_image custom_post_image">
	<?php if ( ! empty( $preview_bg ) ): ?>
			<div class="w-blog-post-preview <?php echo $post_categories[0]->slug; ?>" style="background-image: url(<?php echo $preview_bg ?>)"><img class="attachment-large size-large fix-image" src="<?php echo $preview_bg; ?>" alt=""/></div>
		<?php elseif ( ! empty( $preview_html ) OR $preview_type == 'modern' ): ?>
			<div class="w-blog-post-preview <?php echo $post_categories[0]->slug; ?>">
				<?php echo $preview_html ?>
			</div>
		<?php endif; ?>
</div>
<?php
}
if($preview_type == "modern"){
?>
<div class="post_image">
		<?php if ( ! empty( $preview_bg ) ): ?>
			<div class="w-blog-post-preview <?php echo $post_categories[0]->slug; ?>" style="background-image: url(<?php echo $preview_bg ?>)"><img class="attachment-large size-large fix-image" src="<?php echo $preview_bg; ?>" alt=""/>
                            <div class="img-bot">
                                    <div class="l-section-h">
                                        <div class="disp-tab">
                                            <div class="disp-cell">
                                                <h1  class="w-blog-post-title entry-title <?php echo $preview_type; ?>" itemprop="headline"><?php the_title() ?></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
		<?php elseif ( ! empty( $preview_html ) OR $preview_type == 'modern' ): ?>
			<div class="w-blog-post-preview <?php echo $post_categories[0]->slug; ?>">
				<?php echo $preview_html ?>
                                <div class="l-section-h">
                                        <div class="disp-tab">
                                            <div class="disp-cell">
                                                <h1  class="w-blog-post-title entry-title <?php echo $preview_type; ?>" itemprop="headline"><?php the_title() ?></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="img-bot">
                                    <div class="l-section-h">
                                        <div class="disp-tab">
                                            <div class="disp-cell">
                                                <h1  class="w-blog-post-title entry-title <?php echo $preview_type; ?>" itemprop="headline"><?php the_title() ?></h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
			</div>
		<?php endif; ?>
            <?php /*
            <h1 class="w-blog-post-title entry-title <?php echo $preview_type; ?>" itemprop="headline">
		<?php the_title() ?>
		<div class="map_icon">
			<?php
				$cities = get_post_meta(get_the_ID(),'city',true);
				$cities_array = explode(", ",$cities);
				//pr($cities_array);
				foreach($cities_array as $key => $value){
					$genre_url = add_query_arg('city_name',$value, get_permalink( get_page_by_path( 'city' ) ));
				?>
					<a href="<?php echo $genre_url; ?>">
						<div class="city_name">
							<?php echo $value; ?>
						</div>
					</a>
				<?php
				}
			?>
		</div>
	</h1> */ ?>
</div>
<?php } ?>
<article <?php post_class( 'l-section for_blogpost preview_' . $preview_type ) ?>>
	<div class="l-section-h i-cf">
		<?php if($preview_type != "modern" && $preview_type != "basic"){ ?>
		<?php if ( ! empty( $preview_bg ) ): ?>
			<div class="w-blog-post-preview <?php echo $post_categories[0]->slug; ?>" style="background-image: url(<?php echo $preview_bg ?>)"><img class="attachment-large size-large fix-image" src="<?php echo $preview_bg; ?>" alt=""/></div>
		<?php elseif ( ! empty( $preview_html ) OR $preview_type == 'modern' ): ?>
			<div class="w-blog-post-preview <?php echo $post_categories[0]->slug; ?>">
				<?php echo $preview_html ?>
			</div>
		<?php endif; ?>
		<?php } ?>
		<div class="w-blog">
                        <div class="post_breadcrumb">
                        <?php
                            if ( in_array( 'categories', $metas ) ) {
                                    $bread['categories'] = get_the_category_list( ', ' );
                                    if ( ! empty( $bread['categories'] ) ) {
                                            echo $bread['categories'].'<span>/</span>'.get_the_title();
                                    }
                            }
                        ?>
                        </div>
			<div class="w-blog-post-body desktop_view">
				<?php if($preview_type != "modern"){ ?>
				<h1 class="w-blog-post-title entry-title" itemprop="headline">
					<?php the_title() ?>
					<?php dfw_map_icon(); ?>
				</h1>
				<?php } ?>
            <div class="block_city_name">
				<div class="w-blog-post-meta<?php echo empty( $metas ) ? ' hidden' : '' ?>">
					<?php echo implode( '', $meta_html ) ?>
						<?php
						if ( us_get_option( 'post_sharing' ) ) : ?>
								<div class="for_sharing right_social">
												<?php
												$sharing_providers = (array) us_get_option( 'post_sharing_providers' );
												$us_sharing_atts = array(
														'type' => us_get_option( 'post_sharing_type', 'simple' ),
												);
												foreach ( array( 'email', 'facebook', 'twitter', 'linkedin', 'gplus', 'pinterest', 'vk' ) as $provider ) {
														$us_sharing_atts[ $provider ] = in_array( $provider, $sharing_providers );
												}
												us_load_template( 'shortcodes/us_sharing', array( 'atts' => $us_sharing_atts ) );
												?>
												<span class="share_text">Share</span>
								</div>
						<?php endif;?>
				</div>
			</div>
		</div>

		<?php if ( $preview_type == 'trendy' AND $us_layout->sidebar_pos == 'none' AND $us_layout->titlebar == 'none' ): ?>
			<script>
				(function( $ ){
					var $window = $(window),
						windowWidth = $window.width();

					$.fn.trendyPreviewParallax = function(){
						var $this = $(this),
							$postBody = $('.w-blog-post-body');

						function update(){
							if (windowWidth > 900){
								var scrollTop = $window.scrollTop(),
									thisPos = scrollTop*0.3,
									postBodyPos = scrollTop*0.4,
									postBodyOpacity = Math.max(0, 1-scrollTop/450);
								$this.css('transform', 'translateY('+thisPos+'px)');
								$postBody.css('transform', 'translateY('+postBodyPos+'px)');
								$postBody.css('opacity', postBodyOpacity);
							} else {
								$this.css('transform', '');
								$postBody.css('transform', '');
								$postBody.css('opacity', '');
							}
						}

						function resize(){
							windowWidth = $window.width();
							update();
						}

						$window.bind({scroll: update, load: resize, resize: resize});
						resize();
					};

					$('.l-section.for_blogpost.preview_trendy .w-blog-post-preview').trendyPreviewParallax();

				})(jQuery);
			</script>
		<?php endif; ?>
	</div>
</article>

<?php echo $the_content ?>
<?php
$event_time = get_post_meta(get_the_ID(),'time',true);
$map_address = get_post_meta(get_the_ID(),'address',true);
/*$latlong    =   get_lat_long($map_address);
$map        =   explode(',',$latlong);
$map_latitude = $map[0];
$map_longitude = $map[1];*/
$map_latitude = get_post_meta(get_the_ID(),'latitude',true);
$map_longitude = get_post_meta(get_the_ID(),'longitude',true);
$phone_number = get_post_meta(get_the_ID(),'phone_number',true);
$website = get_post_meta(get_the_ID(),'site_url',true);
$place_name = get_post_meta(get_the_ID(),'place_name',true);
$event_date = date("l, F j Y", strtotime(get_post_meta(get_the_ID(),'event_date',true)));
?>
<?php /*
<section id="subdetail" class="l-section blog-subdetail">
    <div class="l-section-h i-cf">
        <div class="post_map_address <?php echo $post_categories[0]->slug; ?>">
            <?php if(isset($event_time) && $event_time != "" && isset($event_date) && $event_date != ""){ ?>
                    <span class="event_time"><?php echo $event_date."   "; ?><?php echo $event_time; ?></span><span class="seprate">|</span>
			<?php } if(isset($map_address) && $map_address != ""){ ?>
                    <span class="event_address"><?php echo $place_name.', '.$map_address; ?></span><span class="seprate">|</span>
            <?php } ?>
            <span class="free">FREE</span><span class="seprate">|</span>
            <?php if(isset($website) && $website != ""){ ?>
            <span class="event_website"><a target="_blank" href="<?php echo $website; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a></span>
            <?php } ?>
        </div>
    </div>
</section> */ ?>
<div class="l-section ">
<div class="l-section-h i-cf">
    <div class="w-blog">
        <div class="w-blog-post-body mobile_view">
				<?php if($preview_type != "modern"){ ?>
				<h1 class="w-blog-post-title entry-title" itemprop="headline">
					<?php the_title() ?>
					<?php dfw_map_icon(); ?>
				</h1>
				<?php } ?>
            <div class="block_city_name">
				<div class="w-blog-post-meta<?php echo empty( $metas ) ? ' hidden' : '' ?>">
					<?php echo implode( '', $meta_html ) ?>
						<?php
						if ( us_get_option( 'post_sharing' ) ) : ?>
								<div class="for_sharing right_social">
												<?php
												$sharing_providers = (array) us_get_option( 'post_sharing_providers' );
												$us_sharing_atts = array(
														'type' => us_get_option( 'post_sharing_type', 'simple' ),
												);
												foreach ( array( 'email', 'facebook', 'twitter', 'linkedin', 'gplus', 'pinterest', 'vk' ) as $provider ) {
														$us_sharing_atts[ $provider ] = in_array( $provider, $sharing_providers );
												}
												us_load_template( 'shortcodes/us_sharing', array( 'atts' => $us_sharing_atts ) );
												?>
												<span class="share_text">Share</span>
								</div>
						<?php endif;?>
				</div>
			</div>
		</div>
    </div>
</div>
</div>
<?php
$show_map = get_post_meta(get_the_ID(),'show_map',true);
$show_map_address = get_post_meta(get_the_ID(),'show_map_address',true);
$show_map_main = get_option('show_map');
$show_map_address_main = get_option('show_map_address');
$show_flex_map_main = get_option('show_flex_map');
$show_flex_map = get_post_meta(get_the_ID(),'flex_map_id',true);

if($show_flex_map_main == 1 && $show_flex_map != "")
{
    ?>
    <div class="l-section map_detail_block wpb_row our_city_map single_flex_map">
        <div class="l-section-h i-cf">
            <div class="map_section post-map">
                <?php
                    if(isset($show_flex_map) && $show_flex_map != ""){
                        echo do_shortcode('[flexmap id="'.$show_flex_map.'"]');
                    }
                ?>
            </div>
        </div>
    </div>
    <?php
}
else
{?>
<div class="l-section map_detail_block">
    <div class="l-section-h i-cf">
        <div class="map_section">
            <?php
                if($show_map_main == 1)
                {
                    if(isset($show_map) && $show_map == 1)
                    {
            ?>
                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAVUJtZjwzqFedsRAU_5KFMpKy-x-oa4ik"></script>
                        <script>
                                jQuery( document ).ready(function() {
                            //function initMap() {
                              var uluru = {lat: <?php echo $map_latitude ?>, lng: <?php echo $map_longitude ?>};
                              var map = new google.maps.Map(document.getElementById('map'), {
                                zoom: 12,
                                center: uluru,
                                        scrollwheel: false,
                              });
                              var marker = new google.maps.Marker({
                                position: uluru,
                                map: map
                              });
                            //}
                                jQuery(".get_direction a").attr("href", "https://www.google.com/maps?saddr=&daddr=<?php echo str_replace("&","%26",$map_address); ?>");
                                });
                        </script>
                        <div class="map_section_left">
                                <div id="map"></div>
                        </div>
            <?php
                    }
            }
            if($show_map_address_main == 1)
            {
                    if(isset($show_map_address) && $show_map_address == 1)
                    {
            ?>
                    <div class="map_section_right">
                            <div class="place_name"><?php echo $place_name; ?></div>
                            <div class="map_add"><?php echo $map_address; ?></div>
                            <div class="map_phone"><?php echo $phone_number; ?></div>
                            <div class="map_website"><a target="_blank" href="<?php echo $website; ?>"><?php echo $website; ?></a></div>
                            <div class="map_time"><?php echo $event_time; ?></div>
                            <div class="get_direction"><a target="_blank">Get Directions</a></div>
                    </div>
            <?php
                    }
                }
            ?>
        </div>
    </div>
</div>
<?php
}
?>
<style>
      #map {
        width: 100%;
        height: 300px;
        background-color: grey;
      }
    </style>

    <?php if ( $preview_type == 'modern' ) {
        echo '<section class="l-section map_icons_bottom">';
            echo '<div class="l-section-h">';
                dfw_map_icon();
            echo '</div>';
        echo '</section>';
    } ?>

	<section class="l-section for_tags">
		<div class="l-section-h i-cf">
			<?php if ( $show_tags AND ! empty( $the_tags ) ): ?>
			<div class="g-tags">
				<span class="g-tags-title"><?php _e( 'Tags', 'us' ) ?>:</span>
				<?php echo $the_tags ?>
			</div>
			<?php endif; ?>
			<?php if ( us_get_option( 'post_sharing' ) ) : ?>
					<div class="for_sharing">
									<?php
									$sharing_providers = (array) us_get_option( 'post_sharing_providers' );
									$us_sharing_atts = array(
											'type' => us_get_option( 'post_sharing_type', 'simple' ),
									);
									foreach ( array( 'email', 'facebook', 'twitter', 'linkedin', 'gplus', 'pinterest', 'vk' ) as $provider ) {
											$us_sharing_atts[ $provider ] = in_array( $provider, $sharing_providers );
									}
									us_load_template( 'shortcodes/us_sharing', array( 'atts' => $us_sharing_atts ) );
									?>
									<span class="share_text">Share</span>
					</div>
			<?php endif; ?>
		</div>
	</section>
<?php
	$show_nearby_section = get_post_meta(get_the_ID(),'show_nearby_events',true);
	if(get_option('show_nearby_events') == 1){
		if(isset($show_nearby_section) && $show_nearby_section == 1){
?>
<div class="l-section near_wrap">
    <div class="l-section-h i-cf">
		<div class="nearby_event_section">
	<?php
global $wpdb;
$prefix = $wpdb->prefix;
$distance = 100;
$dist_query = "SELECT z.ID,
                        p.distance_unit
                                         * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                                         * COS(RADIANS(z.lat))
                                         * COS(RADIANS(p.longpoint - z.lng))
                                         + SIN(RADIANS(p.latpoint))
                                         * SIN(RADIANS(z.lat)))) AS distance
          FROM (
                        SELECT ".$prefix."posts.ID, a.meta_value as lat, b.meta_value as lng
FROM ".$prefix."posts
INNER JOIN ".$prefix."postmeta AS a ON ".$prefix."posts.ID = a.post_id
INNER JOIN ".$prefix."postmeta AS b ON ".$prefix."posts.ID = b.post_id
WHERE ".$prefix."posts.post_status = 'publish'
AND a.meta_key = 'latitude'
AND b.meta_key= 'longitude'
          ) AS z
          JOIN (
                        SELECT  ".$map_latitude."  AS latpoint,  ".$map_longitude." AS longpoint,
                                        ".$distance." AS radius,      111.045 AS distance_unit
                ) AS p ON 1=1

          WHERE z.lat
                 BETWEEN p.latpoint  - (p.radius / p.distance_unit)
                         AND p.latpoint  + (p.radius / p.distance_unit)
                AND z.lng
                 BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
                         AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))

                   order by distance";
	$data = $wpdb->get_results($dist_query);
	if(!empty($data))
	{
	?>
		<div class="main_title nearby_event">Nearby <span>Events</span></div>
		<div class="nearby_posts_section">
			<?php
			foreach($data as $key => $value)
			{
				$post_data = get_post($value->ID);
				$category_detail=get_the_category( $value->ID );
				if($key < 4)
				{
			?>
				<div class="nearby_posts_block <?php echo $category_detail[0]->slug; ?>">
					<div class="inner-block">
					<div class="nearby_posts_image">
						<?php
							$img_main = get_the_post_thumbnail_url($post_data->ID,"home_latest_stories");
							if(isset($img_main) && $img_main != "")
							{
								$dis_img = $img_main;
							}else{
								$dis_img = get_stylesheet_directory_uri()."/img/not-found400_300.jpg";
							}
						?>
						<a href="<?php echo get_permalink($post_data->ID); ?>"><img class="fix-image" src="<?php echo $dis_img ?>" alt=""/></a>
					</div>
					<div class="nearby_posts_content">
						<div class="title-wrap">
							<?php if (function_exists('z_taxonomy_image_url')){
								echo '<img src="'.z_taxonomy_image_url($category_detail[0]->term_id).'" alt="" />';
							} ?>
							<div class="title-text">
								<a href="<?php echo get_permalink($post_data->ID); ?>"><?php echo $post_data->post_title; ?></a>
								<span class="post-date"><?php echo date("m/d/Y", strtotime($post_data->post_date)); ?></span>
							</div>
						</div>
						<div class="nearby_event_content"><?php echo wp_trim_words($post_data->post_excerpt, 8, "..."); ?></div>
					</div>
					</div>
				</div>
			<?php
				}
			}
	}
?>
			</div>
		</div>
	</div>
</div>
<?php
		}
	}
?>
<?php
	$show_what_people_are_sharing_section = get_post_meta(get_the_ID(),'show_what_people_are_sharing',true);
	if(get_option('show_what_people_are_sharing') == 1){
		if(isset($show_what_people_are_sharing_section) && $show_what_people_are_sharing_section == 1){
?>
<div class="l-section near_wrap social_section">
    <div class="l-section-h i-cf">
		<div class="nearby_event_section">
			<?php
				$instagram_user_id = get_post_meta(get_the_ID(),'instagram_user_id',true);
				$instagram_hashtag = get_post_meta(get_the_ID(),'instagram_social_hashtags',true);

				$twitter_user_timeline = get_post_meta(get_the_ID(),'twitter_user_timeline',true);
				$twitter_hashtag = get_post_meta(get_the_ID(),'twitter_social_hasgtags',true);

				$facebook_page_id = get_post_meta(get_the_ID(),'facebook_page_id',true);
				$facebook_hashtag = get_post_meta(get_the_ID(),'facebook_soical_hashtags',true);
			?>
			<div class="main_title nearby_event">What People Are <span>Sharing</span></div>
			<div class="nearby_posts_section">
				<section class="l-section wpb_row height_medium social_feeds">
					<div class="l-section-h i-cf">
						<div class="g-cols offset_small">
							<?php
								if((isset($instagram_hashtag) && $instagram_hashtag != "") || (isset($instagram_user_id) && $instagram_user_id != ""))
								{
							?>
							<div class="vc_col-sm-4 wpb_column vc_column_container">
								<div class="vc_column-inner">
									<div class="wpb_text_column ">
										<div class="wpb_wrapper">
											<?php
												if($instagram_user_id != ""){
													$insta_id = 'id="'.$instagram_user_id.'"';
												}else{
													$insta_id = '';
												}
												if($instagram_user_id != "" && $instagram_hashtag != ""){
													$insta_hashtag = 'includewords="'.$instagram_hashtag.'"';
												}else if($instagram_hashtag != ""){
													$insta_hashtag = 'type=hashtag hashtag="'.$instagram_hashtag.'"';
												}else{
													$insta_hashtag = '';
												}
												echo do_shortcode('[instagram-feed '.$insta_id.' '.$insta_hashtag.' width=100% num=1 cols=1 showcaption=false showbio=false]');
											?>
										</div>
									</div>
								</div>
							</div>
							<?php
								}
								if((isset($twitter_hashtag) && $twitter_hashtag != "") || (isset($twitter_user_timeline) && $twitter_user_timeline != ""))
								{
							?>
							<div class="vc_col-sm-4 wpb_column vc_column_container">
								<div class="vc_column-inner">
									<div class="wpb_text_column ">
										<div class="wpb_wrapper">
											<?php
												if($twitter_user_timeline != ""){
													$twitter_screen = 'screenname="'.$twitter_user_timeline.'"';
												}else{
													$twitter_screen = '';
												}
												if($twitter_user_timeline != "" && $twitter_hashtag != ""){
													$twit_hashtag = 'includewords="'.$twitter_hashtag.'"';
												}elseif($twitter_hashtag != ""){
													$twit_hashtag = 'hashtag="'.$twitter_hashtag.'"';
												}else{
													$twit_hashtag = '';
												}
												echo do_shortcode('[custom-twitter-feeds '.$twitter_screen.' '.$twit_hashtag.' num=1 showfollow=false]');
											?>
										</div>
									</div>
								</div>
							</div>
							<?php
								}
								if((isset($facebook_hashtag) && $facebook_hashtag != "") || (isset($facebook_page_id) && $facebook_page_id != ""))
								{
							?>
							<div class="vc_col-sm-4 wpb_column vc_column_container facebook_feed">
								<div class="vc_column-inner">
									<div class="wpb_text_column ">
										<div class="wpb_wrapper">
											<?php
												if($facebook_page_id != ""){
													$face_id = 'id="'.$facebook_page_id.'"';
												}else{
													$face_id = '';
												}
												if($facebook_hashtag != ""){
													$face_hashtag = 'filter="'.$facebook_hashtag.'"';
												}else{
													$face_id = "";
												}
												echo do_shortcode('[custom-facebook-feed '.$face_id.' '.$face_hashtag.' pagetype=group num=1 layout=thumb]');
											?>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>
</div>
<?php
		}
	}
?>
<?php if ( us_get_option( 'post_author_box' ) ): ?>
	<?php us_load_template( 'templates/blog/single-post-author' ) ?>
<?php endif; ?>

<?php if ( us_get_option( 'post_nav' ) AND ! empty( $prevnext ) ): ?>
	<section class="l-section for_blognav footer_socail">
		<div class="l-section-h i-cf">
			<div class="w-blognav">
				<?php foreach ( $prevnext as $key => $item ): ?>
					<a class="w-blognav-<?php echo $key ?>" href="<?php echo $item['link'] ?>">
						<span class="w-blognav-meta"><?php echo $item['meta'] ?></span>
						<span class="w-blognav-title"><?php echo $item['title'] ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php if ( us_get_option( 'post_related', TRUE ) ): ?>
	<?php us_load_template( 'templates/blog/single-post-related' ) ?>
<?php endif; ?>
<?php
    $facebook_box_show = json_decode(stripslashes(get_option( 'wpdevart_comments_box_show_in' )), true);
    $meta_facebook = get_post_meta(get_the_ID());
    $facebook_status = $meta_facebook['_disabel_wpdevart_facebook_comment'][0];
    if($facebook_box_show['post'] == 1){
    if($facebook_status != "disable"){
?>
<div class="l-section fb_comment">
		<div class="l-section-h i-cf">
			<div class="footer_fb_comment">
				<?php
				echo do_shortcode('[wpdevart_facebook_comment curent_url="'.get_permalink().'" order_type="social" title_text="Leave A Comment" title_text_color="#000000" title_text_font_size="22" title_text_font_famely="monospace" title_text_position="left" width="100%" bg_color="#d4d4d4" animation_effect="random" count_of_comments="3" ]');
				?>
			</div>
		</div>
</div>
    <?php
    }
    }
    ?>
<?php /*if ( comments_open() OR get_comments_number() != '0' ): ?>
	<section class="l-section for_comments">
		<div class="l-section-h i-cf">
			<?php wp_enqueue_script( 'comment-reply' ) ?>
			<?php comments_template() ?>
		</div>
	</section>
<?php endif; */?>
