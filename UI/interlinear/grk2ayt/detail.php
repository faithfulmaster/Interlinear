<?php

require('config.php');

include('../header.php');



$html = '';
//$html.= "<span class=\"abc\" style=\"margin-left:700px;\">Coba</span>";
$html.= "<div class=\"interlinear\">";
$html.= "<canvas id=\"myCanvas\"></canvas>\n";

$av_text = htmlentities($row_bible['av']);
$nasb_text = htmlentities($row_bible['nasb']);
$nasb_strong_text = htmlentities($row_bible['nasb_strong']);

$html.= "<div id=\"av\" class=\"dd\"><div class=\"av\"><b>KJV : </b>$av_text<hr /><b>NASB : </b>$nasb_text<hr /><b>NASB# : </b>$nasb_strong_text</div></div>\n";

$result = mysql_query("SELECT * FROM $target_table WHERE id = $id;");
$ncount = mysql_num_rows($result);
while ($row = mysql_fetch_assoc($result)) {
    $html.= "<div id=\"n{$row['pos']}\" class=\"dd\" onclick=\"clickWord(this, 'l', {$row['pos']})\" ondblclick=\"dblClickWord(this, 'l', {$row['pos']})\">{$row['word']}</div>\n";
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
$html.= "<script type=\"text/javascript\">\n";
foreach ($local as $key => $value) {
    $v = str_replace("'", "\'", implode('<br />', $value));
    $html.= "tip_strong$key = '<b>KJV here</b>: $v';";
}
$html.= "</script>\n";
$result_words = mysql_query("SELECT * FROM wh_word LEFT JOIN greek ON wh_word.strong = greek.id LEFT JOIN nasb_greek ON wh_word.strong = nasb_greek.id WHERE ayat = $id GROUP BY pos;");
$hcount = mysql_num_rows($result_words);

while ($row = mysql_fetch_assoc($result_words)) {
	
    $usage = str_replace("'", "\'", "<b>KJV: {$row['count']}</b><br />{$row['av']}<br /><b>NASB:</b><br />{$row['gloss']}");
    $definition = str_replace(array("\r\n", "'"), array('<br />', "\'"), "<b>NASB:</b><br />{$row['def']}<br /><b>KJV:  {$row['count']}</b><br />{$row['definition']}");
    $tip_link_header = str_replace("'","\'","<a class=lexlinkheader href={$alkitab_link}/strong.php?id={$row['strong']} target=_blank>{$row['strong']}</a>");
    $tip_link = str_replace("'","\'","<a class=lexlinkcontent href={$alkitab_link}/strong.php?id={$row['strong']} target=_blank>&gt;&gt; selengkapnya &gt;&gt;</a>");
    
    //echo $usage.'<br/><br/>'.$definition;die;
    $html.= "<script type=\"text/javascript\">\n";
    $html.= "tip_usage{$row['pos']} = '$usage';";
    $html.= "tip_def{$row['pos']} = '$definition';";
    $html.= "</script>\n";
    
    if (array_key_exists($row['strong'], $local))
        $html.= "<div id=\"h{$row['pos']}\" class=\"dd\" onclick=\"clickWord(this, 'r', {$row['pos']})\" ondblclick=\"dblClickWord(this, 'r', {$row['pos']})\" onmouseover=\"showTip(tip_strong{$row['strong']} + '<br />' + tip_usage{$row['pos']} + '<br/>{$tip_link}', '{$row['word']} ({$tip_link_header})')\" onmouseout=\"UnTip()\"><span class=\"s\" >&lt;{$row['strong']}&gt;</span> <span class=\"g\" onmouseover=\"return escape(tip_usage{$row['pos']})\">{$row['kata']}</span></div>\n";
    else
        $html.= "<div id=\"h{$row['pos']}\" class=\"dd\" onclick=\"clickWord(this, 'r', {$row['pos']})\" ondblclick=\"dblClickWord(this, 'r', {$row['pos']})\" onmouseover=\"showTip('<b>KJV here: -</b><br />' + tip_usage{$row['pos']} + '<br/>{$tip_link}', '{$row['word']} ({$tip_link_header})')\" onmouseout=\"UnTip()\"><span class=\"s\">&lt;{$row['strong']}&gt;</span> <span class=\"g\" onmouseover=\"return escape(tip_usage{$row['pos']})\">{$row['kata']}</span></div>\n";
    if (preg_match_all("|(.+?\s+\d+)(?:,\s+)?|", $row['av'], $match)) {
        $av = $match[1][0];
        if ($match[1][1])
            $av .= ", {$match[1][1]}";
    }
    else
        $av = implode(', ', array_slice(explode(', ', $row['av']), 0, 3));
    $html.= "<div id=\"av{$row['pos']}\" class=\"dd\" onmouseover=\"showTip(tip_def{$row['pos']} + '<br/>{$tip_link}', '{$row['word']} ({$tip_link_header})')\" onmouseout=\"UnTip()\">$av <b>[{$row['kind']}; {$row['count']}]</b></div>\n";
}

$i = 0;
$linkage = array();
///edited : 2 rows below///
$n_null = ($h_null>0) ? array_fill(1, $ncount, true) : array();
$h_null = ($h_null>0) ? array_fill(1, $hcount, true) : array();
$result = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $id;");
$lcount = mysql_num_rows($result);

while ($row = mysql_fetch_array($result))
{
    if (($row['net'] != 255) and ($row['wh'] != 255)) {
        $linkage[$i] = array($row['net'], $row['wh'], $row['tipe'], $row['stage']);
        $n_null[$row['net']] = false;
        $h_null[$row['wh']] = false;
        $i++;
    }
}

$html.= "<script type=\"text/javascript\">\n";
$html.= "<!--\n";

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

$html.= "SET_DHTML(\"av\"+CURSOR_HAND, " . implode(', ', $dd) . ");\n";

$html.= "nmax = 0;";
$html.= "for (i = 1; i <= $ncount; i++) {";
$html.= "  npos = dd.elements[\"n\"+i].w;";
$html.= "  if (nmax < npos)";
$html.= "    nmax = npos;";
$html.= "}\n";

$html.= "hmax = 0;";
$html.= "for (i = 1; i <= $hcount; i++) {";
$html.= "  hpos = dd.elements[\"h\"+i].w;";
$html.= "  if (hmax < hpos)";
$html.= "    hmax = hpos;";
$html.= "}\n";

$html.= "avmax = 0;";
$html.= "for (i = 1; i <= $hcount; i++) {";
$html.= "  avpos = dd.elements[\"av\"+i].w;";
$html.= "  if (avmax < avpos)";
$html.= "    avmax = avpos;";
$html.= "}\n";

$html.= "diff = dd.elements[\"n1\"].h*$ncount-dd.elements[\"h1\"].h*$hcount;";
$html.= "if (diff > 0) {";
$html.= "  linc = dd.elements[\"n1\"].h;";
$html.= "  rinc = dd.elements[\"h1\"].h+diff/($hcount-1);";
$html.= "}\n";
$html.= "else {";
$html.= "  linc = dd.elements[\"n1\"].h-diff/($ncount-1);";
$html.= "  rinc = dd.elements[\"h1\"].h;";
$html.= "}\n";

$html.= "x1 = 20+nmax;";
$html.= "x2 = x1+100;";
$html.= "x3 = x2+hmax+10;";
$html.= "x4 = x3+avmax+10;";
$html.= "w = dd.getWndW()-x4-20;";
$html.= "if (w < 400) {";
$html.= "  w = 400;";
$html.= "  x4 = dd.getWndW()-w-20;";
$html.= "}";
$html.= "ystatic = 10;";
$html.= "y = 50;";
$html.= "y1 = dd.elements[\"n1\"].h/2+y;";
$html.= "y2 = dd.elements[\"h1\"].h/2+y;";
$html.= "cw=97;";

$html.= "for (i = 1; i <= $ncount; i++) {";
$html.= "  dd.elements[\"n\"+i].moveTo(x1-dd.elements[\"n\"+i].w, (i-1)*linc+y);";
$html.= "}";

$html.= "for (i = 1; i <= $hcount; i++) {";
$html.= "  dd.elements[\"h\"+i].moveTo(x2, (i-1)*rinc+y);";
$html.= "  dd.elements[\"av\"+i].moveTo(x3, (i-1)*rinc+y);";
$html.= "}";

/// edited : posisi bottom nav menyesuaikan kata2 yang terbanyak (kanan atau kiri) //
if($hcount>=$ncount)
	$html.= "document.getElementById(\"bottom-nav\").style.top = (y+30)+($hcount*rinc)+\"px\";";
else
	$html.= "document.getElementById(\"bottom-nav\").style.top = (y+30)+($ncount*linc)+\"px\";";

$html.= "var canvas = document.getElementById('myCanvas');";

$html.= "canvas.setAttribute(\"width\",cw+\"px\");";
$html.= "canvas.setAttribute(\"height\",$hcount*rinc+\"px\");";
//$html.= "canvas.style.border = \"1px solid #aaa\";";
$html.= "canvas.style.top=(y+1)+\"px\";";
$html.= "canvas.style.left=(x1+2)+\"px\";";


$html.= "dd.elements.av.hide();";
$html.= "dd.elements.av.moveTo(x4, y);";
$html.= "dd.elements.av.resizeTo(w, dd.elements.av.h);";

$html.= "linkage = new Array();";
foreach ($linkage as $key => $value)
    $html.= "  linkage[$key] = new Array($value[0], $value[1], $value[2], " . ($value[3]+1) . ");";

$html.= "warna = new Array();";
foreach ($warna as $key => $value)
    $html.= "warna[$key] = '$value';";


$html.= "var ctx = canvas.getContext(\"2d\");";

$html.= "for(i=0;i<$lcount;i++){";
$html.= "ctx.beginPath();";
$html.= "ctx.lineWidth=2;";
$html.= "ctx.strokeStyle=warna[linkage[i][2]];";
//$html.= "ctx.strokeStyle=\"#f00\";";
//$html.= "alert(linkage[i]);";

$html.= "if ((linkage[i][0] != 0) && (linkage[i][1] != 0)) {";
$html.= "ctx.moveTo(0, ((linkage[i][0]-1)*linc+ystatic));";
$html.= "ctx.lineTo(cw, ((linkage[i][1]-1)*rinc+ystatic));";
$html.= "ctx.stroke();";
$html.= "ctx.closePath();";
$html.= "}";
$html.= "else if ((linkage[i][0] != 0) && (linkage[i][1] == 0)) {";
$html.= "y = (linkage[i][0]-1)*linc+ystatic;";
$html.= "ctx.moveTo(0, y);";
$html.= "ctx.lineTo(10, y);";
$html.= "ctx.stroke();";
$html.= "ctx.closePath();";
$html.= "ctx.beginPath();";
$html.= "ctx.arc(10,y,2,0,2*Math.PI);";      //jg.fillEllipse(x1+8, y-2, 5, 5);
$html.= "ctx.fillStyle = warna[linkage[i][2]];";
$html.= "ctx.fill();";
$html.= "ctx.stroke();";
$html.= "ctx.closePath();";
$html.= "}";
$html.= "else if ((linkage[i][0] == 0) && (linkage[i][1] != 0)) {";
$html.= "y = (linkage[i][1]-1)*rinc+ystatic;";
$html.= "ctx.moveTo(cw-10, y);";
$html.= "ctx.lineTo(cw, y);";
$html.= "ctx.stroke();";
$html.= "ctx.closePath();";
$html.= "ctx.beginPath();";
$html.= "ctx.arc(cw-10,y,2,0,2*Math.PI);";      //jg.fillEllipse(x2-12, y-2, 5, 5);
$html.= "ctx.fillStyle = warna[linkage[i][2]];";
$html.= "ctx.fill();";
$html.= "ctx.stroke();";
$html.= "ctx.closePath();";
$html.= "}";

//$html.= "ctx.moveTo(0, (linkage[i][0]-1)*linc+y1);";
//$html.= "ctx.lineTo(100,(linkage[i][1]-1)*rinc+y2);";
//$html.= "ctx.moveTo(0, 10);";
//$html.= "ctx.lineTo(100,20);";

$html.= "}\n";



if ($checked)
    $html.= "checked = true;";
else
    $html.= "checked = false;";


$html.= "//-->\n";
$html.= "</script>\n";

//$html.= "<input type=\"hidden\" id=\"canvas_datauri\" value=\"\" />\n";

//$html.= "document.getElementById(\"canvas_datauri\").value = img;";
//$html.= "document.getElementById(\"PDF1\").onclick = function (){ showPDF('".$id."','".$version."',img) };";
$html.= "</script>\n";

$html.= "</div>\n";
$html.= "<script type=\"text/javascript\" language=\"javascript\" src=\"../include/wz_tooltip.js\"></script>\n";

echo $html;

if($_GET['canvasuri']==true)
{
  echo "<form action=\"../pdf.php?id=$id\" id=\"form_datauri\" method=\"post\">";
  echo "<input type=\"hidden\" name=\"id\" value=\"$id\" />";
  echo "<input type=\"hidden\" name=\"canvasuri\" id=\"canvasuri\" value=\"\" />";
  //echo "<input type=\"submit\" value=\"adfadadfadfadfadfadfadfdddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddddd\" />";
  echo "</form>";
	
  echo "<script>\n";
	echo "var img = canvas.toDataURL(\"image/png\").replace(\" \",\"+\");\n";
  echo "document.getElementById(\"canvasuri\").value = img;";
  //echo "alert(document.getElementById(\"canvasuri\").value);";
  echo "document.getElementById(\"form_datauri\").submit();";
  //echo "window.location = '../pdf.php?id=$id&version=$version&img='+img;";
  echo "</script>"; 
}

echo "</body>\n";
echo "</html>\n";


?>
