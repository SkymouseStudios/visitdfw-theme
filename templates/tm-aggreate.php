<?php
/**
 * Template Name: Aggreate
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

 <link href="/wp-content/themes/visitdfw/css/bootstrap.css" rel="stylesheet" type="text/css" />
 	<div class="container">
		<div class="row">
 <div id="slider">
	<img src="<?php the_field('top_image'); ?>" alt="image" /> 
			<div class="slogan">
				<div class="capbtm">
					<div class="captionMain">
						<div class="capbtmin">
							<h4><?php the_title(); ?> </h4>
							<a href="https://visitdfw.com/city/?city_name=<?php echo the_field('city_name'); ?>" class="mapi"><i class="fa fa-map-marker"></i><?php echo the_field('city_name'); ?></a>	
						</div>
					</div>
				</div>
			</div>
</div>
</div>
</div>
 <div id="story">
	<div class="container">
		<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="head">
			<p><?php the_content(); ?></p>
			</div>
		</div>
		<?php 	$args=array(
						'post_type' => 'post',
						'post_status' => 'publish',
						'order' => 'DESC', 
						'posts_per_page' => 3,
						'cat' => 31
						);
						query_posts( $args );

				while ( have_posts() ) : the_post();?>
			<div class="story1" style="width:100%;float:left;">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="sleft">
						<h5><?php the_title(); ?></h5>
						<p>	<?php// $content=get_the_content();
				   // echo wp_trim_words($content,100," ...");?>
				   <?php// $content = $post->post_content;?>
				   <?php  $desc = strip_tags(do_shortcode($post->post_content)); ?>
				   <?php  echo $desc = mb_strimwidth($desc, 0, 500, ‘…’); ?>
				<div class="w-blog"> 
				   <div class="learn_more">
						<a href="<?php echo get_permalink(); ?>">Learn More</a>
					</div>
				</div>
				   
				   </p>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="sright">
					<?php $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>
						<img src="<?php echo $feat_image; ?>" alt="img" />
						<div class="stxt">
							<h4><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h4>
						</div>
					</div>
				</div>
			</div>
			<?php endwhile; ?>
			 
			 		<?php //	$args=array(
						//'post_type' => 'post',
						//'post_status' => 'publish',
						//'order' => 'DESC', 
						//'posts_per_page' => 1,
						//'offset'=> 3,
						//'cat' => 31
						//);
						//query_posts( $args );

				//while ( have_posts() ) : the_post();?>
			<!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="moretxt">
				   <?php // $desc = strip_tags(do_shortcode($post->post_content)); ?>
				   <?php // echo $desc = mb_strimwidth($desc, 0, 800, ‘…’); ?>
				   <div class="w-blog">
						<div class="learn_more">
							<a href="<?php echo get_permalink(); ?>">Learn More</a>
						</div>
					</div>
				</div>
			</div>
			<?php// endwhile; ?>
			<div class="story1">
			<?php //	$args=array(
						/* 'post_type' => 'post',
						'post_status' => 'publish',
						'order' => 'DESC', 
						'posts_per_page' => 2,
						'offset'=> 4,
						'cat' => 31
						);
						query_posts( $args ); */

				//while ( have_posts() ) : the_post();?>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="sright">
					<?php $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>
						<img src="<?php echo $feat_image; ?>" alt="img" />
						<div class="stxt">
							<h4><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h4>
						</div>
 
					</div>
				</div>
			<?php// endwhile; ?>
 
			</div> -->
			 
		</div>  
	</div>
</div>
	 <style>
	 .sleft{margin:0!important;}
	/*  #story .sright img {min-height: 298px;} */
#slider {
    margin-top: 6%;
}
	 </style>
 
<?php get_footer(); ?>