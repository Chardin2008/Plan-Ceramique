<?php
get_header();
$postsPageId = (int) get_option('page_for_posts');
$postsPage = $postsPageId ? get_post($postsPageId) : null;
$hero = $postsPage ? pcp_page_hero_data($postsPage) : pcp_page_hero_data();
?>
<main id="main-content" class="site-main">
  <section class="pcp-page-hero pcp-page-hero--with-media pcp-page-hero--blog">
    <div class="pcp-page-hero__inner">
      <div class="pcp-page-hero__copy">
        <p class="pcp-page-hero__eyebrow"><?php echo esc_html($hero['eyebrow']); ?></p>
        <h1 class="pcp-page-hero__title"><?php echo esc_html($postsPage ? get_the_title($postsPage) : __('Conseils ceramique', 'plan-ceramique-premium')); ?></h1>
        <div class="pcp-page-hero__excerpt">
          <?php if ($postsPage && $postsPage->post_content) : ?>
            <?php echo apply_filters('the_content', $postsPage->post_content); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
          <?php else : ?>
            <?php echo esc_html($hero['description']); ?>
          <?php endif; ?>
        </div>
      </div>
      <figure class="pcp-page-hero__media">
        <img src="<?php echo esc_url($hero['image']); ?>" alt="<?php echo esc_attr($hero['image_alt']); ?>">
      </figure>
    </div>
  </section>

  <section class="pcp-posts-shell">
    <?php if (have_posts()) : ?>
      <div class="pcp-post-grid">
        <?php while (have_posts()) : the_post(); ?>
          <article <?php post_class('pcp-post-card'); ?>>
            <p class="pcp-post-card__meta"><?php echo esc_html(get_the_date()); ?> · <?php echo esc_html(pcp_reading_time(get_the_ID())); ?></p>
            <h2 class="pcp-post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="pcp-post-card__excerpt"><?php the_excerpt(); ?></div>
            <a class="pcp-post-card__link" href="<?php the_permalink(); ?>"><?php esc_html_e('Lire l article', 'plan-ceramique-premium'); ?></a>
          </article>
        <?php endwhile; ?>
      </div>
      <?php the_posts_pagination(); ?>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
