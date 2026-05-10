<?php
get_header();

$asset_img = static fn(string $file): string => get_template_directory_uri() . '/assets/img/' . $file;
$page_id = get_queried_object_id();
$hero_image = pcp_admin_content_value($page_id, 'pcp_hero_image', 'hero-light-ceramique.jpg');

$surfaceCards = [
    ['num' => '01', 'title' => 'Chaleur', 'text' => 'Une surface céramique pensée pour les cuisines exigeantes et les usages quotidiens intensifs.'],
    ['num' => '02', 'title' => 'Rayures', 'text' => 'Une excellente tenue à l’usage, avec un rendu minéral qui reste élégant dans le temps.'],
    ['num' => '03', 'title' => 'Taches', 'text' => 'Une matière compacte, facile à nettoyer et adaptée aux espaces de vie contemporains.'],
    ['num' => '04', 'title' => 'UV', 'text' => 'Des finitions qui conservent leur présence visuelle, en intérieur comme sur certains projets extérieurs.'],
    ['num' => '05', 'title' => 'Usage intensif', 'text' => 'Un choix pertinent pour cuisines familiales, îlots centraux, salles de bain et projets professionnels.'],
];

$surfaceCards = pcp_admin_content_pipe_rows($page_id, 'pcp_surface_cards', ['num', 'title', 'text'], $surfaceCards);
$editorialCards = pcp_admin_content_pipe_rows(
    $page_id,
    'pcp_cards_json',
    ['title', 'text'],
    [
        ['title' => 'Design', 'text' => 'Une présence architecturale qui structure la cuisine.'],
        ['title' => 'Résistance', 'text' => 'Une matière minérale adaptée aux usages exigeants.'],
        ['title' => 'Ambiance', 'text' => 'Des teintes claires, chaleureuses et haut de gamme.'],
    ]
);
$heroBadges = pcp_admin_content_lines(
    $page_id,
    'pcp_hero_badges',
    [
        'Résistant chaleur',
        'Résistant rayures',
        'Intérieur / extérieur',
    ]
);
$scannerPoints = pcp_admin_content_pipe_rows(
    $page_id,
    'pcp_scanner_points',
    ['num', 'x', 'y', 'title', 'text'],
    [
        ['num' => '1', 'x' => '22%', 'y' => '32%', 'title' => 'Veinage', 'text' => 'Un dessin minéral subtil apporte du mouvement sans alourdir la pièce.'],
        ['num' => '2', 'x' => '48%', 'y' => '24%', 'title' => 'Texture', 'text' => 'Un toucher mat ou satiné donne une lecture plus douce et plus architecturale.'],
        ['num' => '3', 'x' => '74%', 'y' => '45%', 'title' => 'Résistance', 'text' => 'La céramique convient aux surfaces très sollicitées avec un entretien simple.'],
        ['num' => '4', 'x' => '38%', 'y' => '72%', 'title' => 'Entretien', 'text' => 'La surface se nettoie facilement au quotidien, sans protocole compliqué.'],
        ['num' => '5', 'x' => '68%', 'y' => '76%', 'title' => 'Ambiance', 'text' => 'Les tons clairs dialoguent avec le bois, le champagne, le sauge et les murs chauds.'],
        ['num' => '6', 'x' => '86%', 'y' => '64%', 'title' => 'Usage conseillé', 'text' => 'Cuisine, îlot, crédence, salle de bain ou table sur mesure.'],
    ]
);
$activeScannerPoint = $scannerPoints[0] ?? ['title' => 'Veinage', 'text' => 'Un dessin minéral subtil apporte du mouvement sans alourdir la pièce.'];

