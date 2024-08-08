<?php
/**
 * Frontend Custom Registartion - Functions
 * 
 * @version	1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_dir_path = plugin_dir_path( __FILE__ );
include( $plugin_dir_path . 'forgot-password.php');

// Enqueue scripts and stylesheets
add_action('wp_enqueue_scripts', 'register_link_css_and_js_files');
function register_link_css_and_js_files() {
  
    wp_enqueue_script('jquery.min.js', 'https://code.jquery.com/jquery-3.5.1.min.js', array('jquery'), '1.61.2', true);
    wp_enqueue_script('jquery.validate.min', 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js', array('jquery'), '1.61.2', true);
    wp_enqueue_script('bootstrap.min.js', 'https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', array('jquery'), '1.61.2', true);
    //wp_enqueue_script('custom_register-js', plugin_basename( __FILE__ ) . '/assets/js/custom_register.js', array( 'jquery' ), '1.0', true );
    $custom_register_script = plugins_url( 'assets/js/custom_register.js', dirname(__FILE__) );
	  wp_enqueue_script( 'custom_register_script', $custom_register_script, array( 'jquery' ), '1.1.1', true );

    wp_register_style( 'custom_css_style', plugins_url( '/assets/css/custom.css', dirname(__FILE__) ) );
	  wp_enqueue_style( 'custom_css_style' );
	
  }

/*****Automatically creating user registartion table****/
global $user_registartion_version;
$user_registartion_version = '1.0';

function user_registartion_install() {
	global $wpdb;
	global $user_registartion_version;

	$table_name = $wpdb->prefix . 'custom_user_registartion';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		email_id varchar(100) NOT NULL,
		password varchar(250) NOT NULL,
		first_name varchar(100)  NOT NULL,
    last_name varchar(100)  NOT NULL,
    company_name varchar(100)  NOT NULL,
    job_title varchar(100)  NOT NULL,
    phone_number varchar(100)  NOT NULL,
    Country varchar(100)  NOT NULL,
    Create_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	add_option( 'user_registartion_version', $user_registartion_version );
}
register_activation_hook( __FILE__, 'user_registartion_install' );

/*****End - Automatically creating user registartion table****/

  add_shortcode('custom_user_registration_form', 'user_registration_form');
  function user_registration_form() {
    ob_start();
    if ( !is_user_logged_in() ) {
      global $registrationError, $registrationSuccess;
      if ( !empty( $registrationError ) ) {
        ?>
        <div class="alert alert-danger">
          <?php echo $registrationError; ?>
        </div>
      <?php } ?>
  
      <?php if ( !empty( $registrationSuccess ) ) { ?>
        <br/>
        <div class="alert alert-success">
          <?php echo $registrationSuccess; ?>
        </div>
      <?php } 

    $plugin_dir_path = plugin_dir_path( __FILE__ );
    include( $plugin_dir_path . 'templates/registration-form.php');

	} 
  else 
  {
		echo '<p class="error-logged">You are already logged in.</p>';
	}
  $register_form = ob_get_clean();
	return $register_form;

}

function replace_textt($text, $replace_text, $register_content) {
  $replaced_text = str_replace($text, $replace_text, $register_content);
  return $replaced_text;
}

/*function custom_registration_enqueue_scripts() {
  wp_enqueue_script( 'custom-registration-script', plugin_dir_url( __FILE__ ) . 'templates/registration-form.php', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'custom_registration_enqueue_scripts' );*/

