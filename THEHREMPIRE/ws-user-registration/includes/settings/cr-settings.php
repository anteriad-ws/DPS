<?php
/**
 * User Registration- Settings
 * 
 * @version	1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function register_admin_scripts() {
    if (is_admin()) {
        // Enqueue jQuery from CDN
		
    
		wp_enqueue_style('bootstrapcss','https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', array(), null, true);
	  wp_enqueue_style('summernotecss','https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css', array(), null, true);
	   wp_enqueue_script('bootstrapmin', 'https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', array(), null, true);
        wp_enqueue_script('summernote', 'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js', array(), null, true);
		$custom_register_script = plugins_url( 'settings/admin.js', dirname(__FILE__) );
	  	wp_enqueue_script( 'custom_admin_script', $custom_register_script, array('jquery' ),'1.1.0', true );
	  
    }
}

add_action('admin_enqueue_scripts', 'register_admin_scripts');

	



function cr_get_plugin_link() {
	return apply_filters( 'cr_plugin_link', 'options-general.php?page=user_registration_page' );
}

add_action('admin_menu', 'cr_main_admin_menu', 95);
function cr_main_admin_menu() {

	add_options_page(
		'User Registartion',
		'User Registartion',
		'manage_options',
		'user_registration_page',
		'user_registration_page'
	);

}

function user_registration_page() {

	user_get_settings_header_content();

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'Registration Email settings';
	
	$active_section = isset( $_GET[ 'section' ] ) ? $_GET[ 'section' ] : 'general';

	
	if ( $active_tab == 'Registration Email settings' ) {
	
		$active_section = isset( $_GET[ 'section' ] ) ? $_GET[ 'section' ] : 'general';
		
		if ( 'general' == $active_section ) {
	
			user_gen_settings_content();
			
		}
	
	}
	if ( $active_tab == 'support' ) {
	
		cr_support_guide();

		do_action( 'user_support_page_content', $active_section );

	}
	if ( $active_tab == 'forgotpassword' ) {
	
		forgotpassword_settings_content();

		do_action( 'user_more_page_content', $active_section );

	}
	do_action( 'user_settings_after_more', $active_tab );


}

function user_get_settings_header_content() {

	?>

	<div class="user-settings-container">
		<div class="user-settings-row">
			<div class="user-settings-col-12 user-main-plugin-content">
				<h1>User Registration settings</h1>
			</div>
		</div>
	</div>

	<div id="userfrp-admin-notices" class="user-settings-container user-settings-errors">
		<div class="user-settings-row">
			<div class="user-settings-col-12">
				<?php settings_errors(); ?>
			</div>
		</div>
	</div>
	
	<div class="user-settings-container">
		<div class="user-settings-row">
		
			<div class="user-settings-col-12">
			
				<?php user_get_settings_tabs(); ?>
				
				
	
			</div>
		</div>
	</div>

<?php

}
function user_get_settings_tabs() {

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'Registration Email settings'; ?>
		
	<h2 class="nav-tab-wrapper">
		<?php
		/*
		<a href="<?php echo userfrp_get_plugin_link(); ?>&tab=home" class="nav-tab <?php echo $active_tab == 'home' ? 'nav-tab-active' : ''; ?>">Home</a>
			<?php do_action( 'userfrp_settings_tabs_after_home', $active_tab ); ?>
		*/
			?>
		<a href="<?php echo cr_get_plugin_link(); ?>&tab=Registration Email settings" class="nav-tab <?php echo $active_tab == 'Registration Email settings' ? 'nav-tab-active' : ''; ?>">Registration Email settings</a>
			<?php do_action( 'cr_settings_tabs_after_settings', $active_tab ); ?>
		<a href="<?php echo cr_get_plugin_link(); ?>&tab=forgotpassword" class="nav-tab <?php echo $active_tab == 'forgotpassword' ? 'nav-tab-active' : ''; ?>">Forgot password Email settings</a>
			<?php do_action( 'cr_settings_tabs_after_forgotpassword', $active_tab ); ?>
		<a href="<?php echo cr_get_plugin_link(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>">Support</a>
			<?php do_action( 'cr_settings_tabs_after_support', $active_tab ); ?>
	</h2>

<?php

}
function user_gen_settings_content() { ?>

	<div class="user-settings-container">
		<div class="user-settings-row">
		
			<div class="user-settings-col-12">
	
				
			
					<div class="userfrp-gen-settings-form-wrap">
			
					<?php
						settings_fields( 'cr_gen_settings' );
						do_settings_sections( 'cr_gen_settings' );
						
					?>
			
					</div>
			
		
			</div>

		</div>
	</div>

<?php
}
function forgotpassword_settings_content() { ?>

	<div class="user-settings-container">
		<div class="user-settings-row">
			<div class="user-settings-col-12">
	

					<div class="userfrp-gen-settings-form-wrap">
			
					<?php
						settings_fields( 'cr_forgotpassword_settings' );
						do_settings_sections( 'cr_forgotpassword_settings' );
						
					?>
			
					</div>
			
				
		
			</div>

		</div>
	</div>

<?php

}

