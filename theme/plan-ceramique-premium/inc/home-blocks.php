<?php

function pcp_home_has_blocks(string $content): bool
{
    return str_contains($content, '<!-- wp:pcp/home-');
}

function pcp_home_asset_img(string $file): string
{
    if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
        return $file;
    }

    return get_template_directory_uri() . '/assets/img/' . ltrim($file, '/');
}

function pcp_home_attr(array $attrs, string $key, string $fallback = ''): string
{
    $value = $attrs[$key] ?? '';

    return is_string($value) && $value !== '' ? $value : $fallback;
}

function pcp_home_meta(int $post_id, array $attrs, string $attr, string $meta, string $fallback = ''): string
{
    return pcp_home_attr($attrs, $attr, pcp_admin_content_value($post_id, $meta, $fallback));
}

function pcp_home_lines_from_attr(array $attrs, string $key, array $fallback): array
{
    $value = (string) ($attrs[$key] ?? '');

    if ($value === '') {
        return $fallback;
    }

    $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: []));

    return $lines ?: $fallback;
}

function pcp_home_pipe_rows_from_attr(array $attrs, string $key, array $columns, array $fallback): array
{
    $rows = [];

    foreach (pcp_home_lines_from_attr($attrs, $key, []) as $line) {
        $parts = array_map('trim', explode('|', $line, count($columns)));

        if (count($parts) !== count($columns)) {
            continue;
        }

        $row = [];

        foreach ($columns as $index => $column) {
            $row[$column] = $parts[$index];
        }

        $rows[] = $row;
    }

    return $rows ?: $fallback;
}

function pcp_home_blocks_from_attr(array $attrs, string $key, array $fallback): array
{
    $value = (string) ($attrs[$key] ?? '');

    if ($value === '') {
        return $fallback;
    }

    $blocks = array_filter(array_map('trim', preg_split('/\r\n\s*\r\n|\r\s*\r|\n\s*\n/', $value) ?: []));
    $parsed = [];

    foreach ($blocks as $block) {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $block) ?: []));

        if (count($lines) < 2) {
            continue;
        }

        $parsed[] = [
            'title' => array_shift($lines),
            'items' => $lines,
        ];
    }

    return $parsed ?: $fallback;
}

