<?php

require('config.php');

function format($string, $tipe, $stage) {
    if ($tipe != 0)
        $string = "<span class=\"t$tipe\">$string</span>";
    if ($stage != 1)
        $string = "<i>$string</i>";
    return $string;
}

$id = $_GET['id'];
if (!$id)
    $id = 1;
if ($id <= 23145)
    $part = 'h';
else
    $part = 'g';
$prev_id = $id - 1;
$next_id = $id + 1;

$link = mysql_connect('localhost', 'root', '');
mysql_selectdb('interlinear_fulldb');

$result = mysql_query("SELECT book, chapter, verse FROM verses WHERE id = $id;");
$row = mysql_fetch_array($result);

echo "<html>\n";
echo "<head>\n";
echo "<title>{$books[$row['book']]['name']} {$row['chapter']}:{$row['verse']}</title>\n";
echo "<link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\" />\n";
echo "</head>\n";
echo "<body>\n";
if ($prev_id >= 1)
    echo "<a href=\"?id=$prev_id\">Prev Verse</a>\n";
if ($part == 'h')
    $site = 'heb2tb';
else
    $site = 'grk2tb';
echo "<b><big><a href=\"http://daniel.sabda.ylsa/interlinear/$site/detail.php?id=$id\">{$books[$row['book']]['name']} {$row['chapter']}:{$row['verse']}</a></big></b>\n";
if ($next_id <= 31102)
    echo "<a href=\"?id=$next_id\">Next Verse</a>\n";

echo "<script type=\"text/javascript\">\n";
/*
$result = mysql_query("SELECT * FROM net_notes WHERE id = $id;");
if (mysql_num_rows($result)) {
    $pos = 0;
    $offset = 0;
    while ($row = mysql_fetch_assoc($result)) {
        if ($row['pos'] != $pos) {
            if ($pos)
                echo "';\n";
            else
                $offset = $row['pos'] - 1;
            $pos = $row['pos'] - $offset;
            echo "note$pos = '";
        }
        echo "<b>{$row['type']}</b> ";
        echo str_replace("'", "\'", $row['note']);
        echo "<br />";
    }
    echo "';\n";
}
*/
$local = array();
$result = mysql_query("SELECT * FROM av_phrase WHERE id = $id;");
while ($row = mysql_fetch_assoc($result)) {
    $local_strong = explode(' ', $row['strong']);
    foreach ($local_strong as $s) {
        $s = (int)$s;
        if (array_key_exists($s, $local))
            $local[$s][] = $row['original'];
        else
            $local[$s] = array($row['original']);
    }
}

if ($part == 'h')
    $result = mysql_query("SELECT pos, strong, hebrew.word AS root, nasb_hebrew.word AS trans, '' AS parsing, count, definition, def FROM bhsstr_word LEFT JOIN hebrew ON bhsstr_word.strong = hebrew.id LEFT JOIN nasb_hebrew ON bhsstr_word.strong = nasb_hebrew.id WHERE ayat = $id GROUP BY pos;");
else
    $result = mysql_query("SELECT pos, strong, greek.word AS root, nasb_greek.word AS trans, jenis1 AS parsing, count, definition, def FROM wh_word LEFT JOIN greek ON wh_word.strong = greek.id LEFT JOIN nasb_greek ON wh_word.strong = nasb_greek.id WHERE ayat = $id GROUP BY pos;");
while ($row = mysql_fetch_assoc($result)) {
    if (array_key_exists($row['strong'], $local))
        $loc = implode('<br />', $local[$row['strong']]);
    else
        $loc = '-';
    $root = str_replace("'", "\'", '<big><b>OLB:</b> ' . preg_replace(array('/\\\\\^(.+?)\\\\\^/', '/\\\\~(.+?)\\\\~/'), array("<span class=\"h\">$1</span>", "<span class=\"g\">$1</span>"), $row['root']) . '<br /><b>NASB: ' . $row['count'] . '</b> ' . $row['trans'] . '<br /><b>Parsing:</b> ' . $row['parsing'] . '<br /><b>KJV here:</b> ' . $loc . '</big>');
    echo "root{$row['pos']} = '$root';\n";
    $definition = str_replace(array("\r\n", "'"), array('<br />', "\'"), "<b>NASB:</b><br />{$row['def']}<br /><b>KJV: {$row['count']}</b><br />{$row['definition']}");
    echo "def{$row['pos']} = '$definition';\n";
    $low_max = $row['pos'];
}

echo "</script>\n";

