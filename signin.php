<?php
//connecting database
include "partials/_config.php";

//for login success alert
$login = false;
$showErr = false;

//sign in
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    //Check wheater email exist
    $sql = "SELECT * FROM `users` WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                $login = true;
                session_start();
                $_SESSION['signedin'] = true;
                $_SESSION['user_id'] = $row['id']; 
                $_SESSION['email'] = $email;
                header("location: dashboard.php");
            } else {
                $showErr = "Password is incorrect";
            }
        }
    } else {
        $showErr = "Invalid Credential";
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

    <title>To-Do | Sign In</title>
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
      <strong>Login successful!</strong> You have loggedin.
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
        <h2 class="text-center mt-4">Welcome Back!</h2>
        <p class="text-center text-muted">Log in to access your personal to-do list and stay on track.</p>
        <!-- heading end -->
        <!-- form start -->
        <form action="/todo_app/signin.php" method="post">
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
            <button type="submit" class="btn btn-primary col-md-6">Sign In</button>
            <a href="signup.php" class="mt-3" style="text-decoration: none;">Don't have an account? Sign Up here</a>
        </form>
        <!-- form ends here -->
    </div>

    <!--  Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>