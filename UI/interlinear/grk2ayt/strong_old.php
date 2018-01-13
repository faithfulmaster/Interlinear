<?php

require('config.php');

$strong = $_GET['s'];

$link = mysql_connect($host, $name, $pass);
mysql_selectdb($db);

$result = mysql_query("SELECT * FROM greek WHERE id = $strong;");
$greek = mysql_fetch_assoc($result);

echo "<html>\n";
echo "<head>\n";
echo "<title>{$greek['word_id']} #$strong</title>\n";
echo "<link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\" />\n";
echo "<script type=\"text/javascript\" language=\"javascript\" src=\"scripts.js\"></script>\n";
echo "</head>\n";
echo "<body>\n";

$prev_strong = $strong - 1;
$next_strong = $strong + 1;
$to_one = array();
$to_many = array();
$to_zero = array();
$to_none = array();
$max_stage = 0;
$auto = 1;

$result = mysql_query("SELECT strong FROM strong_name WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=name#s$strong\">Index Name</a> | Type Name:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_name LEFT JOIN $status_table ON ($status_table.id = strong_name.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_name = $row['id'];
        echo "<a href=\"?s=$prev_name\">Prev # ($prev_name)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_name LEFT JOIN $status_table ON ($status_table.id = strong_name.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_name = $row['id'];
        echo "<a href=\"?s=$next_name\">Next # ($next_name)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_noun WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=noun#s$strong\">Index Noun</a> | Type Noun:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_noun LEFT JOIN $status_table ON ($status_table.id = strong_noun.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_noun = $row['id'];
        echo "<a href=\"?s=$prev_noun\">Prev # ($prev_noun)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_noun LEFT JOIN $status_table ON ($status_table.id = strong_noun.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_noun = $row['id'];
        echo "<a href=\"?s=$next_noun\">Next # ($next_noun)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_verb WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=verb#s$strong\">Index Verb</a> | Type Verb:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_verb LEFT JOIN $status_table ON ($status_table.id = strong_verb.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_verb = $row['id'];
        echo "<a href=\"?s=$prev_verb\">Prev # ($prev_verb)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_verb LEFT JOIN $status_table ON ($status_table.id = strong_verb.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_verb = $row['id'];
        echo "<a href=\"?s=$next_verb\">Next # ($next_verb)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_adj WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=adj#s$strong\">Index Adjective</a> | Type Adjective:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_adj LEFT JOIN $status_table ON ($status_table.id = strong_adj.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_adj = $row['id'];
        echo "<a href=\"?s=$prev_adj\">Prev # ($prev_adj)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_adj LEFT JOIN $status_table ON ($status_table.id = strong_adj.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_adj = $row['id'];
        echo "<a href=\"?s=$next_adj\">Next # ($next_adj)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_adv WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=adv#s$strong\">Index Adverb</a> | Type Adverb:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_adv LEFT JOIN $status_table ON ($status_table.id = strong_adv.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_adv = $row['id'];
        echo "<a href=\"?s=$prev_adv\">Prev # ($prev_adv)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_adv LEFT JOIN $status_table ON ($status_table.id = strong_adv.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_adv = $row['id'];
        echo "<a href=\"?s=$next_adv\">Next # ($next_adv)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_conj WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=conj#s$strong\">Index Conjunction</a> | Type Conjunction:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_conj LEFT JOIN $status_table ON ($status_table.id = strong_conj.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_conj = $row['id'];
        echo "<a href=\"?s=$prev_conj\">Prev # ($prev_conj)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_conj LEFT JOIN $status_table ON ($status_table.id = strong_conj.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_conj = $row['id'];
        echo "<a href=\"?s=$next_conj\">Next # ($next_conj)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_prep WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=prep#s$strong\">Index Preposition</a> | Type Preposition:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_prep LEFT JOIN $status_table ON ($status_table.id = strong_prep.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_prep = $row['id'];
        echo "<a href=\"?s=$prev_prep\">Prev # ($prev_prep)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_prep LEFT JOIN $status_table ON ($status_table.id = strong_prep.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_prep = $row['id'];
        echo "<a href=\"?s=$next_prep\">Next # ($next_prep)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_part WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=part#s$strong\">Index Particle</a> | Type Particle:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_part LEFT JOIN $status_table ON ($status_table.id = strong_part.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_part = $row['id'];
        echo "<a href=\"?s=$prev_part\">Prev # ($prev_part)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_part LEFT JOIN $status_table ON ($status_table.id = strong_part.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_part = $row['id'];
        echo "<a href=\"?s=$next_part\">Next # ($next_part)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_pron WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=pron#s$strong\">Index Pronoun</a> | Type Pronoun:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_pron LEFT JOIN $status_table ON ($status_table.id = strong_pron.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_pron = $row['id'];
        echo "<a href=\"?s=$prev_pron\">Prev # ($prev_pron)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_pron LEFT JOIN $status_table ON ($status_table.id = strong_pron.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_pron = $row['id'];
        echo "<a href=\"?s=$next_pron\">Next # ($next_pron)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_rare WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=rare#s$strong\">Index Rare</a> | Type Rare:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_rare LEFT JOIN $status_table ON ($status_table.id = strong_rare.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_rare = $row['id'];
        echo "<a href=\"?s=$prev_rare\">Prev # ($prev_rare)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_rare LEFT JOIN $status_table ON ($status_table.id = strong_rare.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_rare = $row['id'];
        echo "<a href=\"?s=$next_rare\">Next # ($next_rare)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_semirare WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=semirare#s$strong\">Index Semirare</a> | Type Semirare:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_semirare LEFT JOIN $status_table ON ($status_table.id = strong_semirare.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_semirare = $row['id'];
        echo "<a href=\"?s=$prev_semirare\">Prev # ($prev_semirare)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_semirare LEFT JOIN $status_table ON ($status_table.id = strong_semirare.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_semirare = $row['id'];
        echo "<a href=\"?s=$next_semirare\">Next # ($next_semirare)</a>\n";
    }
    echo "</b></p>";
}
$result = mysql_query("SELECT strong FROM strong_rest WHERE strong = $strong;");
if (mysql_num_rows($result)) {
    echo "<p><b><a href=\".#s$strong\">Index</a> | <a href=\".?type=rest#s$strong\">Index Rest</a> | Type Rest:\n";
    $result = mysql_query("SELECT $status_table.id FROM strong_rest LEFT JOIN $status_table ON ($status_table.id = strong_rest.strong) WHERE $status_table.id < $strong AND checked = 0 ORDER BY $status_table.id DESC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $prev_rest = $row['id'];
        echo "<a href=\"?s=$prev_rest\">Prev # ($prev_rest)</a>\n";
    }
    $result = mysql_query("SELECT $status_table.id FROM strong_rest LEFT JOIN $status_table ON ($status_table.id = strong_rest.strong) WHERE $status_table.id > $strong AND checked = 0 ORDER BY $status_table.id ASC LIMIT 1;");
    if ($row = mysql_fetch_assoc($result)) {
        $next_rest = $row['id'];
        echo "<a href=\"?s=$next_rest\">Next # ($next_rest)</a>\n";
    }
    echo "</b></p>";
}

