<?php require "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "partials/styles.php" ?>
    <title>Online Dictionary</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <?php require_once "partials/header.php" ?>
    <main class="my-5">
        <div class="container">
            <div class="row justify-content-center">
                <?php
                require_once "config/db.php";
                $query = "SELECT Id,word FROM Words WHERE userId=" . $_SESSION['userId'] . " LIMIT 1";
                $result = mysqli_query($connection, $query);

                if (!$result) {
                    require_once "partials/500.php";
                    exit();
                }

                $words = mysqli_fetch_all($result);
                mysqli_free_result($result);

                if (isset($words) && count($words) > 0) { ?>
                    <div class="content-container col-lg-12 col-md-12 col-sm-12">
                        <div class=" position-relative w-100 ">
                            <h1 class="pb-3 m-0 w-100 text-center">Words</h1>
                            <span class="count">Total: </span>
                        </div>
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 10%" scope="col">#</th>
                                    <th style="width: 25%" scope="col">Words</th>
                                    <th style="width: 60%" scope="col">Translations</th>
                                    <th class="text-center" style="width: 5%;" scope="col">View</th>
                                    <th class="text-center" style="width: 5%;" scope="col">Edit</th>
                                    <th class="text-center" style="width: 5%;" scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end align-items-center">
                            <button id="load-more" class="btn btn-primary" type="button">
                                <span class="d-none spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="value">Load More</span>
                            </button>
                        </div>
                    </div>
                <?php } else { ?>
                    <h1>No Records yet</h1>
                <?php } ?>
            </div>
        </div>
    </main>
    <?php require_once "partials/scripts.php" ?>
    <script src="scripts/fetchData.js"></script>
</body>

</html>