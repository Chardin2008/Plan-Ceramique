<?php

if (!defined('ABSPATH')) {
    exit;
}

function pcp_route(string $path = '/'): string
{
    return home_url($path);
}

function pcp_asset(string $filename): string
{
    return content_url('themes/plan-ceramique-premium/assets/images/' . ltrim($filename, '/'));
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
    $html = '';

    foreach ($items as $item) {
        $html .= '<li>' . esc_html($item) . '</li>';
    }

    return '<!-- wp:list --><ul class="wp-block-list">' . $html . '</ul><!-- /wp:list -->';
}

function pcp_image(string $filename, string $alt): string
{
    return sprintf(
        '<!-- wp:image {"sizeSlug":"full","linkDestination":"none"} --><figure class="wp-block-image size-full"><img src="%1$s" alt="%2$s" /></figure><!-- /wp:image -->',
        esc_url(pcp_asset($filename)),
        esc_attr($alt)
    );
}

function pcp_button_group(array $buttons): string
{
    $html = '';

    foreach ($buttons as $button) {
        $className = $button['class'] ?? '';
        $attrs = $className ? ' ' . wp_json_encode(['className' => $className], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';
        $classAttr = 'wp-block-button' . ($className ? ' ' . $className : '');

        $html .= sprintf(
            '<!-- wp:button%1$s --><div class="%2$s"><a class="wp-block-button__link wp-element-button" href="%3$s">%4$s</a></div><!-- /wp:button -->',
            $attrs,
            esc_attr($classAttr),
            esc_url($button['url']),
            esc_html($button['label'])
        );
    }

    return '<!-- wp:buttons --><div class="wp-block-buttons">' . $html . '</div><!-- /wp:buttons -->';
}

function pcp_column(string $content, string $className = ''): string
{
    $attrs = $className ? ' ' . wp_json_encode(['className' => $className], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';
    $classAttr = 'wp-block-column' . ($className ? ' ' . $className : '');

    return sprintf(
        '<!-- wp:column%1$s --><div class="%2$s">%3$s</div><!-- /wp:column -->',
        $attrs,
        esc_attr($classAttr),
        $content
    );
}

function pcp_columns(array $columns, string $className = '', string $align = 'wide'): string
{
    $attrs = ['align' => $align];

    if ($className) {
        $attrs['className'] = $className;
    }

    $json = wp_json_encode($attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $classAttr = 'wp-block-columns align' . $align . ($className ? ' ' . $className : '');
    $html = '';

    foreach ($columns as $column) {
        $html .= pcp_column($column['content'], $column['class'] ?? '');
    }

    return sprintf(
        '<!-- wp:columns %1$s --><div class="%2$s">%3$s</div><!-- /wp:columns -->',
        $json,
        esc_attr($classAttr),
        $html
    );
}

function pcp_group(string $content, string $className = '', string $align = 'wide'): string
{
    $attrs = ['align' => $align];

    if ($className) {
        $attrs['className'] = $className;
    }

    $json = wp_json_encode($attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $classAttr = 'wp-block-group align' . $align . ($className ? ' ' . $className : '');

    return sprintf(
        '<!-- wp:group %1$s --><div class="%2$s"><div class="wp-block-group__inner-container">%3$s</div></div><!-- /wp:group -->',
        $json,
        esc_attr($classAttr),
        $content
    );
}

function pcp_card(string $eyebrow, string $title, string $body): array
{
    return [
        'class' => 'pcp-card',
        'content' => pcp_paragraph($eyebrow, 'pcp-card__eyebrow') . pcp_heading($title, 3) . pcp_paragraph($body),
    ];
}

function pcp_panel_shortcode(string $shortcode): string
{
    return pcp_group('<!-- wp:shortcode -->' . $shortcode . '<!-- /wp:shortcode -->', 'pcp-form-panel');
}

function pcp_update_post(string $postType, string $slug, array $data): void
{
    $post = get_page_by_path($slug, OBJECT, $postType);

    if (!$post) {
        return;
    }

    wp_update_post(array_merge(['ID' => $post->ID], $data));
}

function pcp_find_form_id(string $title): int
{
    $form = get_page_by_title($title, OBJECT, 'wpcf7_contact_form');

    return $form ? (int) $form->ID : 0;
}

$contactFormId = pcp_find_form_id('Formulaire de contact');
$quoteFormId = pcp_find_form_id('Formulaire de demande de devis');

$home = pcp_group(
    pcp_columns(
        [
            [
                'content' =>
                    pcp_paragraph('Plan de travail en ceramique sur mesure', 'pcp-card__eyebrow') .
                    pcp_heading('L excellence minerale au coeur de votre cuisine', 1) .
                    pcp_paragraph('Fabrication, livraison et pose de plans de travail en ceramique pour cuisines sur mesure, avec une lecture claire et une demande de devis facile.', 'pcp-hero__lead') .
                    pcp_button_group([
                        ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/')],
                        ['label' => 'Voir les collections', 'url' => pcp_route('/collections/'), 'class' => 'is-style-outline'],
                    ]) .
                    pcp_list([
                        'Resistance a la chaleur',
                        'Resistance aux rayures',
                        'Entretien facile et finitions soignees',
                    ]),
            ],
            [
                'content' => pcp_image('hero-home.jpg', 'Cuisine premium avec ilot central et plan de travail en ceramique'),
            ],
        ],
        'pcp-hero__grid'
    ),
    'pcp-section pcp-section--hero',
    'full'
);

$home .= pcp_group(
    pcp_paragraph('Nos services', 'pcp-section-intro') .
    pcp_heading('Un accompagnement clair du relevé a la pose') .
    pcp_paragraph('Conseil, prise de mesure, fabrication, livraison et pose sont presentes de facon concrete pour faciliter la projection du client.') .
    pcp_columns(
        [
            pcp_card('Conseil', 'Projet cadre', 'Nous clarifions l usage, le niveau de finition et le type de cuisine sur mesure.'),
            pcp_card('Fabrication', 'Dimensions prepares', 'Les decoupes et les dimensions utiles sont cadrees avant fabrication.'),
            pcp_card('Pose', 'Rendu final propre', 'La pose finalise l integration du plan de travail dans la cuisine.'),
        ],
        'pcp-card-grid'
    ) .
    pcp_button_group([
        ['label' => 'Voir nos services', 'url' => pcp_route('/nos-services/')],
    ]),
    'pcp-section'
);

$home .= pcp_group(
    pcp_columns(
        [
            [
                'content' => pcp_image('hero-materials.jpg', 'Gros plan premium sur une matiere ceramique'),
            ],
            [
                'content' =>
                    pcp_paragraph('Materiaux', 'pcp-section-intro') .
                    pcp_heading('Une matiere dense, stable et simple a entretenir') .
                    pcp_paragraph('La page materiaux detaille la resistance a la chaleur, la tenue aux rayures, les finitions et la facilite d entretien.') .
                    pcp_list([
                        'Surface dense et hygienique',
                        'Finitions pierre, sable, marbre ou graphite',
                        'Nettoyage simple dans une cuisine active',
                    ]) .
                    pcp_button_group([
                        ['label' => 'Voir les materiaux', 'url' => pcp_route('/materiaux/')],
                    ]),
            ],
        ]
    ),
    'pcp-section'
);

$home .= pcp_group(
    pcp_columns(
        [
            [
                'content' =>
                    pcp_paragraph('Collections', 'pcp-section-intro') .
                    pcp_heading('Des tonalites coherentes pour une cuisine plus signee') .
                    pcp_paragraph('Les collections presentent veinages, couleurs et styles pour aider le client a cadrer son projet avant devis.') .
                    pcp_list([
                        'Tons clairs et mineraux',
                        'Finitions plus contrastees',
                        'Associations avec cuisine bois ou graphite',
                    ]) .
                    pcp_button_group([
                        ['label' => 'Voir les collections', 'url' => pcp_route('/collections/')],
                    ]),
            ],
            [
                'content' => pcp_image('hero-collections.jpg', 'Showroom premium de couleurs et finitions ceramiques'),
            ],
        ]
    ),
    'pcp-section'
);

$home .= pcp_group(
    pcp_columns(
        [
            [
                'content' => pcp_image('hero-projects.jpg', 'Cuisine terminee avec plan de travail en ceramique'),
            ],
            [
                'content' =>
                    pcp_paragraph('Realisations', 'pcp-section-intro') .
                    pcp_heading('Des cuisines terminees pour donner un cadre concret') .
                    pcp_paragraph('Les realisations montrent le niveau de finition, l integration des ilots et la lecture du materiau dans une cuisine finale.') .
                    pcp_list([
                        'Plans de travail lineaires',
                        'Ilots centraux et credences assorties',
                        'Cuisine terminee avec pose soignee',
                    ]) .
                    pcp_button_group([
                        ['label' => 'Voir les realisations', 'url' => pcp_route('/realisations/')],
                    ]),
            ],
        ]
    ),
    'pcp-section'
);

$home .= pcp_group(
    pcp_paragraph('Avantages de la ceramique', 'pcp-section-intro') .
    pcp_heading('Des benefices lisibles pour une decision plus rapide') .
    pcp_columns(
        [
            pcp_card('Chaleur', 'Cuisine plus sereine', 'La ceramique repond bien aux usages intensifs autour des zones de cuisson.'),
            pcp_card('Rayures', 'Surface plus durable', 'Le materiau accompagne le quotidien avec une perception de robustesse.'),
            pcp_card('Entretien', 'Nettoyage plus simple', 'La surface reste nette et pratique a vivre jour apres jour.'),
        ],
        'pcp-card-grid'
    ),
    'pcp-section'
);

$home .= pcp_group(
    pcp_paragraph('Processus', 'pcp-section-intro') .
    pcp_heading('Une methode simple du brief au chantier') .
    pcp_columns(
        [
            ['class' => 'pcp-process__step', 'content' => pcp_heading('Brief', 3) . pcp_paragraph('Cadrage du projet, des usages et des attentes esthetiques.')],
            ['class' => 'pcp-process__step', 'content' => pcp_heading('Mesures', 3) . pcp_paragraph('Verification des dimensions, decoupes et points techniques.')],
            ['class' => 'pcp-process__step', 'content' => pcp_heading('Fabrication', 3) . pcp_paragraph('Preparation du plan de travail en ceramique selon le projet valide.')],
            ['class' => 'pcp-process__step', 'content' => pcp_heading('Pose', 3) . pcp_paragraph('Livraison et installation pour un rendu propre et coherent.')],
        ],
        'pcp-process'
    ),
    'pcp-section'
);

$home .= pcp_group(
    pcp_columns(
        [
            [
                'content' =>
                    pcp_paragraph('Blog', 'pcp-section-intro') .
                    pcp_heading('Des conseils concrets avant la demande de devis') .
                    pcp_paragraph('Le blog nourrit la reflexion autour de l entretien, des mesures, des finitions et du choix du bon plan de travail.') .
                    pcp_list([
                        'Conseils d entretien',
                        'Comparaison de materiaux',
                        'Aide a la preparation du projet',
                    ]) .
                    pcp_button_group([
                        ['label' => 'Lire le blog', 'url' => pcp_route('/blog/')],
                    ]),
            ],
            [
                'content' => pcp_image('hero-blog.jpg', 'Visuel editorial autour du projet de cuisine en ceramique'),
            ],
        ]
    ),
    'pcp-section'
);

$home .= pcp_group(
    pcp_columns(
        [
            [
                'content' => pcp_image('hero-quote.jpg', 'Ambiance showroom pour une demande de devis ceramique'),
            ],
            [
                'content' =>
                    pcp_paragraph('Demande de devis', 'pcp-section-intro') .
                    pcp_heading('Un passage a l action simple et direct') .
                    pcp_paragraph('Le formulaire devis rassemble les dimensions, le type de projet, le materiau souhaite et les fichiers utiles sans alourdir le parcours.') .
                    pcp_list([
                        'Projet cuisine ou ilot central',
                        'Ville, dimensions et finition souhaitee',
                        'Plan ou photo possible',
                    ]) .
                    pcp_button_group([
                        ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/')],
                    ]),
            ],
        ]
    ),
    'pcp-section'
);

$servicesPage = pcp_group(
    pcp_paragraph('Introduction', 'pcp-section-intro') .
    pcp_heading('Des services penses pour un projet de cuisine sur mesure') .
    pcp_paragraph('La page services detaille le conseil, la fabrication, la livraison et la pose pour donner une vision claire du niveau d accompagnement.') .
    pcp_list([
        'Orientation sur la bonne finition ceramique',
        'Prise de mesure avant fabrication',
        'Livraison organisee et pose propre',
    ]),
    'pcp-section'
);

$servicesPage .= pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image('hero-services.jpg', 'Prise de mesure pour plan de travail en ceramique')],
            ['content' => pcp_heading('Conseil et relevé') . pcp_paragraph('Nous cadrons l usage de la cuisine, les dimensions, les zones de cuisson et les points techniques avant fabrication.')],
        ]
    ),
    'pcp-section'
);

