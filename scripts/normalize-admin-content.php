<?php

if (!defined('ABSPATH')) {
    exit;
}

function pcp_normalize_page_meta(string $slug, array $values): void
{
    $page = get_page_by_path($slug);

    if (!$page instanceof WP_Post) {
        return;
    }

    foreach ($values as $key => $value) {
        update_post_meta($page->ID, $key, $value);
    }
}

pcp_normalize_page_meta(
    'accueil',
    [
        'pcp_loader_brand' => 'PLAN CÉRAMIQUE STUDIO',
        'pcp_loader_text' => 'Surface nouvelle génération',
        'pcp_hero_eyebrow' => 'Studio de surfaces premium',
        'pcp_hero_title' => 'Plan céramique nouvelle génération',
        'pcp_hero_lead' => 'Des plans de travail premium pour cuisines, îlots, salles de bain et projets architecturaux.',
        'pcp_hero_image' => 'hero-light-ceramique.jpg',
        'pcp_hero_image_alt' => 'Cuisine lumineuse haut de gamme avec plan de travail en céramique claire',
        'pcp_hero_caption' => 'Cuisine — Îlot central — Salle de bain — Crédence — Extérieur',
        'pcp_primary_cta_text' => 'Demander un devis',
        'pcp_primary_cta_url' => '#devis',
        'pcp_secondary_cta_text' => 'Explorer les matières',
        'pcp_secondary_cta_url' => '#matieres',
        'pcp_hero_badges' => "Résistant chaleur\nRésistant rayures\nIntérieur / extérieur",
        'pcp_intro_eyebrow' => 'Nouvelle ère',
        'pcp_intro_title' => 'L’ère nouvelle du plan de travail',
        'pcp_intro_text' => 'Le plan de travail n’est plus seulement une surface fonctionnelle. Il devient une pièce centrale de l’espace, un élément de design, de confort et de personnalité.',
        'pcp_cards_json' => "Design | Une présence architecturale qui structure la cuisine.\nRésistance | Une matière minérale adaptée aux usages exigeants.\nAmbiance | Des teintes claires, chaleureuses et haut de gamme.",
        'pcp_surface_eyebrow' => 'Surface Intelligence',
        'pcp_surface_title' => 'Une surface pensée pour les espaces exigeants.',
        'pcp_surface_cards' => "01 | Chaleur | Une surface céramique pensée pour les cuisines exigeantes et les usages quotidiens intensifs.\n02 | Rayures | Une excellente tenue à l’usage, avec un rendu minéral qui reste élégant dans le temps.\n03 | Taches | Une matière compacte, facile à nettoyer et adaptée aux espaces de vie contemporains.\n04 | UV | Des finitions qui conservent leur présence visuelle, en intérieur comme sur certains projets extérieurs.\n05 | Usage intensif | Un choix pertinent pour cuisines familiales, îlots centraux, salles de bain et projets professionnels.",
        'pcp_scanner_eyebrow' => 'Material Scanner',
        'pcp_scanner_title' => 'Analysez la matière qui donnera du caractère à votre espace.',
        'pcp_scanner_image' => 'texture-white-vein.jpg',
        'pcp_scanner_image_alt' => 'Texture claire de surface céramique veinée',
        'pcp_scanner_panel_eyebrow' => 'Point matière',
        'pcp_scanner_points' => "1 | 22% | 32% | Veinage | Un dessin minéral subtil apporte du mouvement sans alourdir la pièce.\n2 | 48% | 24% | Texture | Un toucher mat ou satiné donne une lecture plus douce et plus architecturale.\n3 | 74% | 45% | Résistance | La céramique convient aux surfaces très sollicitées avec un entretien simple.\n4 | 38% | 72% | Entretien | La surface se nettoie facilement au quotidien, sans protocole compliqué.\n5 | 68% | 76% | Ambiance | Les tons clairs dialoguent avec le bois, le champagne, le sauge et les murs chauds.\n6 | 86% | 64% | Usage conseillé | Cuisine, îlot, crédence, salle de bain ou table sur mesure.",
        'pcp_process_eyebrow' => 'Processus',
        'pcp_process_title' => 'De l’idée à la surface finale',
        'pcp_process_steps' => "Analyse de votre espace\nSélection de la matière\nPrise de mesures\nDécoupe et préparation\nPose et ajustement\nContrôle final",
        'pcp_ambiance_eyebrow' => 'Ambiances signatures',
        'pcp_ambiance_title' => 'Choisissez une atmosphère, pas seulement une matière.',
        'pcp_ambiance_cta_text' => 'Choisir cette ambiance',
        'pcp_ambiance_cta_url' => '#devis',
        'pcp_ambiance_cards' => "Warm Mineral | kitchen-warm-ceramique.jpg | Cuisine lumineuse avec plan de travail céramique clair et bois noyer | Pierre claire, bois noyer et lumière chaude pour un intérieur doux, élégant et accueillant. | #FBF8F2,#D8C7AD,#6B4E35\nPure Marble | kitchen-white-ceramique.jpg | Cuisine blanche premium avec surface céramique veinée | Blanc veiné, lignes épurées et lumière naturelle pour une cuisine lumineuse et intemporelle. | #FFFFFF,#E7DFD2,#C9A76A\nSage Architecture | kitchen-sage-ceramique.jpg | Cuisine vert sauge avec plan de travail céramique clair | Vert sauge, pierre claire et détails champagne pour une ambiance contemporaine et apaisante. | #A7B3A0,#F4EFE6,#B8785F",
        'pcp_applications_eyebrow' => 'Applications',
        'pcp_applications_title' => 'Une matière, plusieurs espaces',
        'pcp_applications' => "Plan de travail cuisine | kitchen-white-ceramique.jpg | Plan de travail cuisine en céramique claire\nÎlot central | island-light-ceramique.jpg | Îlot central avec surface céramique premium\nCrédence | texture-white-vein.jpg | Crédence en céramique claire veinée\nSalle de bain | bathroom-light-ceramique.jpg | Salle de bain lumineuse avec surface céramique\nTable sur mesure | texture-walnut-stone.jpg | Détail de matière minérale pour table sur mesure\nExtérieur | outdoor-light-ceramique.jpg | Espace extérieur clair avec surface céramique",
        'pcp_compare_eyebrow' => 'Comparateur',
        'pcp_compare_title' => 'Céramique vs surface classique',
        'pcp_compare_columns' => "Surface céramique\nBonne tenue à la chaleur selon usage et finition.\nTrès bonne résistance aux rayures du quotidien.\nEntretien simple sur surface compacte.\nCompatible avec de nombreux projets intérieurs.\nRendu esthétique minéral très premium.\n\nSurface classique\nPerformances variables selon matériau.\nPlus sensible aux traces ou impacts selon usage.\nEntretien parfois plus spécifique.\nMoins adaptée à certains environnements exigeants.\nRendu dépendant fortement de la finition.",
        'pcp_details_eyebrow' => 'Détails premium',
        'pcp_details_title' => 'Les détails invisibles font le luxe visible',
        'pcp_premium_details' => "Épaisseur\nType de chant\nFinition mate ou brillante\nIntégration évier\nCrédence assortie\nArrondis et découpes spéciales",
        'pcp_final_cta_eyebrow' => 'Projet architectural',
        'pcp_final_cta_title' => 'Votre projet mérite une surface d’exception',
        'pcp_final_cta_text' => 'Parlez-nous de votre espace, de vos envies et de votre ambiance idéale. Nous vous aidons à imaginer un plan céramique adapté à votre projet.',
        'pcp_final_cta_button_text' => 'Demander un devis',
        'pcp_final_cta_button_url' => '#devis',
        'pcp_final_cta_secondary_text' => 'Lire le blog',
        'pcp_final_cta_secondary_url' => home_url('/blog/'),
    ]
);

