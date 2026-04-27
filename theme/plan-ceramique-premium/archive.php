<?php
get_header();
$hero = pcp_page_hero_data();
?>
<main id="main-content" class="site-main">
  <section class="pcp-page-hero pcp-page-hero--with-media">
    <div class="pcp-page-hero__inner">
      <div class="pcp-page-hero__copy">
        <p class="pcp-page-hero__eyebrow"><?php esc_html_e('Archives', 'plan-ceramique-premium'); ?></p>
        <h1 class="pcp-page-hero__title"><?php the_archive_title(); ?></h1>
        <div class="pcp-page-hero__excerpt">
          <?php echo wp_kses_post(get_the_archive_description() ?: esc_html__('Conseils et inspirations autour des plans de travail en ceramique, de la fabrication a la pose.', 'plan-ceramique-premium')); ?>
        </div>
      </div>
      <figure class="pcp-page-hero__media">
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-blog.jpg'); ?>" alt="<?php esc_attr_e('Visuel editorial premium pour archives du blog ceramique', 'plan-ceramique-premium'); ?>">
      </figure>
    </div>
  </section>
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
        <p><?php esc_html_e('Aucun contenu pour le moment.', 'plan-ceramique-premium'); ?></p>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
