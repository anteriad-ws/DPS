<?php
$output = '';

	extract(shortcode_atts(array(
		'font_size' => '',
		'label_hide' => '',
		'meta_hide' => '',		
		'el_class' => '',
		'css_animation' => '',
		'css_animation_delay' => ''
	), $atts));
	
	$el_class = $this->getExtraClass($el_class);
	
	$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'entry-header clearfix article-info'.$el_class, $this->settings['base']);
	$css_class .= $this->getCSSAnimation($css_animation);
	($css_animation != '' && $css_animation_delay != '') ? $css_class .= $this->getExtraClass($css_animation_delay) : '';

	if( $font_size != '') {
	$font_size = 'style="font-size:'. esc_attr($font_size) .'"';
	}
	
	$output .= '<header class="'. esc_attr( trim( $css_class ) ) .'">';

echo $output;
?>

	<?php 
	if(function_exists('mnky_label') && $label_hide != 'off' ){
    mnky_label();
	} ?>	
	<h5><?php the_category( ', ' ); ?></h5>
	<h1 class="entry-title" <?php echo $font_size ?>><?php the_title(); ?></h1>
	<?php 
	if(function_exists('mnky_post_meta') && $meta_hide != 'off'){
    mnky_post_meta();
	} ?>
	</header><!-- .entry-header -->