echo "<form action=\"$strong_proc_page\" method=\"post\">\n";
if ($prev_strong > 0)
    echo "<a href=\"?s=$prev_strong\">Prev # ($prev_strong)</a><br>\n";
echo "<input type=\"submit\" value=\"Submit\">\n";
if ($next_strong < 5625)
    echo "<a href=\"?s=$next_strong\">Next # ($next_strong)</a>\n";

$result = mysql_query("SELECT * FROM nasb_greek WHERE id = $strong;");
$nasb = mysql_fetch_assoc($result);

echo "<table>\n";
echo "<tr valign=\"top\"><td>\n";
echo "<table>\n";
$word = preg_replace('/\\\\~(.+?)\\\\~/', "<span class=\"g\">$1</span>", $greek['word']);
echo "<tr valign=\"top\"><td>Strong#:</td><td><big>$strong // $word</big></td></tr>\n";
echo "<tr valign=\"top\"><td>Orig:</td><td><b>{$greek['kind']}</b> // {$greek['origin']}</td></tr>\n";
echo "<tr valign=\"top\"><td>NASB:</td><td>==>{$nasb['gloss']}</td></tr>\n";
echo "<tr valign=\"top\"><td>AV={$greek['count']}</td><td>==>{$greek['av']}</td></tr>\n";
echo "</table>\n";
echo "</td><td>\n";
echo "<table>\n";
echo "<tr valign=\"top\"><td>Definition:</td><td><pre>{$greek['definition']}</pre></td></tr>\n";
echo "<tr valign=\"top\"><td>&nbsp;&nbsp;&nbsp;&nbsp;NASB:</td><td>&nbsp;&nbsp;{$nasb['def']}</td></tr>\n";
echo "</table>\n";
echo "</td></tr>\n";    
echo "</table>\n";

