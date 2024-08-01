<?php
$output = $el_class = $title = $currency = $price = $time  = $meta = $add_badge = $box_style = $bg_color = $badge_bg = $badge_color = $badge_text = $color = $css_animation = $css_animation_delay = $meta = $link = $a_href = $a_title = $a_target = $a_rel = '';
	
	$accent_color = '#e74c3c';
	
	if ( function_exists( 'ot_get_option' ) ) {
	  $text_color = ot_get_option('body_text_color');
	}
	
	$accent_color = '#14b8c0';
	
	if ( function_exists( 'ot_get_option' ) ) {
	  $accent_color = ot_get_option('accent_color');
	}

	
		extract(shortcode_atts(array(
			'el_class' => '',
			'title' => 'Starter Plan',
			'currency' => '$',
			'price' => '10',
			'time' => '/mo',
			'meta' => 'Great for small business',
			'add_badge' => 'off',
			'box_style' => 'box-style-1',
			'bg_color' => '#FFFFFF',
			'color' => '#5E5E5E',
			'badge_text' => 'Best Offer',
			'header_bg' => '#5E5E5E',
			'header_color' => '#FFFFFF',
			'badge_bg' => '#DD3333',
			'badge_color' => '#FFFFFF',
			'border_color' => '',
			'hover_effect' => '',
			'effect_active' => '',
			'link' => '',
			'css_animation' => '',
			'css_animation_delay' => ''
		), $atts));
		
		$el_class = $this->getExtraClass($el_class);
		$box_style = $this->getExtraClass($box_style);
		$hover_effect = $this->getExtraClass($hover_effect);
		$effect_active = $this->getExtraClass($effect_active);
		
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'pricing-box'.$box_style.$hover_effect.$effect_active.$el_class, $this->settings['base']);
		$css_class .= $this->getCSSAnimation($css_animation);
		($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';
		
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
			
		$title = '<span class="plan-title">'. esc_html( $title ) .'</span>';
		$currency = '<span class="plan-currency">'. esc_html( $currency ) .'</span>';
		$price = '<span class="plan-price">'. esc_html( $price ) .'</span>';
		$time = '<span class="plan-time">'. esc_html( $time ).'</span>';
		($meta != '') ? $meta = '<span class="plan-meta">'. esc_html( $meta ).'</span>' : $meta = '';
		($border_color != '') ? $border_color = ' border:1px solid '. esc_attr( $border_color ) .';' : $border_color = '';
		
		$output .= '<div class="'. esc_attr( trim( $css_class ) ) .'" >';
			$output .= '<div class="pricing-box-inner" style="background-color:'. esc_attr( $bg_color ) .'; color:'. esc_attr( $color ) .';'.$border_color.'">';
			
				if($add_badge == 'on') {
					$output .= '<div class="plan-badge" style="background-color:'. esc_attr( $badge_bg ) .'; color:'. esc_attr( $badge_color ) .';"><span>'. esc_html( $badge_text ) .'</span></div>';
				}
			
				if($box_style == ' box-style-1') {
					$output .= '<div class="plan-header">';
						$output .= $title . $currency . $price . $time . $meta;
					$output .= '</div>';
					$output .= '<span class="plan-divider" style="background-color:'. esc_attr( $color ) .';"></span>';
				} elseif($box_style == ' box-style-2') {
					$output .= '<div class="plan-header" style="background-color:'. esc_attr( $header_bg ) .'; color:'. esc_attr( $header_color ) .';">';
						$output .= $title . $currency . $price . $time . $meta;
					$output .= '</div>';
					$output .= '<div class="plan-arrow" style="border-bottom-color:'. esc_attr( $bg_color ) .';"></div>';
				} elseif($box_style == ' box-style-3') {
					$output .= '<div class="plan-header">';
						$output .= '<span class="plan-title-wrapper" style="background-color:'. esc_attr( $header_bg ) .'; color:'. esc_attr( $header_color ) .';">';
							$output .= $title . $meta;
						$output .= '</span>';
						$output .= '<div class="plan-arrow" style="border-top-color:'. esc_attr( $header_bg ) .';"></div>';
						$output .= $currency . $price . $time;
					$output .= '</div>';
					$output .= '<span class="plan-divider" style="background-color:'. esc_attr( $color ) .';"></span>';
				} elseif($box_style == ' box-style-4') {
					$output .= '<div class="plan-header" style="background-color:'. esc_attr( $header_bg ) .'; color:'. esc_attr( $header_color ).';">';
						$output .= $title . $meta;
						$output .= '<span class="plan-price-wrapper" style="background-color:'. esc_attr( $header_bg ) .'; color:'. esc_attr( $header_color ) .'; border-color:'. esc_attr( $bg_color ) .'; ">';
							$output .= $currency . $price . $time;
						$output .= '</span>';

					$output .= '</div>';
				}
				
				$output .= '<div class="plan-features">';
					$output .= wpb_js_remove_wpautop($content);
				$output .= '</div>'; //.plan-features
			$output .= '</div>'; //.pricing-box-container
		$output .= '</div>'; //.pricing-box
		
		if ( $use_link ) {
			$output = '<a ' .$link_attributes. '>' . $output . '</a>';
		}


echo $output;