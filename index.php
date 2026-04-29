<?php
session_start();

if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] !== true) {
    header("Location: login.php");
    exit;
}

require 'postgressconexion.php';

$stmt = $pdo->query("SELECT * FROM produits");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

$nb_articles = isset($_SESSION['panier']) ? array_sum($_SESSION['panier']) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinky - Boutique de Beauté</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylepinky.css">
</head>
<body>
    <div class="promo-bar">
        BIENVENUE A NOTRE BRAND 💋
    </div>

    <header>
        <div class="header-container">
            <div class="header-left">
                
                <a href="logout.php" style="color: var(--text-light); font-size: 0.8rem; font-weight: 600; letter-spacing: 1px; text-decoration: none; text-transform: uppercase;">
                    Déconnexion
                </a>
            </div>
            <div class="header-center">
                <h1>Pinky</h1>
            </div>
            <div class="header-right">
                <a href="pinkypanier.php" class="btn-panier">
                    PANIER (<span class="cart-count"><?= $nb_articles ?></span>)
                </a>
            </div>
        </div>
    </header>

    <main>
        <div class="hero-section">
            <h2>Nouveautés Maquillage</h2>
            <p>Découvrez notre dernière collection pour un look parfait.</p>
        </div>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'ajoute'): ?>
            <div class="success-msg">L'article a été ajouté à votre panier avec succès.</div>
        <?php endif; ?>

        <div class="produits-grid">
            <?php foreach ($produits as $p): ?>
                <div class="produit-carte">
                    <div class="image-container">
                        <img src="<?= htmlspecialchars($p['image_url']) ?>" 
                             alt="<?= htmlspecialchars($p['nom']) ?>" 
                             class="produit-image">
                    </div>
                    <h3><?= htmlspecialchars($p['nom']) ?></h3>
                    <p class="prix"><?= number_format($p['prix'], 2) ?> DT</p>
                    <a href="actions.php?action=ajouter&id=<?= $p['id'] ?>" class="btn">AJOUTER AU PANIER</a>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>