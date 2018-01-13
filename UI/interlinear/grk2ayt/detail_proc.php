<?php

require('config.php');

$id = $_GET['id'];
echo $id;
if (($id < 23146) || ($id > 31102))
	exit('wrong verse number');
$strong = $_GET['strong'];

$l = $_GET['l'];
$r = $_GET['r'];
$t = $_GET['t'];
$s = $_GET['s'];

if ((!$r) && (!$l)) {
    $r = array();
    $l = array();
}

$count = count($l);
if ($count != count($r))
	exit('different number of data');

$link = mysql_connect($host, $name, $pass);
mysql_selectdb($db);

$result = mysql_query("SELECT * FROM $target_table WHERE id = $id;");
$ncount = mysql_num_rows($result);
while ($row = mysql_fetch_assoc($result)) {
    echo $row['word'];
    $nets[$row['pos']] = mysql_escape_string($row['word']);
}

$result = mysql_query("SELECT * FROM wh_word WHERE ayat = $id;");
$hcount = mysql_num_rows($result);
while ($row = mysql_fetch_assoc($result)) {
    $hebs[$row['pos']] = $row['strong'];
}

//$result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $id;");
//if ($row = mysql_fetch_assoc($result)) {
//    $pair = $row['pair'];
//}
//else
//    exit('wrong pair');

mysql_query("LOCK TABLES $linkage_table WRITE, $status_table WRITE;");
mysql_query("DELETE FROM $linkage_table where ayat = $id;");
for ($i = 0; $i < $count; $i++) {
    if ($s[$i] == -1)
        $ss = 1;
    else
        $ss = $s[$i] - 1;
    if ($hebs[$r[$i]])
        $str = $hebs[$r[$i]];
    else
        $str = 0;
    mysql_query("INSERT INTO $linkage_table VALUES($id, {$l[$i]}, \"{$nets[$l[$i]]}\", {$r[$i]}, $str, {$t[$i]}, $ss);");
}

for ($i = 1; $i <= $ncount; $i++) {
    if (!in_array($i, $l))
        mysql_query("INSERT INTO $linkage_table VALUES($id, $i, \"{$nets[$i]}\", 255, NULL, 0, 0);");
}
for ($i = 1; $i <= $hcount; $i++) {
    if (!in_array($i, $r))
        mysql_query("INSERT INTO $linkage_table VALUES($id, 255, NULL, $i, {$hebs[$i]}, 0, 0);");
}
$strongs = array_unique($hebs);
foreach ($strongs as $str) {
    mysql_query("UPDATE $status_table SET refreshed = 0 WHERE id = $str;");
}
mysql_query("UNLOCK TABLES;");

header("Location: $location$detail_page?id=$id&s=$strong");

?>
