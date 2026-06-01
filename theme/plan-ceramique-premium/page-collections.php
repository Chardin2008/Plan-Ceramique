<?php
get_header();

$page_id = get_queried_object_id();
$page_content = get_post_field('post_content', $page_id);

if (function_exists('pcp_detail_has_blocks') && pcp_detail_has_blocks((string) $page_content)) :
    ?>
    <main id="main-content" class="site-main pcp-detail pcp-collections">
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
$hero_image = pcp_admin_content_value($page_id, 'pcp_hero_image', 'hero-collections.jpg');
$feature_image = pcp_admin_content_value($page_id, 'pcp_feature_image', 'hero-projects.jpg');

$cards = [
    ['icon' => 'M', 'eyebrow' => '01 Marbre', 'title' => 'Clair et élégant', 'text' => 'Idéal pour apporter de la lumière, de la finesse et une sensation plus ouverte dans la cuisine.'],
    ['icon' => 'P', 'eyebrow' => '02 Pierre', 'title' => 'Naturel et profond', 'text' => 'Un rendu minéral plus sobre, adapté aux cuisines chaleureuses, contemporaines ou très structurées.'],
    ['icon' => 'B', 'eyebrow' => '03 Béton', 'title' => 'Calme et architectural', 'text' => 'Une finition discrète pour les cuisines épurées, les îlots centraux et les volumes modernes.'],
    ['icon' => 'T', 'eyebrow' => '04 Tons profonds', 'title' => 'Contraste maîtrisé', 'text' => 'Des teintes plus présentes pour donner du caractère sans perdre la cohérence du projet.'],
];
$cards = pcp_admin_content_pipe_rows($page_id, 'pcp_cards_json', ['icon', 'eyebrow', 'title', 'text'], $cards);
?>
<main id="main-content" class="site-main pcp-detail pcp-collections">
  <section class="pcp-detail-hero">
    <div class="pcp-detail-hero__copy">
      <p class="pcp-detail-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_eyebrow', 'Collections')); ?></p>
      <h1><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_title', 'Choisir une finition qui donne le ton à toute la cuisine.')); ?></h1>
      <p class="pcp-detail-hero__lead"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_lead', 'Les collections aident à construire l’ambiance du projet : marbre clair, pierre douce, béton minéral ou teinte plus profonde. L’objectif est de trouver une surface belle, cohérente et facile à vivre.')); ?></p>
      <div class="pcp-detail-actions">
        <a class="button" href="<?php echo esc_url($page_url(pcp_admin_content_value($page_id, 'pcp_primary_cta_url', '/realisations/'))); ?>"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_primary_cta_text', 'Voir les réalisations')); ?></a>
        <a class="button button--ghost" href="<?php echo esc_url($page_url(pcp_admin_content_value($page_id, 'pcp_secondary_cta_url', '/demander-un-devis/'))); ?>"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_secondary_cta_text', 'Demander un devis')); ?></a>
      </div>
    </div>
    <figure class="pcp-detail-hero__media">
      <img src="<?php echo esc_url($asset_uri($hero_image)); ?>" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_hero_image_alt', 'Showroom de finitions céramiques pour cuisine')); ?>">
    </figure>
  </section>

  <section class="pcp-detail-intro">
    <div>
      <p class="pcp-detail-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_eyebrow', 'Ambiances')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_title', 'Une collection se choisit avec la cuisine entière, pas seule sur un écran.')); ?></h2>
    </div>
    <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_text', 'La lumière, les façades, le sol, la crédence et la taille du plan changent fortement la perception d’une finition. La bonne sélection doit rester élégante dans le projet réel.')); ?></p>
  </section>

  <section class="pcp-detail-grid" aria-label="Familles de collections">
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
      <img src="<?php echo esc_url($asset_uri($feature_image)); ?>" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_feature_image_alt', 'Cuisine terminée avec plan de travail en céramique')); ?>">
    </figure>
    <div class="pcp-detail-feature__content">
      <p class="pcp-detail-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_eyebrow', 'Projection')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_title', 'Regarder une finition en situation aide à décider plus sereinement.')); ?></h2>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_text', 'Les réalisations permettent de voir comment une teinte se comporte avec un îlot, une crédence, des chants visibles ou une cuisine ouverte sur le séjour.')); ?></p>
      <a class="button button--ghost" href="<?php echo esc_url($page_url(pcp_admin_content_value($page_id, 'pcp_feature_cta_url', '/realisations/'))); ?>"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_cta_text', 'Voir les projets')); ?></a>
    </div>
  </section>
</main>
<?php get_footer(); ?>