$result = mysql_query("SELECT COUNT(*) AS count FROM wh_word WHERE strong = $strong;");
if ($row = mysql_fetch_assoc($result))
    $total = $row['count'];

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
        $net = array_shift($pos_list);
        if (!array_key_exists($row['stage'], $to_many))
            $to_many[$row['stage']] = array();
        if (array_key_exists($phrase, $to_many[$row['stage']]))
            $to_many[$row['stage']][$phrase][$row['ayat'] * 10000 + $row['wh'] * 100 + $net] = array($row, implode('|', $pos_list));
        else
            $to_many[$row['stage']][$phrase] = array($row['ayat'] * 10000 + $row['wh'] * 100 + $net => array($row, implode('|', $pos_list)));
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
echo "<hr><b>Total: $total</b>\n";
echo "<input type=\"hidden\" name=\"h_strong\" value=\"$strong\">\n";
for ($stage = $max_stage; $stage >= 0; $stage--) {
    echo "<table>\n";
    echo "<tr><td colspan=\"6\"><b><big>{$stages[$stage]}</big></b></td></tr>\n";
    if (!$stage)
        $super_combo = array();
    if (array_key_exists($stage, $to_one)) {
        $to_one_index = array();
        $sub_total = 0;
        foreach ($to_one[$stage] as $key => $value) {
            $count = count($value);
            $sub_total += $count;
            if (array_key_exists($count, $to_one_index))
                $to_one_index[$count][] = $key;
            else
                $to_one_index[$count] = array($key);
        }
        $total -= $sub_total;
        krsort($to_one_index);
        if (count($to_one_index)) {
            $links = array();
            foreach ($to_one_index as $key => $value)
                foreach ($value as $val) {
                    $refs = $to_one[$stage][$val];
                    ksort($refs);
                    $r = array_values($refs);
                    if ($r[0]['tipe'] == 1)
                        $checked = ' checked';
                    else
                        $checked = '';
                    $name = implode('_', array_keys($refs));
                    if ($stage)
                        echo "<tr valign=\"top\"><td class=\"dem\"><input type=\"checkbox\" name=\"u1_" . $name . "\"></td>";
                    else
                        echo "<tr valign=\"top\"><td class=\"del\"><input type=\"checkbox\" name=\"d1_" . $name . "\"></td>";
                    if (($stage == $max_stage) && ($stage != 0))
                        echo "<td><select id=\"as_$auto\" name=\"s1_" . $name . "\">";
                    else
                        echo "<td><select id=\"as_$auto\" name=\"s1_" . $name . "\" onchange=\"autoCheck(this, ac_$auto)\">";
                    foreach ($types as $type => $vis) {
                        if ($type == 0)
                            echo "<option value=\"$type\" selected>$vis";
                        else
                            echo "<option value=\"$type\">$vis";
                    }
                    if (($stage == $max_stage) && ($stage != 0))
                        echo "</select></td><td><img src=\"bullet.gif\" onclick=\"autoSelect(as_$auto)\" /></td>";
                    else
                        echo "</select></td><td><img src=\"bullet.gif\" onclick=\"autoSelect2(as_$auto, ac_$auto)\" /></td>";
                    if (($stage == $max_stage) && ($stage != 0))
                        echo "<td><span class=\"checked\">&#10004;</span></td>";
                    else
                        echo "<td><input type=\"checkbox\" id=\"ac_$auto\" name=\"c1_" . $name . "\"$checked></td>";
                    echo "<td>$val</td>";
                    $combo = array();
                    foreach ($refs as $ref)
                        $combo[] = $ref['ayat'] * 100 + $ref['wh'];
                    $combo = implode(',', $combo);
                    if (!$stage)
                        array_push($super_combo, $combo);
                    echo "<td><a href=\"$combo_page?ids=$combo&amp;s=$strong\">$key</a></td><td>";
                    $auto++;
                    $i = 1;
                    foreach ($refs as $ref) {
                        echo "<a href=\"$detail_page?id={$ref['ayat']}&amp;s=$strong\" title=\"{$books[$ref['book']]['abbr']} {$ref['chapter']}:{$ref['verse']}\">&middot;</a>";
                        if ($i % 10 == 0)
                            echo "\n";
                        $i++;
                    }
                    echo "</td></tr>\n";
                    if ($stage) {
                        $v = mysql_escape_string($val);
                        mysql_query("INSERT INTO $found_table VALUES ($strong, \"$v\", $key);");
                    }
                }
        }
    }
    if (array_key_exists($stage, $to_many)) {
        $to_many_index = array();
        $sub_total = 0;
        foreach ($to_many[$stage] as $key => $value) {
            $count = count($value);
            $sub_total += $count;
            if (array_key_exists($count, $to_many_index))
                $to_many_index[$count][] = $key;
            else
                $to_many_index[$count] = array($key);
        }
        $total -= $sub_total;
        krsort($to_many_index);
        if (count($to_many_index)) {
            $links = array();
            foreach ($to_many_index as $key => $value)
                foreach ($value as $val) {
                    $refs = $to_many[$stage][$val];
                    ksort($refs);
                    $r = array();
                    foreach ($refs as $k => $v)
                        $r[] = "$k|{$v[1]}";
                    $name = implode('_', $r);
                    if ($stage)
                        echo "<tr valign=\"top\"><td class=\"dem\"><input type=\"checkbox\" name=\"u2_" . $name . "\"></td>";
                    else
                        echo "<tr valign=\"top\"><td class=\"del\"><input type=\"checkbox\" name=\"d2_" . $name . "\"></td>";
                    if (($stage == $max_stage) && ($stage != 0))
                        echo "<td><select id=\"as_$auto\" name=\"s2_" . $name . "\">";
                    else
                        echo "<td><select id=\"as_$auto\" name=\"s2_" . $name . "\" onchange=\"autoCheck(this, ac_$auto)\">";
                    $r = array_values($refs);
                    foreach ($types as $type => $vis) {
                        if ($type == 0)
                            echo "<option value=\"$type\" selected>$vis";
                        else
                            echo "<option value=\"$type\">$vis";
                    }
                    if (($stage == $max_stage) && ($stage != 0))
                        echo "</select></td><td><img src=\"bullet.gif\" onclick=\"autoSelect(as_$auto)\" /></td>";
                    else
                        echo "</select></td><td><img src=\"bullet.gif\" onclick=\"autoSelect2(as_$auto, ac_$auto)\" /></td>";
                    if (($stage == $max_stage) && ($stage != 0))
                        echo "<td><span class=\"checked\">&#10004;</span></td>";
                    else
                        echo "<td><input type=\"checkbox\" id=\"ac_$auto\" name=\"c2_" . $name . "\"></td>";
                    echo "<td>$val</td>";
                    $combo = array();
                    foreach ($refs as $ref)
                        $combo[] = $ref[0]['ayat'] * 100 + $ref[0]['wh'];
                    $combo = implode(',', $combo);
                    if (!$stage)
                        array_push($super_combo, $combo);
                    echo "<td><a href=\"$combo_page?ids=$combo&amp;s=$strong\">$key</a></td><td>";
                    $auto++;
                    $i = 1;
                    foreach ($refs as $ref) {
                        echo "<a href=\"$detail_page?id={$ref[0]['ayat']}&amp;s=$strong\" title=\"{$books[$ref[0]['book']]['abbr']} {$ref[0]['chapter']}:{$ref[0]['verse']}\">&middot;</a>";
                        if ($i % 10 == 0)
                            echo "\n";
                        $i++;
                    }
                    echo "</td></tr>\n";
                    if ($stage) {
                        $v = mysql_escape_string($val);
                        mysql_query("INSERT INTO $found_table VALUES ($strong, \"$v\", $key);");
                    }
                }
        }
    }
    if (count($to_zero[$stage])) {
        $count = count($to_zero[$stage]);
        $total -= $count;
        ksort($to_zero[$stage]);
        $name = implode('_', array_keys($to_zero[$stage]));
        if ($stage)
            echo "<tr valign=\"top\"><td class=\"dem\"><input type=\"checkbox\" name=\"u3_" . $name . "\"></td>";
        else
            echo "<tr valign=\"top\"><td class=\"del\">&nbsp;</td>";
        if (($stage == $max_stage) && ($stage != 0)) {
            echo "<td><input type=\"radio\" name=\"s3_" . $name . "\" value=\"0\" title=\"0\" checked><input type=\"radio\" name=\"s3_" . $name . "\" value=\"255\" title=\"[NOT TRANSLATED]\"></td><td>&nbsp;</td>";
            echo "<td><span class=\"checked\">&#10004;</span></td>";
        }
        else {
            echo "<td><input type=\"radio\" name=\"s3_" . $name . "\" value=\"0\" onclick=\"autoCheck2(this, ac_$auto)\" title=\"0\" checked><input type=\"radio\" name=\"s3_" . $name . "\" value=\"255\" onclick=\"autoCheck2(this, ac_$auto)\" title=\"[NOT TRANSLATED]\"></td><td>&nbsp;</td>";
            echo "<td><input type=\"checkbox\" id=\"ac_$auto\" name=\"c3_" . $name . "\"></td>";
        }
        echo "<td><span class=\"empty\">&#8709;</span></td>";
        $combo = array();
        foreach ($to_zero[$stage] as $ref)
            $combo[] = $ref['ayat'] * 100 + $ref['wh'];
        $combo = implode(',', $combo);
        if (!$stage)
            array_push($super_combo, $combo);
        echo "<td><a href=\"$combo_page?ids=$combo&amp;s=$strong\">$count</a></td><td>";
        $auto++;
        $i = 1;
        foreach ($to_zero[$stage] as $ref) {
            echo "<a href=\"$detail_page?id={$ref['ayat']}&amp;s=$strong\" title=\"{$books[$ref['book']]['abbr']} {$ref['chapter']}:{$ref['verse']}\">&middot;</a>";
            if ($i % 10 == 0)
                echo "\n";
            $i++;
        }
        echo "</td></tr>\n";
    }
    if (count($to_none[$stage])) {
        $count = count($to_none[$stage]);
        $total -= $count;
        ksort($to_none[$stage]);
        $name = implode('_', array_keys($to_none[$stage]));
        if ($stage)
            echo "<tr valign=\"top\"><td class=\"dem\"><input type=\"checkbox\" name=\"u4_" . $name . "\"></td>";
        else
            echo "<tr valign=\"top\"><td class=\"del\">&nbsp;</td>";
        echo "<td><input type=\"radio\" name=\"s4_" . $name . "\" value=\"0\" onclick=\"autoCheck2(this, ac_$auto)\" title=\"0\"><input type=\"radio\" name=\"s4_" . $name . "\" value=\"255\" onclick=\"autoCheck2(this, ac_$auto)\" title=\"[NOT TRANSLATED]\" checked></td><td>&nbsp;</td>";
        if (($stage == $max_stage) && ($stage != 0))
            echo "<td><span class=\"checked\">&#10004;</span></td>";
        else
            echo "<td><input type=\"checkbox\" id=\"ac_$auto\" name=\"c4_" . $name . "\"></td>";
        echo "<td>[NOT TRANSLATED]</td>";
        $combo = array();
        foreach ($to_none[$stage] as $ref)
            $combo[] = $ref['ayat'] * 100 + $ref['wh'];
        $combo = implode(',', $combo);
        if (!$stage)
            array_push($super_combo, $combo);
        echo "<td><a href=\"$combo_page?ids=$combo&amp;s=$strong\">$count</a></td><td>";
        $auto++;
        $i = 1;
        foreach ($to_none[$stage] as $ref) {
            echo "<a href=\"$detail_page?id={$ref['ayat']}&amp;s=$strong\" title=\"{$books[$ref['book']]['abbr']} {$ref['chapter']}:{$ref['verse']}\">&middot;</a>";
            if ($i % 10 == 0)
                echo "\n";
            $i++;
        }
        echo "</td></tr>\n";
    }
    echo "</table>\n";
}
if ($super_combo && count($super_combo)) {
    $super_combo = implode(',', $super_combo);
    echo "<a href=\"$combo_page?ids=$super_combo&amp;s=$strong\">COMBO EDITOR</a>\n";
}
if ($unchecked)
    mysql_query("UPDATE $status_table SET checked = 0 WHERE id = $strong;");
