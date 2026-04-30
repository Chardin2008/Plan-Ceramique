<?php
get_header();

$asset_uri = static fn(string $file): string => get_template_directory_uri() . '/assets/images/' . $file;
$page_url = static fn(string $path): string => home_url($path);

$cards = [
    ['icon' => 'F', 'eyebrow' => '01 Famille', 'title' => 'Cuisine quotidienne', 'text' => 'Une surface pensée pour préparer, poser, nettoyer et garder une cuisine agréable dans la durée.'],
    ['icon' => 'I', 'eyebrow' => '02 Îlot', 'title' => 'Point central', 'text' => 'Un îlot en céramique structure la pièce et demande une attention particulière aux proportions.'],
    ['icon' => 'R', 'eyebrow' => '03 Rénovation', 'title' => 'Transformation sobre', 'text' => 'La matière peut moderniser une cuisine existante sans perdre la cohérence avec les éléments déjà présents.'],
    ['icon' => 'C', 'eyebrow' => '04 Crédence', 'title' => 'Finition complète', 'text' => 'Une crédence assortie renforce la lecture du projet et protège les zones les plus exposées.'],
];
?>
<main id="main-content" class="site-main pcp-detail pcp-projects">
  <section class="pcp-detail-hero">
    <div class="pcp-detail-hero__copy">
      <p class="pcp-detail-eyebrow">Réalisations</p>
      <h1>Des projets concrets pour mieux imaginer votre cuisine.</h1>
      <p class="pcp-detail-hero__lead">Les réalisations montrent comment la céramique se comporte dans des cuisines réelles : proportions, finitions, îlots, crédences, chants et détails de pose.</p>
      <div class="pcp-detail-actions">
        <a class="button" href="<?php echo esc_url($page_url('/demander-un-devis/')); ?>">Lancer mon projet</a>
        <a class="button button--ghost" href="<?php echo esc_url($page_url('/collections/')); ?>">Voir les collections</a>
      </div>
    </div>
    <figure class="pcp-detail-hero__media">
      <img src="<?php echo esc_url($asset_uri('hero-projects.jpg')); ?>" alt="Cuisine terminée avec plan de travail en céramique">
    </figure>
  </section>

  <section class="pcp-detail-intro">
    <div>
      <p class="pcp-detail-eyebrow">Preuves</p>
      <h2>Une réalisation aide à vérifier le rendu, pas seulement l’idée.</h2>
    </div>
    <p>Avant de choisir une matière ou une finition, il est utile d’observer les volumes, les zones visibles, les joints, les chants et la relation entre le plan de travail et le reste de la cuisine.</p>
  </section>

  <section class="pcp-detail-grid" aria-label="Types de réalisations">
    <?php foreach ($cards as $card) : ?>
      <article class="pcp-detail-card">
        <div class="pcp-detail-card__topline">
          <span class="pcp-detail-icon" aria-hidden="true"><?php echo esc_html($card['icon']); ?></span>
          <p class="pcp-detail-eyebrow"><?php echo esc_html($card['eyebrow']); ?></p>
        </div>
        <h3><?php echo esc_html($card['title']); ?></h3>
        <p><?php echo esc_html($card['text']); ?></p>
      </article>
    <?php endforeach; ?>
  </section>

  <section class="pcp-detail-feature">
    <figure class="pcp-detail-feature__media">
      <img src="<?php echo esc_url($asset_uri('hero-quote.jpg')); ?>" alt="Préparation d’une demande de devis pour plan de travail en céramique">
    </figure>
    <div class="pcp-detail-feature__content">
      <p class="pcp-detail-eyebrow">Votre projet</p>
      <h2>Une réalisation commence par quelques informations simples.</h2>
      <p>Dimensions approximatives, photos, contraintes d’accès, choix de finition ou inspiration : ces éléments permettent de cadrer le projet et d’obtenir une réponse plus juste.</p>
      <a class="button button--ghost" href="<?php echo esc_url($page_url('/demander-un-devis/')); ?>">Demander un devis</a>
    </div>
  </section>
</main>
<?php get_footer(); ?>