pcp_normalize_page_meta(
    'nos-services',
    [
        'pcp_hero_eyebrow' => 'Nos services',
        'pcp_hero_title' => 'Un service clair pour votre plan de travail en céramique.',
        'pcp_hero_lead' => 'De la première idée jusqu’à la pose, chaque étape est cadrée pour éviter les imprévus : conseil, mesures, choix de matière, fabrication, livraison et installation.',
        'pcp_hero_image_alt' => 'Conseil et préparation d’un projet de plan de travail en céramique',
        'pcp_secondary_cta_text' => 'Voir les matériaux',
        'pcp_intro_eyebrow' => 'Méthode',
        'pcp_intro_title' => 'Chaque service répond à une étape réelle du projet.',
        'pcp_intro_text' => 'La page Services sert à comprendre comment le projet avance : ce que nous vérifions, ce que nous préparons et ce qui permet d’obtenir un résultat propre le jour de la pose.',
        'pcp_feature_eyebrow' => 'Précision',
        'pcp_feature_title' => 'Un service utile parce qu’il relie esthétique et contraintes techniques.',
        'pcp_feature_text' => 'Un plan de travail réussi ne dépend pas seulement de la couleur choisie. Les dimensions, les découpes, les chants, les accès de livraison et la pose influencent directement le rendu final.',
        'pcp_feature_image_alt' => 'Surface céramique minérale pour cuisine sur mesure',
        'pcp_feature_list' => "Projet lisible dès le premier échange\nDécoupes évier, plaque et prises anticipées\nCoordination fabrication, livraison et pose\nIntervention pensée pour les cuisines sur mesure",
        'pcp_feature_cta_text' => '',
        'pcp_feature_cta_url' => '',
        'pcp_cards_json' => "C | 01 Conseil | Cadrer le projet | Nous clarifions l’usage de la cuisine, le style recherché, les contraintes techniques et le niveau de finition attendu.\nM | 02 Mesure | Préparer les dimensions | Les longueurs, profondeurs, angles, découpes et accès sont vérifiés pour sécuriser la fabrication du plan.\nF | 03 Fabrication | Produire sur mesure | Le plan de travail est préparé selon la matière choisie, les découpes prévues, les chants et les détails visibles.\nP | 04 Pose | Livrer et installer | La livraison et la pose sont organisées pour obtenir un rendu propre, stable et cohérent avec votre cuisine.",
        'pcp_final_cta_eyebrow' => 'Suite logique',
        'pcp_final_cta_title' => 'Préparez quelques informations, nous cadrons le reste.',
        'pcp_final_cta_text' => 'Dimensions approximatives, photos, ville, finition souhaitée ou simple idée de départ : ces éléments suffisent pour lancer une demande plus sérieuse.',
        'pcp_final_cta_button_text' => 'Accéder au formulaire devis',
        'pcp_final_cta_button_url' => '/demander-un-devis/',
    ]
);

