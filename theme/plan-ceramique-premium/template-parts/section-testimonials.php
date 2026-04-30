<?php
$fallback = [
    ['name' => 'Nadia M.', 'project' => 'Cuisine avec îlot central', 'rating' => 5, 'text' => 'Le rendu est élégant, lumineux et vraiment haut de gamme. Le plan a changé toute l’ambiance de la cuisine.'],
    ['name' => 'Thomas R.', 'project' => 'Rénovation cuisine', 'rating' => 5, 'text' => 'Le choix des matières rend la pièce beaucoup plus moderne et chaleureuse.'],
    ['name' => 'Sarah L.', 'project' => 'Salle de bain', 'rating' => 5, 'text' => 'Le résultat est propre, contemporain et facile à entretenir. C’est exactement l’ambiance que je voulais.'],
];
$query = new WP_Query(['post_type' => 'pcp_avis', 'posts_per_page' => 3, 'no_found_rows' => true]);
?>
<section class="pcstudio-section pcstudio-reviews reveal-up" id="avis">
  <div class="pcstudio-section__heading">
    <p class="pcstudio-label">4.9/5</p>
    <h2><?php esc_html_e('Ils ont transformé leur espace', 'plan-ceramique-premium'); ?></h2>
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
</section>
