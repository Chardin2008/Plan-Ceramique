<?php get_header(); ?>
<main id="main-content" class="site-main pcstudio pcstudio-blog-page">
  <section class="pcstudio-section pcstudio-blog">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php esc_html_e('Archive', 'plan-ceramique-premium'); ?></p>
      <h1 class="hero-title"><?php the_archive_title(); ?></h1>
      <p><?php echo wp_kses_post(get_the_archive_description() ?: __('Articles et inspirations autour des plans de travail en céramique.', 'plan-ceramique-premium')); ?></p>
    </div>
    <?php if (have_posts()) : ?>
      <div class="pcstudio-blog__grid">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('template-parts/content', 'post-card'); ?>
        <?php endwhile; ?>
      </div>
      <?php
      the_posts_pagination(
          [
              'prev_text' => __('Articles récents', 'plan-ceramique-premium'),
              'next_text' => __('Articles suivants', 'plan-ceramique-premium'),
          ]
      );
      ?>
    <?php else : ?>
      <p><?php esc_html_e('Aucun contenu pour le moment.', 'plan-ceramique-premium'); ?></p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
