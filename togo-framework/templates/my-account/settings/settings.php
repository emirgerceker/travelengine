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
if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'togo_my_account_settings')) {
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $birthday = sanitize_text_field($_POST['birthday']);
    $country = sanitize_text_field($_POST['country']);
    $city = sanitize_text_field($_POST['city']);
    $address = sanitize_text_field($_POST['address']);
    $avatar = $_FILES['avatar'];

    $user_id = $current_user->ID;
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);
    update_user_meta($user_id, 'billing_email', $email);
    update_user_meta($user_id, 'billing_phone', $phone);
    update_user_meta($user_id, 'birthday', $birthday);
    update_user_meta($user_id, 'billing_country', $country);
    update_user_meta($user_id, 'billing_city', $city);
    update_user_meta($user_id, 'billing_address_1', $address);

    if (!empty($avatar['tmp_name'])) {
        // Ensure WordPress functions are available.
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $avatar_id = media_handle_upload('avatar', 0);
        if (is_wp_error($avatar_id)) {
            // Handle error if needed.
        } else {
            // Update user avatar.
            update_user_meta($user_id, 'avatar', $avatar_id);
        }
    }

    echo '<div class="togo-notice togo-notice-success">';
    echo \Togo\Icon::get_svg('check-circle', 'success-icon');
    echo '<p>' . esc_html__('Your settings have been saved successfully.', 'togo-framework') . '</p>';
    echo '</div>';
}

$first_name = get_user_meta($current_user->ID, 'first_name', true);
$last_name = get_user_meta($current_user->ID, 'last_name', true);
$email = get_user_meta($current_user->ID, 'billing_email', true);
$billing_phone = get_user_meta($current_user->ID, 'billing_phone', true);
$birdthday = get_user_meta($current_user->ID, 'birthday', true);
$country = get_user_meta($current_user->ID, 'billing_country', true);
$city = get_user_meta($current_user->ID, 'billing_city', true);
$address = get_user_meta($current_user->ID, 'billing_address_1', true);
?>
<div class="settings-wrapper">
    <ul class="settings-tabs">
        <li><a href="<?php echo esc_url($current_url); ?>" class="active"><?php echo esc_html__('Basic Info', 'togo-framework'); ?></a></li>
        <li><a href="<?php echo esc_url($password_url); ?>"><?php echo esc_html__('Password', 'togo-framework'); ?></a></li>
    </ul>
    <form action="<?php echo esc_url($current_url); ?>" method="post" enctype="multipart/form-data" class="togo-my-settings-form">
        <div class="field-avatar form-field">
            <div class="field-avatar__image">
                <div class="field-avatar__image-avatar">
                    <?php
                    $avatar_id = get_user_meta($current_user->ID, 'avatar', true);
                    if ($avatar_id) {
                        echo wp_get_attachment_image($avatar_id, array(70, 70), false, array('class' => 'avatar'));
                    } else {
                        // If the user has a Gravatar, display it.
                        echo get_avatar($current_user->ID, 70);
                    }
                    ?>
                </div>
                <div class="field-avatar__image-info">
                    <span class="field-avatar__image-name"><?php echo esc_html__('Change avatar', 'togo-framework'); ?></span>
                    <span class="field-avatar__image-type"><?php echo esc_html__('PNG or JPG', 'togo-framework'); ?></span>
                </div>
            </div>
            <div class="field-avatar__upload">
                <label for="avatar" class="field-avatar__upload-label">
                    <span><?php echo esc_html__('Upload', 'togo-framework'); ?></span>
                    <input type="file" name="avatar" id="avatar" accept="image/*" value="<?php echo esc_html__('Upload', 'togo-framework'); ?>">
                </label>
            </div>
        </div>
        <div class="form-field form-text-field">
            <label for="first_name"><?php echo esc_html__('First Name', 'togo-framework'); ?><span class="required">*</span></label>
            <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($first_name); ?>" required>
        </div>
        <div class="form-field form-text-field">
            <label for="last_name"><?php echo esc_html__('Last Name', 'togo-framework'); ?><span class="required">*</span></label>
            <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($last_name); ?>" required>
        </div>
        <div class="form-field form-text-field">
            <label for="email"><?php echo esc_html__('Email', 'togo-framework'); ?><span class="required">*</span></label>
            <input type="email" name="email" id="email" value="<?php echo esc_attr($email); ?>" required>
        </div>
        <div class="form-field form-text-field">
            <label for="phone"><?php echo esc_html__('Phone', 'togo-framework'); ?></label>
            <input type="text" name="phone" id="phone" value="<?php echo esc_attr($billing_phone); ?>">
        </div>
        <div class="form-field form-text-field">
            <label for="birthday"><?php echo esc_html__('Birthday', 'togo-framework'); ?></label>
            <input type="text" name="birthday" id="birthday" placeholder="" onfocus="(this.type='date')" onblur="if(!this.value)this.type='text'" value="<?php echo esc_attr($birdthday); ?>" />
        </div>
        <div class="form-field form-text-field">
            <label for="country"><?php echo esc_html__('Country', 'togo-framework'); ?></label>
            <input type="text" name="country" id="country" value="<?php echo esc_attr($country); ?>">
        </div>
        <div class="form-field form-text-field">
            <label for="city"><?php echo esc_html__('City', 'togo-framework'); ?></label>
            <input type="text" name="city" id="city" value="<?php echo esc_attr($city); ?>">
        </div>
        <div class="form-field form-text-field">
            <label for="address"><?php echo esc_html__('Address', 'togo-framework'); ?></label>
            <input type="text" name="address" id="address" value="<?php echo esc_attr($address); ?>">
        </div>
        <div class="form-submit">
            <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('togo_my_account_settings')); ?>">
            <button type="submit" class="togo-button full-filled"><?php echo esc_html__('Save Changes', 'togo-framework'); ?></button>
        </div>
    </form>
</div>