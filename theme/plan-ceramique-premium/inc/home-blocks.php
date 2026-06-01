<?php

function pcp_home_has_blocks(string $content): bool
{
    return str_contains($content, '<!-- wp:pcp/home-');
}

function pcp_services_has_blocks(string $content): bool
{
    return str_contains($content, '<!-- wp:pcp/services-');
}

function pcp_detail_has_blocks(string $content): bool
{
    return str_contains($content, '<!-- wp:pcp/detail-');
}

function pcp_contact_has_blocks(string $content): bool
{
    return str_contains($content, '<!-- wp:pcp/contact-');
}

function pcp_quote_has_blocks(string $content): bool
{
    return str_contains($content, '<!-- wp:pcp/quote-');
}

function pcp_blog_has_blocks(string $content): bool
{
    return str_contains($content, '<!-- wp:pcp/blog-');
}

function pcp_home_current_post_id(): int
{
    global $pcp_home_render_post_id, $post;

    if (isset($pcp_home_render_post_id) && (int) $pcp_home_render_post_id > 0) {
        return (int) $pcp_home_render_post_id;
    }

    $queried_id = (int) get_queried_object_id();

    if ($queried_id > 0) {
        return $queried_id;
    }

    if ($post instanceof WP_Post) {
        return (int) $post->ID;
    }

    return (int) get_option('page_on_front');
}

function pcp_home_asset_img(string $file): string
{
    if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
        return $file;
    }

    return get_template_directory_uri() . '/assets/img/' . ltrim($file, '/');
}

