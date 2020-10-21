<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <?php require_once "partials/styles.php" ?>
</head>

<body>
    <main>
        <?php $signedUp = false ?>
        <div class="container center">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <form class="p-5" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                        <?php
                        require_once "config/db.php";
                        $username = $password = $repeatPassword = "";
                        $usernameErr = $passwordErr = $repeatPasswordErr = "";
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (empty(trim($_POST["username"]))) $usernameErr = "Required";
                            else if (!preg_match("/^[a-zA-Z0-9]*$/", $_POST['username'])) $usernameErr = "Invalid Username";
                            else {
                                $usernameErr = "";
                                $username =  htmlspecialchars(mysqli_real_escape_string($connection, trim($_POST["username"])));
                                if (checkExistence($username, $connection))  $usernameErr = "Username is already used";
                            }
                            if (empty(trim($_POST["password"]))) $passwordErr = "Required";
                            else {
                                $passwordErr = "";
                                $password =  htmlspecialchars(mysqli_real_escape_string($connection, trim($_POST["password"])));
                            }
                            if (empty(trim($_POST["password"]))) $repeatPasswordErr = "Required";
                            else {
                                $repeatPassword =  htmlspecialchars(mysqli_real_escape_string($connection, trim($_POST["repeat-password"])));
                                if ($repeatPassword != $password) $repeatPasswordErr = "Password must match";
                                else $repeatPasswordErr = "";
                            }
                            if ($passwordErr == $repeatPasswordErr && $passwordErr == $usernameErr && $passwordErr == "") {
                                addUser($username, $password, $connection);
                                $signedUp = true;
                            }
                        }
                        ?>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="username">Enter Username</label>
                                <span class="error"><?php echo $usernameErr ?></span>
                            </div>
                            <input class="form-control" value="<?php echo $username ?>" type="text" name="username" id="username">
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password">Enter Password</label>
                                <span class="error"><?php echo $passwordErr ?></span>
                            </div>
                            <input class="form-control" value="<?php echo $password ?>" type="password" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="repeat-password">Repeat Password</label>
                                <span class="error"><?php echo $repeatPasswordErr ?></span>
                            </div>
                            <input class="form-control" value="<?php echo $repeatPassword ?>" type="password" name="repeat-password" id="repeat-password">
                        </div>
                        <div class="d-flex justify-content-end align-items-center mt-3">
                            <a class="mr-2" href="login.php">Log in</a>
                            <button class="btn btn-primary">Sign up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php if ($signedUp) { ?>
        <div class="custom-modal d-flex justify-content-center align-items-center">
            <form class="p-5" action="login.php" method="POST">
                <div class="d-flex flex-column between align-items-center">
                    <h1 class="text-center success mb-3">Account was added</h1>
                    <button class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    <?php } ?>
</body>

</html>

<?php

function checkExistence($username, $connection)
{
    $result = mysqli_query($connection, "SELECT * FROM Users WHERE username='$username'");
    if (!$result) {
        require_once "partials/500.php";
        exit();
    }
    $rows = mysqli_num_rows($result);
    mysqli_free_result($result);
    return  $rows;
}
function addUser($username, $password, $connection)
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $insertUser = "INSERT INTO Users (username,password) VALUES('$username','$hashedPassword')";
    mysqli_query($connection, $insertUser);
}

?>