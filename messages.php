<?php
session_start();
require_once 'config.php';
if(!isset($_SESSION["loggedin"])) { header("location: index.html"); exit; }
$user_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['message'])) {
    $stmt = $pdo->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $_POST['destinataire_id'], $_POST['message']]);
    header("Location: message.php"); exit;
}

$stmt = $pdo->prepare("SELECT m.*, u.prenom, u.nom FROM messages m 
                       JOIN utilisateurs u ON m.expediteur_id = u.id 
                       WHERE m.destinataire_id = ? ORDER BY date_envoi DESC");
$stmt->execute([$user_id]);
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie</title>
    <style>
        body { font-family: sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .form-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .msg-card { background: white; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 4px solid #2563eb; }
    </style>
</head>
<body>
    <h1>Messagerie</h1>
    <div class="form-container">
        <form method="POST">
            <input type="number" name="destinataire_id" placeholder="ID destinataire" required>
            <textarea name="message" placeholder="Votre message..." required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    </div>
    <h3>RÃ©ception</h3>
    <?php foreach($messages as $msg): ?>
        <div class="msg-card">
            <strong>De : <?= htmlspecialchars($msg['prenom']) ?></strong><br>
            <?= htmlspecialchars($msg['contenu']) ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