function pcp_home_render_loader(int $post_id = 0): string
{
    $post_id = $post_id ?: get_queried_object_id();

    ob_start();
    ?>
    <div class="pcstudio-loader" aria-hidden="true">
      <div class="pcstudio-loader__brand">
        <span class="logo-mark">D</span>
        <span><?php echo esc_html(pcp_admin_content_value($post_id, 'pcp_loader_brand', 'PLAN CERAMIQUE STUDIO')); ?></span>
      </div>
      <span class="pcstudio-loader__line"></span>
      <p><?php echo esc_html(pcp_admin_content_value($post_id, 'pcp_loader_text', 'Surface nouvelle generation')); ?></p>
    </div>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_hero_block(array $attrs): string
{
    $post_id = get_queried_object_id();
    $badges = pcp_home_lines_from_attr(
        $attrs,
        'badges',
        pcp_admin_content_lines($post_id, 'pcp_hero_badges', ['Resistant chaleur', 'Resistant rayures', 'Interieur / exterieur'])
    );

    ob_start();
    ?>
    <section class="pcstudio-hero" id="accueil">
      <div class="pcstudio-hero__copy reveal-up">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_hero_eyebrow', 'Studio de surfaces premium')); ?></p>
        <h1 class="hero-title"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_hero_title', 'Plan ceramique nouvelle generation')); ?></h1>
        <p class="pcstudio-hero__lead"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'lead', 'pcp_hero_lead', 'Des plans de travail premium pour cuisines, ilots, salles de bain et projets architecturaux.')); ?></p>
        <div class="pcstudio-actions">
          <a class="button" href="<?php echo esc_url(pcp_home_meta($post_id, $attrs, 'primaryUrl', 'pcp_primary_cta_url', '#devis')); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'primaryText', 'pcp_primary_cta_text', 'Demander un devis')); ?></a>
          <a class="button button--ghost" href="<?php echo esc_url(pcp_home_meta($post_id, $attrs, 'secondaryUrl', 'pcp_secondary_cta_url', '#matieres')); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'secondaryText', 'pcp_secondary_cta_text', 'Explorer les matieres')); ?></a>
        </div>
        <div class="pcstudio-badges" aria-label="<?php esc_attr_e('Avantages principaux', 'plan-ceramique-premium'); ?>">
          <?php foreach ($badges as $badge) : ?>
            <span><?php echo esc_html($badge); ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <figure class="pcstudio-hero__media reveal-up">
        <img src="<?php echo esc_url(pcp_home_asset_img(pcp_home_meta($post_id, $attrs, 'image', 'pcp_hero_image', 'hero-light-ceramique.jpg'))); ?>" width="980" height="720" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_hero_image_alt', 'Cuisine lumineuse haut de gamme avec plan de travail en ceramique claire')); ?>">
        <figcaption><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'caption', 'pcp_hero_caption', 'Cuisine - Ilot central - Salle de bain - Credence - Exterieur')); ?></figcaption>
      </figure>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_editorial_block(array $attrs): string
{
    $post_id = get_queried_object_id();
    $fallback = pcp_admin_content_pipe_rows(
        $post_id,
        'pcp_cards_json',
        ['title', 'text'],
        [
            ['title' => 'Design', 'text' => 'Une presence architecturale qui structure la cuisine.'],
            ['title' => 'Resistance', 'text' => 'Une matiere minerale adaptee aux usages exigeants.'],
            ['title' => 'Ambiance', 'text' => 'Des teintes claires, chaleureuses et haut de gamme.'],
        ]
    );
    $cards = pcp_home_pipe_rows_from_attr($attrs, 'cards', ['title', 'text'], $fallback);

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-editorial reveal-up" id="matieres">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_intro_eyebrow', 'Nouvelle ere')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_intro_title', 'L ere nouvelle du plan de travail')); ?></h2>
      </div>
      <div class="pcstudio-editorial__grid">
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'text', 'pcp_intro_text', 'Le plan de travail devient une piece centrale de l espace.')); ?></p>
        <div class="pcstudio-editorial__cards">
          <?php foreach ($cards as $card) : ?>
            <article><span><?php echo esc_html($card['title']); ?></span><p><?php echo esc_html($card['text']); ?></p></article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_surface_block(array $attrs): string
{
    $post_id = get_queried_object_id();
    $fallback = pcp_admin_content_pipe_rows(
        $post_id,
        'pcp_surface_cards',
        ['num', 'title', 'text'],
        [
            ['num' => '01', 'title' => 'Chaleur', 'text' => 'Une surface ceramique pensee pour les cuisines exigeantes.'],
            ['num' => '02', 'title' => 'Rayures', 'text' => 'Une excellente tenue a l usage.'],
            ['num' => '03', 'title' => 'Taches', 'text' => 'Une matiere compacte et facile a nettoyer.'],
        ]
    );
    $cards = pcp_home_pipe_rows_from_attr($attrs, 'cards', ['num', 'title', 'text'], $fallback);

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-surface reveal-up">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_surface_eyebrow', 'Surface Intelligence')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_surface_title', 'Une surface pensee pour les espaces exigeants.')); ?></h2>
      </div>
      <div class="pcstudio-surface__grid">
        <?php foreach ($cards as $card) : ?>
          <article class="pcstudio-feature-card">
            <span><?php echo esc_html($card['num']); ?></span>
            <h3><?php echo esc_html($card['title']); ?></h3>
            <p><?php echo esc_html($card['text']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_template_part_block(array $attrs, string $slug, string $name): string
{
    ob_start();
    get_template_part($slug, $name);

    return (string) ob_get_clean();
}

function pcp_render_home_scanner_block(array $attrs): string
{
    $post_id = get_queried_object_id();
    $fallback = pcp_admin_content_pipe_rows(
        $post_id,
        'pcp_scanner_points',
        ['num', 'x', 'y', 'title', 'text'],
        [
            ['num' => '1', 'x' => '22%', 'y' => '32%', 'title' => 'Veinage', 'text' => 'Un dessin mineral subtil apporte du mouvement.'],
            ['num' => '2', 'x' => '48%', 'y' => '24%', 'title' => 'Texture', 'text' => 'Un toucher mat ou satine donne une lecture plus douce.'],
        ]
    );
    $points = pcp_home_pipe_rows_from_attr($attrs, 'points', ['num', 'x', 'y', 'title', 'text'], $fallback);
    $active = $points[0] ?? ['title' => '', 'text' => ''];

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-scanner reveal-up" data-scanner>
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_scanner_eyebrow', 'Material Scanner')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_scanner_title', 'Analysez la matiere qui donnera du caractere a votre espace.')); ?></h2>
      </div>
      <div class="pcstudio-scanner__stage">
        <figure>
          <img src="<?php echo esc_url(pcp_home_asset_img(pcp_home_meta($post_id, $attrs, 'image', 'pcp_scanner_image', 'texture-white-vein.jpg'))); ?>" loading="lazy" width="920" height="620" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_scanner_image_alt', 'Texture claire de surface ceramique veinee')); ?>">
          <?php foreach ($points as $index => $point) : ?>
            <button class="scanner-point<?php echo $index === 0 ? ' is-active' : ''; ?>" type="button" style="--x:<?php echo esc_attr($point['x']); ?>;--y:<?php echo esc_attr($point['y']); ?>" data-title="<?php echo esc_attr($point['title']); ?>" data-text="<?php echo esc_attr($point['text']); ?>"><?php echo esc_html($point['num']); ?></button>
          <?php endforeach; ?>
        </figure>
        <aside class="pcstudio-scanner__panel">
          <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'panelEyebrow', 'pcp_scanner_panel_eyebrow', 'Point matiere')); ?></p>
          <h3 data-scanner-title><?php echo esc_html($active['title']); ?></h3>
          <p data-scanner-text><?php echo esc_html($active['text']); ?></p>
        </aside>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_moods_block(array $attrs): string
{
    $post_id = get_queried_object_id();
    $fallback = pcp_admin_content_pipe_rows(
        $post_id,
        'pcp_ambiance_cards',
        ['title', 'image', 'alt', 'text', 'colors'],
        [
            ['title' => 'Warm Mineral', 'image' => 'kitchen-warm-ceramique.jpg', 'alt' => 'Cuisine lumineuse', 'text' => 'Pierre claire, bois noyer et lumiere chaude.', 'colors' => '#FBF8F2,#D8C7AD,#6B4E35'],
        ]
    );
    $moods = pcp_home_pipe_rows_from_attr($attrs, 'cards', ['title', 'image', 'alt', 'text', 'colors'], $fallback);

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-moods reveal-up" id="ambiances">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_ambiance_eyebrow', 'Ambiances signatures')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_ambiance_title', 'Choisissez une atmosphere, pas seulement une matiere.')); ?></h2>
      </div>
      <div class="pcstudio-moods__grid">
        <?php foreach ($moods as $mood) : ?>
          <article class="pcstudio-mood-card">
            <img src="<?php echo esc_url(pcp_home_asset_img($mood['image'])); ?>" loading="lazy" width="720" height="520" alt="<?php echo esc_attr($mood['alt']); ?>">
            <div>
              <h3><?php echo esc_html($mood['title']); ?></h3>
              <p><?php echo esc_html($mood['text']); ?></p>
              <div class="pcstudio-swatches">
                <?php foreach (array_filter(array_map('trim', explode(',', (string) $mood['colors']))) as $color) : ?>
                  <span style="--swatch: <?php echo esc_attr($color); ?>"></span>
                <?php endforeach; ?>
              </div>
              <a href="<?php echo esc_url(pcp_home_meta($post_id, $attrs, 'ctaUrl', 'pcp_ambiance_cta_url', '#devis')); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'ctaText', 'pcp_ambiance_cta_text', 'Choisir cette ambiance')); ?></a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_configurator_block(array $attrs): string
{
    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-config reveal-up" data-configurator>
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'eyebrow', 'Configurateur express')); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'title', 'Trouvez votre ambiance idéale')); ?></h2>
      </div>
      <div class="pcstudio-config__grid">
        <div class="pcstudio-config__steps">
          <fieldset>
            <legend><?php esc_html_e('Votre projet', 'plan-ceramique-premium'); ?></legend>
            <?php foreach (['Cuisine', 'Îlot central', 'Salle de bain', 'Extérieur'] as $index => $choice) : ?>
              <button type="button" class="<?php echo $index === 0 ? 'is-active' : ''; ?>" data-group="project" data-value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></button>
            <?php endforeach; ?>
          </fieldset>
          <fieldset>
            <legend><?php esc_html_e('Votre style', 'plan-ceramique-premium'); ?></legend>
            <?php foreach (['Clair', 'Chaleureux', 'Marbre', 'Pierre', 'Naturel'] as $index => $choice) : ?>
              <button type="button" class="<?php echo $index === 0 ? 'is-active' : ''; ?>" data-group="style" data-value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></button>
            <?php endforeach; ?>
          </fieldset>
          <fieldset>
            <legend><?php esc_html_e('Votre ambiance', 'plan-ceramique-premium'); ?></legend>
            <?php foreach (['Premium', 'Minimaliste', 'Familiale', 'Architecturale'] as $index => $choice) : ?>
              <button type="button" class="<?php echo $index === 0 ? 'is-active' : ''; ?>" data-group="mood" data-value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></button>
            <?php endforeach; ?>
          </fieldset>
        </div>
        <aside class="pcstudio-config__result">
          <img src="<?php echo esc_url(pcp_home_asset_img('kitchen-warm-ceramique.jpg')); ?>" loading="lazy" width="520" height="340" alt="<?php esc_attr_e('Ambiance céramique recommandée', 'plan-ceramique-premium'); ?>" data-config-image>
          <p class="pcstudio-label"><?php esc_html_e('Résultat recommandé', 'plan-ceramique-premium'); ?></p>
          <h3 data-config-title>Warm Mineral</h3>
          <p data-config-text>Une base lumineuse, minérale et chaleureuse pour un projet Cuisine au style Clair.</p>
          <div class="pcstudio-swatches" data-config-swatches>
            <span style="--swatch:#FBF8F2"></span>
            <span style="--swatch:#D8C7AD"></span>
            <span style="--swatch:#6B4E35"></span>
          </div>
          <a class="button" href="#devis"><?php esc_html_e('Préparer mon devis', 'plan-ceramique-premium'); ?></a>
        </aside>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_applications_block(array $attrs): string
{
    $post_id = get_queried_object_id();
    $fallback = pcp_admin_content_pipe_rows(
        $post_id,
        'pcp_applications',
        ['title', 'image', 'alt'],
        [
            ['title' => 'Plan de travail cuisine', 'image' => 'kitchen-white-ceramique.jpg', 'alt' => 'Plan de travail cuisine en ceramique claire'],
            ['title' => 'Ilot central', 'image' => 'island-light-ceramique.jpg', 'alt' => 'Ilot central avec surface ceramique premium'],
        ]
    );
    $items = pcp_home_pipe_rows_from_attr($attrs, 'items', ['title', 'image', 'alt'], $fallback);

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-applications reveal-up" id="applications">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_applications_eyebrow', 'Applications')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_applications_title', 'Une matiere, plusieurs espaces')); ?></h2>
      </div>
      <div class="pcstudio-applications__grid">
        <?php foreach ($items as $item) : ?>
          <article>
            <img src="<?php echo esc_url(pcp_home_asset_img($item['image'])); ?>" loading="lazy" width="620" height="460" alt="<?php echo esc_attr($item['alt']); ?>">
            <h3><?php echo esc_html($item['title']); ?></h3>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_compare_block(array $attrs): string
{
    $post_id = get_queried_object_id();
    $fallback = pcp_admin_content_blocks(
        $post_id,
        'pcp_compare_columns',
        [
            ['title' => 'Surface ceramique', 'items' => ['Bonne tenue a la chaleur selon usage et finition.', 'Entretien simple sur surface compacte.']],
            ['title' => 'Surface classique', 'items' => ['Performances variables selon materiau.', 'Entretien parfois plus specifique.']],
        ]
    );
    $columns = pcp_home_blocks_from_attr($attrs, 'columns', $fallback);

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-compare reveal-up">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_compare_eyebrow', 'Comparateur')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_compare_title', 'Ceramique vs surface classique')); ?></h2>
      </div>
      <div class="pcstudio-compare__grid">
        <?php foreach ($columns as $index => $column) : ?>
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
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_list_grid_block(array $attrs, string $class, string $meta_key, array $fallback): string
{
    $post_id = get_queried_object_id();
    $items = pcp_home_lines_from_attr($attrs, 'items', pcp_admin_content_lines($post_id, $meta_key, $fallback));

    ob_start();
    ?>
    <section class="pcstudio-section <?php echo esc_attr($class); ?> reveal-up">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'eyebrow', '')); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'title', '')); ?></h2>
      </div>
      <div class="<?php echo esc_attr($class); ?>__grid">
        <?php foreach ($items as $index => $item) : ?>
          <article>
            <?php if ($class === 'pcstudio-process') : ?>
              <span><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
            <?php else : ?>
              <span aria-hidden="true">+</span>
            <?php endif; ?>
            <h3><?php echo esc_html($item); ?></h3>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_process_block(array $attrs): string
{
    $post_id = get_queried_object_id();
    $attrs['eyebrow'] = pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_process_eyebrow', 'Processus');
    $attrs['title'] = pcp_home_meta($post_id, $attrs, 'title', 'pcp_process_title', 'De l idee a la surface finale');

    return pcp_render_home_list_grid_block($attrs, 'pcstudio-process', 'pcp_process_steps', ['Analyse de votre espace', 'Selection de la matiere', 'Prise de mesures']);
}

