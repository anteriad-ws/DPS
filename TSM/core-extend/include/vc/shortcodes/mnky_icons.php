<?php
$output = $a_style = $link_class = $styles = $style = $icon_fontawesome = $icon_openiconic = $icon_typicons = $icon_entypoicons = $icon_linecons = $link = $a_href = $a_title = $a_target = $a_rel = '';
	
	$accent_color = '#e74c3c';
	
	if ( function_exists( 'ot_get_option' ) ) {
	  $accent_color = ot_get_option('accent_color');
	}
		
	extract(shortcode_atts(array(
		'el_class' => '',
		'icon_type' => 'fontawesome',
		'icon_fontawesome' => 'fa fa-adjust',
		'icon_openiconic' => '',
		'icon_typicons' => '',
		'icon_entypoicons' => '',
		'icon_linecons' => '',
		'icon_entypo' => '',
		'icon_size' => '28px',
		'padding_left' => '0px',
		'padding_right' => '0px',
		'link' => '',
		'hover_effect' => '',
		'hover_color' => $accent_color,
		'icon_color' => '#444444',
		'css_animation' => '',
		'css_animation_delay' => ''
	), $atts));

	$el_class = $this->getExtraClass($el_class);
	
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'mnky-font-icon'.$el_class, $this->settings['base']);
	$css_class .= $this->getCSSAnimation($css_animation);
	($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';
	
	vc_icon_element_fonts_enqueue( $icon_type );

	$styles = array(
		($icon_size != '') ? 'font-size:'.$icon_size.';' : null,
		($icon_color != '') ? 'color:'.$icon_color.';' : null,
		($padding_left != '') ? 'padding-left:'.$padding_left.';' : null,
		($padding_right != '') ? 'padding-right:'.$padding_right.';' : null
	); 
	
	if( !empty($styles) ){
		$style = implode(' ', array_filter($styles));	
		$style = ' style="'. esc_attr( $style ) .'"';
	}
	
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

	if($hover_effect == 'change_color'){
		$a_style = ' style="color:'. esc_attr( $hover_color ) .';"';
		$link_class = ' change-color';
	} elseif ($hover_effect == 'bounce'){
		$link_class = ' bounce';
	} elseif ($hover_effect == 'shrink'){
		$link_class = ' shrink';
	} elseif ($hover_effect == 'fade_out'){
		$link_class = ' fade-out';
	}
	

	if ( $use_link ) {
		$output .= '<i class="'. esc_attr( ${"icon_" . $icon_type} ) .'"'. $style .'></i>';
		$output = '<a class="'. esc_attr( trim( $css_class . $link_class ) ) .'" '.$a_style.' ' .$link_attributes. '>' . $output . '</a>';
	} else {
		$output .= '<i class="'.esc_attr( ${"icon_" . $icon_type} ).'"'.$style.'></i>';
		$output = '<span class="'. esc_attr( trim( $css_class . $link_class ) ) .'"'.$a_style.'>' . $output . '</span>';
	}
	
echo $output;
