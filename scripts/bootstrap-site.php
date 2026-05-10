<?php

if (!defined('ABSPATH')) {
    exit;
}

function pcp_asset_url(string $filename): string
{
    return content_url('themes/plan-ceramique-premium/assets/images/' . ltrim($filename, '/'));
}

function pcp_route(string $path = '/'): string
{
    return home_url($path);
}

function pcp_heading(string $content, int $level = 2, string $className = ''): string
{
    $attrs = ['level' => $level];

    if ($className) {
        $attrs['className'] = $className;
    }

    $json = wp_json_encode($attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $tag = 'h' . $level;
    $classAttr = $className ? ' class="' . esc_attr($className) . '"' : '';

    return sprintf(
        '<!-- wp:heading %1$s --><%2$s%3$s>%4$s</%2$s><!-- /wp:heading -->',
        $json,
        $tag,
        $classAttr,
        esc_html($content)
    );
}

function pcp_paragraph(string $content, string $className = ''): string
{
    $attrs = [];

    if ($className) {
        $attrs['className'] = $className;
    }

    $json = $attrs ? ' ' . wp_json_encode($attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';
    $classAttr = $className ? ' class="' . esc_attr($className) . '"' : '';

    return sprintf(
        '<!-- wp:paragraph%1$s --><p%2$s>%3$s</p><!-- /wp:paragraph -->',
        $json,
        $classAttr,
        esc_html($content)
    );
}

function pcp_list(array $items): string
{
    $listItems = '';

    foreach ($items as $item) {
        $listItems .= '<li>' . esc_html($item) . '</li>';
    }

    return '<!-- wp:list --><ul class="wp-block-list">' . $listItems . '</ul><!-- /wp:list -->';
}

function pcp_image(string $src, string $alt, string $className = ''): string
{
    $attrs = [
        'sizeSlug' => 'full',
        'linkDestination' => 'none',
    ];

    if ($className) {
        $attrs['className'] = $className;
    }

    $json = wp_json_encode($attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $classAttr = 'wp-block-image size-full' . ($className ? ' ' . $className : '');

    return sprintf(
        '<!-- wp:image %1$s --><figure class="%2$s"><img src="%3$s" alt="%4$s" /></figure><!-- /wp:image -->',
        $json,
        esc_attr($classAttr),
        esc_url($src),
        esc_attr($alt)
    );
}

function pcp_buttons(array $buttons): string
{
    $inner = '';

    foreach ($buttons as $button) {
        $className = $button['class'] ?? '';
        $buttonClass = 'wp-block-button' . ($className ? ' ' . $className : '');
        $attrs = $className ? ' ' . wp_json_encode(['className' => $className], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';

        $inner .= sprintf(
            '<!-- wp:button%1$s --><div class="%2$s"><a class="wp-block-button__link wp-element-button" href="%3$s">%4$s</a></div><!-- /wp:button -->',
            $attrs,
            esc_attr($buttonClass),
            esc_url($button['url']),
            esc_html($button['label'])
        );
    }

    return '<!-- wp:buttons --><div class="wp-block-buttons">' . $inner . '</div><!-- /wp:buttons -->';
}

function pcp_group(string $inner, string $className = '', string $align = 'wide'): string
{
    $attrs = ['align' => $align];

    if ($className) {
        $attrs['className'] = $className;
    }

    $json = wp_json_encode($attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $classes = 'wp-block-group align' . $align . ($className ? ' ' . $className : '');

    return sprintf(
        '<!-- wp:group %1$s --><div class="%2$s"><div class="wp-block-group__inner-container">%3$s</div></div><!-- /wp:group -->',
        $json,
        esc_attr($classes),
        $inner
    );
}

function pcp_columns(array $columns, string $className = '', string $align = 'wide'): string
{
    $attrs = ['align' => $align];

    if ($className) {
        $attrs['className'] = $className;
    }

    $json = wp_json_encode($attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $classes = 'wp-block-columns align' . $align . ($className ? ' ' . $className : '');
    $inner = '';

    foreach ($columns as $column) {
        $inner .= pcp_column($column['content'], $column['class'] ?? '');
    }

    return sprintf(
        '<!-- wp:columns %1$s --><div class="%2$s">%3$s</div><!-- /wp:columns -->',
        $json,
        esc_attr($classes),
        $inner
    );
}

function pcp_column(string $inner, string $className = ''): string
{
    $attrs = [];

    if ($className) {
        $attrs['className'] = $className;
    }

    $json = $attrs ? ' ' . wp_json_encode($attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';
    $classes = 'wp-block-column' . ($className ? ' ' . $className : '');

    return sprintf(
        '<!-- wp:column%1$s --><div class="%2$s">%3$s</div><!-- /wp:column -->',
        $json,
        esc_attr($classes),
        $inner
    );
}

function pcp_card_column(string $eyebrow, string $title, string $body, string $extraClass = ''): array
{
    $content = pcp_paragraph($eyebrow, 'pcp-card__eyebrow');
    $content .= pcp_heading($title, 3);
    $content .= pcp_paragraph($body);

    return [
        'class' => 'pcp-card' . ($extraClass ? ' ' . $extraClass : ''),
        'content' => $content,
    ];
}

function pcp_article_content(array $sections, array $takeaways, string $ctaText, string $ctaUrl): string
{
    $content = '';

    foreach ($sections as $section) {
        $inner = pcp_heading($section['title']);

        foreach ($section['paragraphs'] as $paragraph) {
            $inner .= pcp_paragraph($paragraph);
        }

        $content .= pcp_group($inner, 'pcp-article-section');
    }

    $content .= pcp_group(
        pcp_heading('À retenir') .
        pcp_list($takeaways),
        'pcp-article-section pcp-article-takeaways'
    );

    $content .= pcp_group(
        pcp_heading('Passer au projet concret') .
        pcp_paragraph('Si ces repères correspondent à votre cuisine, la suite logique consiste à rassembler vos dimensions, vos photos et vos préférences de finition. Nous pourrons ensuite transformer ces informations en demande de devis plus précise pour un plan de travail en céramique sur mesure.') .
        pcp_paragraph('Cette étape permet aussi de vérifier la cohérence entre le rendu souhaité et les contraintes réelles : accès de livraison, configuration des meubles, découpes techniques, choix des chants et niveau de finition attendu. Plus ces éléments sont clairs au départ, plus l’échange devient fluide et plus le projet peut avancer avec méthode.') .
        pcp_buttons([['label' => $ctaText, 'url' => $ctaUrl]]),
        'pcp-article-section pcp-article-cta'
    );

    return $content;
}

function pcp_upsert_post(array $args): int
{
    $existing = get_page_by_path($args['post_name'], OBJECT, $args['post_type']);

    if ($existing) {
        $args['ID'] = $existing->ID;
        wp_update_post($args);

        return (int) $existing->ID;
    }

    return (int) wp_insert_post($args);
}

function pcp_set_yoast_meta(int $postId, string $title, string $description, string $focusKeyword = '', string $socialImage = ''): void
{
    $defaultSocialImage = content_url('themes/plan-ceramique-premium/assets/img/og-plan-ceramique.jpg');
    $socialImage = $socialImage ?: $defaultSocialImage;

    update_post_meta($postId, '_yoast_wpseo_title', $title);
    update_post_meta($postId, '_yoast_wpseo_metadesc', $description);
    update_post_meta($postId, '_yoast_wpseo_opengraph-title', $title);
    update_post_meta($postId, '_yoast_wpseo_opengraph-description', $description);
    update_post_meta($postId, '_yoast_wpseo_opengraph-image', $socialImage);
    update_post_meta($postId, '_yoast_wpseo_twitter-title', $title);
    update_post_meta($postId, '_yoast_wpseo_twitter-description', $description);
    update_post_meta($postId, '_yoast_wpseo_twitter-image', $socialImage);

    if ($focusKeyword) {
        update_post_meta($postId, '_yoast_wpseo_focuskw', $focusKeyword);
    }
}

function pcp_configure_yoast_defaults(): void
{
    $wpseo = get_option('wpseo', []);
    $wpseo = is_array($wpseo) ? $wpseo : [];
    update_option(
        'wpseo',
        array_merge(
            $wpseo,
            [
                'company_or_person' => 'company',
                'company_name' => 'Plan Céramique Studio',
                'website_name' => 'Plan Céramique Studio',
                'alternate_website_name' => 'Plan Céramique',
                'enable_xml_sitemap' => true,
                'opengraph' => true,
                'twitter' => true,
            ]
        )
    );

    $wpseoTitles = get_option('wpseo_titles', []);
    $wpseoTitles = is_array($wpseoTitles) ? $wpseoTitles : [];
    update_option(
        'wpseo_titles',
        array_merge(
            $wpseoTitles,
            [
                'separator' => 'sc-dash',
                'title-home-wpseo' => 'Plan de travail en céramique sur mesure | Plan Céramique Studio',
                'metadesc-home-wpseo' => 'Plan de travail en céramique sur mesure pour cuisine premium : conseil, fabrication, livraison et pose partout en France.',
                'title-page' => '%%title%% | Plan Céramique Studio',
                'metadesc-page' => '%%excerpt%%',
                'title-post' => '%%title%% | Plan Céramique Studio',
                'metadesc-post' => '%%excerpt%%',
                'breadcrumbs-enable' => true,
            ]
        )
    );

    $wpseoSocial = get_option('wpseo_social', []);
    $wpseoSocial = is_array($wpseoSocial) ? $wpseoSocial : [];
    update_option(
        'wpseo_social',
        array_merge(
            $wpseoSocial,
            [
                'og_default_image' => content_url('themes/plan-ceramique-premium/assets/img/og-plan-ceramique.jpg'),
                'twitter_card_type' => 'summary_large_image',
            ]
        )
    );
}

function pcp_default_cf7_meta(string $key, $fallback)
{
    $existingForms = get_posts(
        [
            'post_type' => 'wpcf7_contact_form',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'orderby' => 'ID',
            'order' => 'ASC',
            'fields' => 'ids',
        ]
    );

    if ($existingForms) {
        $value = get_post_meta((int) $existingForms[0], $key, true);

        if (!empty($value)) {
            return $value;
        }
    }

    return $fallback;
}

function pcp_upsert_cf7_form(string $slug, string $title, string $formMarkup, array $mailConfig): int
{
    $postId = pcp_upsert_post(
        [
            'post_type' => 'wpcf7_contact_form',
            'post_status' => 'publish',
            'post_title' => $title,
            'post_name' => $slug,
            'post_content' => '',
        ]
    );

    $defaultMail = pcp_default_cf7_meta(
        '_mail',
        [
            'active' => 1,
            'subject' => '[' . get_bloginfo('name') . '] Nouveau message',
            'sender' => 'Plan Céramique Studio <wordpress@localhost>',
            'body' => '',
            'recipient' => getenv('PCP_FORM_RECIPIENT') ?: 'hello@mpc.contact',
            'additional_headers' => '',
            'attachments' => '',
            'use_html' => 0,
            'exclude_blank' => 0,
        ]
    );

    $defaultMessages = pcp_default_cf7_meta(
        '_messages',
        [
            'mail_sent_ok' => 'Merci, votre message a bien été envoyé.',
            'mail_sent_ng' => 'Une erreur est survenue lors de l’envoi. Merci de réessayer.',
            'validation_error' => 'Merci de vérifier les champs du formulaire.',
            'accept_terms' => 'Merci d’accepter les conditions pour continuer.',
            'invalid_required' => 'Ce champ est obligatoire.',
            'upload_failed' => 'Le fichier n’a pas pu être téléchargé.',
            'upload_file_too_large' => 'Le fichier est trop volumineux.',
            'upload_file_type_invalid' => 'Le type de fichier n’est pas autorisé.',
        ]
    );

    update_post_meta($postId, '_locale', 'fr_FR');
    update_post_meta($postId, '_form', $formMarkup);
    update_post_meta($postId, '_mail', array_merge($defaultMail, $mailConfig));
    update_post_meta($postId, '_mail_2', ['active' => 0]);
    update_post_meta($postId, '_messages', $defaultMessages);
    update_post_meta($postId, '_additional_settings', '');

    return $postId;
}

function pcp_form_shortcode(string $type): string
{
    return '<!-- wp:shortcode -->[pcp_contact_form type="' . esc_attr($type) . '"]<!-- /wp:shortcode -->';
}

$visibleEmail = getenv('VISIBLE_CONTACT_EMAIL') ?: 'contact@plan-travail-ceramique.fr';
$serviceArea = getenv('SERVICE_AREA_TEXT') ?: 'Intervention et livraison partout en France.';

update_option(
    'pcp_theme_settings',
    [
        'visible_email' => $visibleEmail,
        'service_area' => $serviceArea,
        'google_site_verification' => '',
    ]
);

update_option('default_ping_status', 'closed');
update_option('default_comment_status', 'closed');
update_option('show_avatars', '0');

$contactFormMarkup = <<<'CF7'
<label>Nom
  [text* your-name autocomplete:name]
</label>

<label>Email
  [email* your-email autocomplete:email]
</label>

<label>Téléphone
  [tel your-phone autocomplete:tel]
</label>

<label>Votre message
  [textarea* your-message]
</label>

[submit "Envoyer le message"]
CF7;

$contactMail = [
    'subject' => '[Plan Céramique Studio] Nouveau message de contact',
    'sender' => 'Plan Céramique Studio <wordpress@localhost>',
    'recipient' => getenv('PCP_FORM_RECIPIENT') ?: 'hello@mpc.contact',
    'body' => "Nom : [your-name]\nEmail : [your-email]\nTéléphone : [your-phone]\n\nMessage :\n[your-message]",
    'additional_headers' => 'Reply-To: [your-email]',
    'attachments' => '',
];

$quoteFormMarkup = <<<'CF7'
<label>Nom
  [text* your-last-name autocomplete:family-name]
</label>

<label>Prénom
  [text* your-first-name autocomplete:given-name]
</label>

<label>Email
  [email* your-email autocomplete:email]
</label>

<label>Téléphone
  [tel your-phone autocomplete:tel]
</label>

<label>Ville
  [text your-city]
</label>

<label>Type de projet
  [select* project-type "Plan de travail de cuisine" "Îlot central" "Crédence assortie" "Rénovation de cuisine" "Projet professionnel"]
</label>

<label>Matériau souhaité
  [select* desired-material "Céramique aspect marbre" "Céramique pleine masse" "Effet pierre naturelle" "Effet béton minéral" "À définir avec un conseiller"]
</label>

<label>Dimensions approximatives
  [text project-dimensions placeholder "Exemple : 320 x 65 cm + îlot 180 x 90 cm"]
</label>

<label>Message
  [textarea* your-message placeholder "Décrivez votre cuisine, vos contraintes et le niveau de finition attendu."]
</label>

<label>Plan ou photo
  [file your-file limit:10mb filetypes:jpg|jpeg|png|pdf]
</label>

[submit "Recevoir mon étude de projet"]
CF7;

$quoteMail = [
    'subject' => '[Plan Céramique Studio] Nouvelle demande de devis',
    'sender' => 'Plan Céramique Studio <wordpress@localhost>',
    'recipient' => getenv('PCP_FORM_RECIPIENT') ?: 'hello@mpc.contact',
    'body' => "Nom : [your-last-name]\nPrénom : [your-first-name]\nEmail : [your-email]\nTéléphone : [your-phone]\nVille : [your-city]\nType de projet : [project-type]\nMatériau souhaité : [desired-material]\nDimensions approximatives : [project-dimensions]\n\nMessage :\n[your-message]",
    'additional_headers' => 'Reply-To: [your-email]',
    'attachments' => '[your-file]',
];

$contactFormId = pcp_upsert_cf7_form('formulaire-contact', 'Formulaire de contact', $contactFormMarkup, $contactMail);
$quoteFormId = pcp_upsert_cf7_form('formulaire-devis', 'Formulaire de demande de devis', $quoteFormMarkup, $quoteMail);

$heroSection = pcp_group(
    pcp_columns(
        [
            [
                'content' =>
                    pcp_paragraph('Plan de travail en céramique sur mesure', 'pcp-card__eyebrow') .
                    pcp_heading('Des cuisines durables, élégantes et pensées pour le quotidien.', 1) .
                    pcp_paragraph('Conseil, choix des matériaux céramiques, prise de mesure, fabrication, livraison et pose : la page d’accueil résume ici tout le parcours du projet.') .
                    pcp_buttons(
                        [
                            ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/')],
                            ['label' => 'Voir nos réalisations', 'url' => pcp_route('/realisations/'), 'class' => 'is-style-outline'],
                        ]
                    ) .
                    pcp_columns(
                        [
                            ['content' => pcp_heading('Chaleur', 3) . pcp_paragraph('Surface conçue pour rester stable face aux usages intenses en cuisine.'), 'class' => 'pcp-kpi'],
                            ['content' => pcp_heading('Rayures', 3) . pcp_paragraph('Résistance adaptée aux gestes du quotidien et aux préparations répétées.'), 'class' => 'pcp-kpi'],
                            ['content' => pcp_heading('Entretien', 3) . pcp_paragraph('Nettoyage simple pour garder un plan de travail net et rassurant.'), 'class' => 'pcp-kpi'],
                        ],
                        'pcp-kpis',
                        'wide'
                    ),
            ],
            [
                'content' => pcp_image(
                    pcp_asset_url('hero-ceramique.svg'),
                    'Cuisine premium avec plan de travail en céramique sur mesure'
                ),
            ],
        ],
        'pcp-hero__grid',
        'wide'
    ),
    'pcp-section pcp-section--hero',
    'full'
);

$servicesSection = pcp_group(
    pcp_columns(
        [
            [
                'content' =>
                    pcp_heading('Nos services') .
                    pcp_paragraph('De la première discussion jusqu’à la pose, la page services détaille chaque étape qui sécurise un projet de plan de travail en céramique.') .
                    pcp_list(
                        [
                            'Conseil sur les usages de la cuisine et les contraintes de chaleur.',
                            'Prise de mesure avant fabrication sur mesure.',
                            'Livraison organisée et pose soignée partout en France.',
                        ]
                    ) .
                    pcp_buttons([['label' => 'Découvrir les services', 'url' => pcp_route('/nos-services/')]]),
            ],
            [
                'content' => pcp_image(
                    pcp_asset_url('services-mesure.svg'),
                    'Conseil et prise de mesure pour plan de travail en céramique'
                ),
            ],
        ]
    ),
    'pcp-section'
);

$materialsSection = pcp_group(
    pcp_columns(
        [
            [
                'content' => pcp_image(
                    pcp_asset_url('materiaux-ceramique.svg'),
                    'Gros plan sur une surface en céramique pour plan de travail'
                ),
            ],
            [
                'content' =>
                    pcp_heading('Matériaux') .
                    pcp_paragraph('La page matériaux explique les différences de finitions, l’entretien, la résistance aux rayures et les usages les plus adaptés en cuisine sur mesure.') .
                    pcp_list(
                        [
                            'Surface non poreuse et entretien rapide.',
                            'Compatibilité avec des finitions pierre, marbre ou béton.',
                            'Repères clairs pour choisir selon le style de cuisine.',
                        ]
                    ) .
                    pcp_buttons([['label' => 'Voir les matériaux', 'url' => pcp_route('/materiaux/')]]),
            ],
        ]
    ),
    'pcp-section pcp-section--soft'
);

$collectionsSection = pcp_group(
    pcp_columns(
        [
            [
                'content' =>
                    pcp_heading('Collections') .
                    pcp_paragraph('La page collections présente les harmonies de couleurs, les rendus mats ou texturés, et les associations possibles avec les façades de cuisine.') .
                    pcp_buttons([['label' => 'Explorer les collections', 'url' => pcp_route('/collections/')]]),
            ],
            [
                'content' => pcp_image(
                    pcp_asset_url('collections-finitions.svg'),
                    'Échantillons de couleurs et finitions pour plan de travail en céramique'
                ),
            ],
        ]
    ),
    'pcp-section'
);

$projectsSection = pcp_group(
    pcp_columns(
        [
            [
                'content' => pcp_image(
                    pcp_asset_url('realisations-cuisine.svg'),
                    'Cuisine terminée avec plan de travail en céramique installé'
                ),
            ],
            [
                'content' =>
                    pcp_heading('Réalisations') .
                    pcp_paragraph('La page réalisations sert de vitrine : cuisines terminées, détails de finitions, crédences assorties et îlots en céramique sur mesure.') .
                    pcp_buttons([['label' => 'Voir les réalisations', 'url' => pcp_route('/realisations/')]]),
            ],
        ]
    ),
    'pcp-section pcp-section--soft'
);

$advantagesSection = pcp_group(
    pcp_heading('Avantages de la céramique') .
    pcp_paragraph('Cette section résume les bénéfices les plus recherchés pour une cuisine active : stabilité, résistance et nettoyage simple.') .
    pcp_columns(
        [
            pcp_card_column('Résistance à la chaleur', 'Cuisine plus sereine', 'La céramique reste un matériau rassurant pour les zones de préparation et les usages intensifs.'),
            pcp_card_column('Résistance aux rayures', 'Surface pensée pour durer', 'Le matériau accompagne les gestes du quotidien avec un excellent niveau de tenue.'),
            pcp_card_column('Entretien facile', 'Nettoyage rapide', 'Une routine simple suffit pour garder une surface nette et agréable à vivre.'),
        ],
        'pcp-card-grid',
        'wide'
    ) .
    pcp_group(
        pcp_image(
            pcp_asset_url('advantages-performance.svg'),
            'Illustration des avantages d’un plan de travail en céramique'
        ),
        '',
        'wide'
    ),
    'pcp-section'
);

$processSection = pcp_group(
    pcp_heading('Processus') .
    pcp_paragraph('Le site présente un parcours clair pour éviter les zones d’ombre entre la prise d’information, la fabrication et la pose.') .
    pcp_columns(
        [
            ['class' => 'pcp-process__step', 'content' => pcp_heading('Échange projet', 3) . pcp_paragraph('Nous cadrons votre cuisine, vos dimensions et l’usage attendu.')],
            ['class' => 'pcp-process__step', 'content' => pcp_heading('Choix matière', 3) . pcp_paragraph('Nous orientons vers la bonne épaisseur, finition et couleur de céramique.')],
            ['class' => 'pcp-process__step', 'content' => pcp_heading('Fabrication', 3) . pcp_paragraph('Le plan est préparé sur mesure selon les relevés et les découpes utiles.')],
            ['class' => 'pcp-process__step', 'content' => pcp_heading('Livraison et pose', 3) . pcp_paragraph('La mise en place finale est coordonnée pour un rendu propre et stable.')],
        ],
        'pcp-process',
        'wide'
    ) .
    pcp_group(
        pcp_image(
            pcp_asset_url('process-pose.svg'),
            'Illustration du processus fabrication livraison et pose d’un plan de travail en céramique'
        ),
        '',
        'wide'
    ),
    'pcp-section pcp-section--soft'
);

$blogSection = pcp_group(
    pcp_columns(
        [
            [
                'content' =>
                    pcp_heading('Blog') .
                    pcp_paragraph('Le blog relie le site au référencement naturel avec des sujets concrets : entretien, prise de mesure, choix des finitions et conseils d’usage.') .
                    pcp_buttons([['label' => 'Lire le blog', 'url' => pcp_route('/blog/')]]),
            ],
            [
                'content' => pcp_image(
                    pcp_asset_url('blog-ceramique.svg'),
                    'Visuel éditorial pour articles sur les plans de travail en céramique'
                ),
            ],
        ]
    ),
    'pcp-section'
);

$quoteSection = pcp_group(
    pcp_columns(
        [
            [
                'content' => pcp_image(
                    pcp_asset_url('devis-texture.svg'),
                    'Texture rassurante liée à une demande de devis pour plan de travail en céramique'
                ),
            ],
            [
                'content' =>
                    pcp_heading('Demande de devis') .
                    pcp_paragraph('Le formulaire dédié recueille les dimensions approximatives, le matériau souhaité, la ville et un éventuel plan de cuisine pour lancer une étude sérieuse.') .
                    pcp_buttons([['label' => 'Accéder au formulaire devis', 'url' => pcp_route('/demander-un-devis/')]]),
            ],
        ]
    ),
    'pcp-section pcp-section--soft'
);

$homeContent = $heroSection .
    $servicesSection .
    $materialsSection .
    $collectionsSection .
    $projectsSection .
    $advantagesSection .
    $processSection .
    $blogSection .
    $quoteSection;

$servicesContent = pcp_group(
    pcp_columns(
        [
            ['content' => pcp_heading('Un accompagnement complet') . pcp_paragraph('Nous structurons chaque projet de plan de travail en céramique avec conseil, prise de mesure, fabrication, livraison et pose pour garder une chaîne claire de bout en bout.') . pcp_list(['Étude technique du besoin et du rythme de vie en cuisine.', 'Préparation des découpes évier, plaque et retombées.', 'Coordination pose et finitions en cohérence avec la cuisine sur mesure.'])],
            ['content' => pcp_image(pcp_asset_url('services-mesure.svg'), 'Prise de mesure et conseil pour plan de travail en céramique')],
        ]
    ),
    'pcp-section'
) .
pcp_group(
    pcp_columns(
        [
            pcp_card_column('Conseil', 'Sélection guidée', 'Choix du matériau céramique selon l’esthétique, la résistance et l’entretien.'),
            pcp_card_column('Mesures', 'Relevés précis', 'Validation des dimensions avant la fabrication du plan de travail.'),
            pcp_card_column('Pose', 'Mise en œuvre propre', 'Livraison coordonnée et pose pour un rendu stable et durable.'),
        ],
        'pcp-card-grid',
        'wide'
    ),
    'pcp-section pcp-section--soft'
) .
pcp_group(
    pcp_heading('Un projet cadré dès le départ') .
    pcp_paragraph('Pour poursuivre, consultez aussi la page matériaux ou demandez directement un devis détaillé.') .
    pcp_buttons([
        ['label' => 'Voir les matériaux', 'url' => pcp_route('/materiaux/')],
        ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/'), 'class' => 'is-style-outline'],
    ]),
    'pcp-section'
);

$materialsContent = pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image(pcp_asset_url('materiaux-ceramique.svg'), 'Texture de matériau céramique pour cuisine sur mesure')],
            ['content' => pcp_heading('Choisir la bonne surface céramique') . pcp_paragraph('Cette page pose les bases : effet visuel, résistance aux rayures, entretien facile, épaisseur et adaptation aux usages d’une cuisine familiale ou premium.') . pcp_list(['Effets pierre, marbre, béton ou tons unis.', 'Surface dense pensée pour la chaleur et les usages intensifs.', 'Repères simples pour anticiper l’entretien au quotidien.'])],
        ]
    ),
    'pcp-section'
) .
pcp_group(
    pcp_columns(
        [
            pcp_card_column('Entretien', 'Nettoyage facile', 'Un chiffon doux et des gestes simples suffisent dans la majorité des cas.'),
            pcp_card_column('Résistance', 'Usage cuisine', 'La céramique est adaptée aux zones de préparation et aux cuisines actives.'),
            pcp_card_column('Style', 'Finitions variées', 'Les collections permettent de construire des univers très contemporains ou plus naturels.'),
        ],
        'pcp-card-grid',
        'wide'
    ),
    'pcp-section pcp-section--soft'
);

$collectionsContent = pcp_group(
    pcp_columns(
        [
            ['content' => pcp_heading('Couleurs et finitions') . pcp_paragraph('Les collections donnent une vision plus esthétique du projet : nuances claires, marbres plus marqués, effets minéraux sobres ou tonalités profondes.') . pcp_list(['Collections lumineuses pour petites cuisines.', 'Finitions plus contrastées pour îlots ou cuisines ouvertes.', 'Associations possibles avec façades bois, mates ou satinées.'])],
            ['content' => pcp_image(pcp_asset_url('collections-finitions.svg'), 'Collections de couleurs et finitions pour céramique')],
        ]
    ),
    'pcp-section'
) .
pcp_group(
    pcp_heading('Préparer une sélection utile') .
    pcp_paragraph('Le but est d’arriver rapidement à une palette cohérente avant la fabrication. Vous pouvez ensuite passer aux réalisations pour voir un rendu plus concret.') .
    pcp_buttons([
        ['label' => 'Voir les réalisations', 'url' => pcp_route('/realisations/')],
        ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/'), 'class' => 'is-style-outline'],
    ]),
    'pcp-section pcp-section--soft'
);

$projectsContent = pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image(pcp_asset_url('realisations-cuisine.svg'), 'Réalisation de cuisine avec plan de travail en céramique')],
            ['content' => pcp_heading('Des réalisations lisibles et crédibles') . pcp_paragraph('Cette page accueille des exemples de cuisines terminées, avec mise en avant des matériaux choisis, de la pose et des détails de finition autour des points techniques.') . pcp_list(['Îlots centraux avec céramique grand format.', 'Plans linéaires et crédences assorties.', 'Finitions pensées pour l’entretien et l’usage quotidien.'])],
        ]
    ),
    'pcp-section'
) .
pcp_group(
    pcp_columns(
        [
            pcp_card_column('Projet 1', 'Cuisine familiale', 'Plan clair, retombée discrète et entretien rapide.'),
            pcp_card_column('Projet 2', 'Îlot sculptural', 'Finition minérale et lignes franches pour cuisine ouverte.'),
            pcp_card_column('Projet 3', 'Rénovation sobre', 'Association façade bois et surface céramique texturée.'),
        ],
        'pcp-card-grid',
        'wide'
    ),
    'pcp-section pcp-section--soft'
);

