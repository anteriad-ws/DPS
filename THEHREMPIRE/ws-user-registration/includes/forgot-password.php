<?php
add_shortcode( 'cr_forgot_pwd_form', 'cr_forgot_pwd_form_callback' );

function cr_forgot_pwd_form_callback() {
	ob_start();

	

		global $getPasswordError, $getPasswordSuccess;

		if ( !empty( $getPasswordError ) ) {
			?>
			<div class="alert alert-danger">
				<?php echo $getPasswordError; ?>
			</div>
		<?php } ?>

		<?php if ( !empty( $getPasswordSuccess ) ) { ?>
			<br/>
			<div class="alert alert-success">
				<?php echo $getPasswordSuccess; ?>
			</div>
		<?php } ?>

		<form method="post" class="wc-forgot-pwd-form">
			<div class="forgot_pwd_form">
				<div class="log_user">
					<label for="user_login">Username or E-mail:</label>
					<?php $user_login = isset($_POST['user_login']) ? $_POST['user_login'] : ''; ?>
					<input type="text" name="user_login" id="user_login" value="<?php echo $user_login; ?>" />
				</div>
				<div class="log_user">
					<?php
					ob_start();
					//do_action( 'lostpassword_form' );
					echo ob_get_clean();
					?>
					<?php wp_nonce_field('userGetPassword', 'formType'); ?>
					<button type="submit" name="getpassword" class="get_new_password">Get New Password</button>
				</div>
			</div>
		</form>
		<?php
	
    return ob_get_clean();
	//$forgot_pwd_form = ob_get_clean();
	//return $forgot_pwd_form;
}

add_action('wp', 'cr_user_forgot_pwd_callback');

function cr_user_forgot_pwd_callback() {
	if ( isset( $_POST['getpassword'] )  ) {

		global $getPasswordError, $getPasswordSuccess;

		$email = trim( $_POST['user_login'] );

		if ( empty( $email ) ) {
			$getPasswordError = '<strong>Error! </strong>Enter a e-mail address.';
		} else if ( !is_email( $email ) ) {
			$getPasswordError = '<strong>Error! </strong>Invalid e-mail address.';
		} else if ( !email_exists( $email ) ) {
			$getPasswordError = '<strong>Error! </strong>There is no user registered with that email address.';
		} else {

              // lets generate our new password
			$random_password = wp_generate_password( 12, false );


              // Get user data by field and data, other field are ID, slug, slug and login
			$user = get_user_by( 'email', $email );

			$update_user = wp_update_user( array(
				'ID' => $user->ID,
				'user_pass' => $random_password
			) );

              // if  update user return true then lets send user an email containing the new password
			if ( $update_user ) {
				$to = $email;
				
                global $wpdb;
                $table = 'email_settings';
                $query = $wpdb->prepare("SELECT * FROM `{$table}` WHERE id=4");
                $row = $wpdb->get_row($query);
                $brand_name = get_bloginfo('name');
                $email_body = replace_text('[random_password]', $random_password, $row->email_template);
                $message = apply_filters('wpa_email_message', __($email_body));
                $subject2 = $row->subject;
                $subject = $brand_name . ':' . $subject2;

				//$message = 'Your new password is: ' . $random_password;

                  /* $headers[] = 'MIME-Version: 1.0' . "\r\n";
                    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers[] = "X-Mailer: PHP \r\n";
                    $headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n"; */
                    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

                    $mail = wp_mail( $to, $subject, $message, $headers );
                    if ( $mail ) {
                    	$getPasswordSuccess = '<strong>Success! </strong>Check your email address for you new password.';
                    }
                } else {
                	$getPasswordError = '<strong>Error! </strong>Oops something went wrong.';
                }
            }
        }
    }
//****Change Password */
    add_shortcode( 'cr_change_pwd_form', 'cr_change_pwd_form_callback' );
    
    function cr_change_pwd_form_callback() {
        ob_start();
        if ( is_user_logged_in() ) {
    
            global $changePasswordError, $changePasswordSuccess;
    
            if ( !empty( $changePasswordError ) ) {
                ?>
                <div class="alert alert-danger">
                    <?php echo $changePasswordError; ?>
                </div>
            <?php } ?>
    
            <?php if ( !empty( $changePasswordSuccess ) ) { ?>
                <br/>
                <div class="alert alert-success">
                    <?php echo $changePasswordSuccess; ?>
                </div>
            <?php } ?>
                <h2 class="text-center dashboard_title">Change Password</h2>
            <form method="post" class="wc-change-pwd-form">
                <div class="change_pwd_form">
    
                    <div class="log_pass">
                        <label for="user_oldpassword">Current password</label>
                        <input type="password" name="user_opassword" id="user_oldpassword" />
                    </div>
    
                    <div class="log_pass">
                        <label for="user_password">New Password</label>
                        <input type="password" name="user_password" id="user_password" />
                    </div>
    
                    <div class="log_pass">
                        <label for="user_cpassword">Confirm Password</label>
                        <input type="password" name="user_cpassword" id="user_cpassword" />
                    </div>
    
                    <div class="log_pass">
                        <?php
                        ob_start();
                        do_action( 'password_reset' );
                        echo ob_get_clean();
                        ?>
                    </div>
    
                    <div class="log_user">
                        <?php wp_nonce_field( 'changePassword', 'formType' ); ?>
                        <button type="submit" class="register_user">Save changes</button>
                    </div>
    
                </div>
            </form>
            <?php
        }
        $change_pwd_form = ob_get_clean();
        return $change_pwd_form;
    }
    
    add_action( 'wp', 'cr_user_change_pwd_callback' );
    
    function cr_user_change_pwd_callback() {
    
        if ( isset( $_POST['formType'] ) && wp_verify_nonce( $_POST['formType'], 'changePassword' ) ) {
            global $changePasswordError, $changePasswordSuccess;
    
            $user = wp_get_current_user();
    
            $changePasswordError = '';
            $changePasswordSuccess = '';
            $u_opwd = trim( $_POST['user_opassword'] );
            $u_pwd = trim( $_POST['user_password'] );
            $u_cpwd = trim( $_POST['user_cpassword'] );
    
            if ( $u_opwd == '' || $u_pwd == '' || $u_cpwd == '' ) {
                $changePasswordError .= '<strong>ERROR: </strong> Enter Password.,';
            }
    
            if ( !wp_check_password( $u_opwd, $user->data->user_pass, $user->ID ) ) {
                $changePasswordError .= '<strong>ERROR: </strong> Old Password wrong.,';
            }
    
            if ( $u_pwd != $u_cpwd ) {
                $changePasswordError .= '<strong>ERROR: </strong> Password are not matching.,';
            }
    
            if ( strlen( $u_pwd ) < 7 ) {
                $changePasswordError .= '<strong>ERROR: </strong> Use minimum 7 character in password.,';
            }
    
            $changePasswordError = trim( $changePasswordError, ',' );
            $changePasswordError = str_replace( ",", "<br/>", $changePasswordError );
    
            if ( empty( $changePasswordError ) ) {
                wp_set_password( $u_pwd, $user->ID );
                do_action( 'wp_login', $user->user_login, $user );
                wp_set_current_user( $user->ID );
                wp_set_auth_cookie( $user->ID );
                $changePasswordSuccess = 'Password is successfully updated.';
            }
        }
    }
    

