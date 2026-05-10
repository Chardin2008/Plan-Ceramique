<?php
get_header();

$asset_uri = static fn(string $file): string => get_template_directory_uri() . '/assets/images/' . $file;
$page_url = static fn(string $path): string => home_url($path);
$page_id = get_queried_object_id();
$hero_image = pcp_admin_content_value($page_id, 'pcp_hero_image', 'hero-services.jpg');
$feature_image = pcp_admin_content_value($page_id, 'pcp_feature_image', 'hero-materials.jpg');

$services = [
    [
        'icon' => 'C',
        'eyebrow' => '01 Conseil',
        'title' => 'Cadrer le projet',
        'text' => 'Nous clarifions l’usage de la cuisine, le style recherché, les contraintes techniques et le niveau de finition attendu.',
    ],
    [
        'icon' => 'M',
        'eyebrow' => '02 Mesure',
        'title' => 'Préparer les dimensions',
        'text' => 'Les longueurs, profondeurs, angles, découpes et accès sont vérifiés pour sécuriser la fabrication du plan.',
    ],
    [
        'icon' => 'F',
        'eyebrow' => '03 Fabrication',
        'title' => 'Produire sur mesure',
        'text' => 'Le plan de travail est préparé selon la matière choisie, les découpes prévues, les chants et les détails visibles.',
    ],
    [
        'icon' => 'P',
        'eyebrow' => '04 Pose',
        'title' => 'Livrer et installer',
        'text' => 'La livraison et la pose sont organisées pour obtenir un rendu propre, stable et cohérent avec votre cuisine.',
    ],
];
$services = pcp_admin_content_pipe_rows($page_id, 'pcp_cards_json', ['icon', 'eyebrow', 'title', 'text'], $services);

$reassurance = [
    'Projet lisible dès le premier échange',
    'Découpes évier, plaque et prises anticipées',
    'Coordination fabrication, livraison et pose',
    'Intervention pensée pour les cuisines sur mesure',
];
$reassurance = pcp_admin_content_lines($page_id, 'pcp_feature_list', $reassurance);
?>
<main id="main-content" class="site-main pcp-services">
  <section class="pcp-services-hero">
    <div class="pcp-services-hero__copy">
      <p class="pcp-services-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_eyebrow', 'Nos services')); ?></p>
      <h1><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_title', 'Un service clair pour votre plan de travail en céramique.')); ?></h1>
      <p class="pcp-services-hero__lead"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_lead', 'De la première idée jusqu’à la pose, chaque étape est cadrée pour éviter les imprévus : conseil, mesures, choix de matière, fabrication, livraison et installation.')); ?></p>
      <div class="pcp-services-actions">
        <a class="button" href="<?php echo esc_url($page_url(pcp_admin_content_value($page_id, 'pcp_primary_cta_url', '/demander-un-devis/'))); ?>"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_primary_cta_text', 'Demander un devis')); ?></a>
        <a class="button button--ghost" href="<?php echo esc_url($page_url(pcp_admin_content_value($page_id, 'pcp_secondary_cta_url', '/materiaux/'))); ?>"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_secondary_cta_text', 'Voir les matériaux')); ?></a>
      </div>
    </div>
    <figure class="pcp-services-hero__media">
      <img src="<?php echo esc_url($asset_uri($hero_image)); ?>" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_hero_image_alt', 'Conseil et préparation d’un projet de plan de travail en céramique')); ?>">
    </figure>
  </section>

  <section class="pcp-services-intro">
    <div>
      <p class="pcp-services-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_eyebrow', 'Méthode')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_title', 'Chaque service répond à une étape réelle du projet.')); ?></h2>
    </div>
    <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_text', 'La page Services sert à comprendre comment le projet avance : ce que nous vérifions, ce que nous préparons et ce qui permet d’obtenir un résultat propre le jour de la pose.')); ?></p>
  </section>

  <section class="pcp-services-grid" aria-label="Étapes de service">
    <?php foreach ($services as $service) : ?>
      <article class="pcp-services-card">
        <div class="pcp-services-card__topline">
          <span class="pcp-services-icon" aria-hidden="true"><?php echo esc_html($service['icon']); ?></span>
          <p class="pcp-services-eyebrow"><?php echo esc_html($service['eyebrow']); ?></p>
        </div>
        <h3><?php echo esc_html($service['title']); ?></h3>
        <p><?php echo esc_html($service['text']); ?></p>
      </article>
    <?php endforeach; ?>
  </section>

  <section class="pcp-services-feature">
    <figure class="pcp-services-feature__media">
      <img src="<?php echo esc_url($asset_uri($feature_image)); ?>" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_feature_image_alt', 'Surface céramique minérale pour cuisine sur mesure')); ?>">
    </figure>
    <div class="pcp-services-feature__content">
      <p class="pcp-services-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_eyebrow', 'Précision')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_title', 'Un service utile parce qu’il relie esthétique et contraintes techniques.')); ?></h2>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_text', 'Un plan de travail réussi ne dépend pas seulement de la couleur choisie. Les dimensions, les découpes, les chants, les accès de livraison et la pose influencent directement le rendu final.')); ?></p>
      <ul class="pcp-services-list">
        <?php foreach ($reassurance as $item) : ?>
          <li><?php echo esc_html($item); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <section class="pcp-services-cta">
    <div>
      <p class="pcp-services-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_final_cta_eyebrow', 'Suite logique')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_final_cta_title', 'Préparez quelques informations, nous cadrons le reste.')); ?></h2>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_final_cta_text', 'Dimensions approximatives, photos, ville, finition souhaitée ou simple idée de départ : ces éléments suffisent pour lancer une demande plus sérieuse.')); ?></p>
    </div>
    <a class="button" href="<?php echo esc_url($page_url(pcp_admin_content_value($page_id, 'pcp_final_cta_button_url', '/demander-un-devis/'))); ?>"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_final_cta_button_text', 'Accéder au formulaire devis')); ?></a>
  </section>
</main>
<?php get_footer(); ?>
