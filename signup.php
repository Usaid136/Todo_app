<?php
//connecting database
include "partials/_config.php";

//for login success alert
$login = false;
$showErr = false;

//form php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    //Check whether username exists
    $existSql = "SELECT * FROM `users` WHERE username = '$username' OR email = '$email'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        $showErr = "Username: $username or Email: $email already exist.";
    } else {
        //checking password match to each other
        if ($password == $cpassword) {
            //storing password in hash form
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (`username`, `email`, `password`) VALUES ('$username', '$email', '$hash')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $login = true;
            }
        } else {
            $showErr = "Passwords not match";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>To-Do | Sign Up</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>

<body>
    <?php
    //including navbar file
    require "partials/_nav.php";

    //login Success alert
    if ($login) {
        echo '
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Sign up successful!</strong> Your account has been created & now you can <a href="signin.php" class="alert-link">Sign in</a>.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }

    //login Success alert
    if ($showErr) {
        echo '
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>Error! </strong>' . $showErr .
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    ?>
    <div class="container my-4">
        <!-- heading start -->
        <h2 class="text-center mt-4">Welcome! Let's Get Started</h2>
        <p class="text-center text-muted">Create your to-do account to stay organized</p>
        <!-- heading end -->
        <!-- form start -->
        <form action="/todo_app/signup.php" method="post">
            <!-- username input -->
            <div class="mb-3 col-md-6">
                <label for="exampleInputEmail1" class="form-label">Username</label>
                <input type="text" placeholder="Enter your username" name="username" class="form-control"
                    id="exampleInputEmail1" aria-describedby="emailHelp">
            </div>
            <!-- email input -->
            <div class="mb-3 col-md-6">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" placeholder="Enter your email" name="email" class="form-control"
                    id="exampleInputEmail1" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <!-- password input -->
            <div class="mb-3 col-md-6">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" placeholder="Enter your password" name="password" class="form-control"
                    id="exampleInputPassword1">
            </div>
            <!-- confirm password input -->
            <div class="mb-3 col-md-6">
                <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
                <input type="password" placeholder="Confirm your password" name="cpassword" class="form-control"
                    id="exampleInputPassword1">
                <div id="emailHelp" class="form-text">Make sure to type same password</div>
            </div>
            <button type="submit" class="btn btn-primary col-md-6">Sign Up</button>
            <a href="signin.php" class="mt-3" style="text-decoration: none;">Already have an account? Sign In here</a>
        </form>
        <!-- form ends here -->
    </div>

    <!--  Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>