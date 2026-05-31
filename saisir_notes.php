<?php
// Fichier : C:/MAMP/htdocs/smartcampus/saisir_notes.php
session_start();
require_once 'config.php';

// Sécurité : On bloque l'accès si la personne n'est pas connectée en tant qu'enseignant
if(!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== 'enseignant'){
    die("Accès refusé. Vous devez être enseignant.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On récupère les données envoyées par le formulaire de l'enseignant
    $id_inscription = intval($_POST['id_inscription']);
    $type_evaluation = $_POST['type_evaluation'];
    $valeur = floatval($_POST['valeur']);
    $coefficient = intval($_POST['coefficient']);

    // Requête SQL d'insertion
    // Le "ON DUPLICATE KEY UPDATE" permet de MODIFIER la note au lieu d'en créer une nouvelle si elle existe déjà
    $sql = "INSERT INTO notes (id_inscription, type_evaluation, valeur, coefficient) 
            VALUES (:id_inscription, :type_evaluation, :valeur, :coefficient)
            ON DUPLICATE KEY UPDATE valeur = :valeur, coefficient = :coefficient";

    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':id_inscription', $id_inscription, PDO::PARAM_INT);
        $stmt->bindParam(':type_evaluation', $type_evaluation, PDO::PARAM_STR);
        $stmt->bindParam(':valeur', $valeur);
        $stmt->bindParam(':coefficient', $coefficient, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Tout s'est bien passé -> On renvoie l'enseignant sur son tableau de bord avec un message vert
            header("location: enseignant_dashboard.php?statut=success");
            exit;
        } else {
            echo "Une erreur système est survenue lors de l'enregistrement de la note.";
        }
    }
}
?>
