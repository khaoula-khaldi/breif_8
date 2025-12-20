<?php 
include 'connexion.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_incomes'])) {
        $idIn=$_POST['idIn'];
        $stmt =$pdo->prepare("DELETE FROM incomes WHERE idIn=?");
        $stmt -> execute([$idIn]);

    }
    if (isset($_POST['delete_Expenses'])) {
        $idEx=$_POST['idEx'];
        $stmt =$pdo->prepare("DELETE FROM expenses WHERE idEx=?");
        $stmt -> execute([$idEx]);

    }
    header("Location: tableborde.php");
    exit;
}