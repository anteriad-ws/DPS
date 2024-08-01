<?php
$output = $text_align = $img = $name = $author_dec = '';

	extract(shortcode_atts(array(
		'el_class' => '',
		'img_url' => '',
		'name' => 'John Doe',
		'author_dec' => 'Designer',
		'description' => ''
	), $atts));
			
	$el_class = $this->getExtraClass($el_class);
	
	if($img_url != '') {
		$img_url = wp_get_attachment_image_src( $img_url, 'large');
		$img_url = $img_url[0];
		$img = '<div class="testimonial-img"><img src="'. esc_url( aq_resize($img_url, '140', '140', true) ) .'" alt="" /></div>';
	}
	
	if($name != '') {
		$name = '<div class="testimonial-author" >'. esc_html( $name );
		if($author_dec != '') { $name .= '<span class="testimonial-separator"></span><span class="testimonial-author-desc">'. esc_html( $author_dec ) .'</span>'; }
		$name .= '</div>';	
	}
	
	$output .= '<li class="testimonial-wrapper'. esc_attr( $el_class ) .'" style="display:none;">';	
	$output .= '<div class="testimonial-content"><span>'.wpb_js_remove_wpautop($content).'</span></div>';
	$output .= $img;
	$output .= $name;	
	$output .= '</li>';

echo $output;