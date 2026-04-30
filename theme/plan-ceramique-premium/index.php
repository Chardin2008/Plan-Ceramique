<?php get_header(); ?>
<main id="main-content" class="site-main pcstudio pcstudio-blog-page">
  <section class="pcstudio-section pcstudio-blog">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php esc_html_e('Plan Céramique Studio', 'plan-ceramique-premium'); ?></p>
      <h1 class="hero-title"><?php esc_html_e('Articles & inspirations', 'plan-ceramique-premium'); ?></h1>
    </div>
    <?php if (have_posts()) : ?>
      <div class="pcstudio-blog__grid">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('template-parts/content', 'post-card'); ?>
        <?php endwhile; ?>
      </div>
      <?php the_posts_pagination(); ?>
    <?php else : ?>
      <p><?php esc_html_e('Aucun contenu publié.', 'plan-ceramique-premium'); ?></p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
