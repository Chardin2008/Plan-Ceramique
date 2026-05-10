<?php

if (!defined('ABSPATH')) {
    exit;
}

function pcp_import_set_page_meta(string $slug, array $values): void
{
    $page = get_page_by_path($slug);

    if (!$page instanceof WP_Post) {
        return;
    }

    foreach ($values as $key => $value) {
        if ((string) get_post_meta($page->ID, $key, true) !== '') {
            continue;
        }

        update_post_meta($page->ID, $key, $value);
    }
}

function pcp_import_find_post(string $post_type, string $slug): ?WP_Post
{
    $posts = get_posts(
        [
            'name' => $slug,
            'post_type' => $post_type,
            'post_status' => 'any',
            'numberposts' => 1,
        ]
    );

    return $posts ? $posts[0] : null;
}

function pcp_import_insert_draft(string $post_type, string $title, string $slug, string $content, string $excerpt, array $meta): int
{
    $existing = pcp_import_find_post($post_type, $slug);

    if ($existing instanceof WP_Post) {
        $post_id = $existing->ID;
    } else {
        $post_id = wp_insert_post(
            [
                'post_type' => $post_type,
                'post_status' => 'draft',
                'post_title' => $title,
                'post_name' => $slug,
                'post_content' => $content,
                'post_excerpt' => $excerpt,
            ],
            true
        );

        if (is_wp_error($post_id)) {
            return 0;
        }
    }

    foreach ($meta as $key => $value) {
        if ((string) get_post_meta((int) $post_id, $key, true) === '') {
            update_post_meta((int) $post_id, $key, $value);
        }
    }

    update_post_meta((int) $post_id, '_pcp_admin_import_source', 'current-theme-fallback');

    return (int) $post_id;
}

