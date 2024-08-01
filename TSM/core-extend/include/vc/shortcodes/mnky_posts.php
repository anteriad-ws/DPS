<?php
$output = $metadate = $image_src = $image_alt = $image = $image_defined = $image_bg = $image_meta = $breaking_placeholder = $publisher = '';

	extract( shortcode_atts( array(
		'post_type' => 'post',
		'offset' => 0,
		'layout' => '1',
		'posts_per_page' => '4',
		'order' => 'DESC',
		'orderby' => 'date',
		'author' => '',
		'category' => '',
		'tag' => '',
		'category_2' => '',
		'tag_2' => '',
		'taxonomy' => '',
		'tax_term' => '',
		'tax_operator' => 'IN',
		'tax_2' => '',
		'taxonomy_2' => '',
		'tax_term_2' => '',
		'tax_operator_2' => 'IN',
		'tax_relation' => 'OR',
		'time_limit' => '',
		'width' => 600,
		'height' => 320,
		'el_class' => '',
		'css_class' => '',
		'css_animation' => '',
		'css_animation_delay' => '',
		'cat_hide' => '',
		'date_hide' => '',
		'excerpt_hide' => '',
		'excerpt_show' => '',
		'views_hide' => '',
		'comments_hide' => '',
		'author_hide' => '',
		'post_on_column' => '',
		'no_dublicate' => '',
		'allow_dublicate' => '',
		'breaking_bg' => '#e74c3c',
		'breaking_color' => '#fff',
		'breaking_ph_color' => '#2b2b2b',
		'breaking_text' => '',
		'breaking_title' => '',
		'breaking_paddings' => '',
		'rating_hide' => '',
		'rating_color' => '#f1c40f',
		'pagination' => ''
	), $atts ) );
	
	$el_class = $this->getExtraClass($el_class);
	
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'mnky-posts clearfix mp-layout-'.$layout.$el_class, $this->settings['base']);
	$css_class .= $this->getCSSAnimation($css_animation);
	($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';
	
	// Clean user input
	$width = preg_replace('/\D/', '', $width);
	$height = preg_replace('/\D/', '', $height);
		
	// Set up initial query for post
	$args = array(
		'post_type' => explode( ',', $post_type ),
		'posts_per_page' => $posts_per_page,
		'order' => $order,
		'orderby' => $orderby
	);
	
	// Store shown post IDs
	$mp_do_not_duplicate = array();
	global $mp_do_not_duplicate;
	
	// Offset
	if( $offset != 0 && $offset != '') {
		$args['offset'] = $offset;
	}	
	
	// Pagination
	if( $pagination == 'on' ) {
		//Protect against arbitrary paged values
		if ( get_query_var('paged') ) { 
			$paged = get_query_var('paged'); 
		} else if ( get_query_var('page') ) {
			$paged = get_query_var('page'); 
		} else { 
			$paged = 1; 
		}
		$args['paged'] = $paged;
	}	
	
	// If do not dublicate
	if( $no_dublicate == 'yes' ) {
		$args['post__not_in'] = $mp_do_not_duplicate;
	}
	
	// If order by views
	if( $orderby == 'meta_value_num' ) {
		$args['meta_key'] = 'mnky_post_views_count';
	}	
	
	// If author selected
	if( $taxonomy == 'author' ) {
		if( $author != 'all' ) {
			$args['author__in'] = $author;
		}
	}	
	
	// If time limit
	if( $time_limit != '' ) {
		$date_args = array();
		
		if( $time_limit == 'today' ) {
			$today = getdate();
			$date_args = array(
				'date_query' => array(
					array(
						'year'  => $today['year'],
						'month' => $today['mon'],
						'day'   => $today['mday'],
					),
				)
			);
		} elseif( $time_limit == 'week' ) {
			$date_args = array(
				'date_query' => array(
					array(
						'year' => date( 'Y' ),
						'week' => date( 'W' ),
					)
				)
			);
		} elseif( $time_limit == 'month' ) {
			$date_args = array(
				'date_query' => array(
					array(
						'year' => date( 'Y' ),
						'month' => date( 'n' ),
					)
				)
			);
		}
		$args = array_merge( $args, $date_args );
	}
	
	// If taxonomy attributes, create a taxonomy query
	if ( ( $taxonomy != 'all_posts' &&  $taxonomy != 'author' ) && ( ! empty( $category ) || ! empty( $tag ) ) ) {
		
		// Term string to array
		if($taxonomy == 'category') {
			$tax_term = explode( ', ', $category );
		} else {
			$tax_term = explode( ', ', $tag );
		}
	
		$tax_args = array(
			'tax_query' => array(
				array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $tax_term,
					'operator' => $tax_operator
				)
			)
		);
		
		if( $taxonomy_2 != 'none' && ( ! empty( $category_2 ) || ! empty( $tag_2 ) ) ) {
			// Term string to array
			if($taxonomy_2 == 'category') {
				$tax_term_2 = explode( ', ', $category_2 );
			} else {
				$tax_term_2 = explode( ', ', $tag_2 );
			}
			
			$tax_args['tax_query']['relation'] = $tax_relation;
			$tax_2 = array(
				'taxonomy' => $taxonomy_2,
				'field' => 'slug',
				'terms' => $tax_term_2,
				'operator' => $tax_operator_2
			);
			array_push($tax_args['tax_query'], $tax_2);
		}
		
		$args = array_merge( $args, $tax_args );
	}
	
	
	$query = new WP_Query( $args );
	
	if ( ! $query -> have_posts() )
		return false;
	
	$count = 0;
	while ( $query -> have_posts() ): $query -> the_post(); 
		$count++;
		
		if( $allow_dublicate != 'yes' ) {
			$mp_do_not_duplicate[] = get_the_ID();
		}	

		if( $count == 1 ) {
			$article_order = 'main';
		} else {
			if( $post_on_column != 'on' ) {
				$article_order = ($count % 2 == 0) ? 'even mp-post-secondary' : 'odd mp-post-secondary';
			} else {
				$article_order = '';
			}	
		}	
		
		if( $breaking_paddings == 'yes' ) {
			$breaking_paddings = 'style="padding:0px"';
		}
		
		if( $breaking_text != '') {
			$breaking_placeholder = '<span style="color:'. esc_attr($breaking_ph_color) .'" class="breaking-placeholder">'. esc_html($breaking_text) .'</span>';
		}
		
		$breaking_title = '<h2 itemprop="headline" class="mp-title" '. $breaking_paddings .'>'.$breaking_placeholder.'<a itemprop="mainEntityOfPage" style="color:'. esc_attr($breaking_color) .'" href="'. esc_url(get_the_permalink()) .'" title="'. sprintf( esc_attr__( 'View %s', 'core-extend' ), the_title_attribute( 'echo=0' ) ) .'" rel="bookmark">'. esc_html(get_the_title()) .'</a></h2>';
		
		$title = '<h2 itemprop="headline" class="mp-title"><a itemprop="mainEntityOfPage" href="'. esc_url(get_the_permalink()) .'" title="'. sprintf( esc_attr__( 'View %s', 'core-extend' ), the_title_attribute( 'echo=0' ) ) .'" rel="bookmark">'. esc_html(get_the_title()) .'</a></h2>';
		
		if( has_post_thumbnail() ){
			$image_src = wp_get_attachment_url( get_post_thumbnail_id(), 'full' );
			$meta_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			$image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);	
			if( function_exists( 'ot_get_option' ) && ot_get_option('srcset_for_images') == 'on' ) {			
				$srcset = 'srcset="'. esc_url( aq_resize( $image_src, $width*2, $height*2, true, true, true ) ) .' '. esc_attr($width)*2 .'w, '. esc_url( aq_resize( $image_src, $width, $height, true, true, true ) ) .' '. esc_attr($width) .'w, '. esc_url( aq_resize( $image_src, $width/2, $height/2, true, true, true ) ) .' '. esc_attr($width)/2 .'w" sizes="(max-width: '. esc_attr( $width ) .'px) 100vw, '. esc_attr( $width ) .'px"';
				$srcset_defined = 'srcset="'. esc_url( aq_resize( $image_src, 480, 332, true, true, true ) ) .' 480w, '. esc_url( aq_resize( $image_src, 240, 166, true, true, true ) ) .' 240w" sizes="(max-width:240px) 100vw, 240px"';
				} else {
				$srcset = '';	
				$srcset_defined = '';
			}
		} elseif( function_exists( 'ot_get_option' ) && ot_get_option('default_post_image') ) {
			$image_src = wp_get_attachment_image_url( ot_get_option('default_post_image'), 'full' );  
			$meta_image = wp_get_attachment_image_src( ot_get_option('default_post_image'), 'full' );
			$image_alt = get_post_meta(ot_get_option('default_post_image'), '_wp_attachment_image_alt', true);
			$srcset = '';
			$srcset_defined = '';
		} else {
			$image_src = '';
			$meta_image = null;
			$srcset = '';
			$srcset_defined = '';
		}	
		
		
		if( $image_src != '' ){
			
			$image = '<a href="'. esc_url(get_the_permalink()) .'" class="mp-image" rel="bookmark"><div itemprop="image" itemscope itemtype="https://schema.org/ImageObject"><img src="'. esc_url(aq_resize( $image_src, $width, $height, true, true, true )) .'" '.$srcset.' alt="'. esc_attr($image_alt) .'" height="'. esc_attr($height) .'" width="'. esc_attr($width) .'"/><meta itemprop="url" content="'. esc_url($meta_image[0]) .'"><meta itemprop="width" content="'. esc_attr($meta_image[1]) .'"><meta itemprop="height" content="'. esc_attr($meta_image[2]) .'"></div></a>';
			
			$image_defined = '<a href="'. esc_url(get_the_permalink()) .'" class="mp-image" rel="bookmark"><div itemprop="image" itemscope itemtype="https://schema.org/ImageObject"><img src="'. esc_url(aq_resize( $image_src, '240', '166', true, true, true )) .'" '.$srcset_defined.' alt="'. esc_attr($image_alt) .'" height="166" width="240"/><meta itemprop="url" content="'. esc_url($meta_image[0]) .'"><meta itemprop="width" content="'. esc_attr($meta_image[1]) .'"><meta itemprop="height" content="'. esc_attr($meta_image[2]) .'"></div></a>';
		
			$image_bg = esc_url( aq_resize( $image_src, $width, $height, true, true, true ) );
			
			$image_meta = '<div class="hidden-meta" itemprop="image" itemscope itemtype="https://schema.org/ImageObject"><meta itemprop="url" content="'. esc_url($meta_image[0]) .'"><meta itemprop="width" content="'. esc_attr($meta_image[1]) .'"><meta itemprop="height" content="'. esc_attr($meta_image[2]) .'"></div>';
		} else {
			$image_alt = $image = $image_defined = $image_bg = $image_meta = '';
		}
			
		if( $cat_hide == 'off' ) {
			$cat = '';
		} else {
			$cat = '<span class="mp-category">'. get_the_category_list( ', ' ) .'</span>';
		}
		
		if( $author_hide == 'off' ) {
			$author = '<div class="hidden-meta" itemprop="author" itemscope itemtype="http://schema.org/Person"><meta itemprop="name" content="'. esc_html(get_the_author()) .'"></div>';
		} else {	
			if( $date_hide == 'off' ) {
				$author = '<span class="mp-author"><a class="author-url" href="'. esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )) .'" title="'. esc_attr(sprintf( __( 'View all posts by %s', 'core-extend' ), get_the_author() )) .'" rel="author"><span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">'. esc_html(get_the_author()) .'</span></span></a></span>';
			} else {
				$author = '<span class="mp-author mp-author-divider"><a class="author-url" href="'. esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )) .'" title="'. esc_attr(sprintf( __( 'View all posts by %s', 'core-extend' ), get_the_author() )) .'" rel="author"><span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">'. esc_html(get_the_author()) .'</span></span></a></span>';
			}	
		}	
		
		if( $date_hide == 'off' ) {
			$date = '';
			$metadate = '<time datetime="'. esc_attr(get_the_date( 'c' )) .'" itemprop="datePublished"></time><time class="meta-date-modified" datetime="'. esc_attr(get_the_modified_date( 'c' )) .'" itemprop="dateModified"></time>';
		} else {
			if ( function_exists( 'mnky_post_time_output' ) ) {
				$date = '<span class="mp-date">'. mnky_post_time_output() .'</span>';
			} else {
				$date = get_the_date();
			}	
		}
		
		if( $excerpt_hide == 'off' ) {
			$excerpt = '';
		} else {			
			$excerpt = '<span itemprop="articleBody" class="mp-excerpt">'. esc_html(get_the_excerpt()) .'</span>';
		}
		
		if( $excerpt_show != 'on' ) {
			$hidden_excerpt = '';
		} else {			
			$hidden_excerpt = '<span itemprop="articleBody" class="mp-excerpt">'. esc_html(get_the_excerpt()) .'</span>';
		}
				
		if( $views_hide == 'off' ) {
			$post_views = '';
		} else {
			if ( function_exists( 'mnky_getPostViews' ) ) {			
				$post_views = '<span class="mp-views">'. mnky_getPostViews( get_the_ID() ) .'</span>';
			} else {
				$post_views = '';
			}	
		}
		
		if( $comments_hide == 'off' ) {
			$comments = '';
		} else {
			$comments = '<span class="mp-comment"><a href="'. esc_url(get_comments_link()) .'" title="'. __('Comments', 'core-extend') .'"><i class="post-icon icon-comments"></i>'. esc_html(get_comments_number()) .'</a></span><meta itemprop="interactionCount" content="UserComments:'. esc_html(get_comments_number()) .'"/>';
		}
		
		$post_format = ' post-format-'.get_post_format();
		if ( false === get_post_format() ) {
			$post_format = '';
		}
		
		if( $rating_hide != 'off' && get_post_meta( get_the_ID(), 'enable_review', true ) == 'on' && function_exists( 'mnky_review_sum' ) ){	
				if ( get_post_meta( get_the_ID(), 'review_breakdown', true ) == 'off' ) {
					$rating = '<div class="mp-rating-wrapper"><div class="mp-rating-stars"><span style="color:'.esc_attr($rating_color).'; width:'. esc_attr( get_post_meta( get_the_ID(), 'review_overall_rating', true ) * 10 ) .'%"></span></div></div>';
				} else {
					$rating = '<div class="mp-rating-wrapper"><div class="mp-rating-stars"><span style="color:'.esc_attr($rating_color).'; width:'. esc_attr(mnky_review_sum() * 10 ).'%"></span></div></div>';
				}
		} else {
			$rating = '';
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
		
		
		if( $layout == 1 ){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" class="mp-container mp-post-'. esc_attr($count) . esc_attr($post_format) .' mp-post-'. esc_attr($article_order) .'">'. $image .'<div class="mp-content">'. $cat . $title . $rating . $excerpt . $author . $date . $comments . $post_views .'</div>'. $metadate . $publisher .'</div>';
		} elseif( $layout == 2 ){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" class="mp-container mp-post-'. esc_attr($count) . esc_attr($post_format) .' mp-post-'. esc_attr($article_order) .'">'. $image .'<div class="mp-content">'. $cat . $title . $rating . $excerpt . $author . $date . $comments . $post_views .'</div>'. $metadate . $publisher .'</div>';
		} elseif( $layout == 3){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" class="mp-container mp-post-'. esc_attr($count) . esc_attr($post_format) .' mp-post-'. esc_attr($article_order) .'">'. $image .'<div class="mp-content">'. $cat . $title . $rating . $excerpt . $author . $date . $comments . $post_views .'</div>'. $metadate . $publisher .'</div>';
		} elseif( $layout == 4 ){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" class="mp-container mp-post-'. esc_attr($count) . esc_attr($post_format) .'"><div class="mp-content">'. $title . $rating .$cat . $author . $date . $comments . $post_views . '</div>'. $metadate . $image_meta . $publisher .'</div>';
		} elseif( $layout == 5 ){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" class="mp-container mp-post-'. esc_attr($count) . esc_attr($post_format) .'">'. $image .'<div class="mp-content">'. $title . $rating . $hidden_excerpt . $author . $date . $comments . $post_views .'</div>'. $metadate . $publisher .'</div>';
		} elseif( $layout == 6 ){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" class="mp-container mp-post-'. esc_attr($count) . esc_attr($post_format) .' clearfix">'. $image_defined .'<div class="mp-content">'. $title . $rating . $author . $date . $comments . $post_views .'</div>'. $metadate . $publisher .'</div>';
		} elseif( $layout == 7 ){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" class="mp-container mp-post-'. esc_attr($count) . esc_attr($post_format) .' clearfix">'. $image_defined .'<div class="mp-content">'. $title . $rating . $author . $date . $comments . $post_views .'</div>'. $metadate . $publisher .'</div>';			
		} elseif( $layout == 8 ){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" class="mp-container mp-post-'. esc_attr($count) . esc_attr($post_format) .'">'. $title . $rating . $image .'<div class="mp-content">'. $excerpt . $author . $date . $comments . $post_views .'</div>'. $metadate . $publisher .'</div>';
		} elseif( $layout == 9){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" style="height:'. esc_attr($height) .'px; background-image:url('. $image_bg .');" class="mp-container mp-post-'. esc_attr($count) . esc_attr($post_format) .'"><a class="mp-bg-url" href="'. esc_url(get_permalink()) .'"></a><a href="'. esc_url(get_permalink()) .'" class="format-icon"></a><div class="mp-content">'. $cat . $title . $rating . $excerpt . $author . $date .'</div>'. $metadate . $image_meta . $publisher .'</div>';
		}	
		elseif( $layout == 10){
			$output .= '<div id="post-' . get_the_ID() . '" itemscope itemtype="http://schema.org/Article" class="mp-container mp-post-'. esc_attr($count) .'"><div class="mp-content" style="background-color:'. esc_attr($breaking_bg) .'">'. $breaking_title .'</div><time datetime="'. esc_attr(get_the_date( 'c' )) .'" itemprop="datePublished"></time><time class="meta-date-modified" datetime="'. esc_attr(get_the_modified_date( 'c' )) .'" itemprop="dateModified"></time>'. $image_meta . $publisher .'<div class="hidden-meta" itemprop="author" itemscope itemtype="http://schema.org/Person"><meta itemprop="name" content="'. esc_html(get_the_author()) .'"></div></div>';
		}			
	endwhile; 
	
	if( $pagination == 'on' ) {
		$output .= '<div class="navigation pagination">';
		$big = 999999999; // need an unlikely integer

		$output .= paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '/page/%#%',
			'current' => max( 1, $paged ),
			'end_size' => 3,
			'mid_size' => 3,
			'prev_text' => __( 'Previous', 'core-extend' ),
			'next_text' => __( 'Next', 'core-extend' ),
			'total' => $query->max_num_pages
		) );
		$output .= '</div>';
	}
	
	wp_reset_postdata();

	$output = '<div class="'.esc_attr( trim( $css_class ) ).'" >'. $output .'</div>';

echo $output;