<?php
$output = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypoicons = $icon_linecons = $link = $a_href = $a_title = $a_target = $a_rel = '';
	
	$accent_color = '#e74c3c';
	
	if ( function_exists( 'ot_get_option' ) ) {
	  $accent_color = ot_get_option('accent_color');
	}
		
	extract(shortcode_atts(array(
		'icon_type' => 'fontawesome',
		'icon_fontawesome' => 'fa fa-adjust',
		'icon_openiconic' => '',
		'icon_typicons' => '',
		'icon_entypoicons' => '',
		'icon_linecons' => '',
		'icon_entypo' => '',
		'icon_color' => $accent_color,
		'link' => '',
		'css_animation' => '',
		'css_animation_delay' => '',
		'el_class' => ''
	), $atts));
	
	$el_class = $this->getExtraClass($el_class);
	($icon_color != '') ? $icon_color = ' style="color:'. esc_attr( $icon_color ) .'"' : '';
	
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'custom-list-item'.$el_class, $this->settings['base']);
	$css_class .= $this->getCSSAnimation($css_animation);
	($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';
	
	vc_icon_element_fonts_enqueue( $icon_type );

	//parse link
	$link = ( '||' === $link ) ? '' : $link;
	$link = vc_build_link( $link );
	$use_link = false;
	if ( strlen( $link['url'] ) > 0 ) {
		$use_link = true;
		$a_href = $link['url'];
		$a_title = $link['title'];
		$a_target = $link['target'];
		$a_rel = $link['rel'];
	}
	
	$link_attributes = array();
	
	if ( $use_link ) {
	$link_attributes[] = 'href="' . trim( $a_href ) . '"';
	$link_attributes[] = 'title="' . esc_attr( trim( $a_title ) ) . '"';
	if ( ! empty( $a_target ) ) {
		$link_attributes[] = 'target="' . esc_attr( trim( $a_target ) ) . '"';
	}
	if ( ! empty( $a_rel ) ) {
		$link_attributes[] = 'rel="' . esc_attr( trim( $a_rel ) ) . '"';
	}
	}
	
	$link_attributes = implode( ' ', $link_attributes );

	if ( $use_link ) {
		$output .= '<i class="'. esc_attr( ${"icon_" . $icon_type} ) .'"'. $icon_color .'></i>';
		$output = '<div class="'. esc_attr( trim( $css_class ) ) .'">' . $output .'<a ' .$link_attributes. '>'. wpb_js_remove_wpautop($content).'</a></div>';
	} else {
		$output .= '<div class="'. esc_attr( trim( $css_class ) ) .'"><i class="'. esc_attr( ${"icon_" . $icon_type} ) .'"'.$icon_color.'></i>'.wpb_js_remove_wpautop($content).'</div>';
	}
	
echo $output;
