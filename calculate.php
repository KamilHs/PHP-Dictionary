<?php require "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results</title>
    <?php require_once "partials/styles.php" ?>
</head>

<body>
    <?php
    require_once "partials/header.php";
    require_once "config/db.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $result = mysqli_query($connection, "SELECT * FROM Words WHERE userId=" . $_SESSION['userId']);
        if (!$result) {
            require_once "partials/500.php";
            exit();
        }

        $max = mysqli_num_rows($result);

        if (isset($_POST['count']) && (int) $_POST['count'] <= $max && (int) $_POST['count'] >= 1) {
            $_SESSION['data'] = checkData($connection);
            mysqli_close($connection);
            header('HTTp/1.1 301 Moved Permanently');
            header("Location: quiz-result.php");
        }
    } else { ?>
        <h1 class="text-center my-5">Not Found!</h1>
    <?php } ?>

    <?php require_once "partials/scripts.php" ?>
</body>

</html>


<?php
function checkData($connection)
{
    $data = [];
    $count = $_POST['count'];
    $startedAt = htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, $_POST['startedAt'])));

    mysqli_query($connection, "INSERT INTO Quizzes (userId,startedAt,endedAt,questionCount) VALUES(" . $_SESSION['userId'] . ",'$startedAt','" . date("Y-m-d H:i:s") . "',$count)");

    $quizId = mysqli_insert_id($connection);
    $correct = 0;

    for ($i = 0; $i < $count; $i++) {
        $id = htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, $_POST['word-' . ($i + 1)])));

        $result = mysqli_query($connection, "SELECT word FROM Words WHERE userId=" . $_SESSION['userId'] . " AND Id=$id");

        if (!$result) {
            require_once "partials/500.php";
            exit();
        }

        $word = mysqli_fetch_row($result)[0];
        mysqli_free_result($result);

        $answer = htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, $_POST["answer-" . ($i + 1)])));
        $answer = trim($answer);
        $checkExistance = "SELECT word FROM Translations WHERE wordId=$id AND word='$answer'";

        mysqli_query($connection, "UPDATE Words SET wasInQuiz = wasInQuiz + 1 WHERE userId=" . $_SESSION['userId'] . " AND Id=$id");

        $result = mysqli_query($connection, $checkExistance);
        if (mysqli_num_rows($result)) {
            $correct++;
            mysqli_query($connection, "UPDATE Words SET guessedCorrectly = guessedCorrectly + 1 WHERE userId=" . $_SESSION['userId'] . " AND Id=$id");
            array_push($data, ['isCorrect' => true, 'id' => $id, 'word' => $word, 'answer' => $answer]);
            mysqli_query($connection, "INSERT INTO Quiz_Items (quizId,word,isCorrect,userAnswer) VALUES($quizId,'$word',1,'$answer')");
        } else {
            $allTranslations = "SELECT word FROM Translations WHERE wordId = $id";

            $result = mysqli_query($connection, $allTranslations);

            if (!$result) {
                require_once "partials/500.php";
                exit();
            }

            $trns = mysqli_fetch_all($result);
            $implodedTrns = "";
            foreach ($trns as  $trn)
                $implodedTrns = $implodedTrns  . $trn[0] . ", ";
            array_push($data, ['isCorrect' => false, 'id' => $id, 'word' => $word, 'answer' => $answer, 'correct' => substr($implodedTrns, 0, -2)]);
            mysqli_query($connection, "INSERT INTO Quiz_Items (quizId,word,isCorrect,correctAnswer,userAnswer) VALUES($quizId,'$word',0,'" . substr($implodedTrns, 0, -2) . "','$answer')");
        }
    }
    mysqli_query($connection, "UPDATE Quizzes SET Score=$correct WHERE Id=$quizId");
    array_push($data, ['score' => $correct, 'count' => $count]);
    return $data;
}
