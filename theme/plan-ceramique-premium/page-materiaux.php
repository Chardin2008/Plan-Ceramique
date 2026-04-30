<?php
get_header();

$asset_uri = static fn(string $file): string => get_template_directory_uri() . '/assets/images/' . $file;
$page_url = static fn(string $path): string => home_url($path);

$cards = [
    ['icon' => 'R', 'eyebrow' => '01 Résistance', 'title' => 'Une surface faite pour durer', 'text' => 'La céramique accompagne les gestes répétés de la cuisine : préparation, service, nettoyage et usage quotidien.'],
    ['icon' => 'C', 'eyebrow' => '02 Chaleur', 'title' => 'Une matière rassurante', 'text' => 'Le matériau garde une excellente stabilité dans les zones actives, autour de la cuisson et des préparations chaudes.'],
    ['icon' => 'E', 'eyebrow' => '03 Entretien', 'title' => 'Un nettoyage simple', 'text' => 'Une routine douce suffit pour conserver une surface nette, hygiénique et agréable à vivre tous les jours.'],
    ['icon' => 'F', 'eyebrow' => '04 Finitions', 'title' => 'Des rendus très variés', 'text' => 'Effet marbre, pierre, béton ou minéral uni : la finition se choisit selon la lumière et le style de cuisine.'],
];
?>
<main id="main-content" class="site-main pcp-detail pcp-materials">
  <section class="pcp-detail-hero">
    <div class="pcp-detail-hero__copy">
      <p class="pcp-detail-eyebrow">Matériaux</p>
      <h1>Comprendre la céramique avant de choisir votre surface.</h1>
      <p class="pcp-detail-hero__lead">La matière influence le style, l’entretien, la résistance et le confort d’usage. Cette page vous aide à comparer les critères essentiels avant de passer aux collections ou au devis.</p>
      <div class="pcp-detail-actions">
        <a class="button" href="<?php echo esc_url($page_url('/collections/')); ?>">Voir les collections</a>
        <a class="button button--ghost" href="<?php echo esc_url($page_url('/demander-un-devis/')); ?>">Demander un devis</a>
      </div>
    </div>
    <figure class="pcp-detail-hero__media">
      <img src="<?php echo esc_url($asset_uri('hero-materials.jpg')); ?>" alt="Surface céramique minérale pour plan de travail">
    </figure>
  </section>

  <section class="pcp-detail-intro">
    <div>
      <p class="pcp-detail-eyebrow">Repères</p>
      <h2>Le bon matériau se choisit selon l’usage réel de la cuisine.</h2>
    </div>
    <p>Une cuisine familiale, un îlot central ou une rénovation premium n’ont pas toujours les mêmes priorités. La céramique permet de relier esthétique, résistance et entretien dans une surface cohérente.</p>
  </section>

  <section class="pcp-detail-grid" aria-label="Critères des matériaux">
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
      <img src="<?php echo esc_url($asset_uri('hero-collections.jpg')); ?>" alt="Finitions et couleurs céramiques pour cuisine">
    </figure>
    <div class="pcp-detail-feature__content">
      <p class="pcp-detail-eyebrow">Choix</p>
      <h2>La finition doit dialoguer avec la lumière, les façades et le sol.</h2>
      <p>Une finition claire agrandit visuellement la pièce, un effet pierre apporte de la profondeur, un béton minéral donne une lecture plus architecturale. Le choix se fait toujours avec le contexte complet de la cuisine.</p>
      <a class="button button--ghost" href="<?php echo esc_url($page_url('/collections/')); ?>">Explorer les finitions</a>
    </div>
  </section>
</main>
<?php get_footer(); ?>