$blogContent = pcp_group(
    pcp_paragraph('Guide projet', 'pcp-section-intro') .
    pcp_heading('Des conseils courts pour avancer avec méthode') .
    pcp_paragraph('Le blog rassemble les repères utiles avant de demander un devis : choisir la matière, préparer les mesures, comparer les finitions, anticiper un îlot, organiser la pose et garder une surface facile à vivre.') .
    pcp_columns(
        [
            pcp_card_column('Matière', 'Choisir avec recul', 'Comparer la céramique, les finitions et les usages réels de la cuisine.'),
            pcp_card_column('Mesures', 'Préparer le projet', 'Rassembler dimensions, photos, découpes et contraintes avant l’étude.'),
            pcp_card_column('Pose', 'Anticiper la suite', 'Comprendre les accès, les supports, les validations et le jour de pose.'),
        ],
        'pcp-card-grid',
        'wide'
    ),
    'pcp-section'
);

$contactContent = pcp_group(
    pcp_columns(
        [
            ['content' => pcp_heading('Parler de votre projet de cuisine') . pcp_paragraph('Le formulaire de contact sert aux premiers échanges : question technique, demande d’information sur un matériau, délai de fabrication ou zone de livraison.') . pcp_list(['Réponse orientée plan de travail en céramique.', 'Échanges préparatoires avant devis.', 'Intervention et livraison partout en France.'])],
            ['content' => pcp_image(pcp_asset_url('services-mesure.svg'), 'Contact pour projet de plan de travail en céramique')],
        ]
    ),
    'pcp-section'
) .
pcp_group(
    pcp_heading('Formulaire de contact') .
    pcp_paragraph('Décrivez votre besoin, même brièvement. Nous pourrons ensuite vous orienter vers la bonne collection, le bon matériau ou une demande de devis plus complète.') .
    pcp_form_shortcode('contact'),
    'pcp-section pcp-section--soft'
);

