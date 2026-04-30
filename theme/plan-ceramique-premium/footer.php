<?php
$email = pcp_get_setting('visible_email') ?: 'contact@planceramique.fr';

$phone = pcp_get_setting('phone');
$city = pcp_get_setting('city');
$serviceArea = pcp_get_setting('service_area');
$instagram = pcp_get_setting('instagram_url');
$pinterest = pcp_get_setting('pinterest_url');
$linkedin = pcp_get_setting('linkedin_url');
$hasSocial = $instagram || $pinterest || $linkedin;
$privacyUrl = get_privacy_policy_url();
$year = gmdate('Y');
?>
<footer class="site-footer">
  <div class="site-footer__inner">
    <div class="site-footer__brand">
      <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Plan Céramique Studio - Accueil', 'plan-ceramique-premium'); ?>">
        <span class="logo-mark" aria-hidden="true">D</span>
        <span class="logo-text">
          <strong><?php esc_html_e('PLAN CÉRAMIQUE', 'plan-ceramique-premium'); ?></strong>
          <small><?php esc_html_e('STUDIO', 'plan-ceramique-premium'); ?></small>
        </span>
      </a>
      <p><?php esc_html_e('Plans de travail en céramique premium pour cuisines, intérieurs et projets architecturaux.', 'plan-ceramique-premium'); ?></p>
    </div>

    <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Navigation pied de page', 'plan-ceramique-premium'); ?>">
      <p class="site-footer__heading"><?php esc_html_e('Navigation', 'plan-ceramique-premium'); ?></p>
      <ul class="site-footer__links">
        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Accueil', 'plan-ceramique-premium'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#matieres')); ?>"><?php esc_html_e('Matières', 'plan-ceramique-premium'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#ambiances')); ?>"><?php esc_html_e('Ambiances', 'plan-ceramique-premium'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#realisations')); ?>"><?php esc_html_e('Réalisations', 'plan-ceramique-premium'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#galerie')); ?>"><?php esc_html_e('Galerie', 'plan-ceramique-premium'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'plan-ceramique-premium'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#devis')); ?>"><?php esc_html_e('Devis', 'plan-ceramique-premium'); ?></a></li>
      </ul>
    </nav>

    <div class="site-footer__meta">
      <p class="site-footer__heading"><?php esc_html_e('Contact', 'plan-ceramique-premium'); ?></p>
      <div class="site-footer__contact-list">
        <p>
          <span><?php esc_html_e('Email', 'plan-ceramique-premium'); ?></span>
          <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
        </p>

        <?php if ($phone) : ?>
          <p>
            <span><?php esc_html_e('Téléphone', 'plan-ceramique-premium'); ?></span>
            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
          </p>
        <?php endif; ?>

        <?php if ($city) : ?>
          <p>
            <span><?php esc_html_e('Base', 'plan-ceramique-premium'); ?></span>
            <?php echo esc_html($city); ?>
          </p>
        <?php endif; ?>

        <?php if ($serviceArea) : ?>
          <p>
            <span><?php esc_html_e('Zone', 'plan-ceramique-premium'); ?></span>
            <?php echo esc_html($serviceArea); ?>
          </p>
        <?php endif; ?>
      </div>
      <a class="site-footer__contact-link" href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Accéder à la page contact', 'plan-ceramique-premium'); ?></a>
    </div>

    <div class="site-footer__social">
      <p class="site-footer__heading"><?php esc_html_e('Studio', 'plan-ceramique-premium'); ?></p>
      <?php if ($hasSocial) : ?>
        <div class="site-footer__social-links">
          <?php if ($instagram) : ?><a href="<?php echo esc_url($instagram); ?>" aria-label="<?php esc_attr_e('Instagram Plan Céramique Studio', 'plan-ceramique-premium'); ?>">Instagram</a><?php endif; ?>
          <?php if ($pinterest) : ?><a href="<?php echo esc_url($pinterest); ?>" aria-label="<?php esc_attr_e('Pinterest Plan Céramique Studio', 'plan-ceramique-premium'); ?>">Pinterest</a><?php endif; ?>
          <?php if ($linkedin) : ?><a href="<?php echo esc_url($linkedin); ?>" aria-label="<?php esc_attr_e('LinkedIn Plan Céramique Studio', 'plan-ceramique-premium'); ?>">LinkedIn</a><?php endif; ?>
        </div>
      <?php else : ?>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Nous contacter', 'plan-ceramique-premium'); ?></a>
      <?php endif; ?>
    </div>
  </div>

  <div class="site-footer__bottom">
    <span>&copy; <?php echo esc_html($year); ?> <?php esc_html_e('Plan Céramique Studio', 'plan-ceramique-premium'); ?></span>
    <div class="site-footer__legal">
      <a href="<?php echo esc_url(home_url('/mentions-legales/')); ?>"><?php esc_html_e('Mentions légales', 'plan-ceramique-premium'); ?></a>
      <?php if ($privacyUrl) : ?>
        <a href="<?php echo esc_url($privacyUrl); ?>"><?php esc_html_e('Confidentialité', 'plan-ceramique-premium'); ?></a>
      <?php endif; ?>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
