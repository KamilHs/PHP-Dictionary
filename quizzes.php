<?php require "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzes</title>
    <?php require_once "partials/styles.php" ?>
</head>

<body>
    <?php require_once "partials/header.php" ?>
    <main class="my-5">
        <div class="container center">
            <div class="row justify-content-center">
                <?php
                require_once "config/db.php";

                $result = mysqli_query($connection, "SELECT * FROM Words WHERE userId=" . $_SESSION['userId']);
                if (!$result) {
                    require_once "partials/500.php";
                    exit();
                }
                mysqli_close($connection);

                $max = mysqli_num_rows($result);

                if ($max > 0) { ?>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form class="p-4" action="quiz.php" method="GET">
                            <h6 class="text-center">Order By</h6>
                            <div class="order-by d-flex justify-content-around">
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="order" data-clause="createdAt" data-order="DESC" value="latest" class="custom-control-input" id="latest">
                                    <label class="custom-control-label" for="latest">Latest</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="order" data-clause="createdAt" data-order="ASC" value="oldest" class="custom-control-input" id="oldest">
                                    <label class="custom-control-label" for="oldest">Oldest</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="order" data-clause="randomly" value="randomly" class="custom-control-input" id="randomly" checked>
                                    <label class="custom-control-label" for="randomly">Randomly</label>
                                </div>
                            </div>
                            <div class="mt-4 form-group d-flex justify-content between align-items-center">
                                <label class="text-nowrap mr-4" for="count">Number of questions (Max:<?php echo $max ?>):</label>
                                <input class="form-control" type="number" name="count" id="count" step="1" min="1" max="<?php echo $max ?>" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="error"></span>
                                <button type="submit" class="btn btn-primary">Start Quiz</button>
                            </div>
                        </form>
                    </div>
                <?php } else { ?>
                    <h1>No Records Yet</h1>
                <?php } ?>
            </div>
        </div>
    </main>
    <?php require_once "partials/scripts.php" ?>
</body>

</html>