function pcp_services_asset_image(string $file): string
{
    if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
        return $file;
    }

    return get_template_directory_uri() . '/assets/images/' . ltrim($file, '/');
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
    $value = str_replace(['\\r\\n', '\\n', '\\r'], "\n", $value);
    $value = preg_replace('/(?<=[\p{Ll}\d\.\)])n(?=[\p{Lu}\d])/u', "\n", $value) ?: $value;
    $value = preg_replace('/(?<=\S)n(?=(?:[\p{Lu}\d][^|\r\n]{1,50}|sur mesure)\s\|)/u', "\n", $value) ?: $value;

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
    $post_id = pcp_home_current_post_id();
    $badges = pcp_home_lines_from_attr(
        $attrs,
        'badges',
        pcp_admin_content_lines($post_id, 'pcp_hero_badges', ['Resistant chaleur', 'Resistant rayures', 'Interieur / exterieur'])
    );

    ob_start();
    ?>
    <section class="pcstudio-hero" id="accueil">
      <div class="pcstudio-hero__copy reveal-up">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_hero_eyebrow', 'Plans de travail en ceramique sur mesure')); ?></p>
        <h1 class="hero-title"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_hero_title', 'Le plan ceramique qui transforme votre cuisine')); ?></h1>
        <p class="pcstudio-hero__lead"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'lead', 'pcp_hero_lead', 'Une surface premium, elegante et resistante pour ilots, credences, salles de bain et projets sur mesure.')); ?></p>
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
        <img src="<?php echo esc_url(pcp_home_asset_img(pcp_home_meta($post_id, $attrs, 'image', 'pcp_hero_image', 'island-light-ceramique.jpg'))); ?>" width="980" height="720" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_hero_image_alt', 'Ilot de cuisine lumineux avec plan de travail en ceramique premium')); ?>">
        <figcaption><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'caption', 'pcp_hero_caption', 'Ilot central - cuisine - credence - salle de bain - exterieur')); ?></figcaption>
      </figure>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_proof_block(array $attrs): string
{
    return '';

    $stats = pcp_home_pipe_rows_from_attr(
        $attrs,
        'stats',
        ['value', 'label'],
        [
            ['value' => '12 mm', 'label' => 'finesse visuelle possible'],
            ['value' => '0 joint', 'label' => 'lecture continue du plan'],
            ['value' => 'sur mesure', 'label' => 'cuisine, ilot, credence'],
        ]
    );
    $cards = pcp_home_pipe_rows_from_attr(
        $attrs,
        'cards',
        ['title', 'text'],
        [
            ['title' => 'Impact immediat', 'text' => 'Un plan de travail qui installe le niveau premium des le premier regard.'],
            ['title' => 'Usage quotidien', 'text' => 'Une surface facile a vivre, pensee pour les repas, la preparation et les passages intensifs.'],
            ['title' => 'Projet lisible', 'text' => 'Dimensions, chants, finition et ambiance restent coherents avant la demande de devis.'],
        ]
    );

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-proof reveal-up" id="preuves">
      <div class="pcstudio-proof__copy">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'eyebrow', 'Pourquoi la ceramique')); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'title', 'Une surface qui transforme la cuisine sans compliquer le projet.')); ?></h2>
        <p><?php echo esc_html(pcp_home_attr($attrs, 'text', 'La ceramique apporte une lecture nette, minerale et durable au plan de travail. Elle permet de relier esthétique, contraintes techniques et usage quotidien dans une seule surface.')); ?></p>

        <div class="pcstudio-proof__stats">
          <?php foreach ($stats as $stat) : ?>
            <article>
              <strong><?php echo esc_html($stat['value']); ?></strong>
              <span><?php echo esc_html($stat['label']); ?></span>
            </article>
          <?php endforeach; ?>
        </div>
      </div>

      <figure class="pcstudio-proof__media">
        <img src="<?php echo esc_url(pcp_home_asset_img(pcp_home_attr($attrs, 'image', 'texture-white-vein.jpg'))); ?>" loading="lazy" width="760" height="620" alt="<?php echo esc_attr(pcp_home_attr($attrs, 'imageAlt', 'Detail de surface ceramique veinee sur plan de travail premium')); ?>">
        <figcaption><?php echo esc_html(pcp_home_attr($attrs, 'caption', 'Detail matiere - veinage - chant - lumiere')); ?></figcaption>
      </figure>

      <div class="pcstudio-proof__cards">
        <?php foreach ($cards as $card) : ?>
          <article>
            <h3><?php echo esc_html($card['title']); ?></h3>
            <p><?php echo esc_html($card['text']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_editorial_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $fallback = pcp_admin_content_pipe_rows(
        $post_id,
        'pcp_cards_json',
        ['title', 'text'],
        [
            ['title' => 'Architecture', 'text' => 'Des lignes nettes qui structurent l ilot, la credence et les zones de preparation.'],
            ['title' => 'Usage quotidien', 'text' => 'Une surface resistante a la chaleur, aux rayures et aux taches.'],
            ['title' => 'Coherence projet', 'text' => 'Couleurs, chants et finitions restent lisibles avant la demande de devis.'],
        ]
    );
    $cards = pcp_home_pipe_rows_from_attr($attrs, 'cards', ['title', 'text'], $fallback);

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-editorial reveal-up" id="introduction">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_intro_eyebrow', 'Surface premium')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_intro_title', 'Un plan de travail pense comme une piece maitresse')); ?></h2>
      </div>
      <div class="pcstudio-editorial__grid">
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'text', 'pcp_intro_text', 'La ceramique relie le style, la resistance et le confort d usage. Elle donne une ligne claire a la cuisine, accompagne la lumiere et prepare naturellement le choix des matieres.')); ?></p>
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
    $post_id = pcp_home_current_post_id();
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
    <section class="pcstudio-section pcstudio-surface reveal-up" id="avantages">
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
    $post_id = pcp_home_current_post_id();
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
    $post_id = pcp_home_current_post_id();
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
        <p><?php echo esc_html(pcp_home_attr($attrs, 'text', 'Trois directions visuelles pour aider le visiteur a se projeter vite, puis a transformer son inspiration en demande de devis.')); ?></p>
      </div>
      <div class="pcstudio-moods__grid">
        <?php foreach ($moods as $index => $mood) : ?>
          <article class="pcstudio-mood-card">
            <img src="<?php echo esc_url(pcp_home_asset_img($mood['image'])); ?>" loading="lazy" width="720" height="520" alt="<?php echo esc_attr($mood['alt']); ?>">
            <div>
              <span class="pcstudio-mood-card__index"><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
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
    $projectChoices = pcp_home_lines_from_attr($attrs, 'projectChoices', ['Cuisine', 'Ilot central', 'Salle de bain', 'Exterieur']);
    $styleChoices = pcp_home_lines_from_attr($attrs, 'styleChoices', ['Clair', 'Chaleureux', 'Marbre', 'Pierre', 'Naturel']);
    $moodChoices = pcp_home_lines_from_attr($attrs, 'moodChoices', ['Premium', 'Minimaliste', 'Familiale', 'Architecturale']);
    $resultAlt = pcp_home_attr($attrs, 'resultAlt', 'Ambiance ceramique recommandee');
    $buttonUrl = pcp_site_url(pcp_home_attr($attrs, 'buttonUrl', '#devis'));

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-config reveal-up" data-configurator>
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'eyebrow', 'Configurateur express')); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'title', 'Trouvez votre ambiance idéale')); ?></h2>
        <p><?php echo esc_html(pcp_home_attr($attrs, 'text', 'Un parcours court pour qualifier le projet sans quitter la landing : usage, style, ambiance, puis formulaire de devis.')); ?></p>
      </div>
      <div class="pcstudio-config__grid">
        <div class="pcstudio-config__steps">
          <fieldset>
            <legend><?php echo esc_html(pcp_home_attr($attrs, 'projectLegend', 'Votre projet')); ?></legend>
            <?php foreach ($projectChoices as $index => $choice) : ?>
              <button type="button" class="<?php echo $index === 0 ? 'is-active' : ''; ?>" data-group="project" data-value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></button>
            <?php endforeach; ?>
          </fieldset>
          <fieldset>
            <legend><?php echo esc_html(pcp_home_attr($attrs, 'styleLegend', 'Votre style')); ?></legend>
            <?php foreach ($styleChoices as $index => $choice) : ?>
              <button type="button" class="<?php echo $index === 0 ? 'is-active' : ''; ?>" data-group="style" data-value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></button>
            <?php endforeach; ?>
          </fieldset>
          <fieldset>
            <legend><?php echo esc_html(pcp_home_attr($attrs, 'moodLegend', 'Votre ambiance')); ?></legend>
            <?php foreach ($moodChoices as $index => $choice) : ?>
              <button type="button" class="<?php echo $index === 0 ? 'is-active' : ''; ?>" data-group="mood" data-value="<?php echo esc_attr($choice); ?>"><?php echo esc_html($choice); ?></button>
            <?php endforeach; ?>
          </fieldset>
        </div>
        <aside class="pcstudio-config__result">
          <img src="<?php echo esc_url(pcp_home_asset_img('kitchen-warm-ceramique.jpg')); ?>" loading="lazy" width="520" height="340" alt="<?php echo esc_attr($resultAlt); ?>" data-config-image>
          <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'resultLabel', 'Resultat recommande')); ?></p>
          <h3 data-config-title><?php echo esc_html(pcp_home_attr($attrs, 'resultTitle', 'Warm Mineral')); ?></h3>
          <p data-config-text><?php echo esc_html(pcp_home_attr($attrs, 'resultText', 'Une base lumineuse, minerale et chaleureuse pour un projet Cuisine au style Clair.')); ?></p>
          <div class="pcstudio-swatches" data-config-swatches>
            <span style="--swatch:#FBF8F2"></span>
            <span style="--swatch:#D8C7AD"></span>
            <span style="--swatch:#6B4E35"></span>
          </div>
          <a class="button" href="<?php echo esc_url($buttonUrl); ?>"><?php echo esc_html(pcp_home_attr($attrs, 'buttonText', 'Preparer mon devis')); ?></a>
        </aside>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_applications_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
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
    $post_id = pcp_home_current_post_id();
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
    <section class="pcstudio-section pcstudio-compare reveal-up" id="comparatif">
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
    $post_id = pcp_home_current_post_id();
    $raw_items = (string) ($attrs['items'] ?? '');
    $fallback_lines = pcp_admin_content_lines($post_id, $meta_key, $fallback);
    $items = str_contains($raw_items, '|')
        ? pcp_home_pipe_rows_from_attr($attrs, 'items', ['title', 'text'], array_map(static fn(string $item): array => ['title' => $item, 'text' => ''], $fallback_lines))
        : array_map(static fn(string $item): array => ['title' => $item, 'text' => ''], pcp_home_lines_from_attr($attrs, 'items', $fallback_lines));
    $section_ids = [
        'pcstudio-process' => 'processus',
        'pcstudio-details' => 'details',
    ];
    $section_id = $section_ids[$class] ?? '';

    ob_start();
    ?>
    <section class="pcstudio-section <?php echo esc_attr($class); ?> reveal-up"<?php echo $section_id ? ' id="' . esc_attr($section_id) . '"' : ''; ?>>
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
            <h3><?php echo esc_html($item['title']); ?></h3>
            <?php if (!empty($item['text'])) : ?>
              <p><?php echo esc_html($item['text']); ?></p>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_process_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $attrs['eyebrow'] = pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_process_eyebrow', 'Processus');
    $attrs['title'] = pcp_home_meta($post_id, $attrs, 'title', 'pcp_process_title', 'De l idee a la surface finale');

    return pcp_render_home_list_grid_block($attrs, 'pcstudio-process', 'pcp_process_steps', ['Analyse de votre espace', 'Selection de la matiere', 'Prise de mesures']);
}

function pcp_render_home_details_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $attrs['eyebrow'] = pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_details_eyebrow', 'Details premium');
    $attrs['title'] = pcp_home_meta($post_id, $attrs, 'title', 'pcp_details_title', 'Les details invisibles font le luxe visible');

    return pcp_render_home_list_grid_block($attrs, 'pcstudio-details', 'pcp_premium_details', ['Epaisseur', 'Type de chant', 'Finition mate ou brillante']);
}

