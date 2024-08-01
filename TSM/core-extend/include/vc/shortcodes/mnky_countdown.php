<?php
$output = '';

	extract(shortcode_atts(array(
		'el_class' => '',
		'date' => '2015/04/16',
		'font' => '100px',
		'border' => '#ccc' 
	), $atts));
	
	wp_enqueue_script( 'Countdown', MNKY_PLUGIN_URL . 'assets/js/jquery.countdown.min.js', array('jquery'));
	
	$el_class = $this->getExtraClass($el_class);
	
	$output .= '<div class="countdown_wrapper '. esc_attr( trim( $el_class ) ) .' clearfix" data-countdown="'. esc_attr( $date ) .'" style="border-color:'. esc_attr( $border ) .';"><div class="countdown_days vc_col-sm-3"><span style="font-size:'. esc_attr( $font ) .';">00</span>'.__('Days', 'core-extend').'</div><div class="countdown_hours vc_col-sm-3"><span style="font-size:'. esc_attr( $font ) .';">00</span>'.__('Hours', 'core-extend').'</div><div class="countdown_min vc_col-sm-3"><span style="font-size:'. esc_attr( $font ) .';">00</span>'.__('Min', 'core-extend').'</div><div class="countdown_sec vc_col-sm-3"><span style="font-size:'. esc_attr( $font ) .';">00</span>'.__('Sec', 'core-extend').'</div></div>';

echo $output;
