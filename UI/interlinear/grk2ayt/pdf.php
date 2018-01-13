<?php
	//require('config.php');
	include('MPDF56/mpdf.php');
	
//	$html = '';
///*
//$id = $_GET['id'];
//if (!$id)
//    $id = 1;
//$strong = $_GET['s'];
//if (!$strong)
//    $strong = 0;
//
//$link = mysql_connect($host, $name, $pass);
//mysql_selectdb($db);
//
//$prev_id = $id - 1;
//$next_id = $id + 1;
//
//$result = mysql_query("SELECT book, chapter, verse, av_st.text as av, nasb_strong.text as nasb_strong, nasb.text as nasb FROM verses LEFT JOIN av_st USING (id) LEFT JOIN nasb_strong USING (id) LEFT JOIN nasb USING (id) WHERE verses.id=$id;");
//$row = mysql_fetch_array($result);
//*/
//
//$html.= "<canvas id=\"myCanvas\" style=\"position:absolute;\"></canvas>\n";
///*$av_text = htmlentities($row['av']);
//$nasb_text = htmlentities($row['nasb']);
//$nasb_strong_text = htmlentities($row['nasb_strong']);
////$html.= "<div id=\"av\" class=\"dd\"><div class=\"av\"><b>KJV : </b>$av_text<hr /><b>NASB : </b>$nasb_text<hr /><b>NASB# : </b>$nasb_strong_text</div></div>\n";
//
//$result = mysql_query("SELECT * FROM $target_table WHERE id = $id;");
//$ncount = mysql_num_rows($result);
//while ($row = mysql_fetch_assoc($result)) {
//    $html.= "<div id=\"n{$row['pos']}\" class=\"dd\" onclick=\"clickWord(this, 'l', {$row['pos']})\" ondblclick=\"dblClickWord(this, 'l', {$row['pos']})\">{$row['word']}</div>\n";
//}
//$local = array();
//$result = mysql_query("SELECT * FROM av_phrase WHERE id = $id;");
//while ($row = mysql_fetch_assoc($result)) {
//    $local_strong = explode(' ', $row['strong']);
//    foreach ($local_strong as $s) {
//        $s = (int)$s;
//        if (array_key_exists($s, $local))
//            $local[$s][] = $row['original'];
//        else
//            $local[$s] = array($row['original']);
//    }
//}
//$html.= "<script type=\"text/javascript\">\n";
//foreach ($local as $key => $value) {
//    $v = str_replace("'", "\'", implode('<br />', $value));
//    $html.= "tip_strong$key = '<b>KJV here</b>: $v';";
//}
//$html.= "</script>\n";
//$result = mysql_query("SELECT * FROM wh_word LEFT JOIN greek ON wh_word.strong = greek.id LEFT JOIN nasb_greek ON wh_word.strong = nasb_greek.id WHERE ayat = $id GROUP BY pos;");
//$hcount = mysql_num_rows($result);
//while ($row = mysql_fetch_assoc($result)) {
//    $usage = str_replace("'", "\'", "<b>KJV: {$row['count']}</b><br />{$row['av']}<br /><b>NASB:</b><br />{$row['gloss']}");
//    $definition = str_replace(array("\r\n", "'"), array('<br />', "\'"), "<b>NASB:</b><br />{$row['def']}<br /><b>KJV:  {$row['count']}</b><br />{$row['definition']}");
//    $html.= "<script type=\"text/javascript\">\n";
//    $html.= "tip_usage{$row['pos']} = '$usage';";
//    $html.= "tip_def{$row['pos']} = '$definition';";
//    $html.= "</script>\n";
//    if (array_key_exists($row['strong'], $local))
//        $html.= "<div id=\"h{$row['pos']}\" class=\"dd\" onclick=\"clickWord(this, 'r', {$row['pos']})\" ondblclick=\"dblClickWord(this, 'r', {$row['pos']})\" onmouseover=\"return escape(tip_strong{$row['strong']} + '<br />' + tip_usage{$row['pos']})\">&lt;{$row['strong']}&gt; <span class=\"g\" onmouseover=\"return escape(tip_usage{$row['pos']})\">{$row['kata']}</span></div>\n";
//    else
//        $html.= "<div id=\"h{$row['pos']}\" class=\"dd\" onclick=\"clickWord(this, 'r', {$row['pos']})\" ondblclick=\"dblClickWord(this, 'r', {$row['pos']})\" onmouseover=\"return escape('<b>KJV here: -</b><br />' + tip_usage{$row['pos']})\">&lt;{$row['strong']}&gt; <span class=\"g\" onmouseover=\"return escape(tip_usage{$row['pos']})\">{$row['kata']}</span></div>\n";
//    if (preg_match_all("|(.+?\s+\d+)(?:,\s+)?|", $row['av'], $match)) {
//        $av = $match[1][0];
//        if ($match[1][1])
//            $av .= ", {$match[1][1]}";
//    }
//    else
//        $av = implode(', ', array_slice(explode(', ', $row['av']), 0, 3));
//    $html.= "<div id=\"av{$row['pos']}\" class=\"dd\" onmouseover=\"return escape(tip_def{$row['pos']})\">$av <b>[{$row['kind']}; {$row['count']}]</b></div>\n";
//}
//
//$i = 0;
//$linkage = array();
//$n_null = array_fill(1, $ncount, true);
//$h_null = array_fill(1, $hcount, true);
//$result = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $id;");
//$lcount = mysql_num_rows($result);
///*
//while ($row = mysql_fetch_array($result))
//{
//    if (($row['net'] != 255) and ($row['wh'] != 255)) {
//        $linkage[$i] = array($row['net'], $row['wh'], $row['tipe'], $row['stage']);
//        $n_null[$row['net']] = false;
//        $h_null[$row['wh']] = false;
//        $i++;
//    }
//}
//*/
//$html.= "<script type=\"text/javascript\">\n";
//$html.= "<!--\n";
//
//
//$html.= "var canvas = document.getElementById('myCanvas');";
//$html.= "var ctx = canvas.getContext(\"2d\");";
//$html.= "for(i=0;i<$lcount;i++){";
//$html.= "ctx.beginPath();";
//$html.= "ctx.lineWidth=2;";
////$html.= "ctx.strokeStyle=warna[linkage[i][2]];";
//$html.= "ctx.strokeStyle=\"#f00\";";
////$html.= "alert(linkage[i]);";
//
//
////$html.= "ctx.moveTo(0, (linkage[i][0]-1)*linc+y1);";
////$html.= "ctx.lineTo(100,(linkage[i][1]-1)*rinc+y2);";
//$html.= "ctx.moveTo(0, 10);";
//$html.= "ctx.lineTo(100,20);";
//$html.= "ctx.stroke();";
//$html.= "ctx.closePath();";
//$html.= "}";
//
//$html.= "var img = canvas.toDataURL(\"image/png\");";
//$html.= "document.write(\'<img src="\'+img+\'"/>\');";
//
//if ($checked)
//    $html.= "checked = true;";
//else
//    $html.= "checked = false;";
//
//$html.= "//-->\n";
//$html.= "</script>\n";

	include ($_GET['version'].'/detail.php');
	
	while($row = mysql_fetch_array($result_words))
		print_r($row);
	
//	$mpdf = new mPDF();
//	$mpdf->WriteHTML($html);
//	$mpdf->Output();
//	exit;
?>