<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'admin'){
    header("location: index.html"); exit;
}
require_once 'config.php';

$stats = $pdo->query("SELECT 
    (SELECT COUNT(*) FROM utilisateurs WHERE role='etudiant') as total_etudiants,
    (SELECT COUNT(*) FROM utilisateurs WHERE role='enseignant') as total_profs,
    (SELECT COUNT(*) FROM cours) as total_cours")->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SmartCampus - Admin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; margin: 0; background-color: #f3f4f6; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #ffffff; padding: 20px; box-shadow: 2px 0 5px rgba(0,0,0,0.05); }
        .sidebar h2 { color: #0056b3; font-weight: 800; font-size: 1.4rem; margin-bottom: 30px; }
        .sidebar a { display: block; padding: 12px 15px; text-decoration: none; color: #4b5563; font-weight: 600; border-radius: 8px; margin-bottom: 5px; }
        .sidebar a.logout { color: #ef4444; margin-top: 50px; font-weight: bold; }
        .content { flex-grow: 1; padding: 40px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #3b82f6; }
        h1 { margin-bottom: 20px; color: #111827; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>SMARTCAMPUS</h2>
        <a href="#" style="background:#eff6ff; color:#2563eb;">Dashboard Global</a>
        <a href="logout.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
        <h1>Dashboard Administrateur</h1>
        <div class="grid">
            <div class="card"><h3><?= $stats['total_etudiants'] ?></h3> Étudiants inscrits</div>
            <div class="card" style="border-color:#fbbf24;"><h3><?= $stats['total_profs'] ?></h3> Enseignants actifs</div>
            <div class="card" style="border-color:#10b981;"><h3><?= $stats['total_cours'] ?></h3> Cours gérés</div>
        </div>
        <p style="margin-top:30px; color: #6b7280;">Système en production - Version 1.0</p>
    </div>
</body>
</html>