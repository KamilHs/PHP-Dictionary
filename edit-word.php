<?php require "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <?php require_once "partials/styles.php" ?>
</head>

<body>
    <?php
    require_once "partials/header.php";
    require_once "config/db.php";
    if (isset($_GET['wordId'])) $wordId = htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, $_GET['wordId'])));
    if (isset($_GET['translationId'])) $translationId = htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, $_GET['translationId'])));
    if (isset($wordId) && is_numeric($wordId)) { ?>
        <main>
            <div class="container center">
                <div class="row align-items-center flex-column">
                    <?php
                    $findWord = "SELECT word FROM Words WHERE Id=$wordId";
                    $result = mysqli_query($connection, $findWord);
                    if (!$result) {
                        require_once "partials/500.php";
                        exit();
                    }

                    $word = mysqli_fetch_row($result);
                    mysqli_free_result($result);

                    if (!isset($word)) {
                        echo "<h1 class='mt-5 text-center'>Word wasn't Found!</h1>";
                        exit();
                    } ?>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <form class="p-5" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                            <?php
                            $valueErr = "";
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                                $value = "";
                                if (empty(trim($_POST['newValue']))) $valueErr = "Required";
                                else {
                                    $valueErr = "";
                                    $value = htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, trim($_POST["newValue"]))));
                                    $value =  trim(implode("''", explode("'", $value)));

                                    if (isset($_SESSION['translationId']))
                                        $updateQry = "UPDATE Translations SET word='$value' WHERE Id=$_SESSION[translationId]";
                                    else  $updateQry = "UPDATE Words SET word='$value' WHERE Id=$_SESSION[wordId]";
                                    mysqli_query($connection, $updateQry);
                                    mysqli_close($connection);
                                    header('HTTp/1.1 301 Moved Permanently');
                                    header("Location: translation.php?wordId=$_SESSION[wordId]");
                                    $_SESSION['wordId'] = $wordId;
                                    $_SESSION['translationId'] = $translationId;
                                    exit();
                                }
                            }

                            $word = $word[0];

                            $_SESSION['wordId'] = $wordId;
                            if (isset($translationId) && is_numeric($translationId)) {
                                $findTranslation = "SELECT word FROM Translations WHERE Id=$translationId AND wordId=$wordId";

                                $result = mysqli_query($connection, $findTranslation);
                                if (!$result) {
                                    require_once "partials/500.php";
                                    exit();
                                }

                                $translation = mysqli_fetch_row($result);
                                mysqli_free_result($result);

                                if (!isset($translation)) {
                                    echo "<h1 class='mt-5 text-center'>Translation of $word wasn't found!</h1>";
                                    exit();
                                }
                                $translation = $translation[0];
                                $_SESSION['translationId'] = $translationId; ?>
                            <?php } ?>
                            <div class="form-group">
                                <div class="d-flex justify-content-between aling-items-center">
                                    <label for="newValue">Enter new value for the word "<?php echo isset($translation) ? $translation : $word ?>"</label>
                                    <span class="error"><?php echo $valueErr ?></span>
                                </div>
                                <input class="form-control" autocomplete="off" id="newValue" name="newValue" type="text" value="<?php echo isset($translation) ? $translation : $word ?>" autofocus>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary" type="submit">Apply Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    <?php } else { ?>
        <h1 class="my-5 text-center">Not Found</h1>
    <?php } ?>
    <?php require_once "partials/scripts.php" ?>
</body>


</html>