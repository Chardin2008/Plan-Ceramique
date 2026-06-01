<?php get_header(); ?>
<main id="main-content" class="site-main pcstudio pcstudio-single">
  <?php while (have_posts()) : the_post(); ?>
    <?php
    $postId = get_the_ID();
    $image = pcp_post_image_url($postId);
    $categories = get_the_category($postId);
    $category = $categories ? $categories[0] : null;
    $related = new WP_Query(
        [
            'post_type' => 'post',
            'posts_per_page' => 3,
            'post__not_in' => [$postId],
            'ignore_sticky_posts' => true,
            'no_found_rows' => true,
            'category__in' => $category ? [$category->term_id] : [],
        ]
    );
    $blogPageId = (int) get_option('page_for_posts');
    $blogUrl = $blogPageId ? get_permalink($blogPageId) : home_url('/blog/');
    $backText = pcp_get_setting('article_back_text') ?: 'Retour au blog';
    $asideHeading = pcp_get_setting('article_aside_heading') ?: 'Reperes';
    $projectLabel = pcp_get_setting('article_project_label') ?: 'Projet ceramique';
    $asideCtaText = pcp_get_setting('article_aside_cta_text') ?: 'Demander un devis';
    $asideCtaUrl = pcp_site_url(pcp_get_setting('article_aside_cta_url') ?: '/#devis');
    $prevText = pcp_get_setting('article_prev_text') ?: 'Article precedent';
    $nextText = pcp_get_setting('article_next_text') ?: 'Article suivant';
    $relatedEyebrow = pcp_get_setting('article_related_eyebrow') ?: 'A lire aussi';
    $relatedTitle = pcp_get_setting('article_related_title') ?: 'Continuer a preparer votre projet.';
    $ctaEyebrow = pcp_get_setting('article_cta_eyebrow') ?: 'Projet sur mesure';
    $ctaTitle = pcp_get_setting('article_cta_title') ?: 'Besoin de traduire ces idees dans votre cuisine ?';
    $ctaText = pcp_get_setting('article_cta_text') ?: 'Decrivez votre espace, vos dimensions et l’ambiance souhaitee. Nous vous aidons a cadrer une surface ceramique coherente avec votre projet.';
    $ctaButtonText = pcp_get_setting('article_cta_button_text') ?: 'Preparer mon devis';
    $ctaButtonUrl = pcp_site_url(pcp_get_setting('article_cta_button_url') ?: '/#devis');
    ?>
    <article <?php post_class('pcstudio-article'); ?>>
      <header class="pcstudio-article-hero">
        <div>
          <a class="pcstudio-article-back" href="<?php echo esc_url($blogUrl); ?>"><?php echo esc_html($backText); ?></a>
          <p class="pcstudio-label">
            <?php if ($category) : ?>
              <?php echo esc_html($category->name); ?> ·
            <?php endif; ?>
            <?php echo esc_html(get_the_date()); ?> · <?php echo esc_html(pcp_reading_time($postId)); ?>
          </p>
          <h1 class="hero-title"><?php the_title(); ?></h1>
          <?php if (has_excerpt()) : ?>
            <p class="pcstudio-article-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
          <?php endif; ?>
        </div>
        <figure>
          <img src="<?php echo esc_url($image); ?>" width="980" height="620" alt="<?php echo esc_attr(get_the_title()); ?>">
        </figure>
      </header>

      <div class="pcstudio-article-shell">
        <aside class="pcstudio-article-aside" aria-label="<?php esc_attr_e('Repères article', 'plan-ceramique-premium'); ?>">
          <p class="pcstudio-label"><?php echo esc_html($asideHeading); ?></p>
          <ul>
            <li><?php echo esc_html(pcp_reading_time($postId)); ?></li>
            <?php if ($category) : ?>
              <li><?php echo esc_html($category->name); ?></li>
            <?php endif; ?>
            <li><?php echo esc_html($projectLabel); ?></li>
          </ul>
          <a class="button button--ghost" href="<?php echo esc_url($asideCtaUrl); ?>"><?php echo esc_html($asideCtaText); ?></a>
        </aside>

        <div class="pcstudio-article-body entry-content">
          <?php the_content(); ?>
        </div>
      </div>

      <nav class="pcstudio-article-nav" aria-label="<?php esc_attr_e('Navigation entre articles', 'plan-ceramique-premium'); ?>">
        <div><?php previous_post_link('%link', esc_html($prevText)); ?></div>
        <div><?php next_post_link('%link', esc_html($nextText)); ?></div>
      </nav>

      <?php if ($related->have_posts()) : ?>
        <section class="pcstudio-section pcstudio-related-posts">
          <div class="pcstudio-section__heading">
            <p class="pcstudio-label"><?php echo esc_html($relatedEyebrow); ?></p>
            <h2><?php echo esc_html($relatedTitle); ?></h2>
          </div>
          <div class="pcstudio-blog__grid">
            <?php while ($related->have_posts()) : $related->the_post(); ?>
              <?php get_template_part('template-parts/content', 'post-card'); ?>
            <?php endwhile; ?>
          </div>
        </section>
        <?php wp_reset_postdata(); ?>
      <?php endif; ?>

      <section class="pcstudio-article-cta">
        <div>
          <p class="pcstudio-label"><?php echo esc_html($ctaEyebrow); ?></p>
          <h2><?php echo esc_html($ctaTitle); ?></h2>
          <p><?php echo esc_html($ctaText); ?></p>
        </div>
        <a class="button" href="<?php echo esc_url($ctaButtonUrl); ?>"><?php echo esc_html($ctaButtonText); ?></a>
      </section>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