// Process the registration form data
function custom_registration_process() {
  if (isset($_POST['usersubmit'])) {

    global $registrationError, $registrationSuccess,$wpdb;
    $datetime = date('Y-m-d H:i:s', current_time('timestamp', 0));
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $first_name = 	sanitize_text_field( $_POST['fname'] );
    $last_name 	= 	sanitize_text_field( $_POST['lname'] );
    $companyname 	= sanitize_text_field( $_POST['companyname'] );
    $jobtitle 		= sanitize_text_field( $_POST['jobtitle'] );
    $contact_number 		= sanitize_text_field( $_POST['phoneno'] );
    $country 		= sanitize_text_field( $_POST['country'] );
    $verification_token = wp_generate_password(32, false);
    
    // Validate inputs
    //$errors = array();
    $error = new WP_Error();
    
    if (empty($username) || !validate_username($username)) {
        //$errors[] = 'Invalid username';
        $registrationError .= '<strong>Error! </strong>Invalid username,';
    }
    if ( username_exists( $username ) ) {
      $registrationError .= '<strong>Error! </strong> Username In Use!.,';
    }
    if ( empty( $email ) || ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
        //$errors[] = 'Invalid email address.';
        $registrationError .= '<strong>Error! </strong>Invalid email address';
      }
     
     
      if ( email_exists( $email ) ) {
        $registrationError .= '<strong>Error! </strong>This Email is already registered.';
          //$errors[]= 'Email Already in use.';
      }
    
    if ( empty( $password ) ) {
        //$errors[] = 'Password is required.';
        $registrationError .= '<strong>Error! </strong>Password is required.';
      }

      $registrationError = trim( $registrationError, ',' );
      $registrationError = str_replace( ",", "<br/>", $registrationError );


      if ( empty( $registrationError ) ) {
        // Create user
        $user_id = wp_create_user( $username, $password, $email,  $first_name, $last_name,$companyname,$jobtitle,$contact_number, $country );
         
        if (!is_wp_error($user_id)) {
          $user_ip = $_SERVER['REMOTE_ADDR'];
          update_user_meta($user_id, 'signup_ip', $user_ip);
          if (!empty($_POST['fname'])) {
            update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['fname']));
        }
        if (!empty($_POST['lname'])) {
          update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['lname']));
      }
      if (!empty($_POST['companyname'])) {
          update_user_meta($user_id, 'company_name', sanitize_text_field($_POST['companyname']));
      } 
    
    if (!empty($_POST['jobtitle'])) {
      update_user_meta($user_id, 'job_title', sanitize_text_field($_POST['jobtitle']));
    } 
    if (!empty($_POST['phoneno'])) {
      update_user_meta($user_id, 'phone_number', sanitize_text_field($_POST['phoneno']));
    }       
    if (!empty($_POST['country'])) {
      update_user_meta($user_id, 'country', sanitize_text_field($_POST['country']));
    } 
    
    if ($user_id > 0) {
      // Get user data
      $user_data = get_userdata($user_id);
      $user_meta = get_user_meta($user_id);
     

      // Prepare data to send to SendGrid
      $data = array(
        'list_ids' => array(
            sendgridController::SG_REGISTRATION_LISTID
        ),
        'contacts' => array(array(
                "country" => (isset($user_meta['country'])) ? $user_meta['country'][0] : '',
                "email" => $user_data->data->user_email,
                "first_name" => $user_meta['first_name'][0],
                "last_name" => $user_meta['last_name'][0],
                "phone_number" => $user_meta['phone_number'][0],
            ))
    );
    
        $response = sendgridController::add_to_list($data);

     
  }
            // Generate verification code
            $verification_code = wp_generate_password(20, false);
            
            // Store verification code in user meta
            update_user_meta($user_id,'verification_code', $verification_code);
            update_user_meta( $user_id, 'email_verified', false );
            
            // Send verification email
            $verification_url = add_query_arg(
                array(
                    'user_id' => $user_id,
                    'verification_code' => $verification_code
                ),
                site_url('sign-in')
            );
            global $wpdb;
            $table = 'email_settings';
            $query = $wpdb->prepare("SELECT * FROM `{$table}` WHERE id=1");
            $row = $wpdb->get_row($query);
            $brand_name = get_bloginfo('name');
            $email_body = replace_textt('[verification_url]', $verification_url, $row->email_template);
            $message = apply_filters('wpa_email_message', __($email_body));
            
           
            $subject2 = $row->subject;
            $subject = $brand_name . ':' . $subject2;
            //$content= $row->email_template;
           
            //$subject = 'Email Verification';
            //$message = replace_text('[$verification_url]', $verification_url, $row->email_template);
            //$message .= 'Please click the following link to verify your email:';
            //$message.= $verification_url;
            //$message = apply_filters('wpa_email_message', __($email_body));
            //wp_mail($email, $subject, $message);
            $headers = array('Content-Type: text/html; charset=UTF-8');

            $sent_mail = wp_mail($email, $subject, $message,$headers);
           /* if ($sent_mail) {
              $registrationSuccess = '<strong>Success! </strong>Please check your email. You will soon receive an email with a verification link.';
              
            }*/
            // Redirect to success page
            wp_redirect(site_url('sign-in?msg=registered'));
            exit;
        } else {
          $registrationError = $user_id->get_error_message();
        }
    }
    
   
}
}


