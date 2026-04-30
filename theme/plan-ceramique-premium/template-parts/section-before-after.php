<section class="pcstudio-section pcstudio-before-after reveal-up">
  <div class="pcstudio-section__heading">
    <p class="pcstudio-label"><?php esc_html_e('Avant / Après', 'plan-ceramique-premium'); ?></p>
    <h2><?php esc_html_e('Une surface qui transforme immédiatement la perception.', 'plan-ceramique-premium'); ?></h2>
  </div>
  <div class="pcstudio-before-after__slider" data-before-after>
    <img class="pcstudio-before-after__after" src="<?php echo esc_url(pcp_asset_img('kitchen-warm-ceramique.jpg')); ?>" loading="lazy" width="1100" height="680" alt="<?php esc_attr_e('Cuisine après rénovation avec surface céramique claire', 'plan-ceramique-premium'); ?>">
    <div class="pcstudio-before-after__before" style="--before-width: 48%;">
      <img src="<?php echo esc_url(pcp_asset_img('texture-concrete-light.jpg')); ?>" loading="lazy" width="1100" height="680" alt="<?php esc_attr_e('Ambiance avant transformation avec surface neutre', 'plan-ceramique-premium'); ?>">
    </div>
    <span class="pcstudio-before-after__label pcstudio-before-after__label--before"><?php esc_html_e('Avant', 'plan-ceramique-premium'); ?></span>
    <span class="pcstudio-before-after__label pcstudio-before-after__label--after"><?php esc_html_e('Après', 'plan-ceramique-premium'); ?></span>
    <input type="range" min="0" max="100" value="48" aria-label="<?php esc_attr_e('Comparer avant et après', 'plan-ceramique-premium'); ?>" data-before-after-range>
  </div>
</section>
