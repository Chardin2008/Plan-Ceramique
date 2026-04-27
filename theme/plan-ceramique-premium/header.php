<?php
$email = pcp_get_setting('visible_email');
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

<header class="site-header">
  <div class="site-header__inner">

    <a class="site-header__brand" href="<?php echo esc_url(home_url('/')); ?>">
      <?php bloginfo('name'); ?>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-navigation">
      <span class="nav-toggle__line"></span>
      <span class="nav-toggle__line"></span>
      <span class="nav-toggle__line"></span>
      <span class="screen-reader-text">
        <?php esc_html_e('Ouvrir le menu', 'plan-ceramique-premium'); ?>
      </span>
    </button>

    <div class="site-header__nav-shell" id="site-navigation">
      <nav class="site-nav" aria-label="<?php esc_attr_e('Navigation principale', 'plan-ceramique-premium'); ?>">
        <?php
        wp_nav_menu([
          'theme_location' => 'primary',
          'container' => false,
          'menu_class' => 'site-nav__menu',
          'fallback_cb' => 'wp_page_menu',
        ]);
        ?>
      </nav>

      <a class="site-header__cta" href="<?php echo esc_url(home_url('/demander-un-devis/')); ?>">
        <?php esc_html_e('Demander un devis', 'plan-ceramique-premium'); ?>
      </a>
    </div>

  </div>
</header>