add_action( 'init', 'custom_registration_process' );

// Verify email address
function custom_user_verification() {

  
  if (isset($_GET['user_id']) && isset($_GET['verification_code'])) {
    $user_id = intval($_GET['user_id']);
    $verification_code = sanitize_text_field($_GET['verification_code']);
    
    // Get stored verification code
    $stored_verification_code = get_user_meta($user_id, 'verification_code', true);
    if ( $user_id && $verification_code && $verification_code === $stored_verification_code )
    if ($verification_code === $stored_verification_code) {
        // Update user meta to mark email as verified
        update_user_meta($user_id, 'email_verified', true);
        update_user_meta($user_id, 'approval_status', 'approved');
        delete_user_meta( $user_id, 'verification_code' );
        $user = get_user_by( 'ID', $user_id );
        //$user->set_role( 'approved' );
            wp_update_user( $user );
        // Redirect to email verification success page
        wp_redirect(site_url('sign-in?msg=verified'));
        exit;
    } else {
        // Redirect to email verification error page
        wp_redirect(site_url('email-verification-error'));
        exit;
    }
}
}
add_action('init', 'custom_user_verification');

/*function wpse_restrict_access() {
  if ( ! is_user_logged_in() ) {
    return;
}

$user_id = get_current_user_id();
$email_verified = get_user_meta( $user_id, 'email_verified', true );
$user = get_user_by( 'ID', $user_id );
$user_status = $user->roles[0];

if ( ! $email_verified || $user_status !== 'approved' ) {
    // Redirect the user to a verification reminder page or other appropriate action
    wp_redirect( home_url( '/email-verification-reminder/' ) );
    exit;
}
}
add_action( 'template_redirect', 'wpse_restrict_access' );*/

// Add a custom column to the user listing in the backend
add_filter('manage_users_columns', 'user_add_approval_status_column');

function user_add_approval_status_column($columns) {
    $columns['approval_status'] = 'Approval Status';
    return $columns;
}

// Populate the custom column with the approval status
add_action('manage_users_custom_column', 'user_display_approval_status_column', 10, 3);

function user_display_approval_status_column($value, $column_name, $user_id) {
    if ($column_name === 'approval_status') {
        $approval_status = get_user_meta($user_id, 'approval_status', true);
        
        if ($approval_status === 'approved') {
            $value = 'Approved';
        } else {
            $value = 'Pending';
        }
    }
    
    return $value;
}
// Add the approval status field to the user profile
add_action('show_user_profile', 'user_add_approval_status_field');
add_action('edit_user_profile', 'user_add_approval_status_field');