$ambiances = [
    [
        'title' => 'Warm Mineral',
        'image' => 'kitchen-warm-ceramique.jpg',
        'alt' => 'Cuisine lumineuse avec plan de travail céramique clair et bois noyer',
        'text' => 'Pierre claire, bois noyer et lumière chaude pour un intérieur doux, élégant et accueillant.',
        'colors' => ['#FBF8F2', '#D8C7AD', '#6B4E35'],
    ],
    [
        'title' => 'Pure Marble',
        'image' => 'kitchen-white-ceramique.jpg',
        'alt' => 'Cuisine blanche premium avec surface céramique veinée',
        'text' => 'Blanc veiné, lignes épurées et lumière naturelle pour une cuisine lumineuse et intemporelle.',
        'colors' => ['#FFFFFF', '#E7DFD2', '#C9A76A'],
    ],
    [
        'title' => 'Sage Architecture',
        'image' => 'kitchen-sage-ceramique.jpg',
        'alt' => 'Cuisine vert sauge avec plan de travail céramique clair',
        'text' => 'Vert sauge, pierre claire et détails champagne pour une ambiance contemporaine et apaisante.',
        'colors' => ['#A7B3A0', '#F4EFE6', '#B8785F'],
    ],
];

$ambiances = array_map(
    static function (array $ambiance): array {
        if (isset($ambiance['colors']) && is_string($ambiance['colors'])) {
            $ambiance['colors'] = array_filter(array_map('trim', explode(',', $ambiance['colors'])));
        }

        return $ambiance;
    },
    pcp_admin_content_pipe_rows($page_id, 'pcp_ambiance_cards', ['title', 'image', 'alt', 'text', 'colors'], $ambiances)
);

$applications = [
    ['title' => 'Plan de travail cuisine', 'image' => 'kitchen-white-ceramique.jpg', 'alt' => 'Plan de travail cuisine en céramique claire'],
    ['title' => 'Îlot central', 'image' => 'island-light-ceramique.jpg', 'alt' => 'Îlot central avec surface céramique premium'],
    ['title' => 'Crédence', 'image' => 'texture-white-vein.jpg', 'alt' => 'Crédence en céramique claire veinée'],
    ['title' => 'Salle de bain', 'image' => 'bathroom-light-ceramique.jpg', 'alt' => 'Salle de bain lumineuse avec surface céramique'],
    ['title' => 'Table sur mesure', 'image' => 'texture-walnut-stone.jpg', 'alt' => 'Détail de matière minérale pour table sur mesure'],
    ['title' => 'Extérieur', 'image' => 'outdoor-light-ceramique.jpg', 'alt' => 'Espace extérieur clair avec surface céramique'],
];

$applications = pcp_admin_content_pipe_rows($page_id, 'pcp_applications', ['title', 'image', 'alt'], $applications);
$compareColumns = pcp_admin_content_blocks(
    $page_id,
    'pcp_compare_columns',
    [
        [
            'title' => 'Surface céramique',
            'items' => [
                'Bonne tenue à la chaleur selon usage et finition.',
                'Très bonne résistance aux rayures du quotidien.',
                'Entretien simple sur surface compacte.',
                'Compatible avec de nombreux projets intérieurs.',
                'Rendu esthétique minéral très premium.',
            ],
        ],
        [
            'title' => 'Surface classique',
            'items' => [
                'Performances variables selon matériau.',
                'Plus sensible aux traces ou impacts selon usage.',
                'Entretien parfois plus spécifique.',
                'Moins adaptée à certains environnements exigeants.',
                'Rendu dépendant fortement de la finition.',
            ],
        ],
    ]
);

$process = [
    'Analyse de votre espace',
    'Sélection de la matière',
    'Prise de mesures',
    'Découpe et préparation',
    'Pose et ajustement',
    'Contrôle final',
];
$process = pcp_admin_content_lines($page_id, 'pcp_process_steps', $process);

$details = [
    'Épaisseur',
    'Type de chant',
    'Finition mate ou brillante',
    'Intégration évier',
    'Crédence assortie',
    'Arrondis et découpes spéciales',
];
$details = pcp_admin_content_lines($page_id, 'pcp_premium_details', $details);

$fallbackPosts = [
    ['cat' => 'Conseils', 'title' => 'Comment choisir la bonne couleur pour un plan céramique ?', 'image' => 'blog-material-choice.jpg', 'text' => 'Couleur claire, effet marbre, pierre naturelle ou ambiance chaleureuse : chaque finition change l’atmosphère de la cuisine.'],
    ['cat' => 'Inspiration', 'title' => 'Les tendances cuisine premium en 2026', 'image' => 'blog-kitchen-trends.jpg', 'text' => 'Les cuisines modernes misent sur la lumière, les matières naturelles, les tons doux et les surfaces durables.'],
    ['cat' => 'Guide', 'title' => 'Entretenir un plan de travail céramique au quotidien', 'image' => 'blog-ceramique-maintenance.jpg', 'text' => 'Quelques gestes simples permettent de garder une surface propre, élégante et agréable à utiliser.'],
];
?>

