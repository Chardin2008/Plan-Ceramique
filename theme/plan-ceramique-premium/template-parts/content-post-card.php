<?php
$image = pcp_post_image_url(get_the_ID());
$category = get_the_category();
$label = $category ? $category[0]->name : __('Conseils', 'plan-ceramique-premium');
?>
<article <?php post_class('pcstudio-post-card'); ?>>
  <img src="<?php echo esc_url($image); ?>" loading="lazy" width="540" height="360" alt="<?php echo esc_attr(get_the_title()); ?>">
  <div class="pcstudio-post-card__meta">
    <p class="pcstudio-label"><?php echo esc_html($label); ?></p>
    <span><?php echo esc_html(pcp_reading_time(get_the_ID())); ?></span>
  </div>
  <h3><?php the_title(); ?></h3>
  <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
  <a href="#devis"><?php esc_html_e('Demander un conseil', 'plan-ceramique-premium'); ?></a>
</article>
