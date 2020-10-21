<?php
const HOST = "localhost";
const USER = "root";
const PASSWORD = "";
const DATABASE = "dictionary";


$connection = mysqli_connect(HOST, USER, PASSWORD, DATABASE);

if (!$connection) {
  echo mysqli_connect_error();
  exit();
} else {
  $showTables = "SHOW TABLES FROM " . DATABASE;

  $result = mysqli_query($connection, $showTables);

  if (!$result) {
    require_once "partials/500.php";
    exit();
  }

  if (mysqli_num_rows($result) == 0)
    prepareDatabase($connection);
  else
    mysqli_free_result($result);
}


function prepareDatabase($connection)
{
  $query = "
    CREATE TABLE IF NOT EXISTS `Words` (
        `id` int(11) NOT NULL,
        `userId` int(11) NOT NULL,
        `createdAt` datetime NOT NULL,
        `word` varchar(255) NOT NULL,
        `guessedCorrectly` int(11) NOT NULL,
        `wasInQuiz` int(11) NOT NULL
      );

      CREATE TABLE IF NOT EXISTS `Quizzes` (
        `Id` int(11) NOT NULL,
        `userId` int(11) NOT NULL,
        `startedAt` datetime NOT NULL,
        `endedAt` datetime NOT NULL,
        `score` int(11) NOT NULL,
        `questionCount` int(11) NOT NULL
      );
      
      CREATE TABLE IF NOT EXISTS `Quiz_Items` (
        `Id` int(11) NOT NULL,
        `quizId` int(11) NOT NULL,
        `word` varchar(255) NOT NULL,
        `isCorrect` tinyint(1) NOT NULL,
        `correctAnswer` varchar(255) NOT NULL,
        `userAnswer` varchar(255) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
      
      CREATE TABLE IF NOT EXISTS `Translations` (
        `Id` int(11) NOT NULL,
        `word` varchar(255) NOT NULL,
        `wordId` int(11) NOT NULL
      );

      CREATE TABLE IF NOT EXISTS `Users` (
        `userId` int(11) NOT NULL,
        `username` varchar(50) NOT NULL,
        `password` varchar(200) NOT NULL
      );

      ALTER TABLE `Words`
        ADD PRIMARY KEY (`id`),
        ADD KEY `userId` (`userId`);

      ALTER TABLE `Quizzes`
        ADD PRIMARY KEY (`Id`),
        ADD KEY `userId` (`userId`);
      
      ALTER TABLE `Quiz_Items`
        ADD PRIMARY KEY (`Id`),
        ADD KEY `quiz_id` (`quizId`);

      ALTER TABLE `Translations`
        ADD PRIMARY KEY (`Id`),
        ADD KEY `wordId` (`wordId`);

      ALTER TABLE `Users`
        ADD PRIMARY KEY (`userId`);
      
      ALTER TABLE `Words`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
      
      ALTER TABLE `Quizzes`
        MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
      
      ALTER TABLE `Quiz_Items`
        MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
      
      ALTER TABLE `Translations`
        MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
      
      ALTER TABLE `Users`
        MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
      
      ALTER TABLE `Words`
        ADD CONSTRAINT `Words_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
      
      ALTER TABLE `Quizzes`
        ADD CONSTRAINT `Quizzes_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `Users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
      
      ALTER TABLE `Quiz_Items`
        ADD CONSTRAINT `Quiz_Items_ibfk_2` FOREIGN KEY (`quizId`) REFERENCES `Quizzes` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
      ALTER TABLE `Translations`
        ADD CONSTRAINT `Translations_ibfk_1` FOREIGN KEY (`wordId`) REFERENCES `Words` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
      COMMIT;
    ";

  if (!mysqli_multi_query($connection, $query)) {
    require_once "../partials/500.php";
  }
}
