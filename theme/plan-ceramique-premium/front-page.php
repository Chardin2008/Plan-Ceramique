<?php get_header(); ?>
<main id="main-content" class="site-main">
  <?php
  while (have_posts()) :
      the_post();
      ?>
    <article <?php post_class('pcp-entry pcp-entry--front'); ?>>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
