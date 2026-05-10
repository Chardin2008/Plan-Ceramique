<?php
get_header();

$asset_uri = static fn(string $file): string => get_template_directory_uri() . '/assets/images/' . $file;
$page_id = get_queried_object_id();
$hero_image = pcp_admin_content_value($page_id, 'pcp_hero_image', 'hero-contact.jpg');
$visible_email = pcp_get_setting('visible_email');
$service_area = pcp_get_setting('service_area');

$cards = [
    ['icon' => 'Q', 'title' => 'Question simple', 'text' => 'Un doute sur une finition, une contrainte de cuisine ou une étape du projet.'],
    ['icon' => 'M', 'title' => 'Matière et rendu', 'text' => 'Comparer un effet marbre, pierre, béton minéral ou une surface plus sobre.'],
    ['icon' => 'D', 'title' => 'Avant devis', 'text' => 'Préparer les bonnes informations avant de passer à une demande chiffrée.'],
];
$cards = pcp_admin_content_pipe_rows($page_id, 'pcp_cards_json', ['icon', 'title', 'text'], $cards);
?>
<main id="main-content" class="site-main pcp-contact-page">
  <section class="pcp-contact-hero">
    <div class="pcp-contact-hero__copy">
      <p class="pcp-contact-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_eyebrow', 'Contact')); ?></p>
      <h1><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_title', 'Parlez-nous de votre projet, simplement.')); ?></h1>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_lead', 'Une question sur la céramique, une finition, une étape de pose ou une première idée de cuisine ? Cette page sert aux échanges rapides avant de cadrer un devis complet.')); ?></p>
    </div>
    <figure class="pcp-contact-hero__media">
      <img src="<?php echo esc_url($asset_uri($hero_image)); ?>" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_hero_image_alt', 'Espace de contact pour projet de plan de travail en céramique')); ?>">
    </figure>
  </section>

  <section class="pcp-contact-layout">
    <aside class="pcp-contact-panel" aria-label="Informations utiles">
      <p class="pcp-contact-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_eyebrow', 'Repères')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_title', 'Quand utiliser le contact ?')); ?></h2>
      <div class="pcp-contact-mini-grid">
        <?php foreach ($cards as $card) : ?>
          <article class="pcp-contact-mini">
            <span><?php echo esc_html($card['icon']); ?></span>
            <h3><?php echo esc_html($card['title']); ?></h3>
            <p><?php echo esc_html($card['text']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="pcp-contact-info">
        <p><strong><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_contact_email_label', 'Email')); ?></strong><br><?php echo esc_html($visible_email); ?></p>
        <p><strong><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_contact_zone_label', 'Zone')); ?></strong><br><?php echo esc_html($service_area); ?></p>
      </div>
    </aside>

    <section class="pcp-contact-form-card" aria-label="Formulaire de contact">
      <p class="pcp-contact-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_eyebrow', 'Formulaire')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_title', 'Envoyer un message')); ?></h2>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_text', 'Décrivez votre besoin en quelques lignes. Si le projet est déjà précis, nous pourrons ensuite vous orienter vers la demande de devis.')); ?></p>
      <?php echo do_shortcode('[pcp_contact_form type="contact"]'); ?>
    </section>
  </section>
</main>
<?php get_footer(); ?>
