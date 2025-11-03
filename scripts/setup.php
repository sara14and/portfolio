<?php
require_once __DIR__ . '/../db/database.php';

$db = Database::getInstance();

$db->exec("DROP TABLE IF EXISTS projects");
$db->exec("DROP TABLE IF EXISTS contacts");

// table for projects
$db->exec("
    CREATE TABLE projects (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title_en TEXT NOT NULL,
        title_fr TEXT NOT NULL,
        description_en TEXT NOT NULL,
        description_fr TEXT NOT NULL,
        link TEXT NOT NULL
    )
");

// table for contacts
$db->exec("
    CREATE TABLE IF NOT EXISTS contacts (
        id           INTEGER PRIMARY KEY AUTOINCREMENT,
        name         TEXT    NOT NULL,
        email        TEXT    NOT NULL,
        message      TEXT    NOT NULL,
        submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

// insert entries
$projects = [
    [
        "Capstone Project (In Progress)",
        "Projet de Capstone (en cours)",
        "Developing an open-source benchmark to evaluate LLMs’ ability to generate code from domain models.",
        "Développement d’un cadre d’évaluation open source pour mesurer la capacité des LLMs à générer du code à partir de modèles de domaine.",
        ""
    ],
    [
        "Online Game Store Management System",
        "Système de Gestion de Boutique de Jeux en Ligne",
        "Built a Spring Boot/PostgreSQL web app for video game shop inventory management and customer orders.",
        "Création d’une application web Spring Boot/PostgreSQL pour gérer l’inventaire et les commandes d’une boutique de jeux vidéo.",
        ""
    ],
    [
        "Autonomous Robotics System",
        "Système Robotique Autonome",
        "Designed and programmed an autonomous fire-detecting robot in Python using sensors and grid navigation.",
        "Conception et programmation d’un robot autonome de détection d’incendie en Python avec capteurs et navigation sur grille.",
        ""
    ],
    [
        "AI Model Aggregation for Improved Learning",
        "Agrégation de Modèles IA pour un Apprentissage Amélioré",
        "Implemented decision trees and bagging ensembles in Python to improve generalization across models.",
        "Mise en œuvre d’arbres de décision et d’ensembles bagging en Python pour améliorer la généralisation des modèles.",
        ""
    ],
    [
        "Portfolio Website",
        "Site Portfolio",
        "Responsive portfolio built with PHP, HTML, CSS, and JavaScript.",
        "Portfolio interactif développé avec PHP, HTML, CSS et JavaScript.",
        "https://portefolio-yz5d.onrender.com/"
    ]
];

$stmt = $db->prepare("
    INSERT INTO projects (title_en, title_fr, description_en, description_fr, link)
    VALUES (?, ?, ?, ?, ?)
");

foreach ($projects as $p) {
    $stmt->execute($p);
}

echo "Database seeded with " . count($projects) . " projects.\n";
