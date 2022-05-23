<?php
session_start();
$url = 'localhost';
$username = 'root';
$password = '';
$conn = mysqli_connect($url, $username, $password, "med");
if (!$conn) {
    die('Could not Connect My Sql:');
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_GET['upid'])) {
        $id = $_GET['upid'];

        mysqli_begin_transaction($conn);
        try {
            $med = $_POST['med'];
            $test = $_POST['test'];

            echo "$med";

            $sql1 = " UPDATE prescription SET test = '$test', med = '$med'
                      WHERE presId = (SELECT presId FROM func WHERE pId='$id') ";

            $q = mysqli_query($conn, $sql1);

            mysqli_commit($conn);
            echo "updated";
            header("Location: dashboard.php");
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($conn);
            throw $exception;
        }
    }
}


?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Display</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Prescribe</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">Home</a>
                    </li>

                </ul>

                <?php
                // hello user
                $nam = $_SESSION['user_uname'];
                echo "Hello, " . "$nam";

                ?>

            </div>
        </div>
    </nav>

    <?php


    if (isset($_GET['upid'])) {
        $id = $_GET['upid'];

        $sql1 = "SELECT * FROM patient WHERE pId='$id' ";
        $sql2 = " SELECT * FROM prescription WHERE presId= (SELECT presId FROM func WHERE pId='$id' ); ";
        $q1 = mysqli_query($conn, $sql1);
        $q2 = mysqli_query($conn, $sql2);


        $row1 = mysqli_fetch_assoc($q1);
        $row2 = mysqli_fetch_assoc($q2);

        $name = $row1['pName'];
        $age = $row1['age'];
        $gen = $row1['gender'];
        $bg = $row1['bg'];
        $phone = $row1['phone'];
        $case = $row1['disease'];
        $test = $row2['test'];
        $med = $row2['med'];
        $date = $row2['date'];
        $presId = $row2['presId'];

        echo '
        <center>
        <p>' . $presId . '</p><br>
        <h3>Name: ' . $name . '</h3><br>
        <p>Age: ' . $age . '------ Gender: ' . $gen . ' ------BG: ' . $bg . ' ------Phone: ' . $phone . '</p><br>
        <p>Disease: ' . $case . '</p><br>
        <p>-------------------------------------------------------------------------------------</p><br>
        <p>Test: ' . $test . '</p><br>
        <p>Treatment: </p><br>
        <p>' . $med . '</p><br>
        </center>';
    }


    ?>
    <div class="container">
        <form action="up.php" method="post">
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Diagnostic</label>
                <textarea name="test" id="test" cols="20" rows="5" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Medicine</label>
                <textarea name="med" id="test" cols="20" rows="5" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button><br>

        </form><br>

    </div>







    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>