$quotePageContent = pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image(pcp_asset_url('devis-texture.svg'), 'Demande de devis pour plan de travail en céramique')],
            ['content' => pcp_heading('Préparer une étude sérieuse') . pcp_paragraph('Ce formulaire permet de centraliser les informations utiles : ville, type de projet, matériau souhaité, dimensions approximatives et éventuel plan ou photo de la cuisine.') . pcp_list(['Projet cuisine ou îlot central.', 'Choix matière déjà défini ou à cadrer.', 'Fichier joint possible pour accélérer l’analyse.'])],
        ]
    ),
    'pcp-section'
) .
pcp_group(
    pcp_heading('Formulaire de demande de devis') .
    pcp_paragraph('Le formulaire reste volontairement clair et rassurant pour éviter la surcharge, tout en collectant l’essentiel pour un chiffrage utile.') .
    pcp_form_shortcode('quote'),
    'pcp-section pcp-section--soft'
);

$homePageId = pcp_upsert_post(
    [
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => 'Accueil',
        'post_name' => 'accueil',
        'post_excerpt' => 'Plan de travail en céramique sur mesure, fabrication, livraison et pose partout en France.',
        'post_content' => $homeContent,
    ]
);

$pages = [
    [
        'title' => 'Nos services',
        'slug' => 'nos-services',
        'excerpt' => 'Conseil, prise de mesure, fabrication, livraison et pose de plans de travail en céramique.',
        'content' => $servicesContent,
        'seo_title' => 'Nos services de plan de travail en céramique | Plan Céramique Studio',
        'seo_description' => 'Découvrez nos services pour un plan de travail en céramique sur mesure : conseil, fabrication, livraison et pose.',
    ],
    [
        'title' => 'Matériaux',
        'slug' => 'materiaux',
        'excerpt' => 'Comparer les matériaux céramiques pour une cuisine sur mesure durable et facile à entretenir.',
        'content' => $materialsContent,
        'seo_title' => 'Matériaux céramiques pour cuisine sur mesure | Plan Céramique Studio',
        'seo_description' => 'Choisissez le bon matériau céramique pour votre plan de travail : résistance, entretien et finitions.',
    ],
    [
        'title' => 'Collections',
        'slug' => 'collections',
        'excerpt' => 'Explorez les couleurs et finitions céramiques adaptées à votre cuisine.',
        'content' => $collectionsContent,
        'seo_title' => 'Collections et finitions céramiques | Plan Céramique Studio',
        'seo_description' => 'Découvrez les collections de couleurs et finitions pour un plan de travail en céramique sur mesure.',
    ],
    [
        'title' => 'Réalisations',
        'slug' => 'realisations',
        'excerpt' => 'Exemples de cuisines terminées avec plan de travail en céramique et finitions soignées.',
        'content' => $projectsContent,
        'seo_title' => 'Réalisations de cuisines en céramique | Plan Céramique Studio',
        'seo_description' => 'Parcourez nos réalisations de cuisines avec plan de travail en céramique sur mesure.',
    ],
    [
        'title' => 'Blog',
        'slug' => 'blog',
        'excerpt' => 'Articles utiles sur la céramique, l’entretien, la prise de mesure et la pose.',
        'content' => $blogContent,
        'seo_title' => 'Blog plan de travail en céramique | Plan Céramique Studio',
        'seo_description' => 'Conseils pratiques sur la céramique, l’entretien, la fabrication et la pose de plans de travail.',
    ],
    [
        'title' => 'Contact',
        'slug' => 'contact',
        'excerpt' => 'Contactez-nous pour parler de votre projet de plan de travail en céramique.',
        'content' => $contactContent,
        'seo_title' => 'Contact plan de travail céramique | Plan Céramique Studio',
        'seo_description' => 'Contactez Plan Céramique Studio pour votre projet de cuisine sur mesure en céramique.',
    ],
    [
        'title' => 'Demander un devis',
        'slug' => 'demander-un-devis',
        'excerpt' => 'Déposez votre demande de devis pour un plan de travail en céramique sur mesure.',
        'content' => $quotePageContent,
        'seo_title' => 'Demander un devis plan de travail céramique | Plan Céramique Studio',
        'seo_description' => 'Envoyez votre demande de devis pour un plan de travail en céramique avec dimensions, ville et fichiers.',
    ],
];