function user_add_approval_status_field($user) {
    $approval_status = get_user_meta($user->ID, 'approval_status', true);
    ?>
    <h3>Approval Status</h3>
    <table class="form-table">
        <tr>
            <th><label for="approval_status">Approval Status</label></th>
            <td>
                <select name="approval_status" id="approval_status">
                    <option value="pending" <?php selected($approval_status, 'pending'); ?>>Pending</option>
                    <option value="approved" <?php selected($approval_status, 'approved'); ?>>Approved</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

// Save the approval status field when the user profile is updated
add_action('personal_options_update', 'user_save_approval_status_field');
add_action('edit_user_profile_update', 'user_save_approval_status_field');

function user_save_approval_status_field($user_id) {
    if (current_user_can('edit_user', $user_id)) {
        $approval_status = isset($_POST['approval_status']) ? $_POST['approval_status'] : '';
        update_user_meta($user_id, 'approval_status', $approval_status);
    }
}


/*** Login function */
add_shortcode( 'cr_login_form', 'cr_login_form_callback' );

function cr_login_form_callback() {
	ob_start();

	if ( !is_user_logged_in() ) {

		global $errors_login;

		if (!empty( $errors_login ) ) {
			?>
			<div class="alert alert-danger">
				<?php echo $errors_login; ?>
			</div>
		<?php } ?>
		<form method="post" class="wc-login-form">
			<div class="login_form">
				<div class="log_user">
					<label for="user_name">Enter your Username *</label>
					<input name="log" type="text" id="user_name" value="<?php echo isset($_POST['log']) ? $_POST['log'] : ''; ?>">
				</div>
				<div class="log_pass">
					<label for="user_password">Password</label>
					<input name="pwd" id="user_password" type="password">
				</div>
				<?php
				ob_start();
				do_action( 'login_form' );
				echo ob_get_clean();
				?>
				<?php wp_nonce_field( 'userLogin', 'formType' ); ?>
			</div>
			<button type="submit" class="btn btn-primary loginbtn" style="margin-top:20px">LOG IN</button>
		</form>
		<?php
	} else {
		echo '<p class="error-logged">You are already logged in.</p>';
	}

	$login_form = ob_get_clean();
	return $login_form;
}

add_action( 'wp', 'cr_user_login_callback' );

function cr_user_login_callback() {

	if ( isset( $_POST['formType'] ) && wp_verify_nonce( $_POST['formType'], 'userLogin' ) ) {
		global $errors_login;
		$uName = $_POST['log'];
		$uPassword = $_POST['pwd'];		
    $uemail=$_POST['log'];

		if ($uName == '' && $uPassword != '') {
			$errors_login = '<strong>Error! </strong> Username is required.';
		} elseif ($uName != '' && $uPassword == '') {
			$errors_login = '<strong>Error! </strong> Password is required.';
		} elseif ($uName == '' && $uPassword == '') {
			$errors_login = '<strong>Error! </strong> Username & Password are required.';
		} elseif ($uName != '' && $uPassword != '') {
			$creds = array();
			$creds['user_login'] = $uName;
      $creds['user_email'] = $uemail;
			$creds['user_password'] = $uPassword;
			$creds['remember'] = false;
			$user = wp_signon( $creds, false );
			if ( is_wp_error($user) ) {
				$errors_login = $user->get_error_message();
			} else {				
				wp_set_current_user( $user->ID );
				wp_set_auth_cookie( $user->ID );
				do_action( 'wp_login', $user->user_login, $user );
				wp_redirect( site_url() );
				exit;
			}
		}
	}
}
// Hook into the authentication process
//add_action('wp_authenticate', 'check_user_status', 10, 2);

function check_user_status($username, $password) {
    // Get the user object by the username
    $user = get_user_by('login', $username);
    
    // Check if the user exists and their status is approved
    if ($user && get_user_meta($user->ID, 'approved_status', true) === 'approved') {
        // User is approved, proceed with login
        return;
    } else {
        // User is not approved or does not exist
        // You can customize the error message or redirect the user to a specific page
        wp_die('Your account is not approved. Please contact the administrator for assistance.');
    }
}


?>




