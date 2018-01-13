<?php

require('config.php');

include('../header_old.php');

echo "<script type=\"text/javascript\" language=\"javascript\" src=\"wz_jsgraphics.js\"></script>\n";
echo "<div class=\"interlinear\">";
echo "<div id=\"canvas\" style=\"position:absolute;top:0px;left:0px;height:1px;width:1px;\"></div>\n";

$av_text = htmlentities($row_bible['av']);
$nasb_text = htmlentities($row_bible['nasb']);
$nasb_strong_text = htmlentities($row_bible['nasb_strong']);
echo "<div id=\"av\" class=\"dd\"><div class=\"av\"><b>KJV : </b>$av_text<hr /><b>NASB : </b>$nasb_text<hr /><b>NASB# : </b>$nasb_strong_text</div></div>\n";

$result = mysql_query("SELECT * FROM $target_table WHERE id = $id;");
$ncount = mysql_num_rows($result);


while ($row = mysql_fetch_assoc($result)) {
    echo "<div id=\"n{$row['pos']}\" class=\"dd\" onclick=\"clickWord(this, 'l', {$row['pos']})\" ondblclick=\"dblClickWord(this, 'l', {$row['pos']})\">{$row['word']}</div>\n";
}

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
echo "<script type=\"text/javascript\">\n";
foreach ($local as $key => $value) {
    $v = str_replace("'", "\'", implode('<br />', $value));
    echo "tip_strong$key = '<b>KJV here</b>: $v';";
}
echo "</script>\n";
$result = mysql_query("SELECT * FROM wh_word LEFT JOIN greek ON wh_word.strong = greek.id LEFT JOIN nasb_greek ON wh_word.strong = nasb_greek.id WHERE ayat = $id GROUP BY pos;");
$hcount = mysql_num_rows($result);

while ($row = mysql_fetch_assoc($result)) {
    $usage = str_replace("'", "\'", "<b>KJV: {$row['count']}</b><br />{$row['av']}<br /><b>NASB:</b><br />{$row['gloss']}");
    $definition = str_replace(array("\r\n", "'"), array('<br />', "\'"), "<b>NASB:</b><br />{$row['def']}<br /><b>KJV:  {$row['count']}</b><br />{$row['definition']}");
    echo "<script type=\"text/javascript\">\n";
    echo "tip_usage{$row['pos']} = '$usage';";
    echo "tip_def{$row['pos']} = '$definition';";
    echo "</script>\n";
    if (array_key_exists($row['strong'], $local))
        echo "<div id=\"h{$row['pos']}\" class=\"dd\" onclick=\"clickWord(this, 'r', {$row['pos']})\" ondblclick=\"dblClickWord(this, 'r', {$row['pos']})\" onmouseover=\"return escape(tip_strong{$row['strong']} + '<br />' + tip_usage{$row['pos']})\">&lt;{$row['strong']}&gt; <span class=\"g\" onmouseover=\"return escape(tip_usage{$row['pos']})\">{$row['kata']}</span></div>\n";
    else
        echo "<div id=\"h{$row['pos']}\" class=\"dd\" onclick=\"clickWord(this, 'r', {$row['pos']})\" ondblclick=\"dblClickWord(this, 'r', {$row['pos']})\" onmouseover=\"return escape('<b>KJV here: -</b><br />' + tip_usage{$row['pos']})\">&lt;{$row['strong']}&gt; <span class=\"g\" onmouseover=\"return escape(tip_usage{$row['pos']})\">{$row['kata']}</span></div>\n";
    if (preg_match_all("|(.+?\s+\d+)(?:,\s+)?|", $row['av'], $match)) {
        $av = $match[1][0];
        if ($match[1][1])
            $av .= ", {$match[1][1]}";
    }
    else
        $av = implode(', ', array_slice(explode(', ', $row['av']), 0, 3));
    echo "<div id=\"av{$row['pos']}\" class=\"dd\" onmouseover=\"return escape(tip_def{$row['pos']})\">$av <b>[{$row['kind']}; {$row['count']}]</b></div>\n";
}

$i = 0;
$linkage = array();
///edited : 2 rows below///
$n_null = ($h_null>0) ? array_fill(1, $ncount, true) : array();
$h_null = ($h_null>0) ? array_fill(1, $hcount, true) : array();
$result = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $id;");
while ($row = mysql_fetch_array($result))
    if (($row['net'] != 255) and ($row['wh'] != 255)) {
        $linkage[$i] = array($row['net'], $row['wh'], $row['tipe'], $row['stage']);
        $n_null[$row['net']] = false;
        $h_null[$row['wh']] = false;
        $i++;
    }

