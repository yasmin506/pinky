<?php
session_start();
require 'postgressconexion.php';

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm']  ?? '');

    if (empty($username) || empty($password) || empty($confirm)) {
        $erreur = "Veuillez remplir tous les champs.";
    } elseif ($password !== $confirm) {
        $erreur = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) {
        $erreur = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        
        $stmt = $pdo->prepare("SELECT id FROM clients WHERE username = :u");
        $stmt->execute(['u' => $username]);
        if ($stmt->fetch()) {
            $erreur = "Cet identifiant est déjà utilisé.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO clients (username, password) VALUES (:u, :p)");
            $stmt->execute(['u' => $username, 'p' => $hash]);
            $succes = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Pinky</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylepinky.css">
    <style>
        .register-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 70vh;
            padding: 40px 20px;
        }
        .register-box {
            background: #fff;
            border: 1px solid #eeeeee;
            padding: 50px 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .register-box h2 {
            font-size: 2rem;
            margin-bottom: 8px;
        }
        .register-box p.subtitle {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-bottom: 35px;
            letter-spacing: 0.5px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
            color: var(--text-light);
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #cccccc;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            border-color: var(--pink-dark);
        }
        .btn-register {
            width: 100%;
            background-color: var(--pink-blush);
            color: var(--text-main);
            border: none;
            padding: 14px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        .btn-register:hover {
            background-color: var(--pink-dark);
        }
        .erreur-msg {
            background-color: #fff0f0;
            color: #cc0000;
            border: 1px solid #ffcccc;
            padding: 12px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        .succes-msg {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            padding: 12px;
            font-size: 0.85rem;
            margin-bottom: 20px;
        }
        .link-login {
            margin-top: 25px;
            font-size: 0.82rem;
            color: var(--text-light);
        }
        .link-login a {
            color: var(--pink-dark);
            font-weight: 600;
            text-decoration: none;
        }
        .link-login a:hover {
            text-decoration: underline;
        }
    </style>
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
            <div class="header-right"></div>
        </div>
    </header>

    <main>
        <div class="register-wrapper">
            <div class="register-box">
                <h2>Créer un compte</h2>
                <p class="subtitle">Rejoignez la communauté Pinky</p>

                <?php if ($erreur): ?>
                    <div class="erreur-msg"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <?php if ($succes): ?>
                    <div class="succes-msg"><?= htmlspecialchars($succes) ?></div>
                    <a href="login.php" class="btn-register" style="display:block; text-decoration:none; text-align:center; padding: 14px; margin-top: 0;">
                        SE CONNECTER
                    </a>
                <?php else: ?>
                    <form method="POST" action="register.php">
                        <div class="form-group">
                            <label for="username">Identifiant</label>
                            <input type="text" id="username" name="username" placeholder="Choisissez un identifiant" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" id="password" name="password" placeholder="Minimum 6 caractères" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm">Confirmer le mot de passe</label>
                            <input type="password" id="confirm" name="confirm" placeholder="Répétez le mot de passe" required>
                        </div>
                        <button type="submit" class="btn-register">CRÉER MON COMPTE</button>
                    </form>
                <?php endif; ?>

                <div class="link-login">
                    Vous avez déjà un compte ? <a href="login.php">Se connecter</a>
                </div>
            </div>
        </div>
    </main>

</body>
</html>