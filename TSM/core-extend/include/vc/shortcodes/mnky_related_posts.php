<?php
$output = $categories = $tags = $image_alt = $image_src = $image = $author = $title = $publisher = '';

	extract( shortcode_atts( array(
		'num' => '4',
		'offset' => 0,
		'order' => 'DESC',
		'orderby' => 'date',
		'width' => 380,
		'height' => 250,
		'id' => '',
		'no_tags' => '',
		'no_categories' => ''
	), $atts ) );
		
		
	// Get id
	if( $id == '' ) {
		$id = get_the_ID();
	}	
	
	// Set up initial query for post
	$args = array(
		'post_type' => 'post',
		'posts_per_page' => $num,
		'order' => $order,
		'orderby' => $orderby,
		'offset' => $offset
	);
	
	// If order by views
	if( $orderby == 'meta_value_num' ) {
		$args['meta_key'] = 'mnky_post_views_count';
	}
	
	// Exclude current post
	$args['post__not_in'] = explode( ',', $id);
	
	// Get post categories
	$categories = wp_get_post_categories( $id );
	
	// Get post tags
	$tags = wp_get_post_tags( $id, array( 'fields' => 'ids' ) );
	
	// Clean user input
	$width = preg_replace('/\D/', '', $width);
	$height = preg_replace('/\D/', '', $height);

	$tax_args = array(
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms' => $categories,
				'operator' => 'IN'
			)
		)
	);
	
	if ( $no_categories == 'on' ) {
		$tax_args = array(
				'tax_query' => array(
					array(
						'taxonomy' => 'post_tag',
						'field' => 'term_id',
						'terms' => $tags,
						'operator' => 'IN'
					)
				)
			);
	}
	
	if ( ! empty( $tags ) && $no_tags != 'on' ) {
		$tax_args['tax_query']['relation'] = 'OR';
		$tax_2 = array(
			'taxonomy' => 'post_tag',
			'field' => 'term_id',
			'terms' => $tags,
			'operator' => 'IN'
		);
		array_push($tax_args['tax_query'], $tax_2);
	}

	$args = array_merge( $args, $tax_args );
	

	$query = new WP_Query( $args );
	
	if ( ! $query -> have_posts() )
		return false;
	
	while ( $query -> have_posts() ) : $query -> the_post(); 
		global $post;
		
		// Post format	
		$post_format = ' post-format-'.get_post_format();
			if ( false === get_post_format() ) {
				$post_format = '';
		}

		if( has_post_thumbnail() ){
			$image_src = wp_get_attachment_url( get_post_thumbnail_id(), 'full' );
			$meta_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			$image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);	
			if( function_exists( 'ot_get_option' ) && ot_get_option('srcset_for_images') == 'on' ) {			
				$srcset = 'srcset="'. esc_url( aq_resize( $image_src, $width*2, $height*2, true, true, true ) ) .' '. esc_attr($width)*2 .'w, '. esc_url( aq_resize( $image_src, $width, $height, true, true, true ) ) .' '. esc_attr($width) .'w, '. esc_url( aq_resize( $image_src, $width/2, $height/2, true, true, true ) ) .' '. esc_attr($width)/2 .'w" sizes="(max-width: '. esc_attr( $width ) .'px) 100vw, '. esc_attr( $width ) .'px"';
				} else {
				$srcset = '';	
			}
		} elseif( function_exists( 'ot_get_option' ) && ot_get_option('default_post_image') ) {
			$image_src = wp_get_attachment_url( ot_get_option('default_post_image'), 'full' );  
			$meta_image = wp_get_attachment_image_src( ot_get_option('default_post_image'), 'full' );
			$image_alt = get_post_meta(ot_get_option('default_post_image'), '_wp_attachment_image_alt', true);
			$srcset = '';
		} else {
			$image_src = '';
			$meta_image = null;
			$srcset = '';
		}		
		
		if( $image_src != '' ){		
			$image = '<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject"><img src="'. esc_url( aq_resize( $image_src, $width, $height, true, true, true ) ) .'" '.$srcset.' alt="'. esc_attr($image_alt) .'" height="'. esc_attr( $height ) .'" width="'. esc_attr( $width ) .'"/><meta itemprop="url" content="'. esc_url($meta_image[0]) .'"><meta itemprop="width" content="'. esc_attr($meta_image[1]) .'"><meta itemprop="height" content="'. esc_attr($meta_image[2]) .'"></div>';
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
		
		$author = '<div class="hidden-meta" itemprop="author" itemscope itemtype="http://schema.org/Person"><meta itemprop="name" content="'. esc_html(get_the_author()) .'"></div>';
				
		$title = get_the_title();
		
		$output .= '<li itemscope itemtype="http://schema.org/Article" class="related-post-container'. esc_attr( $post_format) .'"><a itemprop="mainEntityOfPage" href="'. esc_url( get_the_permalink() ) .'" rel="bookmark"><div class="mrp-img">'. $image .'</div><h6 itemprop="headline">'. esc_html( $title ) .'</h6></a><time datetime="'. esc_attr(get_the_date( 'c' )) .'" itemprop="datePublished"></time><time class="meta-date-modified" datetime="'. esc_attr(get_the_modified_date( 'c' )) .'" itemprop="dateModified"></time>'. $author . $publisher .'</li>';
		
	endwhile; 
	wp_reset_postdata();

	$output = '<ul class="mnky-related-posts mrp-'. esc_attr( $num ) .' clearfix" >'. $output .'</ul>';

echo $output;