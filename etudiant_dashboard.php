<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'etudiant'){
    header("location: index.html"); exit;
}
require_once 'config.php';

$sql = "SELECT c.code_cours, c.libelle, n.type_evaluation, n.valeur, n.coefficient 
        FROM inscriptions i
        JOIN cours c ON i.id_cours = c.id
        LEFT JOIN notes n ON n.id_inscription = i.id
        WHERE i.id_etudiant = :id_etudiant";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_etudiant' => $_SESSION["id"]]);
$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SmartCampus - Mes Notes</title>
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
        tr:hover { background-color: #f9fafb; }
        .note { font-weight: 800; color: #10b981; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>SMARTCAMPUS</h2>
        <a href="etudiant_dashboard.php" class="active">Mes Notes</a>
        <a href="emploi_du_temps.php">Emploi du temps</a>
        <a href="absences.php">Mes Absences</a>
		<a href="messages.php">Messagerie</a>
        <a href="logout.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
        <div class="header">
            <h1>Mes Notes & Évaluations</h1>
            <div>Étudiant(e) : <strong><?= htmlspecialchars($_SESSION["prenom"] . " " . $_SESSION["nom"]); ?></strong></div>
        </div>
        <div class="card">
            <table>
                <thead>
                    <tr><th>Code</th><th>Cours</th><th>Évaluation</th><th>Note</th><th>Coef.</th></tr>
                </thead>
                <tbody>
                    <?php foreach($resultats as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['code_cours']); ?></td>
                            <td><?= htmlspecialchars($row['libelle']); ?></td>
                            <td><?= htmlspecialchars($row['type_evaluation'] ?? 'Pas encore d\'évaluation'); ?></td>
                            <td class="note"><?= htmlspecialchars($row['valeur'] !== null ? $row['valeur']."/20" : "-"); ?></td>
                            <td><?= htmlspecialchars($row['coefficient'] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>