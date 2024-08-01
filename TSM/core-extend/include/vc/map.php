<?php
/*---------------------------------------------------------------*/
/* Register shortcode within Visual Composer interface
/*---------------------------------------------------------------*/

add_action( 'init', 'mnky_vc_map' );
function mnky_vc_map() {

	if ( function_exists( 'vc_map' ) ) {
	$add_css_animation = vc_map_add_css_animation( true );
	} else {
	$add_css_animation = '';
	}

	$add_css_animation_delay = array(
		'type' => 'dropdown',
		'heading' => esc_html__('CSS Animation Delay', 'core-extend'),
		'param_name' => 'css_animation_delay',
		'value' => array(
			'0ms' => '', 
			'100ms' => 'delay-100', 
			'200ms' => 'delay-200', 
			'300ms' => 'delay-300', 
			'400ms' => 'delay-400', 
			'500ms' => 'delay-500', 
			'600ms' => 'delay-600', 
			'700ms' => 'delay-700', 
			'800ms' => 'delay-800', 
			'900ms' => 'delay-900', 
			'1000ms' => 'delay-1000', 
			'1100ms' => 'delay-1100', 
			'1200ms' => 'delay-1200', 
			'1300ms' => 'delay-1300', 
			'1400ms' => 'delay-1400', 
			'1500ms' => 'delay-1500', 
			'1600ms' => 'delay-1600',
			'1700ms' => 'delay-1700',
			'1800ms' => 'delay-1800', 
			'1900ms' => 'delay-1900', 
			'2000ms' => 'delay-2000'
		)
	);
	
	// Progress Bar
	vc_map( array(
	  'name' => esc_html__('Progress Bar', 'core-extend'),
	  'base' => 'mnky_progress_bar',
	  'icon' => 'icon-wpb-graph',
	  'category' => esc_html__('Content', 'core-extend'),
	  'description' => esc_html__('Animated progress bar', 'core-extend'),
	  'params' => array(
		array(
		  'type' => 'exploded_textarea',
		  'heading' => esc_html__('Graphic values', 'core-extend'),
		  'param_name' => 'values',
		  'description' => esc_html__('Input graph values here. Divide values with linebreaks (Enter). Example: 90|Development', 'core-extend'),
		  'value' => '90|Development,80|Design,70|Marketing'
		),
		array(
		  'type' => 'textfield',
		  'heading' => esc_html__('Units', 'core-extend'),
		  'param_name' => 'units',
		  'description' => esc_html__('Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.', 'core-extend')
		),
		array(
		  'type' => 'colorpicker',
		  'heading' => esc_html__('Bar color', 'core-extend'),
		  'param_name' => 'bar_color',
		  'description' => esc_html__('Leave empty to use theme accent color.', 'core-extend')
		),
		array(
		  'type' => 'checkbox',
		  'heading' => esc_html__('Options', 'core-extend'),
		  'param_name' => 'options',
		  'value' => array(esc_html__('Add Stripes?', 'core-extend') => 'striped', esc_html__('Add animation? Will be visible with striped bars.', 'core-extend') => 'animated')
		),
		array(
		  'type' => 'textfield',
		  'heading' => esc_html__('Extra class name', 'core-extend'),
		  'param_name' => 'el_class',
		  'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
		)
	  )
	) );


	// Buttons
	vc_map( array(
		'name' => esc_html__('Styled Button', 'core-extend'),
		'base' => 'mnky_button',
		'icon' => 'icon-mnky_button',
		'category' => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Eye catching button', 'core-extend'),
		'params' => array(
			array(
				'type' => 'vc_link',
				'heading' => esc_html__('URL (Link)', 'core-extend'),
				'param_name' => 'link',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Button text', 'core-extend'),
				'admin_label' => true,
				'param_name' => 'title',
				'value' => esc_html__('Text on the button', 'core-extend'),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Button color', 'core-extend'),
				'param_name' => 'bg_color',
				'value' => '',
				'description' => esc_html__('Leave empty to use theme accent color.', 'core-extend')
			),			
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Text color', 'core-extend'),
				'param_name' => 'color',
				'value' => '#fff',
				'description' => esc_html__('', 'core-extend')
			),			
			array(
				'type' => 'mnky_preview',
				'heading' => esc_html__( 'Background hover color', 'core-extend' ),
				'param_name' => 'bg_hover_color',
				'value' => array('Blue' => 'flat-blue', 'Soft Blue' => 'flat-softblue', 'Coffe' => 'flat-coffe', 'Pink' => 'flat-pink', 'Lime' => 'flat-lime', 'Watermelon' => 'flat-watermelon', 'Brown' => 'flat-brown', 'Purple' => 'flat-purple', 'Gray' => 'flat-gray', 'Silver' => 'flat-silver', 'Mint' => 'flat-mint', 'Green' => 'flat-green', 'Sky Blue' => 'flat-sky', 'Teal' => 'flat-teal', 'Magenta' => 'flat-magenta', 'Sand' => 'flat-sand', 'Yellow' => 'flat-yellow', 'Black' => 'flat-black', 'Navy Blue' => 'flat-navy', 'Plum' => 'flat-plum', 'Red' => 'flat-red', 'Orange' => 'flat-orange'),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Icon library', 'core-extend' ),
				'value' => array(
					esc_html__( 'No icon', 'core-extend' ) => '',
					esc_html__( 'Font Awesome', 'core-extend' ) => 'fontawesome',
					esc_html__( 'Open Iconic', 'core-extend' ) => 'openiconic',
					esc_html__( 'Typicons', 'core-extend' ) => 'typicons',
					esc_html__( 'Entypo', 'core-extend' ) => 'entypo',
					esc_html__( 'Linecons', 'core-extend' ) => 'linecons'
				),
				'param_name' => 'icon_type',
				'description' => esc_html__( 'Select icon library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_fontawesome',
				'value' => 'fa fa-info-circle',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'fontawesome',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_openiconic',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'openiconic',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'openiconic',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_typicons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'typicons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
				'element' => 'icon_type',
				'value' => 'typicons',
			),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_entypo',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'entypo',
					'iconsPerPage' => 300, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'entypo',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_linecons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'linecons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'linecons',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			$add_css_animation,
			$add_css_animation_delay,
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)
		)
	) );
	
	// Heading
	vc_map( array(
		'name' => esc_html__('Styled Heading', 'core-extend'),
		'base' => 'mnky_heading',
		'icon' => 'icon-mnky_heading',
		'category' => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Eye catching headings', 'core-extend'),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Title', 'core-extend'),
				'param_name' => 'title',
				'value' => 'This is title',
				'admin_label' => true,
			),
			array(
				'type' => 'vc_link',
				'heading' => esc_html__('URL (Link)', 'core-extend'),
				'param_name' => 'link',
			),			
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Heading color', 'core-extend'),
				'param_name' => 'color',
				'value' => '',
				'description' => esc_html__('', 'core-extend')
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Bottom line color', 'core-extend'),
				'param_name' => 'line_color',
				'value' => '',
				'description' => esc_html__('', 'core-extend')
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Bottom line first part color', 'core-extend'),
				'param_name' => 'line_part_color',
				'value' => '',
				'description' => esc_html__('', 'core-extend')
			),
			array(
				'type' => 'mnky_cat',
				'heading' => esc_html__( 'Add categories link under title', 'core-extend' ),
				'param_name' => 'categories',
				'description' => '',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Category link color', 'core-extend'),
				'param_name' => 'cat_color',
				'value' => '',
				'description' => esc_html__('', 'core-extend'),	
			),			
			$add_css_animation,
			$add_css_animation_delay,
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)
		)
	) );
	

	// Icons
	vc_map( array(
		'name' => esc_html__('Icons', 'core-extend'),
		'base' => 'mnky_icons',
		'icon' => 'icon-mnky_icons',
		'category' => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Scalable vector icons', 'core-extend'),
		'admin_enqueue_js' => array( MNKY_PLUGIN_URL . 'assets/js/extend-composer-custom-views.js' ),
		'admin_enqueue_css' => array( MNKY_PLUGIN_URL . 'assets/css/core-extend-backend.css'),
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Icon library', 'core-extend' ),
				'value' => array(
					esc_html__( 'Font Awesome', 'core-extend' ) => 'fontawesome',
					esc_html__( 'Open Iconic', 'core-extend' ) => 'openiconic',
					esc_html__( 'Typicons', 'core-extend' ) => 'typicons',
					esc_html__( 'Entypo', 'core-extend' ) => 'entypo',
					esc_html__( 'Linecons', 'core-extend' ) => 'linecons'
				),
				'param_name' => 'icon_type',
				'admin_label' => true,
				'description' => esc_html__( 'Select icon library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_fontawesome',
				'value' => 'fa fa-info-circle',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'fontawesome',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_openiconic',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'openiconic',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'openiconic',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_typicons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'typicons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
				'element' => 'icon_type',
				'value' => 'typicons',
			),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_entypo',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'entypo',
					'iconsPerPage' => 300, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'entypo',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_linecons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'linecons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'linecons',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Icon size', 'core-extend'),
				'param_name' => 'icon_size',
				'value' => '28px',
				'description' => esc_html__('', 'core-extend')
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icon color', 'core-extend'),
				'param_name' => 'icon_color',
				'value' => '#444444',
				'description' => esc_html__('', 'core-extend')
			),		
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Padding left', 'core-extend'),
				'param_name' => 'padding_left',
				'value' => '0px',
				'description' => esc_html__('The padding-left property sets the left padding (space) of an element.', 'core-extend')
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Padding right', 'core-extend'),
				'param_name' => 'padding_right',
				'value' => '0px',
				'description' => esc_html__('The padding-right property sets the right padding (space) of an element.', 'core-extend')
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Hover effect', 'core-extend'),
				'param_name' => 'hover_effect',
				'value' => array(esc_html__('None', 'core-extend') => '', esc_html__('Fade out', 'core-extend') => 'fade_out', esc_html__('Change color', 'core-extend') => 'change_color', esc_html__('Bounce', 'core-extend') => 'bounce', esc_html__('Shrink', 'core-extend') => 'shrink')
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Hover color', 'core-extend'),
				'param_name' => 'hover_color',
				'description' => esc_html__('Leave empty to use theme accent color.', 'core-extend'),
				'dependency' => array('element' => 'hover_effect', 'value' => array('change_color'))
			),
			array(
				'type' => 'vc_link',
				'heading' => esc_html__('URL (Link)', 'core-extend'),
				'param_name' => 'link',
			),
			$add_css_animation,
			$add_css_animation_delay,		
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)
		),
		'js_view' => 'VcIconView'
	) );


	// Team
	vc_map( array(
		'name' => esc_html__('Team', 'core-extend'),
		'base' => 'mnky_team',
		'icon' => 'icon-mnky_team',
		'wrapper_class' => 'clearfix',
		'category' => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Staff members', 'core-extend'),
		'params' => array(
			array(
				'type' => 'attach_image',
				'heading' => esc_html__('Member image', 'core-extend'),
				'param_name' => 'img_url',
				'value' => '', 
				'description' => esc_html__('Recommended min. width: 540px', 'core-extend')
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Name', 'core-extend'),
				'param_name' => 'name',
				'value' => 'John Doe',
				'admin_label' => true,
				'description' => esc_html__('', 'core-extend')
			),		
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Position', 'core-extend'),
				'param_name' => 'position',
				'value' => 'designer',
				'admin_label' => true,
				'description' => esc_html__('e.g. Senior Designer', 'core-extend')
			),
			array(
				'type' => 'textarea',
				'heading' => esc_html__('Aditional info (optional)', 'core-extend'),
				'param_name' => 'content',
				'value' => '',
				'description' => esc_html__('', 'core-extend')
			),
			$add_css_animation,
			$add_css_animation_delay,
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)
		)
	) );


	// Testimonial slider
	vc_map( array(
		'name' => esc_html__('Testimonials', 'core-extend'),
		'base' => 'mnky_testimonial_slider',
		'icon' => 'icon-mnky_testimonials', 
		'as_parent' => array('only' => 'mnky_testimonial'),
		'is_container' => true,
		'show_settings_on_create' => true,
		'category' => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Quote slider', 'core-extend'),
		'params' => array(	
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Animation', 'core-extend'),
				'param_name' => 'flex_animation',
				'value' => array(esc_html__('Fade', 'core-extend') => '', esc_html__('Slide', 'core-extend') => 'slide',)
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Slide show speed', 'core-extend'),
				'param_name' => 'slide_speed',
				'value' => '5',
				'description' => esc_html__('Set the speed of the slideshow cycling, in seconds', 'core-extend')
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Hide paging control?', 'core-extend' ),
				'param_name' => 'bullets',
				'value' => array(esc_html__( 'Yes, please', 'core-extend' ) => 'paging-false')
			),				
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)
		),
		'custom_markup' => '
			<h4>Testimonials/Quotes</h4>
			<div class="wpb_tetimonial_holder wpb_holder clearfix vc_container_for_children">
			%content%
			</div>
			<div class="tab_controls">
			    <a class="add_tab" title="' . esc_html__( 'Add Testimonials', 'core-extend' ) . '"><span class="vc_icon"></span> <span class="tab-label">' . esc_html__( 'Add testimonial', 'core-extend' ) . '</span></a>
			</div>
		',
	  'default_content' => '
	  [mnky_testimonial name="John Doe" position="Designer"]I am tetimonial text. Click edit button to change this text.[/mnky_testimonial]
	  [mnky_testimonial name="Nathan Benson" position="Developer"]I am tetimonial text. Click edit button to change this text.[/mnky_testimonial]
	  ',
	  'js_view' => 'VcTestimonialsView'
	  
	) );


	// Testimonial
	vc_map( array(
		'name' => esc_html__('Testimonial', 'core-extend'),
		'base' => 'mnky_testimonial',
		'is_container' => true,
		'content_element' => false,
		'as_child' => array('only' => 'mnky_testimonial_slider'),
		'category' => esc_html__('Premium', 'core-extend'),
		'params' => array(
			array(
				'type' => 'attach_image',
				'heading' => esc_html__('Author image', 'core-extend'),
				'param_name' => 'img_url'
			),		
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Author name', 'core-extend'),
				'param_name' => 'name',
				'value' => 'John Doe',
				'holder' => 'h3',
				'description' => esc_html__('', 'core-extend')
			),		
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Author info', 'core-extend'),
				'param_name' => 'author_dec',
				'value' => 'Designer',
				'description' => esc_html__('e.g. "Senior Designer" or  "Happy Customer"', 'core-extend')
			),
			array(
				'type' => 'textarea',
				'class' => 'quote_text',
				'heading' => esc_html__('Testimonial/Quote text', 'core-extend'),
				'holder' => 'div',
				'param_name' => 'content',
				'value' => esc_html__('I am tetimonial text.', 'core-extend')
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)
		)
	) );


	// List item
	vc_map( array(
		'name' => esc_html__('List Item', 'core-extend'),
		'base' => 'mnky_list_item',
		'icon' => 'icon-mnky_list', 
		'is_container' => false,
		'description' => esc_html__('List with custom icon', 'core-extend'),
		'category' => esc_html__('Premium', 'core-extend'),
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Icon library', 'core-extend' ),
				'value' => array(
					esc_html__( 'Font Awesome', 'core-extend' ) => 'fontawesome',
					esc_html__( 'Open Iconic', 'core-extend' ) => 'openiconic',
					esc_html__( 'Typicons', 'core-extend' ) => 'typicons',
					esc_html__( 'Entypo', 'core-extend' ) => 'entypo',
					esc_html__( 'Linecons', 'core-extend' ) => 'linecons'
				),
				'param_name' => 'icon_type',
				'admin_label' => true,
				'description' => esc_html__( 'Select icon library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_fontawesome',
				'value' => 'fa fa-info-circle',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'fontawesome',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_openiconic',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'openiconic',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'openiconic',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_typicons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'typicons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
				'element' => 'icon_type',
				'value' => 'typicons',
			),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_entypo',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'entypo',
					'iconsPerPage' => 300, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'entypo',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_linecons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'linecons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'linecons',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('List icon color', 'core-extend'),
				'param_name' => 'icon_color',
				'description' => esc_html__('Leave empty to use theme accent color.', 'core-extend')
			),
			array(
				'type' => 'vc_link',
				'heading' => esc_html__('URL (Link)', 'core-extend'),
				'param_name' => 'link',
			),
			array(
				'type' => 'textarea',
				'heading' => esc_html__('List item text', 'core-extend'),
				'param_name' => 'content',
				'value' => esc_html__('I am a list item', 'core-extend'),
				'admin_label' => true,
			),
			$add_css_animation,
			$add_css_animation_delay,
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)		
		),
		'js_view' => 'VcIconView'
	) );


	// Service
	vc_map( array(
		'name' => esc_html__('Service', 'core-extend'),
		'base' => 'mnky_service',
		'icon' => 'icon-mnky_service',
		'category' => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Service info with custom icon', 'core-extend'),
		'params' => array(
						array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Icon library', 'core-extend' ),
				'value' => array(
					esc_html__( 'Font Awesome', 'core-extend' ) => 'fontawesome',
					esc_html__( 'Open Iconic', 'core-extend' ) => 'openiconic',
					esc_html__( 'Typicons', 'core-extend' ) => 'typicons',
					esc_html__( 'Entypo', 'core-extend' ) => 'entypo',
					esc_html__( 'Linecons', 'core-extend' ) => 'linecons'
				),
				'param_name' => 'icon_type',
				'admin_label' => true,
				'description' => esc_html__( 'Select icon library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_fontawesome',
				'value' => 'fa fa-info-circle',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'fontawesome',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_openiconic',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'openiconic',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'openiconic',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_typicons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'typicons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
				'element' => 'icon_type',
				'value' => 'typicons',
			),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_entypo',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'entypo',
					'iconsPerPage' => 300, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'entypo',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'core-extend' ),
				'param_name' => 'icon_linecons',
				'settings' => array(
					'emptyIcon' => false, // default true, display an 'EMPTY' icon?
					'type' => 'linecons',
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'linecons',
				),
				'description' => esc_html__( 'Select icon from library.', 'core-extend' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Title', 'core-extend'),
				'param_name' => 'title',
				'admin_label' => true,
				'value' => esc_html__('Your service title', 'core-extend')
			),
			array(
				'type' => 'textarea',
				'heading' => esc_html__('Service description', 'core-extend'),
				'param_name' => 'content',
				'value' => esc_html__('I am service box text. Click edit button to change this text.', 'core-extend')
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Layout style', 'core-extend'),
				'param_name' => 'position',
				'value' => array(esc_html__('Left', 'core-extend') => '', esc_html__('Right', 'core-extend') => 'sb_right', esc_html__('Center', 'core-extend') => 'sb_center')
			),			
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icon color', 'core-extend'),
				'param_name' => 'icon_color',
				'description' => esc_html__('Leave empty to use theme accent color.', 'core-extend')
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Title color', 'core-extend'),
				'param_name' => 'heading_color',
				'description' => esc_html__('Leave empty to use default heading color', 'core-extend')
			),
			array(
				'type' => 'vc_link',
				'heading' => esc_html__('URL (Link)', 'core-extend'),
				'param_name' => 'link',
			),
			$add_css_animation,
			$add_css_animation_delay,
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)
		),
		 'js_view' => 'VcIconView'

	) );
	
	
	// Counter
	vc_map( array(
		'name'		=> esc_html__('Counter', 'core-extend'),
		'base'		=> 'mnky_counter',
		'icon'		=> 'icon-mnky_counter',
		'category'  => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Animated numerical data', 'core-extend'),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Value you want to begin at', 'core-extend'),
				'param_name' => 'from',
				'value' => '0',
				'description' => esc_html__('', 'core-extend')
			),			
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Value you want to arrive at', 'core-extend'),
				'param_name' => 'to',
				'value' => '2000',
				'admin_label' => true,
				'description' => esc_html__('', 'core-extend')
			),			
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Duration in milliseconds', 'core-extend'),
				'param_name' => 'speed',
				'value' => '1000',
				'description' => esc_html__('', 'core-extend')
			),			
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Interval', 'core-extend'),
				'param_name' => 'interval',
				'value' => '10',
				'description' => esc_html__('How often the element should be updated', 'core-extend')
			),	
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Decimals', 'core-extend'),
				'param_name' => 'decimals',
				'value' => '0',
				'description' => esc_html__('The number of decimal places to show', 'core-extend')
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)
		)

	) );

	
	// Countdown
	vc_map( array(
		'name'		=> esc_html__('Countdown', 'core-extend'),
		'base'		=> 'mnky_countdown',
		'icon'		=> 'icon-mnky_countdown',
		'category'  => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Count down to specific date', 'core-extend'),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Date', 'core-extend'),
				'param_name' => 'date',
				'value' => '2018/04/16',
				'admin_label' => true,
				'description' => esc_html__('Enter date to counts down seconds, minutes, hours and days. e.g YYYY/MM/DD', 'core-extend')
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Digit font size', 'core-extend'),
				'param_name' => 'font',
				'value' => '100px',
				'description' => esc_html__('', 'core-extend')
			),			
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Divider color', 'core-extend' ),
				'param_name' => 'border',
				'value' => '#cccccc',
				'description' => esc_html__( '', 'core-extend' ),
			),	
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend')
			)
		)

	) );
	
	
	// Pricing box
	vc_map( array(
	  'name'		=> esc_html__('Pricing', 'core-extend'),
	  'base'		=> 'mnky_pricing_box',
	  'icon'		=> 'icon-mnky_pricing_box',
	  'allowed_container_element' => false,
	  'is_container' => true,
	  'category'  => esc_html__('Premium', 'core-extend'),
	  'description' => esc_html__('Styled pricing boxes', 'core-extend'),
	  'params' => array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Title', 'core-extend' ),
				'param_name' => 'title',
				'holder' => 'h4',
				'description' => esc_html__( 'Give your plan a title.', 'core-extend' ),
				'value' => esc_html__( 'Starter Pack', 'core-extend' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Currency', 'core-extend' ),
				'param_name' => 'currency',
				'holder' => 'span',
				'description' => esc_html__( 'Enter currency symbol or text, e.g., $ or USD.', 'core-extend' ),
				'value' => esc_html__( '$', 'core-extend' )
			),	
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Price', 'core-extend' ),
				'param_name' => 'price',
				'holder' => 'span',
				'description' => esc_html__( 'Set the price for this plan.', 'core-extend' ),
				'value' => esc_html__( '10', 'core-extend' )
			),						
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Time', 'core-extend' ),
				'param_name' => 'time',
				'holder' => 'span',
				'description' => esc_html__( 'Choose time span for you plan, e.g., /mo, month or /yr.', 'core-extend' ),
				'value' => esc_html__( '/mo', 'core-extend' )
			),				
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Meta', 'core-extend' ),
				'param_name' => 'meta',
				'holder' => 'span',
				'description' => esc_html__( 'A short desciption or slogan for the plan.', 'core-extend' ),
				'value' => esc_html__( 'Great for small business', 'core-extend' )
			),
			array(
				'type' => 'vc_link',
				'heading' => esc_html__( 'Add URL to the whole box (optional)', 'core-extend' ),
				'param_name' => 'link',
			),
			$add_css_animation,
			$add_css_animation_delay,
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'core-extend' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend' )
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Select style', 'core-extend' ),
				'param_name' => 'box_style',
				'value' => array('Minimal' => 'box-style-1', 'Strict' => 'box-style-2', 'Header' => 'box-style-3', 'Circle' => 'box-style-4'),
				'description' => esc_html__( 'Choose style for this pricing box.', 'core-extend' ),
				'group' => esc_html__('Design', 'core-extend')
			),	
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Background color', 'core-extend' ),
				'param_name' => 'bg_color',
				'value' => '#FFFFFF',
				'description' => esc_html__( 'Set background color for pricing box body.', 'core-extend' ),
				'group' => esc_html__('Design', 'core-extend')
			),					
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Text color', 'core-extend' ),
				'param_name' => 'color',
				'value' => '#5E5E5E',
				'description' => esc_html__( 'Set text color for pricing box content.', 'core-extend' ),
				'group' => esc_html__('Design', 'core-extend')
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Header background color', 'core-extend' ),
				'param_name' => 'header_bg',
				'value' => '#5E5E5E',
				'group' => esc_html__('Design', 'core-extend'),
				'description' =>  esc_html__( 'Set background color for box header.', 'core-extend' ),
				'dependency' => array('element' => 'box_style', 'value' => array('box-style-2', 'box-style-3', 'box-style-4') )
			),			
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Header text color', 'core-extend' ),
				'param_name' => 'header_color',
				'value' => '#FFFFFF',
				'group' => esc_html__('Design', 'core-extend'),
				'description' => esc_html__( 'Set color for text inside box header area.', 'core-extend' ),
				'dependency' => array('element' => 'box_style', 'value' => array('box-style-2', 'box-style-3', 'box-style-4') )
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Border color (optional)', 'core-extend' ),
				'param_name' => 'border_color',
				'description' => esc_html__( 'Add border to whole box. Leave empty for no border.', 'core-extend' ),
				'group' => esc_html__('Design', 'core-extend')
			),			
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Add badge?', 'core-extend' ),
				'param_name' => 'add_badge',
				'group' => esc_html__('Badge', 'core-extend'),
				'description' => 'Adds a nice badge to your pricing box.',
				'value' => array(esc_html__( 'Yes, please', 'core-extend' ) => 'on')
			),			
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Badge background color', 'core-extend' ),
				'param_name' => 'badge_bg',
				'group' => esc_html__('Badge', 'core-extend'),
				'description' => esc_html__( 'Set a background color for the badge.', 'core-extend' ),
				'dependency' => array('element' => 'add_badge', 'not_empty' => true)
			),			
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Badge text color', 'core-extend' ),
				'param_name' => 'badge_color',
				'group' => esc_html__('Badge', 'core-extend'),
				'value' => '#fff',
				'description' => esc_html__( 'Set a text color for the badge.', 'core-extend' ),
				'dependency' => array('element' => 'add_badge', 'not_empty' => true)
			),				
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Badge text', 'core-extend' ),
				'param_name' => 'badge_text',
				'value' => esc_html__( 'Best Offer', 'core-extend' ),
				'group' => esc_html__('Badge', 'core-extend'),
				'description' => esc_html__( 'What do you want your badge to say? , E.g., 50% Off or Save 30%.', 'core-extend' ),
				'dependency' => array('element' => 'add_badge', 'not_empty' => true)
			),			
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Hover effect', 'core-extend' ),
				'param_name' => 'hover_effect',
				'value' => array('None' => '', 'Emphasize' => 'box-effect-1', 'Add Shadow' => 'box-effect-2', 'Emphasize + Add Shadow' => 'box-effect-3'),
				'description' => esc_html__( 'Enable and choose a hover effect.', 'core-extend' ),
				'group' => esc_html__('Effect', 'core-extend')
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Always active? (by default only on hover state)', 'core-extend' ),
				'param_name' => 'effect_active',
				'group' => esc_html__('Effect', 'core-extend'),
				'value' => array(esc_html__( 'Yes, please', 'core-extend' ) => 'box-effect-active'),
				'description' => esc_html__( 'Use this option, if you want to accentuate one of the boxes.', 'core-extend' ),
			)			
		),
		'js_view' => 'VcPricingView'
	) );	
	

	// Posts
	$author_list = array();
	$author_list['Select author...'] = 'all';
	$blogusers = get_users( array( 'fields' => array( 'display_name', 'ID' ) ) );
	foreach ( $blogusers as $user ) {
		$author_list[$user->display_name] = $user->ID;
	}
	
	$animation_group = array( 'group' => esc_html__('Layout', 'core-extend') );
	$add_css_animation_posts = array_merge($add_css_animation, $animation_group );
	$add_css_animation_delay_posts = array_merge($add_css_animation_delay, $animation_group );

	vc_map( array(
		'name'		=> esc_html__('Article Block', 'core-extend'),
		'base'		=> 'mnky_posts',
		'icon'		=> 'icon-mnky_posts',
		'class'		=> 'mnky-get-posts',
		'front_enqueue_css' => MNKY_PLUGIN_URL . 'assets/css/core-extend-frontend.css',
		'category'  => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Display selected posts', 'core-extend'),
		'show_settings_on_create' => true,
		'params' => array(			
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Layout', 'core-extend' ),
				'param_name' => 'layout',
				'value' => array(
					'Centered partial cover' => '1',
					'Right partial cover' => '2',
					'Left partial cover' => '3',
					'List style' => '4',
					'Image above content' => '5',
					'Image on the left' => '6',
					'Image on the left (boxed)' => '7',
					'Title above image' => '8',
					'Image overlay' => '9',
					'Breaking news' => '10'
				),
				'group' => esc_html__('Layout', 'core-extend'),
				'description' => esc_html__( '', 'core-extend' ),
				'admin_label' => true
			),
			array(
			  'type' => 'colorpicker',
			  'heading' => esc_html__('Choose background color for breaking news', 'core-extend'),
			  'param_name' => 'breaking_bg',
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('10') )
			),	
			array(
			  'type' => 'colorpicker',
			  'heading' => esc_html__('Choose color for breaking news placeholder', 'core-extend'),
			  'param_name' => 'breaking_ph_color',
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('10') )
			),	
			array(
			  'type' => 'colorpicker',
			  'heading' => esc_html__('Choose color for breaking news link', 'core-extend'),
			  'param_name' => 'breaking_color',
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('10') )
			),	
			array(
			  'type' => 'textfield',
			  'heading' => esc_html__('Optional placeholder before news title, e.g., "Breaking News:"', 'core-extend'),
			  'param_name' => 'breaking_text',
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('10') )
			),
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Remove paddings', 'core-extend'),
			  'param_name' => 'breaking_paddings',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'yes'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('10') )
			),				
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Do not duplicate posts', 'core-extend'),
			  'param_name' => 'no_dublicate',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'yes'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'description' => esc_html__( 'Do not include posts that are already shown before in other post section.', 'js_composer' ),
			  'admin_label' => true
			),				
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Allow to duplicate posts from this section', 'core-extend'),
			  'param_name' => 'allow_dublicate',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'yes'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'description' => esc_html__( 'Other post sections below will include posts from THIS section even if "Do not duplicate posts" will be active.', 'js_composer' ),
			  'admin_label' => true
			),					
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Display posts in one column', 'core-extend'),
			  'param_name' => 'post_on_column',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'on'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3',) )
			),
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide review rating', 'core-extend'),
			  'param_name' => 'rating_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '4', '5', '6', '7', '8', '9') )
			),	
			array(
			  'type' => 'colorpicker',
			  'heading' => esc_html__('Rating stars color', 'core-extend'),
			  'param_name' => 'rating_color',
			  'group' => esc_html__('Design', 'core-extend'),
			  'description' => esc_html__('Choose custom color for rating stars.', 'core-extend')
			),					
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide post category', 'core-extend'),
			  'param_name' => 'cat_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '4', '9') )
			),
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide post author', 'core-extend'),
			  'param_name' => 'author_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '4', '5', '6', '7', '8', '9') )
			),				
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide post date', 'core-extend'),
			  'param_name' => 'date_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			  'group' => esc_html__('Layout', 'core-extend'),
			   'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '4', '5', '6', '7', '8', '9') )
			),			
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide post comment count', 'core-extend'),
			  'param_name' => 'comments_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '4', '5', '6', '7', '8') )
			),
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide post views count', 'core-extend'),
			  'param_name' => 'views_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '4', '5', '6', '7', '8') )
			),			
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide post excerpt', 'core-extend'),
			  'param_name' => 'excerpt_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '8', '9') )
			),	
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Show post excerpt', 'core-extend'),
			  'param_name' => 'excerpt_show',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'on'),
			  'group' => esc_html__('Layout', 'core-extend'),
			  'dependency' => array('element' => 'layout', 'value' => array('5') )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Posts per page', 'js_composer' ),
				'param_name' => 'posts_per_page',
				'value' => '4',
				'description' => esc_html__( 'Number of post to show per page (-1 to show all posts)', 'js_composer' ),
				'group' => esc_html__('Parameters', 'core-extend')
			),			
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Limit posts by time', 'js_composer' ),
				'param_name' => 'time_limit',
				'value' => array('No Limit' => '', 'Today' => 'today', 'This Week' => 'week', 'This Month' => 'month'),
				'group' => esc_html__('Parameters', 'core-extend')
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order', 'core-extend' ),
				'param_name' => 'order',
				'value' => array('Descending' => 'DESC', 'Ascending' => 'ASC'),
				'group' => esc_html__('Parameters', 'core-extend')
			),			
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order by', 'core-extend' ),
				'param_name' => 'orderby',
				'value' => array('By date' => 'date', 'By post views' => 'meta_value_num', 'By last modified date' => 'modified', 'By number of comments' => 'comment_count', 'Random order' => 'rand', 'By title' => 'title', 'By ID' => 'ID', 'By author' => 'author', ' By post slug' => 'name', 'By post type' => 'type', 'By post/page parent id' => 'parent', 'No order' => 'none' ),
				'group' => esc_html__('Parameters', 'core-extend')
			),
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Show pagination', 'core-extend'),
			  'param_name' => 'pagination',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'on'),
			  'group' => esc_html__('Parameters', 'core-extend')
			),	
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Offset', 'js_composer' ),
				'param_name' => 'offset',
				'value' => '0',
				'description' => esc_html__( 'Number of post to displace or pass over', 'js_composer' ),
				'group' => esc_html__('Parameters', 'core-extend')
			),				
			array(
				'type' => 'mnky_preview',
				'heading' => esc_html__('Filter results', 'core-extend'),
				'param_name' => 'taxonomy',
				'group' => esc_html__('Filter', 'core-extend'),
				'holder' => 'div',
				'value' => array('All posts' => 'all_posts', 'By Category' => 'category', 'By Tags' => 'post_tag', 'By Author' => 'author'),
			),	
			array(
				'type' => 'mnky_preview',
				'heading' => esc_html__('Operator', 'core-extend'),
				'param_name' => 'tax_operator',
				'group' => esc_html__('Filter', 'core-extend'),
				'value' => array('IN' => 'IN', 'NOT IN' => 'NOT IN', 'AND' => 'AND'),
				'dependency' => array('element' => 'taxonomy', 'value' => array('category', 'post_tag')),
				'description' => sprintf (esc_html_x( 'IN = Posts must be %2$s at least in ONE %3$s of selected categories or tags %1$s NOT IN = Excludes posts that are in selected categories or tags %1$s AND = Post must be %2$s in ALL %3$s selected categories or tags', '%1$s stands for line break, %2$s and %3$s stand for <strong> tags.' ,'core-extend' ), '<br/>', '<strong>', '</strong>')
			),	
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Select author', 'core-extend' ),
				'param_name' => 'author',
				'value' => $author_list,
				'admin_label' => true,
				'group' => esc_html__('Filter', 'core-extend'),
				'dependency' => array('element' => 'taxonomy', 'value' => array('author'))
			),			
			array(
				'type' => 'mnky_cat',
				'heading' => esc_html__( 'Categories', 'core-extend' ),
				'param_name' => 'category',
				'description' => '',
				'group' => esc_html__('Filter', 'core-extend'),
				'dependency' => array('element' => 'taxonomy', 'value' => array('category'))
			),			
			array(
				'type' => 'mnky_tags',
				'heading' => esc_html__( 'Tags', 'core-extend' ),
				'param_name' => 'tag',
				'description' => '',
				'group' => esc_html__('Filter', 'core-extend'),
				'dependency' => array('element' => 'taxonomy', 'value' => array('post_tag'))
			),		
			array(
				'type' => 'mnky_preview',
				'heading' => esc_html__('Refine results', 'core-extend'),
				'param_name' => 'taxonomy_2',
				'group' => esc_html__('Filter', 'core-extend'),
				'value' => array('None' => 'none', 'By Categories' => 'category', 'By Tags' => 'post_tag'),
				'dependency' => array('element' => 'taxonomy', 'value' => array('category', 'post_tag'))
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Taxonomy relation', 'core-extend' ),
				'param_name' => 'tax_relation',
				'value' => array('OR' => 'OR', 'AND' => 'AND'),
				'description' => esc_html__( 'The logical relationship between each taxonomy.', 'core-extend' ),
				'group' => esc_html__('Filter', 'core-extend'),
				'dependency' => Array('element' => 'taxonomy_2', 'value' => array('category', 'post_tag'))
			),		
			array(
				'type' => 'mnky_preview',
				'heading' => esc_html__('Operator 2', 'core-extend'),
				'param_name' => 'tax_operator_2',
				'group' => esc_html__('Filter', 'core-extend'),
				'value' => array('IN' => 'IN', 'NOT IN' => 'NOT IN', 'AND' => 'AND'),
				'dependency' => array('element' => 'taxonomy_2', 'value' => array('category', 'post_tag'))
			),						
			array(
				'type' => 'mnky_cat',
				'heading' => esc_html__( 'Categories 2', 'core-extend' ),
				'param_name' => 'category_2',
				'description' => '',
				'group' => esc_html__('Filter', 'core-extend'),
				'dependency' => array('element' => 'taxonomy_2', 'value' => array('category'))
			),			
			array(
				'type' => 'mnky_tags',
				'heading' => esc_html__( 'Tags 2', 'core-extend' ),
				'param_name' => 'tag_2',
				'description' => '',
				'group' => esc_html__('Filter', 'core-extend'),
				'dependency' => array('element' => 'taxonomy_2', 'value' => array('post_tag'))
			),				
			array(
				'type' => 'mnky_info',
				'param_name' => 'image_enabled',
				'description' => esc_html__( 'Image will always be stretched to fit container, but you can use these options to control aspect ratio and image size.', 'core-extend' ),
				'group' => esc_html__('Image', 'core-extend'),
				'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '5', '8', '9') )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image width', 'core-extend' ),
				'param_name' => 'width',
				'description' => '',
				'group' => esc_html__('Image', 'core-extend'),
				'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '5', '8', '9') ),
				'description' => esc_html__( 'Specified size is converted to pixels, use plain numbers, e.g., "350"', 'core-extend' )
			),			
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image height', 'core-extend' ),
				'param_name' => 'height',
				'description' => '',
				'group' => esc_html__('Image', 'core-extend'),
				'dependency' => array('element' => 'layout', 'value' => array('1', '2', '3', '5', '8', '9') ),
				'description' => esc_html__( 'Specified size is converted to pixels, use plain numbers, e.g., "350"', 'core-extend' )
			),
			array(
				'type' => 'mnky_info',
				'param_name' => 'image_disabled',
				'description' => esc_html__( 'Image dimensions are fixed or images are not displayed for this style.', 'core-extend' ),
				'group' => esc_html__('Image', 'core-extend'),
				'dependency' => array('element' => 'layout', 'value' => array('4', '6', '7') )
			),
			$add_css_animation_posts,
			$add_css_animation_delay_posts,
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'core-extend' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend' ),
				'group' => esc_html__('Layout', 'core-extend')
			)
		),
		'js_view' => 'MNKYPostView'

	) );


	// Post grid
	vc_map( array(
		'name'		=> esc_html__('Post Grid', 'core-extend'),
		'base'		=> 'mnky_posts_grid',
		'icon'		=> 'icon-mnky_posts',
		'class'		=> 'mnky-get-posts',
		'category'  => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Display latest posts in grid with background image', 'core-extend'),
		'show_settings_on_create' => true,
		'params' => array(					
			array(
				'type' => 'mnky_preview',
				'heading' => esc_html__( 'Grid layout', 'core-extend' ),
				'param_name' => 'grid_layout',
				'admin_label' => true,
				'value' => array('Layout 1' => 'mpg-layout-1', 'Layout  2' => 'mpg-layout-2', 'Layout  3' => 'mpg-layout-3', 'Layout  4' => 'mpg-layout-4', 'Layout  5' => 'mpg-layout-5', 'Layout 6' => 'mpg-layout-6', 'Layout 7' => 'mpg-layout-7'),
			),			
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Grid height', 'core-extend' ),
				'param_name' => 'grid_height',
				'value' => array('420px' => 'mpg-height-420', '500px' => 'mpg-height-500'),
			),
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Do not dublicate posts', 'core-extend'),
			  'param_name' => 'no_dublicate',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'yes'),
			  'description' => esc_html__( 'Do not include posts that are already shown before in other post section.', 'js_composer' ),
			  'admin_label' => true
			),				
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Allow to dublicate posts from this section', 'core-extend'),
			  'param_name' => 'allow_dublicate',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'yes'),
			  'description' => esc_html__( 'Other post sections below will include posts from THIS section even if "Do not dublicate posts" will be active.', 'js_composer' ),
			  'admin_label' => true
			),	
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order', 'core-extend' ),
				'param_name' => 'order',
				'value' => array(esc_html__('Descending', 'core-extend') => 'DESC', esc_html__('Ascending', 'core-extend') => 'ASC'),
				'group' => esc_html__('Parameters', 'core-extend')
			),			
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order by', 'core-extend' ),
				'param_name' => 'orderby',
				'value' => array(esc_html__('By date', 'core-extend' ) => 'date', esc_html__('By post views', 'core-extend' ) => 'meta_value_num', esc_html__('By last modified date', 'core-extend' ) => 'modified', esc_html__('By number of comments', 'core-extend' ) => 'comment_count', esc_html__('Random order', 'core-extend' ) => 'rand', esc_html__('By title', 'core-extend' ) => 'title', esc_html__('By ID', 'core-extend' ) => 'ID', esc_html__('By author', 'core-extend' ) => 'author', esc_html__('By post slug', 'core-extend' ) => 'name', esc_html__('By post/page parent id', 'core-extend' ) => 'parent', esc_html__('No order', 'core-extend' ) => 'none' ),
				'group' => esc_html__('Parameters', 'core-extend')
			),
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide post category', 'core-extend'),
			  'param_name' => 'cat_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			),				
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide post excerpt', 'core-extend'),
			  'param_name' => 'excerpt_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			),				
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Category 1 (optional)', 'core-extend' ),
				'param_name' => 'category_1',
				'description' => sprintf (esc_html_x( 'Choose category to display in the first box. Use category %2$s slug name %3$s. %1$s Leave empty to display latest posts from all categories.', '%1$s stands for line break, %2$s and %3$s stand for <strong> tags.' ,'core-extend' ), '<br/>', '<strong>', '</strong>')
			),				
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Category 2 (optional)', 'core-extend' ),
				'param_name' => 'category_2',
				'description' => sprintf (esc_html_x( 'Choose category to display in the second box. Use category %2$s slug name %3$s. %1$s Leave empty to display latest posts from all categories.', '%1$s stands for line break, %2$s and %3$s stand for <strong> tags.' ,'core-extend' ), '<br/>', '<strong>', '</strong>')
			),			
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Category 3 (optional)', 'core-extend' ),
				'param_name' => 'category_3',
				'description' => sprintf (esc_html_x( 'Choose category to display in the third box. Use category %2$s slug name %3$s. %1$s Leave empty to display latest posts from all categories.', '%1$s stands for line break, %2$s and %3$s stand for <strong> tags.' ,'core-extend' ), '<br/>', '<strong>', '</strong>')
			),				
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Category 4 (optional)', 'core-extend' ),
				'param_name' => 'category_4',
				'dependency' => Array( 'element' => 'grid_layout', 'value' => array('mpg-layout-1', 'mpg-layout-2', 'mpg-layout-3', 'mpg-layout-6', 'mpg-layout-7') ), 
				'description' => sprintf (esc_html_x( 'Choose category to display in the fourth box. Use category %2$s slug name %3$s. %1$s Leave empty to display latest posts from all categories.', '%1$s stands for line break, %2$s and %3$s stand for <strong> tags.' ,'core-extend' ), '<br/>', '<strong>', '</strong>')
			),				
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Category 5 (optional)', 'core-extend' ),
				'param_name' => 'category_5',
				'dependency' => Array( 'element' => 'grid_layout', 'value' => array('mpg-layout-1', 'mpg-layout-3') ),
				'description' => sprintf (esc_html_x( 'Choose category to display in the fifth box. Use category %2$s slug name %3$s. %1$s Leave empty to display latest posts from all categories.', '%1$s stands for line break, %2$s and %3$s stand for <strong> tags.' ,'core-extend' ), '<br/>', '<strong>', '</strong>')
			),				
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Category 6 (optional)', 'core-extend' ),
				'param_name' => 'category_6',
				'dependency' => Array( 'element' => 'grid_layout', 'value' => array('mpg-layout-3') ),
				'description' => sprintf (esc_html_x( 'Choose category to display in the sixth box. Use category %2$s slug name %3$s. %1$s Leave empty to display latest posts from all categories.', '%1$s stands for line break, %2$s and %3$s stand for <strong> tags.' ,'core-extend' ), '<br/>', '<strong>', '</strong>')
			),	
			$add_css_animation,
			$add_css_animation_delay,			
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Extra class name', 'core-extend' ),
				'param_name' => 'el_class',
				'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'core-extend' )
			)
		)

	) );


	// Related posts
	vc_map( array(
		'name'		=> esc_html__('Related posts', 'core-extend'),
		'base'		=> 'mnky_related_posts',
		'icon'		=> 'icon-mnky_posts',
		'category'  => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Posts with similar categories or tags', 'core-extend'),
		'show_settings_on_create' => true,
		'params' => array(
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Display only category related posts (no tags)', 'core-extend' ),
				'param_name' => 'no_tags',
				'value' => array(esc_html__('On', 'core-extend') => 'on'),				
				'description' => esc_html__( '', 'core-extend' )
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Display only tag related posts (no category)', 'core-extend' ),
				'param_name' => 'no_categories',
				'value' => array(esc_html__('On', 'core-extend') => 'on'),				
				'description' => esc_html__( '', 'core-extend' )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Display related posts for specific post ID (optional)', 'core-extend' ),
				'param_name' => 'id',
				'description' => esc_html__( '', 'core-extend' )
			),			
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'How many posts to display', 'core-extend' ),
				'param_name' => 'num',
				'value' => '4',
				'description' => esc_html__( '', 'core-extend' )
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order', 'core-extend' ),
				'param_name' => 'order',
				'value' => array('Descending' => 'DESC', 'Ascending' => 'ASC')
			),			
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order by', 'core-extend' ),
				'param_name' => 'orderby',
				'value' => array('By date' => 'date', 'By post views' => 'meta_value_num', 'By last modified date' => 'modified', 'By number of comments' => 'comment_count', 'Random order' => 'rand', 'By title' => 'title', 'By ID' => 'ID', 'By author' => 'author', ' By post slug' => 'name', 'By post type' => 'type', 'By post/page parent id' => 'parent', 'No order' => 'none' )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Offset', 'js_composer' ),
				'param_name' => 'offset',
				'value' => '0',
				'description' => esc_html__( 'Number of post to displace or pass over.', 'js_composer' )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image width', 'core-extend' ),
				'param_name' => 'width',
				'description' => esc_html__( 'Specified size is converted to pixels, use plain numbers, e.g., "350".', 'core-extend' )
			),			
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Image height', 'core-extend' ),
				'param_name' => 'height',
				'description' => esc_html__( 'Specified size is converted to pixels, use plain numbers, e.g., "350".', 'core-extend' )
			)
		)
	) );	

	
	// Menu posts
	vc_map( array(
		'name'		=> esc_html__('Menu posts', 'core-extend'),
		'base'		=> 'mnky_menu_posts',
		'icon'		=> 'icon-mnky_menu_posts',
		'category'  => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Posts', 'core-extend'),
		'show_settings_on_create' => false,
		'content_element' => false,
		'params' => array(
		)
	) );

	
	// Article info
	vc_map( array(
		'name' => esc_html__('Article Info', 'core-extend'),
		'base' => 'mnky_article_info',
		'icon' => 'icon-mnky_article_info',
		'category' => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Displays current article header', 'core-extend'),
		'params' => array(
			$add_css_animation,
			$add_css_animation_delay,
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Heading font size', 'core-extend'),
				'param_name' => 'font_size',
				'description' => esc_html__('Choose font size for the heading, enter value in pixels, e.g., 30px.', 'core-extend')
			),
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide labels', 'core-extend'),
			  'param_name' => 'label_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			),		
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Hide post meta', 'core-extend'),
			  'param_name' => 'meta_hide',
			  'value' => array(esc_html__('Yes, please!', 'core-extend') => 'off'),
			),					
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file. Use class "light" for white text and links.', 'core-extend')
			)
		)
	) );	

	
	// Ads
	$ads_title = array('None' => '');
	$args = array( 'post_type' => 'ads', 'posts_per_page' => -1, );
	$loop = new WP_Query( $args );

	if( $loop->have_posts() ){
		while( $loop->have_posts() ): $loop->the_post();
			$ads_title[get_the_title()] = get_the_ID();
		endwhile;
	} else {
		$ads_title = array('No ads have been found!' => '');
	}
	wp_reset_postdata();
	
	$ads_category = array('None' => '');
	$terms = get_terms( 'ads_category', 'hide_empty=0' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$ads_category[$term->name] = $term->slug;
		}
	} else {
		$ads_category = array('No categories have been found!' => '');
	}

	vc_map( array(
		'name'		=> esc_html__('Ads', 'core-extend'),
		'base'		=> 'mnky_ads',
		'icon'		=> 'icon-mnky_ads',
		'category'  => esc_html__('Premium', 'core-extend'),
		'description' => esc_html__('Add ads to the content', 'core-extend'),
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Specific ad', 'core-extend' ),
				'param_name' => 'id',
				'admin_label' => true,
				'value' => $ads_title,
			),		
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Ads by category', 'core-extend' ),
				'param_name' => 'category',
				'admin_label' => true,
				'value' => $ads_category,
				'dependency' => array('element' => 'id', 'value' => array('') )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'How many ads to show in column', 'core-extend' ),
				'param_name' => 'posts_per_page',
				'value' => '1',
				'dependency' => array('element' => 'id', 'value' => array('') )
			),			
			array(
			  'type' => 'checkbox',
			  'heading' => esc_html__('Rotate ads randomly', 'core-extend'),
			  'param_name' => 'rotate_ads',
			  'value' => array(esc_html__('On', 'core-extend') => 'on'),
			  'dependency' => array('element' => 'id', 'value' => array('') )
			),
			$add_css_animation,
			$add_css_animation_delay,
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Extra class name', 'core-extend'),
				'param_name' => 'el_class',
				'description' => esc_html__('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file. <br> Use class "light" for white text and links.', 'core-extend')
			)			
		)
	) );		


	
}