<!-- This is the main form that displays in the WP Admin -->
<div class="wrap">
    <h1>Headless Payments Settings</h1>
    <form method="post" action="options.php">
        <?php
            settings_fields('hp_settings_group');
            do_settings_sections('hp_settings_group');
        ?>

        <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
            <a href="#" class="nav-tab nav-tab-active" data-tab="paypal">PayPal</a>
            <a href="#" class="nav-tab" data-tab="stripe">Stripe</a>
            <a href="#" class="nav-tab" data-tab="square">Square</a>
        </nav>

        <!-- PayPal Config -->
        <div class="tab-content" data-tab-content="paypal">
            <h2>PayPal</h2>
            <p>This section is your PayPal API configuration.</p>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Gateway</th>
                    <td><input type="checkbox" name="hp_paypal_enabled" value="1" <?php checked(get_option('hp_paypal_enabled'), 1); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Mode</th>
                    <td>
                        <select name="hp_paypal_mode">
                            <option value="sandbox" <?php selected(get_option('hp_paypal_mode'), 'sandbox'); ?>>Sandbox</option>
                            <option value="live" <?php selected(get_option('hp_paypal_mode'), 'live'); ?>>Live</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">PayPal Client ID</th>
                    <td><input type="text" name="hp_paypal_client_id" value="<?php echo esc_attr(get_option('hp_paypal_client_id')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">PayPal Secret</th>
                    <td><input type="password" name="hp_paypal_secret" value="<?php echo esc_attr(get_option('hp_paypal_secret')); ?>" class="regular-text" /></td>
                </tr>
            </table>
        </div>

        <!-- Stripe Config -->
        <div class="tab-content" data-tab-content="stripe" style="display:none;">
            <h2>Stripe</h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Gateway</th>
                    <td><input type="checkbox" name="hp_stripe_enabled" value="1" <?php checked(get_option('hp_stripe_enabled'), 1); ?> /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Mode</th>
                    <td>
                        <select name="hp_stripe_mode">
                            <option value="sandbox" <?php selected(get_option('hp_stripe_mode'), 'sandbox'); ?>>Sandbox</option>
                            <option value="live" <?php selected(get_option('hp_stripe_mode'), 'live'); ?>>Live</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Publishable Key</th>
                    <td><input type="text" name="hp_stripe_publishable_key" value="<?php echo esc_attr(get_option('hp_stripe_publishable_key')); ?>" class="regular-text" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Secret Key</th>
                    <td><input type="password" name="hp_stripe_secret" value="<?php echo esc_attr(get_option('hp_stripe_secret')); ?>" class="regular-text" /></td>
                </tr>
            </table>
        </div>

        <!-- Square Config -->
        <div class="tab-content" data-tab-content="square" style="display:none;">
            <h2>Square</h2>
            <p>Square settings coming soon...</p>
        </div>

        <?php submit_button(); ?>
    </form>
</div>