<?php
// lang/fr.php
return [
  // hero
  'welcome'           => 'Sara Andari',
  'subtitle'          => 'Étudiante en génie informatique',
  'hello'             => 'Salut! Bienvenue sur mon portfolio.',

  // navbar
  'nav' => [
    'home'       => 'Accueil',
    'projects'   => 'Projets',
    'experience' => 'Expérience',
    'skills'     => 'Compétences',
    'contact'    => 'Contact',
  ],

  // CV button
  'download_cv'       => 'Télécharger mon CV',

  // search bar 
  'search_placeholder' => 'Rechercher...',
  'reset_label' => 'Réinitialiser la recherche',
  'no_results'  => 'Aucun résultat trouvé.',
  'match_one'   => '{count} résultat trouvé.',
  'match_other' => '{count} résultats trouvés.',
  'reset_search'       => 'Réinitialiser la recherche',
  'search_empty'       => 'Veuillez entrer un terme de recherche.',

  // AJAX button labels
  'view_desc'         => 'Voir la description',
  'hide_desc'         => 'Masquer la description',
  'loading'           => 'Chargement…',

  // theme toggle
  'theme_dark_label'  => 'Activer le mode sombre',
  'theme_light_label' => 'Activer le mode clair',

  // experience data
  'experience_data' => [
    'atlas' => [
      'role'    => 'Stagiaire support technique',
      'company' => 'Atlas Copco Compressors Canada',
      'points'  => [
        'Résolution de tickets de support technique, y compris demandes de pièces spéciales et listes de service.',
        'Collaboration avec les équipes de service sur les réclamations de garantie et le suivi des bulletins de modification d’ingénierie.',
        'Gestion de la documentation sur les canaux SharePoint internes.',
      ],
    ],
    'asda' => [
      'role'    => 'Stagiaire R&D',
      'company' => 'Applied Systems Design & Analysis (ASDA) Inc.',
      'points'  => [
        'Recherche architecturale et propositions d’améliorations pour l’outil de modélisation énergétique.',
        'Contribution à la conception d’un calculateur énergétique avec Excel, HTML et les bases CODERS/SPINE.',
      ],
    ],
    'factory' => [
      'role'    => 'Directrice générale & Responsable communication',
      'company' => 'The Factory - Hardware Design Lab',
      'points'  => [
        'Animation d’ateliers et formation des pairs sur l’équipement du laboratoire (soudure, fraisage PCB, impression 3D).',
        'Organisation et promotion de hackathons et d’événements techniques.',
        'Gestion des réseaux sociaux et site web',
      ],
    ],
  ],

  // skills section
  'skills'      => 'Compétences',
  'skills_data' => [
    [
      'label' => 'Langages de programmation',
      'items' => ['Python', 'Java', 'C', 'Scripts Unix/Shell', 'HTML', 'CSS', 'ARM Assembly', 'PHP', 'JavaScript'],
    ],
    [
      'label' => 'Frameworks & Outils',
      'items' => ['Spring Boot', 'PostgreSQL', 'GitHub', 'IntelliJ IDEA', 'VS Code', 'Eclipse', 'PyCharm', 'Vim'],
    ],
    [
      'label' => 'Matériel',
      'items' => ['STM32 (ARM Cortex-M)', 'Arduino', 'Raspberry Pi', 'Soudure', 'Impression 3D'],
    ],
    [
      'label' => 'Langues parlées',
      'items' => ['Français', 'Anglais', 'Krio', 'Arabe'],
    ],
    [
      'label' => 'Compétences non techniques',
      'items' => ['Adaptable', 'Collaborative', 'Déterminée', 'Créative', 'Curieuse', 'Communicative'],
    ],
  ],

  // contact section
  'contact_message' => 'Des questions? Contactez‑moi!',

  // contact form
  'form' => [
  'name'        => 'Nom',
  'email'       => 'Email',
  'message'     => 'Message',
  'send'        => 'Envoyer',
  'name_req'    => 'Le nom est requis.',
  'email_req'   => 'Email invalide.',
  'message_req' => 'Le message est requis.',
  ],
  'contact_success' => 'Merci, %s!',
  'contact_error'   => 'Veuillez remplir tous les champs correctement.',
  'contact_mail_sent' => 'Votre message arrive dans ma boîte de réception.',
  'contact_mail_failed' => 'Message enregistré, mais l’envoi de l’email a échoué.',
  'contact_mail_disabled' => 'Message enregistré ; les alertes email ne sont pas encore configurées.',
];