pcp_normalize_page_meta(
    'materiaux',
    [
        'pcp_hero_eyebrow' => 'Matériaux',
        'pcp_hero_title' => 'Comprendre la céramique avant de choisir votre surface.',
        'pcp_hero_lead' => 'La matière influence le style, l’entretien, la résistance et le confort d’usage. Cette page vous aide à comparer les critères essentiels avant de passer aux collections ou au devis.',
        'pcp_hero_image_alt' => 'Surface céramique minérale pour plan de travail',
        'pcp_primary_cta_text' => 'Voir les collections',
        'pcp_intro_eyebrow' => 'Repères',
        'pcp_intro_title' => 'Le bon matériau se choisit selon l’usage réel de la cuisine.',
        'pcp_intro_text' => 'Une cuisine familiale, un îlot central ou une rénovation premium n’ont pas toujours les mêmes priorités. La céramique permet de relier esthétique, résistance et entretien dans une surface cohérente.',
        'pcp_cards_json' => "R | 01 Résistance | Une surface faite pour durer | La céramique accompagne les gestes répétés de la cuisine : préparation, service, nettoyage et usage quotidien.\nC | 02 Chaleur | Une matière rassurante | Le matériau garde une excellente stabilité dans les zones actives, autour de la cuisson et des préparations chaudes.\nE | 03 Entretien | Un nettoyage simple | Une routine douce suffit pour conserver une surface nette, hygiénique et agréable à vivre tous les jours.\nF | 04 Finitions | Des rendus très variés | Effet marbre, pierre, béton ou minéral uni : la finition se choisit selon la lumière et le style de cuisine.",
        'pcp_feature_eyebrow' => 'Choix',
        'pcp_feature_title' => 'La finition doit dialoguer avec la lumière, les façades et le sol.',
        'pcp_feature_text' => 'Une finition claire agrandit visuellement la pièce, un effet pierre apporte de la profondeur, un béton minéral donne une lecture plus architecturale. Le choix se fait toujours avec le contexte complet de la cuisine.',
        'pcp_feature_image_alt' => 'Finitions et couleurs céramiques pour cuisine',
        'pcp_feature_cta_text' => 'Explorer les finitions',
        'pcp_feature_cta_url' => '/collections/',
    ]
);

