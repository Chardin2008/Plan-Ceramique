<?php
$fallback = [
    ['title' => 'Blanc veiné', 'filter' => 'Clair', 'image' => 'texture-white-vein.jpg', 'text' => 'Une surface lumineuse avec veinage subtil pour agrandir visuellement la cuisine.', 'mood' => 'Pure Marble', 'use' => 'Cuisine, crédence, salle de bain'],
    ['title' => 'Beige minéral', 'filter' => 'Chaleureux', 'image' => 'texture-sand-stone.jpg', 'text' => 'Une teinte douce, sable et champagne, idéale pour les intérieurs chaleureux.', 'mood' => 'Warm Mineral', 'use' => 'Îlot central, cuisine familiale'],
    ['title' => 'Gris clair béton', 'filter' => 'Béton', 'image' => 'texture-concrete-light.jpg', 'text' => 'Un esprit architectural calme, plus contemporain que froid.', 'mood' => 'Soft Concrete', 'use' => 'Cuisine moderne, table sur mesure'],
    ['title' => 'Pierre naturelle', 'filter' => 'Pierre', 'image' => 'hero-light-ceramique.jpg', 'text' => 'Un rendu minéral équilibré pour une ambiance premium et intemporelle.', 'mood' => 'Natural Stone', 'use' => 'Cuisine, salle de bain'],
    ['title' => 'Sable chaud', 'filter' => 'Naturel', 'image' => 'kitchen-warm-ceramique.jpg', 'text' => 'Une matière claire qui dialogue avec le bois, le lin et les murs chauds.', 'mood' => 'Warm Mineral', 'use' => 'Îlot central, crédence'],
    ['title' => 'Terre douce', 'filter' => 'Chaleureux', 'image' => 'texture-walnut-stone.jpg', 'text' => 'Une note plus organique pour donner du relief sans assombrir le projet.', 'mood' => 'Sage Architecture', 'use' => 'Cuisine, projet architectural'],
];

$query = new WP_Query([
    'post_type' => 'pcp_matiere',
    'posts_per_page' => 6,
    'no_found_rows' => true,
]);
$material_cta_text = pcp_get_setting('landing_material_card_cta_text') ?: 'Demander un conseil matiere';
?>
<section class="pcstudio-section pcstudio-materials reveal-up" id="matieres" data-filter-scope>
  <div class="pcstudio-section__heading">
    <p class="pcstudio-label"><?php esc_html_e('Matières dynamiques', 'plan-ceramique-premium'); ?></p>
    <h2><?php esc_html_e('Collections céramiques à composer selon votre ambiance.', 'plan-ceramique-premium'); ?></h2>
  </div>
  <div class="pcstudio-filter-bar" aria-label="<?php esc_attr_e('Filtrer les matières', 'plan-ceramique-premium'); ?>">
    <?php foreach (['Tous', 'Clair', 'Chaleureux', 'Pierre', 'Béton', 'Naturel'] as $filter) : ?>
      <button type="button" class="<?php echo $filter === 'Tous' ? 'is-active' : ''; ?>" data-filter="<?php echo esc_attr($filter); ?>"><?php echo esc_html($filter); ?></button>
    <?php endforeach; ?>
  </div>
  <div class="pcstudio-materials__grid">
    <?php if ($query->have_posts()) : ?>
      <?php while ($query->have_posts()) : $query->the_post(); ?>
        <?php
        $postId = get_the_ID();
        $filter = pcp_post_meta($postId, 'pcp_color_family', 'Clair');
        ?>
        <article class="pcstudio-material-card" data-filter-item data-filter-value="<?php echo esc_attr($filter); ?>">
          <img src="<?php echo esc_url(pcp_post_image_url($postId, 'texture-white-vein.jpg')); ?>" loading="lazy" width="520" height="380" alt="<?php echo esc_attr(get_the_title()); ?>">
          <div>
            <p class="pcstudio-label"><?php echo esc_html($filter); ?></p>
            <h3><?php the_title(); ?></h3>
            <p><?php echo esc_html(pcp_excerpt_text(get_post(), 18)); ?></p>
            <small><?php echo esc_html(pcp_post_meta($postId, 'pcp_mood', __('Ambiance à définir', 'plan-ceramique-premium'))); ?> · <?php echo esc_html(pcp_post_meta($postId, 'pcp_use', __('Usage polyvalent', 'plan-ceramique-premium'))); ?></small>
            <a href="<?php echo esc_url(pcp_site_url('#devis')); ?>"><?php echo esc_html($material_cta_text); ?></a>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
      <?php foreach ($fallback as $item) : ?>
        <article class="pcstudio-material-card" data-filter-item data-filter-value="<?php echo esc_attr($item['filter']); ?>">
          <img src="<?php echo esc_url(pcp_asset_img($item['image'])); ?>" loading="lazy" width="520" height="380" alt="<?php echo esc_attr($item['title']); ?>">
          <div>
            <p class="pcstudio-label"><?php echo esc_html($item['filter']); ?></p>
            <h3><?php echo esc_html($item['title']); ?></h3>
            <p><?php echo esc_html($item['text']); ?></p>
            <small><?php echo esc_html($item['mood']); ?> · <?php echo esc_html($item['use']); ?></small>
            <a href="<?php echo esc_url(pcp_site_url('#devis')); ?>"><?php echo esc_html($material_cta_text); ?></a>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>
