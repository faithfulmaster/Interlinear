<?php

require('config.php');

$ids = $_POST['ids'];
$strong = $_POST['strong'];

$link = mysql_connect($host, $name, $pass);
mysql_selectdb($db);

$prev_ayat = 0;
$prev_wh = 0;
$prev_strong = 0;
$is_empty = FALSE;
mysql_query("LOCK TABLES $linkage_table WRITE, $target_table READ, wh_word READ;");
foreach ($_POST as $key => $value) {
    if ($key[0] == 'c') {
        $links = explode('_', substr($key, 1));
        $ayat = $links[0];
        $wh = $links[1];
        $net = $links[2];
//        $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//        $row = mysql_fetch_assoc($result);
//        $pair = $row['pair'];
        $result = mysql_query("SELECT strong FROM wh_word WHERE ayat = $ayat AND pos = $wh;");
        $row = mysql_fetch_assoc($result);
        $strong = $row['strong'];
        $result = mysql_query("SELECT word FROM $target_table WHERE id = $ayat AND pos = $net;");
        $row = mysql_fetch_assoc($result);
        $word = $row['word'];
        if (($ayat != $prev_ayat) || ($wh != $prev_wh)){
            if ($is_empty)
                mysql_query("INSERT INTO $linkage_table VALUES ($prev_ayat, 255, NULL, $prev_wh, $prev_strong, 0, 0);");
            mysql_query("DELETE FROM $linkage_table WHERE ayat = $ayat AND wh = $wh;");
            $prev_ayat = $ayat;
            $prev_wh = $wh;
            $prev_strong = $strong;
            $is_empty = TRUE;
        }
        if ($value != 'd0') {
            $val = (int)substr($value, 1);
            mysql_query("INSERT INTO $linkage_table VALUES ($ayat, $net, \"$word\", $wh, $strong, $val, 0);");
            mysql_query("DELETE FROM $linkage_table WHERE ayat = $ayat AND net = $net AND wh = 255;");
            $is_empty = FALSE;
        }
        else {
            $result = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $ayat AND net = $net;");
            if (!mysql_num_rows($result))
                mysql_query("INSERT INTO $linkage_table VALUES ($ayat, $net, \"$word\", 255, NULL, 0, 0);");
        }
    }
}
if ($is_empty)
    mysql_query("INSERT INTO $linkage_table VALUES ($prev_ayat, 255, NULL, $prev_wh, $prev_strong, 0, 0);");
mysql_query("UNLOCK TABLES;");

header("Location: $location$strong_page?s=$strong");
?>
