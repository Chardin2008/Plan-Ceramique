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
        'brand_name' => 'PLAN CÉRAMIQUE',
        'brand_suffix' => 'STUDIO',
        'brand_home_label' => 'Plan Céramique Studio - Accueil',
        'footer_description' => 'Plans de travail en ceramique premium pour cuisines, interieurs et projets architecturaux.',
        'footer_copyright_name' => 'Plan Céramique Studio',
        'primary_cta_text' => 'Demander un devis',
        'primary_cta_url' => '/#devis',
        'footer_contact_link_text' => 'Acceder a la page contact',
        'footer_contact_link_url' => '/contact/',
        'footer_navigation_heading' => 'Navigation',
        'footer_contact_heading' => 'Contact',
        'footer_studio_heading' => 'Studio',
        'footer_email_label' => 'Email',
        'footer_phone_label' => 'Telephone',
        'footer_city_label' => 'Base',
        'footer_service_area_label' => 'Zone',
        'footer_social_fallback_text' => 'Nous contacter',
        'footer_legal_notice_text' => 'Mentions legales',
        'footer_legal_notice_url' => '/mentions-legales/',
        'footer_privacy_text' => 'Confidentialite',
        'contact_form_name_label' => 'Nom',
        'contact_form_email_label' => 'Email',
        'contact_form_phone_label' => 'Telephone',
        'contact_form_message_label' => 'Votre message',
        'contact_form_submit_text' => 'Envoyer le message',
        'quote_form_last_name_label' => 'Nom',
        'quote_form_first_name_label' => 'Prenom',
        'quote_form_email_label' => 'Email',
        'quote_form_phone_label' => 'Telephone',
        'quote_form_city_label' => 'Ville',
        'quote_form_project_type_label' => 'Type de projet',
        'quote_form_project_type_options' => "Plan de travail de cuisine\nIlot central\nCredence assortie\nRenovation de cuisine\nProjet professionnel",
        'quote_form_material_label' => 'Materiau souhaite',
        'quote_form_material_options' => "Ceramique aspect marbre\nCeramique pleine masse\nEffet pierre naturelle\nEffet beton mineral\nA definir avec un conseiller",
        'quote_form_budget_label' => 'Budget approximatif',
        'quote_form_budget_options' => "A definir\nMoins de 2 000 euros\n2 000 a 5 000 euros\n5 000 a 10 000 euros\nPlus de 10 000 euros",
        'quote_form_dimensions_label' => 'Dimensions approximatives',
        'quote_form_dimensions_placeholder' => 'Exemple : 320 x 65 cm + ilot 180 x 90 cm',
        'quote_form_message_label' => 'Message',
        'quote_form_message_placeholder' => 'Decrivez votre cuisine, vos contraintes et le niveau de finition attendu.',
        'quote_form_file_label' => 'Plan ou photo',
        'quote_form_submit_text' => 'Recevoir mon etude de projet',
        'form_recipient_email' => getenv('PCP_FORM_RECIPIENT') ?: 'hello@mpc.contact',
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
                    'brand_name' => sanitize_text_field($input['brand_name'] ?? ''),
                    'brand_suffix' => sanitize_text_field($input['brand_suffix'] ?? ''),
                    'brand_home_label' => sanitize_text_field($input['brand_home_label'] ?? ''),
                    'footer_description' => sanitize_textarea_field($input['footer_description'] ?? ''),
                    'footer_copyright_name' => sanitize_text_field($input['footer_copyright_name'] ?? ''),
                    'primary_cta_text' => sanitize_text_field($input['primary_cta_text'] ?? ''),
                    'primary_cta_url' => esc_url_raw($input['primary_cta_url'] ?? ''),
                    'footer_contact_link_text' => sanitize_text_field($input['footer_contact_link_text'] ?? ''),
                    'footer_contact_link_url' => esc_url_raw($input['footer_contact_link_url'] ?? ''),
                    'footer_navigation_heading' => sanitize_text_field($input['footer_navigation_heading'] ?? ''),
                    'footer_contact_heading' => sanitize_text_field($input['footer_contact_heading'] ?? ''),
                    'footer_studio_heading' => sanitize_text_field($input['footer_studio_heading'] ?? ''),
                    'footer_email_label' => sanitize_text_field($input['footer_email_label'] ?? ''),
                    'footer_phone_label' => sanitize_text_field($input['footer_phone_label'] ?? ''),
                    'footer_city_label' => sanitize_text_field($input['footer_city_label'] ?? ''),
                    'footer_service_area_label' => sanitize_text_field($input['footer_service_area_label'] ?? ''),
                    'footer_social_fallback_text' => sanitize_text_field($input['footer_social_fallback_text'] ?? ''),
                    'footer_legal_notice_text' => sanitize_text_field($input['footer_legal_notice_text'] ?? ''),
                    'footer_legal_notice_url' => esc_url_raw($input['footer_legal_notice_url'] ?? ''),
                    'footer_privacy_text' => sanitize_text_field($input['footer_privacy_text'] ?? ''),
                    'contact_form_name_label' => sanitize_text_field($input['contact_form_name_label'] ?? ''),
                    'contact_form_email_label' => sanitize_text_field($input['contact_form_email_label'] ?? ''),
                    'contact_form_phone_label' => sanitize_text_field($input['contact_form_phone_label'] ?? ''),
                    'contact_form_message_label' => sanitize_text_field($input['contact_form_message_label'] ?? ''),
                    'contact_form_submit_text' => sanitize_text_field($input['contact_form_submit_text'] ?? ''),
                    'quote_form_last_name_label' => sanitize_text_field($input['quote_form_last_name_label'] ?? ''),
                    'quote_form_first_name_label' => sanitize_text_field($input['quote_form_first_name_label'] ?? ''),
                    'quote_form_email_label' => sanitize_text_field($input['quote_form_email_label'] ?? ''),
                    'quote_form_phone_label' => sanitize_text_field($input['quote_form_phone_label'] ?? ''),
                    'quote_form_city_label' => sanitize_text_field($input['quote_form_city_label'] ?? ''),
                    'quote_form_project_type_label' => sanitize_text_field($input['quote_form_project_type_label'] ?? ''),
                    'quote_form_project_type_options' => sanitize_textarea_field($input['quote_form_project_type_options'] ?? ''),
                    'quote_form_material_label' => sanitize_text_field($input['quote_form_material_label'] ?? ''),
                    'quote_form_material_options' => sanitize_textarea_field($input['quote_form_material_options'] ?? ''),
                    'quote_form_budget_label' => sanitize_text_field($input['quote_form_budget_label'] ?? ''),
                    'quote_form_budget_options' => sanitize_textarea_field($input['quote_form_budget_options'] ?? ''),
                    'quote_form_dimensions_label' => sanitize_text_field($input['quote_form_dimensions_label'] ?? ''),
                    'quote_form_dimensions_placeholder' => sanitize_text_field($input['quote_form_dimensions_placeholder'] ?? ''),
                    'quote_form_message_label' => sanitize_text_field($input['quote_form_message_label'] ?? ''),
                    'quote_form_message_placeholder' => sanitize_text_field($input['quote_form_message_placeholder'] ?? ''),
                    'quote_form_file_label' => sanitize_text_field($input['quote_form_file_label'] ?? ''),
                    'quote_form_submit_text' => sanitize_text_field($input['quote_form_submit_text'] ?? ''),
                    'form_recipient_email' => sanitize_email($input['form_recipient_email'] ?? ''),
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

    add_theme_page(
        __('Guide Plan Ceramique', 'plan-ceramique-premium'),
        __('Guide Plan Ceramique', 'plan-ceramique-premium'),
        'edit_posts',
        'pcp-theme-guide',
        'pcp_render_admin_guide_page'
    );
}
add_action('admin_menu', 'pcp_register_settings');