function pcp_render_home_details_block(array $attrs): string
{
    $post_id = get_queried_object_id();
    $attrs['eyebrow'] = pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_details_eyebrow', 'Details premium');
    $attrs['title'] = pcp_home_meta($post_id, $attrs, 'title', 'pcp_details_title', 'Les details invisibles font le luxe visible');

    return pcp_render_home_list_grid_block($attrs, 'pcstudio-details', 'pcp_premium_details', ['Epaisseur', 'Type de chant', 'Finition mate ou brillante']);
}

function pcp_render_home_blog_block(array $attrs): string
{
    $fallback_posts = [
        ['cat' => 'Conseils', 'title' => 'Comment choisir la bonne couleur pour un plan ceramique ?', 'image' => 'blog-material-choice.jpg', 'text' => 'Couleur claire, effet marbre, pierre naturelle ou ambiance chaleureuse.'],
        ['cat' => 'Inspiration', 'title' => 'Les tendances cuisine premium en 2026', 'image' => 'blog-kitchen-trends.jpg', 'text' => 'Les cuisines modernes misent sur la lumiere et les matieres naturelles.'],
        ['cat' => 'Guide', 'title' => 'Entretenir un plan de travail ceramique au quotidien', 'image' => 'blog-ceramique-maintenance.jpg', 'text' => 'Quelques gestes simples permettent de garder une surface propre.'],
    ];

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-blog reveal-up">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'eyebrow', 'Conseils & inspirations')); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'title', 'Guides, tendances et idées pour imaginer votre futur plan de travail.')); ?></h2>
      </div>
      <div class="pcstudio-blog__grid">
        <?php
        $landing_posts = new WP_Query(['posts_per_page' => 3, 'ignore_sticky_posts' => true]);
        if ($landing_posts->have_posts()) :
            while ($landing_posts->have_posts()) :
                $landing_posts->the_post();
                get_template_part('template-parts/content', 'post-card');
            endwhile;
            wp_reset_postdata();
        else :
            foreach ($fallback_posts as $post) :
                ?>
                <article class="pcstudio-post-card">
                  <img src="<?php echo esc_url(pcp_home_asset_img($post['image'])); ?>" loading="lazy" width="540" height="360" alt="<?php echo esc_attr($post['title']); ?>">
                  <p class="pcstudio-label"><?php echo esc_html($post['cat']); ?></p>
                  <h3><?php echo esc_html($post['title']); ?></h3>
                  <p><?php echo esc_html($post['text']); ?></p>
                  <a href="#devis"><?php esc_html_e('Demander un conseil', 'plan-ceramique-premium'); ?></a>
                </article>
                <?php
            endforeach;
        endif;
        ?>
      </div>
      <a class="button button--ghost pcstudio-blog__more" href="#devis"><?php esc_html_e('Demander un conseil', 'plan-ceramique-premium'); ?></a>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_quote_block(array $attrs): string
{
    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-quote reveal-up" id="devis" data-quote-wizard>
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'eyebrow', 'Formulaire')); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'title', 'Demander un devis')); ?></h2>
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
          <h3><?php esc_html_e('Type de projet', 'plan-ceramique-premium'); ?></h3>
          <div class="pcstudio-choice-grid">
            <?php foreach (['Cuisine', 'Îlot central', 'Salle de bain', 'Crédence', 'Extérieur', 'Autre'] as $choice) : ?>
              <label><input type="radio" name="project_type" value="<?php echo esc_attr($choice); ?>" <?php checked($choice, 'Cuisine'); ?>><?php echo esc_html($choice); ?></label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="pcstudio-wizard__step" data-step="2">
          <h3><?php esc_html_e('Style souhaité', 'plan-ceramique-premium'); ?></h3>
          <div class="pcstudio-choice-grid">
            <?php foreach (['Blanc veiné', 'Beige minéral', 'Pierre naturelle', 'Gris clair', 'Bois & pierre', 'Je ne sais pas encore'] as $choice) : ?>
              <label><input type="radio" name="style" value="<?php echo esc_attr($choice); ?>" <?php checked($choice, 'Blanc veiné'); ?>><?php echo esc_html($choice); ?></label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="pcstudio-wizard__step" data-step="3">
          <h3><?php esc_html_e('Budget approximatif', 'plan-ceramique-premium'); ?></h3>
          <div class="pcstudio-choice-grid">
            <?php foreach (['Moins de 2 000 €', '2 000 à 5 000 €', '5 000 à 10 000 €', 'Plus de 10 000 €', 'À définir'] as $choice) : ?>
              <label><input type="radio" name="budget" value="<?php echo esc_attr($choice); ?>" <?php checked($choice, 'À définir'); ?>><?php echo esc_html($choice); ?></label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="pcstudio-wizard__step" data-step="4">
          <h3><?php esc_html_e('Informations', 'plan-ceramique-premium'); ?></h3>
          <div class="pcstudio-form-grid">
            <label><?php esc_html_e('Nom', 'plan-ceramique-premium'); ?><input type="text" name="last_name" autocomplete="name" required></label>
            <label><?php esc_html_e('Email', 'plan-ceramique-premium'); ?><input type="email" name="email" autocomplete="email" required></label>
            <label><?php esc_html_e('Téléphone', 'plan-ceramique-premium'); ?><input type="tel" name="phone" autocomplete="tel"></label>
            <label><?php esc_html_e('Ville', 'plan-ceramique-premium'); ?><input type="text" name="city" autocomplete="address-level2"></label>
            <label><?php esc_html_e('Dimensions approximatives', 'plan-ceramique-premium'); ?><input type="text" name="dimensions_display" data-wizard-dimensions-display placeholder="Ex. 320 x 65 cm + îlot"></label>
            <label class="is-wide"><?php esc_html_e('Message', 'plan-ceramique-premium'); ?><textarea name="message_display" data-wizard-message-display placeholder="<?php esc_attr_e('Décrivez votre espace, vos envies et vos contraintes.', 'plan-ceramique-premium'); ?>"></textarea></label>
          </div>
          <div class="pcstudio-wizard__summary" data-wizard-summary></div>
        </div>
        <div class="pcstudio-wizard__actions">
          <button type="button" class="button button--ghost" data-wizard-prev><?php esc_html_e('Précédent', 'plan-ceramique-premium'); ?></button>
          <button type="button" class="button" data-wizard-next><?php esc_html_e('Continuer', 'plan-ceramique-premium'); ?></button>
        </div>
        <p class="pcstudio-wizard__status" data-wizard-status data-pcp-form-status aria-live="polite"></p>
      </form>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_final_cta_block(array $attrs): string
{
    $post_id = get_queried_object_id();

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-final reveal-up">
      <div>
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_final_cta_eyebrow', 'Projet architectural')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_final_cta_title', 'Votre projet merite une surface d exception')); ?></h2>
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'text', 'pcp_final_cta_text', 'Parlez-nous de votre espace, de vos envies et de votre ambiance ideale.')); ?></p>
      </div>
      <div class="pcstudio-actions">
        <a class="button" href="<?php echo esc_url(pcp_home_meta($post_id, $attrs, 'primaryUrl', 'pcp_final_cta_button_url', '#devis')); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'primaryText', 'pcp_final_cta_button_text', 'Demander un devis')); ?></a>
        <a class="button button--ghost" href="<?php echo esc_url(pcp_home_attr($attrs, 'secondaryUrl', '#matieres')); ?>"><?php echo esc_html(pcp_home_attr($attrs, 'secondaryText', 'Explorer les matières')); ?></a>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_home_register_blocks(): void
{
    wp_register_script(
        'pcp-home-blocks',
        get_template_directory_uri() . '/assets/js/home-blocks.js',
        ['wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'wp-i18n'],
        pcp_theme_version(),
        true
    );

    $text_attrs = [
        'eyebrow' => ['type' => 'string', 'default' => ''],
        'title' => ['type' => 'string', 'default' => ''],
        'text' => ['type' => 'string', 'default' => ''],
    ];

    $blocks = [
        'home-hero' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'lead' => ['type' => 'string', 'default' => ''],
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                    'caption' => ['type' => 'string', 'default' => ''],
                    'primaryText' => ['type' => 'string', 'default' => ''],
                    'primaryUrl' => ['type' => 'string', 'default' => ''],
                    'secondaryText' => ['type' => 'string', 'default' => ''],
                    'secondaryUrl' => ['type' => 'string', 'default' => ''],
                    'badges' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_home_hero_block',
        ],
        'home-editorial' => [
            'attributes' => array_merge($text_attrs, ['cards' => ['type' => 'string', 'default' => '']]),
            'render_callback' => 'pcp_render_home_editorial_block',
        ],
        'home-surface' => [
            'attributes' => array_merge($text_attrs, ['cards' => ['type' => 'string', 'default' => '']]),
            'render_callback' => 'pcp_render_home_surface_block',
        ],
        'home-materials' => [
            'attributes' => [],
            'render_callback' => static fn(array $attrs): string => pcp_render_home_template_part_block($attrs, 'template-parts/section', 'matieres'),
        ],
        'home-scanner' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                    'panelEyebrow' => ['type' => 'string', 'default' => ''],
                    'points' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_home_scanner_block',
        ],
        'home-sticky-story' => [
            'attributes' => [],
            'render_callback' => static fn(array $attrs): string => pcp_render_home_template_part_block($attrs, 'template-parts/section', 'sticky-story'),
        ],
        'home-moods' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'cards' => ['type' => 'string', 'default' => ''],
                    'ctaText' => ['type' => 'string', 'default' => ''],
                    'ctaUrl' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_home_moods_block',
        ],
        'home-configurator' => [
            'attributes' => $text_attrs,
            'render_callback' => 'pcp_render_home_configurator_block',
        ],
        'home-projects' => [
            'attributes' => [],
            'render_callback' => static fn(array $attrs): string => pcp_render_home_template_part_block($attrs, 'template-parts/section', 'realisations'),
        ],
        'home-applications' => [
            'attributes' => array_merge($text_attrs, ['items' => ['type' => 'string', 'default' => '']]),
            'render_callback' => 'pcp_render_home_applications_block',
        ],
        'home-compare' => [
            'attributes' => array_merge($text_attrs, ['columns' => ['type' => 'string', 'default' => '']]),
            'render_callback' => 'pcp_render_home_compare_block',
        ],
        'home-process' => [
            'attributes' => array_merge($text_attrs, ['items' => ['type' => 'string', 'default' => '']]),
            'render_callback' => 'pcp_render_home_process_block',
        ],
        'home-details' => [
            'attributes' => array_merge($text_attrs, ['items' => ['type' => 'string', 'default' => '']]),
            'render_callback' => 'pcp_render_home_details_block',
        ],
        'home-before-after' => [
            'attributes' => [],
            'render_callback' => static fn(array $attrs): string => pcp_render_home_template_part_block($attrs, 'template-parts/section', 'before-after'),
        ],
        'home-gallery' => [
            'attributes' => [],
            'render_callback' => static fn(array $attrs): string => pcp_render_home_template_part_block($attrs, 'template-parts/section', 'gallery'),
        ],
        'home-blog' => [
            'attributes' => $text_attrs,
            'render_callback' => 'pcp_render_home_blog_block',
        ],
        'home-testimonials' => [
            'attributes' => [],
            'render_callback' => static fn(array $attrs): string => pcp_render_home_template_part_block($attrs, 'template-parts/section', 'testimonials'),
        ],
        'home-quote' => [
            'attributes' => $text_attrs,
            'render_callback' => 'pcp_render_home_quote_block',
        ],
        'home-final-cta' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'primaryText' => ['type' => 'string', 'default' => ''],
                    'primaryUrl' => ['type' => 'string', 'default' => ''],
                    'secondaryText' => ['type' => 'string', 'default' => ''],
                    'secondaryUrl' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_home_final_cta_block',
        ],
    ];

    foreach ($blocks as $name => $settings) {
        register_block_type(
            'pcp/' . $name,
            [
                'api_version' => 3,
                'editor_script' => 'pcp-home-blocks',
                'attributes' => $settings['attributes'],
                'render_callback' => $settings['render_callback'],
            ]
        );
    }
}
add_action('init', 'pcp_home_register_blocks');

