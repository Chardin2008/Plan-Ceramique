<?php

function pcp_enqueue_assets(): void
{
    wp_enqueue_style(
        'pcp-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'pcp-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['pcp-fonts'],
        pcp_theme_version()
    );

    wp_enqueue_script(
        'pcp-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        [],
        pcp_theme_version(),
        true
    );

    wp_enqueue_script(
        'pcp-landing',
        get_template_directory_uri() . '/assets/js/landing.js',
        [],
        pcp_theme_version(),
        true
    );

    wp_enqueue_script(
        'pcp-forms',
        get_template_directory_uri() . '/assets/js/forms.js',
        [],
        pcp_theme_version(),
        true
    );

    wp_localize_script(
        'pcp-landing',
        'pcpLanding',
        [
            'assetImgBase' => trailingslashit(get_template_directory_uri()) . 'assets/img/',
        ]
    );

    wp_localize_script(
        'pcp-forms',
        'pcpForms',
        [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'submitUrl' => get_template_directory_uri() . '/inc/fast-form-endpoint.php',
            'nonce' => wp_create_nonce('pcp_submit_form'),
        ]
    );
}
add_action('wp_enqueue_scripts', 'pcp_enqueue_assets');

function pcp_site_meta(): void
{
    $themeUri = get_template_directory_uri();
    $ogImage = $themeUri . '/assets/img/og-plan-ceramique.jpg';
    ?>
    <meta name="theme-color" content="#FBF8F2">
    <?php if (!defined('WPSEO_VERSION')) : ?>
        <meta property="og:title" content="<?php echo esc_attr__('Plan Céramique Studio - Plans de travail premium nouvelle génération', 'plan-ceramique-premium'); ?>">
        <meta property="og:description" content="<?php echo esc_attr__('Plans de travail en céramique pour cuisines, îlots, salles de bain et projets architecturaux.', 'plan-ceramique-premium'); ?>">
        <meta property="og:image" content="<?php echo esc_url($ogImage); ?>">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
    <?php endif; ?>
    <link rel="icon" href="<?php echo esc_url($themeUri . '/favicon.svg'); ?>" type="image/svg+xml">
    <link rel="icon" href="<?php echo esc_url($themeUri . '/favicon-32x32.png'); ?>" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="<?php echo esc_url($themeUri . '/apple-touch-icon.png'); ?>">
    <link rel="manifest" href="<?php echo esc_url($themeUri . '/site.webmanifest'); ?>">
    <?php
}
add_action('wp_head', 'pcp_site_meta', 2);
