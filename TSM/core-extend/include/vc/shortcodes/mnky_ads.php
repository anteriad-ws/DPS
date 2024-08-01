<?php
$output = $image = $html = $target = $width  = $label = $responsive_image = $responsive_class = '';

	extract( shortcode_atts( array(
		'posts_per_page' => '1',
		'category' => '',
		'id' => '',
		'rotate_ads' => '',
		'orderby' => 'post_date',
		'el_class' => '',
		'css_class' => '',
		'css_animation' => ''
	), $atts ) );
	
	$el_class = $this->getExtraClass($el_class);
	
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'site-commerc'.$el_class, $this->settings['base']);
	$css_class .= $this->getCSSAnimation($css_animation);
	($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';
		
	if( $category == '' ){
		$posts_per_page = '1';
	}	
		
	if( $category != '' && $rotate_ads == 'on' ){
		$orderby = 'rand';
	}	
	
	// Set up initial query for post
	$args = array(
		'post_type' => 'ads',
		'posts_per_page' => $posts_per_page,
		'orderby' => $orderby
	);
	
	// If Post IDs
	if( $id ) {
		$posts_in = explode( ',', $id );
		$args['post__in'] = $posts_in;
	}	
	
	// If Category
	if( $category && !$id ) {
		$tax_args = array(
			'tax_query' => array(
				array(
					'taxonomy' => 'ads_category',
					'field' => 'slug',
					'terms' => explode( ',', $category ),
				)
			)
		);
		$args = array_merge( $args, $tax_args );
	}
	
	
	$query = new WP_Query( $args );
	
	if ( ! $query -> have_posts() )
		return false;
	
	while ( $query -> have_posts() ): $query -> the_post(); 
		global $post;
		
		if( get_post_meta( get_the_ID(), 'ad_image_width', true ) ){
			$image_width = 'width='.get_post_meta( get_the_ID(), 'ad_image_width', true ) .'';
		} else {
			$image_width = '';
		}	
		
		if( get_post_meta( get_the_ID(), 'ad_image_height', true ) ){
			$image_height = 'height='.get_post_meta( get_the_ID(), 'ad_image_height', true ) .'';
		} else {
			$image_height = '';
		}		
				
		if( get_post_meta( get_the_ID(), 'ad_image', true ) ){
			$image = '<img class="commerc-img" '. esc_attr($image_width) .' '. esc_attr($image_height) .' src="'. esc_url( get_post_meta( get_the_ID(), 'ad_image', true ) ) .'" alt="'. esc_html( get_post_meta( get_the_ID(), 'ad_alt_text', true ) ) .'" itemprop="image">';
		} else {
			$image = '';
		}	
		
		if( get_post_meta( get_the_ID(), 'responsive_ad', true ) == 'on' ){
			
			if( get_post_meta( get_the_ID(), 'responsive_ad_image_width', true ) ){
				$responsive_image_width = get_post_meta( get_the_ID(), 'responsive_ad_image_width', true );
			} else {
				$responsive_image_width = '';
			}	
			
			if( get_post_meta( get_the_ID(), 'responsive_ad_image_height', true ) ){
				$responsive_image_height = get_post_meta( get_the_ID(), 'responsive_ad_image_height', true );
			} else {
				$responsive_image_height = '';
			}		

			if( get_post_meta( get_the_ID(), 'responsive_ad_image', true ) ){
				$responsive_image = '<img width='. esc_attr($responsive_image_width) .' height='. esc_attr($responsive_image_height) .' src="'. esc_url( get_post_meta( get_the_ID(), 'responsive_ad_image', true ) ) .'" alt="'. esc_html( get_post_meta( get_the_ID(), 'ad_alt_text', true ) ) .'" itemprop="image">';
			} else {
				$responsive_image = '';
			}
		}		


		if( get_post_meta( get_the_ID(), 'hide_responsive_ad', true ) == 'on' ){
			$hide_responsive_ad = ' hide-ad';
		} else {
			$hide_responsive_ad = '';
		}
	
		
		if( get_post_meta( get_the_ID(), 'ad_html', true ) ){
			$html = get_post_meta( get_the_ID(), 'ad_html', true );
		} else {
			$html = '';
		}		
		
		if( get_post_meta( get_the_ID(), 'ad_width', true ) ){
			$width = 'max-width:'. get_post_meta( get_the_ID(), 'ad_width', true ) .';';
		} else {
			$width = 'max-width:none;';
		}	
		
		if( get_post_meta( get_the_ID(), 'ad_height', true ) ){
			$height = 'max-height:'. get_post_meta( get_the_ID(), 'ad_height', true ).';';
		} else {
			$height = 'max-height:none;';
		}

		if( get_post_meta( get_the_ID(), 'ad_position', true ) ){
			$position = 'margin:'. get_post_meta( get_the_ID(), 'ad_position', true ).';';
		} else {
			$position = '';
		}	

		if( get_post_meta( get_the_ID(), 'ad_float', true ) ){
			$float = 'float:'. get_post_meta( get_the_ID(), 'ad_float', true ).';';
		} else {
			$float= '';
		}				
		
		if( get_post_meta( get_the_ID(), 'ad_note', true ) ){
			$label = '<div class="label">'. esc_html( get_post_meta( get_the_ID(), 'ad_note', true ) ) .'</div>';
		} else {
			$label = '';
		}
		
		if( get_post_meta( get_the_ID(), 'responsive_ad', true ) == 'on' ){
			if( get_post_meta( get_the_ID(), 'ad_url', true ) ){
				$target = 'target="'. esc_attr( get_post_meta( get_the_ID(), 'ad_url_target', true ) ) .'" ';
				$nofollow =  esc_html( get_post_meta( get_the_ID(), 'ad_url_rel', true ) );
				$output .= '<div class="commercial responsive-hide" style="'.  esc_attr($width) . esc_attr($height) .  esc_attr($position) . esc_attr($float) .'"><a href="'. esc_url( get_post_meta( get_the_ID(), 'ad_url', true ) ) .'" '. $target . $nofollow .' itemprop="url"> '. $image .'</a>'. $html . $label .'</div><div class="commercial responsive-show" style="width:'.  esc_attr($responsive_image_width) .'px;'.  esc_attr($position) . esc_attr($float) .'"><a href="'. esc_url( get_post_meta( get_the_ID(), 'ad_url', true ) ) .'" '. $target . $nofollow .' itemprop="url"> '. $responsive_image .'</a>'. $html . $label .'</div>';
			} else {
				$output .= '<div class="commercial responsive-hide" style="'.  esc_attr($width) . esc_attr($height) .  esc_attr($position) . esc_attr($float) .'">'. $image . $html . $label .'</div><div class="commercial responsive-show" style="width:'.  esc_attr($responsive_image_width) .'px;'. esc_attr($position) . esc_attr($float) .'">'. $responsive_image . $html . $label .'</div>';
			}
		} else {
			if( get_post_meta( get_the_ID(), 'ad_url', true ) ){
				$target = 'target="'. esc_attr( get_post_meta( get_the_ID(), 'ad_url_target', true ) ) .'" ';
				$nofollow =  esc_html( get_post_meta( get_the_ID(), 'ad_url_rel', true ) );
				$output .= '<div class="commercial" style="'.  esc_attr($width) . esc_attr($height) .  esc_attr($position) . esc_attr($float) .'"><a href="'. esc_url( get_post_meta( get_the_ID(), 'ad_url', true ) ) .'" '. $target . $nofollow .' itemprop="url"> '. $image.'</a>'. $html . $label .'</div>';
			} else {
				$output .= '<div class="commercial" style="'.  esc_attr($width) . esc_attr($height) .  esc_attr($position) . esc_attr($float) .'">'. $image . $html . $label .'</div>';
			}
		}
		
	endwhile; 
	wp_reset_postdata();
	$output = '<aside itemscope itemtype="https://schema.org/WPAdBlock" class="'.  esc_attr(trim( $css_class) ) .  esc_attr($hide_responsive_ad) .'" >'. $output .'</aside>';

echo $output;