function pcp_home_block_comment(string $name, array $attrs = []): string
{
    $json = $attrs ? ' ' . wp_json_encode($attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';

    return '<!-- wp:pcp/' . $name . $json . ' /-->';
}

function pcp_home_default_block_content(int $post_id = 0): string
{
    $post_id = $post_id ?: (int) get_option('page_on_front');
    $meta = static fn(string $key): string => $post_id ? pcp_admin_content_value($post_id, $key) : '';
    $block_attrs = [
        'home-hero' => [
            'eyebrow' => $meta('pcp_hero_eyebrow'),
            'title' => $meta('pcp_hero_title'),
            'lead' => $meta('pcp_hero_lead'),
            'image' => $meta('pcp_hero_image'),
            'imageAlt' => $meta('pcp_hero_image_alt'),
            'caption' => $meta('pcp_hero_caption'),
            'primaryText' => $meta('pcp_primary_cta_text'),
            'primaryUrl' => $meta('pcp_primary_cta_url'),
            'secondaryText' => $meta('pcp_secondary_cta_text'),
            'secondaryUrl' => $meta('pcp_secondary_cta_url'),
            'badges' => $meta('pcp_hero_badges'),
        ],
        'home-editorial' => [
            'eyebrow' => $meta('pcp_intro_eyebrow'),
            'title' => $meta('pcp_intro_title'),
            'text' => $meta('pcp_intro_text'),
            'cards' => $meta('pcp_cards_json'),
        ],
        'home-surface' => [
            'eyebrow' => $meta('pcp_surface_eyebrow'),
            'title' => $meta('pcp_surface_title'),
            'cards' => $meta('pcp_surface_cards'),
        ],
        'home-scanner' => [
            'eyebrow' => $meta('pcp_scanner_eyebrow'),
            'title' => $meta('pcp_scanner_title'),
            'image' => $meta('pcp_scanner_image'),
            'imageAlt' => $meta('pcp_scanner_image_alt'),
            'panelEyebrow' => $meta('pcp_scanner_panel_eyebrow'),
            'points' => $meta('pcp_scanner_points'),
        ],
        'home-moods' => [
            'eyebrow' => $meta('pcp_ambiance_eyebrow'),
            'title' => $meta('pcp_ambiance_title'),
            'cards' => $meta('pcp_ambiance_cards'),
            'ctaText' => $meta('pcp_ambiance_cta_text'),
            'ctaUrl' => $meta('pcp_ambiance_cta_url'),
        ],
        'home-applications' => [
            'eyebrow' => $meta('pcp_applications_eyebrow'),
            'title' => $meta('pcp_applications_title'),
            'items' => $meta('pcp_applications'),
        ],
        'home-compare' => [
            'eyebrow' => $meta('pcp_compare_eyebrow'),
            'title' => $meta('pcp_compare_title'),
            'columns' => $meta('pcp_compare_columns'),
        ],
        'home-process' => [
            'eyebrow' => $meta('pcp_process_eyebrow'),
            'title' => $meta('pcp_process_title'),
            'items' => $meta('pcp_process_steps'),
        ],
        'home-details' => [
            'eyebrow' => $meta('pcp_details_eyebrow'),
            'title' => $meta('pcp_details_title'),
            'items' => $meta('pcp_premium_details'),
        ],
        'home-final-cta' => [
            'eyebrow' => $meta('pcp_final_cta_eyebrow'),
            'title' => $meta('pcp_final_cta_title'),
            'text' => $meta('pcp_final_cta_text'),
            'primaryText' => $meta('pcp_final_cta_button_text'),
            'primaryUrl' => $meta('pcp_final_cta_button_url'),
        ],
    ];
    $block_names = [
        'home-hero',
        'home-editorial',
        'home-surface',
        'home-materials',
        'home-scanner',
        'home-sticky-story',
        'home-moods',
        'home-configurator',
        'home-projects',
        'home-applications',
        'home-compare',
        'home-process',
        'home-details',
        'home-before-after',
        'home-gallery',
        'home-blog',
        'home-testimonials',
        'home-quote',
        'home-final-cta',
    ];

    return implode(
        "\n\n",
        array_map(
            static fn(string $name): string => pcp_home_block_comment($name, array_filter($block_attrs[$name] ?? [], static fn($value): bool => $value !== '')),
            $block_names
        )
    );
}
