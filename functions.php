<?php
/* Custom functions code goes here. */

function nipl_categories_post_function( $atts ) {
	extract(shortcode_atts(array(
            'cat' => '',
         ), $atts));
			/*echo '<pre>';
			print_r($atts);
			echo '</pre>';*/
			//echo "<pre>";
			///print_r(array('orderby' => 'date', 'order' => 'DESC' , 'showposts' => 1,'cat' => $cat,'tag__in' => $tag));
         $return_string = '<div class="home_blog_list">';
         query_posts(array('orderby' => 'date', 'order' => 'DESC' , 'showposts' => 1,'cat' => $cat));
         if (have_posts()) :
            while (have_posts()) : the_post();
			$img_main = get_the_post_thumbnail_url(get_the_id(),"large");
			if(isset($img_main) && $img_main != "")
			{
				$dis_img = $img_main;
			}else{
				$dis_img = get_stylesheet_directory_uri()."/img/not-found.jpg";
			}
         $return_string .= '<a href="'.get_permalink().'"><div class="post-image"><img class="fix-image" src="'.$dis_img.'" alt=""/></div></a>';
               $return_string .= '<div class="post-bottom-cont">';
               $return_string .= '<div class="title-wrap">';
               if (function_exists('z_taxonomy_image_url')){
                   $return_string .= '<img src="'.z_taxonomy_image_url().'" alt="" />';
               }
               $return_string .= '<div class="title-text">';
               $return_string .= '<a href="'.get_permalink().'">'.get_the_title().'</a>';
               $return_string .= '<span class="post-date">'.date("m/d/Y",strtotime(get_the_date())).'</span>';
               $return_string .= '</div>';
               $return_string .= '</div>';
               $return_string .= '<div class="post-content">'.wp_trim_words(get_the_excerpt(), 8, "..." ).'</div>';
               $return_string .= '</div>';

            endwhile;
         endif;
         $return_string .= '</div>';

         wp_reset_query();
         return $return_string;
}
add_shortcode( 'nipl_categories_post', 'nipl_categories_post_function' );

function nipl_tag_post_function( $atts ) {
	extract(shortcode_atts(array(
            'tag' => '',
			'count' => 6,
			'size' => 'home_latest_stories'
         ), $atts));
		$i = 0;
         query_posts(array('orderby' => 'date', 'order' => 'DESC' , 'showposts' => $count,'tag' => $tag));
         if (have_posts()) :
            while (have_posts()) : the_post();
				$i++;
				if($i > 3){
                                        //$column_num = '6';
					$column_num = '4';
				}else{
					$column_num = '4';
				}
				$category_detail = get_the_category( get_the_ID() );
				//$return_string .= '<div class="g-cols wpb_row offset_small vc_inner">';
					$return_string .= '<div class="vc_col-sm-'.$column_num.' wpb_column vc_column_container '.$category_detail[0]->slug.'">';
						$return_string .= '<div class="vc_column-inner">';
							$return_string .= '<div class="wpb_text_column">';
								$return_string .= '<div class="wpb_wrapper">';
									$return_string .= '<div class="home_blog_list">';
										$img_main = get_the_post_thumbnail_url(get_the_id(),$size);
										if(isset($img_main) && $img_main != "")
										{
											$dis_img = $img_main;
										}else{
											$dis_img = get_stylesheet_directory_uri()."/img/not-found400_300.jpg";
										}
										$return_string .= '<a href="'.get_permalink().'"><div class="post-image"><img class="fix-image" src="'.$dis_img.'" alt=""/></div></a>';
										$return_string .= '<div class="post-bottom-cont">';
											$return_string .= '<div class="title-wrap">';

												if (function_exists('z_taxonomy_image_url')){
													$return_string .='<img src="'.z_taxonomy_image_url($category_detail[0]->term_id).'" alt="" />';
												}
												$return_string .= '<div class="title-text">';
													$return_string .= '<a href="'.get_permalink().'">'.get_the_title().'</a>';
													//$return_string .= '<span class="post-date">'.date("m/d/Y",strtotime(get_the_date())).'</span>';
												$return_string .= '</div>';
											$return_string .= '</div>';
											//$return_string .= '<div class="post-content">'.wp_trim_words(get_the_excerpt(), 8, "..." ).'</div>';
										$return_string .= '</div>';
									$return_string .= '</div>';
								$return_string .= '</div>';
							$return_string .= '</div>';
						$return_string .= '</div>';
					$return_string .= '</div>';
				//$return_string .= '</div>';
            endwhile;
         endif;
         wp_reset_query();
         return $return_string;
}
add_shortcode( 'nipl_tag_post', 'nipl_tag_post_function' );