<div class="pcstudio-loader" aria-hidden="true">
  <div class="pcstudio-loader__brand">
    <span class="logo-mark">D</span>
    <span><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_loader_brand', 'PLAN CÉRAMIQUE STUDIO')); ?></span>
  </div>
  <span class="pcstudio-loader__line"></span>
  <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_loader_text', 'Surface nouvelle génération')); ?></p>
</div>

<main id="main-content" class="site-main pcstudio">
  <section class="pcstudio-hero" id="accueil">
    <div class="pcstudio-hero__copy reveal-up">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_eyebrow', 'Studio de surfaces premium')); ?></p>
      <h1 class="hero-title"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_title', 'Plan céramique nouvelle génération')); ?></h1>
      <p class="pcstudio-hero__lead"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_lead', 'Des plans de travail premium pour cuisines, îlots, salles de bain et projets architecturaux.')); ?></p>
      <div class="pcstudio-actions">
        <a class="button" href="#devis"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_primary_cta_text', 'Demander un devis')); ?></a>
        <a class="button button--ghost" href="#matieres"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_secondary_cta_text', 'Explorer les matières')); ?></a>
      </div>
      <div class="pcstudio-badges" aria-label="Avantages principaux">
<?php foreach ($heroBadges as $badge) : ?>
        <span><?php echo esc_html($badge); ?></span>
<?php endforeach; ?>
      </div>
    </div>
    <figure class="pcstudio-hero__media reveal-up">
      <img src="<?php echo esc_url($asset_img($hero_image)); ?>" width="980" height="720" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_hero_image_alt', 'Cuisine lumineuse haut de gamme avec plan de travail en céramique claire')); ?>">
      <figcaption><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_caption', 'Cuisine — Îlot central — Salle de bain — Crédence — Extérieur')); ?></figcaption>
    </figure>
  </section>

  <section class="pcstudio-section pcstudio-editorial reveal-up" id="matieres">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_eyebrow', 'Nouvelle ère')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_title', 'L’ère nouvelle du plan de travail')); ?></h2>
    </div>
    <div class="pcstudio-editorial__grid">
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_text', 'Le plan de travail n’est plus seulement une surface fonctionnelle. Il devient une pièce centrale de l’espace, un élément de design, de confort et de personnalité.')); ?></p>
      <div class="pcstudio-editorial__cards">
<?php foreach ($editorialCards as $card) : ?>
        <article><span><?php echo esc_html($card['title']); ?></span><p><?php echo esc_html($card['text']); ?></p></article>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="pcstudio-section pcstudio-surface reveal-up">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_surface_eyebrow', 'Surface Intelligence')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_surface_title', 'Une surface pensée pour les espaces exigeants.')); ?></h2>
    </div>
    <div class="pcstudio-surface__grid">
      <?php foreach ($surfaceCards as $card) : ?>
        <article class="pcstudio-feature-card">
          <span><?php echo esc_html($card['num']); ?></span>
          <h3><?php echo esc_html($card['title']); ?></h3>
          <p><?php echo esc_html($card['text']); ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <?php get_template_part('template-parts/section', 'matieres'); ?>

  <section class="pcstudio-section pcstudio-scanner reveal-up" data-scanner>
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_scanner_eyebrow', 'Material Scanner')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_scanner_title', 'Analysez la matière qui donnera du caractère à votre espace.')); ?></h2>
    </div>
    <div class="pcstudio-scanner__stage">
      <figure>
        <img src="<?php echo esc_url($asset_img(pcp_admin_content_value($page_id, 'pcp_scanner_image', 'texture-white-vein.jpg'))); ?>" loading="lazy" width="920" height="620" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_scanner_image_alt', 'Texture claire de surface céramique veinée')); ?>">