$unit_list = array();
$empty_low = array();
$error_list = array();
if ($part == 'h')
    $result = mysql_query("SELECT net, bhs AS low, word, bhsstr_word.strong, bhsstr_word.kata, tipe, stage FROM giza_tb_linkage_clean LEFT JOIN giza_tb_pairmap USING (pair) LEFT JOIN pltb_word ON giza_tb_pairmap.ayat = pltb_word.id AND giza_tb_linkage_clean.net = pltb_word.pos LEFT JOIN bhsstr_word ON giza_tb_pairmap.ayat = bhsstr_word.ayat AND giza_tb_linkage_clean.bhs = bhsstr_word.pos WHERE giza_tb_pairmap.ayat = $id ORDER BY net, bhs;");
else
    $result = mysql_query("SELECT net, wh AS low, word, wh_word.strong, wh_word.kata, tipe, stage FROM giza_linkage_grk2tb LEFT JOIN pbtb_word ON giza_linkage_grk2tb.ayat = pbtb_word.id AND giza_linkage_grk2tb.net = pbtb_word.pos LEFT JOIN wh_word ON giza_linkage_grk2tb.ayat = wh_word.ayat AND giza_linkage_grk2tb.wh = wh_word.pos WHERE giza_linkage_grk2tb.ayat = $id ORDER BY net, wh;");
while ($row = mysql_fetch_assoc($result)) {
    $node = array('net' => $row['net'], 'low' => $row['low'], 'word' => $row['word'], 'strong' => $row['strong'], 'lower' => $row['kata'], 'tipe' => $row['tipe'], 'stage' => $row['stage']);
    $break = FALSE;
    foreach ($unit_list as $key => $list) {
        if ((in_array($row['net'], $list['net']) && ($row['net'] != 255) && ($row['net'] != 0)) || (in_array($row['low'], $list['low']) && ($row['low'] != 255) && ($row['low'] != 0))) {
            if (!$break) {
                $list['net'][] = $row['net'];
                $list['low'][] = $row['low'];
                $list['node'][] = $node;
                array_splice($unit_list, $key, 1, array($list));
                $break = TRUE;
            }
            else {
                $error_list[] = $row;
            }
        }
    }
    if (!$break) {
        if (($row['net'] != 255) && ($row['net'] != 0))
            $unit_list[] = array('net' => array($row['net']), 'low' => array($row['low']), 'node' => array($node));
        else
            if (($row['low'] != 255) && ($row['low'] != 0))
                $empty_low[$row['low']] = $node;
    }
}
ksort($empty_low);

if (count($error_list)) {
    echo "<p><b>Warning: The verse contains badly connected nodes (split links)</b></p>";
}