function pcp_sync_cf7_form_recipients($old_value, $value): void
{
    if (!is_array($value)) {
        return;
    }

    $recipient = sanitize_email($value['form_recipient_email'] ?? '');

    if (!$recipient) {
        return;
    }

    $forms = get_posts(
        [
            'post_type' => 'wpcf7_contact_form',
            'post_status' => 'any',
            'posts_per_page' => -1,
        ]
    );

    foreach ($forms as $form) {
        $mail = get_post_meta($form->ID, '_mail', true);

        if (!is_array($mail)) {
            continue;
        }

        $mail['recipient'] = $recipient;
        update_post_meta($form->ID, '_mail', $mail);
    }
}
add_action('update_option_pcp_theme_settings', 'pcp_sync_cf7_form_recipients', 10, 2);

function pcp_render_settings_text_field(array $settings, string $key, string $label, string $type = 'text', string $description = ''): void
{
    $fieldId = 'pcp-' . str_replace('_', '-', $key);
    ?>
    <tr>
      <th scope="row"><label for="<?php echo esc_attr($fieldId); ?>"><?php echo esc_html($label); ?></label></th>
      <td>
        <input id="<?php echo esc_attr($fieldId); ?>" name="pcp_theme_settings[<?php echo esc_attr($key); ?>]" type="<?php echo esc_attr($type); ?>" class="regular-text" value="<?php echo esc_attr($settings[$key] ?? ''); ?>">
        <?php if ($description) : ?>
          <p class="description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
      </td>
    </tr>
    <?php
}