pcp_import_set_page_meta(
    'accueil',
    [
        'pcp_loader_brand' => 'PLAN CERAMIQUE STUDIO',
        'pcp_loader_text' => 'Surface nouvelle generation',
        'pcp_hero_eyebrow' => 'Studio de surfaces premium',
        'pcp_hero_title' => 'Plan ceramique nouvelle generation',
        'pcp_hero_lead' => 'Des plans de travail premium pour cuisines, ilots, salles de bain et projets architecturaux.',
        'pcp_hero_image' => 'hero-light-ceramique.jpg',
        'pcp_hero_image_alt' => 'Cuisine lumineuse haut de gamme avec plan de travail en ceramique claire',
        'pcp_primary_cta_text' => 'Demander un devis',
        'pcp_primary_cta_url' => '#devis',
        'pcp_secondary_cta_text' => 'Explorer les matieres',
        'pcp_secondary_cta_url' => '#matieres',
        'pcp_hero_badges' => "Resistant chaleur\nResistant rayures\nInterieur / exterieur",
        'pcp_intro_eyebrow' => 'Nouvelle ere',
        'pcp_intro_title' => 'L ere nouvelle du plan de travail',
        'pcp_intro_text' => 'Le plan de travail n est plus seulement une surface fonctionnelle. Il devient une piece centrale de l espace, un element de design, de confort et de personnalite.',
        'pcp_cards_json' => "Design | Une presence architecturale qui structure la cuisine.\nResistance | Une matiere minerale adaptee aux usages exigeants.\nAmbiance | Des teintes claires, chaleureuses et haut de gamme.",
        'pcp_surface_cards' => "01 | Chaleur | Une surface ceramique pensee pour les cuisines exigeantes et les usages quotidiens intensifs.\n02 | Rayures | Une excellente tenue a l usage, avec un rendu mineral qui reste elegant dans le temps.\n03 | Taches | Une matiere compacte, facile a nettoyer et adaptee aux espaces de vie contemporains.\n04 | UV | Des finitions qui conservent leur presence visuelle, en interieur comme sur certains projets exterieurs.\n05 | Usage intensif | Un choix pertinent pour cuisines familiales, ilots centraux, salles de bain et projets professionnels.",
        'pcp_scanner_points' => "1 | 22% | 32% | Veinage | Un dessin mineral subtil apporte du mouvement sans alourdir la piece.\n2 | 48% | 24% | Texture | Un toucher mat ou satine donne une lecture plus douce et plus architecturale.\n3 | 74% | 45% | Resistance | La ceramique convient aux surfaces tres sollicitees avec un entretien simple.\n4 | 38% | 72% | Entretien | La surface se nettoie facilement au quotidien, sans protocole complique.\n5 | 68% | 76% | Ambiance | Les tons clairs dialoguent avec le bois, le champagne, le sauge et les murs chauds.\n6 | 86% | 64% | Usage conseille | Cuisine, ilot, credence, salle de bain ou table sur mesure.",
        'pcp_ambiance_cards' => "Warm Mineral | kitchen-warm-ceramique.jpg | Cuisine lumineuse avec plan de travail ceramique clair et bois noyer | Pierre claire, bois noyer et lumiere chaude pour un interieur doux, elegant et accueillant. | #FBF8F2,#D8C7AD,#6B4E35\nPure Marble | kitchen-white-ceramique.jpg | Cuisine blanche premium avec surface ceramique veinee | Blanc veine, lignes epurees et lumiere naturelle pour une cuisine lumineuse et intemporelle. | #FFFFFF,#E7DFD2,#C9A76A\nSage Architecture | kitchen-sage-ceramique.jpg | Cuisine vert sauge avec plan de travail ceramique clair | Vert sauge, pierre claire et details champagne pour une ambiance contemporaine et apaisante. | #A7B3A0,#F4EFE6,#B8785F",
        'pcp_applications' => "Plan de travail cuisine | kitchen-white-ceramique.jpg | Plan de travail cuisine en ceramique claire\nIlot central | island-light-ceramique.jpg | Ilot central avec surface ceramique premium\nCredence | texture-white-vein.jpg | Credence en ceramique claire veinee\nSalle de bain | bathroom-light-ceramique.jpg | Salle de bain lumineuse avec surface ceramique\nTable sur mesure | texture-walnut-stone.jpg | Detail de matiere minerale pour table sur mesure\nExterieur | outdoor-light-ceramique.jpg | Espace exterieur clair avec surface ceramique",
        'pcp_compare_columns' => "Surface ceramique\nBonne tenue a la chaleur selon usage et finition.\nTres bonne resistance aux rayures du quotidien.\nEntretien simple sur surface compacte.\nCompatible avec de nombreux projets interieurs.\nRendu esthetique mineral tres premium.\n\nSurface classique\nPerformances variables selon materiau.\nPlus sensible aux traces ou impacts selon usage.\nEntretien parfois plus specifique.\nMoins adaptee a certains environnements exigeants.\nRendu dependant fortement de la finition.",
        'pcp_process_steps' => "Analyse de votre espace\nSelection de la matiere\nPrise de mesures\nDecoupe et preparation\nPose et ajustement\nControle final",
        'pcp_premium_details' => "Epaisseur\nType de chant\nFinition mate ou brillante\nIntegration evier\nCredence assortie\nArrondis et decoupes speciales",
        'pcp_final_cta_eyebrow' => 'Projet architectural',
        'pcp_final_cta_title' => 'Votre projet merite une surface d exception',
        'pcp_final_cta_text' => 'Parlez-nous de votre espace, de vos envies et de votre ambiance ideale. Nous vous aidons a imaginer un plan ceramique adapte a votre projet.',
    ]
);

