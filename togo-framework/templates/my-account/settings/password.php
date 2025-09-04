<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get the current user ID.
$current_user = wp_get_current_user();
if (!$current_user || !isset($current_user->ID)) {
    return; // User not found or not logged in.
}

// Get the current page URL.
$current_url = strtok((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", '?');
// Remove param status
$current_url = remove_query_arg('status', $current_url);
$password_url = add_query_arg('tab', 'password', $current_url);

// Check if the form is submitted.
if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'togo_my_account_setting_password')) {
    $current_password = sanitize_text_field($_POST['current_password']);
    $password = sanitize_text_field($_POST['password']);
    $confirm_password = sanitize_text_field($_POST['confirm_password']);

    $user_id = $current_user->ID;

    // Get the user object
    $user = get_userdata($user_id);

    // Get the hashed password
    $hashed_password = $user->user_pass;

    // Check if the current password is correct
    if (wp_check_password($current_password, $hashed_password, $user_id)) {
        if ($password === $confirm_password) {
            // Check if the password contains at least one uppercase letter, one lowercase letter, one number, and one special character
            $uppercase = preg_match('@[A-Z]@', $password);
            $lowercase = preg_match('@[a-z]@', $password);
            $number    = preg_match('@[0-9]@', $password);
            $specialChars = preg_match('/[!@#\$%\^&\*\(\),\.\?":\{\}\|<>]/', $password);

            if ($uppercase && $lowercase && $number && $specialChars) {
                wp_set_password($password, $user_id);
                echo '<div class="togo-notice togo-notice-success">';
                echo \Togo\Icon::get_svg('check-circle', 'success-icon');
                echo '<p>' . esc_html__('Your password has been changed successfully.', 'togo-framework') . '</p>';
                echo '</div>';
            } else {
                echo '<div class="togo-notice togo-notice-error">';
                echo \Togo\Icon::get_svg('letter-x-circle', 'error-icon');
                echo '<p>' . esc_html__('Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.', 'togo-framework') . '</p>';
                echo '</div>';
            }
        } else {
            echo '<div class="togo-notice togo-notice-error">';
            echo \Togo\Icon::get_svg('letter-x-circle', 'error-icon');
            echo '<p>' . esc_html__('Passwords do not match.', 'togo-framework') . '</p>';
            echo '</div>';
        }
    } else {
        echo '<div class="togo-notice togo-notice-error">';
        echo \Togo\Icon::get_svg('letter-x-circle', 'error-icon');
        echo '<p>' . esc_html__('Current password is incorrect.', 'togo-framework') . '</p>';
        echo '</div>';
    }
}
?>
<div class="password-wrapper">
    <ul class="settings-tabs">
        <li><a href="<?php echo esc_url($current_url); ?>"><?php echo esc_html__('Basic Info', 'togo-framework'); ?></a></li>
        <li><a href="<?php echo esc_url($password_url); ?>" class="active"><?php echo esc_html__('Password', 'togo-framework'); ?></a></li>
    </ul>
    <form action="<?php echo esc_url($password_url); ?>" method="post" class="togo-my-settings-form">
        <div class="field-password form-field form-text-field">
            <label for="current_password" class="field-password__label"><?php echo esc_html__('Current Password', 'togo-framework'); ?></label>
            <input type="password" name="current_password" id="current_password" value="">
        </div>
        <div class="field-password form-field form-text-field">
            <label for="password" class="field-password__label"><?php echo esc_html__('Password', 'togo-framework'); ?></label>
            <input type="password" name="password" id="password" value="">
        </div>
        <div class="field-password form-field form-text-field">
            <label for="confirm_password" class="field-password__label"><?php echo esc_html__('Confirm Password', 'togo-framework'); ?></label>
            <input type="password" name="confirm_password" id="confirm_password" value="">
        </div>
        <div class="form-submit">
            <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('togo_my_account_setting_password')); ?>">
            <button type="submit" class="togo-button full-filled"><?php echo esc_html__('Save', 'togo-framework'); ?></button>
        </div>
    </form>
</div>