<?php

if (!defined('ABSPATH')) {
    exit;
}

function pcp_blog_upsert_post(array $args): int
{
    $existing = get_page_by_path($args['post_name'], OBJECT, $args['post_type']);

    if ($existing instanceof WP_Post) {
        $args['ID'] = $existing->ID;
        wp_update_post($args);

        return (int) $existing->ID;
    }

    return (int) wp_insert_post($args);
}

function pcp_blog_heading(string $content, int $level = 2): string
{
    $tag = 'h' . $level;

    return '<!-- wp:heading {"level":' . $level . '} --><' . $tag . '>' . esc_html($content) . '</' . $tag . '><!-- /wp:heading -->';
}

function pcp_blog_paragraph(string $content): string
{
    return '<!-- wp:paragraph --><p>' . esc_html($content) . '</p><!-- /wp:paragraph -->';
}

function pcp_blog_list(array $items): string
{
    $html = '';

    foreach ($items as $item) {
        $html .= '<li>' . esc_html($item) . '</li>';
    }

    return '<!-- wp:list --><ul class="wp-block-list">' . $html . '</ul><!-- /wp:list -->';
}

function pcp_blog_button(string $label, string $url): string
{
    return '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url($url) . '">' . esc_html($label) . '</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';
}

function pcp_blog_group(string $inner, string $className = 'pcp-article-section'): string
{
    $json = wp_json_encode(['align' => 'wide', 'className' => $className], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    return '<!-- wp:group ' . $json . ' --><div class="wp-block-group alignwide ' . esc_attr($className) . '"><div class="wp-block-group__inner-container">' . $inner . '</div></div><!-- /wp:group -->';
}

function pcp_blog_video(string $query, string $title): string
{
    if (preg_match('~(?:youtu\.be/|youtube\.com/watch\?v=|youtube\.com/embed/)([A-Za-z0-9_-]{6,})~', $query, $matches)) {
        $src = 'https://www.youtube-nocookie.com/embed/' . $matches[1];
    } else {
        $src = 'https://www.youtube-nocookie.com/embed?listType=search&list=' . rawurlencode($query);
    }

    return '<!-- wp:html --><div class="pcp-video-embed"><iframe loading="lazy" title="' . esc_attr($title) . '" src="' . esc_url($src) . '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div><!-- /wp:html -->';
}

function pcp_blog_article_content(array $article, string $ctaUrl): string
{
    $content = pcp_blog_group(
        pcp_blog_heading($article['section_1']) .
        pcp_blog_paragraph('Dans une cuisine sur mesure, ' . mb_strtolower($article['keyword']) . ' ne doit jamais être traité comme un détail isolé. Le plan de travail, les façades, la lumière, la crédence et les usages quotidiens doivent être lus ensemble pour obtenir un résultat cohérent, durable et agréable à vivre.') .
        pcp_blog_paragraph($article['intro'] . ' L’objectif est de prendre une décision utile, pas seulement séduisante sur une photo. Un bon choix facilite l’entretien, sécurise la pose et donne à la cuisine une présence plus premium dès le premier regard.')
    );

    $content .= pcp_blog_group(
        pcp_blog_heading($article['section_2']) .
        pcp_blog_paragraph('Le premier repère consiste à relier la matière à votre rythme de vie. Une cuisine familiale, un îlot central très utilisé ou un espace ouvert sur le séjour n’imposent pas les mêmes priorités. La résistance à la chaleur, les rayures, les taches et la facilité de nettoyage deviennent alors des critères concrets.') .
        pcp_blog_paragraph($article['advice'] . ' Il faut aussi regarder les chants visibles, les découpes autour de l’évier, la plaque de cuisson, les jonctions et la façon dont la lumière révèle la surface. Ce sont ces détails qui donnent au projet son niveau final.')
    );

    if (!empty($article['video_query'])) {
        $content .= pcp_blog_video($article['video_query'], 'Vidéo conseil - ' . $article['title']);
    }

    $content .= pcp_blog_group(
        pcp_blog_heading($article['section_3']) .
        pcp_blog_paragraph('Avant de demander un devis, rassemblez les informations essentielles : dimensions approximatives, photos de la cuisine, type de meubles, contraintes d’accès, finition souhaitée et éléments techniques à prévoir. Même une base imparfaite permet déjà de cadrer la discussion et d’éviter les malentendus.') .
        pcp_blog_paragraph($article['mistake'] . ' Une demande claire aide à vérifier la faisabilité, à comparer les bonnes options et à avancer vers une fabrication plus précise. Le résultat paraît plus simple, mais il repose sur une préparation sérieuse.')
    );

    $content .= pcp_blog_group(
        pcp_blog_heading('À retenir') .
        pcp_blog_list($article['takeaways']) .
        pcp_blog_paragraph('Si vous souhaitez transformer ces repères en projet concret, l’étape suivante consiste à envoyer vos informations principales pour obtenir un avis plus précis sur la matière, la finition, la fabrication et la pose.') .
        pcp_blog_button($article['cta'], $ctaUrl),
        'pcp-article-section pcp-article-takeaways'
    );

    return $content;
}

$categories = [];

foreach (['Conception', 'Entretien', 'Matériaux', 'Tendances'] as $categoryName) {
    $term = term_exists($categoryName, 'category');
    $categories[$categoryName] = $term ? (int) $term['term_id'] : (int) wp_create_category($categoryName);
}

$imageBase = 'https://loremflickr.com/1200/800/kitchen,countertop';
$articles = [
    ['category' => 'Matériaux', 'slug' => 'choisir-bons-materiaux-cuisine-premium', 'title' => 'Choisir les bons matériaux pour une cuisine premium', 'keyword' => 'choisir les bons matériaux cuisine premium', 'excerpt' => 'Les repères essentiels pour sélectionner une surface cohérente avec une cuisine élégante, durable et simple à vivre.', 'section_1' => 'Partir des usages avant l’apparence', 'section_2' => 'Comparer les matières avec méthode', 'section_3' => 'Préparer le choix final', 'intro' => 'un matériau se choisit pour son rendu, mais aussi pour sa résistance, son toucher et sa tenue dans le temps.', 'advice' => 'La céramique répond très bien aux projets qui cherchent une lecture minérale, stable et facile à entretenir.', 'mistake' => 'L’erreur serait de choisir une finition seule, sans tenir compte des meubles, du sol et de la lumière.', 'takeaways' => ['Comparer usage, entretien et rendu visuel.', 'Vérifier la cohérence avec les façades.', 'Anticiper les découpes avant fabrication.'], 'cta' => 'Voir les matériaux'],
    ['category' => 'Entretien', 'slug' => 'entretien-cuisine-ceramique-exception', 'title' => 'Entretien cuisine d’exception : garder une surface belle sans effort', 'keyword' => 'entretien cuisine céramique', 'excerpt' => 'Une méthode simple pour conserver une surface céramique nette, lumineuse et agréable au quotidien.', 'section_1' => 'Installer une routine simple', 'section_2' => 'Nettoyer sans agresser la surface', 'section_3' => 'Préserver le rendu dans le temps', 'intro' => 'l’entretien doit rester fluide pour que la cuisine garde son niveau visuel sans devenir une contrainte.', 'advice' => 'Un chiffon doux, une éponge non abrasive et un nettoyage régulier suffisent dans la majorité des usages.', 'mistake' => 'Il ne faut pas attendre que les traces s’accumulent autour de l’évier, des joints ou de la plaque.', 'takeaways' => ['Nettoyer rapidement après préparation.', 'Éviter les accessoires abrasifs.', 'Surveiller les joints et les chants.'], 'cta' => 'Demander un devis', 'video_query' => 'https://www.youtube.com/watch?v=d7zzPCpZRr8'],
    ['category' => 'Tendances', 'slug' => 'tendances-cuisine-2026-elegance-service-perennite', 'title' => 'Tendances cuisine 2026 : l’élégance au service de la pérennité', 'keyword' => 'tendances cuisine 2026', 'excerpt' => 'Les tendances à garder pour une cuisine qui reste actuelle, sobre et durable plusieurs années.', 'section_1' => 'Choisir une tendance durable', 'section_2' => 'Miser sur les matières sobres', 'section_3' => 'Adapter la tendance au projet', 'intro' => 'la tendance utile n’est pas celle qui crie le plus fort, mais celle qui reste juste dans le temps.', 'advice' => 'Les tons minéraux, les veinages maîtrisés et les contrastes doux s’intègrent bien dans une cuisine premium.', 'mistake' => 'Le risque est de copier une ambiance sans vérifier si elle correspond à la lumière réelle de la pièce.', 'takeaways' => ['Privilégier les tendances sobres.', 'Associer matière, lumière et façades.', 'Garder une cuisine facile à vivre.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Conception', 'slug' => 'conception-cuisine-reve-personnalisation-excellence', 'title' => 'Conception de cuisine de rêve : personnalisation et excellence', 'keyword' => 'conception cuisine de rêve', 'excerpt' => 'Comment cadrer une cuisine haut de gamme avec une surface adaptée, des proportions justes et un rendu cohérent.', 'section_1' => 'Définir le niveau d’ambition', 'section_2' => 'Personnaliser sans compliquer', 'section_3' => 'Valider les détails visibles', 'intro' => 'la personnalisation doit renforcer le confort et la cohérence, pas multiplier les décisions inutiles.', 'advice' => 'Un plan en céramique permet de structurer l’ensemble avec une matière forte mais maîtrisée.', 'mistake' => 'Il faut éviter de tout miser sur un seul effet décoratif si les proportions ne sont pas justes.', 'takeaways' => ['Clarifier les usages avant le style.', 'Choisir une matière qui structure la pièce.', 'Soigner chants, crédence et jonctions.'], 'cta' => 'Préparer mon devis'],
    ['category' => 'Matériaux', 'slug' => 'materiaux-cuisine-combiner-noblesse-innovation', 'title' => 'Matériaux cuisine : combiner noblesse et innovation', 'keyword' => 'matériaux cuisine nobles', 'excerpt' => 'Céramique, bois, métal et pierre : comment composer une cuisine raffinée sans perdre en praticité.', 'section_1' => 'Créer une alliance équilibrée', 'section_2' => 'Relier innovation et usage quotidien', 'section_3' => 'Construire une palette durable', 'intro' => 'les matériaux nobles donnent du caractère lorsqu’ils sont choisis avec une vraie logique d’usage.', 'advice' => 'La céramique apporte la résistance et le bois peut ajouter la chaleur, à condition de garder des proportions justes.', 'mistake' => 'Trop de matières fortes dans un même espace peuvent brouiller la lecture premium.', 'takeaways' => ['Limiter le nombre de matières dominantes.', 'Associer surface résistante et touches chaleureuses.', 'Préserver une palette lisible.'], 'cta' => 'Voir les matériaux'],
    ['category' => 'Conception', 'slug' => 'conception-cuisine-sur-mesure-art-allier-noblesse-intemporalite', 'title' => 'Conception cuisine sur mesure : allier noblesse et intemporalité', 'keyword' => 'cuisine sur mesure intemporelle', 'excerpt' => 'Les choix qui permettent de créer une cuisine élégante sans dépendre d’une mode passagère.', 'section_1' => 'Penser au-delà de la photo d’inspiration', 'section_2' => 'Choisir des finitions qui vieillissent bien', 'section_3' => 'Garder une base technique claire', 'intro' => 'une cuisine sur mesure réussie doit rester belle et logique plusieurs années après la pose.', 'advice' => 'Les effets pierre, marbre doux et béton minéral fonctionnent bien lorsqu’ils restent proportionnés.', 'mistake' => 'Une finition trop spectaculaire peut fatiguer si elle domine toute la pièce.', 'takeaways' => ['Choisir une base sobre.', 'Utiliser la matière pour donner du relief.', 'Valider les détails avant fabrication.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Entretien', 'slug' => 'cuisiner-perfection-gestes-quotidiens-cuisine', 'title' => 'Cuisiner à la perfection : les gestes quotidiens qui protègent la cuisine', 'keyword' => 'gestes quotidiens cuisine céramique', 'excerpt' => 'Des habitudes simples pour garder une cuisine premium agréable à utiliser tous les jours.', 'section_1' => 'Respecter les zones les plus sollicitées', 'section_2' => 'Préserver les bords et les raccords', 'section_3' => 'Installer de bons réflexes', 'intro' => 'la qualité d’une cuisine se voit aussi dans les gestes ordinaires : préparation, nettoyage, rangement et protection.', 'advice' => 'Même avec une surface résistante, une planche à découper et un nettoyage doux prolongent la beauté du plan.', 'mistake' => 'Négliger les arêtes, les angles et les joints finit par diminuer la perception haut de gamme.', 'takeaways' => ['Protéger les zones de coupe.', 'Nettoyer sans produits agressifs.', 'Garder les raccords propres.'], 'cta' => 'Demander un devis'],
    ['category' => 'Matériaux', 'slug' => 'materiaux-reve-cuisine-luxe-selection-harmonieuse', 'title' => 'Matériaux de rêve pour une cuisine de luxe : l’art de la sélection harmonieuse', 'keyword' => 'matériaux cuisine de luxe', 'excerpt' => 'Comment sélectionner les matières d’une cuisine de luxe sans tomber dans l’excès visuel.', 'section_1' => 'Chercher la cohérence avant l’effet', 'section_2' => 'Créer un dialogue entre surfaces', 'section_3' => 'Finaliser avec sobriété', 'intro' => 'le luxe se construit souvent dans la précision, la simplicité et la cohérence des matières.', 'advice' => 'Une céramique bien choisie peut devenir le point d’ancrage de la cuisine sans écraser le reste.', 'mistake' => 'Multiplier les effets marbre, métal brillant et bois très marqué peut rendre l’ensemble confus.', 'takeaways' => ['Choisir une matière principale.', 'Limiter les contrastes inutiles.', 'Travailler les détails de finition.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Matériaux', 'slug' => 'secrets-materiaux-nobles-cuisine-reve', 'title' => 'Les secrets des matériaux nobles pour une cuisine de rêve', 'keyword' => 'matériaux nobles cuisine', 'excerpt' => 'Les repères pour reconnaître une matière qui apporte vraiment de la valeur à la cuisine.', 'section_1' => 'Comprendre ce qui rend une matière noble', 'section_2' => 'Évaluer la tenue dans le quotidien', 'section_3' => 'Choisir sans surcharger', 'intro' => 'une matière noble n’est pas seulement belle : elle doit aussi rester pertinente dans l’usage.', 'advice' => 'La céramique garde une lecture minérale tout en répondant aux contraintes d’une cuisine active.', 'mistake' => 'Confondre rareté visuelle et qualité d’usage peut mener à un choix difficile à vivre.', 'takeaways' => ['Regarder la résistance autant que le style.', 'Faire dialoguer les matières.', 'Éviter l’accumulation décorative.'], 'cta' => 'Voir les matériaux'],
    ['category' => 'Tendances', 'slug' => 'tendances-cuisine-2024-2026-elegance-perenne', 'title' => 'Tendances cuisine 2024-2026 : intégrer l’élégance pérenne à votre projet', 'keyword' => 'tendances cuisine 2024 2026', 'excerpt' => 'Les tendances utiles à conserver pour construire une cuisine moderne mais durable.', 'section_1' => 'Séparer mode courte et vraie direction', 'section_2' => 'Choisir des matières qui traversent le temps', 'section_3' => 'Adapter les tendances au plan de travail', 'intro' => 'les tendances les plus intéressantes sont celles qui améliorent la cuisine sans la dater trop vite.', 'advice' => 'Les tons pierre, les surfaces satinées et les lignes calmes donnent une base durable.', 'mistake' => 'Suivre une couleur très présente sans vérifier la lumière et le sol peut déséquilibrer la pièce.', 'takeaways' => ['Garder une base intemporelle.', 'Utiliser les tendances par touches.', 'Valider la matière avec la lumière réelle.'], 'cta' => 'Explorer les collections', 'video_query' => 'https://www.youtube.com/watch?v=u3kktvKt8gI'],
    ['category' => 'Conception', 'slug' => 'ilot-central-ceramique-cuisine-ouverte', 'title' => 'Îlot central en céramique : réussir une cuisine ouverte', 'keyword' => 'îlot central céramique cuisine ouverte', 'excerpt' => 'Dimensions, débords, circulation et finitions : les points clés d’un îlot central réussi.', 'section_1' => 'Donner une fonction claire à l’îlot', 'section_2' => 'Soigner les proportions visibles', 'section_3' => 'Préparer livraison et pose', 'intro' => 'un îlot central devient vite le centre visuel et pratique de la cuisine ouverte.', 'advice' => 'La céramique convient très bien à l’îlot, mais les chants, débords et découpes doivent être anticipés.', 'mistake' => 'Un îlot trop grand ou mal placé peut rendre la circulation moins confortable.', 'takeaways' => ['Définir les usages avant les dimensions.', 'Soigner les chants visibles.', 'Vérifier les accès de livraison.'], 'cta' => 'Préparer mon devis'],
    ['category' => 'Conception', 'slug' => 'credence-assortie-plan-travail-ceramique', 'title' => 'Crédence assortie au plan de travail : quand la céramique change tout', 'keyword' => 'crédence céramique assortie', 'excerpt' => 'Pourquoi une crédence assortie peut donner plus de continuité et de profondeur à la cuisine.', 'section_1' => 'Créer une continuité visuelle', 'section_2' => 'Choisir la bonne hauteur', 'section_3' => 'Éviter l’effet trop massif', 'intro' => 'la crédence peut transformer la perception du plan de travail et renforcer le style de la cuisine.', 'advice' => 'Une céramique assortie donne une lecture plus architecturale, surtout avec un veinage maîtrisé.', 'mistake' => 'Monter trop haut avec une finition très expressive peut alourdir l’espace.', 'takeaways' => ['Assortir pour un rendu plus premium.', 'Adapter la hauteur à la pièce.', 'Coordonner prises et découpes.'], 'cta' => 'Voir les matériaux'],
    ['category' => 'Matériaux', 'slug' => 'effet-marbre-ceramique-cuisine-chic', 'title' => 'Effet marbre en céramique : réussir une cuisine chic sans fragilité', 'keyword' => 'céramique effet marbre cuisine', 'excerpt' => 'Les conseils pour utiliser l’effet marbre avec élégance dans une cuisine sur mesure.', 'section_1' => 'Comprendre la force du veinage', 'section_2' => 'Choisir un contraste juste', 'section_3' => 'Coordonner le reste de la cuisine', 'intro' => 'l’effet marbre apporte immédiatement une impression de luxe, mais il doit rester maîtrisé.', 'advice' => 'Un veinage doux se marie bien avec des façades sobres, tandis qu’un contraste fort demande plus d’espace.', 'mistake' => 'Choisir un marbre trop chargé dans une petite cuisine peut réduire la sensation de calme.', 'takeaways' => ['Adapter le veinage à la taille de la pièce.', 'Associer avec des façades calmes.', 'Anticiper les raccords visibles.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Matériaux', 'slug' => 'effet-pierre-cuisine-minerale-chaleureuse', 'title' => 'Effet pierre : créer une cuisine minérale et chaleureuse', 'keyword' => 'céramique effet pierre cuisine', 'excerpt' => 'Comment l’effet pierre apporte de la profondeur sans rendre la cuisine froide.', 'section_1' => 'Trouver le bon équilibre minéral', 'section_2' => 'Réchauffer avec les bonnes associations', 'section_3' => 'Valider le rendu en lumière naturelle', 'intro' => 'l’effet pierre donne une impression naturelle et stable, idéale pour les cuisines sobres.', 'advice' => 'Associé au bois, au blanc cassé ou au métal doux, il peut rester chaleureux et contemporain.', 'mistake' => 'Une pierre trop grise dans une pièce peu lumineuse peut paraître froide.', 'takeaways' => ['Utiliser la pierre pour apaiser la cuisine.', 'Réchauffer avec bois ou tons doux.', 'Observer le rendu en lumière réelle.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Matériaux', 'slug' => 'effet-beton-cuisine-contemporaine', 'title' => 'Effet béton : donner un style contemporain à une cuisine sur mesure', 'keyword' => 'céramique effet béton cuisine', 'excerpt' => 'Les avantages d’un effet béton pour une cuisine sobre, graphique et facile à vivre.', 'section_1' => 'Installer une base architecturale', 'section_2' => 'Éviter la froideur visuelle', 'section_3' => 'Adapter les détails de finition', 'intro' => 'l’effet béton fonctionne bien quand on cherche une cuisine contemporaine, calme et structurée.', 'advice' => 'La céramique effet béton apporte cette lecture sans imposer l’entretien d’un béton brut.', 'mistake' => 'Un total look gris peut manquer de relief si aucune matière chaude ne l’accompagne.', 'takeaways' => ['Créer une base sobre.', 'Ajouter des matières plus chaudes.', 'Soigner éclairage et poignées.'], 'cta' => 'Voir les matériaux'],
    ['category' => 'Conception', 'slug' => 'petite-cuisine-plan-travail-ceramique', 'title' => 'Petite cuisine : bien choisir un plan de travail en céramique', 'keyword' => 'petite cuisine plan travail céramique', 'excerpt' => 'Les choix de couleur, finition et épaisseur qui agrandissent visuellement une petite cuisine.', 'section_1' => 'Garder une lecture légère', 'section_2' => 'Choisir la bonne couleur', 'section_3' => 'Optimiser chaque détail', 'intro' => 'dans une petite cuisine, chaque choix de surface modifie la sensation d’espace.', 'advice' => 'Les tons clairs, les veinages doux et une crédence bien pensée peuvent agrandir visuellement la pièce.', 'mistake' => 'Une finition trop sombre ou trop contrastée peut couper le volume.', 'takeaways' => ['Privilégier les teintes lumineuses.', 'Limiter les motifs trop présents.', 'Utiliser la crédence pour prolonger la surface.'], 'cta' => 'Préparer mon devis'],
    ['category' => 'Conception', 'slug' => 'cuisine-ouverte-harmoniser-plan-travail-sejour', 'title' => 'Cuisine ouverte : harmoniser le plan de travail avec le séjour', 'keyword' => 'cuisine ouverte plan travail', 'excerpt' => 'Comment choisir une surface qui reste élégante depuis la cuisine comme depuis le salon.', 'section_1' => 'Penser la cuisine comme une pièce de vie', 'section_2' => 'Coordonner les matières visibles', 'section_3' => 'Garder une transition douce', 'intro' => 'une cuisine ouverte se regarde depuis plusieurs angles, ce qui rend le plan de travail très important.', 'advice' => 'Une surface minérale sobre permet de relier les meubles de cuisine au mobilier du séjour.', 'mistake' => 'Un contraste trop dur peut créer une rupture entre cuisine et espace de vie.', 'takeaways' => ['Observer la cuisine depuis le séjour.', 'Coordonner sol, meubles et plan.', 'Choisir une finition lisible de loin.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Conception', 'slug' => 'budget-plan-travail-ceramique-comprendre-prix', 'title' => 'Budget plan de travail céramique : comprendre ce qui influence le prix', 'keyword' => 'budget plan travail céramique', 'excerpt' => 'Dimensions, matière, découpes et pose : les facteurs qui font varier le budget d’un projet.', 'section_1' => 'Regarder le projet complet', 'section_2' => 'Comprendre les postes techniques', 'section_3' => 'Préparer une demande précise', 'intro' => 'le budget dépend rarement de la surface seule : il dépend aussi de la complexité réelle du projet.', 'advice' => 'Les découpes, chants, retombées, crédences et contraintes de pose influencent fortement l’estimation.', 'mistake' => 'Comparer deux prix sans comparer le même niveau de finition peut induire en erreur.', 'takeaways' => ['Lister les dimensions et découpes.', 'Préciser les finitions attendues.', 'Comparer des devis équivalents.'], 'cta' => 'Demander un devis'],
    ['category' => 'Conception', 'slug' => 'demande-devis-plan-travail-ceramique-complete', 'title' => 'Demande de devis : les informations à fournir pour un plan de travail céramique', 'keyword' => 'devis plan travail céramique', 'excerpt' => 'La checklist des éléments utiles pour obtenir une réponse claire et adaptée à votre cuisine.', 'section_1' => 'Envoyer une base exploitable', 'section_2' => 'Ajouter les détails techniques', 'section_3' => 'Accélérer la réponse', 'intro' => 'une demande de devis bien préparée permet de gagner du temps et d’éviter les allers-retours inutiles.', 'advice' => 'Photos, dimensions, ville, type de finition et contraintes d’accès forment une base solide.', 'mistake' => 'Oublier l’évier, la plaque ou la crédence peut changer la lecture du projet.', 'takeaways' => ['Joindre photos et dimensions.', 'Indiquer la finition souhaitée.', 'Préciser les contraintes d’accès.'], 'cta' => 'Préparer mon devis'],
    ['category' => 'Conception', 'slug' => 'livraison-plan-travail-ceramique-anticipee', 'title' => 'Livraison d’un plan de travail céramique : ce qu’il faut anticiper', 'keyword' => 'livraison plan travail céramique', 'excerpt' => 'Accès, poids, étages et protection : les points à vérifier avant la livraison.', 'section_1' => 'Traiter la livraison comme une étape du projet', 'section_2' => 'Contrôler les accès', 'section_3' => 'Préparer la pièce', 'intro' => 'la livraison influence directement la qualité et la sérénité du chantier.', 'advice' => 'Il faut vérifier portes, escaliers, ascenseur, stationnement et distance entre le déchargement et la cuisine.', 'mistake' => 'Découvrir un accès compliqué le jour même peut retarder ou compliquer la pose.', 'takeaways' => ['Mesurer les accès principaux.', 'Dégager le chemin de livraison.', 'Prévoir une présence sur place.'], 'cta' => 'Voir nos services'],
    ['category' => 'Conception', 'slug' => 'decoupes-evier-plaque-plan-travail-ceramique', 'title' => 'Découpes évier et plaque : sécuriser un plan de travail en céramique', 'keyword' => 'découpes évier plaque céramique', 'excerpt' => 'Les informations à valider avant fabrication pour éviter les erreurs de découpe.', 'section_1' => 'Identifier les zones techniques', 'section_2' => 'Valider les appareils choisis', 'section_3' => 'Contrôler les marges utiles', 'intro' => 'les découpes techniques doivent être anticipées avec précision avant fabrication.', 'advice' => 'Les références d’évier, de plaque et d’accessoires permettent de préparer des ouvertures plus fiables.', 'mistake' => 'Changer d’appareil après validation peut rendre les découpes incohérentes.', 'takeaways' => ['Fournir les références appareils.', 'Confirmer les emplacements.', 'Vérifier les distances de sécurité.'], 'cta' => 'Préparer mon devis', 'video_query' => 'https://www.youtube.com/watch?v=NfDgMcgltoo'],
    ['category' => 'Entretien', 'slug' => 'joints-chants-plan-travail-ceramique-finition', 'title' => 'Joints et chants : les détails qui changent la finition d’un plan céramique', 'keyword' => 'joints chants plan céramique', 'excerpt' => 'Pourquoi les bords, raccords et joints visibles comptent autant que la surface principale.', 'section_1' => 'Regarder les détails visibles', 'section_2' => 'Choisir un chant adapté', 'section_3' => 'Préserver la finition', 'intro' => 'les joints et chants donnent souvent la première impression de précision.', 'advice' => 'Un chant bien choisi renforce l’épaisseur perçue et la continuité du plan.', 'mistake' => 'Négliger les raccords peut réduire l’effet premium même avec une belle matière.', 'takeaways' => ['Identifier les chants visibles.', 'Soigner les raccords autour des angles.', 'Nettoyer régulièrement les joints.'], 'cta' => 'Voir les matériaux'],
    ['category' => 'Matériaux', 'slug' => 'epaisseur-plan-travail-ceramique-rendu-premium', 'title' => 'Épaisseur du plan de travail céramique : trouver le bon rendu premium', 'keyword' => 'épaisseur plan travail céramique', 'excerpt' => 'Comment l’épaisseur perçue influence le style, les chants et la présence du plan de travail.', 'section_1' => 'Comprendre l’épaisseur visuelle', 'section_2' => 'Adapter au style de cuisine', 'section_3' => 'Valider avec les chants', 'intro' => 'l’épaisseur modifie immédiatement la perception de solidité et de légèreté.', 'advice' => 'Une ligne fine paraît contemporaine, tandis qu’un chant plus présent donne une lecture plus statutaire.', 'mistake' => 'Choisir une épaisseur sans penser aux meubles peut créer un déséquilibre.', 'takeaways' => ['Comparer finesse et présence.', 'Relier épaisseur et style des façades.', 'Valider les chants visibles.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Conception', 'slug' => 'renovation-cuisine-remplacer-plan-travail-ceramique', 'title' => 'Rénovation cuisine : remplacer un ancien plan par de la céramique', 'keyword' => 'rénovation cuisine plan céramique', 'excerpt' => 'Les vérifications à faire avant de remplacer un plan existant par une surface céramique.', 'section_1' => 'Analyser l’existant', 'section_2' => 'Vérifier les meubles et supports', 'section_3' => 'Préparer une transition propre', 'intro' => 'en rénovation, le nouveau plan doit composer avec une configuration déjà présente.', 'advice' => 'Les meubles doivent être stables, de niveau et capables de recevoir la nouvelle surface.', 'mistake' => 'Ignorer les anciens murs, angles et raccords peut compliquer la pose.', 'takeaways' => ['Photographier l’existant.', 'Contrôler les supports.', 'Prévoir évier, plaque et crédence.'], 'cta' => 'Demander un devis'],
    ['category' => 'Tendances', 'slug' => 'cuisine-haut-gamme-details-qui-font-difference', 'title' => 'Cuisine haut de gamme : les détails qui font vraiment la différence', 'keyword' => 'cuisine haut de gamme détails', 'excerpt' => 'Les choix discrets qui donnent à la cuisine une impression plus raffinée et plus durable.', 'section_1' => 'Chercher la précision', 'section_2' => 'Soigner les transitions', 'section_3' => 'Rester sobre', 'intro' => 'le haut de gamme se voit dans la cohérence générale autant que dans les grands choix.', 'advice' => 'Des joints propres, des chants bien pensés et une matière adaptée font plus qu’un effet spectaculaire.', 'mistake' => 'Ajouter trop de signes de luxe peut rendre la cuisine moins élégante.', 'takeaways' => ['Miser sur la précision.', 'Soigner chaque raccord visible.', 'Garder une palette maîtrisée.'], 'cta' => 'Préparer mon devis'],
    ['category' => 'Conception', 'slug' => 'contraintes-techniques-cuisine-sur-mesure', 'title' => 'Contraintes techniques : les anticiper avant le plan de travail', 'keyword' => 'contraintes techniques cuisine sur mesure', 'excerpt' => 'Murs, angles, arrivées, accès et appareils : ce qu’il faut vérifier avant fabrication.', 'section_1' => 'Voir ce que la photo ne montre pas', 'section_2' => 'Relier technique et esthétique', 'section_3' => 'Limiter les surprises', 'intro' => 'les contraintes techniques influencent directement le rendu final.', 'advice' => 'Un angle irrégulier, une arrivée d’eau ou un appareil non choisi peut modifier la fabrication.', 'mistake' => 'Reporter les décisions techniques à la fin crée souvent des ajustements moins propres.', 'takeaways' => ['Lister les contraintes tôt.', 'Joindre des photos détaillées.', 'Valider les appareils avant fabrication.'], 'cta' => 'Voir nos services'],
    ['category' => 'Tendances', 'slug' => 'cuisine-minimaliste-plan-travail-mineral', 'title' => 'Cuisine minimaliste : réussir un plan de travail minéral sans froideur', 'keyword' => 'cuisine minimaliste plan minéral', 'excerpt' => 'Comment garder une cuisine minimaliste chaleureuse grâce au choix des matières et de la lumière.', 'section_1' => 'Créer une simplicité habitée', 'section_2' => 'Choisir une matière calme', 'section_3' => 'Ajouter de la chaleur', 'intro' => 'le minimalisme fonctionne quand chaque matière a une vraie raison d’être.', 'advice' => 'Une céramique sobre, associée au bois ou à un éclairage chaud, évite l’effet clinique.', 'mistake' => 'Supprimer tout contraste peut rendre la cuisine plate.', 'takeaways' => ['Utiliser peu de matières.', 'Garder une texture visible.', 'Réchauffer avec lumière et bois.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Conception', 'slug' => 'erreurs-eviter-plan-travail-ceramique', 'title' => 'Plan de travail céramique : les erreurs à éviter avant de commander', 'keyword' => 'erreurs plan travail céramique', 'excerpt' => 'Les pièges fréquents qui peuvent compliquer le devis, la fabrication ou la pose.', 'section_1' => 'Ne pas choisir trop vite', 'section_2' => 'Ne pas oublier la technique', 'section_3' => 'Ne pas comparer sans détails', 'intro' => 'un projet peut perdre en qualité si certaines décisions sont prises trop rapidement.', 'advice' => 'Il faut vérifier dimensions, découpes, chants, contraintes d’accès et usage réel avant de valider.', 'mistake' => 'La plus grande erreur est de comparer seulement le prix sans comparer le niveau de finition.', 'takeaways' => ['Valider les dimensions.', 'Choisir la finition avec la lumière.', 'Comparer des devis complets.'], 'cta' => 'Demander un devis'],
    ['category' => 'Conception', 'slug' => 'checklist-projet-cuisine-ceramique', 'title' => 'Checklist projet cuisine céramique : préparer un devis complet', 'keyword' => 'checklist projet cuisine céramique', 'excerpt' => 'La liste des informations à préparer pour un projet de cuisine en céramique plus fluide.', 'section_1' => 'Rassembler les bases', 'section_2' => 'Ajouter les choix esthétiques', 'section_3' => 'Finaliser la demande', 'intro' => 'une checklist simple permet de passer plus vite de l’idée au projet chiffré.', 'advice' => 'Dimensions, photos, finition souhaitée, type de pose et contraintes d’accès donnent une vision claire.', 'mistake' => 'Envoyer une demande trop vague oblige souvent à reprendre tout l’échange.', 'takeaways' => ['Préparer photos et dimensions.', 'Indiquer les finitions aimées.', 'Signaler les contraintes de pose.'], 'cta' => 'Préparer mon devis'],
    ['category' => 'Entretien', 'slug' => 'surface-hygienique-cuisine-ceramique', 'title' => 'Surface hygiénique en cuisine : pourquoi la céramique rassure', 'keyword' => 'surface hygiénique cuisine céramique', 'excerpt' => 'Les raisons pour lesquelles une surface dense et facile à nettoyer simplifie la vie en cuisine.', 'section_1' => 'Comprendre le confort d’usage', 'section_2' => 'Limiter les zones sensibles', 'section_3' => 'Garder une cuisine nette', 'intro' => 'l’hygiène d’une cuisine dépend aussi du choix de la surface principale.', 'advice' => 'Une surface dense et peu contraignante aide à garder le plan propre après les préparations.', 'mistake' => 'Oublier les joints et raccords autour de l’évier peut nuire au confort quotidien.', 'takeaways' => ['Choisir une surface facile à nettoyer.', 'Surveiller les zones humides.', 'Nettoyer avec des produits doux.'], 'cta' => 'Voir les matériaux'],
    ['category' => 'Matériaux', 'slug' => 'couleur-plan-travail-cuisine-bois-blanc', 'title' => 'Cuisine bois et blanc : quelle couleur de plan de travail choisir ?', 'keyword' => 'couleur plan travail cuisine bois blanc', 'excerpt' => 'Les associations qui fonctionnent pour une cuisine bois et blanc élégante et lumineuse.', 'section_1' => 'Lire la température des matières', 'section_2' => 'Choisir entre clair et contraste', 'section_3' => 'Équilibrer l’ensemble', 'intro' => 'le bois et le blanc créent une base chaleureuse que le plan de travail doit accompagner.', 'advice' => 'Une céramique claire agrandit, tandis qu’un ton pierre ou graphite peut structurer la cuisine.', 'mistake' => 'Un plan trop jaune ou trop froid peut casser l’équilibre entre bois et blanc.', 'takeaways' => ['Observer la teinte du bois.', 'Tester clair, pierre ou graphite.', 'Coordonner crédence et poignées.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Tendances', 'slug' => 'cuisine-sans-poignees-plan-travail-elegant', 'title' => 'Cuisine sans poignées : choisir un plan de travail élégant et lisible', 'keyword' => 'cuisine sans poignées plan travail', 'excerpt' => 'Comment une surface minérale complète une cuisine épurée sans la rendre froide.', 'section_1' => 'Renforcer les lignes pures', 'section_2' => 'Choisir une matière qui donne du relief', 'section_3' => 'Soigner les proportions', 'intro' => 'une cuisine sans poignées met encore plus en valeur le plan de travail.', 'advice' => 'Un effet pierre doux ou marbre discret apporte du relief sans casser la ligne épurée.', 'mistake' => 'Une surface trop neutre peut rendre la cuisine trop plate visuellement.', 'takeaways' => ['Garder une ligne claire.', 'Ajouter une texture subtile.', 'Coordonner épaisseur et façades.'], 'cta' => 'Préparer mon devis'],
    ['category' => 'Conception', 'slug' => 'plan-travail-et-credence-meme-matiere', 'title' => 'Plan de travail et crédence dans la même matière : bonne idée ?', 'keyword' => 'plan travail crédence même matière', 'excerpt' => 'Les avantages et les précautions d’une continuité matière entre plan et crédence.', 'section_1' => 'Comprendre l’effet de continuité', 'section_2' => 'Choisir la bonne finition', 'section_3' => 'Gérer les détails techniques', 'intro' => 'utiliser la même matière peut donner une cuisine très cohérente et haut de gamme.', 'advice' => 'La continuité fonctionne mieux avec une finition maîtrisée et des raccords anticipés.', 'mistake' => 'Un motif trop fort sur une grande hauteur peut dominer toute la pièce.', 'takeaways' => ['Créer une continuité élégante.', 'Choisir une finition pas trop chargée.', 'Prévoir prises et découpes.'], 'cta' => 'Explorer les collections'],
    ['category' => 'Entretien', 'slug' => 'nettoyage-apres-pose-plan-travail-ceramique', 'title' => 'Après la pose : comment nettoyer et protéger son plan de travail céramique', 'keyword' => 'nettoyage après pose plan céramique', 'excerpt' => 'Les premiers gestes à adopter après la pose pour garder une surface nette et prête à vivre.', 'section_1' => 'Faire un premier nettoyage doux', 'section_2' => 'Contrôler les finitions', 'section_3' => 'Adopter la bonne routine', 'intro' => 'après la pose, quelques gestes simples aident à partir sur une base propre.', 'advice' => 'Il faut retirer les poussières, nettoyer doucement et vérifier les zones autour des joints.', 'mistake' => 'Utiliser des produits trop agressifs dès le départ peut être inutile et inconfortable.', 'takeaways' => ['Nettoyer doucement après pose.', 'Contrôler chants et raccords.', 'Installer une routine simple.'], 'cta' => 'Voir nos services'],
];

$articles = array_slice($articles, 0, 30);
$pexelsImageIds = [
    36511373,
    37153435,
    36887735,
    36777837,
    13722879,
    8031965,
    18285876,
    11295863,
    28753088,
    37153440,
    36777854,
    19192263,
    36777569,
    36777511,
    36777871,
    37153451,
    36834043,
    36777530,
    36777882,
    12202388,
    6264406,
    15409430,
    10099281,
    8142060,
    4119841,
    8142046,
    8504308,
    10164893,
    7601145,
    7535035,
];
$visibleSlugs = array_column($articles, 'slug');
$legacyPosts = get_posts(
    [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    ]
);

foreach ($legacyPosts as $legacyPostId) {
    $legacyPost = get_post((int) $legacyPostId);

    if (!$legacyPost instanceof WP_Post || in_array((string) $legacyPost->post_name, $visibleSlugs, true)) {
        continue;
    }

    wp_update_post(
        [
            'ID' => (int) $legacyPostId,
            'post_status' => 'draft',
        ]
    );
}

foreach ($articles as $index => $article) {
    $imageId = $pexelsImageIds[$index] ?? $pexelsImageIds[0];
    $imageUrl = 'https://images.pexels.com/photos/' . $imageId . '/pexels-photo-' . $imageId . '.jpeg?auto=compress&cs=tinysrgb&w=1200&h=800&fit=crop';
    $content = pcp_blog_article_content($article, home_url('/demander-un-devis/'));
    $postId = pcp_blog_upsert_post(
        [
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_title' => $article['title'],
            'post_name' => $article['slug'],
            'post_excerpt' => $article['excerpt'],
            'post_content' => $content,
            'post_date' => gmdate('Y-m-d H:i:s', strtotime('-' . (30 - $index) . ' days')),
            'post_date_gmt' => gmdate('Y-m-d H:i:s', strtotime('-' . (30 - $index) . ' days')),
        ]
    );

    wp_set_post_categories($postId, [$categories[$article['category']]]);
    update_post_meta($postId, '_pcp_article_image_url', $imageUrl);
    update_post_meta($postId, '_yoast_wpseo_focuskw', $article['keyword']);
    update_post_meta($postId, '_yoast_wpseo_title', $article['title'] . ' | Plan Céramique Studio');
    update_post_meta($postId, '_yoast_wpseo_metadesc', $article['excerpt']);
    update_post_meta($postId, '_yoast_wpseo_opengraph-image', $imageUrl);
    update_post_meta($postId, '_yoast_wpseo_twitter-image', $imageUrl);

    if (!empty($article['video_query'])) {
        update_post_meta($postId, '_pcp_article_video_embed', $article['video_query']);
    } else {
        delete_post_meta($postId, '_pcp_article_video_embed');
    }
}

update_option('posts_per_page', 10);
flush_rewrite_rules();

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success('30 articles SEO publies avec images differentes et 3 videos.');
}
