<?php
$fallback = [
    ['title' => 'Cuisine lumineuse', 'filter' => 'Cuisine', 'image' => 'kitchen-white-ceramique.jpg'],
    ['title' => 'Îlot central clair', 'filter' => 'Îlot', 'image' => 'island-light-ceramique.jpg'],
    ['title' => 'Salle de bain minérale', 'filter' => 'Salle de bain', 'image' => 'bathroom-light-ceramique.jpg'],
    ['title' => 'Crédence veinée', 'filter' => 'Crédence', 'image' => 'texture-white-vein.jpg'],
    ['title' => 'Cuisine sauge', 'filter' => 'Cuisine', 'image' => 'kitchen-sage-ceramique.jpg'],
    ['title' => 'Extérieur lumineux', 'filter' => 'Extérieur', 'image' => 'outdoor-light-ceramique.jpg'],
];
$query = new WP_Query(['post_type' => 'pcp_realisation', 'posts_per_page' => 8, 'no_found_rows' => true]);
?>
<section class="pcstudio-section pcstudio-gallery reveal-up" id="galerie" data-filter-scope data-lightbox-gallery>
  <div class="pcstudio-section__heading">
    <p class="pcstudio-label"><?php esc_html_e('Galerie immersive', 'plan-ceramique-premium'); ?></p>
    <h2><?php esc_html_e('Galerie d’inspirations', 'plan-ceramique-premium'); ?></h2>
  </div>
  <div class="pcstudio-filter-bar" aria-label="<?php esc_attr_e('Filtrer la galerie', 'plan-ceramique-premium'); ?>">
    <?php foreach (['Tous', 'Cuisine', 'Îlot', 'Salle de bain', 'Crédence', 'Extérieur'] as $filter) : ?>
      <button type="button" class="<?php echo $filter === 'Tous' ? 'is-active' : ''; ?>" data-filter="<?php echo esc_attr($filter); ?>"><?php echo esc_html($filter); ?></button>
    <?php endforeach; ?>
  </div>
  <div class="pcstudio-gallery__grid">
    <?php if ($query->have_posts()) : ?>
      <?php while ($query->have_posts()) : $query->the_post(); ?>
        <?php
        $postId = get_the_ID();
        $filter = pcp_post_meta($postId, 'pcp_gallery_filter', pcp_post_meta($postId, 'pcp_project_type', 'Cuisine'));
        $image = pcp_post_image_url($postId, 'kitchen-white-ceramique.jpg');
        ?>
        <button type="button" data-filter-item data-filter-value="<?php echo esc_attr($filter); ?>" data-lightbox-src="<?php echo esc_url($image); ?>" data-lightbox-alt="<?php echo esc_attr(get_the_title()); ?>">
          <img src="<?php echo esc_url($image); ?>" loading="lazy" width="720" height="520" alt="<?php echo esc_attr(get_the_title()); ?>">
        </button>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
      <?php foreach ($fallback as $item) : ?>
        <?php $image = pcp_asset_img($item['image']); ?>
        <button type="button" data-filter-item data-filter-value="<?php echo esc_attr($item['filter']); ?>" data-lightbox-src="<?php echo esc_url($image); ?>" data-lightbox-alt="<?php echo esc_attr($item['title']); ?>">
          <img src="<?php echo esc_url($image); ?>" loading="lazy" width="720" height="520" alt="<?php echo esc_attr($item['title']); ?>">
        </button>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

<div class="pcstudio-lightbox" data-lightbox role="dialog" aria-modal="true" aria-hidden="true" hidden>
  <button class="pcstudio-lightbox__close" type="button" data-lightbox-close aria-label="<?php esc_attr_e('Fermer la galerie', 'plan-ceramique-premium'); ?>">×</button>
  <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==" alt="<?php esc_attr_e('Aperçu galerie', 'plan-ceramique-premium'); ?>" data-lightbox-image>
</div>