$servicesPage .= pcp_group(
    pcp_columns(
        [
            pcp_card('Fabrication', 'Preparation du plan', 'Le plan de travail est prepare selon les cotes, decoupes et finitions validees.'),
            pcp_card('Livraison', 'Organisation du chantier', 'Le transport et l acces sont anticipés pour eviter les blocages.'),
            pcp_card('Pose', 'Finition finale', 'La pose vient stabiliser le rendu et la lecture haut de gamme du projet.'),
        ],
        'pcp-card-grid'
    ),
    'pcp-section'
);

$servicesPage .= pcp_group(
    pcp_heading('Pourquoi demander un devis maintenant ?') .
    pcp_paragraph('Une demande de devis permet de cadrer plus vite les dimensions, les finitions et la faisabilite du projet de cuisine sur mesure.') .
    pcp_button_group([
        ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/')],
    ]),
    'pcp-section'
);

$materialsPage = pcp_group(
    pcp_paragraph('Introduction', 'pcp-section-intro') .
    pcp_heading('Comprendre la ceramique avant de choisir votre plan de travail') .
    pcp_paragraph('La page materiaux aide a comparer la resistance, l entretien et les finitions utiles dans une cuisine sur mesure.') .
    pcp_list([
        'Resistance a la chaleur',
        'Resistance aux rayures',
        'Entretien facile et rendu net',
    ]),
    'pcp-section'
);

