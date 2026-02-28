<?php
defined('ABSPATH') || exit;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_admin_referer('save');

    if (isset($_POST['test'])) {
        // Globals required to communicate with hook functions
        global $smtp_test_settings, $smtp_error;

        // Intercept errors to display them during tests
        add_action('wp_mail_failed', function ($wp_error) {
            global $smtp_error;
            $smtp_error = $wp_error;
        }, 0);

        // Overrides the mailer configuration during tests
        add_filter('phpmailer_init', function ($mailer) {
            global $smtp_test_settings;

            $mailer->IsSMTP();
            $mailer->Host = $smtp_test_settings['host'];
            $mailer->Port = $smtp_test_settings['port'];
            $mailer->SMTPSecure = $smtp_test_settings['secure'];
            $mailer->SMTPAutoTLS = true;
            $mailer->SMTPAuth = true;
            $mailer->Username = $smtp_test_settings['username'];
            $mailer->Password = $smtp_test_settings['password'];

            return $mailer;
        }, 99);

        $settings = wp_unslash($_POST['data']);
        $smtp_test_settings = $settings;
        wp_mail(sanitize_email($_POST['test_email']), 'Test email from the SMTP plugin', 'This is a simple message to check the correct SMTP connection and message delivery');
        if (isset($smtp_error)) {
            $error = $smtp_error->get_error_message();
            if (stripos($error, 'could not connect') !== false) {
                $error .= '<br><br>This error means you need to check or change the parameters OR, worse, your hosting provider do not let your site to connect to the SMTP.';
            } elseif (stripos($error, 'timeout') !== false) {
                $error .= '<br><br>This error means your hosting provider do not let your site to connect to the SMTP and is blocking it with a firewall rule. Contact them!';
            }
        }
    }

    if (isset($_POST['save'])) {
        // TODO: Add kses, unslash, ...
        $settings = wp_unslash($_POST['data']);

        update_option('smtp_settings', $settings, false);
    }
} else {

    $settings = get_option('smtp_settings', []);
}
?>

<div class="wrap">
    <h2>Settings</h2>
    <?php
    if ($error) {
        echo '<div class="notice notice-error"><p>', wp_kses_post($error), '</p></div>';
    }
    if (!isset($settings['enabled'])) {
        echo '<div class="notice notice-warning"><p>The SMTP is not enabled, when ready enable it.</p></div>';
    }
    ?>

    <p>
        <a href="https://www.satollo.net/plugins/smtp" target="_blank">Read the official page, it's short.</a>. This plugin, when uninstalled,
        does not left traces on your site.
    </p>

    <form method="post">

        <?php wp_nonce_field('save'); ?>

        <table class="form-table">

            <tbody>
                <tr>
                    <th>
                        Enabled
                    </th>
                    <td>
                        <input type="checkbox" name="data[enabled]" <?= isset($settings['enabled']) ? 'checked' : '' ?>>
                        <p class="description">
                            You can run tests without enabling the SMTP connection.
                        </p>
                    </td>
                </tr>

                <tr>
                    <th>
                        Host
                    </th>
                    <td>
                        <input type="text" name="data[host]" size="40" value="<?= esc_attr($settings['host'] ?? ''); ?>">
                        <p class="description"></p>
                    </td>

                </tr>
                <tr>
                    <th>
                        Port
                    </th>
                    <td>
                        <input type="text" name="data[port]" size="5" value="<?= esc_attr($settings['port'] ?? '25'); ?>">
                        <p class="description">25, 465, 587, ...</p>
                    </td>
                </tr>
                <tr>
                    <th>
                        Protocol
                    </th>
                    <td>
                        <input type="text" name="data[protocol]" size="10" value="<?= esc_attr($settings['protocol'] ?? ''); ?>">
                        <p class="description">
                            tls or ssl
                        </p>
                    </td>
                </tr>

                <tr>
                    <th>
                        Username
                    </th>
                    <td>
                        <input type="text" name="data[username]" size="40" value="<?= esc_attr($settings['username'] ?? ''); ?>">
                        <p class="description"></p>
                    </td>

                </tr>
                <tr>
                    <th>
                        Password
                    </th>
                    <td>
                        <input type="password" name="data[password]" size="40" value="<?= esc_attr($settings['password'] ?? ''); ?>">
                        <p class="description"></p>
                    </td>

                </tr>

                </tr>
                <tr>
                    <th>
                        Send a test
                    </th>
                    <td>
                        <input type="email" name="test_email" size="40" value="<?= esc_attr($_POST['test_email'] ?? ''); ?>">
                        <button name="test" class="button button-secondary">Send</button>
                        <p class="description"></p>
                    </td>
                </tr>

            </tbody>
        </table>

        <p>
            <button name="save" class="button button-primary">Save</button>
        </p>

    </form>
    <?php if (WP_DEBUG) { ?>

        <h3>Debug</h3>
        <p>
            That helps me when supporting you...
        </p>
        <pre><?= esc_html(print_r(get_option('smtp_settings'), true)); ?></pre>
    <?php } ?>
</div>