$pageIds = [];

pcp_configure_yoast_defaults();

foreach ($pages as $page) {
    $pageId = pcp_upsert_post(
        [
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_title' => $page['title'],
            'post_name' => $page['slug'],
            'post_excerpt' => $page['excerpt'],
            'post_content' => $page['content'],
        ]
    );

    $pageIds[$page['slug']] = $pageId;
    pcp_set_yoast_meta($pageId, $page['seo_title'], $page['seo_description']);
}

update_option('show_on_front', 'page');
update_option('page_on_front', $homePageId);
update_option('page_for_posts', $pageIds['blog']);

pcp_set_yoast_meta(
    $homePageId,
    'Plan de travail en céramique sur mesure | Plan Céramique Studio',
    'Plan de travail en céramique sur mesure pour cuisine premium : conseil, fabrication, livraison et pose partout en France.'
);

$seoFocusKeywords = [
    $homePageId => 'plan de travail en céramique',
    $pageIds['nos-services'] => 'services plan de travail céramique',
    $pageIds['materiaux'] => 'matériaux céramiques cuisine',
    $pageIds['collections'] => 'finitions céramiques cuisine',
    $pageIds['realisations'] => 'réalisations cuisine céramique',
    $pageIds['blog'] => 'blog plan de travail céramique',
    $pageIds['contact'] => 'contact plan de travail céramique',
    $pageIds['demander-un-devis'] => 'devis plan de travail céramique',
];