function pcp_render_home_testimonials_block(array $attrs): string
{
    $fallback = [
        ['name' => 'Nadia M.', 'project' => 'Cuisine avec ilot central', 'rating' => 5, 'text' => 'Le rendu est elegant, lumineux et vraiment haut de gamme. Le plan a change toute l ambiance de la cuisine.'],
        ['name' => 'Thomas R.', 'project' => 'Renovation cuisine', 'rating' => 5, 'text' => 'Le choix des matieres rend la piece beaucoup plus moderne et chaleureuse.'],
        ['name' => 'Sarah L.', 'project' => 'Salle de bain', 'rating' => 5, 'text' => 'Le resultat est propre, contemporain et facile a entretenir. C est exactement l ambiance que je voulais.'],
    ];
    $proofs = pcp_home_lines_from_attr($attrs, 'proofs', ['4.9/5 satisfaction', 'Conseil matiere inclus', 'Projet cadre avant devis']);
    $query = new WP_Query(['post_type' => 'pcp_avis', 'posts_per_page' => 3, 'no_found_rows' => true]);

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-reviews reveal-up" id="avis">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'eyebrow', '4.9/5')); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'title', 'Ils ont transforme leur espace')); ?></h2>
        <p><?php echo esc_html(pcp_home_attr($attrs, 'text', 'Des retours courts, concrets et rassurants pour aider le visiteur a passer de l inspiration a la demande de devis.')); ?></p>
      </div>

      <div class="pcstudio-reviews__proofs" aria-label="<?php esc_attr_e('Reperes de confiance', 'plan-ceramique-premium'); ?>">
        <?php foreach ($proofs as $proof) : ?>
          <span><?php echo esc_html($proof); ?></span>
        <?php endforeach; ?>
      </div>

      <div class="pcstudio-reviews__grid">
        <?php if ($query->have_posts()) : ?>
          <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php
            $postId = get_the_ID();
            $name = pcp_post_meta($postId, 'pcp_client_name', get_the_title());
            $rating = (int) pcp_post_meta($postId, 'pcp_rating', '5');
            ?>
            <article>
              <span class="pcstudio-review-initials"><?php echo esc_html(function_exists('mb_substr') ? mb_substr($name, 0, 1) : substr($name, 0, 1)); ?></span>
              <span class="pcstudio-stars"><?php echo esc_html(str_repeat('★', max(1, min(5, $rating)))); ?></span>
              <p>“<?php echo esc_html(wp_strip_all_tags(get_the_content())); ?>”</p>
              <strong><?php echo esc_html($name); ?></strong>
              <small><?php echo esc_html(pcp_post_meta($postId, 'pcp_project_type', get_the_excerpt())); ?></small>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
          <?php foreach ($fallback as $item) : ?>
            <article>
              <span class="pcstudio-review-initials"><?php echo esc_html(function_exists('mb_substr') ? mb_substr($item['name'], 0, 1) : substr($item['name'], 0, 1)); ?></span>
              <span class="pcstudio-stars"><?php echo esc_html(str_repeat('★', $item['rating'])); ?></span>
              <p>“<?php echo esc_html($item['text']); ?>”</p>
              <strong><?php echo esc_html($item['name']); ?></strong>
              <small><?php echo esc_html($item['project']); ?></small>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <div class="pcstudio-reviews__cta">
        <a class="button button--ghost" href="<?php echo esc_url(pcp_site_url(pcp_home_attr($attrs, 'ctaUrl', '#devis'))); ?>"><?php echo esc_html(pcp_home_attr($attrs, 'ctaText', 'Demander un avis sur mon projet')); ?></a>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_blog_block(array $attrs): string
{
    $posts_page_id = (int) get_option('page_for_posts');
    $blog_url = $posts_page_id ? (get_permalink($posts_page_id) ?: home_url('/blog/')) : home_url('/blog/');
    $cardLinkText = pcp_home_attr($attrs, 'cardLinkText', pcp_get_setting('blog_card_read_more_text') ?: 'Lire l’article');
    $moreText = pcp_home_attr($attrs, 'moreText', 'Voir tous les articles');
    $fallback_posts = [
        ['cat' => 'Conseils', 'title' => 'Comment choisir la bonne couleur pour un plan ceramique ?', 'image' => 'blog-material-choice.jpg', 'text' => 'Couleur claire, effet marbre, pierre naturelle ou ambiance chaleureuse.'],
        ['cat' => 'Inspiration', 'title' => 'Les tendances cuisine premium en 2026', 'image' => 'blog-kitchen-trends.jpg', 'text' => 'Les cuisines modernes misent sur la lumiere et les matieres naturelles.'],
        ['cat' => 'Guide', 'title' => 'Entretenir un plan de travail ceramique au quotidien', 'image' => 'blog-ceramique-maintenance.jpg', 'text' => 'Quelques gestes simples permettent de garder une surface propre.'],
    ];

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-blog reveal-up" id="blog">
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'eyebrow', 'Conseils & inspirations')); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'title', 'Guides, tendances et idées pour imaginer votre futur plan de travail.')); ?></h2>
      </div>
      <div class="pcstudio-blog__grid">
        <?php
        $landing_posts = new WP_Query(['post_type' => 'post', 'posts_per_page' => 3, 'ignore_sticky_posts' => true, 'no_found_rows' => true]);
        if ($landing_posts->have_posts()) :
            while ($landing_posts->have_posts()) :
                $landing_posts->the_post();
                $post_id = get_the_ID();
                $categories = get_the_category($post_id);
                $category = $categories ? $categories[0]->name : (pcp_get_setting('blog_card_default_label') ?: 'Conseils');
                ?>
                <article class="pcstudio-post-card">
                  <img src="<?php echo esc_url(pcp_post_image_url($post_id, 'blog-material-choice.jpg')); ?>" loading="lazy" width="540" height="360" alt="<?php echo esc_attr(get_the_title($post_id)); ?>">
                  <p class="pcstudio-label"><?php echo esc_html($category); ?></p>
                  <h3><?php echo esc_html(get_the_title($post_id)); ?></h3>
                  <p><?php echo esc_html(pcp_excerpt_text(get_post($post_id), 18)); ?></p>
                  <a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html($cardLinkText); ?></a>
                </article>
                <?php
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
                  <a href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html($cardLinkText); ?></a>
                </article>
                <?php
            endforeach;
        endif;
        ?>
      </div>
      <a class="button button--ghost pcstudio-blog__more" href="<?php echo esc_url($blog_url); ?>"><?php echo esc_html($moreText); ?></a>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_blog_hero_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $hero_image = pcp_home_meta($post_id, $attrs, 'image', 'pcp_hero_image', 'blog-kitchen-trends.jpg');

    ob_start();
    ?>
    <section class="pcstudio-blog-hero">
      <div class="pcstudio-blog-hero__copy">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_hero_eyebrow', 'Blog & conseils')); ?></p>
        <h1><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_hero_title', 'Conseils pour concevoir une cuisine durable, belle et vraiment fonctionnelle.')); ?></h1>
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'lead', 'pcp_hero_lead', 'Un espace éditorial pour préparer votre projet : tendances, matériaux, implantation, entretien et pose.')); ?></p>
      </div>
      <figure class="pcstudio-blog-hero__media">
        <img src="<?php echo esc_url(pcp_asset_img($hero_image)); ?>" width="1320" height="720" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_hero_image_alt', 'Cuisine premium lumineuse avec plan de travail minéral')); ?>">
      </figure>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_blog_archive_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $pagination_prev_text = pcp_home_attr($attrs, 'prevText', pcp_get_setting('blog_pagination_prev_text') ?: 'Articles récents');
    $pagination_next_text = pcp_home_attr($attrs, 'nextText', pcp_get_setting('blog_pagination_next_text') ?: 'Articles suivants');
    $empty_text = pcp_home_attr($attrs, 'emptyText', pcp_get_setting('blog_empty_text') ?: 'Aucun article publié pour le moment.');
    $posts_per_page = (int) pcp_home_attr($attrs, 'postsPerPage', (string) get_option('posts_per_page'));
    $posts_per_page = $posts_per_page > 0 ? min($posts_per_page, 30) : (int) get_option('posts_per_page');
    $paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
    $archive_query = new WP_Query(
        [
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'ignore_sticky_posts' => true,
        ]
    );

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-blog pcstudio-blog--archive">
      <div class="pcstudio-section__heading pcstudio-section__heading--center">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_intro_eyebrow', 'Tous les articles')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_intro_title', '30 sujets SEO pour avancer sans se tromper.')); ?></h2>
      </div>
      <?php if ($archive_query->have_posts()) : ?>
        <div class="pcstudio-blog__grid">
          <?php
          while ($archive_query->have_posts()) :
              $archive_query->the_post();
              get_template_part('template-parts/content', 'post-card');
          endwhile;
          wp_reset_postdata();
          ?>
        </div>
        <?php
        echo wp_kses_post(
            paginate_links(
                [
                    'total' => $archive_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => esc_html($pagination_prev_text),
                    'next_text' => esc_html($pagination_next_text),
                ]
            ) ?: ''
        );
        ?>
      <?php else : ?>
        <p><?php echo esc_html($empty_text); ?></p>
      <?php endif; ?>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_quote_block(array $attrs): string
{
    $projectOptions = pcp_setting_lines('quote_form_project_type_options');
    $materialOptions = pcp_setting_lines('quote_form_material_options');
    $budgetOptions = pcp_setting_lines('quote_form_budget_options');
    $defaultMaterial = $materialOptions[0] ?? '';
    $defaultBudget = end($budgetOptions) ?: '';
    $infoTitle = pcp_get_setting('quote_wizard_info_title') ?: 'Informations';
    $previousText = pcp_get_setting('quote_wizard_prev_text') ?: 'Precedent';
    $nextText = pcp_get_setting('quote_wizard_next_text') ?: 'Continuer';

    ob_start();
    ?>
    <section class="pcstudio-section pcstudio-quote reveal-up" id="devis" data-quote-wizard>
      <div class="pcstudio-section__heading">
        <p class="pcstudio-label"><?php echo esc_html(pcp_home_attr($attrs, 'eyebrow', 'Formulaire')); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'title', 'Demander un devis')); ?></h2>
        <p><?php echo esc_html(pcp_home_attr($attrs, 'text', 'Quelques choix suffisent pour cadrer la demande et recevoir une premiere orientation claire.')); ?></p>
      </div>
      <form class="pcstudio-wizard" data-pcp-form novalidate>
        <input type="hidden" name="pcp_form_type" value="quote">
        <input type="hidden" name="first_name" value="">
        <input type="hidden" name="desired_material" data-wizard-material value="<?php echo esc_attr($defaultMaterial); ?>">
        <input type="hidden" name="project_dimensions" data-wizard-dimensions value="">
        <input type="hidden" name="message" data-wizard-message value="">
        <input type="text" name="website" value="" autocomplete="off" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px;">
        <div class="pcstudio-wizard__progress"><span data-wizard-progress></span></div>
        <div class="pcstudio-wizard__step is-active" data-step="1">
          <h3><?php echo esc_html(pcp_get_setting('quote_form_project_type_label') ?: 'Type de projet'); ?></h3>
          <div class="pcstudio-choice-grid">
            <?php foreach ($projectOptions as $index => $choice) : ?>
              <label><input type="radio" name="project_type" value="<?php echo esc_attr($choice); ?>" <?php checked($index, 0); ?>><?php echo esc_html($choice); ?></label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="pcstudio-wizard__step" data-step="2">
          <h3><?php echo esc_html(pcp_get_setting('quote_form_material_label') ?: 'Materiau souhaite'); ?></h3>
          <div class="pcstudio-choice-grid">
            <?php foreach ($materialOptions as $index => $choice) : ?>
              <label><input type="radio" name="style" value="<?php echo esc_attr($choice); ?>" <?php checked($index, 0); ?>><?php echo esc_html($choice); ?></label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="pcstudio-wizard__step" data-step="3">
          <h3><?php echo esc_html(pcp_get_setting('quote_form_budget_label') ?: 'Budget approximatif'); ?></h3>
          <div class="pcstudio-choice-grid">
            <?php foreach ($budgetOptions as $choice) : ?>
              <label><input type="radio" name="budget" value="<?php echo esc_attr($choice); ?>" <?php checked($choice, $defaultBudget); ?>><?php echo esc_html($choice); ?></label>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="pcstudio-wizard__step" data-step="4">
          <h3><?php echo esc_html($infoTitle); ?></h3>
          <div class="pcstudio-form-grid">
            <label><?php echo esc_html(pcp_get_setting('quote_form_last_name_label') ?: 'Nom'); ?><input type="text" name="last_name" autocomplete="name" required></label>
            <label><?php echo esc_html(pcp_get_setting('quote_form_email_label') ?: 'Email'); ?><input type="email" name="email" autocomplete="email" required></label>
            <label><?php echo esc_html(pcp_get_setting('quote_form_phone_label') ?: 'Telephone'); ?><input type="tel" name="phone" autocomplete="tel"></label>
            <label><?php echo esc_html(pcp_get_setting('quote_form_city_label') ?: 'Ville'); ?><input type="text" name="city" autocomplete="address-level2"></label>
            <label><?php echo esc_html(pcp_get_setting('quote_form_dimensions_label') ?: 'Dimensions approximatives'); ?><input type="text" name="dimensions_display" data-wizard-dimensions-display placeholder="<?php echo esc_attr(pcp_get_setting('quote_form_dimensions_placeholder') ?: 'Exemple : 320 x 65 cm + ilot'); ?>"></label>
            <label class="is-wide"><?php echo esc_html(pcp_get_setting('quote_form_message_label') ?: 'Message'); ?><textarea name="message_display" data-wizard-message-display placeholder="<?php echo esc_attr(pcp_get_setting('quote_form_message_placeholder') ?: 'Decrivez votre espace, vos envies et vos contraintes.'); ?>"></textarea></label>
          </div>
          <?php echo function_exists('pcp_render_captcha_field') ? pcp_render_captcha_field() : ''; ?>
          <div class="pcstudio-wizard__summary" data-wizard-summary></div>
        </div>
        <div class="pcstudio-wizard__actions">
          <button type="button" class="button button--ghost" data-wizard-prev><?php echo esc_html($previousText); ?></button>
          <button type="button" class="button" data-wizard-next><?php echo esc_html($nextText); ?></button>
        </div>
        <p class="pcstudio-wizard__status" data-wizard-status data-pcp-form-status aria-live="polite"></p>
      </form>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_home_final_cta_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();

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

