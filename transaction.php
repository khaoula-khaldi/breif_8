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
<title>Transaction entre cartes</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col items-center justify-center min-h-screen bg-purple-100">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
<h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Effectuer une transaction</h2>


<form action="transaction.php" method="POST" class="space-y-4">
    
    <div>
        <label class="block mb-1 font-medium text-gray-700">Carte source :</label>
        <select name="id_send" required class="w-full border border-gray-300 p-2 rounded">
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

                echo "<option value='".$c['id']."'>".$c['nom']." (Solde: ".$solde." DH)</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label class="block mb-1 font-medium text-gray-700">Carte destination :</label>
        <select name="id_service" required class="w-full border border-gray-300 p-2 rounded">
            <option value="">Choisir la carte destination</option>
            <?php
            $stmt = $pdo->query("SELECT * FROM carte");
            while($c = $stmt->fetch()){
                echo "<option value='".$c['id']."'>".$c['nom']." (Propriétaire: ".$c['user_id'].")</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <label class="block mb-1 font-medium text-gray-700">Montant :</label>
        <input type="number" step="0.01" name="montant" required class="w-full border border-gray-300 p-2 rounded" placeholder="Montant à transférer">
    </div>

    <div>
        <label class="block mb-1 font-medium text-gray-700">description :</label>
        <input type="text" name="description" required class="w-full border border-gray-300 p-2 rounded" placeholder="Ajouter une description">
    </div>

    <button type="submit" name="transaction" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Envoyer</button>

    <a href="tableborde.php" class="block text-center text-sm text-gray-600 mt-3">
        ← Retour au tableau de bord
    </a>
</form>
</div>

</body>
</html>


<?php


        try{
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction'])){
            $id_send = $_POST['id_send'];         // Carte source
            $id_service = $_POST['id_service'];   // Carte destination
            $montant = $_POST['montant']; 
            $description=$_POST['description'];
            $user_id = $_SESSION['user_id'];

            $pdo->beginTransaction(); // Début de la transaction
            
            $stmt = $pdo->prepare("INSERT INTO incomes(user_id, carte_id, MontantIn, descreptionIn) VALUES (?,?,?,?)");
            $stmt->execute([$user_id, $id_service, $montant,$description]);
            
            $stmt = $pdo->prepare("INSERT INTO expenses(user_id, carte_id, MontantEx, descreptionEx) VALUES (?,?,?,?)");
            $stmt->execute([$user_id, $id_send, $montant,$description]);
            
            $stmt = $pdo -> prepare("INSERT INTO transactions(user_id,carte_source_id,carte_dest_id,montant,description) VALUES (?,?,?,?,?)");
            $stmt->execute([$user_id,$id_send,$id_service,$montant,$description]);

            $pdo->commit();
            
        }  

        }catch(Exception $e){
            $pdo->rollBack();
            echo "Erreur lors de la transaction : " . $e->getMessage();
        }
        
?>
