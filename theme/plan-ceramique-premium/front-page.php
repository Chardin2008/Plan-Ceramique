<?php
$content = get_post_field('post_content', get_queried_object_id());

if (!function_exists('pcp_home_has_blocks') || !pcp_home_has_blocks($content)) {
    get_template_part('template-parts/front-page', 'legacy');

    return;
}

get_header();

if (have_posts()) {
    the_post();
}

echo pcp_home_render_loader(get_queried_object_id());
?>
<main id="main-content" class="site-main pcstudio">
    <?php the_content(); ?>

    <div class="pcstudio-floating-cta" data-floating-cta>
        <a class="button" href="#devis"><?php esc_html_e('Demander un devis', 'plan-ceramique-premium'); ?></a>
        <a class="pcstudio-top-link" href="#accueil" aria-label="<?php esc_attr_e('Retour en haut', 'plan-ceramique-premium'); ?>">&uarr;</a>
    </div>
</main>
<?php
get_footer();
