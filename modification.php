<?php 
include 'connexion.php';
if(isset($_POST['modifier_incomes'])){
    $id = $_POST['idIn']; 
    $stmt = $pdo->prepare("SELECT * FROM incomes WHERE idIn = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Dépense</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen bg-purple-100 p-4">

    <div class="w-full max-w-lg p-6 rounded-xl shadow-lg border bg-blue-100">
        <h2 class="text-2xl font-bold text-gray-700 mb-6 text-center">
            Modifier Dépense
        </h2>

        <form action="modification.php" method="POST" class="space-y-4">

            <input type="hidden" name="idIn" value="<?php echo $row['idIn']; ?>">

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Montant</label>
                <input type="text" name="MontantIn" value="<?php echo $row['MontantIn']; ?>"
                       class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Description</label>
                <input type="text" name="descreptionIn" value="<?php echo $row['descreptionIn']; ?>"
                       class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block font-semibold text-gray-700 mb-1">Date</label>
                <input type="date" name="date_enterIn" value="<?php echo $row['date_enterIn']; ?>"
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
       
           $idIn = $_POST['idIn'];    
           $MontantIn = $_POST['MontantIn']; 
           $descreptionIn = $_POST['descreptionIn']; 
           $date_enterIn = $_POST['date_enterIn'];

           $stmt = $pdo->prepare("UPDATE incomes SET MontantIn = ?, descreptionIn = ?, date_enterIn = ? WHERE idIn = ?");
           $stmt->execute([$MontantIn, $descreptionIn,$date_enterIn,$idIn]);
           $row= $stmt->fetch(PDO::FETCH_ASSOC);
        
      if ($stmt->execute()) {
        header("Location: tableborde.php");
        exit;
    } else {
        echo "Erreur : " . $stmt->error;
    }
}









        //     if(isset($_POST['modifier_expenses'])){
        //      $idIn = $_POST['idIn'];    
        //     $montant = $_POST['montant']; 
        //     $description = $_POST['description']; 
        //     $stmt = $pdo->prepare("UPDATE expenses SET montant = ?, description = ? WHERE idIn = ?");
        //     $stmt->execute([$montant, $description, $idIn]);
        // }

    
    ?>