$page_data = [
    'nos-services' => [
        'pcp_hero_eyebrow' => 'Nos services',
        'pcp_hero_title' => 'Un service clair pour votre plan de travail en ceramique.',
        'pcp_hero_lead' => 'De la premiere idee jusqu a la pose, chaque etape est cadree pour eviter les imprevus : conseil, mesures, choix de matiere, fabrication, livraison et installation.',
        'pcp_hero_image' => 'hero-services.jpg',
        'pcp_hero_image_alt' => 'Conseil et preparation d un projet de plan de travail en ceramique',
        'pcp_primary_cta_text' => 'Demander un devis',
        'pcp_primary_cta_url' => '/demander-un-devis/',
        'pcp_secondary_cta_text' => 'Voir les materiaux',
        'pcp_secondary_cta_url' => '/materiaux/',
        'pcp_intro_eyebrow' => 'Methode',
        'pcp_intro_title' => 'Chaque service repond a une etape reelle du projet.',
        'pcp_intro_text' => 'La page Services sert a comprendre comment le projet avance : ce que nous verifions, ce que nous preparons et ce qui permet d obtenir un resultat propre le jour de la pose.',
        'pcp_cards_json' => "C | 01 Conseil | Cadrer le projet | Nous clarifions l usage de la cuisine, le style recherche, les contraintes techniques et le niveau de finition attendu.\nM | 02 Mesure | Preparer les dimensions | Les longueurs, profondeurs, angles, decoupes et acces sont verifies pour securiser la fabrication du plan.\nF | 03 Fabrication | Produire sur mesure | Le plan de travail est prepare selon la matiere choisie, les decoupes prevues, les chants et les details visibles.\nP | 04 Pose | Livrer et installer | La livraison et la pose sont organisees pour obtenir un rendu propre, stable et coherent avec votre cuisine.",
        'pcp_feature_eyebrow' => 'Precision',
        'pcp_feature_title' => 'Un service utile parce qu il relie esthetique et contraintes techniques.',
        'pcp_feature_text' => 'Un plan de travail reussi ne depend pas seulement de la couleur choisie. Les dimensions, les decoupes, les chants, les acces de livraison et la pose influencent directement le rendu final.',
        'pcp_feature_image' => 'hero-materials.jpg',
        'pcp_feature_image_alt' => 'Surface ceramique minerale pour cuisine sur mesure',
        'pcp_feature_list' => "Projet lisible des le premier echange\nDecoupes evier, plaque et prises anticipees\nCoordination fabrication, livraison et pose\nIntervention pensee pour les cuisines sur mesure",
        'pcp_final_cta_eyebrow' => 'Suite logique',
        'pcp_final_cta_title' => 'Preparez quelques informations, nous cadrons le reste.',
        'pcp_final_cta_text' => 'Dimensions approximatives, photos, ville, finition souhaitee ou simple idee de depart : ces elements suffisent pour lancer une demande plus serieuse.',
    ],
    'materiaux' => [
        'pcp_hero_eyebrow' => 'Materiaux',
        'pcp_hero_title' => 'Comprendre la ceramique avant de choisir votre surface.',
        'pcp_hero_lead' => 'La matiere influence le style, l entretien, la resistance et le confort d usage. Cette page vous aide a comparer les criteres essentiels avant de passer aux collections ou au devis.',
        'pcp_hero_image' => 'hero-materials.jpg',
        'pcp_hero_image_alt' => 'Surface ceramique minerale pour plan de travail',
        'pcp_primary_cta_text' => 'Voir les collections',
        'pcp_primary_cta_url' => '/collections/',
        'pcp_secondary_cta_text' => 'Demander un devis',
        'pcp_secondary_cta_url' => '/demander-un-devis/',
        'pcp_intro_eyebrow' => 'Reperes',
        'pcp_intro_title' => 'Le bon materiau se choisit selon l usage reel de la cuisine.',
        'pcp_intro_text' => 'Une cuisine familiale, un ilot central ou une renovation premium n ont pas toujours les memes priorites. La ceramique permet de relier esthetique, resistance et entretien dans une surface coherente.',
        'pcp_cards_json' => "R | 01 Resistance | Une surface faite pour durer | La ceramique accompagne les gestes repetes de la cuisine : preparation, service, nettoyage et usage quotidien.\nC | 02 Chaleur | Une matiere rassurante | Le materiau garde une excellente stabilite dans les zones actives, autour de la cuisson et des preparations chaudes.\nE | 03 Entretien | Un nettoyage simple | Une routine douce suffit pour conserver une surface nette, hygienique et agreable a vivre tous les jours.\nF | 04 Finitions | Des rendus tres varies | Effet marbre, pierre, beton ou mineral uni : la finition se choisit selon la lumiere et le style de cuisine.",
        'pcp_feature_eyebrow' => 'Choix',
        'pcp_feature_title' => 'La finition doit dialoguer avec la lumiere, les facades et le sol.',
        'pcp_feature_text' => 'Une finition claire agrandit visuellement la piece, un effet pierre apporte de la profondeur, un beton mineral donne une lecture plus architecturale. Le choix se fait toujours avec le contexte complet de la cuisine.',
        'pcp_feature_image' => 'hero-collections.jpg',
        'pcp_feature_image_alt' => 'Finitions et couleurs ceramiques pour cuisine',
    ],
    'collections' => [
        'pcp_hero_eyebrow' => 'Collections',
        'pcp_hero_title' => 'Choisir une finition qui donne le ton a toute la cuisine.',
        'pcp_hero_lead' => 'Les collections aident a construire l ambiance du projet : marbre clair, pierre douce, beton mineral ou teinte plus profonde. L objectif est de trouver une surface belle, coherente et facile a vivre.',
        'pcp_hero_image' => 'hero-collections.jpg',
        'pcp_hero_image_alt' => 'Showroom de finitions ceramiques pour cuisine',
        'pcp_primary_cta_text' => 'Voir les realisations',
        'pcp_primary_cta_url' => '/realisations/',
        'pcp_secondary_cta_text' => 'Demander un devis',
        'pcp_secondary_cta_url' => '/demander-un-devis/',
        'pcp_intro_eyebrow' => 'Ambiances',
        'pcp_intro_title' => 'Une collection se choisit avec la cuisine entiere, pas seule sur un ecran.',
        'pcp_intro_text' => 'La lumiere, les facades, le sol, la credence et la taille du plan changent fortement la perception d une finition. La bonne selection doit rester elegante dans le projet reel.',
        'pcp_cards_json' => "M | 01 Marbre | Clair et elegant | Ideal pour apporter de la lumiere, de la finesse et une sensation plus ouverte dans la cuisine.\nP | 02 Pierre | Naturel et profond | Un rendu mineral plus sobre, adapte aux cuisines chaleureuses, contemporaines ou tres structurees.\nB | 03 Beton | Calme et architectural | Une finition discrete pour les cuisines epurees, les ilots centraux et les volumes modernes.\nT | 04 Tons profonds | Contraste maitrise | Des teintes plus presentes pour donner du caractere sans perdre la coherence du projet.",
        'pcp_feature_eyebrow' => 'Projection',
        'pcp_feature_title' => 'Regarder une finition en situation aide a decider plus sereinement.',
        'pcp_feature_text' => 'Les realisations permettent de voir comment une teinte se comporte avec un ilot, une credence, des chants visibles ou une cuisine ouverte sur le sejour.',
        'pcp_feature_image' => 'hero-projects.jpg',
        'pcp_feature_image_alt' => 'Cuisine terminee avec plan de travail en ceramique',
    ],
    'realisations' => [
        'pcp_hero_eyebrow' => 'Realisations',
        'pcp_hero_title' => 'Des projets concrets pour mieux imaginer votre cuisine.',
        'pcp_hero_lead' => 'Les realisations montrent comment la ceramique se comporte dans des cuisines reelles : proportions, finitions, ilots, credences, chants et details de pose.',
        'pcp_hero_image' => 'hero-projects.jpg',
        'pcp_hero_image_alt' => 'Cuisine terminee avec plan de travail en ceramique',
        'pcp_primary_cta_text' => 'Lancer mon projet',
        'pcp_primary_cta_url' => '/demander-un-devis/',
        'pcp_secondary_cta_text' => 'Voir les collections',
        'pcp_secondary_cta_url' => '/collections/',
        'pcp_intro_eyebrow' => 'Preuves',
        'pcp_intro_title' => 'Une realisation aide a verifier le rendu, pas seulement l idee.',
        'pcp_intro_text' => 'Avant de choisir une matiere ou une finition, il est utile d observer les volumes, les zones visibles, les joints, les chants et la relation entre le plan de travail et le reste de la cuisine.',
        'pcp_cards_json' => "F | 01 Famille | Cuisine quotidienne | Une surface pensee pour preparer, poser, nettoyer et garder une cuisine agreable dans la duree.\nI | 02 Ilot | Point central | Un ilot en ceramique structure la piece et demande une attention particuliere aux proportions.\nR | 03 Renovation | Transformation sobre | La matiere peut moderniser une cuisine existante sans perdre la coherence avec les elements deja presents.\nC | 04 Credence | Finition complete | Une credence assortie renforce la lecture du projet et protege les zones les plus exposees.",
        'pcp_feature_eyebrow' => 'Votre projet',
        'pcp_feature_title' => 'Une realisation commence par quelques informations simples.',
        'pcp_feature_text' => 'Dimensions approximatives, photos, contraintes d acces, choix de finition ou inspiration : ces elements permettent de cadrer le projet et d obtenir une reponse plus juste.',
        'pcp_feature_image' => 'hero-quote.jpg',
        'pcp_feature_image_alt' => 'Preparation d une demande de devis pour plan de travail en ceramique',
    ],
    'contact' => [
        'pcp_hero_eyebrow' => 'Contact',
        'pcp_hero_title' => 'Parlez-nous de votre projet, simplement.',
        'pcp_hero_lead' => 'Une question sur la ceramique, une finition, une etape de pose ou une premiere idee de cuisine ? Cette page sert aux echanges rapides avant de cadrer un devis complet.',
        'pcp_hero_image' => 'hero-contact.jpg',
        'pcp_hero_image_alt' => 'Espace de contact pour projet de plan de travail en ceramique',
        'pcp_intro_eyebrow' => 'Reperes',
        'pcp_intro_title' => 'Quand utiliser le contact ?',
        'pcp_cards_json' => "Q | Question simple | Un doute sur une finition, une contrainte de cuisine ou une etape du projet.\nM | Matiere et rendu | Comparer un effet marbre, pierre, beton mineral ou une surface plus sobre.\nD | Avant devis | Preparer les bonnes informations avant de passer a une demande chiffree.",
        'pcp_feature_eyebrow' => 'Formulaire',
        'pcp_feature_title' => 'Envoyer un message',
        'pcp_feature_text' => 'Decrivez votre besoin en quelques lignes. Si le projet est deja precis, nous pourrons ensuite vous orienter vers la demande de devis.',
    ],
    'demander-un-devis' => [
        'pcp_hero_eyebrow' => 'Demande de devis',
        'pcp_hero_title' => 'Preparer une etude claire pour votre plan de travail.',
        'pcp_hero_lead' => 'Le formulaire devis rassemble les informations utiles pour comprendre votre cuisine, les dimensions, les decoupes, la finition souhaitee et les conditions de pose.',
        'pcp_hero_image' => 'hero-quote.jpg',
        'pcp_hero_image_alt' => 'Ambiance showroom pour demande de devis en ceramique',
        'pcp_intro_eyebrow' => 'Avant d envoyer',
        'pcp_intro_title' => 'Quelques reperes suffisent pour commencer.',
        'pcp_cards_json' => "1 | Dimensions | Longueurs, profondeurs, ilot, retours ou plan approximatif.\n2 | Finition | Effet marbre, pierre, beton mineral ou choix encore a definir.\n3 | Contraintes | Ville, acces, etage, meubles deja poses ou cuisine en renovation.",
        'pcp_feature_eyebrow' => 'Ce qui aide',
        'pcp_feature_title' => 'Plus le projet est precis, plus la reponse peut etre juste.',
        'pcp_feature_text' => 'Vous pouvez joindre une photo, un plan ou donner des dimensions approximatives. Meme si tout n est pas encore finalise, ces elements permettent de comprendre la configuration.',
        'pcp_feature_list' => "Type de cuisine ou ilot central.\nEvier, plaque, credence ou decoupes prevues.\nFinition souhaitee ou ambiance recherchee.\nVille et contraintes d acces.",
    ],
    'blog' => [
        'pcp_hero_eyebrow' => 'Conseils & inspirations',
        'pcp_hero_title' => 'Blog ceramique premium',
        'pcp_hero_lead' => 'Guides, tendances et idees pour imaginer un plan de travail lumineux, durable et parfaitement integre a votre espace.',
        'pcp_hero_image' => 'blog-kitchen-trends.jpg',
        'pcp_hero_image_alt' => 'Moodboard cuisine premium avec ceramique claire et bois',
        'pcp_intro_eyebrow' => 'Articles',
        'pcp_intro_title' => 'Des reperes clairs pour preparer votre projet.',
    ],
];