$materialsPage .= pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image('hero-materials.jpg', 'Texture premium de surface ceramique')],
            ['content' => pcp_heading('Une surface dense et stable') . pcp_paragraph('La ceramique convient aux cuisines actives qui cherchent une lecture propre, durable et simple a entretenir.')],
        ]
    ),
    'pcp-section'
);

$materialsPage .= pcp_group(
    pcp_columns(
        [
            pcp_card('Chaleur', 'Usage cuisine', 'La surface repond bien aux sollicitations quotidiennes autour des zones de cuisson.'),
            pcp_card('Entretien', 'Routine simple', 'Le nettoyage reste facile et rapide dans une cuisine active.'),
            pcp_card('Finitions', 'Lecture esthetique', 'Pierre, sable, graphite ou marbre permettent de personnaliser la cuisine.'),
        ],
        'pcp-card-grid'
    ),
    'pcp-section'
);

$materialsPage .= pcp_group(
    pcp_heading('Passer des materiaux au projet') .
    pcp_paragraph('Une fois le materiau mieux defini, la demande de devis permet de cadrer les dimensions, les finitions et la pose.') .
    pcp_button_group([
        ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/')],
    ]),
    'pcp-section'
);

$collectionsPage = pcp_group(
    pcp_paragraph('Introduction', 'pcp-section-intro') .
    pcp_heading('Des collections pour cadrer la couleur et la finition') .
    pcp_paragraph('Cette page aide a choisir veinages, tons et styles de plans de travail en ceramique selon la cuisine sur mesure.') .
    pcp_list([
        'Tons clairs, sable et mineraux',
        'Finitions plus marquees et plus graphiques',
        'Associations avec bois, blanc casse ou graphite',
    ]),
    'pcp-section'
);

