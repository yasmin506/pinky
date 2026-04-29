<<?php
session_start();
require 'postgressconexion.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    
    if ($user === 'admin' && $pass === 'pinky123') {
        $_SESSION['connecte'] = true;
        $_SESSION['utilisateur'] = $user;
        $_SESSION['role'] = 'admin';
        header("Location: index.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE username = :u");
    $stmt->execute(['u' => $user]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($client && password_verify($pass, $client['password'])) {
        $_SESSION['connecte'] = true;
        $_SESSION['utilisateur'] = $client['username'];
        $_SESSION['role'] = 'client';
        header("Location: index.php");
        exit;
    } else {
        $erreur = "Identifiant ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Pinky</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylepinky.css">
    <style>
        .login-wrapper { display:flex; justify-content:center; align-items:center; min-height:70vh; padding:40px 20px; }
        .login-box { background:#fff; border:1px solid #eee; padding:50px 40px; width:100%; max-width:400px; text-align:center; }
        .login-box h2 { font-size:2rem; margin-bottom:8px; }
        .subtitle { color:var(--text-light); font-size:0.85rem; margin-bottom:35px; letter-spacing:0.5px; }
        .form-group { margin-bottom:20px; text-align:left; }
        .form-group label { display:block; font-size:0.75rem; font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-bottom:8px; color:var(--text-light); }
        .form-group input { width:100%; padding:12px 15px; border:1px solid #ccc; font-family:'Montserrat',sans-serif; font-size:0.9rem; box-sizing:border-box; outline:none; transition:border-color 0.3s; }
        .form-group input:focus { border-color:var(--pink-dark); }
        .btn-login { width:100%; background-color:var(--pink-blush); color:var(--text-main); border:none; padding:14px; font-family:'Montserrat',sans-serif; font-size:0.85rem; font-weight:600; letter-spacing:1.5px; text-transform:uppercase; cursor:pointer; transition:background 0.3s; margin-top:10px; }
        .btn-login:hover { background-color:var(--pink-dark); }
        .erreur-msg { background-color:#fff0f0; color:#cc0000; border:1px solid #ffcccc; padding:12px; font-size:0.85rem; margin-bottom:20px; }
        .divider { display:flex; align-items:center; gap:12px; margin:25px 0 20px; }
        .divider::before,.divider::after { content:''; flex:1; height:1px; background-color:#eee; }
        .divider span { font-size:0.75rem; color:var(--text-light); letter-spacing:1px; text-transform:uppercase; }
        .btn-register-link { display:block; width:100%; background-color:var(--bg-white); color:var(--text-main); border:1px solid var(--text-main); padding:14px; font-family:'Montserrat',sans-serif; font-size:0.85rem; font-weight:600; letter-spacing:1.5px; text-transform:uppercase; text-decoration:none; text-align:center; cursor:pointer; transition:all 0.3s; box-sizing:border-box; }
        .btn-register-link:hover { background-color:var(--text-main); color:var(--bg-white); }
    </style>
</head>
<body>
    <div class="promo-bar">BIENVENUE A NOTRE BRAND 💋</div>
    <header>
        <div class="header-container">
            <div class="header-left"></div>
            <div class="header-center"><h1>Pinky</h1></div>
            <div class="header-right"></div>
        </div>
    </header>
    <main>
        <div class="login-wrapper">
            <div class="login-box">
                <h2>Connexion</h2>
                <p class="subtitle">Accédez à votre espace Pinky</p>
                <?php if ($erreur): ?>
                    <div class="erreur-msg"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>
                <form method="POST" action="login.php">
                    <div class="form-group">
                        <label for="username">Identifiant</label>
                        <input type="text" id="username" name="username" placeholder="Votre identifiant" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
                    </div>
                    <button type="submit" class="btn-login">SE CONNECTER</button>
                </form>
                <div class="divider"><span>ou</span></div>
                <a href="register.php" class="btn-register-link">CRÉER UN COMPTE</a>
            </div>
        </div>
    </main>
</body>
</html>