foreach ($page_data as $slug => $values) {
    pcp_import_set_page_meta($slug, $values);
}

$materials = [
    ['Blanc veine', 'blanc-veine', 'Une surface lumineuse avec veinage subtil pour agrandir visuellement la cuisine.', 'Clair', '#FFFFFF', 'Pure Marble', 'Cuisine, credence, salle de bain'],
    ['Beige mineral', 'beige-mineral', 'Une teinte douce, sable et champagne, ideale pour les interieurs chaleureux.', 'Chaleureux', '#D8C7AD', 'Warm Mineral', 'Ilot central, cuisine familiale'],
    ['Gris clair beton', 'gris-clair-beton', 'Un esprit architectural calme, plus contemporain que froid.', 'Beton', '#C8C8C4', 'Soft Concrete', 'Cuisine moderne, table sur mesure'],
    ['Pierre naturelle', 'pierre-naturelle', 'Un rendu mineral equilibre pour une ambiance premium et intemporelle.', 'Pierre', '#D6D0C2', 'Natural Stone', 'Cuisine, salle de bain'],
    ['Sable chaud', 'sable-chaud', 'Une matiere claire qui dialogue avec le bois, le lin et les murs chauds.', 'Naturel', '#D8C1A0', 'Warm Mineral', 'Ilot central, credence'],
    ['Terre douce', 'terre-douce', 'Une note plus organique pour donner du relief sans assombrir le projet.', 'Chaleureux', '#8F6D58', 'Sage Architecture', 'Cuisine, projet architectural'],
];

