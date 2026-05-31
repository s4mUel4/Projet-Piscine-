<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $sql = "SELECT id, nom, prenom, email, mot_de_passe, role FROM utilisateurs WHERE email = :email";
    
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            if ($row = $stmt->fetch()) {
                if (password_verify($password, $row['mot_de_passe'])) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $row['id'];
                    $_SESSION["nom"] = $row['nom'];
                    $_SESSION["prenom"] = $row['prenom'];
                    $_SESSION["role"] = $row['role'];
                    
                    if ($row['role'] == 'etudiant') {
                        header("location: etudiant_dashboard.php");
                    } elseif ($row['role'] == 'enseignant') {
                        header("location: enseignant_dashboard.php");
                    } elseif ($row['role'] == 'admin') {
                        header("location: admin_dashboard.php");
                    }
                    exit;
                } else {
                    header("location: index.html?erreur=mdp"); exit;
                }
            }
        } else {
            header("location: index.html?erreur=email"); exit;
        }
    }
}
?>