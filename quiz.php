<?php require "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <?php require_once "partials/styles.php" ?>
</head>

<body>
    <?php
    require_once "partials/header.php";
    require_once "config/db.php";

    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $isValid = false;

        $result = mysqli_query($connection, "SELECT * FROM Words WHERE userId=" . $_SESSION['userId']);

        if (!$result) {
            require_once "partials/500.php";
            exit();
        }

        $max = mysqli_num_rows($result);
        mysqli_free_result($result);

        if (isset($_GET['order']) && isset($_GET['count']) && (int) $_GET['count'] <= $max && (int) $_GET['count'] >= 1) {
            $order = htmlspecialchars($_GET['order']);
            $count = (int) htmlspecialchars($_GET['count']);
            if ($order == "randomly" || $order == "latest" || $order == "oldest") {
                $isValid = true;
                generateQuiz(fetchQuestions($order, $count, $connection));
                mysqli_close($connection);
            }
        }
        if (!$isValid) { ?>
            <h1 class="text-center mt-5">Not Found!</h1>
    <?php }
    } ?>
</body>

</html>


<?php
function fetchQuestions($order, $count, $connection)
{
    $fetchWords = "SELECT Id,word FROM Words WHERE userId=" . $_SESSION['userId'] . " ";
    if ($order == "randomly") {
        $fetchWords .= "ORDER BY RAND()";
    } else {
        $fetchWords .= "ORDER BY createdAt ";
        $fetchWords .= $order == "latest" ? "DESC" : "ASC";
    }
    $fetchWords .= " LIMIT $count";

    $result = mysqli_query($connection, $fetchWords);

    if (!$result) {
        require_once "partials/500.php";
        exit();
    }

    $words =  mysqli_fetch_all($result);
    mysqli_free_result($result);

    $data = [];
    foreach ($words as  $word) {
        $allTranslations = "SELECT Word FROM Translations WHERE wordId = $word[0]";
        $result = mysqli_query($connection, $allTranslations);
        if (!$result) {
            require_once "partials/500.php";
            exit();
        }

        $trns = mysqli_fetch_all($result);
        $implodedTrns = "";
        foreach ($trns as  $trn) $implodedTrns = $implodedTrns  . $trn[0] . ", ";
        array_push($data, ['id' => $word[0], 'word' => $word[1], 'trns' => substr($implodedTrns, 0, -2)]);
    }
    return $data;
}


function generateQuiz($data)
{ ?>
    <main class="my-5">
        <div class="container">
            <div class="row align-items-center flex-column">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="position-relative">
                        <h1 class="text-center">Quiz</h1>
                        <span id="timer"></span>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
                            <form id="quiz" class="p-3" action="calculate.php" method="POST">
                                <div class="form-groups">
                                    <?php
                                    foreach ($data as $index => $question) { ?>
                                        <div style="display: none;" class="tab">
                                            <div class="question my-3">
                                                <h2 class="text-center"><?php echo $question['word'] ?></h2>
                                                <div class="form-group d-flex align-items-center justify-content-center">
                                                    <input class="form-control w-50" autocomplete="off" type="text" name="answer-<?php echo $index + 1 ?>">
                                                    <input type="hidden" name="word-<?php echo $index + 1 ?>" value="<?php echo $question['id'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="pagination-container d-flex justify-content-between">
                                    <button class="btn btn-primary mx-3 navigation-btn prev">Previous</button>
                                    <button class="btn btn-primary mx-3 navigation-btn next">Next</button>
                                </div>
                                <input type="hidden" name="count" value="<?php echo count($data) ?>">
                                <input type="hidden" name="startedAt" value="<?php echo date("Y-m-d H:i:s") ?>">
                            </form>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                            <aside class="p-3 h-100">
                                <div class="flags-container d-flex flex-wrap">
                                    <?php
                                    for ($i = 0; $i < count($data); $i++) { ?>
                                        <span class="flag m-1 p-1 <?php echo $i == 0 ? "current" : "unvisited" ?>"><?php echo $i + 1 ?></span>
                                    <?php } ?>
                                </div>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php require_once "partials/scripts.php" ?>
    <script src="scripts/multiplePageForm.js"></script>
    <script src="scripts/timer.js"></script>
<?php } ?>