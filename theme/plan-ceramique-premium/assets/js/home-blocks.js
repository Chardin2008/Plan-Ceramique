(function (blocks, element, components, blockEditor, i18n) {
  const el = element.createElement;
  const { InspectorControls } = blockEditor;
  const { PanelBody, TextControl, TextareaControl, Notice } = components;
  const { __ } = i18n;

  const text = (label, key) => ({ label, key, type: 'text' });
  const area = (label, key, help) => ({ label, key, type: 'area', help });

  const blockDefinitions = [
    {
      name: 'pcp/home-hero',
      title: 'Accueil - Hero',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'lead'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
        text('Legende', 'caption'),
        text('Bouton principal', 'primaryText'),
        text('URL bouton principal', 'primaryUrl'),
        text('Bouton secondaire', 'secondaryText'),
        text('URL bouton secondaire', 'secondaryUrl'),
        area('Badges', 'badges', 'Un badge par ligne.'),
      ],
    },
    {
      name: 'pcp/home-proof',
      title: 'Accueil - Preuves',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'text'),
        area('Statistiques', 'stats', 'Format : valeur | texte. Une statistique par ligne.'),
        area('Cartes', 'cards', 'Format : titre | texte. Une carte par ligne.'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
        text('Legende', 'caption'),
      ],
    },
    {
      name: 'pcp/home-editorial',
      title: 'Accueil - Introduction',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'text'),
        area('Cartes', 'cards', 'Format : titre | texte. Une carte par ligne.'),
      ],
    },
    {
      name: 'pcp/home-surface',
      title: 'Accueil - Surface',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Cartes', 'cards', 'Format : numero | titre | texte. Une carte par ligne.'),
      ],
    },
    { name: 'pcp/home-materials', title: 'Accueil - Matieres dynamiques', controls: [] },
    {
      name: 'pcp/home-scanner',
      title: 'Accueil - Scanner matiere',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
        text('Eyebrow panneau', 'panelEyebrow'),
        area('Points', 'points', 'Format : numero | x | y | titre | texte. Une ligne par point.'),
      ],
    },
    { name: 'pcp/home-sticky-story', title: 'Accueil - Sticky story', controls: [] },
    {
      name: 'pcp/home-moods',
      title: 'Accueil - Ambiances',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte intro', 'text'),
        area('Cartes', 'cards', 'Format : titre | image | alt | texte | couleurs HEX separees par virgules.'),
        text('Texte lien', 'ctaText'),
        text('URL lien', 'ctaUrl'),
      ],
    },
    {
      name: 'pcp/home-configurator',
      title: 'Accueil - Configurateur',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte intro', 'text'),
        text('Legende projet', 'projectLegend'),
        area('Choix projet', 'projectChoices', 'Un choix par ligne.'),
        text('Legende style', 'styleLegend'),
        area('Choix style', 'styleChoices', 'Un choix par ligne.'),
        text('Legende ambiance', 'moodLegend'),
        area('Choix ambiance', 'moodChoices', 'Un choix par ligne.'),
        text('Libelle resultat', 'resultLabel'),
        text('Titre resultat', 'resultTitle'),
        area('Texte resultat', 'resultText'),
        text('Texte bouton', 'buttonText'),
        text('URL bouton', 'buttonUrl'),
      ],
    },
    { name: 'pcp/home-projects', title: 'Accueil - Realisations', controls: [] },
    {
      name: 'pcp/home-applications',
      title: 'Accueil - Applications',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Applications', 'items', 'Format : titre | image | alt. Une ligne par application.'),
      ],
    },
    {
      name: 'pcp/home-compare',
      title: 'Accueil - Comparateur',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Colonnes', 'columns', 'Titre de colonne puis liste. Separer les colonnes par une ligne vide.'),
      ],
    },
    {
      name: 'pcp/home-process',
      title: 'Accueil - Processus',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Etapes', 'items', 'Une etape par ligne. Option : titre | detail.'),
      ],
    },
    {
      name: 'pcp/home-details',
      title: 'Accueil - Details premium',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Details', 'items', 'Un detail par ligne.'),
      ],
    },
    { name: 'pcp/home-before-after', title: 'Accueil - Avant / Apres', controls: [] },
    { name: 'pcp/home-gallery', title: 'Accueil - Galerie', controls: [] },
    {
      name: 'pcp/home-blog',
      title: 'Accueil - Blog',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        text('Texte lien carte', 'cardLinkText'),
        text('Texte bouton tous les articles', 'moreText'),
      ],
    },
    {
      name: 'pcp/home-testimonials',
      title: 'Accueil - Avis',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte intro', 'text'),
        area('Reperes confiance', 'proofs', 'Un repere par ligne.'),
        text('Texte bouton', 'ctaText'),
        text('URL bouton', 'ctaUrl'),
      ],
    },
    {
      name: 'pcp/home-quote',
      title: 'Accueil - Formulaire devis',
      controls: [text('Eyebrow', 'eyebrow'), text('Titre', 'title'), area('Texte intro', 'text')],
    },
    {
      name: 'pcp/home-final-cta',
      title: 'Accueil - CTA final',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'text'),
        text('Bouton principal', 'primaryText'),
        text('URL bouton principal', 'primaryUrl'),
        text('Bouton secondaire', 'secondaryText'),
        text('URL bouton secondaire', 'secondaryUrl'),
      ],
    },
    {
      name: 'pcp/blog-hero',
      title: 'Blog - Hero',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'lead'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
      ],
    },
    {
      name: 'pcp/blog-archive',
      title: 'Blog - Liste articles',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        text('Articles par page', 'postsPerPage'),
        text('Texte precedent', 'prevText'),
        text('Texte suivant', 'nextText'),
        text('Texte si vide', 'emptyText'),
      ],
    },
    {
      name: 'pcp/services-hero',
      title: 'Services - Hero',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'lead'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
        text('Bouton principal', 'primaryText'),
        text('URL bouton principal', 'primaryUrl'),
        text('Bouton secondaire', 'secondaryText'),
        text('URL bouton secondaire', 'secondaryUrl'),
      ],
    },
    {
      name: 'pcp/services-intro',
      title: 'Services - Introduction',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'text'),
      ],
    },
    {
      name: 'pcp/services-grid',
      title: 'Services - Cartes',
      controls: [
        area('Cartes', 'cards', 'Format : icone | eyebrow | titre | texte. Une carte par ligne.'),
      ],
    },
    {
      name: 'pcp/services-feature',
      title: 'Services - Mise en avant',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'text'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
        area('Liste', 'items', 'Un point par ligne.'),
      ],
    },
    {
      name: 'pcp/services-cta',
      title: 'Services - CTA final',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'text'),
        text('Bouton', 'buttonText'),
        text('URL bouton', 'buttonUrl'),
      ],
    },
    {
      name: 'pcp/detail-hero',
      title: 'Page detail - Hero',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'lead'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
        text('Bouton principal', 'primaryText'),
        text('URL bouton principal', 'primaryUrl'),
        text('Bouton secondaire', 'secondaryText'),
        text('URL bouton secondaire', 'secondaryUrl'),
      ],
    },
    {
      name: 'pcp/detail-intro',
      title: 'Page detail - Introduction',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'text'),
      ],
    },
    {
      name: 'pcp/detail-grid',
      title: 'Page detail - Cartes',
      controls: [
        area('Cartes', 'cards', 'Format : icone | eyebrow | titre | texte. Une carte par ligne.'),
        text('Label accessibilite', 'ariaLabel'),
      ],
    },
    {
      name: 'pcp/detail-feature',
      title: 'Page detail - Mise en avant',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'text'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
        text('Bouton', 'ctaText'),
        text('URL bouton', 'ctaUrl'),
      ],
    },
    {
      name: 'pcp/contact-hero',
      title: 'Contact - Hero',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'lead'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
      ],
    },
    {
      name: 'pcp/contact-layout',
      title: 'Contact - Contenu et formulaire',
      controls: [
        text('Eyebrow panneau', 'eyebrow'),
        text('Titre panneau', 'title'),
        area('Cartes', 'cards', 'Format : icone | titre | texte. Une carte par ligne.'),
        text('Label email', 'emailLabel'),
        text('Label zone', 'zoneLabel'),
        text('Eyebrow formulaire', 'formEyebrow'),
        text('Titre formulaire', 'formTitle'),
        area('Texte formulaire', 'formText'),
      ],
    },
    {
      name: 'pcp/quote-hero',
      title: 'Devis - Hero',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Texte', 'lead'),
        text('Image', 'image'),
        text('Texte alternatif', 'imageAlt'),
      ],
    },
    {
      name: 'pcp/quote-prep',
      title: 'Devis - Preparation',
      controls: [
        text('Eyebrow', 'eyebrow'),
        text('Titre', 'title'),
        area('Etapes', 'items', 'Format : icone | titre | texte. Une etape par ligne.'),
      ],
    },
    {
      name: 'pcp/quote-layout',
      title: 'Devis - Note et formulaire',
      controls: [
        text('Eyebrow note', 'eyebrow'),
        text('Titre note', 'title'),
        area('Texte note', 'text'),
        area('Liste', 'items', 'Un point par ligne.'),
        text('Eyebrow formulaire', 'formEyebrow'),
        text('Titre formulaire', 'formTitle'),
      ],
    },
  ];

  function renderControls(props, definition) {
    if (!definition.controls.length) {
      return el(
        Notice,
        { status: 'info', isDismissible: false },
        __('Ce bloc utilise les contenus dynamiques WordPress existants.', 'plan-ceramique-premium')
      );
    }

    return definition.controls.map((control) => {
      const Component = control.type === 'area' ? TextareaControl : TextControl;

      return el(Component, {
        key: control.key,
        label: control.label,
        value: props.attributes[control.key] || '',
        help: control.help || '',
        onChange: (value) => props.setAttributes({ [control.key]: value }),
      });
    });
  }

  function attributesFor(definition) {
    return definition.controls.reduce((attributes, control) => {
      attributes[control.key] = {
        type: 'string',
        default: '',
      };

      return attributes;
    }, {});
  }

  function previewValue(attributes, keys) {
    for (const key of keys) {
      if (attributes[key]) {
        return attributes[key];
      }
    }

    return '';
  }

  function renderEditorPreview(props, definition) {
    const eyebrow = previewValue(props.attributes, ['eyebrow', 'panelEyebrow', 'formEyebrow']);
    const title = previewValue(props.attributes, ['title', 'formTitle', 'resultTitle']);
    const textValue = previewValue(props.attributes, ['lead', 'text', 'resultText', 'items', 'cards']);

    return el(
      'div',
      { className: 'pcp-home-block-preview__card' },
      el('span', { className: 'pcp-home-block-preview__type' }, definition.title),
      eyebrow ? el('p', { className: 'pcp-home-block-preview__eyebrow' }, eyebrow) : null,
      el('h3', {}, title || definition.title),
      textValue
        ? el('p', { className: 'pcp-home-block-preview__text' }, textValue)
        : el(
            'p',
            { className: 'pcp-home-block-preview__text' },
            __('Bloc dynamique : son rendu public reste gere par le theme.', 'plan-ceramique-premium')
          )
    );
  }

  blockDefinitions.forEach((definition) => {
    blocks.registerBlockType(definition.name, {
      title: definition.title,
      icon: 'layout',
      category: 'design',
      attributes: attributesFor(definition),
      usesContext: ['postId'],
      supports: {
        html: false,
        reusable: false,
      },
      edit: (props) =>
        el(
          'div',
          { className: 'pcp-home-block-preview' },
          el(
            InspectorControls,
            {},
            el(
              PanelBody,
              { title: __('Contenu du bloc', 'plan-ceramique-premium'), initialOpen: true },
              renderControls(props, definition)
            )
          ),
          renderEditorPreview(props, definition)
        ),
      save: () => null,
    });
  });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.i18n);