else
    mysql_query("UPDATE $status_table SET checked = 1 WHERE id = $strong;");
mysql_query("UPDATE $status_table SET refreshed = 1 WHERE id = $strong;");
mysql_query("UNLOCK TABLES;");

echo "<table>\n";
if ($total) {
    $leftout = array();
    $result = mysql_query("SELECT wh_word.ayat, wh_word.pos FROM wh_word LEFT JOIN $linkage_table ON wh_word.ayat = $linkage_table.ayat AND wh_word.pos = $linkage_table.wh WHERE wh_word.strong = $strong AND $linkage_table.ayat IS NULL AND $linkage_table.wh IS NULL;");
    while ($row = mysql_fetch_assoc($result)) {
        $leftout[$row['ayat'] * 100 + $row['pos']] = $row;
    }
    if (count($leftout)) {
        $count = count($leftout);
        $total -= $count;
        ksort($leftout);
        $name = implode('_', array_keys($leftout));
        echo "<tr valign=\"top\"><td class=\"del\">&nbsp;</td><td>&nbsp;</td><td><input type=\"checkbox\" name=\"c5_" . $name . "\"></td><td>[NOT LISTED]</td><td>$count</td><td>";
        $i = 1;
        foreach ($leftout as $ref) {
            echo "<a href=\"$detail_page?id={$ref['ayat']}&amp;s=$strong\" title=\"{$books[$ref['book']]['abbr']} {$ref['chapter']}:{$ref['verse']}\">&middot;</a>";
            if ($i % 10 == 0)
                echo "\n";
            $i++;
        }
        echo "</td></tr>\n";
    }
}
if ($total) {
    echo "<tr valign=\"top\"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>[MISSING]</td><td>$total</td><td>&nbsp;</td></tr>\n";
}
echo "</table>\n";
echo "<br /><input type=\"submit\" value=\"Submit\">\n";
if ($next_strong < 8675)
    echo "<a href=\"?s=$next_strong\">Next # ($next_strong)</a><br>\n";
if ($prev_strong > 0)
    echo "<a href=\"?s=$prev_strong\">Prev # ($prev_strong)</a>\n";
echo "</form>\n";

echo "</body>\n";
echo "</html>\n";

?>
