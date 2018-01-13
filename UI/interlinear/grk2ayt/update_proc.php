<?php

require('config.php');

$strong = $_GET['s'];
if (!$strong)
    $strong = 1;
$type = $_GET['type'];
if (!$type)
    $type = 'all';
$show = $_GET['show'];
if (!$show)
    $show = 'all';

$link = mysql_connect($host, $name, $pass);
mysql_selectdb($db);

$to_one = array();
$to_many = array();
$to_zero = array();
$to_none = array();
$max_stage = 0;

$result = mysql_query("SELECT ayat, book, chapter, verse, net, kata, COUNT(*) AS count, wh, tipe, stage FROM $linkage_table LEFT JOIN verses ON ayat = verses.id WHERE strong = $strong GROUP BY ayat, wh ORDER BY count;");
while ($row = mysql_fetch_assoc($result)) {
    if ($row['count'] > 1) {
        $i = 0;
        $word_list = array();
        $pos_list = array();
        $result2 = mysql_query("SELECT net, kata, tipe, stage FROM $linkage_table WHERE strong = $strong AND ayat = {$row['ayat']} AND wh = {$row['wh']} ORDER BY net;");
        while ($row2 = mysql_fetch_assoc($result2)) {
            if (!is_null($row2['kata'])) {
                if (($row2['net'] - $i > 1) && ($i != 0))
                    $word_list[] = str_repeat('.', $row2['net'] - $i) . '.';
                $i = $row2['net'];
                if ($row2['tipe'])
                    $word_list[] = "<span class=\"t{$row2['tipe']}\">{$row2['kata']}</span>";
                else
                    $word_list[] = $row2['kata'];
                $pos_list[] = $row2['net'];
            }
        }
        if (count($word_list))
            $phrase = implode('&nbsp;', $word_list);
        else
            $phrase = '';
        sort($pos_list);
        $posi = implode(',', $pos_list);
        $rel_list = array();
        $result2 = mysql_query("SELECT DISTINCT wh, strong, tipe FROM $linkage_table WHERE ayat = {$row['ayat']} AND net IN ($posi) AND strong != $strong ORDER BY wh;");
        while ($row2 = mysql_fetch_assoc($result2)) {
            if ($row2['tipe'])
                $rel_list[] = "<span class=\"t{$row2['tipe']}\">{$row2['strong']}</span>";
            else
                $rel_list[] = "{$row2['strong']}";
        }
        if (count($rel_list))
            $phrase .= ' [' . implode(', ', $rel_list) . ']';
        array_shift($pos_list);
        if (!array_key_exists($row['stage'], $to_many))
            $to_many[$row['stage']] = array();
        if (array_key_exists($phrase, $to_many[$row['stage']]))
            $to_many[$row['stage']][$phrase][$row['ayat'] * 10000 + $row['wh'] * 100 + $row['net']] = array($row, implode('|', $pos_list));
        else
            $to_many[$row['stage']][$phrase] = array($row['ayat'] * 10000 + $row['wh'] * 100 + $row['net'] => array($row, implode('|', $pos_list)));
    }
    elseif (!is_null($row['kata']) && ($row['kata'] != '')) {
        if ($row['tipe'])
            $word = "<span class=\"t{$row['tipe']}\">{$row['kata']}</span>";
        else
            $word = $row['kata'];
        $rel_list = array();
        $result2 = mysql_query("SELECT DISTINCT wh, strong, tipe FROM $linkage_table WHERE ayat = {$row['ayat']} AND net = {$row['net']} AND strong != $strong ORDER BY wh;");
        while ($row2 = mysql_fetch_assoc($result2)) {
            if ($row2['tipe'])
                $rel_list[] = "<span class=\"t{$row2['tipe']}\">{$row2['strong']}</span>";
            else
                $rel_list[] = "{$row2['strong']}";
        }
        if (count($rel_list))
            $word .= ' [' . implode(', ', $rel_list) . ']';
        if (!array_key_exists($row['stage'], $to_one))
            $to_one[$row['stage']] = array();
        if (array_key_exists($word, $to_one[$row['stage']]))
            $to_one[$row['stage']][$word][$row['ayat'] * 10000 + $row['wh'] * 100 + $row['net']] = $row;
        else
            $to_one[$row['stage']][$word] = array($row['ayat'] * 10000 + $row['wh'] * 100 + $row['net'] => $row);
    }
    elseif (!is_null($row['kata'])) {
        if (!array_key_exists($row['stage'], $to_zero))
            $to_zero[$row['stage']] = array();
        $to_zero[$row['stage']][$row['ayat'] * 100 + $row['wh']] = $row;
    }
    else {
        if (!array_key_exists($row['stage'], $to_none))
            $to_none[$row['stage']] = array();
        $to_none[$row['stage']][$row['ayat'] * 100 + $row['wh']] = $row;
    }
    if ($row['stage'] > $max_stage)
        $max_stage = $row['stage'];
}

$result = mysql_query("SELECT * FROM $linkage_table WHERE strong = $strong AND stage = 0;");
$unchecked = mysql_num_rows($result);

mysql_query("LOCK TABLES $found_table WRITE, $status_table WRITE;");
mysql_query("DELETE FROM $found_table WHERE strong = $strong;");
for ($stage = $max_stage; $stage >= 1; $stage--) {
    if (array_key_exists($stage, $to_one)) {
        $to_one_index = array();
        foreach ($to_one[$stage] as $key => $value) {
            $count = count($value);
            if (array_key_exists($count, $to_one_index))
                $to_one_index[$count][] = $key;
            else
                $to_one_index[$count] = array($key);
        }
        krsort($to_one_index);
        if (count($to_one_index)) {
            foreach ($to_one_index as $key => $value)
                foreach ($value as $val) {
                    $v = mysql_escape_string($val);
                    mysql_query("INSERT INTO $found_table VALUES ($strong, \"$v\", $key);");
                }
        }
    }
    if (array_key_exists($stage, $to_many)) {
        $to_many_index = array();
        foreach ($to_many[$stage] as $key => $value) {
            $count = count($value);
            if (array_key_exists($count, $to_many_index))
                $to_many_index[$count][] = $key;
            else
                $to_many_index[$count] = array($key);
        }
        krsort($to_many_index);
        if (count($to_many_index)) {
            foreach ($to_many_index as $key => $value)
                foreach ($value as $val) {
                    $v = mysql_escape_string($val);
                    mysql_query("INSERT INTO $found_table VALUES ($strong, \"$v\", $key);");
                }
        }
    }
}
if ($unchecked)
    mysql_query("UPDATE $status_table SET checked = 0 WHERE id = $strong;");
else
    mysql_query("UPDATE $status_table SET checked = 1 WHERE id = $strong;");
mysql_query("UPDATE $status_table SET refreshed = 1 WHERE id = $strong;");
mysql_query("UNLOCK TABLES;");

header("Location: $location?type=$type&show=$show#s$strong");

?>
