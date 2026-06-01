<?php
get_header();

$page_id = get_queried_object_id();
$page_content = get_post_field('post_content', $page_id);

if (function_exists('pcp_detail_has_blocks') && pcp_detail_has_blocks((string) $page_content)) :
    ?>
    <main id="main-content" class="site-main pcp-detail pcp-projects">
      <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </main>
    <?php
    get_footer();
    return;
endif;

$asset_uri = static fn(string $file): string => get_template_directory_uri() . '/assets/images/' . $file;
$page_url = static fn(string $path): string => home_url($path);
$hero_image = pcp_admin_content_value($page_id, 'pcp_hero_image', 'hero-projects.jpg');
$feature_image = pcp_admin_content_value($page_id, 'pcp_feature_image', 'hero-quote.jpg');

$cards = [
    ['icon' => 'F', 'eyebrow' => '01 Famille', 'title' => 'Cuisine quotidienne', 'text' => 'Une surface pensée pour préparer, poser, nettoyer et garder une cuisine agréable dans la durée.'],
    ['icon' => 'I', 'eyebrow' => '02 Îlot', 'title' => 'Point central', 'text' => 'Un îlot en céramique structure la pièce et demande une attention particulière aux proportions.'],
    ['icon' => 'R', 'eyebrow' => '03 Rénovation', 'title' => 'Transformation sobre', 'text' => 'La matière peut moderniser une cuisine existante sans perdre la cohérence avec les éléments déjà présents.'],
    ['icon' => 'C', 'eyebrow' => '04 Crédence', 'title' => 'Finition complète', 'text' => 'Une crédence assortie renforce la lecture du projet et protège les zones les plus exposées.'],
];
$cards = pcp_admin_content_pipe_rows($page_id, 'pcp_cards_json', ['icon', 'eyebrow', 'title', 'text'], $cards);
?>
<main id="main-content" class="site-main pcp-detail pcp-projects">
  <section class="pcp-detail-hero">
    <div class="pcp-detail-hero__copy">
      <p class="pcp-detail-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_eyebrow', 'Réalisations')); ?></p>
      <h1><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_title', 'Des projets concrets pour mieux imaginer votre cuisine.')); ?></h1>
      <p class="pcp-detail-hero__lead"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_lead', 'Les réalisations montrent comment la céramique se comporte dans des cuisines réelles : proportions, finitions, îlots, crédences, chants et détails de pose.')); ?></p>
      <div class="pcp-detail-actions">
        <a class="button" href="<?php echo esc_url($page_url(pcp_admin_content_value($page_id, 'pcp_primary_cta_url', '/demander-un-devis/'))); ?>"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_primary_cta_text', 'Lancer mon projet')); ?></a>
        <a class="button button--ghost" href="<?php echo esc_url($page_url(pcp_admin_content_value($page_id, 'pcp_secondary_cta_url', '/collections/'))); ?>"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_secondary_cta_text', 'Voir les collections')); ?></a>
      </div>
    </div>
    <figure class="pcp-detail-hero__media">
      <img src="<?php echo esc_url($asset_uri($hero_image)); ?>" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_hero_image_alt', 'Cuisine terminée avec plan de travail en céramique')); ?>">
    </figure>
  </section>

  <section class="pcp-detail-intro">
    <div>
      <p class="pcp-detail-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_eyebrow', 'Preuves')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_title', 'Une réalisation aide à vérifier le rendu, pas seulement l’idée.')); ?></h2>
    </div>
    <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_text', 'Avant de choisir une matière ou une finition, il est utile d’observer les volumes, les zones visibles, les joints, les chants et la relation entre le plan de travail et le reste de la cuisine.')); ?></p>
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
      <img src="<?php echo esc_url($asset_uri($feature_image)); ?>" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_feature_image_alt', 'Préparation d’une demande de devis pour plan de travail en céramique')); ?>">
    </figure>
    <div class="pcp-detail-feature__content">
      <p class="pcp-detail-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_eyebrow', 'Votre projet')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_title', 'Une réalisation commence par quelques informations simples.')); ?></h2>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_text', 'Dimensions approximatives, photos, contraintes d’accès, choix de finition ou inspiration : ces éléments permettent de cadrer le projet et d’obtenir une réponse plus juste.')); ?></p>
      <a class="button button--ghost" href="<?php echo esc_url($page_url(pcp_admin_content_value($page_id, 'pcp_feature_cta_url', '/demander-un-devis/'))); ?>"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_cta_text', 'Demander un devis')); ?></a>
    </div>
  </section>
</main>
<?php get_footer(); ?>