function pcp_render_services_hero_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();

    ob_start();
    ?>
    <section class="pcp-services-hero">
      <div class="pcp-services-hero__copy">
        <p class="pcp-services-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_hero_eyebrow', 'Nos services')); ?></p>
        <h1><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_hero_title', 'Un service clair pour votre plan de travail en céramique.')); ?></h1>
        <p class="pcp-services-hero__lead"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'lead', 'pcp_hero_lead', 'De la première idée jusqu’à la pose, chaque étape est cadrée pour éviter les imprévus.')); ?></p>
        <div class="pcp-services-actions">
          <a class="button" href="<?php echo esc_url(pcp_site_url(pcp_home_meta($post_id, $attrs, 'primaryUrl', 'pcp_primary_cta_url', '/demander-un-devis/'))); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'primaryText', 'pcp_primary_cta_text', 'Demander un devis')); ?></a>
          <a class="button button--ghost" href="<?php echo esc_url(pcp_site_url(pcp_home_meta($post_id, $attrs, 'secondaryUrl', 'pcp_secondary_cta_url', '/materiaux/'))); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'secondaryText', 'pcp_secondary_cta_text', 'Voir les matériaux')); ?></a>
        </div>
      </div>
      <figure class="pcp-services-hero__media">
        <img src="<?php echo esc_url(pcp_services_asset_image(pcp_home_meta($post_id, $attrs, 'image', 'pcp_hero_image', 'hero-services.jpg'))); ?>" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_hero_image_alt', 'Conseil et préparation d’un projet de plan de travail en céramique')); ?>">
      </figure>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_services_intro_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();

    ob_start();
    ?>
    <section class="pcp-services-intro">
      <div>
        <p class="pcp-services-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_intro_eyebrow', 'Méthode')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_intro_title', 'Chaque service répond à une étape réelle du projet.')); ?></h2>
      </div>
      <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'text', 'pcp_intro_text', 'La page Services sert à comprendre comment le projet avance.')); ?></p>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_services_grid_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $fallback = pcp_admin_content_pipe_rows(
        $post_id,
        'pcp_cards_json',
        ['icon', 'eyebrow', 'title', 'text'],
        [
            ['icon' => 'C', 'eyebrow' => '01 Conseil', 'title' => 'Cadrer le projet', 'text' => 'Nous clarifions l’usage, le style et les contraintes techniques.'],
            ['icon' => 'M', 'eyebrow' => '02 Mesure', 'title' => 'Préparer les dimensions', 'text' => 'Les longueurs, profondeurs, angles et accès sont vérifiés.'],
        ]
    );
    $services = pcp_home_pipe_rows_from_attr($attrs, 'cards', ['icon', 'eyebrow', 'title', 'text'], $fallback);

    ob_start();
    ?>
    <section class="pcp-services-grid" aria-label="<?php esc_attr_e('Étapes de service', 'plan-ceramique-premium'); ?>">
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
    <?php

    return (string) ob_get_clean();
}

