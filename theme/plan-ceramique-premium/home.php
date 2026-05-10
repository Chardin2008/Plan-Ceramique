<?php
get_header();
$page_id = (int) get_option('page_for_posts');
$hero_image = pcp_admin_content_value($page_id, 'pcp_hero_image', 'blog-kitchen-trends.jpg');
?>
<main id="main-content" class="site-main pcstudio pcstudio-blog-page">
  <section class="pcstudio-hero pcstudio-hero--blog">
    <div class="pcstudio-hero__copy">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_eyebrow', 'Conseils & inspirations')); ?></p>
      <h1 class="hero-title"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_title', 'Blog céramique premium')); ?></h1>
      <p class="pcstudio-hero__lead"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_lead', 'Guides, tendances et idées pour imaginer un plan de travail lumineux, durable et parfaitement intégré à votre espace.')); ?></p>
    </div>
    <figure class="pcstudio-hero__media">
      <img src="<?php echo esc_url(pcp_asset_img($hero_image)); ?>" width="980" height="720" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_hero_image_alt', 'Moodboard cuisine premium avec céramique claire et bois')); ?>">
    </figure>
  </section>

  <section class="pcstudio-section pcstudio-blog">
    <div class="pcstudio-section__heading">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_eyebrow', 'Articles')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_title', 'Des repères clairs pour préparer votre projet.')); ?></h2>
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
