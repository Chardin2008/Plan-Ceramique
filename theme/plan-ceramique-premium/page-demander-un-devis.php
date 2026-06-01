<?php
get_header();

$page_id = get_queried_object_id();
$page_content = get_post_field('post_content', $page_id);

if (function_exists('pcp_quote_has_blocks') && pcp_quote_has_blocks((string) $page_content)) :
    ?>
    <main id="main-content" class="site-main pcp-quote-page">
      <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; ?>
    </main>
    <?php
    get_footer();
    return;
endif;

$asset_uri = static fn(string $file): string => get_template_directory_uri() . '/assets/images/' . $file;
$hero_image = pcp_admin_content_value($page_id, 'pcp_hero_image', 'hero-quote.jpg');

$steps = [
    ['icon' => '1', 'title' => 'Dimensions', 'text' => 'Longueurs, profondeurs, îlot, retours ou plan approximatif.'],
    ['icon' => '2', 'title' => 'Finition', 'text' => 'Effet marbre, pierre, béton minéral ou choix encore à définir.'],
    ['icon' => '3', 'title' => 'Contraintes', 'text' => 'Ville, accès, étage, meubles déjà posés ou cuisine en rénovation.'],
];
$steps = pcp_admin_content_pipe_rows($page_id, 'pcp_cards_json', ['icon', 'title', 'text'], $steps);

$feature_list = [
    'Type de cuisine ou îlot central.',
    'Évier, plaque, crédence ou découpes prévues.',
    'Finition souhaitée ou ambiance recherchée.',
    'Ville et contraintes d’accès.',
];
$feature_list = pcp_admin_content_lines($page_id, 'pcp_feature_list', $feature_list);
?>
<main id="main-content" class="site-main pcp-quote-page">
  <section class="pcp-quote-hero">
    <div class="pcp-quote-hero__copy">
      <p class="pcp-quote-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_eyebrow', 'Demande de devis')); ?></p>
      <h1><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_title', 'Préparer une étude claire pour votre plan de travail.')); ?></h1>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_lead', 'Le formulaire devis rassemble les informations utiles pour comprendre votre cuisine, les dimensions, les découpes, la finition souhaitée et les conditions de pose.')); ?></p>
    </div>
    <figure class="pcp-quote-hero__media">
      <img src="<?php echo esc_url($asset_uri($hero_image)); ?>" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_hero_image_alt', 'Ambiance showroom pour demande de devis en céramique')); ?>">
    </figure>
  </section>

  <section class="pcp-quote-prep">
    <div>
      <p class="pcp-quote-eyebrow"><?php echo str_replace('&#039;', "'", esc_html(pcp_admin_content_value($page_id, 'pcp_intro_eyebrow', 'Avant d\'envoyer'))); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_title', 'Quelques repères suffisent pour commencer.')); ?></h2>
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
      <p class="pcp-quote-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_eyebrow', 'Ce qui aide')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_title', 'Plus le projet est précis, plus la réponse peut être juste.')); ?></h2>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_feature_text', 'Vous pouvez joindre une photo, un plan ou donner des dimensions approximatives. Même si tout n’est pas encore finalisé, ces éléments permettent de comprendre la configuration.')); ?></p>
      <ul>
<?php foreach ($feature_list as $item) : ?>
        <li><?php echo esc_html($item); ?></li>
<?php endforeach; ?>
      </ul>
    </aside>

    <section class="pcp-quote-form-card" aria-label="Formulaire de demande de devis">
      <p class="pcp-quote-eyebrow"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_form_eyebrow', 'Formulaire')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_form_title', 'Demander mon étude')); ?></h2>
      <?php echo do_shortcode('[pcp_contact_form type="quote"]'); ?>
    </section>
  </section>
</main>
<?php get_footer(); ?>
