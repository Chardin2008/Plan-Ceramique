<?php get_header(); ?>
<main id="main-content" class="site-main pcstudio pcstudio-single">
  <?php while (have_posts()) : the_post(); ?>
    <?php
    $postId = get_the_ID();
    $image = get_the_post_thumbnail_url($postId, 'large') ?: get_template_directory_uri() . '/assets/img/blog-material-choice.jpg';
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
    ?>
    <article <?php post_class('pcstudio-article'); ?>>
      <header class="pcstudio-article-hero">
        <div>
          <a class="pcstudio-article-back" href="<?php echo esc_url(home_url('/blog/')); ?>"><?php esc_html_e('Retour au blog', 'plan-ceramique-premium'); ?></a>
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
          <p class="pcstudio-label"><?php esc_html_e('Repères', 'plan-ceramique-premium'); ?></p>
          <ul>
            <li><?php echo esc_html(pcp_reading_time($postId)); ?></li>
            <?php if ($category) : ?>
              <li><?php echo esc_html($category->name); ?></li>
            <?php endif; ?>
            <li><?php esc_html_e('Projet céramique', 'plan-ceramique-premium'); ?></li>
          </ul>
          <a class="button button--ghost" href="<?php echo esc_url(home_url('/#devis')); ?>"><?php esc_html_e('Demander un devis', 'plan-ceramique-premium'); ?></a>
        </aside>

        <div class="pcstudio-article-body entry-content">
          <?php the_content(); ?>
        </div>
      </div>

      <nav class="pcstudio-article-nav" aria-label="<?php esc_attr_e('Navigation entre articles', 'plan-ceramique-premium'); ?>">
        <div><?php previous_post_link('%link', __('Article précédent', 'plan-ceramique-premium')); ?></div>
        <div><?php next_post_link('%link', __('Article suivant', 'plan-ceramique-premium')); ?></div>
      </nav>

      <?php if ($related->have_posts()) : ?>
        <section class="pcstudio-section pcstudio-related-posts">
          <div class="pcstudio-section__heading">
            <p class="pcstudio-label"><?php esc_html_e('À lire aussi', 'plan-ceramique-premium'); ?></p>
            <h2><?php esc_html_e('Continuer à préparer votre projet.', 'plan-ceramique-premium'); ?></h2>
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
          <p class="pcstudio-label"><?php esc_html_e('Projet sur mesure', 'plan-ceramique-premium'); ?></p>
          <h2><?php esc_html_e('Besoin de traduire ces idées dans votre cuisine ?', 'plan-ceramique-premium'); ?></h2>
          <p><?php esc_html_e('Décrivez votre espace, vos dimensions et l’ambiance souhaitée. Nous vous aidons à cadrer une surface céramique cohérente avec votre projet.', 'plan-ceramique-premium'); ?></p>
        </div>
        <a class="button" href="<?php echo esc_url(home_url('/#devis')); ?>"><?php esc_html_e('Préparer mon devis', 'plan-ceramique-premium'); ?></a>
      </section>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
