<?php 
$url='localhost';
$username='root';
$password='';
$conn=mysqli_connect($url,$username,$password,"med");
if(!$conn){
    die('Could not Connect My Sql:');
}

$docId=$_REQUEST["docId"];
$name=$_REQUEST["name"];
$dept=$_REQUEST["dept"];
$email=$_REQUEST["email"];
$phn=$_REQUEST["phn"];
$uname=$_REQUEST["uname"];
$pass=md5($_REQUEST["pass"]);

$sql1="INSERT INTO doctor VALUES ('$docId','$name','$dept','$phn'); ";
$sql2= "INSERT INTO login_details VALUES ('$uname','$pass','$email','$docId'); ";

if(mysqli_query($conn,$sql1)){
    if(mysqli_query($conn,$sql2)){
        header("Location: login.html");
    }
}


?>