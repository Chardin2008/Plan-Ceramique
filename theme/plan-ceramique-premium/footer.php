<?php
$email = pcp_get_setting('visible_email') ?: 'contact@planceramique.fr';
$phone = pcp_get_setting('phone');
$city = pcp_get_setting('city');
$serviceArea = pcp_get_setting('service_area');
$privacyUrl = get_privacy_policy_url();
$year = gmdate('Y');
$brandName = pcp_get_setting('brand_name') ?: 'PLAN CÉRAMIQUE';
$brandSuffix = pcp_get_setting('brand_suffix') ?: 'STUDIO';
$brandHomeLabel = pcp_get_setting('brand_home_label') ?: 'Plan Céramique Studio - Accueil';
$footerDescription = pcp_get_setting('footer_description');
$footerContactText = 'Demander un devis';
$legalNoticeText = pcp_get_setting('footer_legal_notice_text') ?: 'Mentions légales';
$copyrightName = pcp_get_setting('footer_copyright_name') ?: 'Plan Céramique Studio';
?>
<footer class="site-footer">
  <div class="site-footer__inner">
    <div class="site-footer__brand">
      <a class="site-logo" href="#accueil" aria-label="<?php echo esc_attr($brandHomeLabel); ?>">
        <span class="logo-mark" aria-hidden="true">D</span>
        <span class="logo-text">
          <strong><?php echo esc_html($brandName); ?></strong>
          <small><?php echo esc_html($brandSuffix); ?></small>
        </span>
      </a>
      <p><?php echo esc_html($footerDescription); ?></p>
    </div>

    <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Navigation pied de page', 'plan-ceramique-premium'); ?>">
      <p class="site-footer__heading"><?php echo esc_html(pcp_get_setting('footer_navigation_heading') ?: 'Navigation'); ?></p>
      <?php pcp_render_nav_menu('footer', 'site-footer__links'); ?>
    </nav>

    <div class="site-footer__meta">
      <p class="site-footer__heading"><?php echo esc_html(pcp_get_setting('footer_contact_heading') ?: 'Contact'); ?></p>
      <div class="site-footer__contact-list">
        <p>
          <span><?php echo esc_html(pcp_get_setting('footer_email_label') ?: 'Email'); ?></span>
          <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
        </p>

        <?php if ($phone) : ?>
          <p>
            <span><?php echo esc_html(pcp_get_setting('footer_phone_label') ?: 'Téléphone'); ?></span>
            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
          </p>
        <?php endif; ?>

        <?php if ($city) : ?>
          <p>
            <span><?php echo esc_html(pcp_get_setting('footer_city_label') ?: 'Base'); ?></span>
            <?php echo esc_html($city); ?>
          </p>
        <?php endif; ?>

        <?php if ($serviceArea) : ?>
          <p>
            <span><?php echo esc_html(pcp_get_setting('footer_service_area_label') ?: 'Zone'); ?></span>
            <?php echo esc_html($serviceArea); ?>
          </p>
        <?php endif; ?>
      </div>
      <a class="site-footer__contact-link" href="#devis"><?php echo esc_html($footerContactText); ?></a>
    </div>

    <div class="site-footer__social">
      <p class="site-footer__heading"><?php echo esc_html(pcp_get_setting('footer_studio_heading') ?: 'Studio'); ?></p>
      <a href="#devis"><?php echo esc_html(pcp_get_setting('footer_social_fallback_text') ?: 'Nous contacter'); ?></a>
    </div>
  </div>

  <div class="site-footer__bottom">
    <span>&copy; <?php echo esc_html($year); ?> <?php echo esc_html($copyrightName); ?></span>
    <div class="site-footer__legal">
      <a href="#accueil"><?php echo esc_html($legalNoticeText); ?></a>
      <?php if ($privacyUrl) : ?>
        <a href="#accueil"><?php echo esc_html(pcp_get_setting('footer_privacy_text') ?: 'Confidentialité'); ?></a>
      <?php endif; ?>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
