<?php
session_start();

//if user not loggedin it will redirect him to signin.php
if (!isset($_SESSION['signedin']) || $_SESSION['signedin'] !== true) {
    header("location: signin.php");
    exit;
}

//connecting databse
include "partials/_config.php";

//alerts variables
$success = false;
$error = false;

//Storing user id
$user_id = $_SESSION['user_id'];

//getting name of user from user_id
$usernameSql = "SELECT username FROM users WHERE id = '$user_id'";
$usernameResult = mysqli_query($conn, $usernameSql);
if ($row = mysqli_fetch_assoc($usernameResult)) {
    $username = $row['username'];
}

//adding task 
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $insertTask = $_POST['task'];
    //insert query
    $insertSql = "INSERT INTO tasks (user_id, task) VALUES ('$user_id', '$insertTask')";
    $insertResult = mysqli_query($conn, $insertSql);
    if ($insertResult) {
        $success = " Task added successfully.";
    } else {
        $error = " Fail to add task.";
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - <?php echo $username; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- datatable css link -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .task-completed {
            text-decoration: line-through;
            color: gray;
        }

        .btn-action {
            border: none;
            background: transparent;
            color: #6c757d;
        }

        .btn-action:hover {
            color: #000;
        }
    </style>
</head>

<body>
    <?php
    //connecting navbar
    require "partials/_nav.php";
    //update msg
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">'
            . $_SESSION['message'] . '
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
        unset($_SESSION['message']);
    }
    //delete success msg
    if (isset($_SESSION['success_msg'])) {
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">'
            . $_SESSION['success_msg'] . '
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
        unset($_SESSION['success_msg']);
    }
    //delete error msg
    if (isset($_SESSION['error_msg'])) {
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">'
            . $_SESSION['error_msg'] . '
         <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>';
        unset($_SESSION['error_msg']);
    }


    //success alert
    if ($success) {
        echo '
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
           <i class="fas fa-check-circle me-2"></i>
           <div>
             <b>Successful!</b>' . $success . '
           </div>
           <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
         </div>
        ';
    }

    //error alert
    if ($error) {
        echo '
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
          <i class="fas fa-exclamation-circle me-2"></i>
          <div>
            <b>Error!</b> ' . $error . '
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        ';
    }

    ?>
    </head>

    <body>

        <div class="container py-5">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">👋 Hello, <span class="text-primary"><?php echo $username; ?>
                    </span></h3>
            </div>

            <!-- Add Task Form -->
            <form method="POST" action="/todo_app/dashboard.php" class="input-group mb-4 shadow-sm">
                <input type="text" name="task" class="form-control" placeholder="Enter a new task..." required>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-plus"></i> Add
                </button>
            </form>

            <!-- table start -->

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">📋 Your Tasks</h5>
                </div>
            </div>
            <table id="myTable" class="table">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Task</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // fetching data from table
                    $taskSql = "SELECT * FROM tasks WHERE user_id = '$user_id'";
                    $taskResult = mysqli_query($conn, $taskSql);
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($taskResult)) {
                        echo '
                    <tr>
                        <td>' . $sno++ . '</td>
                        <td>' . $row['task'] . '</td>
                        <td>' . $row['status'] . '</td>
                        <td>' . $row['created_at'] . '</td>

                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn-action btn btn-warning btn-sm editBtn"
                                    data-id="' . $row['id'] . '"
                                    data-name="' . htmlspecialchars($row['task']) . '"
                                    data-status="' . $row['status'] . '"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal" 
                                    title="Edit Task">
                                    <i class="fas fa-pencil text-success"></i>
                                </button>

                                <a href="delete_task.php?id=' . $row['id'] . '&user_id=' . $user_id . '" 
                                   class="btn-action btn btn-danger btn-sm"
                                   onclick="return confirm(\'Are you sure you want to delete this task?\');"
                                   title="Delete Task">
                                   <i class="fas fa-trash text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>


        <!-- Edit Task Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="editTaskForm" method="POST" action="update_task.php">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">

                            <div class="mb-3">
                                <label for="edit-task" class="form-label">Task</label>
                                <input type="text" class="form-control" id="edit-task" name="task" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-status" class="form-label">Status</label>
                                <select class="form-select" id="edit-status" name="status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- edit modal tas end -->

        <!-- jQuery (required for DataTables) -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

        <!-- for datatable -->
        <script>
            $(document).ready(function () {
                $('#myTable').DataTable();
            });

            //for getting data in update form
            document.addEventListener("DOMContentLoaded", function () {
                const editButtons = document.querySelectorAll(".editBtn");

                editButtons.forEach(button => {
                    button.addEventListener("click", function () {
                        // Get data attributes from clicked button
                        let id = this.getAttribute("data-id");
                        let taskName = this.getAttribute("data-name");
                        let taskStatus = this.getAttribute("data-status");

                        // Set values into modal form inputs
                        document.getElementById("edit-id").value = id;
                        document.getElementById("edit-task").value = taskName;
                        document.getElementById("edit-status").value = taskStatus;
                    });
                });
            });


        </script>

        <!-- bs5 js link -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
            integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK"
            crossorigin="anonymous"></script>
    </body>

</html>