function pcp_render_settings_textarea_field(array $settings, string $key, string $label, string $description = ''): void
{
    $fieldId = 'pcp-' . str_replace('_', '-', $key);
    ?>
    <tr>
      <th scope="row"><label for="<?php echo esc_attr($fieldId); ?>"><?php echo esc_html($label); ?></label></th>
      <td>
        <textarea id="<?php echo esc_attr($fieldId); ?>" name="pcp_theme_settings[<?php echo esc_attr($key); ?>]" class="large-text" rows="4"><?php echo esc_textarea($settings[$key] ?? ''); ?></textarea>
        <?php if ($description) : ?>
          <p class="description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
      </td>
    </tr>
    <?php
}

function pcp_setting_lines(string $key): array
{
    $value = pcp_get_setting($key);
    $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: []));

    if ($lines) {
        return $lines;
    }

    $defaults = pcp_settings_defaults();

    return array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($defaults[$key] ?? '')) ?: []));
}

function pcp_render_settings_section(string $title, string $description, callable $callback): void
{
    ?>
    <div class="pcp-settings-panel">
      <h2><?php echo esc_html($title); ?></h2>
      <?php if ($description) : ?>
        <p><?php echo esc_html($description); ?></p>
      <?php endif; ?>
      <table class="form-table" role="presentation">
        <?php $callback(); ?>
      </table>
    </div>
    <?php
}

function pcp_settings_status_label(bool $enabled): string
{
    return $enabled
        ? __('Configuré', 'plan-ceramique-premium')
        : __('À compléter', 'plan-ceramique-premium');
}

function pcp_settings_display_value(string $value): string
{
    return $value !== '' ? $value : __('Non renseigne', 'plan-ceramique-premium');
}

