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

function pcp_set_yoast_meta(int $postId, string $title, string $description): void
{
    update_post_meta($postId, '_yoast_wpseo_title', $title);
    update_post_meta($postId, '_yoast_wpseo_metadesc', $description);
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
            'sender' => 'Plan Céramique Premium <wordpress@localhost>',
            'body' => '',
            'recipient' => 'hello@mpc.contact',
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
    'subject' => '[Plan Céramique Premium] Nouveau message de contact',
    'sender' => 'Plan Céramique Premium <wordpress@localhost>',
    'recipient' => 'hello@mpc.contact',
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
    'subject' => '[Plan Céramique Premium] Nouvelle demande de devis',
    'sender' => 'Plan Céramique Premium <wordpress@localhost>',
    'recipient' => 'hello@mpc.contact',
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

$blogContent = pcp_paragraph('Retrouvez ici des articles utiles sur la céramique, l’entretien, la prise de mesure, la fabrication et la pose des plans de travail sur mesure.');

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
        'seo_title' => 'Nos services de plan de travail en céramique | Plan Céramique Premium',
        'seo_description' => 'Découvrez nos services pour un plan de travail en céramique sur mesure : conseil, fabrication, livraison et pose.',
    ],
    [
        'title' => 'Matériaux',
        'slug' => 'materiaux',
        'excerpt' => 'Comparer les matériaux céramiques pour une cuisine sur mesure durable et facile à entretenir.',
        'content' => $materialsContent,
        'seo_title' => 'Matériaux céramiques pour cuisine sur mesure | Plan Céramique Premium',
        'seo_description' => 'Choisissez le bon matériau céramique pour votre plan de travail : résistance, entretien et finitions.',
    ],
    [
        'title' => 'Collections',
        'slug' => 'collections',
        'excerpt' => 'Explorez les couleurs et finitions céramiques adaptées à votre cuisine.',
        'content' => $collectionsContent,
        'seo_title' => 'Collections et finitions céramiques | Plan Céramique Premium',
        'seo_description' => 'Découvrez les collections de couleurs et finitions pour un plan de travail en céramique sur mesure.',
    ],
    [
        'title' => 'Réalisations',
        'slug' => 'realisations',
        'excerpt' => 'Exemples de cuisines terminées avec plan de travail en céramique et finitions soignées.',
        'content' => $projectsContent,
        'seo_title' => 'Réalisations de cuisines en céramique | Plan Céramique Premium',
        'seo_description' => 'Parcourez nos réalisations de cuisines avec plan de travail en céramique sur mesure.',
    ],
    [
        'title' => 'Blog',
        'slug' => 'blog',
        'excerpt' => 'Articles utiles sur la céramique, l’entretien, la prise de mesure et la pose.',
        'content' => $blogContent,
        'seo_title' => 'Blog plan de travail en céramique | Plan Céramique Premium',
        'seo_description' => 'Conseils pratiques sur la céramique, l’entretien, la fabrication et la pose de plans de travail.',
    ],
    [
        'title' => 'Contact',
        'slug' => 'contact',
        'excerpt' => 'Contactez-nous pour parler de votre projet de plan de travail en céramique.',
        'content' => $contactContent,
        'seo_title' => 'Contact plan de travail céramique | Plan Céramique Premium',
        'seo_description' => 'Contactez Plan Céramique Premium pour votre projet de cuisine sur mesure en céramique.',
    ],
    [
        'title' => 'Demander un devis',
        'slug' => 'demander-un-devis',
        'excerpt' => 'Déposez votre demande de devis pour un plan de travail en céramique sur mesure.',
        'content' => $quotePageContent,
        'seo_title' => 'Demander un devis plan de travail céramique | Plan Céramique Premium',
        'seo_description' => 'Envoyez votre demande de devis pour un plan de travail en céramique avec dimensions, ville et fichiers.',
    ],
];

$pageIds = [];

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
    'Plan de travail en céramique sur mesure | Plan Céramique Premium',
    'Plan de travail en céramique sur mesure pour cuisine premium : conseil, fabrication, livraison et pose partout en France.'
);

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
        'seo_title' => 'Céramique ou quartz pour un plan de travail ? | Plan Céramique Premium',
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
        'seo_title' => 'Prendre les mesures d’un plan de travail céramique | Plan Céramique Premium',
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
        'seo_title' => 'Entretien d’un plan de travail en céramique | Plan Céramique Premium',
        'seo_description' => 'Découvrez comment entretenir facilement un plan de travail en céramique au quotidien.',
    ],
];

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

flush_rewrite_rules();

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success('Pages, menu, posts, forms and SEO defaults created.');
}
