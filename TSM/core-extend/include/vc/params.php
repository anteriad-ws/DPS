<?php

/*---------------------------------------------------------------*/
/* Create custom param for Visual Composer
/*---------------------------------------------------------------*/

// Preview param
function mnky_preview_param($settings, $value) {
	$output = '';

	$output .= '<div class="mnky_preview '.$settings['param_name'].'">';
	foreach ( $settings['value'] as $key=>$val ) {
		$selected = ( $val == $value ) ? ' checked' : '';
			
		$output .= '<label><input type="radio" name="'.$settings['param_name'].'" value="' . $val . '" class="'.$settings['param_name'].'"'.$selected.'><span class="' . $val . '"><i>'.$key.'</i></span></label>';
	}
	$output .= '</div>';
		
	return '<input name="'.$settings['param_name'].'" id="mnky_selected_'.$settings['param_name'].'" class="wpb_vc_param_value '.$settings['param_name'].' '.$settings['type'].'_field"  value="'.$value.'" type="hidden"/>'. $output;	

}
vc_add_shortcode_param('mnky_preview', 'mnky_preview_param', MNKY_PLUGIN_URL . 'assets/js/extend-params.js' );


// Info param
function mnky_info_param($settings, $value) {
	return '<div class="mnky_info '.$settings['param_name'].'"><input name="'.$settings['param_name'].'" class="wpb_vc_param_value '.$settings['param_name'].' '.$settings['type'].'_field"  value=""  type="hidden"/>'.$value.'</div>';
}
vc_add_shortcode_param('mnky_info', 'mnky_info_param');


// Categories param
function mnky_cat_param($settings, $value) {
	$terms = get_terms('category', 'hide_empty=0');
	$term_list = $adclass = '';
	$selected_ids = explode(', ', $value);
	
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ($terms as $term) {

			$term_list .= '<span id="'. $term->slug .'"';
			foreach ($selected_ids as $selected) {
				$term_list .= ( $term->slug == $selected ) ? ' class="selected-cat"' : '';
			}
			$term_list .= '>' . $term->name . '</span>';
		}
	} else { $term_list .= '<div class="no-result">'.esc_html__('No categories found!').'</div>'; }
	
	return '<div class="mnky_categories '.$settings['param_name'].'" data-param-name="'.$settings['param_name'].'"><input name="'.$settings['param_name'].'" id="mnky_selected_'.$settings['param_name'].'" class="wpb_vc_param_value '.$settings['param_name'].' '.$settings['type'].'_field"  value="'.$value.'"  type="hidden"/>'.$term_list.'</div>';
}
vc_add_shortcode_param('mnky_cat', 'mnky_cat_param');


// Tag param
function mnky_tag_param($settings, $value) {
	$terms = get_terms('post_tag', 'hide_empty=0');
	$term_list = $adclass = '';
	$selected_ids = explode(', ', $value);
	
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ($terms as $term) {

			$term_list .= '<span id="'. $term->slug .'"';
			foreach ($selected_ids as $selected) {
				$term_list .= ( $term->slug == $selected ) ? ' class="selected-tag"' : '';
			}
			$term_list .= '>' . $term->name . '</span>';
		}
	} else { $term_list .= '<div class="no-result">'.esc_html__('No tags found!').'</div>'; }
	
	return '<div class="mnky_tags '.$settings['param_name'].'" data-param-name="'.$settings['param_name'].'"><input name="'.$settings['param_name'].'" id="mnky_selected_'.$settings['param_name'].'" class="wpb_vc_param_value '.$settings['param_name'].' '.$settings['type'].'_field"  value="'.$value.'"  type="hidden"/>'.$term_list.'</div>';
}

vc_add_shortcode_param('mnky_tags', 'mnky_tag_param');