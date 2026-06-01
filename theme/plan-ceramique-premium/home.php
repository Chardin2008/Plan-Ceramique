<?php
get_header();

$page_id = (int) get_option('page_for_posts');
$page_content = $page_id ? (string) get_post_field('post_content', $page_id) : '';

if ($page_id && function_exists('pcp_blog_has_blocks') && pcp_blog_has_blocks($page_content)) :
?>
<main id="main-content" class="site-main pcstudio pcstudio-blog-page">
  <?php echo apply_filters('the_content', $page_content); ?>
</main>
<?php
get_footer();
return;
endif;

$hero_image = pcp_admin_content_value($page_id, 'pcp_hero_image', 'blog-kitchen-trends.jpg');
$pagination_prev_text = pcp_get_setting('blog_pagination_prev_text') ?: 'Articles récents';
$pagination_next_text = pcp_get_setting('blog_pagination_next_text') ?: 'Articles suivants';
$empty_text = pcp_get_setting('blog_empty_text') ?: 'Aucun article publié pour le moment.';
?>
<main id="main-content" class="site-main pcstudio pcstudio-blog-page">
  <section class="pcstudio-blog-hero">
    <div class="pcstudio-blog-hero__copy">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_eyebrow', 'Blog & conseils')); ?></p>
      <h1><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_title', 'Conseils pour concevoir une cuisine durable, belle et vraiment fonctionnelle.')); ?></h1>
      <p><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_hero_lead', 'Un espace éditorial pour préparer votre projet : tendances, matériaux, implantation, entretien et pose.')); ?></p>
    </div>
    <figure class="pcstudio-blog-hero__media">
      <img src="<?php echo esc_url(pcp_asset_img($hero_image)); ?>" width="1320" height="720" alt="<?php echo esc_attr(pcp_admin_content_value($page_id, 'pcp_hero_image_alt', 'Cuisine premium lumineuse avec plan de travail minéral')); ?>">
    </figure>
  </section>

  <section class="pcstudio-section pcstudio-blog pcstudio-blog--archive">
    <div class="pcstudio-section__heading pcstudio-section__heading--center">
      <p class="pcstudio-label"><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_eyebrow', 'Tous les articles')); ?></p>
      <h2><?php echo esc_html(pcp_admin_content_value($page_id, 'pcp_intro_title', '30 sujets SEO pour avancer sans se tromper.')); ?></h2>
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
              'prev_text' => esc_html($pagination_prev_text),
              'next_text' => esc_html($pagination_next_text),
          ]
      );
      ?>
    <?php else : ?>
      <p><?php echo esc_html($empty_text); ?></p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
