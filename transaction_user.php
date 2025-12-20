
<?php
session_start();
include 'connexion.php';
if(!isset($_SESSION['user_id'])){
    header('Location: compte_deja.php');
    exit;
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transaction entre utilisateurs</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col items-center justify-center bg-purple-100">
<a href="tableborde.php">Retour au tableau de borde </a>
<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Transaction entre utilisateurs</h2>

        <form action="transaction_user.php" method="POST" class="space-y-4">
                <!-- Carte du destinataire (sera mis à jour via JS si nécessaire) -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Carte destinataire :</label>
                <select name="carte_dest" required class="w-full border p-2 rounded" id="carte_dest">
                    <option value="">Choisir la carte</option>
                </select>
            </div>

            <!-- Montant -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Montant :</label>
                <input type="number" step="0.01" name="montant" required class="w-full border border-gray-300 p-2 rounded" placeholder="Montant à transférer">
            </div>

            <!-- Description -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Description :</label>
                <input type="text" name="description" required class="w-full border border-gray-300 p-2 rounded" placeholder="Ajouter une description">
            </div>

            <!-- Carte source de l'utilisateur connecté -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Carte source :</label>
                <select name="carte_send" required class="w-full border border-gray-300 p-2 rounded">
                    <option value="">Choisir la carte source</option>
                    <?php
                    $stmt = $pdo->prepare("SELECT * FROM carte WHERE user_id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    while($c = $stmt->fetch()){
                        $stmtIn = $pdo->prepare("SELECT SUM(MontantIn) AS totalIn FROM incomes WHERE carte_id = ?");
                        $stmtIn->execute([$c['id']]);
                        $totalIn = $stmtIn->fetch()['totalIn'] ?? 0;

                        $stmtEx = $pdo->prepare("SELECT SUM(MontantEx) AS totalEx FROM expenses WHERE carte_id = ?");
                        $stmtEx->execute([$c['id']]);
                        $totalEx = $stmtEx->fetch()['totalEx'] ?? 0;

                        $solde = $totalIn - $totalEx;

                        echo "<option value='{$c['id']}'>{$c['nom']} (Solde: {$solde} DH)</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Utilisateur destinataire -->
            <div>
                <label class="block mb-1 font-medium text-gray-700">Utilisateur destinataire :</label>
                <select name="user_dest" required class="w-full border p-2 rounded" id="user_dest">
                    <option value="">Choisir utilisateur</option>
                    <?php
                    $stmt = $pdo->prepare("SELECT id, nom FROM utilisateur WHERE id != ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    while($u = $stmt->fetch()){
                        echo "<option value='{$u['id']}'>{$u['nom']}</option>";
                    }
                    ?>
                </select>
            </div>


            <button type="submit" name="transaction" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Envoyer</button>
        </form>
</div>

<?php 

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction'])){
    $user_send = $_SESSION['user_id'];          // Utilisateur qui envoie
    $user_dest = $_POST['user_dest'];           // Utilisateur destinataire
    $carte_send = $_POST['id_send'];            // Carte de l’émetteur
    $carte_dest = $_POST['id_service'];         // Carte du destinataire
    $montant = $_POST['montant'];
    $description = $_POST['description'];
    try{
        $pdo->beginTransaction();

        $stmt=$pdo->prepare("INSERT INTO expenses(user_id,carte_id,MontantEx,descriptionEx) VALUES (?,?,?,?");
        $stmt ->execute([$user_send,$carte_send,$montant,$description]);
        
        $stmt=$pdo->prepare("INSERT INTO incomes(user_id,carte_id,MontantIn,descriptionIn) VALUES (?,?,?,?");
        $stmt ->execute([$user_dest,$carte_dest,$montant,$description]);

        $stmt=$pdo->prepare("INSERT INTO transaction_user(user_id,carte_source,carte_dest_id,montant,description) VALUES (?,?,?,?,?");
        $stmt ->execute([$user_send, $carte_send, $carte_dest, $montant, $description]);

        $pdo->commit();
    }catch(Exception $e){
        $pdo->rollBack();
        echo"Erreur lors de la transaction : ".$e->getMessage();
    }
    }

?>