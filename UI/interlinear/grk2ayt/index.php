<?php

require('config.php');

$types = array('all' => 'All', 'name' => 'Names', 'noun' => 'Nouns', 'verb' => 'Verbs', 'adj' => 'Adjectives', 'adv' => 'Adverbs', 'conj' => 'Conjunctions', 'prep' => 'Prepositions', 'part' => 'Particles', 'pron' => 'Pronouns', 'rare' => 'Rare', 'semirare' => 'Semirare', 'rest' => 'Rest');

$type = $_GET['type'];
if (!$type)
    $type = 'all';
$show = $_GET['show'];
if (!$show)
    $show = 'all';

$link = mysql_connect($host, $name, $pass);
mysql_selectdb($db);

$founds = array();
$result = mysql_query("SELECT * FROM $found_table ORDER BY count DESC;");
while ($row = mysql_fetch_assoc($result)) {
    if (array_key_exists($row['strong'], $founds))
        $founds[$row['strong']][] = "{$row['phrase']} ({$row['count']})";
    else
        $founds[$row['strong']] = array("{$row['phrase']} ({$row['count']})");
}

echo "<html>\n";
echo "<head>\n";
echo "<title>Index {$types[$type]}</title>\n";
echo "<link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\" />\n";
echo "</head>\n";
echo "<body>\n";
$menu = array();
$menu2 = array();
$menu3 = array();
foreach ($types as $key => $value) {
    if (($key == $type) && ($show == 'all'))
        $menu[] = "<b>$value</b>";
    else
        $menu[] = "<a href=\"?type=$key\">$value</a>";
    if (($key == $type) && ($show == 'unchecked'))
        $menu2[] = "<b>$value</b>";
    else
        $menu2[] = "<a href=\"?type=$key&amp;show=unchecked\">$value</a>";
    if (($key == $type) && ($show == 'checked'))
        $menu3[] = "<b>$value</b>";
    else
        $menu3[] = "<a href=\"?type=$key&amp;show=checked\">$value</a>";
}
echo "<table><tr><td><b>Index {$types[$type]}</b></td><td>" . implode(' | ', $menu) . "</td></tr><tr><td><b>Unchecked:</b></td><td>" . implode(' | ', $menu2) . "</td></tr></tr><tr><td><b>Checked:</b></td><td>" . implode(' | ', $menu3) . "</td></tr></table>\n";