<?php foreach ($scannerPoints as $index => $point) : ?>
        <button class="scanner-point<?php echo $index === 0 ? ' is-active' : ''; ?>" type="button" style="--x:<?php echo esc_attr($point['x']); ?>;--y:<?php echo esc_attr($point['y']); ?>" data-title="<?php echo esc_attr($point['title']); ?>" data-text="<?php echo esc_attr($point['text']); ?>"><?php echo esc_html($point['num']); ?></button>
<?php endforeach; ?>
      </figure>
      <aside class="pcstudio-scanner__panel">
        <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_scanner_panel_eyebrow', 'Point matière')); ?></p>
        <h3 data-scanner-title><?php echo esc_html($activeScannerPoint['title']); ?></h3>
        <p data-scanner-text><?php echo esc_html($activeScannerPoint['text']); ?></p>
      </aside>
    </div>
  </section>

  <?php get_template_part('template-parts/section', 'sticky-story'); ?>

  <section class="pcstudio-section pcstudio-moods reveal-up" id="ambiances">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_ambiance_eyebrow', 'Ambiances signatures')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_ambiance_title', 'Choisissez une atmosphère, pas seulement une matière.')); ?></h2>
    </div>
    <div class="pcstudio-moods__grid">
      <?php foreach ($ambiances as $ambiance) : ?>
        <article class="pcstudio-mood-card">
          <img src="<?php echo esc_url($asset_img($ambiance['image'])); ?>" loading="lazy" width="720" height="520" alt="<?php echo esc_attr($ambiance['alt']); ?>">
          <div>
            <h3><?php echo esc_html($ambiance['title']); ?></h3>
            <p><?php echo esc_html($ambiance['text']); ?></p>
            <div class="pcstudio-swatches">
              <?php foreach ($ambiance['colors'] as $color) : ?>
                <span style="--swatch: <?php echo esc_attr($color); ?>"></span>
              <?php endforeach; ?>
            </div>
            <a href="#devis"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_ambiance_cta_text', 'Choisir cette ambiance')); ?></a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="pcstudio-section pcstudio-config reveal-up" data-configurator>
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label">Configurateur express</p>
      <h2>Trouvez votre ambiance idéale</h2>
    </div>
    <div class="pcstudio-config__grid">
      <div class="pcstudio-config__steps">
        <fieldset>
          <legend>Votre projet</legend>
          <button type="button" class="is-active" data-group="project" data-value="Cuisine">Cuisine</button>
          <button type="button" data-group="project" data-value="Îlot central">Îlot central</button>
          <button type="button" data-group="project" data-value="Salle de bain">Salle de bain</button>
          <button type="button" data-group="project" data-value="Extérieur">Extérieur</button>
        </fieldset>
        <fieldset>
          <legend>Votre style</legend>
          <button type="button" class="is-active" data-group="style" data-value="Clair">Clair</button>
          <button type="button" data-group="style" data-value="Chaleureux">Chaleureux</button>
          <button type="button" data-group="style" data-value="Marbre">Marbre</button>
          <button type="button" data-group="style" data-value="Pierre">Pierre</button>
          <button type="button" data-group="style" data-value="Naturel">Naturel</button>
        </fieldset>
        <fieldset>
          <legend>Votre ambiance</legend>
          <button type="button" class="is-active" data-group="mood" data-value="Premium">Premium</button>
          <button type="button" data-group="mood" data-value="Minimaliste">Minimaliste</button>
          <button type="button" data-group="mood" data-value="Familiale">Familiale</button>
          <button type="button" data-group="mood" data-value="Architecturale">Architecturale</button>
        </fieldset>
      </div>
      <aside class="pcstudio-config__result">
        <img src="<?php echo esc_url($asset_img('kitchen-warm-ceramique.jpg')); ?>" loading="lazy" width="520" height="340" alt="Ambiance céramique recommandée" data-config-image>
        <p class="pcstudio-label">Résultat recommandé</p>
        <h3 data-config-title>Warm Mineral</h3>
        <p data-config-text>Une base lumineuse, minérale et chaleureuse pour un projet Cuisine au style Clair.</p>
        <div class="pcstudio-swatches" data-config-swatches>
          <span style="--swatch:#FBF8F2"></span>
          <span style="--swatch:#D8C7AD"></span>
          <span style="--swatch:#6B4E35"></span>
        </div>
        <a class="button" href="#devis">Préparer mon devis</a>
      </aside>
    </div>
  </section>

  <?php get_template_part('template-parts/section', 'realisations'); ?>

  <section class="pcstudio-section pcstudio-applications reveal-up" id="applications">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_applications_eyebrow', 'Applications')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_applications_title', 'Une matière, plusieurs espaces')); ?></h2>
    </div>
    <div class="pcstudio-applications__grid">
      <?php foreach ($applications as $item) : ?>
        <article>
          <img src="<?php echo esc_url($asset_img($item['image'])); ?>" loading="lazy" width="620" height="460" alt="<?php echo esc_attr($item['alt']); ?>">
          <h3><?php echo esc_html($item['title']); ?></h3>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="pcstudio-section pcstudio-compare reveal-up">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_compare_eyebrow', 'Comparateur')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_compare_title', 'Céramique vs surface classique')); ?></h2>
    </div>
    <div class="pcstudio-compare__grid">
