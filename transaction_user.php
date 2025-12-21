<?php
session_start();
include 'connexion.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: compte_deja.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Transaction entre utilisateurs</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-purple-100">

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Transaction entre utilisateurs</h2>

    <form method="POST" action="transaction_user.php" class="space-y-4">

        <!-- Carte source (user connecté) -->
        <div>
            <label class="block mb-1 font-medium">Carte source</label>
            <select name="carte_send" required class="w-full border p-2 rounded">
                <option value="">Choisir la carte</option>
                <?php
                $stmt = $pdo->prepare("SELECT id, nom FROM carte WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                while ($c = $stmt->fetch()) {
                    echo "<option value='{$c['id']}'>{$c['nom']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Utilisateur destinataire -->
        <div>
            <label class="block mb-1 font-medium">Utilisateur destinataire</label>
            <select name="user_dest" required class="w-full border p-2 rounded">
                <option value="">Choisir utilisateur</option>
                <?php
                $stmt = $pdo->prepare("SELECT id, nomcomplet FROM utilisateur WHERE id != ?");
                $stmt->execute([$_SESSION['user_id']]);
                while ($u = $stmt->fetch()) {
                    echo "<option value='{$u['id']}'>{$u['nomcomplet']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Montant -->
        <div>
            <label class="block mb-1 font-medium">Montant</label>
            <input type="number" step="0.01" name="montant" required
                   class="w-full border p-2 rounded"
                   placeholder="Ex: 100">
        </div>

        <!-- Description -->
        <div>
            <label class="block mb-1 font-medium">Description</label>
            <input type="text" name="description" required
                   class="w-full border p-2 rounded"
                   placeholder="Ex: Remboursement">
        </div>

        <!-- Bouton -->
        <button type="submit" name="transaction"
                class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">
            Envoyer
        </button>

        <a href="tableborde.php" class="block text-center text-sm text-gray-600 mt-3">
            ← Retour au tableau de bord
        </a>

    </form>
</div>

</body>
</html>


<?php
        if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST['transaction'])){
            
            $carte_send=$_POST['carte_send'];
            $user_dest=$_POST['user_dest'];
            $user_send=$_SESSION['user_id'];
            $montant=$_POST['montant'];
            $description=$_POST['description'];
            try{
                $pdo->beginTransaction();

                $stmt=$pdo->prepare("SELECT id FROM carte WHERE user_id=?");
                $stmt->execute([$user_dest]);
                $carte_dest = $stmt->fetchColumn();

                if ($carte_dest === false) {
                    throw new Exception("❌ L'utilisateur destinataire n'a pas de carte");
                }

                $stmt=$pdo->prepare("INSERT INTO expenses(user_id,carte_id,MontantEx,descreptionEx)VALUES (?,?,?,?)");
                $stmt->execute([$user_send,$carte_send,$montant,$description]);
                
                $stmt=$pdo->prepare("INSERT INTO incomes(user_id,carte_id,MontantIn,descreptionIn)VALUES(?,?,?,?)");
                $stmt->execute([$user_dest,$carte_dest,$montant,$description]);


                $stmt=$pdo->prepare("INSERT INTO transactions_users(user_send,user_dest,carte_dest,carte_send,montant,description) VALUES(?,?,?,?,?,?)");
                $stmt->execute([$user_send,$user_dest,$carte_dest,$carte_send,$montant,$description]);

                $pdo->commit();
                header("Location: tableborde.php");

            } catch (Exception $e) {
                $pdo->rollBack();
                echo"Erreur : ".$e->getMessage();
            }
         }

?>