<?php
$fallback = [
    ['title' => 'Cuisine lumineuse avec îlot central', 'type' => 'Cuisine', 'mood' => 'Warm Mineral', 'image' => 'island-light-ceramique.jpg', 'text' => 'Un grand îlot clair, pensé comme la pièce centrale de la maison.'],
    ['title' => 'Salle de bain minérale', 'type' => 'Salle de bain', 'mood' => 'Natural Stone', 'image' => 'bathroom-light-ceramique.jpg', 'text' => 'Une ambiance spa lumineuse avec surface céramique douce.'],
    ['title' => 'Plan de travail blanc veiné', 'type' => 'Cuisine', 'mood' => 'Pure Marble', 'image' => 'kitchen-white-ceramique.jpg', 'text' => 'Une cuisine blanche équilibrée par un veinage minéral discret.'],
    ['title' => 'Crédence et plan assortis', 'type' => 'Crédence', 'mood' => 'Pure Marble', 'image' => 'texture-white-vein.jpg', 'text' => 'Une continuité visuelle sobre entre plan, mur et lumière.'],
    ['title' => 'Cuisine vert sauge et pierre claire', 'type' => 'Cuisine', 'mood' => 'Sage Architecture', 'image' => 'kitchen-sage-ceramique.jpg', 'text' => 'Un projet calme, contemporain et très chaleureux.'],
    ['title' => 'Îlot familial chaleureux', 'type' => 'Îlot', 'mood' => 'Warm Mineral', 'image' => 'kitchen-warm-ceramique.jpg', 'text' => 'Une surface robuste dans un intérieur doux et accueillant.'],
];
$query = new WP_Query(['post_type' => 'pcp_realisation', 'posts_per_page' => 6, 'no_found_rows' => true]);
?>
<section class="pcstudio-section pcstudio-projects reveal-up" id="realisations">
  <div class="pcstudio-section__heading">
    <p class="pcstudio-label"><?php esc_html_e('Réalisations inspirantes', 'plan-ceramique-premium'); ?></p>
    <h2><?php esc_html_e('Des projets visuels pour se projeter dans la matière.', 'plan-ceramique-premium'); ?></h2>
  </div>
  <div class="pcstudio-projects__grid">
    <?php if ($query->have_posts()) : ?>
      <?php while ($query->have_posts()) : $query->the_post(); ?>
        <?php $postId = get_the_ID(); ?>
        <article class="pcstudio-project-card">
          <img src="<?php echo esc_url(pcp_post_image_url($postId, 'island-light-ceramique.jpg')); ?>" loading="lazy" width="720" height="520" alt="<?php echo esc_attr(get_the_title()); ?>">
          <div>
            <span><?php echo esc_html(pcp_post_meta($postId, 'pcp_project_type', __('Projet', 'plan-ceramique-premium'))); ?></span>
            <h3><?php the_title(); ?></h3>
            <p><?php echo esc_html(pcp_excerpt_text(get_post(), 20)); ?></p>
            <small><?php echo esc_html(pcp_post_meta($postId, 'pcp_mood', __('Ambiance premium', 'plan-ceramique-premium'))); ?></small>
            <a href="<?php the_permalink(); ?>"><?php esc_html_e('Découvrir', 'plan-ceramique-premium'); ?></a>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
      <?php foreach ($fallback as $item) : ?>
        <article class="pcstudio-project-card">
          <img src="<?php echo esc_url(pcp_asset_img($item['image'])); ?>" loading="lazy" width="720" height="520" alt="<?php echo esc_attr($item['title']); ?>">
          <div>
            <span><?php echo esc_html($item['type']); ?></span>
            <h3><?php echo esc_html($item['title']); ?></h3>
            <p><?php echo esc_html($item['text']); ?></p>
            <small><?php echo esc_html($item['mood']); ?></small>
            <a href="#devis"><?php esc_html_e('Préparer un projet similaire', 'plan-ceramique-premium'); ?></a>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>
