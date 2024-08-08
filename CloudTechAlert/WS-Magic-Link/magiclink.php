<?php
/*Plugin Name:  WS Magic Link
Description:  The WS Magic Link plugin enables passwordless logins via secure, time-sensitive email links, enhancing user experience and security on WordPress sites. Ideal for simplifying access to membership..
Version:      1.5
Author:       Elumalai
Development:  2023/July
*/

// Start writing code after this line!

//Activation hook handler
register_activation_hook( __FILE__, 'magiclink_install_db_table' );

function magiclink_install_db_table() {

    global $wpdb;
    $table_name = $wpdb->prefix .'magic_link';

    $sql = 'CREATE TABLE ' . $table_name . ' (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              subject mediumtext NOT NULL,
              html_code mediumtext NOT NULL,  
              status mediumint(4) NOT NULL,
              user_role mediumtext NOT NULL,
              created_on datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,
              modified_on datetime DEFAULT "0000-00-00 00:00:00" NOT NULL,

              UNIQUE KEY id (id)
        );';

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
    

      $wpdb->query($wpdb->insert( $wpdb->prefix . 'magic_link', array( 'id' => 1, 'subject' => 'Your Magic Link Account!', 'html_code' => '<table border="0" style="width:100%">
        <tbody>
        <tr>
            <td style="text-align:center"><a href="{{home_url}}"><img alt="{{brand_name}}" src="{{email_logo_url}}" style="height:49px; width:200px"></a></td>
        </tr>
        </tbody>
        </table>
        <table border="0" style="width:100%">
        <tbody>
        <tr>
            <td>
             <p>Here’s your Magic Link to {{brand_name}}</p>
             <p>Forget about passwords. Click on the magic link below to instantly sign in to your account</p>
             <p><a href="[unique_url]" style="text-decoration:none"><font style="background-color: rgb(28 62 133);padding:10px;color:#fff;">Click Here Magic link </font></a></p>
             <p>However, be sure to click on the link before it&#39;s magic expires - which is 10 minutes. Feel the magic!</p>
             <p>Yours,</p>

            <p>{{brand_name}}</p>
            </td>
        </tr>
        </tbody>
        </table>
        <table align="center" border="0" cellpadding="0" cellspacing="0" style="text-align: center; width: 100%;background-color: rgb(239, 239, 239);padding-top:20px">
    <tbody>
        <tr>
            <td>
            <h3><font color="#000000">Follow us on</font></h3>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:top">
            <table align="center">
                <tbody>
                    <tr>
                        <td><a href="{{facebook_link}}" target="_blank" style="background-color: rgb(239, 239, 239);"><img alt="Facebook" src="https://cloud-tech-alert.com/assets/facebook.png" style="height:49px; width:50px"> </a></td>
                        <td><a href="{{twitter_link}}" target="_blank" style="background-color: rgb(239, 239, 239);"><img alt="Twitter" src="https://cloud-tech-alert.com/assets/twitter.png" style="height:49px; width:50px"> </a></td>
                        <td><a href="{{linkedin_link}}" target="_blank" style="background-color: rgb(239, 239, 239);"><img alt="LinkedIn" src="https://cloud-tech-alert.com/assets/linkedin.png" style="height:49px; width:50px"> </a></td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
    </tbody>
</table>

<table border="0" cellpadding="10" cellspacing="0" style="width:100%;background-color: rgb(239, 239, 239);">
    <tbody>
        <tr>
            <td style="text-align:center">
            <p><a href="{{home_url}}/contact-us/">Contact Us </a></p>

            <p><a href="{{home_url}}/terms-of-service/">Terms of service </a></p>

            <p><a href="https://anteriad.com/privacy-policy/" target="_blank">Privacy Policy </a></p>

            <p>©{{curr_year}}  {{brand_name}} c/o Anteriad . All rights reserved.</p>

            <p>2 International Drive, Rye Brook, New York 10573, USA</p>

            <p>This email cannot be copied, distributed, or displayed without prior written permission from {{brand_name}}</p>
            </td>
        </tr>
    </tbody>
</table>','status' => 0 , 'user_role' => 'author') )

            );
}

/* frontend login */

/* * ********* * start  Add scripts and styles to the front-end  *************** */
function magic_link_css_and_js_files() {
    wp_enqueue_style('magic-link', plugins_url('/css/magic-link.css', __FILE__), false, '1.0.0', 'all');
    wp_enqueue_script('magic-link-js', plugins_url('/js/magic-link.js', __FILE__), false, '1.0.0', 'all');
}

add_action('wp_enqueue_scripts', 'magic_link_css_and_js_files');
/* End javascript & css file */

/* * ********* * start - Shortcode for the frontend login ** ************** */

function magiclink_front_end_login() {
    ob_start();
    $account = ( isset($_POST['user_email_username']) ) ? $account = sanitize_text_field($_POST['user_email_username']) : false;
    //$userrole=explode(",",$result[0]->user_role);
    $nonce = ( isset($_POST['nonce']) ) ? $nonce = sanitize_key($_POST['nonce']) : false;
    $error_token = ( isset($_GET['wpa_error_token']) ) ? $error_token = sanitize_key($_GET['wpa_error_token']) : false;
    $sent_link = magic_send_link($account, $nonce);

    if ($account && !is_wp_error($sent_link)) {
        echo '<p class="wpa-box wpa-success">' . apply_filters('wpa_success_link_msg', __('Please check your email. You will soon receive an email with a login link.', 'passwordless')) . '</p>';
        
        // Redirect to the desired tab or page with fragment
        // wp_redirect(home_url('/sign-in/#magic-link-div'));
        // exit;
    } elseif (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        echo '<p class="wpa-box wpa-alert">' . apply_filters('wpa_success_login_msg', sprintf(__('You are currently logged in as %1$s. %2$s', 'profilebuilder'), '<a href="' . $authorPostsUrl = get_author_posts_url($current_user->ID) . '" title="' . $current_user->display_name . '">' . $current_user->display_name . '</a>', '<a href="' . wp_logout_url($redirectTo = wpa_curpageurl()) . '" title="' . __('Log out of this account', 'passwordless') . '">' . __('Log out', 'passwordless') . ' &raquo;</a>')) . '</p><!-- .alert-->';
    } else {
        ?>
        <p>
            <b>Long password? Hard to type? </b><br />
        <p style="font-size: 12px">Worry not! Save time with our magic link and sign in instantly.</p>
        </p>
        <form name="magicloginform" id="magicloginform" action="" method="post">
           <!-- <label for="user_email_username"><?php _e('Login with email ') ?></label>-->
            <p class="magic-box" style="margin-bottom:30px">
                <input type="email" name="user_email_username" placeholder="Enter your business email" id="user_email_username" class="input" value="<?php echo esc_attr($account); ?>" size="25" required/>

                <input type="submit" name="wpa-submit" id="wpa-submit" class="button-primary" value="<?php esc_attr_e('Email me magic link'); ?>" />

            </p>
        <?php do_action('wpa_login_form'); ?>
            <?php wp_nonce_field('wpa_passwordless_login_request', 'nonce', false) ?>

        </form>
        <?php
        if (is_wp_error($sent_link)) {
            echo '<p class="wpa-box wpa-error">' . apply_filters('wpa_error', $sent_link->get_error_message()) . '</p>';
        }
        if ($error_token) {
            echo '<p class="wpa-box wpa-error">' . apply_filters('wpa_invalid_token_error', __('Your token has probably expired. Please try again.', 'passwordless')) . '</p>';
        }
    }

    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_shortcode('magiclink-login', 'magiclink_front_end_login');

// // Enqueue custom script to handle tab switching
// function enqueue_custom_scripts() {
//     wp_enqueue_script('custom-tab-script', get_template_directory_uri() . '/js/custom-tab.js', array('jquery'), null, true);
    
// }
// add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
/* * ********** End Magic frontend login *************** */



 /*function insert_smart_tags($args){

  $content = $args['message'];
  $brand_name = get_bloginfo('name');
  $home_url = home_url();
  $facebook_link = esc_attr(get_option('facebook_link'));
  $twitter_link = esc_attr(get_option('twitter_link'));
  $linkedin_link = esc_attr(get_option('linkedin_link'));
  $instagram_link = esc_attr(get_option('instagram_link'));
  $curr_year = date('Y');

  $content    = str_replace( '{{facebook_link}}', $facebook_link, $content );
  $content    = str_replace( '{{twitter_link}}', $twitter_link, $content );
  $content    = str_replace( '{{linkedin_link}}', $linkedin_link, $content );
  $content    = str_replace( '{{instagram_link}}', $instagram_link, $content );
  $content    = str_replace( '{{curr_year}}', $curr_year, $content );
  $content    = str_replace( '{{brand_name}}', $brand_name, $content );
  $content    = str_replace( '{{home_url}}', $home_url, $content );

  $args['message'] = $content;

  return $args;

  }
  add_filter('wp_mail','insert_smart_tags', 10,1); */

/* * ********** start ****** Sends an email with the unique login link. *************** */

function replace_text($text, $replace_text, $html_code) {
    $replaced_text = str_replace($text, $replace_text, $html_code);
    return $replaced_text;
}

function set_mail_content_type() {
    return "text/html";
}

function magic_send_link($email_account = false, $nonce = false) {
    if ($email_account == false) {
        return false;
    }
    $valid_email = magiclink_valid_account($email_account);
    $errors = new WP_Error;
    if (is_wp_error($valid_email)) {
        $errors->add('invalid_account', $valid_email->get_error_message());
    } else {
        global $wpdb;
        $table = $wpdb->prefix . 'magic_link';
        $query = $wpdb->prepare("SELECT * FROM `{$table}` WHERE id=1");

//$query =  $wpdb->prepare("select * FROM $table ORDER BY id DESC LIMIT 1");
        $row = $wpdb->get_row($query);
        $unique_url = wpa_generate_url($valid_email, $nonce);
        $email_body = replace_text('[unique_url]', $unique_url, $row->html_code);
        //$subject = apply_filters('wpa_email_message', __("Login at $blog_name by visiting this url: $unique_url"), $unique_url);
        $message = apply_filters('wpa_email_message', __($email_body));
        $brand_name = get_bloginfo('name');
        $subject2 = $row->subject;
        $subject = $brand_name . ':' . $subject2;
        add_filter('wp_mail_content_type', 'set_mail_content_type');
        $sent_mail = wp_mail($valid_email, $subject, $message);

        if (!$sent_mail) {
            $errors->add('email_not_sent', __('There was a problem sending your email. Please try again or contact an admin.'));
        }
    }
    $error_codes = $errors->get_error_codes();

    if (empty($error_codes)) {
        return false;
    } else {
        return $errors;
    }
}

/* * ****** End **** Sends an email with the unique login link ************ */

/* * ****** Start **** Checks to see if an account is valid. Either email and user roles permission and  Pending Approval ************ */

function magiclink_valid_account($account) {

    /* if (!empty($account)) {
      echo 'Email is required.';
      } */
    //$user = wp_get_current_user();


    if (is_email($account)) {
        $account = sanitize_email($account);
    } else {
        $account = sanitize_user($account);
    }
    if (!is_email($account)) {

        return new WP_Error('invalid_account', __('The email address isn’t correct.', 'magiclink-login'));
    }
    if (is_email($account) && email_exists($account)) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'magic_link';
        $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1");
        if (isset($result[0])) {
            $userrole = explode(",", $result[0]->user_role);
            $user = get_user_by('email', $account);
            $form_id = ur_get_form_id_by_userid($user->ID);
            $general_login_option = get_option('user_registration_general_setting_login_options', 'default');
            //$user_id      = $user->ID;
            //$user_manager = new UR_Admin_User_Manager( $user_id );
            //$status = $user_manager->get_user_status();

            /*             * *
             * *
             *  checking user account pending Approval ****
             * *
             */
            if ('email_confirmation' === ur_get_single_post_meta($form_id, 'user_registration_form_setting_login_options', $general_login_option)) {
                $email_status = get_user_meta($user->ID, 'ur_confirm_email', true);
                if ($email_status === '0') {
                    $message = ' Your Account is Pending Approval. Please verify your Email. ';
                    return new WP_Error('user_email_not_verified', $message);
                }
                //return new WP_Error('invalid_account', __('sorry! Your account is still pending approval.', 'magiclink-login'));
            }

            /**
             * ** checking Restrict access for (Select the User Roles) ****
             * */
            foreach ($userrole as $role) {
                if (in_array($role, $user->roles)) {
                    return new WP_Error('invalid_account', __('Access Denied! Permission Required for Account Access.', 'magiclink-login'));
                }
            }
        }


        return $account;
    }

    if (!is_email($account) && username_exists($account)) {
        $user = get_user_by('login', $account);
        //print_r($account);  
        //print_r($user); die();

        if ($user) {
            return $user->data->user_email;
        }
    }

    return new WP_Error('invalid_account', __('Email Not Found! The provided email does not exist. Please try again.', 'magiclink-login'));
}

/* * ****** End **** Checks to see if an account is valid. Either email and user roles permission and  Pending Approval ************ */

/* * ****** start **** Generates unique URL ******* */

function wpa_generate_url( $email = false, $nonce = false ){
	if ( $email  == false ){
		return false;
	}
	/* get user id */
	$user = get_user_by( 'email', $email );
	$token = wpa_create_onetime_token( 'wpa_'.$user->ID, $user->ID  );

	$arr_params = array( 'wpa_error_token', 'uid', 'token', 'nonce' );
	$url = remove_query_arg( $arr_params, wpa_curpageurl() );

    $url_params = array('uid' => $user->ID, 'token' => $token, 'nonce' => $nonce);
    $url = add_query_arg($url_params, $url);

	return $url;
}

/* * ****** End ****Generates unique URL ******* */


/**
 * Automatically logs in a user with the correct userid ** */
add_action('init', 'wpa_autologin_via_url');
function wpa_autologin_via_url(){

    if( $_SERVER['REQUEST_METHOD'] === "HEAD" ){
        // Redirect to HomePage when REQUEST_METHOD is set to HEAD (avoid issues regarding antivirus Link Protection)
        wp_redirect( home_url(), 301 );
        exit;
    }

	if( isset( $_GET['token'] ) && isset( $_GET['uid'] ) && isset( $_GET['nonce'] ) ){
		$uid   = sanitize_key( $_GET['uid'] );
		$token = sanitize_key( $_REQUEST['token'] );
		$nonce = sanitize_key( $_REQUEST['nonce'] );

		$hash_meta            = get_user_meta( $uid, 'wpa_' . $uid, true);
		$hash_meta_expiration = get_user_meta( $uid, 'wpa_' . $uid . '_expiration', true);
		$arr_params           = array( 'uid', 'token', 'nonce' );
		$current_page_url     = remove_query_arg( $arr_params, wpa_curpageurl() );

		require_once( ABSPATH . 'wp-includes/class-phpass.php');
		$wp_hasher = new PasswordHash(8, TRUE);
		$time      = time();

		$wppb_generalSettings = get_option('wppb_general_settings', 'not_found');//profile builder settings are required for admin approval compatibility

		if ( ! $wp_hasher->CheckPassword($token . $hash_meta_expiration, $hash_meta) || $hash_meta_expiration < $time || ! wp_verify_nonce( $nonce, 'wpa_passwordless_login_request' ) ){
			wp_redirect( $current_page_url . '?wpa_error_token=true' );
			exit;
		}else if ( defined('PROFILE_BUILDER_VERSION') && $wppb_generalSettings != 'not_found' && !empty( $wppb_generalSettings['adminApproval'] ) && $wppb_generalSettings['adminApproval'] == 'yes' && wp_get_object_terms( $uid, 'user_status' ) ){//admin approval compatibility
            wp_redirect( $current_page_url . '?wpa_adminapp_error=true' );
            exit;
        }
		else {
			wp_set_auth_cookie( $uid );
			delete_user_meta($uid, 'wpa_' . $uid );
			delete_user_meta($uid, 'wpa_' . $uid . '_expiration');

			$total_logins = get_option( 'wpa_total_logins', 0);
			update_option( 'wpa_total_logins', $total_logins + 1);

			if ( function_exists('wppb_custom_redirect_url') ){
				$wppb_custom_redirects_url = wppb_custom_redirect_url( 'after_login', $current_page_url );
			}

			$redirect_url = !empty( $wppb_custom_redirects_url ) ? $wppb_custom_redirects_url : $current_page_url;

			wp_redirect( apply_filters('wpa_after_login_redirect', $redirect_url ) );
			exit;
		}

	}
}

/**
 * End   ** */

/* * ****** start ****Create a one time token based on transients ******* */

function wpa_create_onetime_token( $action = -1, $user_id = 0 ) {
	$time = time();

	// random salt
	$key = wp_generate_password( 20, false );

	require_once( ABSPATH . 'wp-includes/class-phpass.php');
	$wp_hasher = new PasswordHash(8, TRUE);
	$string = $key . $action . $time;

	// we're sending this to the user
	$token  = wp_hash( $string );
	$expiration = apply_filters('wpa_change_link_expiration', $time + 60*10);
	$expiration_action = $action . '_expiration';

	// we're storing a combination of token and expiration
	$stored_hash = $wp_hasher->HashPassword( $token . $expiration );

	update_user_meta( $user_id, $action , $stored_hash ); // adjust the lifetime of the token. Currently 10 min.
	update_user_meta( $user_id, $expiration_action , $expiration );
	return $token;
}


/* * ****** End ****Create a one time token based on transients ******* */


/**
 * Returns the home page URL
 */
function wpa_curpageurl() {
    $req_uri = $_SERVER['REQUEST_URI'];

	$parsed_url = parse_url( home_url(), PHP_URL_PATH );

    if( !empty( $parsed_url ) )
        $home_path = trim( $parsed_url, '/' );
    else
        $home_path = $parsed_url;

    if( $home_path === null || $home_path === false )
        $home_path = '';

    $home_path_regex = sprintf( '|^%s|i', preg_quote( $home_path, '|' ) );

    // Trim path info from the end and the leading home path from the front.
    $req_uri = ltrim($req_uri, '/');
    $req_uri = preg_replace( $home_path_regex, '', $req_uri );
    $req_uri = trim(home_url(), '/') . '/' . ltrim( $req_uri, '/' );

    return $req_uri;
}

/**
 * Function that creates the magic link menu in backend
 */
function ml_add_settings_page() {
    add_options_page('Magic Link plugin', 'Magic Link', 'manage_options', 'magiclink_settings', 'ml_render_plugin_settings_page');
}
add_action('admin_menu', 'ml_add_settings_page');

/**
 * Function that storing email template content & subject & roles in database  *** */
function ml_render_plugin_settings_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'magic_link';

    if (isset($_POST['submit'])) {
        /*
          $wpdb->insert(
          $table_name,
          array(

          'html_code' => htmlspecialchars_decode($_POST['html_code'])),
          ); */
        $datetime = date('Y-m-d H:i:s', current_time('timestamp', 0));
        $magic_content = htmlspecialchars_decode($_POST['html_code']);
        $subject_content = htmlspecialchars_decode($_POST['subject']);
        $userrole = implode(",", $_POST["user_role"]);
        //print_r($userrole); 


        $wpdb->query($wpdb->prepare("UPDATE $table_name 
                SET html_code = '$magic_content',  subject = '$subject_content', user_role='$userrole',  modified_on='{$datetime}' WHERE id = 1")
        );


      

        //  $wpdb->query($wpdb->prepare("UPDATE $table_name 
        //         SET html_code = '$magic_content',  subject = '$subject_content', user_role='$userrole',  modified_on='{$datetime}' WHERE id = 1")
        // );
    }
    ?>

    <!------magic link Back End Email template setting form  and Restrict access ----->

    <h2>Magic Link Email Settings</h2>
    <form action="" id="magic_link" method="post">

        Subject : <?php $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1"); ?>
        <input type="text" name="subject" id="subject" width="100%" style="width:65%" value="<?php echo $result[0]->subject; ?>" /> <br><br>



        <?php
        $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id = 1");
        $userrole = explode(",", $result[0]->user_role);
        //print_r($result[0]->user_role);
        //print_r($userrole); 
        ?>
        <textarea id="html_code" name="html_code"><?php echo $result[0]->html_code; ?></textarea>
        <div class="form-group">
            <label>Restrict access for (Select the User Roles you want to restrict access.)</label> <br>
            <div class="checkbox"><label><input type="checkbox" name="user_role[]" id="user_role" value="subscriber"  <?php if (in_array("subscriber", $userrole)) echo "checked" ?>>subscribe  </label></div>
            <div class="checkbox"> <label><input type="checkbox" name="user_role[]" id="user_role" value="editor" <?php if (in_array("editor", $userrole)) echo "checked" ?> >Editor</label></div>
            <div class="checkbox"><label><input type="checkbox" name="user_role[]" id="user_role" value="administrator" <?php if (in_array("administrator", $userrole)) echo "checked" ?>>Admin</label></div>
            <div class="checkbox"><label><input type="checkbox" name="user_role[]" id="user_role" value="author" <?php if (in_array("author", $userrole)) echo "checked" ?>>Author</label></div>
        </div>
        <button type="submit" id="submit" name="submit" style=" padding: 10px 30px;background: #0e8000;color: #fff;border: none;margin-top: 20px;">Submit</button>
    </table>

    </form>
    <!------ End  ----->

    <!-----Add scripts and styles to the back-end for Email template editor  ----->

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script type="text/javascript">

        jQuery(document).ready(function ($) {
            $('#html_code').summernote();
        });
    </script>
    <!-----End  scripts and styles to the back-end for Email template editor  ----->
    <?php
}