foreach ($seoFocusKeywords as $postId => $focusKeyword) {
    update_post_meta((int) $postId, '_yoast_wpseo_focuskw', $focusKeyword);
    update_post_meta((int) $postId, '_yoast_wpseo_focuskeywords', wp_json_encode([['keyword' => $focusKeyword, 'score' => 'good']]));
}

$menuId = wp_get_nav_menu_object('Menu principal');
$menuId = $menuId ? (int) $menuId->term_id : (int) wp_create_nav_menu('Menu principal');

$existingItems = wp_get_nav_menu_items($menuId);
if ($existingItems) {
    foreach ($existingItems as $item) {
        wp_delete_post($item->ID, true);
    }
}

$menuOrder = [
    $homePageId,
    $pageIds['nos-services'],
    $pageIds['materiaux'],
    $pageIds['collections'],
    $pageIds['realisations'],
    $pageIds['blog'],
    $pageIds['contact'],
    $pageIds['demander-un-devis'],
];

foreach ($menuOrder as $index => $pageId) {
    wp_update_nav_menu_item(
        $menuId,
        0,
        [
            'menu-item-object-id' => $pageId,
            'menu-item-object' => 'page',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish',
            'menu-item-position' => $index + 1,
        ]
    );
}

$locations = get_theme_mod('nav_menu_locations', []);
$locations['primary'] = $menuId;
set_theme_mod('nav_menu_locations', $locations);

$categoryId = wp_create_category('Conseils céramique');
$categoryId = $categoryId instanceof WP_Error ? get_cat_ID('Conseils céramique') : (int) $categoryId;

$posts = [
    [
        'title' => 'Plan de travail en céramique ou quartz : comment choisir pour une cuisine sur mesure ?',
        'slug' => 'plan-de-travail-ceramique-ou-quartz',
        'excerpt' => 'Comprendre ce que la céramique change vraiment dans une cuisine sur mesure face à d’autres surfaces.',
        'content' =>
            pcp_group(
                pcp_heading('Comparer selon l’usage réel en cuisine') .
                pcp_paragraph('Le choix d’un plan de travail ne se joue pas uniquement sur l’apparence. La résistance à la chaleur, la tenue dans le temps et la facilité d’entretien doivent être relues à partir de votre quotidien.') .
                pcp_paragraph('La céramique convainc souvent sur les cuisines très actives grâce à son comportement stable, sa surface dense et son entretien simple.') .
                pcp_buttons([['label' => 'Voir les matériaux', 'url' => pcp_route('/materiaux/')]]),
                'pcp-section'
            ) .
            pcp_group(
                pcp_heading('Quels critères garder en tête ?') .
                pcp_list(['Fréquence de cuisson et exposition à la chaleur.', 'Besoin de facilité d’entretien au quotidien.', 'Recherche d’une finition minérale, marbre ou pierre.', 'Importance des découpes et de la pose sur mesure.']),
                'pcp-section pcp-section--soft'
            ),
        'seo_title' => 'Céramique ou quartz pour un plan de travail ? | Plan Céramique Studio',
        'seo_description' => 'Comparez la céramique au quartz pour choisir un plan de travail adapté à votre cuisine sur mesure.',
    ],
    [
        'title' => 'Comment prendre les mesures d’un plan de travail en céramique avant fabrication',
        'slug' => 'prendre-les-mesures-plan-de-travail-ceramique',
        'excerpt' => 'Les points à relever avant fabrication pour éviter les erreurs sur un projet de cuisine sur mesure.',
        'content' =>
            pcp_group(
                pcp_heading('Des mesures utiles avant fabrication') .
                pcp_paragraph('Un bon relevé prépare les découpes, les retombées, l’intégration de l’évier et la cohérence avec les meubles. C’est une étape simple en apparence, mais décisive pour la pose.') .
                pcp_list(['Longueurs et profondeurs exactes.', 'Épaisseur visée et chants visibles.', 'Emplacements évier, plaque et prises.', 'Contraintes de livraison et d’accès.']),
                'pcp-section'
            ) .
            pcp_group(
                pcp_paragraph('Si vous souhaitez être guidé avant de lancer la fabrication, la page services explique comment nous cadrons cette étape avec précision.'),
                'pcp-section pcp-section--soft'
            ),
        'seo_title' => 'Prendre les mesures d’un plan de travail céramique | Plan Céramique Studio',
        'seo_description' => 'Les bonnes pratiques pour relever les dimensions d’un plan de travail en céramique avant fabrication.',
    ],
    [
        'title' => 'Entretien facile : garder un plan de travail en céramique propre au quotidien',
        'slug' => 'entretien-plan-de-travail-ceramique',
        'excerpt' => 'Les bons réflexes pour conserver une surface céramique nette, saine et élégante jour après jour.',
        'content' =>
            pcp_group(
                pcp_heading('Une routine d’entretien simple') .
                pcp_paragraph('L’intérêt de la céramique se voit aussi dans la facilité d’entretien : peu de gestes, peu de contraintes et une sensation de propreté durable dans la cuisine.') .
                pcp_list(['Nettoyage doux après préparation.', 'Réaction rapide sur les taches grasses.', 'Accessoires non abrasifs pour le confort d’usage.', 'Vérification ponctuelle des joints et finitions.']),
                'pcp-section'
            ) .
            pcp_group(
                pcp_paragraph('Pour choisir la bonne finition dès le départ, consultez aussi nos collections et matériaux avant votre demande de devis.'),
                'pcp-section pcp-section--soft'
            ),
        'seo_title' => 'Entretien d’un plan de travail en céramique | Plan Céramique Studio',
        'seo_description' => 'Découvrez comment entretenir facilement un plan de travail en céramique au quotidien.',
    ],
    [
        'title' => 'Quelle finition céramique choisir pour une cuisine lumineuse ?',
        'slug' => 'finition-ceramique-cuisine-lumineuse',
        'excerpt' => 'Marbre clair, pierre douce ou béton minéral : les bons repères pour choisir une finition adaptée à la lumière.',
        'content' =>
            pcp_group(
                pcp_heading('Observer la lumière avant la matière') .
                pcp_paragraph('Une finition céramique change selon l’exposition, la couleur des façades, le sol et la crédence. Une cuisine très lumineuse peut accueillir une surface claire pour agrandir visuellement l’espace, ou une finition plus profonde pour créer du contraste.') .
                pcp_paragraph('Avant de choisir, il faut regarder la pièce dans son ensemble : lumière du matin, éclairage du soir, meubles mats ou brillants, teinte du sol et présence éventuelle d’un îlot central.') .
                pcp_buttons([['label' => 'Explorer les collections', 'url' => pcp_route('/collections/')]]),
                'pcp-section'
            ) .
            pcp_group(
                pcp_heading('Les points à comparer') .
                pcp_list(['Effet marbre clair pour une cuisine lumineuse et élégante.', 'Effet pierre pour une ambiance plus naturelle et douce.', 'Effet béton minéral pour un rendu calme et architectural.', 'Veinage plus discret si la surface comporte beaucoup de découpes.']),
                'pcp-section pcp-section--soft'
            ),
        'seo_title' => 'Choisir une finition céramique pour cuisine lumineuse | Plan Céramique Studio',
        'seo_description' => 'Conseils pour choisir une finition céramique adaptée à une cuisine lumineuse et à un projet sur mesure.',
    ],
    [
        'title' => 'Plan de travail avec îlot central : les points à anticiper',
        'slug' => 'plan-travail-ilot-central-ceramique',
        'excerpt' => 'Dimensions, débords, circulation et découpes : les éléments à cadrer pour un îlot central en céramique.',
        'content' =>
            pcp_group(
                pcp_heading('Un îlot central se voit sous tous les angles') .
                pcp_paragraph('Dans une cuisine ouverte, l’îlot central devient souvent la pièce la plus visible. Le plan de travail doit donc être beau, mais aussi juste dans ses proportions, ses débords, ses chants et ses découpes.') .
                pcp_paragraph('La céramique convient très bien à ce type de projet, à condition d’anticiper les usages : préparation, repas, cuisson, évier, prises ou simple zone de rassemblement.') .
                pcp_buttons([['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/')]]),
                'pcp-section'
            ) .
            pcp_group(
                pcp_heading('Informations utiles') .
                pcp_list(['Longueur, profondeur et hauteur de l’îlot.', 'Débords souhaités pour les assises.', 'Présence d’un évier, d’une plaque ou de prises.', 'Accès de livraison et contraintes de manutention.']),
                'pcp-section pcp-section--soft'
            ),
        'seo_title' => 'Îlot central avec plan de travail céramique | Plan Céramique Studio',
        'seo_description' => 'Les points à anticiper pour un îlot central avec plan de travail en céramique sur mesure.',
    ],
    [
        'title' => 'Livraison et pose : préparer un plan de travail en céramique',
        'slug' => 'livraison-pose-plan-travail-ceramique',
        'excerpt' => 'Les bonnes préparations avant la livraison et la pose pour sécuriser un projet de plan de travail en céramique.',
        'content' =>
            pcp_group(
                pcp_heading('Une pose réussie commence avant le jour J') .
                pcp_paragraph('La livraison et la pose demandent une cuisine prête, des meubles stables et des informations claires. Plus les éléments sont vérifiés avant l’intervention, plus le rendu final peut rester propre et précis.') .
                pcp_paragraph('Il faut anticiper les accès, les étages, les couloirs, la protection de la pièce et la présence d’une personne capable de valider les derniers détails sur place.') .
                pcp_buttons([['label' => 'Voir nos services', 'url' => pcp_route('/nos-services/')]]),
                'pcp-section'
            ) .
            pcp_group(
                pcp_heading('Checklist avant intervention') .
                pcp_list(['Meubles posés, fixés et de niveau.', 'Accès dégagé pour la livraison.', 'Emplacements évier et plaque confirmés.', 'Dernières validations disponibles le jour de la pose.']),
                'pcp-section pcp-section--soft'
            ),
        'seo_title' => 'Livraison et pose plan de travail céramique | Plan Céramique Studio',
        'seo_description' => 'Comment préparer la livraison et la pose d’un plan de travail en céramique sur mesure.',
    ],
];

