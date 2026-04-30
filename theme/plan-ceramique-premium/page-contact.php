<?php
get_header();

$asset_uri = static fn(string $file): string => get_template_directory_uri() . '/assets/images/' . $file;
$visible_email = pcp_get_setting('visible_email');
$service_area = pcp_get_setting('service_area');

$cards = [
    ['icon' => 'Q', 'title' => 'Question simple', 'text' => 'Un doute sur une finition, une contrainte de cuisine ou une étape du projet.'],
    ['icon' => 'M', 'title' => 'Matière et rendu', 'text' => 'Comparer un effet marbre, pierre, béton minéral ou une surface plus sobre.'],
    ['icon' => 'D', 'title' => 'Avant devis', 'text' => 'Préparer les bonnes informations avant de passer à une demande chiffrée.'],
];
?>
<main id="main-content" class="site-main pcp-contact-page">
  <section class="pcp-contact-hero">
    <div class="pcp-contact-hero__copy">
      <p class="pcp-contact-eyebrow">Contact</p>
      <h1>Parlez-nous de votre projet, simplement.</h1>
      <p>Une question sur la céramique, une finition, une étape de pose ou une première idée de cuisine ? Cette page sert aux échanges rapides avant de cadrer un devis complet.</p>
    </div>
    <figure class="pcp-contact-hero__media">
      <img src="<?php echo esc_url($asset_uri('hero-contact.jpg')); ?>" alt="Espace de contact pour projet de plan de travail en céramique">
    </figure>
  </section>

  <section class="pcp-contact-layout">
    <aside class="pcp-contact-panel" aria-label="Informations utiles">
      <p class="pcp-contact-eyebrow">Repères</p>
      <h2>Quand utiliser le contact ?</h2>
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
        <p><strong>Email</strong><br><?php echo esc_html($visible_email); ?></p>
        <p><strong>Zone</strong><br><?php echo esc_html($service_area); ?></p>
      </div>
    </aside>

    <section class="pcp-contact-form-card" aria-label="Formulaire de contact">
      <p class="pcp-contact-eyebrow">Formulaire</p>
      <h2>Envoyer un message</h2>
      <p>Décrivez votre besoin en quelques lignes. Si le projet est déjà précis, nous pourrons ensuite vous orienter vers la demande de devis.</p>
      <?php echo do_shortcode('[pcp_contact_form type="contact"]'); ?>
    </section>
  </section>
</main>
<?php get_footer(); ?>