echo "<script type=\"text/javascript\">\n";
echo "<!--\n";

$dd = array();
for ($i = 1; $i <= $ncount; $i++)
    $dd[] = "\"n$i\"+NO_DRAG";
for ($i = 1; $i <= $hcount; $i++)
    $dd[] = "\"h$i\"+NO_DRAG";
for ($i = 1; $i <= $hcount; $i++)
    $dd[] = "\"av$i\"+NO_DRAG";
    
/// tambahan (prevent bug for empty greek/heb or indo) ///
if($ncount==0)
	$dd[] = "\"n1\"+NO_DRAG";
if($hcount==0)
{
	$dd[] = "\"h1\"+NO_DRAG";
	$dd[] = "\"av1\"+NO_DRAG";
}

echo "SET_DHTML(\"av\"+CURSOR_HAND, " . implode(', ', $dd) . ");";

echo "nmax = 0;";
echo "for (i = 1; i <= $ncount; i++) {";
echo "  npos = dd.elements[\"n\"+i].w;";
echo "  if (nmax < npos)";
echo "    nmax = npos;";
echo "}";

echo "hmax = 0;";
echo "for (i = 1; i <= $hcount; i++) {";
echo "  hpos = dd.elements[\"h\"+i].w;";
echo "  if (hmax < hpos)";
echo "    hmax = hpos;";
echo "}";

echo "avmax = 0;";
echo "for (i = 1; i <= $hcount; i++) {";
echo "  avpos = dd.elements[\"av\"+i].w;";
echo "  if (avmax < avpos)";
echo "    avmax = avpos;";
echo "}";

echo "diff = dd.elements[\"n1\"].h*$ncount-dd.elements[\"h1\"].h*$hcount;";
echo "if (diff > 0) {";
echo "  linc = dd.elements[\"n1\"].h;";
echo "  rinc = dd.elements[\"h1\"].h+diff/($hcount-1);";
echo "}";
echo "else {";
echo "  linc = dd.elements[\"n1\"].h-diff/($ncount-1);";
echo "  rinc = dd.elements[\"h1\"].h;";
echo "}";

echo "x1 = 20+nmax;";
echo "x2 = x1+100;";
echo "x3 = x2+hmax+10;";
echo "x4 = x3+avmax+10;";
echo "w = dd.getWndW()-x4-20;";
echo "if (w < 400) {";
echo "  w = 400;";
echo "  x4 = dd.getWndW()-w-20;";
echo "}";
echo "y = 50;";
echo "y1 = dd.elements[\"n1\"].h/2+y;";
echo "y2 = dd.elements[\"h1\"].h/2+y;";

echo "for (i = 1; i <= $ncount; i++) {";
echo "  dd.elements[\"n\"+i].moveTo(x1-dd.elements[\"n\"+i].w, (i-1)*linc+y);";
echo "}";

echo "for (i = 1; i <= $hcount; i++) {";
echo "  dd.elements[\"h\"+i].moveTo(x2, (i-1)*rinc+y);";
echo "  dd.elements[\"av\"+i].moveTo(x3, (i-1)*rinc+y);";
echo "}";

/// edited : posisi bottom nav menyesuaikan kata2 yang terbanyak (kanan atau kiri) //
if($hcount>=$ncount)
	$html.= "document.getElementById(\"bottom-nav\").style.top = (y+30)+($hcount*rinc)+\"px\";";
else
	$html.= "document.getElementById(\"bottom-nav\").style.top = (y+30)+($ncount*linc)+\"px\";";

echo "dd.elements.av.hide();";
echo "dd.elements.av.moveTo(x4, y);";
echo "dd.elements.av.resizeTo(w, dd.elements.av.h);";

echo "linkage = new Array();";
echo "function initLinkage() {";
foreach ($linkage as $key => $value)
    echo "  linkage[$key] = new Array($value[0], $value[1], $value[2], " . ($value[3]+1) . ");";
echo "}";

echo "warna = new Array();";
foreach ($warna as $key => $value)
    echo "warna[$key] = '$value';";

echo "jg = new jsGraphics('canvas');";
echo "initLinkage();";
echo "displayLinkage();";

if ($checked)
    echo "checked = true;";
else
    echo "checked = false;";

echo "//-->\n";
echo "</script>\n";

echo "<script type=\"text/javascript\" language=\"javascript\" src=\"../include/wz_tooltip_old.js\"></script>\n";
echo "</div>\n";
echo "</body>\n";
echo "</html>\n";

?>