switch ($type) {
    case 'name':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_name LEFT JOIN $status_table ON ($status_table.id = strong_name.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_name LEFT JOIN greek ON (greek.id = strong_name.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'noun':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_noun LEFT JOIN $status_table ON ($status_table.id = strong_noun.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_noun LEFT JOIN greek ON (greek.id = strong_noun.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'verb':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_verb LEFT JOIN $status_table ON ($status_table.id = strong_verb.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_verb LEFT JOIN greek ON (greek.id = strong_verb.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'adj':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_adj LEFT JOIN $status_table ON ($status_table.id = strong_adj.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_adj LEFT JOIN greek ON (greek.id = strong_adj.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'adv':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_adv LEFT JOIN $status_table ON ($status_table.id = strong_adv.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_adv LEFT JOIN greek ON (greek.id = strong_adv.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'conj':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_conj LEFT JOIN $status_table ON ($status_table.id = strong_conj.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_conj LEFT JOIN greek ON (greek.id = strong_conj.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'prep':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_prep LEFT JOIN $status_table ON ($status_table.id = strong_prep.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_prep LEFT JOIN greek ON (greek.id = strong_prep.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'part':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_part LEFT JOIN $status_table ON ($status_table.id = strong_part.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_part LEFT JOIN greek ON (greek.id = strong_part.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'pron':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_pron LEFT JOIN $status_table ON ($status_table.id = strong_pron.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_pron LEFT JOIN greek ON (greek.id = strong_pron.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'rare':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_rare LEFT JOIN $status_table ON ($status_table.id = strong_rare.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_rare LEFT JOIN greek ON (greek.id = strong_rare.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'semirare':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_semirare LEFT JOIN $status_table ON ($status_table.id = strong_semirare.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_semirare LEFT JOIN greek ON (greek.id = strong_semirare.strong) LEFT JOIN $status_table USING (id);");
        break;
    case 'rest':
        $result0 = mysql_query("SELECT count(*) AS count FROM strong_rest LEFT JOIN $status_table ON ($status_table.id = strong_rest.strong) WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM strong_rest LEFT JOIN greek ON (greek.id = strong_rest.strong) LEFT JOIN $status_table USING (id);");
        break;
    default:
        $result0 = mysql_query("SELECT count(*) AS count FROM $status_table WHERE refreshed = 0;");
        $result = mysql_query("SELECT greek.*, refreshed FROM greek LEFT JOIN $status_table USING (id) WHERE greek.id BETWEEN 1 AND 5624;");
        break;
}

$count = mysql_num_rows($result);
echo "<b>Total: $count items</b>\n";

$row0 = mysql_fetch_assoc($result0);
$update = $row0['count'];
if ($update)
    echo "<a href=\"$update_all_proc_page?type=$type&amp;show=$show\" class=\"update\">[update all $update new changes]</a>\n";

echo "<br />\n";
$start = $_GET['start'];
if (($start >= $count) || ($start < 0))
    $start = 0;
$showcount = $_GET['count'];
if (($showcount > $count) || ($showcount < 1)) {
    if ($count < $index_count)
        $showcount = $count;
    else
        $showcount = $index_count;
}
if ($showcount > $count - $start)
    $start = $count - $showcount;
if ($start > 0) {
    $prev = $start - $showcount;
    if ($prev < 0)
        $prev = 0;
    echo "<a href=\"?type=$type&amp;show=$show&amp;start=$prev&amp;count=$showcount\">Previous $showcount</a>\n";
}
echo "<b>Showing item " . ($start + 1) . " - " . ($start + $showcount) . "</b>\n";
if ($start < $count - $showcount) {
    $next = $start + $showcount;
    if ($next >= $count)
        $next = $count - $showcount;
    if ($next < 0)
        $next = 0;
    echo "<a href=\"?type=$type&amp;show=$show&amp;start=$next&amp;count=$showcount\">Next $showcount</a>\n";
}

echo "<table>\n";
mysql_data_seek($result, $start);
for ($sc = 0; $sc < $showcount; $sc++) {
    $row = mysql_fetch_assoc($result);
    $result2 = mysql_query("SELECT * FROM $linkage_table WHERE strong = {$row['id']} AND stage = 0 GROUP  BY ayat, wh;");
    $count = mysql_num_rows($result2);
    if ($count && (($show == 'all') || ($show == 'unchecked'))) {
        $word = preg_replace('/\\\\~(.+?)\\\\~/', "<span class=\"g\">$1</span>", $row['word']);
        echo "<tr valign=\"top\"><td>{$row['id']}</td><td align=\"right\">={$row['count']}</td><td><a href=\"$strong_page?s={$row['id']}\" name=\"s{$row['id']}\">$word</a>";
        echo " <span class=\"left\">$count words left</span>";
        if (!$row['refreshed'])
            echo " <a href=\"$update_proc_page?s={$row['id']}&amp;type=$type&amp;show=$show\" class=\"update\">[update]</a>";
        echo "</td>";
        if (array_key_exists($row['id'], $founds))
            echo "<td>" . implode(', ', $founds[$row['id']]) . "</td>";
        else
            echo "<td>&nbsp;</td>";
        echo "<td>{$row['av']}</td></tr>\n";
    }
    elseif (!$count && (($show == 'all') || ($show == 'checked'))) {
        $word = preg_replace('/\\\\~(.+?)\\\\~/', "<span class=\"g\">$1</span>", $row['word']);
        echo "<tr valign=\"top\"><td>{$row['id']}</td><td align=\"right\">={$row['count']}</td><td><a href=\"$strong_page?s={$row['id']}\" name=\"s{$row['id']}\">$word</a>";
        echo " <span class=\"checked\">&#10004;</span>";
        if (!$row['refreshed'])
            echo " <a href=\"$update_proc_page?s={$row['id']}&amp;type=$type&amp;show=$show\" class=\"update\">[update]</a>";
        echo "</td>";
        if (array_key_exists($row['id'], $founds))
            echo "<td>" . implode(', ', $founds[$row['id']]) . "</td>";
        else
            echo "<td>&nbsp;</td>";
        echo "<td>{$row['av']}</td></tr>\n";
    }
}
echo "</table>\n";

echo "</body>\n";
echo "</html>\n";

?>
