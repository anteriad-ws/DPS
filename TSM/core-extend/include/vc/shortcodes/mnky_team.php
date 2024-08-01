<?php
$output = '';
	
	$accent_color = '#e74c3c';
	
	if ( function_exists( 'ot_get_option' ) ) {
	  $accent_color = ot_get_option('accent_color');
	}
	
	extract(shortcode_atts(array(
		'el_class' => '',
		'img_url' => '',
		'name' => 'John Doe',
		'position' => 'Designer',
		'css_animation' => '',
		'css_animation_delay' => ''
	), $atts));
	
	$el_class = $this->getExtraClass($el_class);
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'team_wrapper'.$el_class, $this->settings['base']);
	$css_class .= $this->getCSSAnimation($css_animation);
	($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';
	
	if($img_url != '') {
		$img_url = wp_get_attachment_image_src( $img_url, 'full');
		$img_url = $img_url[0];
		$img = '<img itemprop="image" src="'. esc_url( aq_resize($img_url, 540, 540, true, true, true) ) .'" alt="" />';
	} else {
		$img = '';
	}

		$output .= '<div itemscope itemtype="http://schema.org/Person" class="'. esc_attr( trim( $css_class ) ) .'"><div class="team_image">'.$img.'</div><div itemprop="name" class="team_member_name">'. esc_html( $name ) .'</div><div itemprop="jobTitle" class="team_member_position"><span>'. esc_html( $position ) .'</span></div><div class="team_info">';
		$output .= wpb_js_remove_wpautop($content);
		$output .= '</div></div>';


echo $output;
