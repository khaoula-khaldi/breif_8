<?php 
include 'connexion.php';
if(isset($_POST['modifier_Expenses'])){
    $id = $_POST['idEx'] ?? null; // check
    if(!$id){
        die("ID manquant !");
    }

    $stmt = $pdo->prepare("SELECT * FROM expenses WHERE idEx = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row){
        die("Aucune dépense trouvée pour cet ID !");
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Expenses</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen bg-purple-100 p-4">

    <div class="w-full max-w-lg p-6 rounded-xl shadow-lg border bg-blue-100">
        <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">
            Modifier Expenses
        </h2>

        <form action="modificationEX.php" method="POST" class="space-y-4">

            <input type="hidden" name="idEx" value="<?php echo $row['idEx']; ?>">

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Montant</label>
                <input type="text" name="MontantEx" value="<?php echo $row['MontantEx']; ?>"
                       class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Description</label>
                <input type="text" name="descreptionEx" value="<?php echo $row['descreptionEx']; ?>"
                       class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Date</label>
                <input type="date" name="date_enterEx" value="<?php echo $row['date_enterEx']; ?>"
                       class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit" name="update_Expenses"
                    class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
                Enregistrer
            </button>

        </form>

    </div>

</body>
</html>
<?php 

   if($_SERVER['REQUEST_METHOD']=="POST" &&isset($_POST['update_Expenses']) ){
       
           $idEx = $_POST['idEx'];    
           $MontantEx = $_POST['MontantEx']; 
           $descreptionEx = $_POST['descreptionEx']; 
           $date_enterEx = $_POST['date_enterEx'];

           $stmt = $pdo->prepare("UPDATE expenses SET MontantEx = ?, descreptionEx = ?, date_enterEx = ? WHERE idEx = ?");
           $envoyer=$stmt->execute([$MontantEx, $descreptionEx,$date_enterEx,$idEx]);
           

      if ($envoyer) {
        header("Location: tableborde.php");
        exit;
    }
}

?>