function pcp_render_services_feature_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $items = pcp_home_lines_from_attr(
        $attrs,
        'items',
        pcp_admin_content_lines(
            $post_id,
            'pcp_feature_list',
            ['Projet lisible dès le premier échange', 'Découpes évier, plaque et prises anticipées']
        )
    );

    ob_start();
    ?>
    <section class="pcp-services-feature">
      <figure class="pcp-services-feature__media">
        <img src="<?php echo esc_url(pcp_services_asset_image(pcp_home_meta($post_id, $attrs, 'image', 'pcp_feature_image', 'hero-materials.jpg'))); ?>" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_feature_image_alt', 'Surface céramique minérale pour cuisine sur mesure')); ?>">
      </figure>
      <div class="pcp-services-feature__content">
        <p class="pcp-services-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_feature_eyebrow', 'Précision')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_feature_title', 'Un service utile parce qu’il relie esthétique et contraintes techniques.')); ?></h2>
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'text', 'pcp_feature_text', 'Un plan de travail réussi dépend aussi des dimensions, découpes, chants, accès et pose.')); ?></p>
        <ul class="pcp-services-list">
          <?php foreach ($items as $item) : ?>
            <li><?php echo esc_html($item); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_services_cta_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();

    ob_start();
    ?>
    <section class="pcp-services-cta">
      <div>
        <p class="pcp-services-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_final_cta_eyebrow', 'Suite logique')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_final_cta_title', 'Préparez quelques informations, nous cadrons le reste.')); ?></h2>
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'text', 'pcp_final_cta_text', 'Dimensions approximatives, photos, ville, finition souhaitée ou simple idée de départ.')); ?></p>
      </div>
      <a class="button" href="<?php echo esc_url(pcp_site_url(pcp_home_meta($post_id, $attrs, 'buttonUrl', 'pcp_final_cta_button_url', '/demander-un-devis/'))); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'buttonText', 'pcp_final_cta_button_text', 'Accéder au formulaire devis')); ?></a>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_detail_hero_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();

    ob_start();
    ?>
    <section class="pcp-detail-hero">
      <div class="pcp-detail-hero__copy">
        <p class="pcp-detail-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_hero_eyebrow', '')); ?></p>
        <h1><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_hero_title', get_the_title($post_id))); ?></h1>
        <p class="pcp-detail-hero__lead"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'lead', 'pcp_hero_lead', '')); ?></p>
        <div class="pcp-detail-actions">
          <a class="button" href="<?php echo esc_url(pcp_site_url(pcp_home_meta($post_id, $attrs, 'primaryUrl', 'pcp_primary_cta_url', '/demander-un-devis/'))); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'primaryText', 'pcp_primary_cta_text', 'Demander un devis')); ?></a>
          <a class="button button--ghost" href="<?php echo esc_url(pcp_site_url(pcp_home_meta($post_id, $attrs, 'secondaryUrl', 'pcp_secondary_cta_url', '/collections/'))); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'secondaryText', 'pcp_secondary_cta_text', 'Voir les collections')); ?></a>
        </div>
      </div>
      <figure class="pcp-detail-hero__media">
        <img src="<?php echo esc_url(pcp_services_asset_image(pcp_home_meta($post_id, $attrs, 'image', 'pcp_hero_image', 'hero-materials.jpg'))); ?>" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_hero_image_alt', '')); ?>">
      </figure>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_detail_intro_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();

    ob_start();
    ?>
    <section class="pcp-detail-intro">
      <div>
        <p class="pcp-detail-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_intro_eyebrow', 'Repères')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_intro_title', '')); ?></h2>
      </div>
      <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'text', 'pcp_intro_text', '')); ?></p>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_detail_grid_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $cards = pcp_home_pipe_rows_from_attr(
        $attrs,
        'cards',
        ['icon', 'eyebrow', 'title', 'text'],
        pcp_admin_content_pipe_rows($post_id, 'pcp_cards_json', ['icon', 'eyebrow', 'title', 'text'], [])
    );

    ob_start();
    ?>
    <section class="pcp-detail-grid" aria-label="<?php echo esc_attr(pcp_home_attr($attrs, 'ariaLabel', 'Cartes de contenu')); ?>">
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
    <?php

    return (string) ob_get_clean();
}

