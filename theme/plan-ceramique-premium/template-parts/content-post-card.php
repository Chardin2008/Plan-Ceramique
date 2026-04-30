<?php
$image = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: get_template_directory_uri() . '/assets/img/blog-material-choice.jpg';
$category = get_the_category();
$label = $category ? $category[0]->name : __('Conseils', 'plan-ceramique-premium');
?>
<article <?php post_class('pcstudio-post-card'); ?>>
  <img src="<?php echo esc_url($image); ?>" loading="lazy" width="540" height="360" alt="<?php echo esc_attr(get_the_title()); ?>">
  <div class="pcstudio-post-card__meta">
    <p class="pcstudio-label"><?php echo esc_html($label); ?></p>
    <span><?php echo esc_html(pcp_reading_time(get_the_ID())); ?></span>
  </div>
  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
  <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
  <a href="<?php the_permalink(); ?>"><?php esc_html_e('Lire l’article', 'plan-ceramique-premium'); ?></a>
</article>
