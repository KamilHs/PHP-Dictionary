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
    $wordId = htmlspecialchars($_GET['wordId']);
    if (!isset($wordId) || !is_numeric($wordId)) echo "<h1 class='mt-5 text-center'>Not Found!</h1>";
    else {
        $wordQuery = "SELECT Word FROM Words WHERE Id = $wordId";
        $result = mysqli_query($connection, $wordQuery);

        if (!$result) {
            require_once "partials/500.php";
            exit();
        }
        $word = mysqli_fetch_row($result);

        mysqli_free_result($result);

        if (!isset($word)) echo "<h1 class='mt-5 text-center'>Not Found!</h1>";
        else {
            $word = $word[0]; ?>
            <main class="my-5">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <h1 class="mb-3 text-center">Translations of <?php echo $word ?></h1>
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 10%" scope="col">#</th>
                                        <th style="width: 80%" scope="col">Translation</th>
                                        <th class="text-center" style="width: 5%;" scope="col">Edit</th>
                                        <th class="text-center" style="width: 5%;" scope="col">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $trnsQry = "SELECT Id,word FROM Translations WHERE wordId=$wordId";
                                    $result = mysqli_query($connection, $trnsQry);
                                    if (!$result) {
                                        require_once "partials/500.php";
                                        exit();
                                    }

                                    $trns = mysqli_fetch_all($result);

                                    mysqli_close($connection);
                                    $row = 1;
                                    foreach ($trns as $trn) { ?>
                                        <tr>
                                            <td><?php echo $row++ ?></td>
                                            <td><?php echo $trn[1] ?></td>
                                            <td><a href=<?php echo "edit-word.php?wordId=$wordId&translationId=$trn[0]" ?>>Edit</a></td>
                                            <td><a href=<?php echo "delete-word.php?wordId=$wordId&translationId=$trn[0]" ?>>Delete</a></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
    <?php
        }
    } ?>
    <?php require_once "partials/scripts.php" ?>
</body>

</html>