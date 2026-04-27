<?php
$email = pcp_get_setting('visible_email');
$serviceArea = pcp_get_setting('service_area');
?>
<footer class="site-footer">
  <div class="site-footer__inner">
    <div class="site-footer__intro">
      <p class="site-footer__eyebrow"><?php esc_html_e('Plan Ceramique Premium', 'plan-ceramique-premium'); ?></p>
      <h2 class="site-footer__title"><?php esc_html_e('Des surfaces minerales concues pour des cuisines plus ambitieuses.', 'plan-ceramique-premium'); ?></h2>
      <p class="site-footer__text"><?php esc_html_e('Esthetique, resistance, hygiene, fabrication sur mesure, livraison et pose : le site est pense pour convertir une intention en demande de devis claire.', 'plan-ceramique-premium'); ?></p>
      <a class="button" href="<?php echo esc_url(home_url('/demander-un-devis/')); ?>">
        <?php esc_html_e('Lancer mon projet', 'plan-ceramique-premium'); ?>
      </a>
    </div>
    <div class="site-footer__content">
      <div class="site-footer__nav">
        <p class="site-footer__heading"><?php esc_html_e('Navigation', 'plan-ceramique-premium'); ?></p>
        <ul class="site-footer__links">
          <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Accueil', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/nos-services/')); ?>"><?php esc_html_e('Nos services', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/materiaux/')); ?>"><?php esc_html_e('Materiaux', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/collections/')); ?>"><?php esc_html_e('Collections', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/realisations/')); ?>"><?php esc_html_e('Realisations', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Blog', 'plan-ceramique-premium'); ?></a></li>
          <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact', 'plan-ceramique-premium'); ?></a></li>
        </ul>
      </div>
      <div class="site-footer__meta">
        <p class="site-footer__heading"><?php esc_html_e('Contact', 'plan-ceramique-premium'); ?></p>
        <p><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
        <p><?php echo esc_html($serviceArea); ?></p>
        <p><a href="<?php echo esc_url(home_url('/demander-un-devis/')); ?>"><?php esc_html_e('Demander un devis', 'plan-ceramique-premium'); ?></a></p>
      </div>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>