$collectionsPage .= pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image('hero-collections.jpg', 'Showroom de couleurs et finitions ceramiques')],
            ['content' => pcp_heading('Une selection plus lisible') . pcp_paragraph('La collection permet de relier plus facilement la matiere ceramique au style de cuisine, aux facades et a l ambiance souhaitee.')],
        ]
    ),
    'pcp-section'
);

$collectionsPage .= pcp_group(
    pcp_columns(
        [
            pcp_card('Clair', 'Cuisine lumineuse', 'Les teintes claires ouvrent l espace et renforcent la sensation de proprete.'),
            pcp_card('Mineral', 'Cuisine sobre', 'Les tons pierre et sable conviennent a une lecture elegante et calme.'),
            pcp_card('Graphite', 'Cuisine contrastee', 'Les finitions plus sombres structurent mieux les volumes et l ilot central.'),
        ],
        'pcp-card-grid'
    ),
    'pcp-section'
);

$collectionsPage .= pcp_group(
    pcp_heading('De la collection au devis') .
    pcp_paragraph('La demande de devis permet de transformer une ambiance choisie en dimensions, finitions et pose reelles.') .
    pcp_button_group([
        ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/')],
    ]),
    'pcp-section'
);

$projectsPage = pcp_group(
    pcp_paragraph('Introduction', 'pcp-section-intro') .
    pcp_heading('Des realisations pour juger le rendu final') .
    pcp_paragraph('La page realisations montre le niveau de finition, l integrite de la pose et la place du plan de travail en ceramique dans une cuisine terminee.') .
    pcp_list([
        'Cuisine lineaire',
        'Ilot central',
        'Details de finition et credence assortie',
    ]),
    'pcp-section'
);

