<?php
get_header();
$hero = pcp_page_hero_data(get_post());
?>
<main id="main-content" class="site-main">
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class('pcp-entry pcp-entry--page'); ?>>
      <header class="pcp-page-hero pcp-page-hero--with-media">
        <div class="pcp-page-hero__inner">
          <div class="pcp-page-hero__copy">
            <p class="pcp-page-hero__eyebrow"><?php echo esc_html($hero['eyebrow']); ?></p>
            <h1 class="pcp-page-hero__title"><?php the_title(); ?></h1>
            <div class="pcp-page-hero__excerpt">
              <?php echo esc_html($hero['description']); ?>
            </div>
          </div>
          <figure class="pcp-page-hero__media">
            <img src="<?php echo esc_url($hero['image']); ?>" alt="<?php echo esc_attr($hero['image_alt']); ?>">
          </figure>
        </div>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
