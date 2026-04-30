<?php
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
    <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Plan Céramique Studio - Accueil', 'plan-ceramique-premium'); ?>">
      <span class="logo-mark" aria-hidden="true">D</span>
      <span class="logo-text">
        <strong><?php esc_html_e('PLAN CÉRAMIQUE', 'plan-ceramique-premium'); ?></strong>
        <small><?php esc_html_e('STUDIO', 'plan-ceramique-premium'); ?></small>
      </span>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-navigation" aria-label="<?php esc_attr_e('Ouvrir le menu', 'plan-ceramique-premium'); ?>">
      <span class="nav-toggle__line"></span>
      <span class="nav-toggle__line"></span>
      <span class="nav-toggle__line"></span>
    </button>

    <div class="site-header__nav-shell" id="site-navigation">
      <nav class="site-nav" aria-label="<?php esc_attr_e('Navigation principale', 'plan-ceramique-premium'); ?>">
        <ul class="site-nav__menu">
          <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Accueil', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/#matieres')); ?>"><?php esc_html_e('Matières', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/#ambiances')); ?>"><?php esc_html_e('Ambiances', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/#applications')); ?>"><?php esc_html_e('Applications', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/#avis')); ?>"><?php esc_html_e('Avis', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/#devis')); ?>"><?php esc_html_e('Devis', 'plan-ceramique-premium'); ?></a></li>
        </ul>
      </nav>

      <a class="site-header__cta" href="<?php echo esc_url(home_url('/#devis')); ?>">
        <?php esc_html_e('Demander un devis', 'plan-ceramique-premium'); ?>
      </a>
    </div>
  </div>
</header>
