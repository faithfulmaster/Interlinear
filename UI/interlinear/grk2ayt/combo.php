<?php

require('config.php');

$ids = $_GET['ids'];
$id_array = explode(',', $ids);

$strong = $_GET['s'];

$link = mysql_connect($host, $name, $pass);
mysql_selectdb($db);

echo "<html>\n";
echo "<head>\n";
echo "<title>Combo Editor</title>\n";
echo "<link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\" />\n";
echo "<script type=\"text/javascript\" language=\"javascript\" src=\"scripts.js\"></script>\n";
echo "</head>\n";
echo "<body>\n";

if ($strong)
    echo "<a href=\"$strong_page?s=$strong\">Back to #$strong</a>\n";
echo "<form action=\"$combo_proc_page\" method=\"post\">\n";
echo "<input type=\"submit\" value=\"Submit\"><br />\n";
echo "<input type=\"hidden\" name=\"ids\" value=\"$ids\">\n";
echo "<input type=\"hidden\" name=\"strong\" value=\"$strong\">\n";
$autoinc = 1;
foreach ($id_array as $id) {
    $ayat = (int)($id / 100);
    $wh = $id % 100;
    $result = mysql_query("SELECT book, chapter, verse, av_st.text as av, nasb_strong.text as nasb_strong, nasb.text as nasb FROM verses LEFT JOIN av_st USING (id) LEFT JOIN nasb_strong USING (id) LEFT JOIN nasb USING (id) WHERE verses.id=$ayat;");
    $row0 = mysql_fetch_array($result);
    $links = array();
    $result = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $ayat AND wh = $wh ORDER BY net;");
    while ($row = mysql_fetch_assoc($result)) {
        $links[$row['net']] = $row;
    }
    $result = mysql_query("SELECT * FROM $target_table WHERE id = $ayat;");
    echo "<big><b><a href=\"$detail_page?id=$ayat&amp;s=$strong\">{$books[$row0['book']]['name']} {$row0['chapter']}:{$row0['verse']}</a></b></big>\n";
    echo "<table><tr><td>\n";
    while ($row = mysql_fetch_assoc($result)) {
        if (array_key_exists($row['pos'], $links)) {
            echo "<input type=\"hidden\" id=\"ai_$autoinc\" name=\"c{$ayat}_{$wh}_{$row['pos']}\" value=\"d{$links[$row['pos']]['tipe']}\">\n";
            echo "<span class=\"d{$links[$row['pos']]['tipe']}\" onclick=\"rotateType(this, ai_$autoinc);\" ondblclick=\"changeType(this, ai_$autoinc);\">{$row['word']}</span>\n";
        }
        else {
            echo "<input type=\"hidden\" id=\"ai_$autoinc\" name=\"c{$ayat}_{$wh}_{$row['pos']}\" value=\"d0\">\n";
            echo "<span class=\"d0\" onclick=\"rotateType(this, ai_$autoinc);\" ondblclick=\"changeType(this, ai_$autoinc);\">{$row['word']}</span>\n";
        }
        $autoinc++;
    }
    echo "</td></tr>\n";
    $result = mysql_query("SELECT * FROM wh_word LEFT JOIN greek ON wh_word.strong = greek.id LEFT JOIN nasb_greek ON wh_word.strong = nasb_greek.id WHERE ayat = $ayat GROUP BY pos;");
    echo "<tr><td>\n";
    while ($row = mysql_fetch_assoc($result)) {
        $kata = str_replace(' ', '&nbsp;', $row['kata']);
        $definition = str_replace(array("\r\n", "'"), array('<br />', "\'"), "<b>NASB:</b><br />{$row['def']}<br /><b>KJV:  {$row['count']}</b><br />{$row['definition']}");
        echo "<script type=\"text/javascript\">\n";
        echo "tip_def{$ayat}_{$row['pos']} = '$definition';";
        echo "</script>\n";
        if ($row['pos'] == $wh)
            echo "<span class=\"on\" onmouseover=\"return escape(tip_def{$ayat}_{$row['pos']})\">&lt;{$row['strong']}&gt;</span>\n";
        elseif ($row['strong'] == $strong)
            echo "<span class=\"semi\" onmouseover=\"return escape(tip_def{$ayat}_{$row['pos']})\">&lt;{$row['strong']}&gt;</span>\n";
        else
            echo "<span class=\"off\" onmouseover=\"return escape(tip_def{$ayat}_{$row['pos']})\">&lt;{$row['strong']}&gt;</span>\n";
    }
    echo "</td></tr>\n";

    $result = mysql_query("SELECT * FROM av_st WHERE id = $ayat;");
    $row = mysql_fetch_assoc($result);
    $av = preg_replace("/(&lt;$strong&gt;)/", "<span class=\"ref\">$1</span>", htmlentities($row['text']));
    echo "<tr><td><b>AV</b>: $av</td></tr></table>\n";
}
echo "<input type=\"submit\" value=\"Submit\">\n";
echo "</form>\n";

echo "<script type=\"text/javascript\">\n";
echo "<!--\n";
echo "next_class = new Array();";
$prev_type = 0;
foreach (array_keys($types) as $type) {
    if ($prev_type)
        echo "next_class['d$prev_type'] = 'd$type';";
    else
        $first_type = $type;
    $prev_type = $type;
}
echo "next_class['d$prev_type'] = 'd$first_type';";
echo "//-->\n";
echo "</script>\n";
echo "<script type=\"text/javascript\" language=\"javascript\" src=\"wz_tooltip.js\"></script>\n";
echo "</body>\n";
echo "</html>\n";

?>
