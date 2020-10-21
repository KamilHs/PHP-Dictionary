<?php require "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Word</title>
    <?php require_once "partials/styles.php" ?>
</head>

<body>
    <?php require_once "partials/header.php" ?>
    <main>
        <div class="container center">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <form class="p-5" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                        <?php
                        require_once "config/db.php";
                        $wordErr = $translationErr = "";
                        $word = $translation = "";

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (!isset($_POST["word"]) || empty(trim($_POST["word"])))
                                $wordErr = "Required";
                            else {
                                $wordErr = "";
                                $word =  htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, trim($_POST["word"]))));
                                $word =  trim(implode("''", explode("'", $word)));
                            }
                            if (!isset($_POST["translation"]) || empty(trim($_POST["translation"])))
                                $translationErr = "Required";
                            else {
                                $translationErr = "";
                                $translation = htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, trim($_POST["translation"]))));
                            }
                            if (!empty($translation) and !empty($word)) {
                                handleSubmit($word, $translation, $connection);
                            }
                        }
                        ?>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="word">Enter Word</label>
                                <span class="error"><?php echo $wordErr ?></span>
                            </div>
                            <input class="form-control" autocomplete="off" value="<?php echo $word ?>" type="text" name="word" id="word">
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="translation">Enter Translation</label>
                                <span class="error"><?php echo $translationErr ?></span>
                            </div>
                            <input class="form-control" autocomplete="off" value="<?php echo $translation ?>" type="text" name="translation" id="translation">
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-primary">Add Word</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php require_once "partials/scripts.php" ?>
</body>


</html>


<?php
function handleSubmit($word, $translation, $connection)
{
    $searchExistingWord = "SELECT Id FROM Words WHERE userId=" . $_SESSION['userId'] . " AND word='$word'";
    $result = mysqli_query($connection, $searchExistingWord);
    if (!$result) {
        require_once "partials/500.php";
        exit();
    }

    $fetchedWord = mysqli_fetch_assoc($result);
    $wordId = $fetchedWord ? $fetchedWord["Id"] : null;
    mysqli_free_result($result);

    $translation = explode(",", $translation);
    foreach ($translation as $trns) {
        $trns = trim($trns);

        if (isset($wordId)) {
            $searchExistingTranslation = "SELECT Id FROM Translations WHERE word = '$trns' AND wordId = $wordId";
            $result = mysqli_query($connection, $searchExistingTranslation);

            if (!$result) {
                require_once "partials/500.php";
                exit();
            }

            $translateRes =  mysqli_fetch_assoc($result);
            mysqli_free_result($result);

            $translationId = $translateRes ? $translateRes["Id"] : null;

            if (isset($translationId)) {
                echo "<span class='error'>Already Exists</span>";
                return;
            }
        } else {
            if (!isset($wordId)) {
                mysqli_query($connection, "INSERT INTO Words (userId,createdAt,word) VALUES(" . $_SESSION['userId'] . ",'" . date("Y-m-d H:i:s") . "','$word')");
                $wordId = mysqli_insert_id($connection);
            }
        }
        mysqli_query($connection, "INSERT INTO Translations (word,wordId) VALUES('$trns', $wordId)");
    }

    header('HTTp/1.1 301 Moved Permanently');
    header("Location: index.php");
}

?>