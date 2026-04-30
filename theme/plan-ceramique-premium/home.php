<?php get_header(); ?>
<main id="main-content" class="site-main pcstudio pcstudio-blog-page">
  <section class="pcstudio-hero pcstudio-hero--blog">
    <div class="pcstudio-hero__copy">
      <p class="pcstudio-label"><?php esc_html_e('Conseils & inspirations', 'plan-ceramique-premium'); ?></p>
      <h1 class="hero-title"><?php esc_html_e('Blog céramique premium', 'plan-ceramique-premium'); ?></h1>
      <p class="pcstudio-hero__lead"><?php esc_html_e('Guides, tendances et idées pour imaginer un plan de travail lumineux, durable et parfaitement intégré à votre espace.', 'plan-ceramique-premium'); ?></p>
    </div>
    <figure class="pcstudio-hero__media">
      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/blog-kitchen-trends.jpg'); ?>" width="980" height="720" alt="<?php esc_attr_e('Moodboard cuisine premium avec céramique claire et bois', 'plan-ceramique-premium'); ?>">
    </figure>
  </section>

  <section class="pcstudio-section pcstudio-blog">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php esc_html_e('Articles', 'plan-ceramique-premium'); ?></p>
      <h2><?php esc_html_e('Des repères clairs pour préparer votre projet.', 'plan-ceramique-premium'); ?></h2>
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
      <p><?php esc_html_e('Aucun article publié pour le moment.', 'plan-ceramique-premium'); ?></p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
