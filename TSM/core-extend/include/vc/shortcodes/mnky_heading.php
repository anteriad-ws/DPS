<?php
$output = $cat_links = '';

	extract(shortcode_atts(array(
		'el_class' => '',
		'title' => 'This is title',
		'color' => '',
		'line_color' => '#e2e2e2',
		'line_part_color' => '#009eed',
		'categories' => '',
		'cat_color' => '#999',
		'link' => '',
		'css_animation' => '',
		'css_animation_delay' => ''
	), $atts));
	
	$el_class = $this->getExtraClass($el_class);
	($color != '') ? $color = ' style="color:'.esc_attr($color).';"' : '';
	
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'heading_wrapper'.$el_class, $this->settings['base']);
	$css_class .= $this->getCSSAnimation($css_animation);
	($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';
	
	//parse link
	$link = ( $link == '||' ) ? '' : $link;
	$link = vc_build_link( $link );
	$use_link = false;
	if ( strlen( $link['url'] ) > 0 ) {
		$use_link = true;
		$a_href = $link['url'];
		$a_title = $link['title'];
		$a_target = strlen( $link['target'] ) > 0 ? $link['target'] : '_self';
	}
	
	if( $categories != '' ){ 
	   $categories = explode(',',$categories);
	   foreach($categories as $category) {
			$id = get_category_by_slug( $category );
			$cat_links .= '<span class="heading-category"><a href="'. esc_url( get_category_link( $id->term_id ) ) .'" style="color:'. esc_attr($cat_color) .'">'. $id->name .'</a></span>';
		}
		$cat_links = '<div class="heading-categories">'. $cat_links .'</div>';
	}

	if ( $use_link ) {
	$output .= '<div class="'. esc_attr( trim( $css_class ) ) .'"><a href="'. esc_url($a_href) .'" title="'. esc_attr($a_title) .'" target="'. esc_attr($a_target) .'"><h2'. $color .'>'. esc_html ($title) .'</h2></a><div class="heading-line" style="background-color:'.esc_attr($line_color).'"><span style="background-color:'.esc_attr($line_part_color).'"></span></div>'. $cat_links .'</div>';
	} else {
	$output .= '<div class="'. esc_attr( trim( $css_class ) ) .'"><h2'. $color .'>'. esc_html ($title) .'</h2><div class="heading-line" style="background-color:'.esc_attr($line_color).'"><span style="background-color:'.esc_attr($line_part_color).'"></span></div>'. $cat_links .'</div>';
	}

echo $output;