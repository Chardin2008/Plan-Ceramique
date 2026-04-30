<?php

function pcp_settings_defaults(): array
{
    return [
        'visible_email' => getenv('VISIBLE_CONTACT_EMAIL') ?: 'contact@planceramique.fr',
        'phone' => '',
        'city' => 'France',
        'service_area' => getenv('SERVICE_AREA_TEXT') ?: 'Intervention et livraison partout en France.',
        'instagram_url' => '',
        'pinterest_url' => '',
        'linkedin_url' => '',
        'primary_cta_text' => 'Demander un devis',
        'google_site_verification' => '',
    ];
}

function pcp_get_setting(string $key): string
{
    $defaults = pcp_settings_defaults();
    $settings = get_option('pcp_theme_settings', []);
    $legacyEmail = 'contact@' . 'plan-travail-ceramique.fr';
    $value = (string) ($settings[$key] ?? $defaults[$key] ?? '');

    if ($key === 'visible_email' && $value === $legacyEmail) {
        return (string) $defaults[$key];
    }

    return $value;
}

function pcp_register_settings(): void
{
    register_setting(
        'pcp_theme_settings_group',
        'pcp_theme_settings',
        [
            'sanitize_callback' => static function ($input): array {
                return [
                    'visible_email' => sanitize_email($input['visible_email'] ?? ''),
                    'phone' => sanitize_text_field($input['phone'] ?? ''),
                    'city' => sanitize_text_field($input['city'] ?? ''),
                    'service_area' => sanitize_text_field($input['service_area'] ?? ''),
                    'instagram_url' => esc_url_raw($input['instagram_url'] ?? ''),
                    'pinterest_url' => esc_url_raw($input['pinterest_url'] ?? ''),
                    'linkedin_url' => esc_url_raw($input['linkedin_url'] ?? ''),
                    'primary_cta_text' => sanitize_text_field($input['primary_cta_text'] ?? ''),
                    'google_site_verification' => sanitize_text_field($input['google_site_verification'] ?? ''),
                ];
            },
            'default' => pcp_settings_defaults(),
        ]
    );

    add_theme_page(
        __('Réglages Plan Céramique', 'plan-ceramique-premium'),
        __('Réglages Plan Céramique', 'plan-ceramique-premium'),
        'manage_options',
        'pcp-theme-settings',
        'pcp_render_settings_page'
    );
}
add_action('admin_menu', 'pcp_register_settings');

function pcp_render_settings_page(): void
{
    $settings = wp_parse_args(get_option('pcp_theme_settings', []), pcp_settings_defaults());
    ?>
    <div class="wrap">
      <h1><?php esc_html_e('Réglages Plan Céramique Studio', 'plan-ceramique-premium'); ?></h1>
      <form action="options.php" method="post">
        <?php settings_fields('pcp_theme_settings_group'); ?>
        <table class="form-table" role="presentation">
          <tr>
            <th scope="row"><label for="pcp-visible-email"><?php esc_html_e('Email affiché sur le site', 'plan-ceramique-premium'); ?></label></th>
            <td><input id="pcp-visible-email" name="pcp_theme_settings[visible_email]" type="email" class="regular-text" value="<?php echo esc_attr($settings['visible_email']); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="pcp-service-area"><?php esc_html_e('Zone de service', 'plan-ceramique-premium'); ?></label></th>
            <td><input id="pcp-service-area" name="pcp_theme_settings[service_area]" type="text" class="regular-text" value="<?php echo esc_attr($settings['service_area']); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="pcp-phone"><?php esc_html_e('Téléphone', 'plan-ceramique-premium'); ?></label></th>
            <td><input id="pcp-phone" name="pcp_theme_settings[phone]" type="text" class="regular-text" value="<?php echo esc_attr($settings['phone']); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="pcp-city"><?php esc_html_e('Ville / base', 'plan-ceramique-premium'); ?></label></th>
            <td><input id="pcp-city" name="pcp_theme_settings[city]" type="text" class="regular-text" value="<?php echo esc_attr($settings['city']); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="pcp-primary-cta-text"><?php esc_html_e('Texte du CTA principal', 'plan-ceramique-premium'); ?></label></th>
            <td><input id="pcp-primary-cta-text" name="pcp_theme_settings[primary_cta_text]" type="text" class="regular-text" value="<?php echo esc_attr($settings['primary_cta_text']); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="pcp-instagram-url"><?php esc_html_e('Instagram', 'plan-ceramique-premium'); ?></label></th>
            <td><input id="pcp-instagram-url" name="pcp_theme_settings[instagram_url]" type="url" class="regular-text" value="<?php echo esc_url($settings['instagram_url']); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="pcp-pinterest-url"><?php esc_html_e('Pinterest', 'plan-ceramique-premium'); ?></label></th>
            <td><input id="pcp-pinterest-url" name="pcp_theme_settings[pinterest_url]" type="url" class="regular-text" value="<?php echo esc_url($settings['pinterest_url']); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="pcp-linkedin-url"><?php esc_html_e('LinkedIn', 'plan-ceramique-premium'); ?></label></th>
            <td><input id="pcp-linkedin-url" name="pcp_theme_settings[linkedin_url]" type="url" class="regular-text" value="<?php echo esc_url($settings['linkedin_url']); ?>"></td>
          </tr>
          <tr>
            <th scope="row"><label for="pcp-google-site-verification"><?php esc_html_e('Code Google Search Console', 'plan-ceramique-premium'); ?></label></th>
            <td>
              <input id="pcp-google-site-verification" name="pcp_theme_settings[google_site_verification]" type="text" class="regular-text" value="<?php echo esc_attr($settings['google_site_verification']); ?>">
              <p class="description"><?php esc_html_e('Laissez vide en local. Vous pourrez coller ici le code de vérification plus tard.', 'plan-ceramique-premium'); ?></p>
            </td>
          </tr>
        </table>
        <?php submit_button(); ?>
      </form>
    </div>
    <?php
}

function pcp_output_search_console_code(): void
{
    $code = pcp_get_setting('google_site_verification');

    if (!$code) {
        return;
    }

    printf(
        '<meta name="google-site-verification" content="%s" />' . PHP_EOL,
        esc_attr($code)
    );
}
add_action('wp_head', 'pcp_output_search_console_code');
