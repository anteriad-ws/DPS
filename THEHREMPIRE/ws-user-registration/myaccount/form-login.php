<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/user-registration/myaccount/form-login.php.
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
 * @version 1.4.7
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$form_template = get_option('user_registration_login_options_form_template', 'default');
$template_class = '';

if ('bordered' === $form_template) {
    $template_class = 'ur-frontend-form--bordered';
} elseif ('flat' === $form_template) {
    $template_class = 'ur-frontend-form--flat';
} elseif ('rounded' === $form_template) {
    $template_class = 'ur-frontend-form--rounded';
} elseif ('rounded_edge' === $form_template) {
    $template_class = 'ur-frontend-form--rounded ur-frontend-form--rounded-edge';
}
?>

<?php apply_filters('user_registration_login_form_before_notice', ur_print_notices()); ?>

<?php do_action('user_registration_before_customer_login_form'); ?>

<div class="ur-frontend-form login <?php echo $template_class; ?>" id="ur-frontend-form">

    <form class="user-registration-form user-registration-form-login login" method="post">

        <div class="ur-form-row">
            <div class="ur-form-grid">
<?php do_action('user_registration_login_form_start'); ?>

                <p class="user-registration-form-row user-registration-form-row--wide form-row form-row-wide">
                        <!--<label for="username"><?php _e('Username or email address', 'user-registration'); ?> <span class="required">*</span></label>-->
                    <input type="text" class="user-registration-Input user-registration-Input--text input-text" name="username" id="username" value="<?php echo (!empty($_POST['username']) ) ? esc_attr($_POST['username']) : ''; ?>" placeholder="Business E-mail *" />
                </p>
                <p class="user-registration-form-row user-registration-form-row--wide form-row form-row-wide<?php echo ( 'yes' === get_option('user_registration_login_option_hide_show_password', 'no') ) ? ' hide_show_password' : ''; ?>">
                        <!--<label for="password"><?php _e('Password', 'user-registration'); ?> <span class="required">*</span></label>-->
                    <span class="password-input-group">
                        <input class="user-registration-Input user-registration-Input--text input-text" type="password" name="password" id="password" placeholder="Password *" />
<?php
if ('yes' === get_option('user_registration_login_option_hide_show_password', 'no')) {
    ?>
                            <a href="javaScript:void(0)" class="password_preview dashicons dashicons-hidden" title="<?php echo __('Show password', 'user-registration'); ?>"></a>
                            <?php
                        }
                        ?>
                    </span>
                </p>

                        <?php
                        if (!empty($recaptcha_node)) {
                            echo '<div id="ur-recaptcha-node" style="width:100px;max-width: 100px;"> ' . $recaptcha_node . '</div>';
                        }
                        ?>

                <?php do_action('user_registration_login_form'); ?>

  <div class="checkbox-group required">
                            <p class="form-row" style="text-align: left;margin: 20px 0 0px;">
               
                                <input class="user-registration-form__input user-registration-form__input-checkbox" name="checkbox_name[]" required="" type="checkbox" style="width:5%;"> <span style="color:#3b4a3f">By signing in, I consent to receive all content formats like news, resources, blogs, videos and newsletters. <a href="https://cloud-tech-alert.com/terms-of-service/" style="font-size: 14px;font-weight: 500;line-height: 20px;color: #2291ed !important;"> Terms of Service </a> and <a href="https://anteriad.com/privacy-policy/" target="_blank" style="font-size: 14px;
    font-weight: 500;line-height: 20px;color: #2291ed !important;"> Privacy Policy</a>.</span>
                            </p>
                   <br>
                </div>
                <p class="form-row">
                    <input type="submit" class="btn-primary btn-lg btn-block wpb_button btn td-login-button ur-submit-button" style="margin-right:0;color: #fff;text-transform: capitalize;font-size: 15px;max-width:100%;border-radius:5px;" name="login" value="<?php esc_attr_e('Sign In', 'user-registration'); ?>" />
                    <span class="clearfix"></span>
                </p>

                <p class="form-row">
                    <span class="pull-left">
                        <?php wp_nonce_field('user-registration-login', 'user-registration-login-nonce'); ?>

                        <input type="hidden" name="redirect" value="<?php echo isset($redirect) ? $redirect : the_permalink(); ?>" />

                        <?php
                        $remember_me_enabled = get_option('user_registration_login_options_remember_me', 'yes');

                        if ('yes' === $remember_me_enabled) {
                            ?>
                            <label class="user-registration-form__label user-registration-form__label-for-checkbox inline">
                                <input class="user-registration-form__input user-registration-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span style="color:#3b4a3f;font-size:14px"><?php _e('Stay signed in', 'user-registration'); ?></span>
                            </label>
                            <?php
                        }
                        ?>
                    </span>
                    <span class="pull-right lost-password-span">
                        <?php
                        $lost_password_enabled = get_option('user_registration_login_options_lost_password', 'yes');

                        if ('yes' === $lost_password_enabled) {
                            ?>
                            <span class="user-registration-LostPassword lost_password">
                                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" style="font-size:14px"><?php _e('Forgot your password?', 'user-registration'); ?></a>
                            </span>
                            <?php
                        }
                        ?>
                    </span>
                    <span class="clearfix"></span>
                </p>
                



<?php
$url_options = get_option('user_registration_general_setting_registration_url_options');

if (!empty($url_options)) {
    echo '<p class="user-registration-register register">';
    $label = get_option('user_registration_general_setting_registration_label');

    if (!empty($label)) {
        ?>
                        <a href="<?php echo get_option('user_registration_general_setting_registration_url_options'); ?>"> <?php echo get_option('user_registration_general_setting_registration_label'); ?>
                        </a>
                        <?php
                    } else {
                        update_option('user_registration_general_setting_registration_label', __('Not a member yet? Register now.', 'user-registration'));
                        ?>
                        <a href="<?php echo get_option('user_registration_general_setting_registration_url_options'); ?>"> <?php echo get_option('user_registration_general_setting_registration_label'); ?>
                        </a>
                        <?php
                    }
                    echo '</p>';
                }
                ?>
                </p>
                <?php do_action('user_registration_login_form_end'); ?>
            </div>
        </div>
    </form>

</div>

<?php do_action('user_registration_after_login_form'); ?>