foreach ($materials as $item) {
    [$title, $slug, $text, $family, $color, $mood, $use] = $item;
    pcp_import_insert_draft(
        'pcp_matiere',
        $title,
        $slug,
        $text,
        $text,
        [
            'pcp_color_family' => $family,
            'pcp_dominant_color' => $color,
            'pcp_mood' => $mood,
            'pcp_use' => $use,
        ]
    );
}

$projects = [
    ['Cuisine lumineuse avec ilot central', 'cuisine-lumineuse-avec-ilot-central', 'Un grand ilot clair, pense comme la piece centrale de la maison.', 'Cuisine', 'Ceramique claire', 'Warm Mineral', 'Cuisine'],
    ['Salle de bain minerale', 'salle-de-bain-minerale', 'Une ambiance spa lumineuse avec surface ceramique douce.', 'Salle de bain', 'Pierre claire', 'Natural Stone', 'Salle de bain'],
    ['Plan de travail blanc veine', 'plan-de-travail-blanc-veine', 'Une cuisine blanche equilibree par un veinage mineral discret.', 'Cuisine', 'Blanc veine', 'Pure Marble', 'Cuisine'],
    ['Credence et plan assortis', 'credence-et-plan-assortis', 'Une continuite visuelle sobre entre plan, mur et lumiere.', 'Credence', 'Blanc veine', 'Pure Marble', 'Credence'],
    ['Cuisine vert sauge et pierre claire', 'cuisine-vert-sauge-et-pierre-claire', 'Un projet calme, contemporain et tres chaleureux.', 'Cuisine', 'Pierre claire', 'Sage Architecture', 'Cuisine'],
    ['Ilot familial chaleureux', 'ilot-familial-chaleureux', 'Une surface robuste dans un interieur doux et accueillant.', 'Ilot', 'Ceramique chaude', 'Warm Mineral', 'Ilot'],
];