pcp_normalize_page_meta(
    'collections',
    [
        'pcp_hero_eyebrow' => 'Collections',
        'pcp_hero_title' => 'Choisir une finition qui donne le ton à toute la cuisine.',
        'pcp_hero_lead' => 'Les collections aident à construire l’ambiance du projet : marbre clair, pierre douce, béton minéral ou teinte plus profonde. L’objectif est de trouver une surface belle, cohérente et facile à vivre.',
        'pcp_hero_image_alt' => 'Showroom de finitions céramiques pour cuisine',
        'pcp_primary_cta_text' => 'Voir les réalisations',
        'pcp_intro_eyebrow' => 'Ambiances',
        'pcp_intro_title' => 'Une collection se choisit avec la cuisine entière, pas seule sur un écran.',
        'pcp_intro_text' => 'La lumière, les façades, le sol, la crédence et la taille du plan changent fortement la perception d’une finition. La bonne sélection doit rester élégante dans le projet réel.',
        'pcp_cards_json' => "M | 01 Marbre | Clair et élégant | Idéal pour apporter de la lumière, de la finesse et une sensation plus ouverte dans la cuisine.\nP | 02 Pierre | Naturel et profond | Un rendu minéral plus sobre, adapté aux cuisines chaleureuses, contemporaines ou très structurées.\nB | 03 Béton | Calme et architectural | Une finition discrète pour les cuisines épurées, les îlots centraux et les volumes modernes.\nT | 04 Tons profonds | Contraste maîtrisé | Des teintes plus présentes pour donner du caractère sans perdre la cohérence du projet.",
        'pcp_feature_eyebrow' => 'Projection',
        'pcp_feature_title' => 'Regarder une finition en situation aide à décider plus sereinement.',
        'pcp_feature_text' => 'Les réalisations permettent de voir comment une teinte se comporte avec un îlot, une crédence, des chants visibles ou une cuisine ouverte sur le séjour.',
        'pcp_feature_image_alt' => 'Cuisine terminée avec plan de travail en céramique',
        'pcp_feature_cta_text' => 'Voir les projets',
        'pcp_feature_cta_url' => '/realisations/',
    ]
);

pcp_normalize_page_meta(
    'realisations',
    [
        'pcp_hero_eyebrow' => 'Réalisations',
        'pcp_hero_title' => 'Des projets concrets pour mieux imaginer votre cuisine.',
        'pcp_hero_lead' => 'Les réalisations montrent comment la céramique se comporte dans des cuisines réelles : proportions, finitions, îlots, crédences, chants et détails de pose.',
        'pcp_hero_image_alt' => 'Cuisine terminée avec plan de travail en céramique',
        'pcp_primary_cta_text' => 'Lancer mon projet',
        'pcp_secondary_cta_text' => 'Voir les collections',
        'pcp_intro_eyebrow' => 'Preuves',
        'pcp_intro_title' => 'Une réalisation aide à vérifier le rendu, pas seulement l’idée.',
        'pcp_intro_text' => 'Avant de choisir une matière ou une finition, il est utile d’observer les volumes, les zones visibles, les joints, les chants et la relation entre le plan de travail et le reste de la cuisine.',
        'pcp_cards_json' => "F | 01 Famille | Cuisine quotidienne | Une surface pensée pour préparer, poser, nettoyer et garder une cuisine agréable dans la durée.\nI | 02 Îlot | Point central | Un îlot en céramique structure la pièce et demande une attention particulière aux proportions.\nR | 03 Rénovation | Transformation sobre | La matière peut moderniser une cuisine existante sans perdre la cohérence avec les éléments déjà présents.\nC | 04 Crédence | Finition complète | Une crédence assortie renforce la lecture du projet et protège les zones les plus exposées.",
        'pcp_feature_eyebrow' => 'Votre projet',
        'pcp_feature_title' => 'Une réalisation commence par quelques informations simples.',
        'pcp_feature_text' => 'Dimensions approximatives, photos, contraintes d’accès, choix de finition ou inspiration : ces éléments permettent de cadrer le projet et d’obtenir une réponse plus juste.',
        'pcp_feature_image_alt' => 'Préparation d’une demande de devis pour plan de travail en céramique',
        'pcp_feature_cta_text' => 'Demander un devis',
        'pcp_feature_cta_url' => '/demander-un-devis/',
    ]
);