$longPostContent = [
    'plan-de-travail-ceramique-ou-quartz' => pcp_article_content(
        [
            [
                'title' => 'Partir de la vraie vie dans la cuisine',
                'paragraphs' => [
                    'Le choix entre un plan de travail en céramique et une autre surface ne doit pas se limiter à une comparaison de noms. Une cuisine est un espace utilisé tous les jours, parfois rapidement, parfois avec plusieurs personnes autour du même plan. Il faut donc regarder la chaleur, les projections, les découpes, les taches, les plats posés à la sortie du four, mais aussi la manière dont la surface vieillit visuellement.',
                    'La céramique répond très bien aux projets où l’on cherche une surface minérale, stable et simple à entretenir. Elle garde une présence élégante sans demander une attention permanente. Le quartz peut rester intéressant dans certaines cuisines, mais il impose de bien vérifier les contraintes de chaleur et les limites d’usage. Pour un projet premium, le bon choix est celui qui correspond à votre rythme réel, pas seulement à une photo d’inspiration.',
                ],
            ],
            [
                'title' => 'Comparer la résistance, l’entretien et le rendu',
                'paragraphs' => [
                    'La céramique se distingue par sa densité, sa résistance aux rayures du quotidien et sa très bonne tenue face aux usages intenses. Cela ne signifie pas qu’il faut traiter le plan de travail sans soin, mais que la matière rassure dans une cuisine active. Pour une famille, un îlot central ou une zone de préparation fréquente, cette stabilité apporte un vrai confort.',
                    'L’entretien est aussi un critère important. Une surface facile à nettoyer permet de garder une cuisine nette sans multiplier les produits. Les finitions mates, satinées ou effet pierre doivent être choisies selon la lumière et les habitudes. Un rendu très clair agrandit l’espace, tandis qu’un décor plus marqué donne du caractère. Dans tous les cas, la cohérence avec les façades, le sol et la crédence reste essentielle.',
                ],
            ],
            [
                'title' => 'Penser fabrication et pose dès le départ',
                'paragraphs' => [
                    'Un plan de travail sur mesure ne se choisit pas comme un simple échantillon. Les dimensions, les découpes, l’évier, la plaque de cuisson, les chants visibles et les jonctions influencent le résultat final. La céramique demande une fabrication précise et une pose bien préparée. C’est justement ce niveau de méthode qui permet d’obtenir un rendu net et durable.',
                    'Avant de décider, il faut donc réunir les informations principales : plan de la cuisine, photos, dimensions approximatives, style souhaité et contraintes d’accès. Ces éléments permettent de vérifier si la finition choisie reste adaptée à la configuration. Une grande surface avec peu de découpes ne se lit pas comme une cuisine très technique avec angles, retombées et nombreux raccords.',
                ],
            ],
            [
                'title' => 'Choisir avec une vision globale',
                'paragraphs' => [
                    'Le meilleur plan de travail est celui qui fait le lien entre esthétique, usage et faisabilité. La céramique convient particulièrement aux cuisines où l’on veut une sensation haut de gamme, une surface minérale et une grande simplicité au quotidien. Elle permet de travailler des effets marbre, pierre, béton ou plus contemporains sans perdre la logique pratique.',
                    'Pour prendre une décision solide, comparez les matières à partir de votre projet réel. Regardez les contraintes, la lumière, les meubles, le budget, la pose et l’entretien. Ensuite seulement, choisissez la finition. Cette approche évite les décisions trop rapides et donne une cuisine plus cohérente, plus durable et plus agréable à vivre.',
                ],
            ],
        ],
        [
            'La céramique est très adaptée aux cuisines actives et aux projets sur mesure.',
            'Le choix doit tenir compte de la chaleur, de l’entretien, des découpes et de la lumière.',
            'La pose et la fabrication doivent être anticipées avant de valider la finition.',
        ],
        'Voir les matériaux',
        pcp_route('/materiaux/')
    ),
    'prendre-les-mesures-plan-de-travail-ceramique' => pcp_article_content(
        [
            [
                'title' => 'Pourquoi les mesures comptent autant',
                'paragraphs' => [
                    'Prendre les mesures d’un plan de travail en céramique semble simple au départ, mais cette étape influence toute la suite du projet. Une longueur imprécise, un angle mal relevé ou une profondeur oubliée peuvent créer des ajustements compliqués au moment de la fabrication. La céramique étant une matière technique, la précision permet de préserver la qualité du rendu et d’éviter les surprises lors de la pose.',
                    'Il ne s’agit pas seulement de noter une grande longueur mur à mur. Il faut comprendre comment les meubles sont positionnés, où se trouvent les murs, quelles parties restent visibles, quelles découpes sont nécessaires et comment le plan va s’intégrer dans la cuisine. Une bonne prise d’informations donne une base claire pour établir un devis plus juste et préparer une fabrication cohérente.',
                ],
            ],
            [
                'title' => 'Les dimensions à relever en priorité',
                'paragraphs' => [
                    'Commencez par relever les longueurs principales, les profondeurs, les retours éventuels et la présence d’un îlot central. Notez aussi les dimensions approximatives de l’évier, de la plaque de cuisson, des prises intégrées ou des zones à laisser libres. Même si un relevé technique peut être confirmé ensuite, ces premières informations aident à cadrer le projet et à comprendre son niveau de complexité.',
                    'Il faut également penser aux chants visibles. Un plan contre un mur ne se lit pas de la même manière qu’un îlot central visible sur quatre côtés. L’épaisseur souhaitée, les retombées, les crédences et les jambages éventuels modifient la perception du plan. Plus ces éléments sont expliqués tôt, plus la réponse peut être précise.',
                ],
            ],
            [
                'title' => 'Photos, accès et contraintes techniques',
                'paragraphs' => [
                    'Les photos sont très utiles. Elles montrent les murs, les meubles, les angles, les arrivées d’eau, les appareils et les contraintes que les chiffres seuls ne racontent pas. Prenez des vues larges de la cuisine, puis quelques détails des zones importantes. Si l’ancien plan est encore en place, il peut servir de repère pour comprendre la configuration actuelle.',
                    'Les accès doivent aussi être signalés. Un grand plan de travail ou une pièce pour îlot central demande de vérifier la livraison, les escaliers, l’ascenseur, les couloirs, les portes et la zone de déchargement. Ces informations ne concernent pas seulement la logistique : elles peuvent influencer la manière de découper, transporter et poser la céramique.',
                ],
            ],
            [
                'title' => 'Préparer une demande de devis claire',
                'paragraphs' => [
                    'Une demande claire n’a pas besoin d’être parfaite. Elle doit surtout permettre de comprendre le projet. Dimensions approximatives, photos, type de cuisine, finition souhaitée, emplacement de l’évier, présence d’un îlot et ville d’intervention forment déjà une très bonne base. À partir de là, il devient possible d’échanger sérieusement.',
                    'Si une incertitude existe, indiquez-la simplement. Un mur pas tout à fait droit, une cuisine en rénovation, un meuble non posé ou une crédence à prévoir sont des informations importantes. Le but n’est pas de tout résoudre seul, mais de donner assez de matière pour que le projet avance dans le bon ordre.',
                ],
            ],
        ],
        [
            'Les longueurs, profondeurs, découpes et chants visibles doivent être indiqués.',
            'Les photos de la cuisine complètent les mesures et évitent les malentendus.',
            'Les contraintes d’accès peuvent influencer la livraison et la pose.',
        ],
        'Demander un devis',
        pcp_route('/demander-un-devis/')
    ),
    'entretien-plan-de-travail-ceramique' => pcp_article_content(
        [
            [
                'title' => 'Une surface pensée pour le quotidien',
                'paragraphs' => [
                    'L’un des grands avantages de la céramique est sa facilité d’entretien. Dans une cuisine, le plan de travail reçoit des projections, des traces de doigts, de l’eau, des miettes, des plats chauds et parfois des taches grasses. Une surface simple à nettoyer change réellement le confort d’usage, surtout lorsque la cuisine est utilisée plusieurs fois par jour.',
                    'La céramique permet de garder une sensation de propreté sans transformer l’entretien en contrainte. Un nettoyage régulier avec une éponge douce, de l’eau tiède et un produit adapté suffit dans la majorité des situations. Le plus important est de conserver de bons réflexes, sans utiliser d’accessoires trop agressifs qui pourraient abîmer les joints, les chants ou les finitions autour du plan.',
                ],
            ],
            [
                'title' => 'Les bons gestes après la préparation',
                'paragraphs' => [
                    'Après avoir cuisiné, il vaut mieux retirer rapidement les résidus alimentaires, les traces grasses et les liquides colorés. Même si la céramique est résistante, une routine simple évite les accumulations et garde le rendu plus net. Sur une finition claire ou très lumineuse, ce geste régulier permet de préserver la sensation premium de la cuisine.',
                    'Il est préférable d’utiliser un chiffon microfibre ou une éponge non abrasive. Les produits trop puissants ne sont pas nécessaires au quotidien. Pour les zones autour de l’évier ou de la plaque, un passage plus attentif peut être utile, car ce sont souvent les parties les plus sollicitées. Les joints et les raccords doivent rester propres pour que l’ensemble garde une finition soignée.',
                ],
            ],
            [
                'title' => 'Adapter l’entretien à la finition choisie',
                'paragraphs' => [
                    'Toutes les finitions ne se lisent pas de la même manière. Un décor marbre clair montre davantage certaines traces, tandis qu’un effet pierre ou béton peut les rendre plus discrètes. Le choix de la finition doit donc tenir compte de l’esthétique, mais aussi de la façon dont vous vivez la cuisine. Une surface très élégante doit rester agréable au quotidien.',
                    'La lumière joue aussi un rôle. Une cuisine très exposée peut révéler les traces en contre-jour. Une finition satinée ou légèrement texturée peut alors être plus confortable visuellement. Ce type de détail mérite d’être évoqué avant de commander, car il influence la satisfaction une fois la cuisine terminée.',
                ],
            ],
            [
                'title' => 'Préserver la qualité dans le temps',
                'paragraphs' => [
                    'Un plan de travail bien entretenu conserve plus longtemps son aspect net. Cela passe par des gestes simples : nettoyer régulièrement, éviter les chocs inutiles sur les arêtes, utiliser une planche pour les découpes intenses et contrôler ponctuellement les zones sensibles. La céramique est résistante, mais la cuisine reste un espace vivant.',
                    'Il faut aussi surveiller l’environnement du plan : silicone, crédence, évier, plaque et meubles. Une belle surface peut perdre de sa force si les éléments autour sont négligés. En gardant une routine claire, le plan de travail reste élégant, pratique et cohérent avec l’esprit premium du projet.',
                ],
            ],
        ],
        [
            'La céramique s’entretient simplement avec des gestes réguliers.',
            'Les accessoires doux suffisent dans la plupart des usages quotidiens.',
            'La finition choisie influence la visibilité des traces et le confort visuel.',
        ],
        'Explorer les collections',
        pcp_route('/collections/')
    ),
    'finition-ceramique-cuisine-lumineuse' => pcp_article_content(
        [
            [
                'title' => 'Observer la lumière avant de choisir',
                'paragraphs' => [
                    'Dans une cuisine lumineuse, la finition du plan de travail prend beaucoup d’importance. La lumière naturelle révèle les veinages, les reflets, les contrastes et les petites variations de matière. Une finition choisie trop vite peut sembler parfaite sur un échantillon, puis devenir trop présente ou trop froide dans la pièce terminée.',
                    'Avant de décider, observez la cuisine à différents moments de la journée. La lumière du matin, l’éclairage du soir, les façades mates ou brillantes et la couleur du sol modifient la perception de la céramique. Une surface claire peut agrandir l’espace, tandis qu’une finition plus profonde peut donner du relief et structurer une cuisine ouverte.',
                ],
            ],
            [
                'title' => 'Choisir entre douceur et contraste',
                'paragraphs' => [
                    'Un effet marbre clair fonctionne bien lorsque l’on veut une cuisine élégante, lumineuse et assez intemporelle. Il apporte de la profondeur sans alourdir l’ensemble, surtout si le veinage reste maîtrisé. À l’inverse, une finition plus contrastée peut devenir le point fort de la pièce, notamment sur un îlot central ou une cuisine aux façades sobres.',
                    'L’effet pierre offre souvent un équilibre intéressant. Il garde une présence minérale, mais avec une lecture plus douce. L’effet béton convient aux cuisines contemporaines qui cherchent un rendu calme, architectural et moins décoratif. Le choix dépend donc autant de l’ambiance recherchée que de la manière dont la lumière frappe la surface.',
                ],
            ],
            [
                'title' => 'Penser aux meubles, au sol et à la crédence',
                'paragraphs' => [
                    'Le plan de travail ne doit pas être choisi seul. Il dialogue avec les meubles, les poignées, le sol, la crédence et parfois la table ou les éléments de séjour. Dans une cuisine ouverte, cette cohérence devient encore plus importante. Une finition très expressive peut être magnifique, mais elle doit avoir assez d’espace pour respirer.',
                    'Si les façades sont déjà très marquées, il vaut mieux calmer le plan. Si la cuisine est très neutre, la céramique peut apporter le caractère qui manque. La crédence doit aussi être anticipée : assortie au plan, plus discrète ou volontairement contrastée. Ces décisions construisent l’équilibre final.',
                ],
            ],
            [
                'title' => 'Valider une direction avant la fabrication',
                'paragraphs' => [
                    'Une fois la direction visuelle choisie, il faut vérifier sa faisabilité sur la configuration réelle. Les grandes longueurs, les retours, les découpes et les chants visibles peuvent modifier la lecture du décor. Un veinage doit être pensé avec les jonctions et les zones visibles, surtout dans une cuisine premium.',
                    'La bonne méthode consiste à partir d’une ambiance générale, puis à confirmer les détails techniques. Cette progression évite de choisir une finition séduisante mais difficile à harmoniser. Le résultat doit rester beau au premier regard et agréable à vivre tous les jours.',
                ],
            ],
        ],
        [
            'La lumière naturelle change fortement la perception des finitions.',
            'Une finition claire agrandit, une finition contrastée structure la cuisine.',
            'Le plan doit rester cohérent avec les meubles, le sol et la crédence.',
        ],
        'Explorer les collections',
        pcp_route('/collections/')
    ),
    'plan-travail-ilot-central-ceramique' => pcp_article_content(
        [
            [
                'title' => 'Un élément central qui se voit partout',
                'paragraphs' => [
                    'L’îlot central attire naturellement le regard. Dans une cuisine ouverte, il devient à la fois une zone de préparation, un espace de repas, un lieu de passage et parfois le véritable centre de la pièce. Le plan de travail doit donc être pensé avec beaucoup de soin, car ses proportions, ses chants et ses finitions restent visibles sous plusieurs angles.',
                    'La céramique convient très bien à ce type de projet grâce à son rendu minéral et sa résistance au quotidien. Mais l’îlot demande plus d’anticipation qu’un plan posé contre un mur. Les débords, les assises, les découpes et la circulation autour doivent être cohérents pour que l’ensemble soit pratique et élégant.',
                ],
            ],
            [
                'title' => 'Définir les usages avant les dimensions',
                'paragraphs' => [
                    'Avant de parler longueur et profondeur, il faut préciser l’usage de l’îlot. Servira-t-il principalement à préparer les repas, à recevoir, à manger rapidement, à intégrer une plaque ou un évier ? Chaque réponse modifie le plan. Une zone repas demande un débord confortable, tandis qu’un îlot technique impose des découpes et des arrivées adaptées.',
                    'La circulation est tout aussi importante. Un bel îlot mal placé peut gêner les déplacements et rendre la cuisine moins agréable. Il faut garder assez d’espace autour pour ouvrir les meubles, passer à plusieurs et accéder aux appareils. La surface en céramique doit accompagner le geste, pas compliquer l’usage.',
                ],
            ],
            [
                'title' => 'Soigner les chants, les débords et les finitions',
                'paragraphs' => [
                    'Sur un îlot, les chants sont très visibles. Leur épaisseur, leur finition et leur continuité participent fortement au rendu premium. Une céramique effet marbre, pierre ou béton ne donnera pas la même impression selon la manière dont les bords sont traités. C’est un point à valider avant fabrication.',
                    'Les débords doivent être pensés avec précision. Trop courts, ils rendent les assises inconfortables. Trop généreux, ils peuvent nécessiter des supports ou créer un déséquilibre visuel. La bonne solution dépend de la longueur de l’îlot, du nombre de places, de la structure du meuble et du style recherché.',
                ],
            ],
            [
                'title' => 'Préparer la livraison et la pose',
                'paragraphs' => [
                    'Un plan d’îlot peut être lourd, volumineux et délicat à manipuler. Il faut donc vérifier les accès : porte d’entrée, couloir, ascenseur, escalier, stationnement et espace de manœuvre dans la cuisine. Cette étape est indispensable pour éviter les complications le jour de la pose.',
                    'La stabilité des meubles doit aussi être confirmée. Un îlot bien posé repose sur une base solide, de niveau et prête à recevoir la céramique. En préparant ces points tôt, le projet gagne en sécurité et le résultat final reste propre, stable et durable.',
                ],
            ],
        ],
        [
            'L’îlot central doit être pensé selon les usages avant les dimensions.',
            'Les chants et les débords influencent beaucoup le rendu final.',
            'La livraison et la manutention doivent être vérifiées avant la pose.',
        ],
        'Demander un devis',
        pcp_route('/demander-un-devis/')
    ),
    'livraison-pose-plan-travail-ceramique' => pcp_article_content(
        [
            [
                'title' => 'Une pose réussie se prépare avant le jour J',
                'paragraphs' => [
                    'La livraison et la pose d’un plan de travail en céramique demandent une vraie préparation. La matière est résistante à l’usage, mais elle reste technique à transporter, à manipuler et à ajuster. Plus les informations sont claires avant l’intervention, plus le jour de pose peut se dérouler dans de bonnes conditions.',
                    'Une cuisine prête, des meubles stables, des accès dégagés et des choix validés évitent les pertes de temps. Cela permet aussi de protéger le rendu final. La pose n’est pas seulement une étape de fin de chantier : c’est le moment où toutes les décisions prises auparavant se rejoignent.',
                ],
            ],
            [
                'title' => 'Vérifier les meubles et la configuration',
                'paragraphs' => [
                    'Les meubles doivent être posés, fixés et de niveau. Une surface en céramique ne peut pas compenser une base instable. Avant l’arrivée du plan, il faut donc contrôler les supports, les angles, les hauteurs et l’alignement général. Cette vérification protège la précision des joints, des chants et des découpes.',
                    'Les emplacements de l’évier, de la plaque, des prises ou des accessoires doivent aussi être confirmés. Une modification tardive peut compliquer la fabrication ou imposer des ajustements. Le mieux est de valider ces points en amont avec des plans, des photos et des mesures cohérentes.',
                ],
            ],
            [
                'title' => 'Anticiper les accès de livraison',
                'paragraphs' => [
                    'La livraison doit être pensée comme une partie du projet. Un grand plan de travail peut nécessiter plusieurs personnes, un chemin dégagé et parfois une organisation particulière. Il faut vérifier les portes, les couloirs, les escaliers, l’ascenseur, le stationnement et la distance entre le point de déchargement et la cuisine.',
                    'Les obstacles doivent être retirés avant l’arrivée de l’équipe. Protéger les sols, dégager les passages et prévoir une présence sur place aide à sécuriser l’intervention. Ces détails peuvent sembler secondaires, mais ils évitent beaucoup de tensions le jour de la pose.',
                ],
            ],
            [
                'title' => 'Garder une validation claire sur place',
                'paragraphs' => [
                    'Le jour de la pose, une personne capable de valider les détails doit être présente ou joignable. Même avec une bonne préparation, certaines décisions pratiques peuvent apparaître sur place : ordre de pose, contrôle visuel, ajustement autour d’un élément ou vérification d’un raccord.',
                    'Une fois le plan posé, il faut prendre le temps de regarder l’ensemble : alignement, joints, chants, intégration de l’évier, propreté et cohérence visuelle. Cette validation finale permet de fermer le projet proprement et de profiter d’une cuisine prête à vivre.',
                ],
            ],
        ],
        [
            'Les meubles doivent être stables, fixés et de niveau avant la pose.',
            'Les accès de livraison doivent être contrôlés avant l’intervention.',
            'Une validation sur place facilite les derniers ajustements.',
        ],
        'Voir nos services',
        pcp_route('/nos-services/')
    ),
];

