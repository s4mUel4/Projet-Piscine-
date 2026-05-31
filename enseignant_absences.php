<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'enseignant'){
    header("location: index.html"); exit;
}
require_once 'config.php';

// On récupère les étudiants des cours de ce prof
$sql = "SELECT i.id AS id_inscription, u.nom, u.prenom, c.libelle AS cours_nom 
        FROM inscriptions i
        JOIN utilisateurs u ON i.id_etudiant = u.id
        JOIN cours c ON i.id_cours = c.id
        WHERE c.id_enseignant = :id_enseignant";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_enseignant' => $_SESSION["id"]]);
$etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SmartCampus - Appel</title>
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
        select, input[type="date"] { padding: 8px; border: 1px solid #d1d5db; border-radius: 6px; font-family: 'Inter', sans-serif; }
        button { background: #3b82f6; color: white; border: none; padding: 8px 15px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        button:hover { background: #2563eb; }
        .success-msg { color: #065f46; background-color: #d1fae5; padding: 15px; border-radius: 8px; font-weight: 600; margin-bottom: 20px; border-left: 5px solid #10b981; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>SMARTCAMPUS</h2>
        <a href="enseignant_dashboard.php">Gestion des Notes</a>
        <a href="enseignant_absences.php" class="active">Faire l'Appel</a>
        <a href="logout.php" class="logout">Déconnexion</a>
    </div>
    <div class="content">
        <div class="header">
            <h1>Gestion des Présences</h1>
            <div>Professeur : <strong>Dr. <?= htmlspecialchars($_SESSION["prenom"] . " " . $_SESSION["nom"]); ?></strong></div>
        </div>
        
        <?php if(isset($_GET['statut']) && $_GET['statut'] == 'success'): ?>
            <div class="success-msg">✅ L'appel a été enregistré avec succès !</div>
        <?php endif; ?>
        
        <div class="card">
            <table>
                <thead>
                    <tr><th>Étudiant</th><th>Cours</th><th>Date</th><th>Statut</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach($etudiants as $etudiant): ?>
                    <tr>
                        <form action="saisir_absences.php" method="POST">
                            <input type="hidden" name="id_inscription" value="<?= $etudiant['id_inscription']; ?>">
                            <td><?= htmlspecialchars($etudiant['prenom'] . " " . $etudiant['nom']); ?></td>
                            <td><?= htmlspecialchars($etudiant['cours_nom']); ?></td>
                            <td>
                                <input type="date" name="date_cours" value="<?= date('Y-m-d'); ?>" required>
                            </td>
                            <td>
                                <select name="statut" required>
                                    <option value="Présent">Présent</option>
                                    <option value="Absent">Absent</option>
                                    <option value="Justifié">Justifié</option>
                                </select>
                            </td>
                            <td><button type="submit">Valider</button></td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>