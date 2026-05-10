(function (blocks, element, components, blockEditor, serverSideRender, i18n) {
  const el = element.createElement;
  const ServerSideRender = serverSideRender;
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
        area('Cartes', 'cards', 'Format : titre | image | alt | texte | couleurs HEX separees par virgules.'),
        text('Texte lien', 'ctaText'),
        text('URL lien', 'ctaUrl'),
      ],
    },
    {
      name: 'pcp/home-configurator',
      title: 'Accueil - Configurateur',
      controls: [text('Eyebrow', 'eyebrow'), text('Titre', 'title')],
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
        area('Etapes', 'items', 'Une etape par ligne.'),
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
      controls: [text('Eyebrow', 'eyebrow'), text('Titre', 'title')],
    },
    { name: 'pcp/home-testimonials', title: 'Accueil - Avis', controls: [] },
    {
      name: 'pcp/home-quote',
      title: 'Accueil - Formulaire devis',
      controls: [text('Eyebrow', 'eyebrow'), text('Titre', 'title')],
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

  blockDefinitions.forEach((definition) => {
    blocks.registerBlockType(definition.name, {
      title: definition.title,
      icon: 'layout',
      category: 'design',
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
          el(ServerSideRender, {
            block: definition.name,
            attributes: props.attributes,
          })
        ),
      save: () => null,
    });
  });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.serverSideRender, window.wp.i18n);