pcp_normalize_page_meta(
    'contact',
    [
        'pcp_hero_eyebrow' => 'Contact',
        'pcp_hero_title' => 'Parlez-nous de votre projet, simplement.',
        'pcp_hero_lead' => 'Une question sur la céramique, une finition, une étape de pose ou une première idée de cuisine ? Cette page sert aux échanges rapides avant de cadrer un devis complet.',
        'pcp_hero_image_alt' => 'Espace de contact pour projet de plan de travail en céramique',
        'pcp_intro_eyebrow' => 'Repères',
        'pcp_intro_title' => 'Quand utiliser le contact ?',
        'pcp_cards_json' => "Q | Question simple | Un doute sur une finition, une contrainte de cuisine ou une étape du projet.\nM | Matière et rendu | Comparer un effet marbre, pierre, béton minéral ou une surface plus sobre.\nD | Avant devis | Préparer les bonnes informations avant de passer à une demande chiffrée.",
        'pcp_contact_email_label' => 'Email',
        'pcp_contact_zone_label' => 'Zone',
        'pcp_feature_eyebrow' => 'Formulaire',
        'pcp_feature_title' => 'Envoyer un message',
        'pcp_feature_text' => 'Décrivez votre besoin en quelques lignes. Si le projet est déjà précis, nous pourrons ensuite vous orienter vers la demande de devis.',
    ]
);

pcp_normalize_page_meta(
    'demander-un-devis',
    [
        'pcp_hero_eyebrow' => 'Demande de devis',
        'pcp_hero_title' => 'Préparer une étude claire pour votre plan de travail.',
        'pcp_hero_lead' => 'Le formulaire devis rassemble les informations utiles pour comprendre votre cuisine, les dimensions, les découpes, la finition souhaitée et les conditions de pose.',
        'pcp_hero_image_alt' => 'Ambiance showroom pour demande de devis en céramique',
        'pcp_intro_eyebrow' => 'Avant d’envoyer',
        'pcp_intro_title' => 'Quelques repères suffisent pour commencer.',
        'pcp_cards_json' => "1 | Dimensions | Longueurs, profondeurs, îlot, retours ou plan approximatif.\n2 | Finition | Effet marbre, pierre, béton minéral ou choix encore à définir.\n3 | Contraintes | Ville, accès, étage, meubles déjà posés ou cuisine en rénovation.",
        'pcp_feature_eyebrow' => 'Ce qui aide',
        'pcp_feature_title' => 'Plus le projet est précis, plus la réponse peut être juste.',
        'pcp_feature_text' => 'Vous pouvez joindre une photo, un plan ou donner des dimensions approximatives. Même si tout n’est pas encore finalisé, ces éléments permettent de comprendre la configuration.',
        'pcp_feature_list' => "Type de cuisine ou îlot central.\nÉvier, plaque, crédence ou découpes prévues.\nFinition souhaitée ou ambiance recherchée.\nVille et contraintes d’accès.",
        'pcp_form_eyebrow' => 'Formulaire',
        'pcp_form_title' => 'Demander mon étude',
    ]
);

pcp_normalize_page_meta(
    'blog',
    [
        'pcp_hero_eyebrow' => 'Conseils & inspirations',
        'pcp_hero_title' => 'Blog céramique premium',
        'pcp_hero_lead' => 'Guides, tendances et idées pour imaginer un plan de travail lumineux, durable et parfaitement intégré à votre espace.',
        'pcp_hero_image_alt' => 'Moodboard cuisine premium avec céramique claire et bois',
        'pcp_intro_eyebrow' => 'Articles',
        'pcp_intro_title' => 'Des repères clairs pour préparer votre projet.',
    ]
);

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success('Admin content normalized with exact French accents.');
}
