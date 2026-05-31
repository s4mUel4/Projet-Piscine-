<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'etudiant'){
    header("location: index.html"); exit;
}
require_once 'config.php';

$sql = "SELECT c.libelle, DATE_FORMAT(p.date_cours, '%d/%m/%Y') as date_fr, p.statut 
        FROM presences p
        JOIN inscriptions i ON p.id_inscription = i.id
        JOIN cours c ON i.id_cours = c.id
        WHERE i.id_etudiant = :id_etudiant
        ORDER BY p.date_cours DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_etudiant' => $_SESSION["id"]]);
$absences = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SmartCampus - Absences</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; margin: 0; background-color: #f3f4f6; color: #1f2937; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #ffffff; padding: 20px; box-shadow: 2px 0 5px rgba(0,0,0,0.05); }
        .sidebar h2 { color: #0056b3; font-weight: 800; font-size: 1.4rem; margin-bottom: 30px; }
        .sidebar a { display: block; padding: 12px 15px; text-decoration: none; color: #4b5563; font-weight: 600; border-radius: 8px; margin-bottom: 5px; transition: 0.2s; }
        .sidebar a:hover { background: #f3f4f6; color: #0056b3; }
        .sidebar a.active { background: #eff6ff; color: #2563eb; }
        .sidebar a.logout { color: #ef4444; margin-top: 50px; }
        .content { flex-grow: 1; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        h1 { margin: 0; font-size: 1.8rem; color: #111827; }
        .card { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #f3f4f6; }
        th { background-color: #f9fafb; font-weight: 600; color: #6b7280; text-transform: uppercase; font-size: 0.85rem; }
        .statut-present { color: #065f46; background: #d1fae5; padding: 6px 12px; border-radius: 6px; font-weight: bold;}
        .statut-absent { color: #991b1b; background: #fee2e2; padding: 6px 12px; border-radius: 6px; font-weight: bold;}
        .statut-justifie { color: #92400e; background: #fef3c7; padding: 6px 12px; border-radius: 6px; font-weight: bold;}
    </style>
</head>
