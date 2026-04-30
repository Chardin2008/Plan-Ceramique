<?php
get_header();

$asset_uri = static fn(string $file): string => get_template_directory_uri() . '/assets/images/' . $file;

$steps = [
    ['icon' => '1', 'title' => 'Dimensions', 'text' => 'Longueurs, profondeurs, îlot, retours ou plan approximatif.'],
    ['icon' => '2', 'title' => 'Finition', 'text' => 'Effet marbre, pierre, béton minéral ou choix encore à définir.'],
    ['icon' => '3', 'title' => 'Contraintes', 'text' => 'Ville, accès, étage, meubles déjà posés ou cuisine en rénovation.'],
];
?>
<main id="main-content" class="site-main pcp-quote-page">
  <section class="pcp-quote-hero">
    <div class="pcp-quote-hero__copy">
      <p class="pcp-quote-eyebrow">Demande de devis</p>
      <h1>Préparer une étude claire pour votre plan de travail.</h1>
      <p>Le formulaire devis rassemble les informations utiles pour comprendre votre cuisine, les dimensions, les découpes, la finition souhaitée et les conditions de pose.</p>
    </div>
    <figure class="pcp-quote-hero__media">
      <img src="<?php echo esc_url($asset_uri('hero-quote.jpg')); ?>" alt="Ambiance showroom pour demande de devis en céramique">
    </figure>
  </section>

  <section class="pcp-quote-prep">
    <div>
      <p class="pcp-quote-eyebrow">Avant d'envoyer</p>
      <h2>Quelques repères suffisent pour commencer.</h2>
    </div>
    <div class="pcp-quote-steps">
      <?php foreach ($steps as $step) : ?>
        <article class="pcp-quote-step">
          <span><?php echo esc_html($step['icon']); ?></span>
          <h3><?php echo esc_html($step['title']); ?></h3>
          <p><?php echo esc_html($step['text']); ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="pcp-quote-layout">
    <aside class="pcp-quote-note">
      <p class="pcp-quote-eyebrow">Ce qui aide</p>
      <h2>Plus le projet est précis, plus la réponse peut être juste.</h2>
      <p>Vous pouvez joindre une photo, un plan ou donner des dimensions approximatives. Même si tout n’est pas encore finalisé, ces éléments permettent de comprendre la configuration.</p>
      <ul>
        <li>Type de cuisine ou îlot central.</li>
        <li>Évier, plaque, crédence ou découpes prévues.</li>
        <li>Finition souhaitée ou ambiance recherchée.</li>
        <li>Ville et contraintes d’accès.</li>
      </ul>
    </aside>

    <section class="pcp-quote-form-card" aria-label="Formulaire de demande de devis">
      <p class="pcp-quote-eyebrow">Formulaire</p>
      <h2>Demander mon étude</h2>
      <?php echo do_shortcode('[pcp_contact_form type="quote"]'); ?>
    </section>
  </section>
</main>
<?php get_footer(); ?>
