<?php 
try{
    $pdo = new pdo('mysql:host=localhost;dbname=financier','root','');

}catch(PDOException $e){
    echo 'error : '. $e->getMessage();
}
?>