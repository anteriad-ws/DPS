<?php
/*
Plugin Name: Custom TI Essentials
Plugin URI: #
Description: This plugin holds all the custom functionlities of TI Media Properties, Resource Type merged with main resources template
Author: John Paul O B
Version: 2.0
Author URI: https://facebook.com/ob.johnpaul
*/
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Cannot access!' );
}

define('text_Domain', 'custom_ti_essentials');

require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'podcast-postype.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'resources-settings.php';
require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'additional-sidebars.php';

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
	wp_enqueue_style('custom_ti_essentials_lmb', plugin_dir_url( __FILE__ ).'/load-more-button.css', array(), $version, false);
	
}
add_action( 'wp_enqueue_scripts', 'plugin_loaded' );

function show_ti_post_by_resourceType( $tag_name ){

    header("Content-Type: text/html");
	
	$args = array(
				'post_type' => 'resources',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'tax_query' => array(
					array (
						'taxonomy' => 'resource_types',
						'field' => 'slug',
						'terms' => $tag_name,
					)
				),
			);

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
			
			$out.='<div class="resource-card vc_col-lg-4 vc_col-xs-12 vc_col-md-4 vc_col-sm-4">
			
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
							$primary_sponser_name = get_post_meta(get_the_ID(), '_yoast_wpseo_primary_sponsored_by', true);
							if (!empty($primary_sponser_name)) {
								$term_name = get_term($primary_sponser_name)->name;
							   
								$out .= '<a style="float:right;" href="#" class="sponsored-by  td-post-category">' . esc_html($term_name) . '</a>';
							}

						// 	$sponsored_by = get_the_terms($post->ID, 'sponsored_by');
						// foreach ($sponsored_by as $sponsored_by_single) {
						// $out.='<a href="'.get_term_link($sponsored_by_single->term_id).'" class="sponsored-name">'. $sponsored_by_single->name.'</a>';
						// }
							$out.='</div>';
						
						}

						// client name

						

						$out.='<h3 class="entry-title td-module-title"><a href="'.get_the_permalink().'" rel="bookmark" title="'.the_title_attribute("echo=0").'">'.get_the_title().'</a></h3>';
						
						/*
						$out.='<div class="td-editor-date">

							<span class="td-author-date">
							
								<span class="td-post-date"><time class="entry-date updated td-module-date" datetime="'.esc_html(date(DATE_W3C, get_the_time('U'))).'">'.get_the_time(get_option('date_format')).'</time></span>
																	
							</span>

						</div>';
						*/
						
						$out.='<a class="readMore" href="'.get_the_permalink().'" rel="bookmark" title="'.the_title_attribute("echo=0").'">Download Now </a>';
						
					$out.='</div>

				</div>

			</div>';
			
			if( $i % 3 == 0 ){
				$out.='<div class=" visible-lg"></div>';
			}
			if( $i % 2 == 0 ){
				$out.='<div class=" visible-sm visible-md"></div>';
			}
			
		endwhile;
				
	}else{
		
		
		//$out.='<div class="vc_col-xs-12" style="padding-left: 20px;padding-right: 20px;padding-bottom: 35px;margin-bottom: 15px;">'.esc_attr( get_option('resource_page_noContent_text') ).'</div>';
		
	}
	
	wp_reset_postdata();
	
	echo $out;

}
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
			
			$out.='<div class="resource-card vc_col-lg-4 vc_col-xs-12 vc_col-md-4 vc_col-sm-6 ">
			
				<div class="td-module-container td-category-pos-above post-box">
				
					<div class="td-image-container123">
						<div class="td-module-thumb"><a href="'.get_the_permalink().'" rel="bookmark" class="td-image-wrap " title="'.the_title_attribute("echo=0").'"><img src="'.esc_url($post_thumbnail_url).'" alt="'.the_title_attribute("echo=0").'" class="img-responsive"  /></a></div>
					</div>

					<div class="td-module-meta-info123 content-box">';
						
						// resource type
						$categories = get_the_terms($loop->post->ID, "resource_types");
						
						if($categories){
						
							$out.='<div class="custom-tax-box">';
							foreach ($categories as $category) {
								
								$out.='<a href="'.get_term_link($category->term_id).'" class="resource-category  td-post-category">'.$category->name.'</a>';
								
							}
							$primary_sponser_name = get_post_meta(get_the_ID(), '_yoast_wpseo_primary_sponsored_by', true);
							if (!empty($primary_sponser_name)) {
								$term_name = get_term($primary_sponser_name)->name;
							   
								$out .= '<a style="float:right;" href="#" class="sponsored-by  td-post-category">' . esc_html($term_name) . '</a>';
							}
						// 	$sponsored_by = get_the_terms($post->ID, 'sponsored_by');
						// foreach ($sponsored_by as $sponsored_by_single) {
						// $out.='<a href="'.get_term_link($sponsored_by_single->term_id).'">'. $sponsored_by_single->name.'</a>';
						// }
							$out.='</div>';
						
						}
						
						// client name

						

						$out.='<h3 class="entry-title td-module-title"><a href="'.get_the_permalink().'" rel="bookmark" title="'.the_title_attribute("echo=0").'">'.get_the_title().'</a></h3>';
						
						/*
						$out.='<div class="td-editor-date">

							<span class="td-author-date">
							
								<span class="td-post-date"><time class="entry-date updated td-module-date" datetime="'.esc_html(date(DATE_W3C, get_the_time('U'))).'">'.get_the_time(get_option('date_format')).'</time></span>
																	
							</span>

						</div>';
						*/
						
						$out.='<a class="readMore" href="'.get_the_permalink().'" rel="bookmark" title="'.the_title_attribute("echo=0").'">Download Now</a>';
					$out.='</div>

				</div>

			</div>';
			
			if( $i % 3 == 0 ){
				$out.='<div class=" visible-lg visible-md"></div>';
			}
			if( $i % 2 == 0 ){
				$out.='<div class=" visible-sm"></div>';
			}
			
		endwhile;
				
	}else{
		
		
		//$out.='<div class="vc_col-xs-12" style="padding-left: 20px;padding-right: 20px;padding-bottom: 35px;margin-bottom: 15px;">'.esc_attr( get_option('resource_page_noContent_text') ).'</div>';
		
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
	
		<div class="col-lg-12 col-md-12 col-sm-12 col-12 resources-types">

			<span class="heading-category all">
				<a class="resource-type-link resource-type-all active-filter" data-type-id=""><span class="tdb-sibling-cat-bg"></span>All</a>
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
					<a class="resource-type-link" data-type-id="<?php echo $resource_type->term_id; ?>"><span class="tdb-sibling-cat-bg"></span><?php if($resource_type->slug == 'case-study')
                {
                $cate_name = 'Case Studies';
 
            }
           
            else
           
            {
                $cate_name = $resource_type->name ."s" ;
 
            }
            echo $cate_name; ?>
               
                </a> </a>
				</span>
			<?php } ?>

		</div>
		
		<div class="col-lg-4 col-md-4 col-sm-6 col-12 ">
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
	
	<div class="col-xs-12">
		<div class="post_loop_ row display-flex" data-post-type="<?php echo $atts['type']; ?>" data-post-per-page="<?php echo $atts['ppp'] ?>">
		
		<?php if(isset($_GET['rtype']) && !empty($_GET['rtype'])){ ?>
		<?php echo show_ti_post_by_resourceType( $_GET['rtype'] ); ?>
		<script>
			resourceType = 'yes';
			var rtype = "<?php echo $_GET['rtype']; ?>";
			jQuery( '.resource-type-'+rtype ).addClass('active-filter');
		</script>
		<?php }else{ ?>
		<script>
			resourceType = 'no';
		</script>
		<?php } ?>
		</div>
	</div>
	
	<div class="clearfix"></div>
		
	<div class="ti_loader"><img src="<?php echo plugin_dir_url( __FILE__ ) . 'loader-blue.gif' ?>"/></div>
	
	<div class="clearfix"></div>
	
	<div class="col-xs-12" id="extra-info"></div>
		
	<div id="latestloadMore">
		
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