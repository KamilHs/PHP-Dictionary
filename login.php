<?php
session_start();
if (isset($_SESSION['userId']) && isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php require_once "partials/styles.php" ?>
</head>

<body>
    <main>
        <?php $data = false ?>
        <div class="container center">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <form class="p-5" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                        <?php
                        require_once "config/db.php";
                        $username = $password = $repeatPassword = "";
                        $usernameErr = $passwordErr = $repeatPasswordErr = $incorrectDataErr = "";
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
                            if (empty(trim($_POST["username"]))) $usernameErr = "Required";
                            else if (!preg_match("/^[a-zA-Z0-9]*$/", $_POST['username'])) $usernameErr = "Invalid Username";
                            else {
                                $usernameErr = "";
                                $username =  htmlspecialchars(mysqli_real_escape_string($connection, trim($_POST["username"])));
                            }
                            if (empty(trim($_POST["password"]))) $passwordErr = "Required";
                            else {
                                $passwordErr = "";
                                $password =  htmlspecialchars(mysqli_real_escape_string($connection, trim($_POST["password"])));
                            }
                            if ($password && $username) {
                                $data = checkForExistence($username, $password, $connection);
                                if (is_array($data)) {
                                    session_start();
                                    $_SESSION['userId'] = $data[0];
                                    $_SESSION['username'] = $data[1];
                                    header("Location: index.php");
                                    $incorrectDataErr = "";
                                    exit();
                                } else $incorrectDataErr = "Wrong Username or Password";
                            }
                        }
                        ?>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="username">Enter Username</label>
                                <span class="error"><?php echo $usernameErr ?></span>
                            </div>
                            <input class="form-control" autocomplete="off" value="<?php echo $username ?>" type="text" name="username" id="username">
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password">Enter Password</label>
                                <span class="error"><?php echo $passwordErr ?></span>
                            </div>
                            <input class="form-control" autocomplete="off" value="<?php echo $password ?>" type="password" name="password" id="password">
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="error"><?php echo $incorrectDataErr ?></span>
                            <div class="d-flex align-items-center">
                                <a class="mr-2" href="signup.php">Sign up</a>
                                <button name="submit" type="submit" value="login-submit" class="btn btn-primary">Log in</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>


<?php
function checkForExistence($username, $password, $connection)
{
    $result = mysqli_query($connection, "SELECT * FROM Users WHERE username='$username'");
    if (!$result) {
        require_once "partials/500.php";
        exit();
    }
    $row = mysqli_fetch_row($result);
    mysqli_free_result($result);
    if (!$row) return false;
    else {
        if (password_verify($password, $row[2])) return $row;
        return false;
    }
}
?>