<?php get_header(); ?>
<main id="main-content" class="site-main">
  <section class="pcp-posts-shell">
    <?php if (have_posts()) : ?>
      <div class="pcp-post-grid">
        <?php while (have_posts()) : the_post(); ?>
          <article <?php post_class('pcp-post-card'); ?>>
            <p class="pcp-post-card__meta"><?php echo esc_html(get_the_date()); ?></p>
            <h2 class="pcp-post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="pcp-post-card__excerpt"><?php the_excerpt(); ?></div>
          </article>
        <?php endwhile; ?>
      </div>
      <?php the_posts_pagination(); ?>
    <?php else : ?>
      <div class="pcp-posts-shell">
        <p><?php esc_html_e('Aucun contenu publié.', 'plan-ceramique-premium'); ?></p>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
