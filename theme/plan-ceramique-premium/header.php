<?php
$brandName = pcp_get_setting('brand_name') ?: 'PLAN CÉRAMIQUE';
$brandSuffix = pcp_get_setting('brand_suffix') ?: 'STUDIO';
$brandHomeLabel = pcp_get_setting('brand_home_label') ?: 'Plan Céramique Studio - Accueil';
$primaryCtaText = pcp_get_setting('primary_cta_text') ?: 'Demander un devis';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content">
  <?php esc_html_e('Aller au contenu', 'plan-ceramique-premium'); ?>
</a>

<header class="site-header" data-site-header>
  <div class="site-header__inner">
    <a class="site-logo" href="#accueil" aria-label="<?php echo esc_attr($brandHomeLabel); ?>">
      <span class="logo-mark" aria-hidden="true">D</span>
      <span class="logo-text">
        <strong><?php echo esc_html($brandName); ?></strong>
        <small><?php echo esc_html($brandSuffix); ?></small>
      </span>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-navigation" aria-label="<?php esc_attr_e('Ouvrir le menu', 'plan-ceramique-premium'); ?>">
      <span class="nav-toggle__line"></span>
      <span class="nav-toggle__line"></span>
      <span class="nav-toggle__line"></span>
    </button>

    <div class="site-header__nav-shell" id="site-navigation">
      <nav class="site-nav" aria-label="<?php esc_attr_e('Navigation principale', 'plan-ceramique-premium'); ?>">
        <?php pcp_render_nav_menu('primary', 'site-nav__menu'); ?>
      </nav>

      <a class="site-header__cta" href="#devis">
        <?php echo esc_html($primaryCtaText); ?>
      </a>
    </div>
  </div>
</header>
