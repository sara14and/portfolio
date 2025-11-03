<?php
// lang/en.php
return [
  // hero
  'welcome'           => 'Sara Andari',
  'subtitle'          => 'Computer Engineering Student',
  'hello'             => 'Hey there! Welcome to my portfolio.',

  // navbar
  'nav' => [
    'home'       => 'Home',
    'projects'   => 'Projects',
    'experience' => 'Experience',
    'skills'     => 'Skills',
    'contact'    => 'Contact',
  ],

  // CV button
  'download_cv'       => 'Download my CV',


  // search bar 
  'search_placeholder' => 'Search...',
  'reset_label' => 'Reset search',
  'no_results'   => 'No results found.',
  'match_one'   => '{count} match found.',
  'match_other' => '{count} matches found.',
  'reset_search'       => 'Reset search',
  'search_empty'       => 'Please enter a search term.',

  // AJAX button labels
  'view_desc'         => 'View description',
  'hide_desc'         => 'Hide description',
  'loading'           => 'Loading…',

  // theme toggle
  'theme_dark_label'  => 'Enable dark mode',
  'theme_light_label' => 'Enable light mode',

  // experience data (used by PHP and AJAX)
  'experience_data' => [
    'atlas' => [
      'role'    => 'Technical Support Intern',
      'company' => 'Atlas Copco Compressors Canada',
      'points'  => [
        'Resolved technical support tickets, including special parts and service list requests.',
        'Collaborated with service teams on warranty claims and Engineering Change Bulletin follow-ups.',
        'Managed documentation across internal SharePoint channels.',
      ],
    ],
    'asda' => [
      'role'    => 'R&D Intern',
      'company' => 'Applied Systems Design & Analysis (ASDA) Inc.',
      'points'  => [
        'Conducted architectural research and proposed improvements for the energy model tool.',
        'Contributed to the design of an Energy Calculator using Excel, HTML, and CODERS/SPINE databases.',
      ],
    ],
    'factory' => [
      'role'    => 'General Manager & Communications Lead',
      'company' => 'The Factory - Hardware Design Lab',
      'points'  => [
        'Led workshops and trained peers on lab equipment (soldering, PCB milling, 3D printing).',
        'Organized and promoted hackathons and technical events',
        'Managed social media channels and website',
      ],
    ],
  ],

  // skills section heading + items
  'skills'      => 'Skills',
  'skills_data' => [
    [
      'label' => 'Programming Languages',
      'items' => ['Python', 'Java', 'C', 'Unix/Shell scripting', 'HTML', 'CSS', 'ARM Assembly', 'PHP', 'JavaScript'],
    ],
    [
      'label' => 'Frameworks & Tools',
      'items' => ['Spring Boot', 'PostgreSQL', 'GitHub', 'IntelliJ IDEA', 'VS Code', 'Eclipse', 'PyCharm', 'Vim'],
    ],
    [
      'label' => 'Hardware',
      'items' => ['STM32 (ARM Cortex-M)', 'Arduino', 'Raspberry Pi', 'Soldering', '3D Printing'],
    ],
    [
      'label' => 'Languages',
      'items' => ['French', 'English', 'Krio', 'Arabic'],
    ],
    [
      'label' => 'Soft Skills',
      'items' => ['Adaptable', 'Collaborative', 'Determined', 'Creative', 'Curious', 'Communicative'],
    ],
  ],

  // contact section
  'contact_message' => "Any questions? Let's connect!",

  // contact form labels + button
  'form' => [
  'name'        => 'Name',
  'email'       => 'Email',
  'message'     => 'Message',
  'send'        => 'Send',
  'name_req'    => 'Name is required.',
  'email_req'   => 'Valid email is required.',
  'message_req' => 'Message is required.',
  ],
  'contact_success' => 'Thank you, %s!',
  'contact_error'   => 'Please complete all fields correctly.',
  'contact_mail_sent' => 'Your message is on its way to my inbox.',
  'contact_mail_failed' => 'Message saved, but email notification failed.',
  'contact_mail_disabled' => 'Message saved; email alerts are not configured yet.',
];