$net = 0;
$split_net = array();
$merged_word = array();
$merged_lower = array();
$merged_strong = array();
foreach($unit_list as $key => $list) {
    $word_list = array();
    $lower_list = array();
    $strong_list = array();
    $split_after = '';
    $bad_link = FALSE;
    foreach ($list['node'] as $node) {
        if (($node['low'] != 255) && ($node['low'] != 0)) {
            $low = $node['low'];
            for ($l = $low - 1; $l > 0; $l--) {
                if (array_key_exists($l, $empty_low))
                    $low = $l;
                else
                    break;
            }
            foreach ($empty_low as $low_key => $low_node) {
                if (($low_key < $node['low']) && ($low_key >= $low) && (!$low_node['merged'])) {
                    $merged_word[$low_node['net']] = '&nbsp;';
                    $merged_lower[$low_node['low']] = format("<span onmouseover=\"return escape(root{$low_node['low']})\">{$low_node['lower']}<sub><small>{$low_node['low']}</small></sub></span>", $low_node['tipe'], $low_node['stage']);
                    $merged_strong[$low_node['low']] = format("<a href=\"http://daniel.sabda.ylsa/interlinear/$site/strong.php?s={$low_node['strong']}\" onmouseover=\"return escape(def{$low_node['low']})\">&lt;{$low_node['strong']}&gt;</a>", $low_node['tipe'], $low_node['stage']);
                    $empty_low[$low_key]['merged'] = TRUE;
                }
            }
        }
        if ($node['net'] == $net + 1)
            $net++;
        if ($node['net'] == $net) {
//            $notes = preg_replace('/(\d+)/', "<sup><small><a href=\"#\" onmouseover=\"return escape(note$1)\">$1</a></small></sup>", $node['notes']);
            if (($node['low'] != 255) && ($node['low'] != 0)) {
                $word_list[$node['net']] = format("{$node['word']}$notes", $node['tipe'], $node['stage']);
                $lower_list[$node['low']] = format("<span onmouseover=\"return escape(root{$node['low']})\">{$node['lower']}<sub><small>{$node['low']}</small></sub></span>", $node['tipe'], $node['stage']);
                $strong_list[$node['low']] = format("<a href=\"http://daniel.sabda.ylsa/interlinear/$site/strong.php?s={$node['strong']}\" onmouseover=\"return escape(def{$node['low']})\">&lt;{$node['strong']}&gt;</a>", $node['tipe'], $node['stage']);
            }
            else {
                $merged_word[$node['net']] = format("{$node['word']}$notes", $node['tipe'], $node['stage']);
                $merged_lower[$node['low']] = "&nbsp;";
                $merged_strong[$node['low']] = "&nbsp;";
            }
        }
        elseif ($node['net'] > $net) {
            if (!array_key_exists($node['net'], $split_net))
                $split_net[$node['net']] = $key;
            $split_after = ' ==&gt;';
        }
        foreach ($error_list as $error) {
            if (($node['net'] == $error['net']) && ($node['low'] == $error['low']))
                $bad_link = TRUE;
        }
    }
    if (count($word_list)) {
        if (count($merged_word) || count($merged_lower)) {
            $word_out = implode(' ', $merged_word);
            if ($part == 'h') {
                $merged_lower = array_reverse($merged_lower);
                $merged_strong = array_reverse($merged_strong);
            }
            $lower_out = implode(' ', $merged_lower);
            $strong_out = implode(' ', $merged_strong);
            echo "<div class=\"fuzzy unit\">$word_out<hr /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
            $merged_word = array();
            $merged_lower = array();
            $merged_strong = array();
        }
        $word_out = implode(' ', $word_list);
        if ($part == 'h') {
            $lower_list = array_reverse($lower_list);
            $strong_list = array_reverse($strong_list);
        }
        $lower_out = implode(' ', $lower_list);
        $strong_out = implode(' ', $strong_list) . $split_after;
        if ($bad_link)
            echo "<div class=\"bad unit\">$word_out<hr /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
        else
            echo "<div class=\"unit\">$word_out<hr /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
    }
    while (array_key_exists($net + 1, $split_net)) {
        $net++;
        $word_list = array();
        $lower_list = array();
        $strong_list = array();
        $split_before = '';
        $split_after = '';
        $bad_link = FALSE;
        foreach ($unit_list[$split_net[$net]]['node'] as $node) {
            if (($node['low'] != 255) && ($node['low'] != 0)) {
                $low = $node['low'];
                for ($l = $low - 1; $l > 0; $l--) {
                    if (array_key_exists($l, $empty_low))
                        $low = $l;
                    else
                        break;
                }
                foreach ($empty_low as $low_key => $low_node) {
                    if (($low_key < $node['low']) && ($low_key >= $low) && (!$low_node['merged'])) {
                        $merged_word[$low_node['net']] = '&nbsp;';
                        $merged_lower[$low_node['low']] = format("<span onmouseover=\"return escape(root{$low_node['low']})\">{$low_node['lower']}<sub><small>{$low_node['low']}</small></sub></span>", $low_node['tipe'], $low_node['stage']);
                        $merged_strong[$low_node['low']] = format("<a href=\"http://daniel.sabda.ylsa/interlinear/$site/strong.php?s={$low_node['strong']}\" onmouseover=\"return escape(def{$low_node['low']})\">&lt;{$low_node['strong']}&gt;</a>", $low_node['tipe'], $low_node['stage']);
                        $empty_low[$low_key]['merged'] = TRUE;
                    }
                }
            }
            if ($node['net'] == $net + 1)
                $net++;
            if ($node['net'] == $net) {
//                $notes = preg_replace('/(\d+)/', "<sup><small><a href=\"#\" onmouseover=\"return escape(note$1)\">$1</a></small></sup>", $node['notes']);
                $split_before = "&lt;== ";
                if (($node['low'] != 255) && ($node['low'] != 0)) {
                    $word_list[$node['net']] = format("{$node['word']}$notes", $node['tipe'], $node['stage']);
                    $lower_list[$node['low']] = format("<span onmouseover=\"return escape(root{$node['low']})\">{$node['lower']}<sub><small>{$node['low']}</small></sub></span>", $node['tipe'], $node['stage']);
                    $strong_list[$node['low']] = format("<a href=\"http://daniel.sabda.ylsa/interlinear/$site/strong.php?s={$node['strong']}\" onmouseover=\"return escape(def{$node['low']})\">&lt;{$node['strong']}&gt;</a>", $node['tipe'], $node['stage']);
                }
                else {
                    $merged_word[$node['net']] = format("{$node['word']}$notes", $node['tipe'], $node['stage']);
                    $merged_lower[$node['low']] = "&nbsp;";
                    $merged_strong[$node['low']] = "&nbsp;";
                }
            }
            elseif ($node['net'] > $net) {
                if (!array_key_exists($node['net'], $split_net))
                    $split_net[$node['net']] = $key;
                $split_after = " ==&gt;";
            }
            foreach ($error_list as $error) {
                if (($node['net'] == $error['net']) && ($node['low'] == $error['low']))
                    $bad_link = TRUE;
            }
        }
        if (count($word_list)) {
            if (count($merged_word) || count($merged_lower)) {
                $word_out = implode(' ', $merged_word);
                if ($part == 'h') {
                    $merged_lower = array_reverse($merged_lower);
                    $merged_strong = array_reverse($merged_strong);
                }
                $lower_out = implode(' ', $merged_lower);
                $strong_out = implode(' ', $merged_strong);
                echo "<div class=\"fuzzy unit\">$word_out<hr /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
                $merged_word = array();
                $merged_lower = array();
                $merged_strong = array();
            }
            $word_out = implode(' ', $word_list);
            if ($part == 'h') {
                $lower_list = array_reverse($lower_list);
                $strong_list = array_reverse($strong_list);
            }
            $lower_out = implode(' ', $lower_list);
            $strong_out = $split_before . implode(' ', $strong_list) . $split_after;
            if ($bad_link)
                echo "<div class=\"bad unit\">$word_out<hr /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
            else
                echo "<div class=\"unit\">$word_out<hr /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
        }
    }
}
$low = $low_max;
for ($l = $low - 1; $l > 0; $l--) {
    if (array_key_exists($l, $empty_low))
        $low = $l;
    else
        break;
}
foreach ($empty_low as $low_key => $low_node) {
    if (($low_key >= $low) && (!$low_node['merged'])) {
        $merged_word[$low_node['net']] = '&nbsp;';
        $merged_lower[$low_node['low']] = format("<span onmouseover=\"return escape(root{$low_node['low']})\">{$low_node['lower']}<sub><small>{$low_node['low']}</small></sub></span>", $low_node['tipe'], $low_node['stage']);
        $merged_strong[$low_node['low']] = format("<a href=\"http://daniel.sabda.ylsa/interlinear/$site/strong.php?s={$low_node['strong']}\" onmouseover=\"return escape(def{$low_node['low']})\">&lt;{$low_node['strong']}&gt;</a>", $low_node['tipe'], $low_node['stage']);
        $empty_low[$low_key]['merged'] = TRUE;
    }
}
if (count($merged_word) || count($merged_lower)) {
    $word_out = implode(' ', $merged_word);
    if ($part == 'h') {
        $merged_lower = array_reverse($merged_lower);
        $merged_strong = array_reverse($merged_strong);
    }
    $lower_out = implode(' ', $merged_lower);
    $strong_out = implode(' ', $merged_strong);
    echo "<div class=\"fuzzy unit\">$word_out<hr /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
}

echo "<script type=\"text/javascript\" language=\"javascript\" src=\"wz_tooltip.js\"></script>\n";
echo "</body>\n";
echo "</html>\n";

// masih ada masalah, jika terjadi split, kata yang di belakang akan pindah ke depan, contoh ayat 53
// masalah di atas sudah dibereskan, tapi jika ada split, semua kata akan terpisah2 menjadi div masing2
// kedua masalah sudah beres

// nomor strong yang tidak tersambung juga belum bisa dipasangkan ke lokasinya
// yang ini juga sudah beres, tapi jika urutan bhs terbalik, strong akan tersambung ke urutan yang muncul lebih dulu

// wah, satu error lagi, jika ada lebih dari satu net diterjemahkan dari satu bhs, yang muncul hanya yg pertama
// variabel $net seharusnya tidak terlalu cepat diincrement... contoh 1015
// seharusnya masalah terakhir ini sudah beres

// untuk link yg salah sambung (dobel) seperti 7778, misalnya, belum ada solusi selain memperbaiki datanya.
// tapi error bisa dideteksi dan ditampilkan

?>
