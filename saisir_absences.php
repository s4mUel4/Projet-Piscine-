<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'enseignant'){
    header("location: index.html"); exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_inscription = $_POST['id_inscription'];
    $date_cours = $_POST['date_cours'];
    $statut = $_POST['statut'];
    
    // Si c'est justifié, on met un 1 dans la colonne est_justifie de la base de données
    $est_justifie = ($statut == 'Justifié') ? 1 : 0; 

    // On vérifie si un statut existe DÉJÀ pour cet élève, ce cours et cette DATE précise
    $check = $pdo->prepare("SELECT id FROM presences WHERE id_inscription = :id_inscription AND date_cours = :date_cours");
    $check->execute([
        ':id_inscription' => $id_inscription,
        ':date_cours' => $date_cours
    ]);
    
    if($check->rowCount() > 0) {
        $sql = "UPDATE presences SET statut = :statut, est_justifie = :est_justifie WHERE id_inscription = :id_inscription AND date_cours = :date_cours";
    } else {
        $sql = "INSERT INTO presences (id_inscription, date_cours, statut, est_justifie) VALUES (:id_inscription, :date_cours, :statut, :est_justifie)";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_inscription' => $id_inscription, 
        ':date_cours' => $date_cours, 
        ':statut' => $statut, 
        ':est_justifie' => $est_justifie
    ]);

    header("location: enseignant_absences.php?statut=success");
    exit;
}
?>