$projectsPage .= pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image('hero-projects.jpg', 'Cuisine terminee avec plan de travail en ceramique')],
            ['content' => pcp_heading('Un rendu concret') . pcp_paragraph('Les realisations aident a mieux juger l echelle, la couleur et la lecture de la surface une fois la cuisine terminee.')],
        ]
    ),
    'pcp-section'
);

$projectsPage .= pcp_group(
    pcp_columns(
        [
            pcp_card('Pose', 'Joints et alignements', 'La qualite de pose joue sur la lecture finale du projet.'),
            pcp_card('Finition', 'Bords et decoupes', 'Les details visibles renforcent la perception premium.'),
            pcp_card('Usage', 'Cuisine quotidienne', 'Les projets montrent comment la ceramique s integre a une vraie cuisine sur mesure.'),
        ],
        'pcp-card-grid'
    ),
    'pcp-section'
);

$projectsPage .= pcp_group(
    pcp_heading('Passer du rendu au projet') .
    pcp_paragraph('Quand le rendu vous parle, la demande de devis permet de valider les dimensions, la finition et la faisabilite.') .
    pcp_button_group([
        ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/')],
    ]),
    'pcp-section'
);

$blogPage = pcp_group(
    pcp_paragraph('Introduction', 'pcp-section-intro') .
    pcp_heading('Des contenus utiles pour mieux preparer le projet') .
    pcp_paragraph('Le blog complete le site avec des conseils sur les finitions, la fabrication, la livraison, la pose et l entretien.') .
    pcp_list([
        'Comparer les solutions',
        'Anticiper les mesures',
        'Comprendre l entretien et les usages',
    ]),
    'pcp-section'
);

$blogPage .= pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image('hero-blog.jpg', 'Scene editoriale autour d un projet de cuisine en ceramique')],
            ['content' => pcp_heading('Un appui pour la decision') . pcp_paragraph('Les articles rassurent avant le contact et aident a arriver au devis avec de meilleures questions et une vision plus claire.')],
        ]
    ),
    'pcp-section'
);

$blogPage .= pcp_group(
    pcp_columns(
        [
            pcp_card('Materiaux', 'Choix mieux cadre', 'Le blog aide a relier finitions et usages du quotidien.'),
            pcp_card('Mesures', 'Projet mieux prepare', 'Il devient plus simple d anticiper dimensions et contraintes.'),
            pcp_card('Entretien', 'Usage mieux compris', 'Le client comprend comment la surface reste propre et facile a vivre.'),
        ],
        'pcp-card-grid'
    ),
    'pcp-section'
);

