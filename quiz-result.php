<?php require "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <?php require_once "partials/styles.php" ?>
</head>

<body>
    <?php
    require_once "partials/header.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        header('HTTp/1.1 301 Moved Permanently');
        header("Location: quizzes.php");
        exit();
    }

    if (isset($_SESSION['data'])) {
        generateResults($_SESSION['data']);
        unset($_SESSION['data']);
    } else { ?>
        <h1 class="text-center my-5">Not Found!</h1>
    <?php } ?>

    <?php require_once "partials/scripts.php" ?>
</body>

</html>

<?php
function generateResults($data)
{ ?>
    <main class="my-5">
        <div class="container">
            <div class="row">
                <?php
                $params =  array_pop($data);
                ?>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="score-container">
                        <h1 class="text-center"><?php echo htmlspecialchars($params['score']), " out of ", htmlspecialchars($params['count']) ?></h1>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
                            <div class="results-container">
                                <form class="p-3" action="#">
                                    <div class="results">
                                        <?php
                                        foreach ($data as $index => $row) { ?>
                                            <div class="tab" style="display: none;">
                                                <div class="<?php echo $row['isCorrect'] ? "correct" : "wrong" ?>">
                                                    <h3 class="text-center mb-2"><?php echo $row['word'] ?></h3>
                                                    <div class="d-flex flex-column justify-content-center align-items-center w-100 mb-3">
                                                        <div class="form-group w-50 d-flex align-items-center m-0">
                                                            <?php if (!$row['isCorrect']) { ?>
                                                                <label class="text-nowrap m-0 mr-2" for="userAnswer-<?php echo $index + 1 ?>">Your answer:</label>
                                                            <?php } ?>
                                                            <input class="form-control" type="text" value="<?php echo $row['answer'] ?>" id="userAnswer-<?php echo $index + 1 ?>" readonly>
                                                        </div>
                                                        <?php
                                                        if (!$row['isCorrect']) { ?>
                                                            <span class="correct-answer">Correct Answer: <?php echo $row['correct'] ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="pagination-container d-flex justify-content-between">
                                        <button class="btn btn-primary mx-3 navigation-btn prev">Previous</button>
                                        <button class="btn btn-primary mx-3 navigation-btn next">Next</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <aside class="p-3 h-100">
                                <div class="flags-container d-flex flex-wrap">
                                    <?php
                                    foreach ($data as $index => $row) { ?>
                                        <span class="flag m-1 p-1 <?php echo $row['isCorrect'] ? "success" : "danger" ?>"><?php echo $index + 1 ?></span>
                                    <?php } ?>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 my-3">
                    <form class="border-0" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Finish Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php
    require_once "partials/scripts.php"; ?>
    <script src="scripts/review.js"></script>
<?php } ?>