function pcp_render_detail_feature_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();

    ob_start();
    ?>
    <section class="pcp-detail-feature">
      <figure class="pcp-detail-feature__media">
        <img src="<?php echo esc_url(pcp_services_asset_image(pcp_home_meta($post_id, $attrs, 'image', 'pcp_feature_image', 'hero-collections.jpg'))); ?>" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_feature_image_alt', '')); ?>">
      </figure>
      <div class="pcp-detail-feature__content">
        <p class="pcp-detail-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_feature_eyebrow', 'Choix')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_feature_title', '')); ?></h2>
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'text', 'pcp_feature_text', '')); ?></p>
        <a class="button button--ghost" href="<?php echo esc_url(pcp_site_url(pcp_home_meta($post_id, $attrs, 'ctaUrl', 'pcp_feature_cta_url', '/demander-un-devis/'))); ?>"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'ctaText', 'pcp_feature_cta_text', 'Demander un devis')); ?></a>
      </div>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_contact_hero_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();

    ob_start();
    ?>
    <section class="pcp-contact-hero">
      <div class="pcp-contact-hero__copy">
        <p class="pcp-contact-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_hero_eyebrow', 'Contact')); ?></p>
        <h1><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_hero_title', get_the_title($post_id))); ?></h1>
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'lead', 'pcp_hero_lead', '')); ?></p>
      </div>
      <figure class="pcp-contact-hero__media">
        <img src="<?php echo esc_url(pcp_services_asset_image(pcp_home_meta($post_id, $attrs, 'image', 'pcp_hero_image', 'hero-contact.jpg'))); ?>" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_hero_image_alt', '')); ?>">
      </figure>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_contact_layout_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $cards = pcp_home_pipe_rows_from_attr(
        $attrs,
        'cards',
        ['icon', 'title', 'text'],
        pcp_admin_content_pipe_rows($post_id, 'pcp_cards_json', ['icon', 'title', 'text'], [])
    );

    ob_start();
    ?>
    <section class="pcp-contact-layout">
      <aside class="pcp-contact-panel" aria-label="<?php esc_attr_e('Informations utiles', 'plan-ceramique-premium'); ?>">
        <p class="pcp-contact-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_intro_eyebrow', 'Repères')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_intro_title', 'Quand utiliser le contact ?')); ?></h2>
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
          <p><strong><?php echo esc_html(pcp_home_attr($attrs, 'emailLabel', pcp_admin_content_value($post_id, 'pcp_contact_email_label', 'Email'))); ?></strong><br><?php echo esc_html(pcp_get_setting('visible_email')); ?></p>
          <p><strong><?php echo esc_html(pcp_home_attr($attrs, 'zoneLabel', pcp_admin_content_value($post_id, 'pcp_contact_zone_label', 'Zone'))); ?></strong><br><?php echo esc_html(pcp_get_setting('service_area')); ?></p>
        </div>
      </aside>

      <section class="pcp-contact-form-card" aria-label="<?php esc_attr_e('Formulaire de contact', 'plan-ceramique-premium'); ?>">
        <p class="pcp-contact-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'formEyebrow', 'pcp_feature_eyebrow', 'Formulaire')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'formTitle', 'pcp_feature_title', 'Envoyer un message')); ?></h2>
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'formText', 'pcp_feature_text', 'Décrivez votre besoin en quelques lignes.')); ?></p>
        <?php echo do_shortcode('[pcp_contact_form type="contact"]'); ?>
      </section>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_quote_hero_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();

    ob_start();
    ?>
    <section class="pcp-quote-hero">
      <div class="pcp-quote-hero__copy">
        <p class="pcp-quote-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_hero_eyebrow', 'Demande de devis')); ?></p>
        <h1><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_hero_title', get_the_title($post_id))); ?></h1>
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'lead', 'pcp_hero_lead', '')); ?></p>
      </div>
      <figure class="pcp-quote-hero__media">
        <img src="<?php echo esc_url(pcp_services_asset_image(pcp_home_meta($post_id, $attrs, 'image', 'pcp_hero_image', 'hero-quote.jpg'))); ?>" alt="<?php echo esc_attr(pcp_home_meta($post_id, $attrs, 'imageAlt', 'pcp_hero_image_alt', '')); ?>">
      </figure>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_render_quote_prep_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $steps = pcp_home_pipe_rows_from_attr(
        $attrs,
        'items',
        ['icon', 'title', 'text'],
        pcp_admin_content_pipe_rows($post_id, 'pcp_cards_json', ['icon', 'title', 'text'], [])
    );

    ob_start();
    ?>
    <section class="pcp-quote-prep">
      <div>
        <p class="pcp-quote-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_intro_eyebrow', "Avant d'envoyer")); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_intro_title', 'Quelques repères suffisent pour commencer.')); ?></h2>
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
    <?php

    return (string) ob_get_clean();
}

function pcp_render_quote_layout_block(array $attrs): string
{
    $post_id = pcp_home_current_post_id();
    $items = pcp_home_lines_from_attr($attrs, 'items', pcp_admin_content_lines($post_id, 'pcp_feature_list', []));

    ob_start();
    ?>
    <section class="pcp-quote-layout">
      <aside class="pcp-quote-note">
        <p class="pcp-quote-eyebrow"><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'eyebrow', 'pcp_feature_eyebrow', 'Ce qui aide')); ?></p>
        <h2><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'title', 'pcp_feature_title', 'Plus le projet est précis, plus la réponse peut être juste.')); ?></h2>
        <p><?php echo esc_html(pcp_home_meta($post_id, $attrs, 'text', 'pcp_feature_text', '')); ?></p>
        <ul>
          <?php foreach ($items as $item) : ?>
            <li><?php echo esc_html($item); ?></li>
          <?php endforeach; ?>
        </ul>
      </aside>

      <section class="pcp-quote-form-card" aria-label="<?php esc_attr_e('Formulaire de demande de devis', 'plan-ceramique-premium'); ?>">
        <p class="pcp-quote-eyebrow"><?php echo esc_html(pcp_home_attr($attrs, 'formEyebrow', pcp_admin_content_value($post_id, 'pcp_form_eyebrow', 'Formulaire'))); ?></p>
        <h2><?php echo esc_html(pcp_home_attr($attrs, 'formTitle', pcp_admin_content_value($post_id, 'pcp_form_title', 'Demander mon étude'))); ?></h2>
        <?php echo do_shortcode('[pcp_contact_form type="quote"]'); ?>
      </section>
    </section>
    <?php

    return (string) ob_get_clean();
}

function pcp_home_render_block_safely(callable $callback, array $attrs, ?WP_Block $block = null): string
{
    global $pcp_home_render_post_id;

    if (!defined('REST_REQUEST') || !REST_REQUEST) {
        return (string) $callback($attrs);
    }

    $buffer_level = ob_get_level();
    $previous_render_post_id = $pcp_home_render_post_id ?? null;
    $pcp_home_render_post_id = isset($block->context['postId']) ? (int) $block->context['postId'] : pcp_home_current_post_id();

    if (current_user_can('edit_posts')) {
        $pcp_home_render_post_id = $previous_render_post_id;

        return '<div class="pcp-home-block-rest-preview">' . esc_html__('Bloc dynamique Plan Ceramique.', 'plan-ceramique-premium') . '</div>';
    }

    ob_start();
    set_error_handler(
        static function (int $severity, string $message, string $file, int $line): bool {
            if (!(error_reporting() & $severity)) {
                return false;
            }

            throw new ErrorException($message, 0, $severity, $file, $line);
        }
    );

    try {
        $rendered = $callback($attrs);
        $unexpected_output = (string) ob_get_clean();
        restore_error_handler();
        $pcp_home_render_post_id = $previous_render_post_id;

        if (is_string($rendered)) {
            return $rendered;
        }

        if ($rendered === null) {
            return $unexpected_output;
        }

        return (string) $rendered;
    } catch (Throwable $e) {
        restore_error_handler();
        $pcp_home_render_post_id = $previous_render_post_id;

        while (ob_get_level() > $buffer_level) {
            ob_end_clean();
        }

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf('PCP home block render error: %s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine()));
        }

        if (current_user_can('edit_posts')) {
            return '<div class="pcp-home-block-error">' . esc_html__('Erreur de rendu du bloc. Consultez le journal WordPress.', 'plan-ceramique-premium') . '</div>';
        }

        return '';
    }
}

