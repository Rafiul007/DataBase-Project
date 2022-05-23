<?php
$url = 'localhost';
$username = 'root';
$password = '';
$conn = mysqli_connect($url, $username, $password, "med");
if (!$conn) {
    die('Could not Connect My Sql:');
}
$insert = false;


if(isset($_GET['delid'])){
    $id=$_GET['delid'];
    
    mysqli_begin_transaction($conn);
    try {
         $sql1= " DELETE FROM patient WHERE pId='$id' ";
         $sql2= " DELETE FROM prescription WHERE presId = (SELECT presId FROM func WHERE pId='$id' ); ";
         $sql3= " DELETE FROM func WHERE pId='$id' ";

         $q3 = mysqli_query($conn, $sql3);
         $q1 = mysqli_query($conn, $sql1);
         $q2 = mysqli_query($conn, $sql2);
         mysqli_commit($conn);
         header("Location: dashboard.php");
    } catch (mysqli_sql_exception $exception) {
        mysqli_rollback($conn);
        throw $exception;
    }



}

?>