<?php foreach ($compareColumns as $index => $column) : ?>
      <article<?php echo $index === 0 ? ' class="is-featured"' : ''; ?>>
        <h3><?php echo esc_html($column['title']); ?></h3>
        <ul>
<?php foreach ($column['items'] as $item) : ?>
          <li><?php echo esc_html($item); ?></li>
<?php endforeach; ?>
        </ul>
      </article>
<?php endforeach; ?>
    </div>
  </section>

  <section class="pcstudio-section pcstudio-process reveal-up">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_process_eyebrow', 'Processus')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_process_title', 'De l’idée à la surface finale')); ?></h2>
    </div>
    <div class="pcstudio-process__grid">
      <?php foreach ($process as $index => $step) : ?>
        <article>
          <span><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
          <h3><?php echo esc_html($step); ?></h3>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="pcstudio-section pcstudio-details reveal-up">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_details_eyebrow', 'Détails premium')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_details_title', 'Les détails invisibles font le luxe visible')); ?></h2>
    </div>
    <div class="pcstudio-details__grid">
      <?php foreach ($details as $detail) : ?>
        <article><span aria-hidden="true">+</span><h3><?php echo esc_html($detail); ?></h3></article>
      <?php endforeach; ?>
    </div>
  </section>

  <?php get_template_part('template-parts/section', 'before-after'); ?>
  <?php get_template_part('template-parts/section', 'gallery'); ?>

  <section class="pcstudio-section pcstudio-blog reveal-up">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label">Conseils & inspirations</p>
      <h2>Guides, tendances et idées pour imaginer votre futur plan de travail.</h2>
    </div>
    <div class="pcstudio-blog__grid">
      <?php
      $landingPosts = new WP_Query(['posts_per_page' => 3, 'ignore_sticky_posts' => true]);
      if ($landingPosts->have_posts()) :
          while ($landingPosts->have_posts()) :
              $landingPosts->the_post();
              get_template_part('template-parts/content', 'post-card');
          endwhile;
          wp_reset_postdata();
      else :
          foreach ($fallbackPosts as $post) :
      ?>
        <article class="pcstudio-post-card">
          <img src="<?php echo esc_url($asset_img($post['image'])); ?>" loading="lazy" width="540" height="360" alt="<?php echo esc_attr($post['title']); ?>">
          <p class="pcstudio-label"><?php echo esc_html($post['cat']); ?></p>
          <h3><?php echo esc_html($post['title']); ?></h3>
          <p><?php echo esc_html($post['text']); ?></p>
          <a href="#devis">Demander un conseil</a>
        </article>
      <?php
          endforeach;
      endif;
      ?>
    </div>
    <a class="button button--ghost pcstudio-blog__more" href="#devis">Demander un conseil</a>
  </section>

  <?php get_template_part('template-parts/section', 'testimonials'); ?>

  <section class="pcstudio-section pcstudio-quote reveal-up" id="devis" data-quote-wizard>
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label">Formulaire</p>
      <h2>Demander un devis</h2>
    </div>
    <form class="pcstudio-wizard" data-pcp-form novalidate>
      <input type="hidden" name="pcp_form_type" value="quote">
      <input type="hidden" name="first_name" value="">
      <input type="hidden" name="desired_material" data-wizard-material value="Blanc veiné">
      <input type="hidden" name="project_dimensions" data-wizard-dimensions value="">
      <input type="hidden" name="message" data-wizard-message value="">
      <input type="text" name="website" value="" autocomplete="off" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px;">
      <div class="pcstudio-wizard__progress"><span data-wizard-progress></span></div>
      <div class="pcstudio-wizard__step is-active" data-step="1">
        <h3>Type de projet</h3>
        <div class="pcstudio-choice-grid">
          <?php foreach (['Cuisine', 'Îlot central', 'Salle de bain', 'Crédence', 'Extérieur', 'Autre'] as $choice) : ?>
            <label><input type="radio" name="project_type" value="<?php echo esc_attr($choice); ?>" <?php checked($choice, 'Cuisine'); ?>><?php echo esc_html($choice); ?></label>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="pcstudio-wizard__step" data-step="2">
        <h3>Style souhaité</h3>
        <div class="pcstudio-choice-grid">
          <?php foreach (['Blanc veiné', 'Beige minéral', 'Pierre naturelle', 'Gris clair', 'Bois & pierre', 'Je ne sais pas encore'] as $choice) : ?>
            <label><input type="radio" name="style" value="<?php echo esc_attr($choice); ?>" <?php checked($choice, 'Blanc veiné'); ?>><?php echo esc_html($choice); ?></label>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="pcstudio-wizard__step" data-step="3">
        <h3>Budget approximatif</h3>
        <div class="pcstudio-choice-grid">
          <?php foreach (['Moins de 2 000 €', '2 000 à 5 000 €', '5 000 à 10 000 €', 'Plus de 10 000 €', 'À définir'] as $choice) : ?>
            <label><input type="radio" name="budget" value="<?php echo esc_attr($choice); ?>" <?php checked($choice, 'À définir'); ?>><?php echo esc_html($choice); ?></label>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="pcstudio-wizard__step" data-step="4">
        <h3>Informations</h3>
        <div class="pcstudio-form-grid">
          <label>Nom<input type="text" name="last_name" autocomplete="name" required></label>
          <label>Email<input type="email" name="email" autocomplete="email" required></label>
          <label>Téléphone<input type="tel" name="phone" autocomplete="tel"></label>
          <label>Ville<input type="text" name="city" autocomplete="address-level2"></label>
          <label>Dimensions approximatives<input type="text" name="dimensions_display" data-wizard-dimensions-display placeholder="Ex. 320 x 65 cm + îlot"></label>
          <label class="is-wide">Message<textarea name="message_display" data-wizard-message-display placeholder="Décrivez votre espace, vos envies et vos contraintes."></textarea></label>
        </div>
        <div class="pcstudio-wizard__summary" data-wizard-summary></div>
      </div>
      <div class="pcstudio-wizard__actions">
        <button type="button" class="button button--ghost" data-wizard-prev>Précédent</button>
        <button type="button" class="button" data-wizard-next>Continuer</button>
      </div>
      <p class="pcstudio-wizard__status" data-wizard-status data-pcp-form-status aria-live="polite"></p>
    </form>
  </section>

  <section class="pcstudio-section pcstudio-final reveal-up">
    <div>
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_final_cta_eyebrow', 'Projet architectural')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_final_cta_title', 'Votre projet mérite une surface d’exception')); ?></h2>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_final_cta_text', 'Parlez-nous de votre espace, de vos envies et de votre ambiance idéale. Nous vous aidons à imaginer un plan céramique adapté à votre projet.')); ?></p>
    </div>
    <div class="pcstudio-actions">
      <a class="button" href="#devis"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_final_cta_button_text', 'Demander un devis')); ?></a>
      <a class="button button--ghost" href="#matieres">Explorer les matières</a>
    </div>
  </section>

  <div class="pcstudio-floating-cta" data-floating-cta>
    <a class="button" href="#devis">Demander un devis</a>
    <a class="pcstudio-top-link" href="#accueil" aria-label="Retour en haut">↑</a>
  </div>
</main>
<?php get_footer(); ?>
