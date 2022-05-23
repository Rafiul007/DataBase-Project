<?php
session_start();
$url = 'localhost';
$username = 'root';
$password = '';
$conn = mysqli_connect($url, $username, $password, "med");
if (!$conn) {
    die('Could not Connect My Sql:');
}
$insert = false;


$n = 10;
function getName($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}
$docId = $_SESSION['docId'] ;

if($_SESSION['is_login']==true){
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $name = $_REQUEST['name'];
        $age = $_REQUEST['age'];
        $gen = $_REQUEST['gender'];
        $bg = $_REQUEST['bg'];
        $phone = $_REQUEST['phone'];
        $case = $_REQUEST['case'];
        $test = $_REQUEST['test'];
        $med = $_REQUEST['med'];
        $date = date("Y-m-d");
        $pId = getName(5);
        $presId = getName(6);
    
        mysqli_begin_transaction($conn);
        try {
            $sql1 = " INSERT INTO prescription VALUES ('$presId','$date', '$med','$test'); ";
            $sql2 = " INSERT INTO patient VALUES ('$pId','$name',$age,'$gen','$phone','$case','$bg'); ";
            $sql3 = " INSERT INTO func (docId,pId,presId) VALUES ('$docId','$pId','$presId') ; ";
    
            
            $q1 = mysqli_query($conn, $sql1);
            $q2 = mysqli_query($conn, $sql2);
            $q3 = mysqli_query($conn, $sql3);
            
            mysqli_commit($conn);
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($conn);
            throw $exception;
        }


    }
    

}else{
    header("Location: login.html");

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
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" class="css">
    <script src="//cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <title>DashBoard</title>
</head>

<body>
    <!-- navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Prescribe</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.html">Home</a>
                    </li>
                    
                </ul>

                <?php
                // hello user
                $nam=$_SESSION['user_uname'];
                echo "Hello, "."$nam";
                
                ?>

            </div>
        </div>
    </nav>

    <?php
    // warning when added
    if ($insert) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>Success!</strong> Your note has been inserted successfully
    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
      <span aria-hidden='true'>×</span>
    </button>
  </div>";
    }
    ?>


    <div class="container my-6">

        <h1>Add</h1>
        <form action="dashboard.php" method="POST">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="name">
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Age</label>
                        <input type="number" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="age">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Gender</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="gender">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">BG</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="bg">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Phone</label>
                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="phone">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Case</label>
                <input type="text" class="form-control" id="exampleInputPassword1" name="case">
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Diagnostic</label>
                <textarea name="test" id="test" cols="20" rows="5" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Medicine</label>
                <textarea name="med" id="test" cols="20" rows="5" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button><br>
        </form>
    </div><br>

    <div class="container">

        <table class="table" id="myTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Disease</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = " SELECT pId,pName,disease FROM patient WHERE pId in (SELECT pId FROM func WHERE docId='D1001' ); ";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $id=$row['pId'];
                    $n=$row['pName'];
                    $d=$row['disease'];

                    echo '
                    <tr>
                    <th scope="row">'.$id.'</th>
                    <td>'.$n.'</td>
                    <td>'.$d.'</td>
                    <td><a class="btn btn-success" href="display.php?viewid='.$id.'" role="button">Display</a>
                    <a class="btn btn-primary" href="up.php?upid='.$id.'" role="button">Update</a>
                    <a class="btn btn-danger" href="del.php?delid='.$id.'" role="button">Delete</a></td>
                  </tr>
                    ';
                }
                
               
                
                ?>

            </tbody>
        </table>
    </div><br>

    <center>
    <a class="btn btn-outline-danger m" href="logout.php" role="button">Log Out</a>
    <br><p>Made by M Rafiul Faisal<br> ID: 201-15-3239<br></p>
    </center>
    

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();

        });
    </script>
</body>

</html>