<?php
session_start();
require_once "config/db.php";
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    $from;
    $to;
    $period = "";
    if (isset($_GET['from']) && isset($_GET['to'])) {
        $from = date("Y-m-d", strtotime(stripcslashes(mysqli_real_escape_string($connection, $_GET['from']))));
        $to = date("Y-m-d", strtotime(stripcslashes(mysqli_real_escape_string($connection, $_GET['to']))));
        $period = "createdAt BETWEEN '$from' AND '$to AND'";
    } else if (isset($_GET['from'])) {
        $from = date("Y-m-d", strtotime(stripcslashes(mysqli_real_escape_string($connection, $_GET['from']))));
        $period = "createdAt > '$from AND'";
    } else if (isset($_GET['to'])) {
        $to = date("Y-m-d", strtotime(stripcslashes(mysqli_real_escape_string($connection, $_GET['to']))));
        $period = "createdAt < '$to AND'";
    }
    if (isset($_GET['limit']) && isset($_GET['start'])) {
        $start = stripcslashes(mysqli_real_escape_string($connection, $_GET['start']));
        $limit = stripcslashes(mysqli_real_escape_string($connection, $_GET['limit']));
        $orderBy = stripcslashes(mysqli_real_escape_string($connection, $_GET['order']));
        $way = stripcslashes(mysqli_real_escape_string($connection, $_GET['way']));
        if (isset($_GET['like'])) {
            $like = stripcslashes(mysqli_real_escape_string($connection, $_GET['like']));
            return getByPattern($start, $limit, $orderBy, $period, $way, $like, $connection);
        }
        return getByOrder($start, $limit, $orderBy, $period, $way, $connection);
    } else if (isset($_GET['max'])) {
        if (isset($_GET['like'])) {
            $like = stripcslashes(mysqli_real_escape_string($connection, $_GET['like']));
            return getCountByPattern($like, $period, $connection);
        }
        return getCountAll($period, $connection);
    }
}

function runQueries($wordQry, $trnsQry, $connection)
{
    $result = mysqli_query($connection, $wordQry);
    if (!$result)
        return "Invalid";
    $words =  mysqli_fetch_all($result);
    mysqli_free_result($result);

    $data = [];
    foreach ($words as $word) {
        $result = mysqli_query($connection, $trnsQry . $word[0]);
        if (!$result)
            return "Invalid";

        $translations = mysqli_fetch_all($result);
        mysqli_free_result($result);

        $implodedTranslations = "";
        foreach ($translations as $translation)
            $implodedTranslations = $implodedTranslations  . $translation[0] . ", ";
        array_push($data, ['id' => $word[0], 'word' => $word[1], 'trns' => substr($implodedTranslations, 0, -2)]);
    }

    return $data;
}


function getByOrder($start, $limit, $orderBy, $period, $way, $connection)
{
    $fetchWord = "SELECT Id,word FROM Words WHERE $period userId=" . $_SESSION['userId'] . " ORDER BY $orderBy $way LIMIT $start,$limit";
    $fetchTrns = "SELECT Word FROM Translations WHERE wordId =";

    echo json_encode(runQueries($fetchWord, $fetchTrns, $connection));
}

function getByPattern($start, $limit, $orderBy, $period, $way, $pattern, $connection)
{
    $fetchWord = "SELECT DISTINCT Words.Id, Words.word FROM Words 
    LEFT JOIN Translations ON Words.Id = Translations.wordId WHERE
    $period Words.userId=" . $_SESSION['userId'] . " AND (Words.word LIKE('%$pattern%') OR Translations.word LIKE('%$pattern%')) ORDER BY $orderBy $way LIMIT $start,$limit";
    $fetchTrns = "SELECT Word FROM Translations WHERE wordId =";

    echo json_encode(runQueries($fetchWord, $fetchTrns, $connection));
}


function getCountAll($period, $connection)
{
    $result = mysqli_query($connection, "SELECT * FROM Words WHERE $period userId=" . $_SESSION['userId']);
    if (!$result)
        return null;
    echo json_encode(mysqli_num_rows($result));
    mysqli_free_result($result);
}


function getCountByPattern($pattern, $period, $connection)
{
    $fetchWord = "SELECT DISTINCT Words.Id FROM Words 
    LEFT JOIN Translations ON Words.Id = Translations.wordId WHERE
    $period userId=" . $_SESSION['userId'] . " AND (Words.word LIKE('%$pattern%') OR Translations.word LIKE('%$pattern%')) ORDER BY createdAt DESC";

    $result = mysqli_query($connection, $fetchWord);
    if (!$result)
        return null;
    echo json_encode(mysqli_num_rows($result));
    mysqli_free_result($result);
}
