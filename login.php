<?php
session_start();
$sName = "localhost";
$userName = "root";
$pass = "";
$db_name = "med";

try {
    $conn = new PDO(
        "mysql:host=$sName;dbname=$db_name",
        $userName,
        $pass
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed : " . $e->getMessage();
}



if (isset($_POST['uname']) && isset($_POST['pass'])) {

    $uname = $_POST['uname'];
    $password =md5($_POST['pass']) ;

    

    if (empty($uname)) {
        echo "Input username";
    } else if (empty($password)) {
        echo "Password requiered";
    } else {
        $stmt = $conn->prepare("SELECT * FROM login_details WHERE username=?");
        $stmt->execute([$uname]);

        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch();
            $user_id = $user['docID'];
            $user_uname = $user['username'];
            $user_password = $user['pass'];
            

            if ($uname === $user_uname) {
                if ($password === $user_password) {
                    $_SESSION['is_login'] = true;
                    $_SESSION['docId'] = $user_id;
                    $_SESSION['user_uname'] = $user_uname;

                    //echo "Wellcome ".$_SESSION['docId'];
                    header("Location: dashboard.php");
                } else {
                    echo "1.invalid username password";
                }
            } else {
                echo "2.invalid username password";
            }
        } else {
            echo "3.invalid username password";
        }
    }
}