foreach ($posts as $index => $postData) {
    if (isset($longPostContent[$postData['slug']])) {
        $posts[$index]['content'] = $longPostContent[$postData['slug']];
    }
}

foreach ($posts as $postData) {
    $postId = pcp_upsert_post(
        [
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_title' => $postData['title'],
            'post_name' => $postData['slug'],
            'post_excerpt' => $postData['excerpt'],
            'post_content' => $postData['content'],
        ]
    );

    wp_set_post_categories($postId, [$categoryId]);
    pcp_set_yoast_meta($postId, $postData['seo_title'], $postData['seo_description']);
}

$postFocusKeywords = [
    'plan-de-travail-ceramique-ou-quartz' => 'céramique ou quartz',
    'prendre-les-mesures-plan-de-travail-ceramique' => 'mesures plan de travail céramique',
    'entretien-plan-de-travail-ceramique' => 'entretien plan de travail céramique',
    'finition-ceramique-cuisine-lumineuse' => 'finition ceramique cuisine',
    'plan-travail-ilot-central-ceramique' => 'ilot central ceramique',
    'livraison-pose-plan-travail-ceramique' => 'pose plan de travail ceramique',
];

foreach ($postFocusKeywords as $slug => $focusKeyword) {
    $post = get_page_by_path($slug, OBJECT, 'post');

    if ($post) {
        update_post_meta((int) $post->ID, '_yoast_wpseo_focuskw', $focusKeyword);
        update_post_meta((int) $post->ID, '_yoast_wpseo_focuskeywords', wp_json_encode([['keyword' => $focusKeyword, 'score' => 'good']]));
    }
}

flush_rewrite_rules();

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success('Pages, menu, posts, forms and SEO defaults created.');
}
