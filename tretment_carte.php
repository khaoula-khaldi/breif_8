<?php
session_start();
include 'connexion.php';



if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Erreur : utilisateur non connecté.");
}

$user_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajoute_carte'])) {

    $nom = trim($_POST['nom']);
    $category_name = trim($_POST['category']);
    $category_limite = trim($_POST['limite']);
    $user_id = $_SESSION['user_id'];

    //  Vérifier si la catégorie existe déjà
    $stmt = $pdo->prepare("SELECT id FROM category WHERE nom = ? ");
    $stmt->execute([$category_name]);
    $existing = $stmt->fetch();

    if ($existing) {
        $category_id = $existing['id'];
    } else {
        // Insérer une nouvelle catégorie
        $stmt = $pdo->prepare("INSERT INTO category(nom,limite_mensuelle) VALUES (?,?)");
        $stmt->execute([$category_name,$category_limite]);
        $category_id = $pdo->lastInsertId();
    }

    //  Insérer la carte avec le category_id
    $stmt = $pdo->prepare("INSERT INTO carte(user_id, nom, category_id) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $nom, $category_id]); // Si tu veux mettre category_id à la place, change la colonne

    //  Rediriger après l'ajout
    header('Location: cards.php');
    exit;
}
?>