function pcp_home_register_blocks(): void
{
    $home_blocks_script = get_template_directory() . '/assets/js/home-blocks.js';

    wp_register_script(
        'pcp-home-blocks',
        get_template_directory_uri() . '/assets/js/home-blocks.js',
        ['wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-i18n'],
        file_exists($home_blocks_script) ? (string) filemtime($home_blocks_script) : pcp_theme_version(),
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
        'home-proof' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'stats' => ['type' => 'string', 'default' => ''],
                    'cards' => ['type' => 'string', 'default' => ''],
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                    'caption' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_home_proof_block',
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
            'attributes' => array_merge(
                $text_attrs,
                [
                    'projectLegend' => ['type' => 'string', 'default' => ''],
                    'projectChoices' => ['type' => 'string', 'default' => ''],
                    'styleLegend' => ['type' => 'string', 'default' => ''],
                    'styleChoices' => ['type' => 'string', 'default' => ''],
                    'moodLegend' => ['type' => 'string', 'default' => ''],
                    'moodChoices' => ['type' => 'string', 'default' => ''],
                    'resultLabel' => ['type' => 'string', 'default' => ''],
                    'resultTitle' => ['type' => 'string', 'default' => ''],
                    'resultText' => ['type' => 'string', 'default' => ''],
                    'resultAlt' => ['type' => 'string', 'default' => ''],
                    'buttonText' => ['type' => 'string', 'default' => ''],
                    'buttonUrl' => ['type' => 'string', 'default' => ''],
                ]
            ),
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
            'attributes' => array_merge(
                $text_attrs,
                [
                    'cardLinkText' => ['type' => 'string', 'default' => ''],
                    'moreText' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_home_blog_block',
        ],
        'home-testimonials' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'proofs' => ['type' => 'string', 'default' => ''],
                    'ctaText' => ['type' => 'string', 'default' => ''],
                    'ctaUrl' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_home_testimonials_block',
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
        'blog-hero' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'lead' => ['type' => 'string', 'default' => ''],
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_blog_hero_block',
        ],
        'blog-archive' => [
            'attributes' => [
                'eyebrow' => ['type' => 'string', 'default' => ''],
                'title' => ['type' => 'string', 'default' => ''],
                'postsPerPage' => ['type' => 'string', 'default' => ''],
                'prevText' => ['type' => 'string', 'default' => ''],
                'nextText' => ['type' => 'string', 'default' => ''],
                'emptyText' => ['type' => 'string', 'default' => ''],
            ],
            'render_callback' => 'pcp_render_blog_archive_block',
        ],
        'services-hero' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'lead' => ['type' => 'string', 'default' => ''],
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                    'primaryText' => ['type' => 'string', 'default' => ''],
                    'primaryUrl' => ['type' => 'string', 'default' => ''],
                    'secondaryText' => ['type' => 'string', 'default' => ''],
                    'secondaryUrl' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_services_hero_block',
        ],
        'services-intro' => [
            'attributes' => $text_attrs,
            'render_callback' => 'pcp_render_services_intro_block',
        ],
        'services-grid' => [
            'attributes' => ['cards' => ['type' => 'string', 'default' => '']],
            'render_callback' => 'pcp_render_services_grid_block',
        ],
        'services-feature' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                    'items' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_services_feature_block',
        ],
        'services-cta' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'buttonText' => ['type' => 'string', 'default' => ''],
                    'buttonUrl' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_services_cta_block',
        ],
        'detail-hero' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'lead' => ['type' => 'string', 'default' => ''],
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                    'primaryText' => ['type' => 'string', 'default' => ''],
                    'primaryUrl' => ['type' => 'string', 'default' => ''],
                    'secondaryText' => ['type' => 'string', 'default' => ''],
                    'secondaryUrl' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_detail_hero_block',
        ],
        'detail-intro' => [
            'attributes' => $text_attrs,
            'render_callback' => 'pcp_render_detail_intro_block',
        ],
        'detail-grid' => [
            'attributes' => [
                'cards' => ['type' => 'string', 'default' => ''],
                'ariaLabel' => ['type' => 'string', 'default' => ''],
            ],
            'render_callback' => 'pcp_render_detail_grid_block',
        ],
        'detail-feature' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                    'ctaText' => ['type' => 'string', 'default' => ''],
                    'ctaUrl' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_detail_feature_block',
        ],
        'contact-hero' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'lead' => ['type' => 'string', 'default' => ''],
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_contact_hero_block',
        ],
        'contact-layout' => [
            'attributes' => [
                'eyebrow' => ['type' => 'string', 'default' => ''],
                'title' => ['type' => 'string', 'default' => ''],
                'cards' => ['type' => 'string', 'default' => ''],
                'emailLabel' => ['type' => 'string', 'default' => ''],
                'zoneLabel' => ['type' => 'string', 'default' => ''],
                'formEyebrow' => ['type' => 'string', 'default' => ''],
                'formTitle' => ['type' => 'string', 'default' => ''],
                'formText' => ['type' => 'string', 'default' => ''],
            ],
            'render_callback' => 'pcp_render_contact_layout_block',
        ],
        'quote-hero' => [
            'attributes' => array_merge(
                $text_attrs,
                [
                    'lead' => ['type' => 'string', 'default' => ''],
                    'image' => ['type' => 'string', 'default' => ''],
                    'imageAlt' => ['type' => 'string', 'default' => ''],
                ]
            ),
            'render_callback' => 'pcp_render_quote_hero_block',
        ],
        'quote-prep' => [
            'attributes' => [
                'eyebrow' => ['type' => 'string', 'default' => ''],
                'title' => ['type' => 'string', 'default' => ''],
                'items' => ['type' => 'string', 'default' => ''],
            ],
            'render_callback' => 'pcp_render_quote_prep_block',
        ],
        'quote-layout' => [
            'attributes' => [
                'eyebrow' => ['type' => 'string', 'default' => ''],
                'title' => ['type' => 'string', 'default' => ''],
                'text' => ['type' => 'string', 'default' => ''],
                'items' => ['type' => 'string', 'default' => ''],
                'formEyebrow' => ['type' => 'string', 'default' => ''],
                'formTitle' => ['type' => 'string', 'default' => ''],
            ],
            'render_callback' => 'pcp_render_quote_layout_block',
        ],
    ];

    foreach ($blocks as $name => $settings) {
        $render_callback = $settings['render_callback'];

        register_block_type(
            'pcp/' . $name,
            [
                'api_version' => 3,
                'editor_script' => 'pcp-home-blocks',
                'attributes' => $settings['attributes'],
                'uses_context' => ['postId'],
                'render_callback' => static function (array $attrs, string $content = '', ?WP_Block $block = null) use ($render_callback): string {
                    return pcp_home_render_block_safely($render_callback, $attrs, $block);
                },
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

function pcp_services_default_block_content(int $post_id = 0): string
{
    $post_id = $post_id ?: pcp_home_current_post_id();
    $meta = static fn(string $key): string => $post_id ? pcp_admin_content_value($post_id, $key) : '';
    $block_attrs = [
        'services-hero' => [
            'eyebrow' => $meta('pcp_hero_eyebrow'),
            'title' => $meta('pcp_hero_title'),
            'lead' => $meta('pcp_hero_lead'),
            'image' => $meta('pcp_hero_image'),
            'imageAlt' => $meta('pcp_hero_image_alt'),
            'primaryText' => $meta('pcp_primary_cta_text'),
            'primaryUrl' => $meta('pcp_primary_cta_url'),
            'secondaryText' => $meta('pcp_secondary_cta_text'),
            'secondaryUrl' => $meta('pcp_secondary_cta_url'),
        ],
        'services-intro' => [
            'eyebrow' => $meta('pcp_intro_eyebrow'),
            'title' => $meta('pcp_intro_title'),
            'text' => $meta('pcp_intro_text'),
        ],
        'services-grid' => [
            'cards' => $meta('pcp_cards_json'),
        ],
        'services-feature' => [
            'eyebrow' => $meta('pcp_feature_eyebrow'),
            'title' => $meta('pcp_feature_title'),
            'text' => $meta('pcp_feature_text'),
            'image' => $meta('pcp_feature_image'),
            'imageAlt' => $meta('pcp_feature_image_alt'),
            'items' => $meta('pcp_feature_list'),
        ],
        'services-cta' => [
            'eyebrow' => $meta('pcp_final_cta_eyebrow'),
            'title' => $meta('pcp_final_cta_title'),
            'text' => $meta('pcp_final_cta_text'),
            'buttonText' => $meta('pcp_final_cta_button_text'),
            'buttonUrl' => $meta('pcp_final_cta_button_url'),
        ],
    ];

    return implode(
        "\n\n",
        array_map(
            static fn(string $name): string => pcp_home_block_comment($name, array_filter($block_attrs[$name] ?? [], static fn($value): bool => $value !== '')),
            array_keys($block_attrs)
        )
    );
}

function pcp_detail_default_block_content(int $post_id = 0, string $aria_label = 'Cartes de contenu'): string
{
    $post_id = $post_id ?: pcp_home_current_post_id();
    $meta = static fn(string $key): string => $post_id ? pcp_admin_content_value($post_id, $key) : '';
    $block_attrs = [
        'detail-hero' => [
            'eyebrow' => $meta('pcp_hero_eyebrow'),
            'title' => $meta('pcp_hero_title'),
            'lead' => $meta('pcp_hero_lead'),
            'image' => $meta('pcp_hero_image'),
            'imageAlt' => $meta('pcp_hero_image_alt'),
            'primaryText' => $meta('pcp_primary_cta_text'),
            'primaryUrl' => $meta('pcp_primary_cta_url'),
            'secondaryText' => $meta('pcp_secondary_cta_text'),
            'secondaryUrl' => $meta('pcp_secondary_cta_url'),
        ],
        'detail-intro' => [
            'eyebrow' => $meta('pcp_intro_eyebrow'),
            'title' => $meta('pcp_intro_title'),
            'text' => $meta('pcp_intro_text'),
        ],
        'detail-grid' => [
            'cards' => $meta('pcp_cards_json'),
            'ariaLabel' => $aria_label,
        ],
        'detail-feature' => [
            'eyebrow' => $meta('pcp_feature_eyebrow'),
            'title' => $meta('pcp_feature_title'),
            'text' => $meta('pcp_feature_text'),
            'image' => $meta('pcp_feature_image'),
            'imageAlt' => $meta('pcp_feature_image_alt'),
            'ctaText' => $meta('pcp_feature_cta_text'),
            'ctaUrl' => $meta('pcp_feature_cta_url'),
        ],
    ];

    return implode(
        "\n\n",
        array_map(
            static fn(string $name): string => pcp_home_block_comment($name, array_filter($block_attrs[$name] ?? [], static fn($value): bool => $value !== '')),
            array_keys($block_attrs)
        )
    );
}

function pcp_contact_default_block_content(int $post_id = 0): string
{
    $post_id = $post_id ?: pcp_home_current_post_id();
    $meta = static fn(string $key): string => $post_id ? pcp_admin_content_value($post_id, $key) : '';
    $block_attrs = [
        'contact-hero' => [
            'eyebrow' => $meta('pcp_hero_eyebrow'),
            'title' => $meta('pcp_hero_title'),
            'lead' => $meta('pcp_hero_lead'),
            'image' => $meta('pcp_hero_image'),
            'imageAlt' => $meta('pcp_hero_image_alt'),
        ],
        'contact-layout' => [
            'eyebrow' => $meta('pcp_intro_eyebrow'),
            'title' => $meta('pcp_intro_title'),
            'cards' => $meta('pcp_cards_json'),
            'emailLabel' => $meta('pcp_contact_email_label'),
            'zoneLabel' => $meta('pcp_contact_zone_label'),
            'formEyebrow' => $meta('pcp_feature_eyebrow'),
            'formTitle' => $meta('pcp_feature_title'),
            'formText' => $meta('pcp_feature_text'),
        ],
    ];

    return implode(
        "\n\n",
        array_map(
            static fn(string $name): string => pcp_home_block_comment($name, array_filter($block_attrs[$name] ?? [], static fn($value): bool => $value !== '')),
            array_keys($block_attrs)
        )
    );
}

function pcp_quote_default_block_content(int $post_id = 0): string
{
    $post_id = $post_id ?: pcp_home_current_post_id();
    $meta = static fn(string $key): string => $post_id ? pcp_admin_content_value($post_id, $key) : '';
    $block_attrs = [
        'quote-hero' => [
            'eyebrow' => $meta('pcp_hero_eyebrow'),
            'title' => $meta('pcp_hero_title'),
            'lead' => $meta('pcp_hero_lead'),
            'image' => $meta('pcp_hero_image'),
            'imageAlt' => $meta('pcp_hero_image_alt'),
        ],
        'quote-prep' => [
            'eyebrow' => $meta('pcp_intro_eyebrow'),
            'title' => $meta('pcp_intro_title'),
            'items' => $meta('pcp_cards_json'),
        ],
        'quote-layout' => [
            'eyebrow' => $meta('pcp_feature_eyebrow'),
            'title' => $meta('pcp_feature_title'),
            'text' => $meta('pcp_feature_text'),
            'items' => $meta('pcp_feature_list'),
            'formEyebrow' => $meta('pcp_form_eyebrow'),
            'formTitle' => $meta('pcp_form_title'),
        ],
    ];

    return implode(
        "\n\n",
        array_map(
            static fn(string $name): string => pcp_home_block_comment($name, array_filter($block_attrs[$name] ?? [], static fn($value): bool => $value !== '')),
            array_keys($block_attrs)
        )
    );
}

function pcp_blog_default_block_content(int $post_id = 0, string $current_content = ''): string
{
    $post_id = $post_id ?: (int) get_option('page_for_posts');
    $meta = static fn(string $key): string => $post_id ? pcp_admin_content_value($post_id, $key) : '';
    $block_attrs = [
        'blog-hero' => [
            'eyebrow' => $meta('pcp_hero_eyebrow'),
            'title' => $meta('pcp_hero_title'),
            'lead' => $meta('pcp_hero_lead'),
            'image' => $meta('pcp_hero_image'),
            'imageAlt' => $meta('pcp_hero_image_alt'),
        ],
        'blog-archive' => [
            'eyebrow' => $meta('pcp_intro_eyebrow'),
            'title' => $meta('pcp_intro_title'),
        ],
    ];

    $parts = [
        pcp_home_block_comment('blog-hero', array_filter($block_attrs['blog-hero'], static fn($value): bool => $value !== '')),
    ];

    $current_content = trim($current_content);

    if ($current_content !== '' && !pcp_blog_has_blocks($current_content)) {
        $parts[] = $current_content;
    }

    $parts[] = pcp_home_block_comment('blog-archive', array_filter($block_attrs['blog-archive'], static fn($value): bool => $value !== ''));

    return implode("\n\n", $parts);
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