$blogPage .= pcp_group(
    pcp_heading('Aller plus loin avec votre demande') .
    pcp_paragraph('Une fois les points cles compris, le devis permet d entrer dans le concret avec dimensions, finitions et pose.') .
    pcp_button_group([
        ['label' => 'Demander un devis', 'url' => pcp_route('/demander-un-devis/')],
    ]),
    'pcp-section'
);

$contactPage = pcp_group(
    pcp_paragraph('Introduction', 'pcp-section-intro') .
    pcp_heading('Un premier contact simple pour un projet de cuisine sur mesure') .
    pcp_paragraph('La page contact sert a clarifier une finition, une question technique, une contrainte de livraison ou un besoin de pose.') .
    pcp_list([
        'Question sur les materiaux',
        'Question sur la fabrication',
        'Question sur la livraison ou la pose',
    ]),
    'pcp-section'
);

$contactPage .= pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image('hero-contact.jpg', 'Showroom premium et prise de contact pour projet ceramique')],
            ['content' => pcp_heading('Une prise de contact utile') . pcp_paragraph('Nous pouvons vous orienter vers les bonnes finitions, les bons formats et la suite logique du projet avant le devis.')],
        ]
    ),
    'pcp-section'
);

$contactPage .= pcp_group(
    pcp_heading('Formulaire de contact') .
    pcp_paragraph('Expliquez votre besoin et nous pourrons vous rediriger vers la bonne reponse ou vers une etude plus complete.') .
    ($contactFormId ? pcp_panel_shortcode('[contact-form-7 id="' . $contactFormId . '" title="Formulaire de contact"]') : ''),
    'pcp-section'
);

$contactPage .= pcp_group(
    pcp_heading('Besoin d un projet chiffre ?') .
    pcp_paragraph('Si vous avez deja vos dimensions ou un plan de cuisine, la page devis est plus adaptee pour entrer dans le concret.') .
    pcp_button_group([
        ['label' => 'Aller au devis', 'url' => pcp_route('/demander-un-devis/')],
    ]),
    'pcp-section'
);

$quotePage = pcp_group(
    pcp_paragraph('Introduction', 'pcp-section-intro') .
    pcp_heading('Un devis utile pour cadrer votre plan de travail en ceramique') .
    pcp_paragraph('La page devis centralise les informations utiles pour une etude serieuse : dimensions, type de projet, ville, materiau souhaite et fichiers.') .
    pcp_list([
        'Projet cuisine, ilot ou credence',
        'Dimensions approximatives',
        'Plan ou photo si disponible',
    ]),
    'pcp-section'
);

$quotePage .= pcp_group(
    pcp_columns(
        [
            ['content' => pcp_image('hero-quote.jpg', 'Ambiance showroom et texture ceramique pour demande de devis')],
            ['content' => pcp_heading('Un parcours plus direct') . pcp_paragraph('Le formulaire reste compact mais assez complet pour lancer une estimation plus pertinente autour de la fabrication, de la livraison et de la pose.')],
        ]
    ),
    'pcp-section'
);

$quotePage .= pcp_group(
    pcp_heading('Formulaire de demande de devis') .
    pcp_paragraph('Remplissez les informations essentielles puis joignez un plan ou une photo si vous en disposez deja.') .
    ($quoteFormId ? pcp_panel_shortcode('[contact-form-7 id="' . $quoteFormId . '" title="Formulaire de demande de devis"]') : ''),
    'pcp-section'
);

$quotePage .= pcp_group(
    pcp_columns(
        [
            pcp_card('Fabrication', 'Projet plus realiste', 'Le devis permet d ajuster le projet a une vraie configuration de cuisine.'),
            pcp_card('Livraison', 'Acces anticipe', 'La logistique peut etre prise en compte plus tot dans le parcours.'),
            pcp_card('Pose', 'Lecture finale', 'Le niveau de finition et la pose sont mieux cadres des le debut.'),
        ],
        'pcp-card-grid'
    ),
    'pcp-section'
);