function pcp_render_settings_page(): void
{
    $settings = wp_parse_args(get_option('pcp_theme_settings', []), pcp_settings_defaults());
    $productionEmail = 'hello@mpc.contact';
    $testEmail = 'chardinpoutcheu@gmail.com';
    $currentRecipient = pcp_get_setting('form_recipient_email') ?: (getenv('PCP_FORM_RECIPIENT') ?: '');
    $smtpHost = getenv('SMTP_HOST') ?: '';
    $smtpConfigured = getenv('SMTP_ENABLED') === '1' && (bool) getenv('SMTP_HOST');
    $recipientConfigured = (bool) getenv('PCP_FORM_RECIPIENT');
    $searchConsoleConfigured = !empty($settings['google_site_verification']);
    ?>
    <div class="wrap">
      <h1><?php esc_html_e('Réglages Plan Céramique Studio', 'plan-ceramique-premium'); ?></h1>
      <p class="description"><?php esc_html_e('Ces réglages alimentent les informations globales du site sans modifier sa mise en page.', 'plan-ceramique-premium'); ?></p>

      <style>
        .pcp-settings-grid {
          display: grid;
          gap: 16px;
          margin-top: 18px;
          max-width: 1040px;
        }

        .pcp-settings-panel {
          background: #fff;
          border: 1px solid #dcdcde;
          box-sizing: border-box;
          padding: 18px 20px;
        }

        .pcp-settings-panel h2 {
          margin-top: 0;
        }

        .pcp-settings-status {
          display: grid;
          gap: 8px;
          margin: 0;
        }

        .pcp-settings-status div {
          align-items: center;
          display: flex;
          gap: 8px;
          justify-content: space-between;
          max-width: 520px;
        }

        .pcp-settings-badge {
          background: #f0f0f1;
          border-radius: 999px;
          display: inline-block;
          font-size: 12px;
          font-weight: 600;
          padding: 3px 9px;
        }
      </style>

      <form action="options.php" method="post">
        <?php settings_fields('pcp_theme_settings_group'); ?>

        <div class="pcp-settings-grid">
          <?php
          pcp_render_settings_section(
              __('Identité et contact', 'plan-ceramique-premium'),
              __('Informations utilisées dans le pied de page, les liens de contact et les appels à l’action.', 'plan-ceramique-premium'),
              static function () use ($settings): void {
                  pcp_render_settings_text_field($settings, 'visible_email', __('Email affiché sur le site', 'plan-ceramique-premium'), 'email', __('Adresse visible par les visiteurs.', 'plan-ceramique-premium'));
                  pcp_render_settings_text_field($settings, 'phone', __('Téléphone', 'plan-ceramique-premium'), 'text', __('Laisser vide pour masquer le téléphone.', 'plan-ceramique-premium'));
                  pcp_render_settings_text_field($settings, 'city', __('Ville / base', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'service_area', __('Zone de service', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'primary_cta_text', __('Texte du CTA principal', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'primary_cta_url', __('URL du CTA principal', 'plan-ceramique-premium'), 'text', __('Prepare le futur branchement du header et des CTA globaux.', 'plan-ceramique-premium'));
              }
          );

          pcp_render_settings_section(
              __('Marque et pied de page', 'plan-ceramique-premium'),
              __('Champs de preparation pour rendre le header et le footer modifiables sans changer leur rendu.', 'plan-ceramique-premium'),
              static function () use ($settings): void {
                  pcp_render_settings_text_field($settings, 'brand_name', __('Nom de marque', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'brand_suffix', __('Suffixe de marque', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'brand_home_label', __('Libelle accessible du logo', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_textarea_field($settings, 'footer_description', __('Description du footer', 'plan-ceramique-premium'));
                  pcp_render_settings_text_field($settings, 'footer_copyright_name', __('Nom copyright footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_contact_link_text', __('Texte lien contact footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_contact_link_url', __('URL lien contact footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_navigation_heading', __('Titre navigation footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_contact_heading', __('Titre contact footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_studio_heading', __('Titre studio footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_email_label', __('Libelle email footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_phone_label', __('Libelle telephone footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_city_label', __('Libelle ville footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_service_area_label', __('Libelle zone footer', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_social_fallback_text', __('Texte lien social de secours', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_legal_notice_text', __('Texte mentions legales', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_legal_notice_url', __('URL mentions legales', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'footer_privacy_text', __('Texte confidentialite', 'plan-ceramique-premium'), 'text');
              }
          );

          pcp_render_settings_section(
              __('Réseaux sociaux', 'plan-ceramique-premium'),
              __('Renseigner uniquement les profils réellement utilisés.', 'plan-ceramique-premium'),
              static function () use ($settings): void {
                  pcp_render_settings_text_field($settings, 'instagram_url', __('Instagram', 'plan-ceramique-premium'), 'url');
                  pcp_render_settings_text_field($settings, 'pinterest_url', __('Pinterest', 'plan-ceramique-premium'), 'url');
                  pcp_render_settings_text_field($settings, 'linkedin_url', __('LinkedIn', 'plan-ceramique-premium'), 'url');
              }
          );

          pcp_render_settings_section(
              __('SEO et vérification', 'plan-ceramique-premium'),
              __('Réglages techniques utiles au référencement et à la validation du domaine.', 'plan-ceramique-premium'),
              static function () use ($settings): void {
                  pcp_render_settings_text_field($settings, 'google_site_verification', __('Code Google Search Console', 'plan-ceramique-premium'), 'text', __('Laisser vide en local. Coller uniquement le code de vérification fourni par Google.', 'plan-ceramique-premium'));
              }
          );

          pcp_render_settings_section(
              __('Formulaires', 'plan-ceramique-premium'),
              __('Pilotage WordPress du destinataire des demandes Contact et Devis.', 'plan-ceramique-premium'),
              static function () use ($settings): void {
                  pcp_render_settings_text_field($settings, 'form_recipient_email', __('Email destinataire des formulaires', 'plan-ceramique-premium'), 'email', __('Utilise par les formulaires du theme. Contact Form 7 est synchronise avec cette valeur lors de l’enregistrement.', 'plan-ceramique-premium'));
              }
          );
          pcp_render_settings_section(
              __('Libelles formulaires', 'plan-ceramique-premium'),
              __('Textes visibles dans les formulaires Contact et Devis.', 'plan-ceramique-premium'),
              static function () use ($settings): void {
                  pcp_render_settings_text_field($settings, 'contact_form_name_label', __('Contact - libelle nom', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'contact_form_email_label', __('Contact - libelle email', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'contact_form_phone_label', __('Contact - libelle telephone', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'contact_form_message_label', __('Contact - libelle message', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'contact_form_submit_text', __('Contact - bouton', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_last_name_label', __('Devis - libelle nom', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_first_name_label', __('Devis - libelle prenom', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_email_label', __('Devis - libelle email', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_phone_label', __('Devis - libelle telephone', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_city_label', __('Devis - libelle ville', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_project_type_label', __('Devis - libelle type de projet', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_textarea_field($settings, 'quote_form_project_type_options', __('Devis - options type de projet', 'plan-ceramique-premium'), __('Une option par ligne.', 'plan-ceramique-premium'));
                  pcp_render_settings_text_field($settings, 'quote_form_material_label', __('Devis - libelle materiau', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_textarea_field($settings, 'quote_form_material_options', __('Devis - options materiau', 'plan-ceramique-premium'), __('Une option par ligne.', 'plan-ceramique-premium'));
                  pcp_render_settings_text_field($settings, 'quote_form_budget_label', __('Devis - libelle budget', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_textarea_field($settings, 'quote_form_budget_options', __('Devis - options budget', 'plan-ceramique-premium'), __('Une option par ligne.', 'plan-ceramique-premium'));
                  pcp_render_settings_text_field($settings, 'quote_form_dimensions_label', __('Devis - libelle dimensions', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_dimensions_placeholder', __('Devis - aide dimensions', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_message_label', __('Devis - libelle message', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_message_placeholder', __('Devis - aide message', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_file_label', __('Devis - libelle fichier', 'plan-ceramique-premium'), 'text');
                  pcp_render_settings_text_field($settings, 'quote_form_submit_text', __('Devis - bouton', 'plan-ceramique-premium'), 'text');
              }
          );
          ?>

          <div class="pcp-settings-panel">
            <h2><?php esc_html_e('État de configuration', 'plan-ceramique-premium'); ?></h2>
            <dl class="pcp-settings-status">
              <div>
                <dt><?php esc_html_e('SMTP', 'plan-ceramique-premium'); ?></dt>
                <dd><span class="pcp-settings-badge"><?php echo esc_html(pcp_settings_status_label($smtpConfigured)); ?></span></dd>
              </div>
              <div>
                <dt><?php esc_html_e('Destinataire formulaires', 'plan-ceramique-premium'); ?></dt>
                <dd><span class="pcp-settings-badge"><?php echo esc_html(pcp_settings_status_label($recipientConfigured)); ?></span></dd>
              </div>
              <div>
                <dt><?php esc_html_e('Email actuellement utilise', 'plan-ceramique-premium'); ?></dt>
                <dd><code><?php echo esc_html(pcp_settings_display_value($currentRecipient)); ?></code></dd>
              </div>
              <div>
                <dt><?php esc_html_e('Email production', 'plan-ceramique-premium'); ?></dt>
                <dd><code><?php echo esc_html($productionEmail); ?></code></dd>
              </div>
              <div>
                <dt><?php esc_html_e('Email de test', 'plan-ceramique-premium'); ?></dt>
                <dd><code><?php echo esc_html($testEmail); ?></code></dd>
              </div>
              <div>
                <dt><?php esc_html_e('Serveur SMTP', 'plan-ceramique-premium'); ?></dt>
                <dd><code><?php echo esc_html(pcp_settings_display_value($smtpHost)); ?></code></dd>
              </div>
              <div>
                <dt><?php esc_html_e('Google Search Console', 'plan-ceramique-premium'); ?></dt>
                <dd><span class="pcp-settings-badge"><?php echo esc_html(pcp_settings_status_label($searchConsoleConfigured)); ?></span></dd>
              </div>
            </dl>
            <p class="description"><?php esc_html_e('Pour les tests reels, utiliser l’adresse de test. Ne pas envoyer de tests vers l’adresse production sans validation.', 'plan-ceramique-premium'); ?></p>
          </div>
        </div>

        <?php submit_button(); ?>
      </form>
    </div>
    <?php
}

function pcp_admin_guide_items(): array
{
    return [
        [
            'title' => __('Accueil et sections principales', 'plan-ceramique-premium'),
            'location' => __('Apparence > Guide Plan Ceramique puis modification progressive des contenus associes', 'plan-ceramique-premium'),
            'note' => __('Le rendu actuel doit rester stable. Les sections codees dans le theme seront rendues administrables une par une.', 'plan-ceramique-premium'),
        ],
        [
            'title' => __('Realisations', 'plan-ceramique-premium'),
            'location' => __('Menu Realisations', 'plan-ceramique-premium'),
            'note' => __('Modifier le titre, le texte, l’image mise en avant et les champs Type de projet, Matiere, Ambiance et Filtre galerie.', 'plan-ceramique-premium'),
        ],
        [
            'title' => __('Matieres', 'plan-ceramique-premium'),
            'location' => __('Menu Matieres', 'plan-ceramique-premium'),
            'note' => __('Administrer les finitions, familles de filtre, couleurs dominantes, ambiances conseillees et usages.', 'plan-ceramique-premium'),
        ],
        [
            'title' => __('Avis clients', 'plan-ceramique-premium'),
            'location' => __('Menu Avis clients', 'plan-ceramique-premium'),
            'note' => __('Modifier le temoignage, le nom client, le type de projet et la note sur 5.', 'plan-ceramique-premium'),
        ],
        [
            'title' => __('Coordonnees et reseaux', 'plan-ceramique-premium'),
            'location' => __('Apparence > Reglages Plan Ceramique', 'plan-ceramique-premium'),
            'note' => __('Modifier email visible, telephone, ville, zone de service, CTA principal et liens sociaux.', 'plan-ceramique-premium'),
        ],
        [
            'title' => __('Blog et conseils', 'plan-ceramique-premium'),
            'location' => __('Articles', 'plan-ceramique-premium'),
            'note' => __('Les articles restent geres par WordPress avec titre, contenu, extrait, image mise en avant et SEO.', 'plan-ceramique-premium'),
        ],
        [
            'title' => __('Formulaires Contact et Devis', 'plan-ceramique-premium'),
            'location' => __('Configuration technique et variables SMTP', 'plan-ceramique-premium'),
            'note' => __('Ne pas modifier le comportement sans test complet. Utiliser chardinpoutcheu@gmail.com pour les tests controles et reserver hello@mpc.contact a la production.', 'plan-ceramique-premium'),
        ],
        [
            'title' => __('SEO et verification Google', 'plan-ceramique-premium'),
            'location' => __('Apparence > Reglages Plan Ceramique et Yoast SEO', 'plan-ceramique-premium'),
            'note' => __('Renseigner Search Console, titres, descriptions, Open Graph et sitemap avant livraison.', 'plan-ceramique-premium'),
        ],
    ];
}

function pcp_render_admin_guide_page(): void
{
    ?>
    <div class="wrap">
      <h1><?php esc_html_e('Guide Plan Ceramique', 'plan-ceramique-premium'); ?></h1>
      <p class="description"><?php esc_html_e('Cette page sert de repere pour modifier le site depuis WordPress sans toucher au rendu public.', 'plan-ceramique-premium'); ?></p>

      <div style="max-width: 1040px; margin-top: 18px;">
        <div style="background: #fff; border: 1px solid #dcdcde; padding: 18px 20px; margin-bottom: 16px;">
          <h2><?php esc_html_e('Regles de modification', 'plan-ceramique-premium'); ?></h2>
          <ul style="list-style: disc; padding-left: 20px;">
            <li><?php esc_html_e('Ne pas modifier le CSS ou les templates front sans validation.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Modifier les contenus depuis WordPress quand une zone est deja administrable.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Tester les formulaires apres toute modification liee aux emails.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Garder les images compressees et coherentes avec le rendu actuel.', 'plan-ceramique-premium'); ?></li>
          </ul>
        </div>

        <div style="background: #fff; border: 1px solid #dcdcde; padding: 18px 20px; margin-bottom: 16px;">
          <h2><?php esc_html_e('Procedure de test email', 'plan-ceramique-premium'); ?></h2>
          <ol style="padding-left: 20px;">
            <li><?php esc_html_e('Verifier le destinataire actuel dans Apparence > Reglages Plan Ceramique.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('En local, verifier les emails dans Mailpit avant tout test reel.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Tester le formulaire Contact puis le formulaire Devis.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Verifier sujet, contenu, Reply-To et pieces jointes eventuelles.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Pour un test reel hors Mailpit, utiliser uniquement chardinpoutcheu@gmail.com.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Ne pas tester sur hello@mpc.contact sans validation.', 'plan-ceramique-premium'); ?></li>
          </ol>
        </div>

        <div style="background: #fff; border: 1px solid #dcdcde; padding: 18px 20px; margin-bottom: 16px;">
          <h2><?php esc_html_e('Checklist avant livraison', 'plan-ceramique-premium'); ?></h2>
          <ul style="list-style: disc; padding-left: 20px;">
            <li><?php esc_html_e('Verifier que le destinataire des formulaires est celui de production.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Verifier que Contact Form 7 affiche le meme destinataire.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Verifier SMTP, cron WordPress et Mailpit ou le service mail de production.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Lancer la verification PHP avec scripts/check-php.ps1.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Verifier Yoast SEO, sitemap, Open Graph et Search Console.', 'plan-ceramique-premium'); ?></li>
            <li><?php esc_html_e('Prevoir sauvegarde, securite, cache et identifiants forts avant mise en ligne.', 'plan-ceramique-premium'); ?></li>
          </ul>
        </div>

        <table class="widefat striped">
          <thead>
            <tr>
              <th><?php esc_html_e('Zone', 'plan-ceramique-premium'); ?></th>
              <th><?php esc_html_e('Ou modifier', 'plan-ceramique-premium'); ?></th>
              <th><?php esc_html_e('Consigne', 'plan-ceramique-premium'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach (pcp_admin_guide_items() as $item) : ?>
              <tr>
                <td><strong><?php echo esc_html($item['title']); ?></strong></td>
                <td><?php echo esc_html($item['location']); ?></td>
                <td><?php echo esc_html($item['note']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
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
