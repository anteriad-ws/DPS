<?php
add_action( 'vc_load_default_templates_action','mnky_custom_template_for_vc' ); // Hook in
 
function mnky_custom_template_for_vc() {
	
	// FULL NEWS HOMEPAGE
    $data               = array(); // Create new array
    $data['name']       = __( '01. Full news homepage', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
    $data['content']    = <<<CONTENT
        [vc_row][vc_column][vc_empty_space height="30px"][mnky_posts layout="10" posts_per_page="1" breaking_bg="#f7f7f7" breaking_ph_color="#ea2a2a" breaking_color="#222222" breaking_text="Hot Topic:"][vc_empty_space height="30px"][mnky_posts_grid grid_height="mpg-height-500" excerpt_hide="off" css_animation="appear"][vc_empty_space height="40px"][mnky_ads][vc_empty_space height="30px"][mnky_heading title="Top News"][vc_empty_space height="20px"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][mnky_posts no_dublicate="yes" comments_hide="off" excerpt_hide="off" posts_per_page="5" offset="2"][vc_empty_space height="20px"][mnky_heading title="Lifestyle" line_part_color="#9548e2"][vc_empty_space height="20px"][vc_row_inner][vc_column_inner width="1/2"][mnky_posts layout="5" no_dublicate="yes" posts_per_page="2"][/vc_column_inner][vc_column_inner width="1/2"][mnky_posts layout="5" no_dublicate="yes" posts_per_page="2"][/vc_column_inner][/vc_row_inner][vc_empty_space height="60px"][/vc_column][vc_column width="1/3" el_class="sticky-container "][vc_column_text]
		<h4><strong>Most Popular Right Now</strong></h4>
		[/vc_column_text][vc_empty_space height="40px"][mnky_posts layout="6" allow_dublicate="yes" author_hide="off" orderby="meta_value_num"][vc_empty_space height="60px"][mnky_ads][vc_empty_space height="20px"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1445336271411{background-color: #f7f7f7 !important;}"][vc_column][vc_empty_space height="60px"][vc_row_inner][vc_column_inner width="1/3"][mnky_heading title="Business" line_part_color="#e74c3c" link="||"][vc_empty_space height="20px"][mnky_posts layout="7" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="3"][/vc_column_inner][vc_column_inner width="1/3"][mnky_heading title="Finance" line_part_color="#3498db" link="||"][vc_empty_space height="20px"][mnky_posts layout="7" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="3"][/vc_column_inner][vc_column_inner width="1/3"][mnky_heading title="Health" line_part_color="#2ecc71" link="||"][vc_empty_space height="20px"][mnky_posts layout="7" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="3" offset="4"][/vc_column_inner][/vc_row_inner][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="40px"][mnky_posts layout="8" no_dublicate="yes" posts_per_page="3" offset="1"][/vc_column][vc_column width="1/3" el_class="sticky-container"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="56px"][vc_column_text]
		<h4><strong>Latest Reviews</strong></h4>
		[/vc_column_text][vc_empty_space height="26px"][mnky_posts layout="5" posts_per_page="3" order="ASC" rating_color="#dd3333"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="30px"][vc_column_text]
		<h4><strong>Best Video Stories</strong></h4>
		[/vc_column_text][vc_empty_space height="20px"][vc_row_inner][vc_column_inner width="1/3" css=".vc_custom_1445339478089{padding-right: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1"][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1445339524091{padding-right: 5px !important;padding-left: 5px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1"][/vc_column_inner][vc_column_inner el_class="center" width="1/3" css=".vc_custom_1445339418474{padding-left: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="2"][/vc_column_inner][/vc_row_inner][vc_empty_space height="20px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="70px"][/vc_column][/vc_row][vc_row][vc_column][mnky_heading title="More News"][vc_empty_space height="20px"][vc_row_inner][vc_column_inner el_class="sticky-container" width="1/3"][mnky_posts layout="6" no_dublicate="yes" author_hide="off" comments_hide="off"][vc_empty_space height="60px"][mnky_ads][vc_empty_space height="60px"][mnky_posts layout="6" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="2"][vc_empty_space height="60px"][/vc_column_inner][vc_column_inner width="2/3"][mnky_posts layout="3" no_dublicate="yes" author_hide="off" comments_hide="off" excerpt_hide="off" posts_per_page="13"][vc_empty_space height="60px"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
	
	// FULL GRID
	$data               = array(); // Create new array
    $data['name']       = __( '02. Full grid news homepage', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
    $data['content']    = <<<CONTENT
        [vc_row full_width="stretch_row_content_no_spaces"][vc_column][mnky_posts_grid grid_layout="mpg-layout-6" grid_height="mpg-height-500" excerpt_hide="off" css_animation="appear"][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row][vc_column width="1/6" el_class="sticky-container"][mnky_posts layout="4" no_dublicate="yes" rating_hide="off" cat_hide="off" author_hide="off" comments_hide="off" views_hide="off" posts_per_page="10"][vc_empty_space height="40px"][/vc_column][vc_column width="3/6"][mnky_posts no_dublicate="yes" rating_hide="off" author_hide="off" comments_hide="off" excerpt_hide="off" posts_per_page="5"][vc_empty_space height="20px"][mnky_posts no_dublicate="yes" rating_hide="off" author_hide="off" comments_hide="off" excerpt_hide="off" posts_per_page="5"][vc_empty_space height="20px"][mnky_posts no_dublicate="yes" rating_hide="off" author_hide="off" comments_hide="off" excerpt_hide="off" posts_per_page="5"][vc_empty_space height="20px"][mnky_posts no_dublicate="yes" rating_hide="off" author_hide="off" comments_hide="off" excerpt_hide="off" posts_per_page="5"][vc_empty_space height="20px"][mnky_posts no_dublicate="yes" rating_hide="off" author_hide="off" comments_hide="off" excerpt_hide="off" posts_per_page="5"][vc_empty_space height="10px"][/vc_column][vc_column width="2/6" el_class="sticky-container"][mnky_ads][vc_empty_space height="60px"][mnky_posts layout="6" no_dublicate="yes" author_hide="off" comments_hide="off" views_hide="off" posts_per_page="15"][vc_empty_space height="40px"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1445336271411{background-color: #f7f7f7 !important;}"][vc_column][vc_empty_space height="60px"][mnky_heading title="Also Read" line_part_color="#e74c3c" link="||"][vc_empty_space height="20px"][vc_row_inner][vc_column_inner width="1/3"][mnky_posts layout="7" no_dublicate="yes" rating_hide="off" author_hide="off" comments_hide="off" posts_per_page="3"][/vc_column_inner][vc_column_inner width="1/3"][mnky_posts layout="7" no_dublicate="yes" rating_hide="off" author_hide="off" comments_hide="off" posts_per_page="3"][/vc_column_inner][vc_column_inner width="1/3"][mnky_posts layout="7" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="3"][/vc_column_inner][/vc_row_inner][vc_empty_space height="60px"][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
		// TRAVEL
	$data               = array(); // Create new array
    $data['name']       = __( '03. Full travel news homepage', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
    $data['content']    = <<<CONTENT
		[vc_row full_width="stretch_row" css=".vc_custom_1449241326884{background-color: #1d7dc6 !important;}"][vc_column][vc_empty_space height="200px"][vc_custom_heading text="Add Slider Revolution Here Instead. Click to see import steps (you will need travel-static.zip)" font_container="tag:h2|text_align:center|color:%23ffffff" use_theme_fonts="yes" link="url:http%3A%2F%2Fwww.mnkystudio.com%2Fbitz-documentation%2Fslider-revolution%2F|title:Slider%20Revolution|target:%20_blank"][vc_empty_space height="200px"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px"][mnky_ads][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="40px"][mnky_posts layout="8" posts_per_page="3" offset="3"][/vc_column][vc_column width="1/3" el_class="sticky-container"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="56px"][vc_column_text]
		<h4><strong>New Social Media Galleries</strong></h4>
		[/vc_column_text][vc_empty_space height="26px"][mnky_posts layout="5" posts_per_page="3"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="30px"][vc_column_text]
		<h4><strong>Travel Inspiration</strong></h4>
		[/vc_column_text][vc_empty_space height="20px"][vc_row_inner][vc_column_inner width="1/3" css=".vc_custom_1445339478089{padding-right: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="1"][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1445339524091{padding-right: 5px !important;padding-left: 5px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="2"][/vc_column_inner][vc_column_inner el_class="center" width="1/3" css=".vc_custom_1445339418474{padding-left: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="2"][/vc_column_inner][/vc_row_inner][vc_empty_space height="20px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="70px"][/vc_column][/vc_row][vc_row][vc_column][mnky_heading title="More Interesting Articles"][vc_empty_space height="20px"][vc_row_inner][vc_column_inner el_class="sticky-container" width="1/3"][mnky_posts layout="6" no_dublicate="yes" author_hide="off" comments_hide="off"][vc_empty_space height="60px"][mnky_ads][vc_empty_space height="60px"][mnky_posts layout="6" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="2"][/vc_column_inner][vc_column_inner width="2/3"][mnky_posts layout="3" no_dublicate="yes" author_hide="off" comments_hide="off" excerpt_hide="off" posts_per_page="17" offset="1"][/vc_column_inner][/vc_row_inner][vc_empty_space height="60px"][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
	// TECH
	$data               = array(); // Create new array
    $data['name']       = __( '04. Full tech news homepage', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
    $data['content']    = <<<CONTENT
		[vc_row css=".vc_custom_1449243309145{background-color: #1d7dc6 !important;}"][vc_column][vc_empty_space height="200px"][vc_custom_heading text="Add Slider Revolution Here Instead. Click to see import steps (you will need news-gallery-tech.zip)" font_container="tag:h2|text_align:center|color:%23ffffff" use_theme_fonts="yes" link="url:http%3A%2F%2Fwww.mnkystudio.com%2Fbitz-documentation%2Fslider-revolution%2F|title:Slider%20Revolution|target:%20_blank"][vc_empty_space height="200px"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px" el_class="bitz-hide-on-mobile"][mnky_ads][vc_empty_space height="60px" el_class="bitz-hide-on-mobile"][/vc_column][/vc_row][vc_row][vc_column width="2/3" css=".vc_custom_1445425729736{padding-right: 30px !important;}"][mnky_posts layout="2" rating_hide="off" excerpt_hide="off" posts_per_page="7"][/vc_column][vc_column width="1/3" css=".vc_custom_1445426591295{margin-left: -15px !important;background-color: #f7f7f7 !important;}" el_class="sticky-container"][vc_empty_space height="15px"][mnky_posts layout="7" no_dublicate="yes" author_hide="off" comments_hide="off" views_hide="off" posts_per_page="10"][vc_empty_space height="15px"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1445427242206{background-color: #e2e2e2 !important;}"][vc_column][vc_empty_space height="60px"][mnky_heading title="Live Tweets From iFruit Presentation" line_part_color="#e74c3c"][vc_empty_space height="20px"][vc_custom_heading text="Add Slider Revolution Here Instead. Click to see import steps (you will need facebook-feed.zip)" font_container="tag:h2|text_align:center|color:%231e73be" use_theme_fonts="yes" link="url:http%3A%2F%2Fwww.mnkystudio.com%2Fbitz-documentation%2Fslider-revolution%2F|title:Slider%20Revolution|target:%20_blank"][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px"][mnky_heading title="More News"][vc_empty_space height="20px"][vc_row_inner][vc_column_inner el_class="sticky-container" width="1/3"][mnky_posts layout="6" no_dublicate="yes" author_hide="off" comments_hide="off"][vc_empty_space height="60px"][mnky_ads][vc_empty_space height="60px"][mnky_posts layout="6" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="2"][/vc_column_inner][vc_column_inner width="2/3"][mnky_posts layout="3" no_dublicate="yes" author_hide="off" comments_hide="off" excerpt_hide="off" posts_per_page="11"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
	// SPORT
	$data               = array(); // Create new array
    $data['name']       = __( '05. Full sport news homepage', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
    $data['content']    = <<<CONTENT
		[vc_row full_width="stretch_row" css=".vc_custom_1449243805100{background-color: #1e84c9 !important;}"][vc_column][vc_empty_space height="100px"][vc_custom_heading text="Select Right Sidebar Template. Add Slider Revolution to Pre Content Area in Page Options. Click to see import steps (you will need news-gallery-sports.zip)" font_container="tag:h4|text_align:center|color:%23ffffff" use_theme_fonts="yes" link="url:http%3A%2F%2Fwww.mnkystudio.com%2Fbitz-documentation%2Fslider-revolution%2F|title:Slider%20Revolution|target:%20_blank"][vc_empty_space height="100px"][/vc_column][/vc_row][vc_row][vc_column][mnky_posts layout="3" excerpt_hide="off" posts_per_page="5"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="25px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="30px"][vc_column_text]
		<h4><strong>Sport Diet How To's</strong></h4>
		[/vc_column_text][vc_empty_space height="20px"][vc_row_inner][vc_column_inner width="1/2" css=".vc_custom_1445421039727{padding-right: 2px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="3"][vc_empty_space height="4px"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="3"][/vc_column_inner][vc_column_inner width="1/2" css=".vc_custom_1445421033177{padding-left: 2px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="3"][vc_empty_space height="4px"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="1"][/vc_column_inner][/vc_row_inner][vc_empty_space height="20px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="70px" el_class="bitz-hide-on-mobile"][mnky_ads][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row][vc_column][mnky_posts layout="3" no_dublicate="yes" posts_per_page="5"][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
	// CLASSIC NEWS
	$data               = array(); // Create new array
    $data['name']       = __( '06. Full classic news homepage', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
    $data['content']    = <<<CONTENT
		[vc_row][vc_column][mnky_posts_grid grid_height="mpg-height-500" excerpt_hide="off"][vc_empty_space height="40px"][mnky_posts layout="10" posts_per_page="1" order="ASC" breaking_bg="#e74c3c" breaking_ph_color="#000000" breaking_text="Breaking News:"][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="40px"][mnky_posts layout="8" no_dublicate="yes" posts_per_page="3"][/vc_column][vc_column width="1/3" el_class="sticky-container"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="56px"][vc_column_text]
		<h4><strong>Popular Right Now</strong></h4>
		[/vc_column_text][vc_empty_space height="26px"][mnky_posts layout="5" posts_per_page="3" orderby="meta_value_num" rating_color="#dd3333"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="30px"][vc_column_text]
		<h4><strong>Best Video Stories</strong></h4>
		[/vc_column_text][vc_empty_space height="20px"][vc_row_inner][vc_column_inner width="1/3" css=".vc_custom_1445339478089{padding-right: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1"][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1445339524091{padding-right: 5px !important;padding-left: 5px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1"][/vc_column_inner][vc_column_inner el_class="center" width="1/3" css=".vc_custom_1445339418474{padding-left: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="2"][/vc_column_inner][/vc_row_inner][vc_empty_space height="20px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row][vc_column][vc_row_inner][vc_column_inner el_class="sticky-container" width="1/3"][mnky_posts layout="6" no_dublicate="yes" author_hide="off" comments_hide="off"][vc_empty_space height="60px"][mnky_ads][vc_empty_space height="60px"][/vc_column_inner][vc_column_inner width="2/3"][mnky_posts layout="3" no_dublicate="yes" author_hide="off" comments_hide="off" excerpt_hide="off" posts_per_page="11"][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
	// VIDEO
	$data               = array(); // Create new array
    $data['name']       = __( '07. Full video news homepage', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
    $data['content']    = <<<CONTENT
		[vc_row][vc_column][vc_empty_space height="60px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="30px"][vc_column_text]
		<h4><strong>Most Popular Video Stories</strong></h4>
		[/vc_column_text][vc_empty_space height="20px"][vc_row_inner][vc_column_inner width="1/3" css=".vc_custom_1445339478089{padding-right: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1"][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1445339524091{padding-right: 5px !important;padding-left: 5px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1"][/vc_column_inner][vc_column_inner el_class="center" width="1/3" css=".vc_custom_1445339418474{padding-left: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1"][/vc_column_inner][/vc_row_inner][vc_empty_space height="20px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="60px" el_class="bitz-hide-on-mobile"][mnky_ads][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row full_width="stretch_row" css=".vc_custom_1445336271411{background-color: #f7f7f7 !important;}"][vc_column][vc_empty_space height="60px"][vc_row_inner][vc_column_inner width="2/3"][mnky_heading title="Our Video Stream" line_part_color="#e74c3c" link="||"][vc_empty_space height="20px"][/vc_column_inner][vc_column_inner width="1/3"][mnky_heading title="More Video Stories" line_part_color="#3498db" link="||"][vc_empty_space height="20px"][mnky_posts layout="7" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="5"][/vc_column_inner][/vc_row_inner][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row full_width="stretch_row_content" video_bg="yes" video_bg_url="https://www.youtube.com/watch?v=MODmEMm1Ql8" el_class="text-right-align"][vc_column][vc_empty_space height="15px"][vc_column_text]<span style="color: #fff; text-transform: uppercase;">Live Coverage</span> <i class="fa fa-circle" style="font-size: 30px; color: #d11717; padding-left: 10px; padding-right: 0px; vertical-align: -4px;"></i>[/vc_column_text][vc_empty_space height="550px"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="40px"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="3" width="800" height="450"][vc_empty_space height="60px"][/vc_column][vc_column width="1/3" el_class="sticky-container"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="56px"][vc_column_text]
		<h4><strong>Latest Reviews from Our Experts</strong></h4>
		[/vc_column_text][vc_empty_space height="26px"][mnky_posts layout="5" posts_per_page="3" order="ASC" rating_color="#dd3333"][vc_empty_space height="60px"][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
	// FASHION
	$data               = array(); // Create new array
    $data['name']       = __( '08. Full fashion news homepage', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
    $data['content']    = <<<CONTENT
		[vc_row][vc_column][mnky_ads][vc_empty_space height="60px" el_class="bitz-hide-on-mobile"][/vc_column][/vc_row][vc_row][vc_column width="2/3"][mnky_posts layout="8" comments_hide="off" views_hide="off" excerpt_hide="off" posts_per_page="1" offset="1"][vc_empty_space height="60px" el_class="bitz-hide-on-tablet"][vc_row_inner el_class="custom-row sticky-container"][vc_column_inner el_class="sticky-container " width="1/2"][mnky_ads el_class="bitz-hide-on-tablet"][/vc_column_inner][vc_column_inner el_class="custom-column" width="1/2"][mnky_posts layout="3" no_dublicate="yes" post_on_column="on" author_hide="off" excerpt_hide="off" posts_per_page="5" offset="5"][/vc_column_inner][/vc_row_inner][/vc_column][vc_column width="1/3" el_class="sticky-container "][vc_empty_space height="16px"][vc_column_text]
		<h4><strong>Most Popular</strong></h4>
		[/vc_column_text][vc_empty_space height="26px"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" offset="1"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="60px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="30px"][vc_column_text]
		<h4><strong>Latest Products in Our Shop</strong></h4>
		[/vc_column_text][vc_empty_space height="20px"][vc_custom_heading text="Install WooCommerce to Add Products" font_container="tag:h2|text_align:center|color:%231e73be" use_theme_fonts="yes" link="||"][vc_empty_space height="20px"][vc_separator color="custom" border_width="10" accent_color="#222222"][/vc_column][/vc_row][vc_row][vc_column][vc_empty_space height="30px"][vc_column_text]
		<h4><strong>From Our Facebook</strong></h4>
		[/vc_column_text][vc_empty_space height="20px"][vc_custom_heading text="Add Slider Revolution Here Instead. Click to see import steps (you will need facebook-feed.zip)" font_container="tag:h2|text_align:center|color:%231e73be" use_theme_fonts="yes" link="url:http%3A%2F%2Fwww.mnkystudio.com%2Fbitz-documentation%2Fslider-revolution%2F|title:Slider%20Revolution|target:%20_blank"][vc_empty_space height="20px"][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
	// ARTICLES AND POPULAR NEWS
	$data               = array(); // Create new array
    $data['name']       = __( '09. Articles and popular news column', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
    $data['content']    = <<<CONTENT
		[vc_row][vc_column width="2/3"][mnky_posts no_dublicate="yes" comments_hide="off" excerpt_hide="off" posts_per_page="5" offset="2"][vc_empty_space height="20px"][mnky_heading title="Category Title" line_part_color="#9548e2"][vc_empty_space height="20px"][vc_row_inner][vc_column_inner width="1/2"][mnky_posts layout="5" no_dublicate="yes" posts_per_page="2"][/vc_column_inner][vc_column_inner width="1/2"][mnky_posts layout="5" no_dublicate="yes" posts_per_page="2"][/vc_column_inner][/vc_row_inner][vc_empty_space height="60px"][/vc_column][vc_column width="1/3" el_class="sticky-container "][vc_column_text]
		<h4><strong>Most Popular Right Now</strong></h4>
		[/vc_column_text][vc_empty_space height="40px"][mnky_posts layout="6" allow_dublicate="yes" author_hide="off" orderby="meta_value_num"][vc_empty_space height="60px"][mnky_ads][vc_empty_space height="20px"][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
	// TREE COLUMN NEWS
	$data               = array(); // Create new array
    $data['name']       = __( '10. Three news category columns', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
	$data['content']    = <<<CONTENT
		[vc_row full_width="stretch_row" css=".vc_custom_1445336271411{background-color: #f7f7f7 !important;}"][vc_column][vc_empty_space height="60px"][vc_row_inner][vc_column_inner width="1/3"][mnky_heading title="Business" line_part_color="#e74c3c" link="||"][vc_empty_space height="20px"][mnky_posts layout="7" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="3"][/vc_column_inner][vc_column_inner width="1/3"][mnky_heading title="Finance" line_part_color="#3498db" link="||"][vc_empty_space height="20px"][mnky_posts layout="7" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="3"][/vc_column_inner][vc_column_inner width="1/3"][mnky_heading title="Health" line_part_color="#2ecc71" link="||"][vc_empty_space height="20px"][mnky_posts layout="7" no_dublicate="yes" author_hide="off" comments_hide="off" posts_per_page="3" offset="4"][/vc_column_inner][/vc_row_inner][vc_empty_space height="60px"][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	
	// BACKGROUND BOXES
	$data               = array(); // Create new array
    $data['name']       = __( '11. Teaser news boxes', 'core-extend' ); // Assign name for your custom template
    $data['custom_class'] = 'mnky_vc_template'; // CSS class name
	$data['content']    = <<<CONTENT
		[vc_row][vc_column][vc_empty_space height="60px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="30px"][vc_column_text]
		<h4><strong>Best News Stories</strong></h4>
		[/vc_column_text][vc_empty_space height="20px"][vc_row_inner][vc_column_inner width="1/3" css=".vc_custom_1445339478089{padding-right: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1"][/vc_column_inner][vc_column_inner width="1/3" css=".vc_custom_1445339524091{padding-right: 5px !important;padding-left: 5px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1"][/vc_column_inner][vc_column_inner el_class="center" width="1/3" css=".vc_custom_1445339418474{padding-left: 0px !important;}"][mnky_posts layout="9" no_dublicate="yes" excerpt_hide="off" posts_per_page="1" offset="2"][/vc_column_inner][/vc_row_inner][vc_empty_space height="20px"][vc_separator color="custom" border_width="10" accent_color="#222222"][vc_empty_space height="70px"][/vc_column][/vc_row]
CONTENT;
  
    vc_add_default_templates( $data );
	

}