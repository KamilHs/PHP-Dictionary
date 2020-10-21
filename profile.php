<?php require_once "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['username'] ?></title>
    <?php require_once "partials/styles.php" ?>
</head>

<body>
    <?php require_once "partials/header.php" ?>
    <main class="my-5">
        <div class="container">
            <div class="row flex-column align-items-center">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h1 class="text-center mb-4"><?php echo $_SESSION['username'] ?></h1>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="stats-container p-3">
                        <h2 class="text-center">Stats</h2>
                        <?php
                        require_once "config/db.php";
                        $quizzesQry = "SELECT COUNT(Id), SUM(score), SUM(questionCount) FROM Quizzes  WHERE userId=" . $_SESSION['userId'];
                        $result = mysqli_query($connection, $quizzesQry);

                        if (!$result) {
                            require_once "partials/500.php";
                            exit();
                        }
                        $quizzesData = mysqli_fetch_row($result);

                        $result = mysqli_query($connection, "SELECT * FROM Words WHERE userId=" . $_SESSION['userId']);

                        if (!$result) {
                            require_once "partials/500.php";
                            exit();
                        }
                        $wordsCount = mysqli_num_rows($result);
                        mysqli_free_result($result);

                        $result = mysqli_query($connection, "SELECT Translations.word FROM Translations LEFT JOIN Words ON wordId = Words.Id WHERE userId=" . $_SESSION['userId']);

                        if (!$result) {
                            require_once "partials/500.php";
                            exit();
                        }
                        $trnsCount = mysqli_num_rows($result);
                        mysqli_free_result($result);

                        ?>
                        <ul class="stats d-flex flex-wrap justify-content-center">
                            <li class="m-3 text-center">Words: <?php echo $wordsCount; ?> </li>
                            <li class="m-3 text-center">Translations: <?php echo $trnsCount; ?></li>
                            <li class="m-3 text-center">Quizzes Passed: <?php echo $quizzesData[0] ?></li>
                            <li class="m-3 text-center">Questions: <?php echo $quizzesData[2] ?></li>
                            <li class="m-3 text-center">Correct Answers: <?php echo $quizzesData[1] ?></li>
                            <li class="m-3 text-center">Wrong Answers: <?php echo $quizzesData[2] - $quizzesData[1] ?></li>
                            <li class="m-3 text-center">Accuracy: <?php echo $quizzesData[2] ? number_format($quizzesData[1] / $quizzesData[2] * 100, 2) : 0 ?>%</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>