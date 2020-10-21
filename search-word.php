<?php require "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Word</title>
    <?php require_once "partials/styles.php" ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <?php
    require_once "partials/header.php";
    require_once "config/db.php";
    ?>
    <main class="mt-5">
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content p-3">
                    <h3 class="text-center mb-1">Filters</h3>
                    <form id="filters" class="border-0" action="#" method="post">
                        <div class="w-100 d-flex justify-content-between mb-3">
                            <div class="order-by d-flex justify-content-between flex-column">
                                <h6 class="text-center">Order By</h6>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="order" data-clause="createdAt" data-order="DESC" value="latest" class="custom-control-input" id="latest" checked>
                                    <label class="custom-control-label" for="latest">Latest</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="order" data-clause="createdAt" data-order="ASC" value="oldest" class="custom-control-input" id="oldest">
                                    <label class="custom-control-label" for="oldest">Oldest</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="order" data-clause="word" data-order="ASC" value="alphabetical" class="custom-control-input" id="alphabetical">
                                    <label class="custom-control-label" for="alphabetical">Alphabetical</label>
                                </div>
                            </div>
                            <div class="interval">
                                <h6 class="text-center">Word was added in interval</h6>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="mr-3 mb-0" for="from">From</label>
                                    <input type="date" data-clause="createdAt" name="from" id="from">
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <label class="mr-3 mb-0" for="to">To</label>
                                    <input type="date" data-clause="createdAt" name="to" id="to">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary" type="submit">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <?php
                $query = "SELECT Id,word FROM Words WHERE userId=" . $_SESSION['userId'] . " LIMIT 1";
                $result = mysqli_query($connection, $query);

                if (!$result) {
                    require_once "partials/500.php";
                    exit();
                }

                $words = mysqli_fetch_all($result);
                mysqli_free_result($result);
                if (isset($words) && count($words) > 0) { ?>
                    <div class="search-bar-container col-lg-12 col-md-12 col-sm-12">
                        <form class="search-bar border-0" action="#" method="POST">
                            <div class="form-group">
                                <input name="query" class="form-control" placeholder="Search" type="text">
                            </div>
                        </form>
                    </div>
                    <div class="content-container col-lg-12 col-md-12 col-sm-12">
                        <div class=" position-relative w-100 ">
                            <button id="filters-button" type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Filters</button>
                            <h2 class="pb-3 m-0 w-100 text-center">Words</h2>
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
    <script src="scripts/filterModal.js"></script>
</body>

</html>