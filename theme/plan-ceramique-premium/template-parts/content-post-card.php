<?php
$image = pcp_post_image_url(get_the_ID());
$category = get_the_category();
$label = $category ? $category[0]->name : (pcp_get_setting('blog_card_default_label') ?: 'Conseils');
$readMoreText = pcp_get_setting('blog_card_read_more_text') ?: 'Lire l’article';
$hasVideo = (bool) pcp_post_meta(get_the_ID(), '_pcp_article_video_embed');
?>
<article <?php post_class('pcstudio-post-card'); ?>>
  <a class="pcstudio-post-card__image" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
    <img src="<?php echo esc_url($image); ?>" loading="lazy" width="540" height="360" alt="<?php echo esc_attr(get_the_title()); ?>">
    <?php if ($hasVideo) : ?>
      <span class="pcstudio-video-pill">Vidéo</span>
    <?php endif; ?>
  </a>
  <div class="pcstudio-post-card__meta">
    <p class="pcstudio-label"><?php echo esc_html($label); ?></p>
    <span><?php echo esc_html(pcp_reading_time(get_the_ID())); ?></span>
  </div>
  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
  <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
  <a href="<?php the_permalink(); ?>"><?php echo esc_html($readMoreText); ?></a>
</article>
