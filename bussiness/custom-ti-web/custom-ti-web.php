<?php
/*
Plugin Name: Custom TI Essentials
Plugin URI: #
Description: This plugin holds all the custom functionlities of this website www.cxomatters.com
Author: Ram Kiran
Version: 1.0
Author URI: #
*/
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cannot access!' );
}

define('text_Domain', 'custom_ti_essentials');
// Include constants
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'resources-settings.php';

function plugin_loaded(){
	
	/*For Development*/
	$version = filemtime(__FILE__).rand(10,100);
	
	/*For Live*/
	//$version = '1.0';
	
	wp_register_script('jquery', ("https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"), false);
	wp_enqueue_script( 'jquery' );
	wp_register_script( "custom_ti_essentials_script", plugin_dir_url( __FILE__ ).'/script.js', array('jquery'), $version, false );
	wp_localize_script( 'custom_ti_essentials_script', 'myAjax', 
	array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 
		   'noResource_text' => esc_attr( get_option('resource_page_noContent_text') )
	)
	);	
	wp_enqueue_script( 'custom_ti_essentials_script' );	
	wp_enqueue_style('custom_ti_essentials_style', plugin_dir_url( __FILE__ ).'/style.css', array(), $version, false);
	wp_enqueue_style('custom_ti_essentials_extrastyles', plugin_dir_url( __FILE__ ).'/extra-styles.css', array(), $version, false);
	wp_enqueue_style('custom_ti_essentials_lmb', plugin_dir_url( __FILE__ ).'/load-more-button.css', array(), $version, false);
	
}
add_action( 'wp_enqueue_scripts', 'plugin_loaded' );

function show_ti_post_ajax(){

    $ppp = (isset($_POST["ppp"])) ? $_POST["ppp"] : 9;
    $page = (isset($_POST['pageNumber'])) ? $_POST['pageNumber'] : 1;
    $postType = (isset($_POST['postType'])) ? $_POST['postType'] : $postType;
    $cat_id = (isset($_POST['cat_id'])) ? $_POST['cat_id'] : '';
    $tag_id = (isset($_POST['tag_id'])) ? $_POST['tag_id'] : '';
    $taxonomy = (isset($_POST['taxonomy'])) ? $_POST['taxonomy'] : '';

    header("Content-Type: text/html");
	
	if(!empty($cat_id) && !empty($tag_id)){
		$args = array(
			'post_type' => $postType,
			'posts_per_page' => $ppp,
			'paged'    => $page,
			'post_status' => 'publish',
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'resource_types',
					'field'    => 'term_id',
					'terms'    => $tag_id
				),
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $cat_id
				),
			),
		);
		
	}else{
		
		if(!empty($tag_id)){
			$args = array(
				'post_type' => $postType,
				'posts_per_page' => $ppp,
				'paged'    => $page,
				'post_status' => 'publish',
				'tax_query' => array(
					array (
						'taxonomy' => 'resource_types',
						'field' => 'term_id',
						'terms' => $tag_id,
					)
				),
			);
			
		}
		elseif(!empty($cat_id)){
			$args = array(
				'post_type' => $postType,
				'posts_per_page' => $ppp,
				'paged'    => $page,
				'post_status' => 'publish',
				'tax_query' => array(
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $cat_id
					),
				),
			);
			
		}else{
			$args = array(
				'post_type' => $postType,
				'posts_per_page' => $ppp,
				'paged'    => $page,
				'post_status' => 'publish',
			);
		}
	
	}

    $loop = new WP_Query($args);

    $out = ''; $i = 0;

	if ($loop->have_posts()) {
		
		while ($loop->have_posts()) : $loop->the_post();
		
			$i++;
		
			$post_thumbnail_url = '';

			if (get_the_post_thumbnail_url(null, 'medium_large') != false) {
				
				$post_thumbnail_url = get_the_post_thumbnail_url(null, 'medium_large');
				
			} else {
				
				$post_thumbnail_url = get_template_directory_uri() . '/images/no-thumb/medium_large.png';
				
			}			
			
			$out.='<div class="bit-3 resource-card col-lg-4 col-md-4 col-sm-6 col-12" style="padding-bottom: 35px;margin-bottom: 15px;">
			
				<div class="td-module-container td-category-pos-above post-box">
				
					<div class="td-image-container123">
						<div class="td-module-thumb"><a href="'.get_the_permalink().'" rel="bookmark" class="td-image-wrap " title="'.the_title_attribute("echo=0").'"><img src="'.esc_url($post_thumbnail_url).'" alt="'.the_title_attribute("echo=0").'" class="img-responsive"  /></a></div>
					</div>

					<div class="td-module-meta-info123 content-box">';
						
						$categories = get_the_terms($loop->post->ID, "resource_types");
						
						if($categories){
						
							$out.='<div class="custom-tax-box">';
							foreach ($categories as $category) {
								
								$out.='<a href="'.get_term_link($category->term_id).'" class="resource-category  td-post-category">'.$category->name.'</a>';
								
							}
							$sponsored_by = get_the_terms($post->ID, 'sponsored_by');
						
						
						
							$out.='<span class="custom-tax-box">';
							foreach ($sponsored_by as $sponsored_by_single) { 
								
								$out.='<a style="float:right;" href="'.get_term_link($sponsored_by_single->term_id).'" class="sponsored-by  td-post-category">'.$sponsored_by_single->name.'</a>';
								
							}
							$out.='</span>';
							$out.='</div>';
						
						}

						$out.='<h6 class="entry-title td-module-title"><a href="'.get_the_permalink().'" rel="bookmark" title="'.the_title_attribute("echo=0").'">'.get_the_title().'</a></h6>';
						
						
						$out.='<div class="td-editor-date">

						<!--	<span class="td-author-date">
							
								<span class="td-post-date"><time class="entry-date updated td-module-date" datetime="'.esc_html(date(DATE_W3C, get_the_time('U'))).'">'.get_the_time(get_option('date_format')).'</time></span>
																	
							</span> -->

						</div>';
						
						
						$out.='<a class="readMore" href="'.get_the_permalink().'" rel="bookmark" title="'.the_title_attribute("echo=0").'">Download Now</a>';

						
					$out.='</div>



				</div>

			</div>';
			
			if( $i % 3 == 0 ){
				//$out.='<div class="clearfix vc_hidden-xs"></div>';
			}
			
		endwhile;
				
	}else{
		
		
		//$out.='<div class="wpb_column12 vc_column_container vc_col-xs-12" style="padding-left: 20px;padding-right: 20px;padding-bottom: 35px;margin-bottom: 15px;">'.esc_attr( get_option('resource_page_noContent_text') ).'</div>';
		
	}
	
	wp_reset_postdata();
	
	echo $out;

}