$posts = [
    'plan-de-travail-ceramique-ou-quartz' => [
        'title' => 'Plan de travail en ceramique ou quartz : quel choix pour une cuisine haut de gamme ?',
        'excerpt' => 'Comparer ceramique et quartz selon l usage, l entretien et la perception finale dans une cuisine sur mesure.',
        'content' =>
            pcp_group(
                pcp_heading('Comparer selon l usage reel') .
                pcp_paragraph('Le bon choix depend de la chaleur, de l entretien, du rendu final et du type de cuisine sur mesure.') .
                pcp_list([
                    'Resistance a la chaleur',
                    'Entretien au quotidien',
                    'Lecture esthetique du plan de travail',
                ]),
                'pcp-section'
            ),
    ],
    'prendre-les-mesures-plan-de-travail-ceramique' => [
        'title' => 'Comment prendre les mesures d un plan de travail en ceramique avant fabrication',
        'excerpt' => 'Les reperes utiles pour cadrer releves, decoupes et acces avant fabrication.',
        'content' =>
            pcp_group(
                pcp_heading('Des mesures utiles avant fabrication') .
                pcp_paragraph('Le relevé sert a cadrer dimensions, decoupes, livraison et pose pour un projet plus propre.') .
                pcp_list([
                    'Longueurs et profondeurs',
                    'Decoupes evier et plaque',
                    'Acces et contraintes de pose',
                ]),
                'pcp-section'
            ),
    ],
    'entretien-plan-de-travail-ceramique' => [
        'title' => 'Entretien d un plan de travail en ceramique : les bons gestes au quotidien',
        'excerpt' => 'Une routine simple pour garder une surface nette, lisible et facile a vivre.',
        'content' =>
            pcp_group(
                pcp_heading('Une routine simple et claire') .
                pcp_paragraph('La ceramique garde un interet fort quand l entretien reste simple et adapte a la vraie vie en cuisine.') .
                pcp_list([
                    'Nettoyage doux',
                    'Reaction rapide sur les taches',
                    'Gestes simples pour un rendu net',
                ]),
                'pcp-section'
            ),
    ],
];

pcp_update_post('page', 'accueil', [
    'post_title' => 'Accueil',
    'post_excerpt' => 'Plan de travail en ceramique sur mesure, fabrication, livraison et pose partout en France.',
    'post_content' => $home,
]);

pcp_update_post('page', 'nos-services', [
    'post_title' => 'Nos services',
    'post_excerpt' => 'Conseil, fabrication, livraison et pose de plans de travail en ceramique.',
    'post_content' => $servicesPage,
]);

pcp_update_post('page', 'materiaux', [
    'post_title' => 'Materiaux',
    'post_excerpt' => 'Comparer les materiaux ceramiques pour une cuisine sur mesure lisible et durable.',
    'post_content' => $materialsPage,
]);

pcp_update_post('page', 'collections', [
    'post_title' => 'Collections',
    'post_excerpt' => 'Choisir couleurs, veinages et finitions pour un plan de travail en ceramique.',
    'post_content' => $collectionsPage,
]);

pcp_update_post('page', 'realisations', [
    'post_title' => 'Realisations',
    'post_excerpt' => 'Des cuisines terminees pour juger le rendu final d un plan de travail en ceramique.',
    'post_content' => $projectsPage,
]);

pcp_update_post('page', 'blog', [
    'post_title' => 'Blog',
    'post_excerpt' => 'Conseils utiles sur les finitions, l entretien, la fabrication, la livraison et la pose.',
    'post_content' => $blogPage,
]);

pcp_update_post('page', 'contact', [
    'post_title' => 'Contact',
    'post_excerpt' => 'Parler simplement de votre projet de plan de travail en ceramique.',
    'post_content' => $contactPage,
]);

pcp_update_post('page', 'demander-un-devis', [
    'post_title' => 'Demander un devis',
    'post_excerpt' => 'Deposez votre demande de devis pour un plan de travail en ceramique sur mesure.',
    'post_content' => $quotePage,
]);

foreach ($posts as $slug => $postData) {
    pcp_update_post('post', $slug, [
        'post_title' => $postData['title'],
        'post_excerpt' => $postData['excerpt'],
        'post_content' => $postData['content'],
    ]);
}

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success('UI foundation content applied.');
}
