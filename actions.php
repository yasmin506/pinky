<?php
session_start();

if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

if ($action === 'ajouter' && $id) {
    $_SESSION['panier'][$id] = ($_SESSION['panier'][$id] ?? 0) + 1;
    header("Location: index.php?msg=ajoute");
    exit;
}

if ($action === 'modifier' && $id && isset($_POST['quantite'])) {
    $qte = (int)$_POST['quantite'];
    if ($qte > 0) $_SESSION['panier'][$id] = $qte;
    else unset($_SESSION['panier'][$id]);
    header("Location: pinkypanier.php");
    exit;
}

if ($action === 'supprimer' && $id) {
    unset($_SESSION['panier'][$id]);
    header("Location: pinkypanier.php");
    exit;
}