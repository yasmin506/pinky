<?php
session_start();
require 'postgressconexion.php';

$panier = $_SESSION['panier'] ?? [];
$produits_panier = [];
$total = 0;

if (!empty($panier)) {
    $ids = array_keys($panier);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    
    $stmt = $pdo->prepare("SELECT * FROM produits WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $produits_panier = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier - Pinky</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylepinky.css">
</head>
<body>
    <div class="promo-bar">
       BIENVENUE A NOTRE BRAND 💋 
    </div>

    <header>
        <div class="header-container">
            <div class="header-left"></div>
            <div class="header-center">
                <h1>Pinky</h1>
            </div>
            <div class="header-right">
                <a href="index.php" class="btn-panier">RETOUR BOUTIQUE</a>
            </div>
        </div>
    </header>

    <main>
        <div class="hero-section" style="margin-bottom: 20px;">
            <h2>Mon Panier</h2>
        </div>
        
        <?php if (empty($produits_panier)): ?>
            <p style="text-align: center;">Votre panier est vide. <a href="index.php" style="color: var(--pink-dark);">Découvrez nos produits</a>.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Sous-total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits_panier as $p): 
                        $qte = $panier[$p['id']];
                        $sous_total = $p['prix'] * $qte;
                        $total += $sous_total;
                    ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="<?= htmlspecialchars($p['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($p['nom']) ?>" 
                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <span><?= htmlspecialchars($p['nom']) ?></span>
                            </div>
                        </td>
                        <td><?= number_format($p['prix'], 2) ?> DT</td>
                        <td>
                            <form action="actions.php?action=modifier&id=<?= $p['id'] ?>" method="POST" class="form-qte">
                                <input type="number" name="quantite" value="<?= $qte ?>" min="1">
                                <button type="submit" title="Mettre à jour">↻</button>
                            </form>
                        </td>
                        <td><strong><?= number_format($sous_total, 2) ?> DT</strong></td>
                        <td><a href="actions.php?action=supprimer&id=<?= $p['id'] ?>" class="btn-delete">✕ Supprimer</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-box">
                <h3>Total : <?= number_format($total, 2) ?> DT</h3>
                <a href="pinkyvalider.php" class="btn-valider">VALIDER LA COMMANDE</a> 
            </div>
        <?php endif; ?>
    </main>
</body>
</html>