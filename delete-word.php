<?php require "partials/checkLogin.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "partials/styles.php" ?>
    <title>Delete</title>
</head>

<body>
    <?php
    require_once "config/db.php";
    require_once "partials/header.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if ($_POST["submit"] == "yes") {
            if (isset($_SESSION['wordId'])) {
                $deleteWordQuery = "DELETE FROM Words WHERE Id=$_SESSION[wordId]";

                if (isset($_SESSION['translationId'])) {
                    $deleteTrnQry = "DELETE FROM Translations WHERE Id=$_SESSION[translationId]";
                    mysqli_query($connection, $deleteTrnQry);

                    if ($_SESSION['total'] == 1)
                        mysqli_query($connection, $deleteWordQuery);
                    else {
                        header('HTTp/1.1 301 Moved Permanently');
                        header("Location: translation.php?wordId=$_SESSION[wordId]");
                        unset($_SESSION['wordId']);
                        unset($_SESSION['translationId']);
                        unset($_SESSION['total']);
                        exit();
                    }
                } else
                    mysqli_query($connection, $deleteWordQuery);
            }
        }
        unset($_SESSION['wordId']);
        unset($_SESSION['translationId']);
        unset($_SESSION['total']);
        header('HTTp/1.1 301 Moved Permanently');
        header("Location: index.php");
    } else {
        if (isset($_GET['wordId']))
            $wordId = htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, $_GET['wordId'])));
        if (isset($_GET['translationId']))
            $translationId = htmlspecialchars(stripcslashes(mysqli_real_escape_string($connection, $_GET['translationId'])));
        if (isset($wordId) && is_numeric($wordId)) { ?>
            <main>
                <div class="container">
                    <div class="row align-items-center flex-column">
                        <?php
                        $findWord = "SELECT word FROM Words WHERE Id=$wordId";
                        $result = mysqli_query($connection, $findWord);
                        if (!$result) {
                            require_once "partials/500.php";
                            exit();
                        }
                        $word = mysqli_fetch_row($result);
                        if (!isset($word)) echo "<h1 class='mt-5 text-center'>Word wasn't Found!</h1>";
                        else {
                            mysqli_free_result($result);
                            $word = $word[0];
                            if (isset($translationId) && is_numeric($translationId)) {
                                $findTrn = "SELECT word FROM Translations WHERE Id=$translationId AND wordId=$wordId";

                                $result = mysqli_query($connection, $findTrn);
                                if (!$result) {
                                    require_once "partials/500.php";
                                    exit();
                                }

                                $translation = mysqli_fetch_row($result);

                                if (!isset($translation)) {
                                    echo "<h1 class='mt-5 text-center'>Translation of $word wasn't found!</h1>";
                                    exit();
                                }

                                mysqli_free_result($result);

                                $allTranslations = "SELECT COUNT(1) as total FROM Translations WHERE wordId=$wordId";
                                $result = mysqli_query($connection, $allTranslations);
                                if (!$result) {
                                    require_once "partials/500.php";
                                    exit();
                                }

                                $total = mysqli_fetch_row($result)[0];
                                mysqli_free_result($result);


                                $translation = $translation[0];
                                $_SESSION['wordId'] = $wordId;
                                $_SESSION['translationId'] = $translationId;
                                $_SESSION['total'] = $total; ?>
                                <h1 class="text-center my-5">Do you want to delete the word "<?php echo $translation ?>" which is translation of "<?php echo $word ?>" ?</h1>
                                <?php
                                if ($total == 1) { ?>
                                    <p class="error">WARNING! After deleting "<?php echo $translation ?>" the word "<?php echo $word ?>" will be also deleted because it will not have any translations.</p>
                                <?php
                                }
                            } else {
                                $_SESSION['wordId'] = $wordId; ?>
                                <h1 class="text-center my-5">Do you want to delete word "<?php echo $word ?>" ?</h1>
                            <?php } ?>
                            <div class="btn-container">
                                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                    <button class="btn btn-primary" name="submit" type="submit" value="yes">✓</button>
                                    <button class="btn btn-primary" name="submit" type="submit" value="no">X</button>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </main>
        <?php } else { ?>
            <h1 class="my-5 text-center">Not Found</h1>
    <?php
        }
    }
    mysqli_close($connection);
    ?>
    <?php require_once "partials/scripts.php" ?>
</body>

</html>