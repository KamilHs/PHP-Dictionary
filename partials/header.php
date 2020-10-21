<?php require "partials/checkLogin.php" ?>

<header style="z-index: 10000000; position:relative">
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav mr-auto mt-lg-0">
                <?php
                $pages = array("Home" => "index.php", "Add Word" => "addWord.php", "Search Word" => "search-word.php", "Quizzes" => "quizzes.php", $_SESSION['username'] => 'profile.php', "Logout" => "logout.php");
                foreach ($pages as $page => $link) { ?>
                    <li class="mx-3 nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == $link ? "active" : ""  ?>" href="<?php echo $link ?>"><?php echo $page ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</header>