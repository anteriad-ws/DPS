<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/user-registration/myaccount/navigation.php.
 *
 * HOWEVER, on occasion UserRegistration will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.wpeverest.com/user-registration/template-structure/
 * @author  WPEverest
 * @package UserRegistration/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'user_registration_before_account_navigation' );
?>



<?php 


// Profile Update Form Shortcode
function custom_profile_update_form() {
  // Display profile update form HTML here
  ob_start(); 
  
  
  ?>
 

  <div class="tab-container">
 
  <div class="custom-tabs">
        <ul class="tab-links" >
            <li ><a href="#dashboard">Dashboard</a></li>
            <li><a href="#change_password">Change Password</a></li>
            <li><a href="#login_history">Login History</a></li>
            <li><a href="#downloads">Downloads</a></li>
            <li><a href="#email_preferences">Email Preferences</a></li>
        </ul>
        <div class="dashboard">
        <div class="tab-content text-center active" id="dashboard">
        <h2 class="center-head dashboard_title">
            
	welcome, <?php
	 echo esc_attr(get_the_author_meta('display_name', get_current_user_id()));
	?>
</h2>
<div class="user-registration-img-container " >
		<?php
			$gravatar_image      = get_avatar_url( get_current_user_id(), $args = null );
			$profile_picture_url = get_user_meta( get_current_user_id(), 'user_registration_profile_pic_url', true );
			$image               = ( ! empty( $profile_picture_url ) ) ? $profile_picture_url : $gravatar_image;
		?>
		<img class="profile-preview" alt="profile-picture" src="<?php echo $image; ?>">
	</div>
  <header>
		<?php
		$first_name = ucfirst( get_user_meta( get_current_user_id(), 'first_name', true ) );
		$last_name  = ucfirst( get_user_meta( get_current_user_id(), 'last_name', true ) );
		$full_name  = $first_name . ' ' . $last_name;
		if ( empty( $first_name ) && empty( $last_name ) ) {
			$full_name = $current_user->display_name;
		}
		?>
		<h3>
		<?php
		printf(
			__( '%1$s', 'user-registration' ),
			esc_html( $full_name )
		);
		?>
        
			</h3>
            <?php
		printf(
			__( '@%1$s', 'user-registration' ),
			esc_html( $full_name )
		);
		?>
		
	</header>
    <p>
                From your account dashboard you can edit your <a href="#"> profile details</a> and <a href="#change_password">Change Password</a></p>
    <a href="<?php echo wp_logout_url(); ?>">Logout</a>

        </div>
        <div class="tab-content" id="change_password">
            <!-- Content for Tab 2 -->
            <?php echo  do_shortcode('[cr_change_pwd_form]'); ?>
        </div>
        <div class="tab-content" id="login_history">
        <?php echo  do_shortcode('[login_history]'); ?>
        </div>
        <div class="tab-content" id="downloads">
        <?php echo  do_shortcode('[download_history]'); ?>
        </div>
        <div class="tab-content" id="email_preferences">
        <?php echo  do_shortcode('[email_preferences]'); ?>
        </div>
    </div>
    </div>
  <?php
  return ob_get_clean();
}
add_shortcode('custom_profile_update', 'custom_profile_update_form');

// Process Registration
function process_registration() {
  if (isset($_POST['register'])) {
      $username = sanitize_user($_POST['username']);
      $email = sanitize_email($_POST['email']);
      $password = $_POST['password'];
      
      $user_id = wp_create_user($username, $password, $email);
      
      if (!is_wp_error($user_id)) {
          // Registration successful
          wp_redirect(home_url());
          exit;
      }
  }
}
add_action('init', 'process_registration');

// Process Profile Update
function process_profile_update() {
  if (is_user_logged_in() && isset($_POST['update_profile'])) {
      $display_name = sanitize_text_field($_POST['display_name']);
      $user_bio = sanitize_textarea_field($_POST['user_bio']);
      
      $user_id = get_current_user_id();
      
      update_user_meta($user_id, 'display_name', $display_name);
      update_user_meta($user_id, 'description', $user_bio);
  }
}
add_action('init', 'process_profile_update');

function custom_user_registration_add_subpages() {
  add_submenu_page(
      'my-account',    // Parent slug (My Account page slug)
      'Dashboard',            // Page title
      'Dashboard',            // Menu title
      'read',                 // Capability required
      'dashboard',            // Sub-page slug
      'custom_dashboard_page' // Callback function to display content
  );

  add_submenu_page(
      'my-account',
      'Login History',
      'Login History',
      'read',
      'login-history',
      'custom_login_history_page'
  );

  // Add more sub-pages as needed
}
add_action('init', 'custom_user_registration_add_subpages');


add_action('load-your-account-slug', 'custom_user_account_sub_menu');
function custom_dashboard_page() {
    if ($_GET['tab'] === 'dashboard') {
        // Display dashboard content
    }
}

function custom_login_history_page() {
    if ($_GET['tab'] === 'login-history') {
        // Display login history content
    }
}
?>
<?php do_action( 'user_registration_after_account_navigation' ); ?>