add_action( 'admin_init', 'cr_settings_init' );
function cr_settings_init() {

	register_setting( 'cr_gen_settings', 'cr_gen_settings' );

	add_settings_section(
		'cr_gen_settings_section',
		__( 'Registration Email Template settings', 'custom-user-registration' ),
		'cr_gen_settings_section_callback',
		'cr_gen_settings'
	);

	add_settings_field(
		'cr_reset_page',
		__( 'Subject & Email content', 'custom-user-registration' ),
		'user_reset_page_render',
		'cr_gen_settings',
		'cr_gen_settings_section'
	);

	register_setting( 'cr_forgotpassword_settings', 'cr_forgotpassword_settings' );
	add_settings_section(
		'cr_forgotpassword_settings_section',
		__( 'Forgot Password Email Template settings', 'custom-user-registration' ),
		'cr_forgotpassword_settings_section_callback',
		'cr_forgotpassword_settings'
	);

	add_settings_field(
		'cr_forgot_password', 
		__( 'Subject & Email content', 'custom-user-registration' ),
		'forgotpassword_page_render',
		'cr_forgotpassword_settings',
		'cr_forgotpassword_settings_section'
	);	
}
function cr_gen_settings_section_callback() { 
	_e( 'General plugin settings', 'custom-user-registration' );
}

function cr_forgotpassword_settings_section_callback() { 
	_e( 'Settings for forgot password ', 'custom-user-registration' );
}
function user_reset_page_render() {

	global $wpdb;

  $table_name = 'email_settings';

  if (isset($_POST['email_submit'])) {
     
      //$datetime = date('Y-m-d H:i:s', current_time('timestamp', 0));
      $register_content = htmlspecialchars_decode($_POST['user_register_code']);
      $subject_content = htmlspecialchars_decode($_POST['subject']);
      
      
  $wpdb->query($wpdb->prepare("UPDATE $table_name 
                SET email_template = '$register_content',  subject = '$subject_content' WHERE id = 1")
        );
  //$wpdb->update($table_name, $data, $where);
 
  echo '<p class="bg-success" style="padding:5px 10px">Update successful!</p>';
      //$wpdb->query($wpdb->prepare("UPDATE $table_name SET email_template = '$register_content',  subject = '$subject_content' WHERE id = 1")
      //);
    
  }

    ?>
   
    <form method="POST" id="user_register"  action="" >
<?php $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1"); ?>
<input type="text" name="subject" id="subject" width="100%" style="width:65%" value="<?php echo $result[0]->subject; ?>"> <br><br>

<?php $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1"); 
?>
<textarea id="user_register_code" name="user_register_code"><?php echo $result[0]->email_template; ?></textarea>

<input type="submit" name="email_submit" value="Submit" style=" padding: 10px 30px;background: #0e8000;color: #fff;border: none;margin-top: 20px;">


</form>
    
    <?php

 }


 function forgotpassword_page_render() {

	 global $wpdb;

$table_name = 'email_settings';

if (isset($_POST['forgot_password_submit'])) {
   
	//$datetime = date('Y-m-d H:i:s', current_time('timestamp', 0));
	$register_content = htmlspecialchars_decode($_POST['forgot_password_code']);
	$subject_content = htmlspecialchars_decode($_POST['password_subject']);
	
	
$wpdb->query($wpdb->prepare("UPDATE $table_name 
			  SET email_template = '$register_content',  subject = '$subject_content' WHERE id = 4")
	  );
//$wpdb->update($table_name, $data, $where);

echo '<p class="bg-success" style="padding:5px 10px">Update successful!</p>';
	//$wpdb->query($wpdb->prepare("UPDATE $table_name SET email_template = '$register_content',  subject = '$subject_content' WHERE id = 1")
	//);
  
}

  ?>

  <form method="POST" id="user_register"  action="" >
<?php $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 4"); ?>
<input type="text" name="password_subject" id="subject" width="100%" style="width:65%" value="<?php echo $result[0]->subject; ?>"> <br><br>
<?php $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 4"); 
?>
<textarea id="forgot_password_code" name="forgot_password_code"><?php echo $result[0]->email_template; ?></textarea>

<input type="submit" name="forgot_password_submit" value="Submit" style=" padding: 10px 30px;background: #0e8000;color: #fff;border: none;margin-top: 20px;">

</form>
<?php

}

function cr_support_guide() { ?>

	<div class="user-settings-container">
		<div class="user-settings-row">
		
			<div class="user-settings-col-7 user-settings-guide user-settings-guide-features">
	
				<h2>Quick Start Guide</h2>
				<p>You'll be up and running in the flashiest of flashes.</p>

				<ul>
					<li>
						<h3>Add The Shortcode</h3>
						<p>Put the reset register form shortcode in one of your pages or post</p>
						<p style="font-weight: bold;"><code>[custom_user_registration_form]</code></p>
						<p>Put the reset login form shortcode in one of your pages or post</p>
						<p style="font-weight: bold;"><code>[cr_login_form]</code></p>
						<p>Put the reset forgot password form shortcode in one of your pages or post</p>
						<p style="font-weight: bold;"><code>[cr_forgot_pwd_form]</code></p>
						
					</li>	
				</ul>
	
			</div>
		</div>
	</div>
<?php }?>


