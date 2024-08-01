<?php
$output = $image_alt = $image_src = $image = $title = '';

	extract( shortcode_atts( array(
		'post_type' => 'post',
		'offset' => 0,
		'num' => '4',
		'order' => 'DESC',
		'orderby' => 'date',
		'category' => '',
		'width' => 380,
		'height' => 250,
		'rating_hide' => '',
		'date_show' => '',
		'excerpt_show' => '',
		'rating_star_style' => ''
	), $atts ) );
		
	
	// Set up initial query for post
	$args = array(
		'post_type' => explode( ',', $post_type ),
		'posts_per_page' => $num,
		'order' => $order,
		'orderby' => $orderby,
		'offset' => $offset,
	);
	
	// If category
	if( $category != '' ) {
		$args['category_name'] = $category;
	}

	$query = new WP_Query( $args );
	
	if ( ! $query -> have_posts() )
		return false;
	
	while ( $query -> have_posts() ): $query -> the_post(); 
		global $post;
		
		// Post format	
		$post_format = ' post-format-'.get_post_format();
			if ( false === get_post_format() ) {
				$post_format = '';
		}

		if( has_post_thumbnail() ){
			$image_src = wp_get_attachment_url( get_post_thumbnail_id(), 'full' );
			$image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
			if( function_exists( 'ot_get_option' ) && ot_get_option('srcset_for_images') == 'on' ) {			
				$srcset = 'srcset="'. esc_url( aq_resize( $image_src, $width*2, $height*2, true, true, true ) ) .' '. esc_attr($width)*2 .'w, '. esc_url( aq_resize( $image_src, $width, $height, true, true, true ) ) .' '. esc_attr($width) .'w, '. esc_url( aq_resize( $image_src, $width/2, $height/2, true, true, true ) ) .' '. esc_attr($width)/2 .'w" sizes="(max-width: '. esc_attr( $width ) .'px) 100vw, '. esc_attr( $width ) .'px"';
				} else {
				$srcset = '';	
			}
		} elseif( function_exists( 'ot_get_option' ) && ot_get_option('default_post_image') ) {
			$image_src = wp_get_attachment_url( ot_get_option('default_post_image'), 'full' );  
			$image_alt = get_post_meta(ot_get_option('default_post_image'), '_wp_attachment_image_alt', true);
			$srcset = '';
		} else {
			$srcset = '';
		}
		
		if( $image_src != '' ){
			$image = '<img src="'. esc_url( aq_resize( $image_src, $width, $height, true, true, true ) ) .'" '.$srcset.' alt="'. esc_attr($image_alt) .'" height="'. esc_attr( $height ) .'" width="'. esc_attr( $width ) .'"/>';
		}

		if( $rating_hide != 'off' && get_post_meta( get_the_ID(), 'enable_review', true ) == 'on' && function_exists( 'mnky_review_sum' ) ){	
				if ( get_post_meta( get_the_ID(), 'review_breakdown', true ) == 'off' ) {
					$rating = '<div class="mp-rating-wrapper"><div class="mp-rating-stars"><span style="width:'. esc_attr( get_post_meta( get_the_ID(), 'review_overall_rating', true ) * 10 ) .'%"></span></div></div>';
				} else {
					$rating = '<div class="mp-rating-wrapper"><div class="mp-rating-stars"><span style="width:'. esc_attr(mnky_review_sum() * 10 ).'%"></span></div></div>';
				}
		} else {
			$rating = '';
		}
		
		if ( function_exists( 'mnky_post_time' )) {
		if( $date_show != 'on' ) {
			$date = '';
		} else {			
			$date = '<div class="mmp-date">'. esc_html(mnky_post_time()) .'</div>';
		}
		}		

		if( $excerpt_show != 'on' ) {
			$excerpt = '';
		} else {			
			$excerpt = '<span class="mmp-excerpt">'. esc_html(get_the_excerpt()) .'</span>';
		}	

		if( $rating_star_style != 'overlay' ) {
			$rating_style = '';
		} else {			
			$rating_style = ' rating-overlay';
		}			
		
		$title = get_the_title();
		
		$output .= '<li class="menu-post-container'. esc_attr($post_format) . esc_attr($rating_style) .'"><a href="'. esc_url( get_the_permalink() ) .'" rel="bookmark"><div class="mmp-img">'. $image .'</div><h6>'. esc_html( $title ) .'</h6></a>'. $rating . $date . $excerpt .'</li>';
		
	endwhile; 
	wp_reset_postdata();

	$output = '<ul class="mnky-menu-posts mmp-'. esc_attr( $num ) .'" >'. $output .'</ul>';

echo $output;