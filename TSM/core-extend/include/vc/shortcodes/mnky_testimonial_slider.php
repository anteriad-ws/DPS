<?php
$output = '';

	extract(shortcode_atts(array(
		'flex_animation' => 'fade',
		'slide_speed' => '5',
		'bullets' => '',
		'el_class' => ''
	), $atts));
			
    wp_enqueue_style('flexslider');
    wp_enqueue_script('flexslider');

	$el_class = $this->getExtraClass($el_class);
	$style = $this->getExtraClass($bullets);	
	
	$output .= '<div class="testimonials-slider'.  esc_attr( $style ) . esc_attr( $el_class ) .'"><div class="wpb_flexslider" data-interval="'. esc_attr( $slide_speed ) .'" data-flex_fx="'. esc_attr( $flex_animation ).'"><ul class="slides">';
		
	$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
	
	$output .= '</ul></div></div>';

echo $output;