add_action('wp_ajax_nopriv_show_ti_post_ajax', 'show_ti_post_ajax');
add_action('wp_ajax_show_ti_post_ajax', 'show_ti_post_ajax');

function show_ti_posts_fn( $atts ){
	
	extract(shortcode_atts($default=array(
        'type' => 'post',
        'ppp' => get_option( 'posts_per_page' ),
        'show-tax' => 'no',
        'tax-name' => '',
    ), $atts));
	
	// print_r($atts);exit;
	// echo $atts['tax-name'];
	?>
	
	<?php
	if( $atts['show-tax'] == 'yes' ){
	?>
	<div class="heading_wrapper">
	
		<div class="bit-3 container resources-types">

			<span class="heading-category all">
				<a class="resource-type-link active-filter" data-type-id=""><span class="tdb-sibling-cat-bg"></span>All</a>
			</span>

			<!--  <ul class="td-category"> -->
			<?php
			$resource_types = get_terms([
				'taxonomy' => 'resource_types',
				'hide_empty' => true,
			]);
			foreach ($resource_types as $resource_type) {
				?>

				<span class="heading-category">
					| <a class="resource-type-link" data-type-id="<?php echo $resource_type->term_id; ?>"><span class="tdb-sibling-cat-bg"></span><?php if($resource_type->slug == 'case-study')
                {
                $cate_name = 'Case Studies';
 
            }
           
            else
           
            {
                $cate_name = $resource_type->name ."s" ;
 
            }
            echo $cate_name; ?>
               
                </a> 
				</span>
			<?php } ?>

		</div>
		
		<div class="bit-3 container">
			<div class="resource-type resources-filter">
				<select class="js-resource-type" data-tax-name="category">
				
					<option value="">All Categories</option>

					<?php
					$terms = get_terms([
						'taxonomy' => 'category',
						'hide_empty' => true,
					]);
					foreach ($terms as $category) {
						$hasposts = get_posts('post_type=resources&category='.$category->term_id);
						if(!empty($hasposts)) {
						?>

						<option value="<?php echo $category->term_id; ?>"><?php echo $category->name; ?></option>
					
						<?php }
					} ?>
					
				</select>
			</div>
		</div>
	</div>
	<?php } ?>

	<div class="clearfix"></div>
	
	<div class="post_loop_" data-post-type="<?php echo $atts['type']; ?>" data-post-per-page="<?php echo $atts['ppp'] ?>">
	
	<?php // echo show_ti_post_ajax( $atts['type'], $atts['ppp'], $atts['tax-name'] ); ?>
	
	</div>
	
	<div class="clearfix"></div>
		
	<div class="ti_loader"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'loader-blue.gif' ?>"/></div>
	
	<div class="clearfix"></div>
	
	
		
	<div id="latestloadMore" style="padding:0px">
		
		<!--<a href="#">Load More</a>-->
		
	</div>
	
	<?php
	
}

add_shortcode( 'show_ti_posts', 'show_ti_posts_fn' );


add_filter( 'body_class', function( $classes ) {
	if(is_user_logged_in()){
		if ( !isset( $classes['logged-in'] ) ) {
			$classes[] = 'logged-in';
		}
	}else{
		$classes[] = 'not-logged-in';
	}
	return $classes;
} );


function addLogoutLink_ajax(){
	$ret = array('url'=>"?_wpnonce=" . wp_create_nonce('user-logout'));
	echo json_encode($ret);
	exit;
}
add_action('wp_ajax_nopriv_addLogoutLink_ajax', 'addLogoutLink_ajax');
add_action('wp_ajax_addLogoutLink_ajax', 'addLogoutLink_ajax');

?>