function footer_bottom(){
    if ( is_active_sidebar( 'us_widget_area_footer_bottom' ) ) : ?>
		<?php dynamic_sidebar( 'us_widget_area_footer_bottom' ); ?>
<?php endif;
}
add_action('us_bottom_subfooter_start','footer_bottom');

function custom_title(){
    if ( is_category() ) {
		$title = sprintf( __( '%s' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s' ), get_the_date( _x( 'Y', 'yearly archives date format' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s' ), get_the_date( _x( 'F Y', 'monthly archives date format' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s' ), get_the_date( _x( 'F j, Y', 'daily archives date format' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives' );
	}
        return $title;
}
add_filter('get_the_archive_title','custom_title');


function wpb_set_post_views($postID) {
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
//To keep the count accurate, lets get rid of prefetching
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

function wpb_track_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;
    }
    wpb_set_post_views($post_id);
}
add_action( 'wp_head', 'wpb_track_post_views');

function wpb_get_post_views($postID){
    $count_key = 'wpb_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count;
}

function footer_fuction(){
	wp_deregister_script('emma js');
	$btn_color = get_option('home_page_read_more_btn_color');
	$btn_text_color = get_option('home_page_read_more_btn_text_color');
	$btn_border_color = get_option('home_page_read_more_btn_border_color');
	$btn_hover_color = get_option('home_page_read_more_btn_hover_color');
	$btn_hover_text_color = get_option('home_page_read_more_btn_hover_text_color');
	$btn_hover_border_color = get_option('home_page_read_more_btn_hover_border_color');
   ?>
<style>
	.home .btn_learn > a{background:<?php echo $btn_color; ?>;border:2px solid <?php echo $btn_border_color; ?>;color:<?php echo $btn_text_color; ?>;}
	.home .btn_learn > a:hover {
		background-color: <?php echo $btn_hover_color; ?> !important;
		border-color: <?php echo $btn_hover_border_color; ?> !important;
		color: <?php echo $btn_hover_text_color; ?> !important;
	}
</style>
<script type="text/javascript">
    jQuery(document).ready(function(){
        function explode(){
            jQuery(".our_city_map .owl-wrapper-outer .owl-wrapper").mCustomScrollbar();
        }
        setTimeout(explode,3000);
        jQuery( ".post-filters .order_by " ).on( "change", function() {
                jQuery(".post-filters input").trigger("click");
        });
		jQuery( window ).load(function(){
				/*jQuery(".fix-image").each(function(){

				var img = jQuery(this);
				var image_heightA = img.height();
				var image_widthA = img.width();
				var parent_image_widthA = img.parent().width();
				var parent_image_heightA = img.parent().height();
				//if(width <= height)

				if(image_heightA > parent_image_heightA && image_widthA > parent_image_widthA){

					img.css('width',parent_image_widthA + 'px');
					var tem_image_heightA = img.height();

					if(tem_image_heightA > parent_image_heightA){
						img.css('width','100%');
					}
					else{
						img.css('width','auto');
						img.css('height','100%');
					}
				}
				else{
					img.css('width',parent_image_widthA + 'px');
					var tem_image_heightA = img.height();

					if(tem_image_heightA > parent_image_heightA){
						img.css('width','100%');
					}
					else{
						img.css('width','auto');
						img.css('height','100%');
					}
				}
				});*/
			});
    });
	jQuery(document).ready(function($) {
	$('#emma-form input#emma-form-submit').click(function(e){
		//prevent the form from actually submitting and refreshing the page
		e.preventDefault();
		e.stopPropagation();

		var thisForm = $(e.target).closest('#emma-subscription-form');
		var thisWrap = $(e.target).closest('.emma-wrap');
		var thisFormUnique = thisForm.attr('data-form-unique');

		thisForm.addClass('activeForm');

		// If a status already exists, fade it out in sync with the form then remove it.
		if ($('.emma-status').length > 0) {
			$('.emma-status').fadeOut({
				duration:300,
				queue: false,
				complete: function(){
					$('.emma-status').remove();
				}
			});
		}

		// Fade out the form, show a little spinner thing
		thisForm.fadeOut({
			duration: 300,
			queue: false,
			complete: function(){
				// Show the WordPress default spinner
				$('<div class="spinner"></div>').prependTo(thisWrap).show();

				// Now let's submit the form via AJAX
				var data = {
					'action': 'emma_ajax_form_submit',
					'emma_email': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_email"]').val(),
					'emma_firstname': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_firstname"]').val(),
					'emma_lastname': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_lastname"]').val(),
					'emma_signup_form_id': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_signup_form_id"]').val(),
					'emma_send_confirmation': $('#emma-subscription-form[data-form-unique="' + thisFormUnique + '"] input[name="emma_send_confirmation"]').val(),
				};
				$.post("<?php echo admin_url( 'admin-ajax.php' )?>", data, function(response) {
					var errorClass = '';
					var hasError = false;

					response = $.parseJSON(response);

					// Check for errors
					if ( response.code > 800) {
						errorClass = 'emma-alert';
						hasError = true;
						response.tracking_pixel = '<p style="display:none !important;">Error occured. No tracking pixel placed.</p>';
					} else {
						errorClass = '';
					}

					// Display the status
					thisWrap.prepend('<div class="emma-status ' + errorClass + '" style="display:none;">' + response.status_txt + '</div>' + response.tracking_pixel);

					// Show/Hide stuff
					$('.spinner').delay(800).fadeOut(300, function(){
						$('.spinner').remove();
						$('.emma-status').fadeIn(300);

						// If we have an error, we need to show the form again
						if (hasError == true) {
							thisForm.fadeIn(300);
						}
					});

					setTimeout(function(){
						$('.emma-status').delay(800).fadeOut(300, function(){
							$('.emma-status').remove();
						});
					}, 3000);
				});
			}
		});
	});
});
</script>
   <?php
}

add_action("wp_footer","footer_fuction");

function pr($post){
	echo "<pre>";
	print_r($post);
	echo "</pre>";
}

function geo_lat_long($address) {
    $address1 = str_replace(" ","+",$address);
    $address1 = str_replace("&","%26",$address1);
    $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address1");
    $json = json_decode($json);
    $lat = $json->results[0]->geometry->location->lat;
    $long = $json->results[0]->geometry->location->lng;
    return array('lat' => $lat, 'long' => $long);
}

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function save_meta_value( $post_id, $post, $update ) {
    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $post_type = get_post_type($post_id);

    // If this isn't a 'book' post, don't update it.
    if ( "post" != $post_type ) return;
    // - Update the post's metadata.
	if(isset($_POST['fields']['field_582297d4662a7'])){
		$address = geo_lat_long($_POST['fields']['field_582297d4662a7']);
		if ( isset( $address['lat'] ) ) {
			update_post_meta( $post_id, 'latitude', sanitize_text_field( $address['lat'] ));
		}
		if ( isset( $address['long'] ) ) {
			update_post_meta( $post_id, 'longitude', sanitize_text_field( $address['long']));
		}
	}
}
add_action( 'save_post', 'save_meta_value', 99, 3 );

add_action( 'wp_ajax_nopriv_us_ajax_blog1', 'us_ajax_blog1' );
add_action( 'wp_ajax_us_ajax_blog1', 'us_ajax_blog1' );
function us_ajax_blog1() {
	WPBMap::addAllMappedShortcodes();
	// Filtering $template_vars, as is will be extracted to the template as local variables
	$template_vars = shortcode_atts( array(
		'query_args' => array(),
		'layout_type' => 'classic',
		'masonry' => FALSE,
		'title_size' => '',
		'metas' => array(),
		'columns' => 2,
		'content_type' => 'none',
		'show_read_more' => FALSE,
		'pagination' => 'regular',
		'el_class' => '',
	), us_maybe_get_post_json( 'template_vars' ) );
	// Filtering query_args
	if ( isset( $template_vars['query_args'] ) AND is_array( $template_vars['query_args'] ) ) {
		// Query Args keys, that won't be filtered
		$allowed_query_keys = array(
			// Blog listing shortcode requests
			'author_name',
			'us_portfolio_category',
			'category_name',
			// Archive requests
			'year',
			'monthnum',
			'day',
			'tag',
			// Search requests
			's',
			// Pagination
			'paged',
			'orderby',
			'posts_per_page',
			'post__not_in',
			// Custom users' queries
			'post_type',
			'nipl_filter'
		);
		$json_value = json_decode(stripslashes($_POST['template_vars']));

		$allowed_post_types = array( 'us_portfolio', 'post' );
		foreach ( $template_vars['query_args'] as $query_key => $query_val ) {
			if ( ! in_array( $query_key, $allowed_query_keys ) ) {
				unset( $template_vars['query_args'][ $query_key ] );
			}
		}
		if ( isset( $template_vars['query_args']['post_type'] ) ) {
			$is_allowed_post_type = FALSE;
			foreach ( $allowed_post_types as $allowed_post_type ) {
				if ( $template_vars['query_args']['post_type'] == $allowed_post_type OR ( is_array( $template_vars['query_args']['post_type'] ) AND count( $template_vars['query_args']['post_type'] ) == 1 AND $template_vars['query_args']['post_type'][0] == $allowed_post_type ) ) {
					$is_allowed_post_type = TRUE;
				}
			}
			if ( ! $is_allowed_post_type ) {
				unset( $template_vars['query_args']['post_type'] );
			}
		}
		if ( ! isset( $template_vars['query_args']['s'] ) AND ! isset( $template_vars['query_args']['post_type'] ) ) { // TODO: check if we ever set post_type in template_vars
			$template_vars['query_args']['post_type'] = 'post';
		}
		// Providing proper post statuses
		$template_vars['query_args']['post_status'] = array( 'publish' => 'publish' );
		$template_vars['query_args']['post_status'] += (array) get_post_stati( array( 'public' => TRUE ) );
		// Add private states if user is capable to view them
		if ( is_user_logged_in() AND current_user_can( 'read_private_posts' ) ) {
			$template_vars['query_args']['post_status'] += (array) get_post_stati( array( 'private' => TRUE ) );
		}
		if(isset($json_value->nipl_filter) && $json_value->nipl_filter != "")
		{
			if($json_value->nipl_filter == "most_popular")
			{
				$template_vars['query_args']['meta_key'] = "wpb_post_views_count";
				$template_vars['query_args']['order'] = "DESC";
			}
			else if($json_value->nipl_filter == "most_recent")
			{
				$template_vars['query_args']['orderby'] = "date";
				$template_vars['query_args']['order'] = "DESC";
			}
			else if($json_value->nipl_filter == "by_location")
			{
				$template_vars['query_args']['meta_key'] = 'city';
				$template_vars['query_args']['orderby'] = 'meta_value';
				$template_vars['query_args']['order'] = 'ASC';
			}
			else
			{

			}
		}
		else
		{

		}
		$template_vars['query_args']['post_status'] = array_values( $template_vars['query_args']['post_status'] );
	}
	// Passing values that were filtered due to post protocol
	us_load_template( 'templates/blog/listing', $template_vars );

	// We don't use JSON to reduce data size
	die;
}

function load_custom_wp_admin_style() {
    wp_enqueue_style( 'custom_bootstrap_toggle_css', get_stylesheet_directory_uri() . '/css/bootstrap-toggle.min.css');
    wp_enqueue_script( 'custom_bootstrap_js', get_stylesheet_directory_uri() . '/js/bootstrap.min.js' );
    wp_enqueue_script( 'custom_bootstrap_toggle_js', get_stylesheet_directory_uri() . '/js/bootstrap-toggle.min.js' );
    wp_enqueue_style( 'jqueryui_css', get_stylesheet_directory_uri() . '/lib/jQueryUI/jQueryUI.css');
    wp_enqueue_script( 'jqueryui_js', get_stylesheet_directory_uri() . '/lib/jQueryUI/jQueryUI.js' );
    wp_enqueue_style( 'evolColorpicker_css', get_stylesheet_directory_uri() . '/lib/evolColorpicker/evol.colorpicker.min.css');
    wp_enqueue_script( 'evolColorpicker_js', get_stylesheet_directory_uri() . '/lib/evolColorpicker/evol.colorpicker.min.js' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

add_action('admin_footer', 'nipl_footer_function');
function nipl_footer_function() { ?>
    <script>
            jQuery("#acf-field-show_nearby_events-1").attr("data-toggle","toggle");
            jQuery("#acf-field-show_what_people_are_sharing-1").attr("data-toggle","toggle");
            jQuery("#acf-field-show_map-1").attr("data-toggle","toggle");
            jQuery("#acf-field-show_map_address-1").attr("data-toggle","toggle");
            jQuery('#home_page_read_more_btn_color,#home_page_read_more_btn_text_color,#home_page_read_more_btn_hover_color,#home_page_read_more_btn_hover_text_color,#home_page_read_more_btn_border_color,#home_page_read_more_btn_hover_border_color').colorpicker();
    </script>
<?php
}

add_action('admin_menu', 'theme_option_menu');

function theme_option_menu() {

	add_theme_page( 'Theme Option', 'Theme Options', 'manage_options', 'theme-options','form_function_show');

	add_action( 'admin_init', 'register_my_theme_settings' );

}
function register_my_theme_settings() {
		//register our settings
		register_setting( 'theme_option', 'show_nearby_events' );
		register_setting( 'theme_option', 'show_what_people_are_sharing' );
		register_setting( 'theme_option', 'home_page_read_more_btn_color' );
		register_setting( 'theme_option', 'home_page_read_more_btn_text_color' );
		register_setting( 'theme_option', 'home_page_read_more_btn_border_color' );
		register_setting( 'theme_option', 'home_page_read_more_btn_hover_color' );
		register_setting( 'theme_option', 'home_page_read_more_btn_hover_text_color' );
		register_setting( 'theme_option', 'home_page_read_more_btn_hover_border_color' );
		register_setting( 'theme_option', 'show_map' );
		register_setting( 'theme_option', 'show_map_address' );
                register_setting( 'theme_option', 'show_flex_map' );
}
add_post_type_support('page','excerpt');
function form_function_show(){
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php echo __('Theme Options')?></h2>
			<form method="post" action="options.php" enctype="multipart/form-data">
				<?php settings_fields( 'theme_option' ); ?>
				<table class="form-table ms_theme">
					<tr valign="top">
						<th scope="row"><?php echo __('Show Map Section')?></th>
                            <td class="toggle_on_off_show_map"><input id="global_show_map" type="checkbox" name="show_map" value="1" <?php if(get_option('show_map') == 1){ echo "checked"; } ?>></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo __('Show Directions Map Address Section')?></th>
                            <td class="toggle_on_off_show_map_address"><input id="global_show_map_address" type="checkbox" name="show_map_address" value="1" <?php if(get_option('show_map_address') == 1){ echo "checked"; } ?>></td>
					</tr>
                                        <tr valign="top">
						<th scope="row"><?php echo __('Show Flex Map Section')?></th>
                            <td class="toggle_on_off_show_flex_map"><input id="global_show_flex_map" type="checkbox" name="show_flex_map" value="1" <?php if(get_option('show_flex_map') == 1){ echo "checked"; } ?>></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo __('Show Nearby Events')?></th>
                            <td class="toggle_on_off_show_nearby_events"><input id="global_show_nearby_events" type="checkbox" name="show_nearby_events" value="1" <?php if(get_option('show_nearby_events') == 1){ echo "checked"; } ?>></td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php echo __('Show What People Are Sharing')?></th>
                            <td class="toggle_on_off_show_what_people_are_sharing"><input id="global_show_what_people_are_sharing" type="checkbox" name="show_what_people_are_sharing" value="1" <?php if(get_option('show_what_people_are_sharing') == 1){ echo "checked"; } ?>></td>
					</tr>
				</table>
				<table class="form-table ms_theme">
					<tr valign="top">
						<th scope="row" style="width:400px"><?php echo __('Home Page Header Learn More Button Color')?></th>
                        <td class="home_page_read_more_btn_text_color color-field"><input id="home_page_read_more_btn_color" type="text" name="home_page_read_more_btn_color" value="<?php echo get_option('home_page_read_more_btn_color'); ?>"></td>
					</tr>
					<tr valign="top">
						<th scope="row" style="width:400px"><?php echo __('Home Page Header Learn More Button Text Color')?></th>
                            <td class="home_page_read_more_btn_text_color color-field"><input id="home_page_read_more_btn_text_color" type="text" name="home_page_read_more_btn_text_color" value="<?php echo get_option('home_page_read_more_btn_text_color'); ?>"></td>
					</tr>
					<tr valign="top">
						<th scope="row" style="width:400px"><?php echo __('Home Page Header Learn More Button Border Color')?></th>
                            <td class="home_page_read_more_btn_border_color color-field"><input id="home_page_read_more_btn_border_color" type="text" name="home_page_read_more_btn_border_color" value="<?php echo get_option('home_page_read_more_btn_border_color'); ?>"></td>
					</tr>
					<tr valign="top">
						<th scope="row" style="width:400px"><?php echo __('Home Page Header Learn More Button Hover Color')?></th>
                            <td class="home_page_read_more_btn_hover_color color-field"><input id="home_page_read_more_btn_hover_color" type="text" name="home_page_read_more_btn_hover_color" value="<?php echo get_option('home_page_read_more_btn_hover_color'); ?>"></td>
					</tr>
					<tr valign="top">
						<th scope="row" style="width:400px"><?php echo __('Home Page Header Learn More Button Hover Text Color')?></th>
                            <td class="home_page_read_more_btn_hover_text_color color-field"><input id="home_page_read_more_btn_hover_text_color" type="text" name="home_page_read_more_btn_hover_text_color" value="<?php echo get_option('home_page_read_more_btn_hover_text_color'); ?>"></td>
					</tr>
					<tr valign="top">
						<th scope="row" style="width:400px"><?php echo __('Home Page Header Learn More Button Hover Border Color')?></th>
                            <td class="home_page_read_more_btn_hover_border_color color-field"><input id="home_page_read_more_btn_hover_border_color" type="text" name="home_page_read_more_btn_hover_border_color" value="<?php echo get_option('home_page_read_more_btn_hover_border_color'); ?>"></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<style>
			.form-table.ms_theme td {padding:0;}
			.form-table.ms_theme .colorPicker{padding:3px 2px!important;}
		</style>
		<script>
			jQuery("#global_show_nearby_events").attr("data-toggle","toggle");
			jQuery("#global_show_what_people_are_sharing").attr("data-toggle","toggle");
			jQuery("#global_show_map").attr("data-toggle","toggle");
			jQuery("#global_show_map_address").attr("data-toggle","toggle");
                        jQuery("#global_show_flex_map").attr("data-toggle","toggle");
		</script>
<?php }

add_action( 'wp_ajax_nopriv_us_ajax_city', 'us_ajax_city' );
add_action( 'wp_ajax_us_ajax_city', 'us_ajax_city' );
function us_ajax_city() {
	WPBMap::addAllMappedShortcodes();
	// Filtering $template_vars, as is will be extracted to the template as local variables
	$template_vars = shortcode_atts( array(
		'query_args' => array(),
		'layout_type' => 'classic',
		'masonry' => FALSE,
		'title_size' => '',
		'metas' => array(),
		'columns' => 2,
		'content_type' => 'none',
		'show_read_more' => FALSE,
		'pagination' => 'regular',
		'el_class' => '',
	), us_maybe_get_post_json( 'template_vars' ) );
	// Filtering query_args
	if ( isset( $template_vars['query_args'] ) AND is_array( $template_vars['query_args'] ) ) {
		// Query Args keys, that won't be filtered
		$allowed_query_keys = array(
			// Blog listing shortcode requests
			'author_name',
			'us_portfolio_category',
			'category_name',
			// Archive requests
			'year',
			'monthnum',
			'day',
			'tag',
			// Search requests
			's',
			// Pagination
			'paged',
			'orderby',
			'posts_per_page',
			'post__not_in',
			// Custom users' queries
			'post_type',
			'nipl_city',
			'nipl_filter'
		);
		$json_value = json_decode(stripslashes($_POST['template_vars']));

		$allowed_post_types = array( 'us_portfolio', 'post' );
		foreach ( $template_vars['query_args'] as $query_key => $query_val ) {
			if ( ! in_array( $query_key, $allowed_query_keys ) ) {
				unset( $template_vars['query_args'][ $query_key ] );
			}
		}
		if ( isset( $template_vars['query_args']['post_type'] ) ) {
			$is_allowed_post_type = FALSE;
			foreach ( $allowed_post_types as $allowed_post_type ) {
				if ( $template_vars['query_args']['post_type'] == $allowed_post_type OR ( is_array( $template_vars['query_args']['post_type'] ) AND count( $template_vars['query_args']['post_type'] ) == 1 AND $template_vars['query_args']['post_type'][0] == $allowed_post_type ) ) {
					$is_allowed_post_type = TRUE;
				}
			}
			if ( ! $is_allowed_post_type ) {
				unset( $template_vars['query_args']['post_type'] );
			}
		}
		if ( ! isset( $template_vars['query_args']['s'] ) AND ! isset( $template_vars['query_args']['post_type'] ) ) { // TODO: check if we ever set post_type in template_vars
			$template_vars['query_args']['post_type'] = 'post';
		}
		// Providing proper post statuses
		$template_vars['query_args']['post_status'] = array( 'publish' => 'publish' );
		$template_vars['query_args']['post_status'] += (array) get_post_stati( array( 'public' => TRUE ) );
		// Add private states if user is capable to view them
		if ( is_user_logged_in() AND current_user_can( 'read_private_posts' ) ) {
			$template_vars['query_args']['post_status'] += (array) get_post_stati( array( 'private' => TRUE ) );
		}
		if(isset($json_value->nipl_filter) && $json_value->nipl_filter != "" && isset($json_value->nipl_city) && $json_value->nipl_city != "")
		{
			if($json_value->nipl_filter == "most_popular")
			{
				$template_vars['query_args']['meta_key'] = 'wpb_post_views_count';
				$template_vars['query_args']['orderby'] = 'meta_value_num';
				$template_vars['query_args']['order'] = 'DESC';
				$template_vars['query_args']['meta_query'] = array(
					'relation'    => 'AND',
					'meta_key' => array(
						'key'     => 'wpb_post_views_count',
						'type'    => 'NUMERIC',
					),
					'meta_key'    => array(
						'key'     => 'city',
						'value'   => $json_value->nipl_city,
						'compare' => 'LIKE',
					),
				);
			}
			else if($json_value->nipl_filter == "most_recent")
			{
				$template_vars['query_args']['meta_key'] = 'city';
				$template_vars['query_args']['orderby'] = "date";
				$template_vars['query_args']['order'] = "DESC";
				$template_vars['query_args']['meta_query'] = array(
					array(
						'key'     => 'city',
						'value'   => $json_value->nipl_city,
						'compare' => 'LIKE',
					),
				);
			}
			else
			{

			}
		}
		else if(isset($json_value->nipl_city) && $json_value->nipl_city != ""){
			$template_vars['query_args']['meta_key'] = 'city';
			$template_vars['query_args']['orderby'] = 'date';
			$template_vars['query_args']['order'] = 'DESC';
			$template_vars['query_args']['meta_query'] = array(
                            array(
                                    'key'     => 'city',
                                    'value'   => $json_value->nipl_city,
                                    'compare' => 'LIKE',
                            ),
                    );
		}
		else
		{

		}
		$template_vars['query_args']['post_status'] = array_values( $template_vars['query_args']['post_status'] );
	}
	// Passing values that were filtered due to post protocol
	us_load_template( 'templates/blog/listing-city', $template_vars );

	// We don't use JSON to reduce data size
	die;
}

add_image_size( 'category_image2', 620, 375);
add_image_size( 'single_post',1200, 400, true);
add_image_size( 'home_latest_stories',400, 300, true);
add_image_size( 'tag_posts_small',400, 200, true);
add_image_size( 'listing-thumb',225, 150, true);
add_image_size( 'listing-thumb-large', 675, 450, true);

/*function get_lat_long($address){
    $address = str_replace(" ","+",$address);
    $result_address = str_replace("&","%26",$address);
    $json = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=$result_address");
    $json = json_decode($json);
    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
    return $lat.','.$long;
}*/
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . "/style.css" ) );
    wp_enqueue_style( 'mCustomScrollbar_css', get_stylesheet_directory_uri() . '/css/jquery.mCustomScrollbar.css');
    wp_enqueue_script( 'mCustomScrollbar_js', get_stylesheet_directory_uri() . '/js/jquery.mCustomScrollbar.js' );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 20 );

function dfw_typekit() { ?>
    <script>
        (function() {
            var config = {
              kitId: 'xqs0aee'
            };
            var d = false;
            var tk = document.createElement('script');
            tk.src = '//use.typekit.net/' + config.kitId + '.js';
            tk.type = 'text/javascript';
            tk.async = 'true';
            tk.onload = tk.onreadystatechange = function() {
                var rs = this.readyState;
                if (d || rs && rs != 'complete' && rs != 'loaded') return;
                d = true;
                try { Typekit.load(config); } catch (e) {}
            };
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(tk, s);
        })();
    </script>
<?php }
add_action( 'wp_head', 'dfw_typekit' );

function dfw_map_icon() { ?>
    <div class="map_icon">
        <?php
        $cities = get_post_meta(get_the_ID(),'city',true);
        $cities_array = explode(", ", $cities);
        foreach($cities_array as $key => $value) {
            $genre_url = add_query_arg('city_name', $value, get_permalink( get_page_by_path( 'city' ) ));
            ?>
            <a href="<?php echo $genre_url; ?>">
                <div class="city_name">
                    <?php echo $value; ?>
                </div>
            </a>
        <?php } ?>
    </div>
<?php }