foreach ($projects as $item) {
    [$title, $slug, $text, $type, $material, $mood, $filter] = $item;
    pcp_import_insert_draft(
        'pcp_realisation',
        $title,
        $slug,
        $text,
        $text,
        [
            'pcp_project_type' => $type,
            'pcp_material' => $material,
            'pcp_mood' => $mood,
            'pcp_gallery_filter' => $filter,
        ]
    );
}

$reviews = [
    ['Avis Nadia M.', 'avis-nadia-m', 'Le rendu est elegant, lumineux et vraiment haut de gamme. Le plan a change toute l ambiance de la cuisine.', 'Nadia M.', 'Cuisine avec ilot central', '5'],
    ['Avis Thomas R.', 'avis-thomas-r', 'Le choix des matieres rend la piece beaucoup plus moderne et chaleureuse.', 'Thomas R.', 'Renovation cuisine', '5'],
    ['Avis Sarah L.', 'avis-sarah-l', 'Le resultat est propre, contemporain et facile a entretenir. C est exactement l ambiance que je voulais.', 'Sarah L.', 'Salle de bain', '5'],
];

foreach ($reviews as $item) {
    [$title, $slug, $text, $name, $project, $rating] = $item;
    pcp_import_insert_draft(
        'pcp_avis',
        $title,
        $slug,
        $text,
        $text,
        [
            'pcp_client_name' => $name,
            'pcp_project_type' => $project,
            'pcp_rating' => $rating,
        ]
    );
}

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success('Admin content imported without publishing frontend replacement content.');
}
