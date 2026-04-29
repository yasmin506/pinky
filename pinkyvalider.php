<?php
session_start();
require 'postgressconexion.php';
$panier = $_SESSION['panier'] ?? [];
if (empty($panier)) { header("Location: index.php"); exit; }

try {
    $pdo->beginTransaction();
    $ids = array_keys($panier);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, prix FROM produits WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $produits_db = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    foreach ($produits_db as $p) { $total += $p['prix'] * $panier[$p['id']]; }

    $stmt_cmd = $pdo->prepare("INSERT INTO commandes (total) VALUES (:t) RETURNING id");
    $stmt_cmd->execute(['t' => $total]);
    $cmd_id = $stmt_cmd->fetchColumn();

    $stmt_det = $pdo->prepare("INSERT INTO details_commande (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
    foreach ($produits_db as $p) {
        $stmt_det->execute([$cmd_id, $p['id'], $panier[$p['id']], $p['prix']]);
    }
    
    $pdo->commit(); // Correction : un seul commit ici
    unset($_SESSION['panier']);
    
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirmation - Pinky</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="stylepinky.css">
        <style>
            .confirmation-container {
                text-align: center;
                padding: 100px 20px;
                max-width: 600px;
                margin: 0 auto;
            }
            .icon-success {
                font-size: 4rem;
                color: var(--pink-dark);
                margin-bottom: 20px;
            }
            .order-number {
                color: var(--text-light);
                font-size: 1.1rem;
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="header-container">
                <div class="header-center" style="margin: 0 auto;">
                    <h1>Pinky</h1>
                </div>
            </div>
        </header>

        <main>
            <div class="confirmation-container">
                <div class="icon-success">♥</div>
                <h2>Félicitations !</h2>
                <p class="success-msg">Votre commande a été validée avec succès.</p>
                <p class="order-number">Numéro de commande : <strong>#<?= $cmd_id ?></strong></p>
                
                <div style="margin-top: 40px;">
                    <a href="index.php" class="btn">RETOUR À LA BOUTIQUE</a>
                </div>
            </div>
        </main>
    </body>
    </html>
    <?php

} catch (Exception $e) { 
    if ($pdo->inTransaction()) {
        $pdo->rollBack(); 
    }
    die("Erreur lors de la validation : " . $e->getMessage()); 
}
?>