<?php
$output = $image_src = $image = $publisher = '';

	extract( shortcode_atts( array(
		'post_type' => 'post',
		'grid_layout' => 'mpg-layout-1',
		'grid_height' => 'mpg-height-420',
		'category_1' => '',
		'category_2' => '',
		'category_3' => '',
		'category_4' => '',
		'category_5' => '',
		'category_6' => '',
		'width' => 700,
		'height' => 450,
		'el_class' => '',
		'cat_hide' => '',
		'excerpt_hide' => '',
		'no_dublicate' => '',
		'allow_dublicate' => '',
		'order' => 'DESC',
		'orderby' => 'date',
		'css_animation' => '',
		'css_animation_delay' => ''
	), $atts ) );
	
	$el_class = $this->getExtraClass($el_class);
	$grid_layout = $this->getExtraClass($grid_layout);
	$grid_height = $this->getExtraClass($grid_height);
	
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'mnky-posts-grid clearfix'.$grid_layout.$grid_height.$el_class, $this->settings['base']);
	$css_class .= $this->getCSSAnimation($css_animation);
	($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';
	
	$categories = array($category_1, $category_2, $category_3); 

	if( $grid_layout == ' mpg-layout-1' || $grid_layout == ' mpg-layout-2' || $grid_layout == ' mpg-layout-3' || $grid_layout == ' mpg-layout-6' || $grid_layout == ' mpg-layout-7' ){
		$categories[] = $category_4;
	}		
	if( $grid_layout == ' mpg-layout-1' || $grid_layout == ' mpg-layout-3' ){
		$categories[] = $category_5;
	}	
	if( $grid_layout == ' mpg-layout-3' ){
		$categories[] = $category_6;
	}	


	// Store shown post IDs
	$mp_do_not_duplicate = array();
	global $mp_do_not_duplicate;
	
	$mpg_do_not_duplicate = array();
	$count = 0;	
	
	// If do not dublicate
	if( $no_dublicate == 'yes' && ! empty( $mp_do_not_duplicate ) ) {
		$mpg_do_not_duplicate = array_merge($mpg_do_not_duplicate, $mp_do_not_duplicate );
	}
	

	
    foreach ( $categories as $category ) {
		$count++;
		 
		// Set up initial query for post
		$args = array(
			'category_name' => $category,
			'post_type' => 'post',
			'posts_per_page' => '1',
			'order' => $order,
			'orderby' => $orderby,
			'post__not_in' => $mpg_do_not_duplicate
		);
		
		// If order by views
		if( $orderby == 'meta_value_num' ) {
		$args['meta_key'] = 'mnky_post_views_count';
		}	
		
		
		$query = new WP_Query( $args );
		
		if ( ! $query -> have_posts() )
			return false;
		
		while ( $query -> have_posts() ): $query -> the_post(); 
			
			$mpg_do_not_duplicate[] = get_the_ID();
	
			if( $allow_dublicate != 'yes' ) {
				$mp_do_not_duplicate[] = get_the_ID();
			}	
			
			$title = esc_html(get_the_title());
			
			if( has_post_thumbnail() ){
				$image_src = wp_get_attachment_url( get_post_thumbnail_id(), 'full' );
				$meta_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			} elseif( function_exists( 'ot_get_option' ) && ot_get_option('default_post_image') ) {
				$image_src = wp_get_attachment_url( ot_get_option('default_post_image'), 'full' );   
				$meta_image = wp_get_attachment_image_src( ot_get_option('default_post_image'), 'full' );				
			} else {
				$meta_image = null;
			}
			
			if( $image_src != '' ){
				$image =  aq_resize( $image_src, $width, $height, true, true, true );
				$image_meta = '<div class="hidden-meta" itemprop="image" itemscope itemtype="https://schema.org/ImageObject"><meta itemprop="url" content="'. esc_url($meta_image[0]) .'"><meta itemprop="width" content="'. esc_attr($meta_image[1]) .'"><meta itemprop="height" content="'. esc_attr($meta_image[2]) .'"></div>';
			} else {
			$image_meta = '';
			}
			
			
			if( $cat_hide == 'off' ) {
				$cat = '';
			} else {
				$cat = get_the_category_list( ', ' );
			}
			
			if($count == 1){
				if( $excerpt_hide == 'off' ) {
					$excerpt = '';
				} else {
					$excerpt = '<span itemprop="articleBody" class="mnky-post-excerpt">'. esc_html(get_the_excerpt()) .'</span>';
				}	
			} else {
				$excerpt = '';
			}
			
			$post_format = ' post-format-'.get_post_format();
			if ( false === get_post_format() ) {
				$post_format = '';
			}
			
			if(function_exists( 'ot_get_option' )){
				$publisher = '<div class="hidden-meta" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
				<div class="hidden-meta" itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="'. esc_attr(ot_get_option('logo')) .'">
				<meta itemprop="width" content="'. esc_attr(str_replace( "px", "", ot_get_option('retina_logo_width') )) .'">
				<meta itemprop="height" content="'. esc_attr(str_replace( "px", "", ot_get_option('retina_logo_height') )) .'">
				</div>
				<meta itemprop="name" content="'. esc_attr(get_bloginfo('name')) .'">
				</div>';
			} else {
				$publisher = '';
			}				

			
			$output .= '<div itemscope itemtype="http://schema.org/Article" class="mpg-item mpg-item-'.esc_attr($count).esc_attr($post_format).'" style="background-image:url('.esc_url($image).');"><a class="mpg-bg-url" href="'. esc_url(get_permalink()) .'"></a><div class="mpg-content"><span class="mpg-category">'. $cat .'</span><a itemprop="mainEntityOfPage" href="'. esc_url(get_permalink()) .'"  title="'. sprintf( esc_attr__( 'View %s', 'bitz' ), the_title_attribute( 'echo=0' ) ) .'" rel="bookmark"><h2 itemprop="headline" class="mpg-title">'. esc_html( $title ) .'</h2>'. $excerpt .'</a></div><a href="'. esc_url(get_permalink()) .'" class="mpg-icon"></a><time datetime="'. esc_attr(get_the_date( 'c' )) .'" itemprop="datePublished"></time><time class="meta-date-modified" datetime="'. esc_attr(get_the_modified_date( 'c' )) .'" itemprop="dateModified"></time>'. $image_meta . $publisher .'<div class="hidden-meta" itemprop="author" itemscope itemtype="http://schema.org/Person"><meta itemprop="name" content="'. esc_html(get_the_author()) .'"></div></div>';
			
		endwhile; 
		wp_reset_postdata();
	}
	$output = '<div class="'.esc_attr( trim( $css_class ) ).'" >'. $output .'</div>';

echo $output;