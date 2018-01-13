<?php
	//require('config.php');
	include('../MPDF56/mpdf.php');
	include('../header_config.php');
	
	$output = getopt('o:b:v:');
	$bible='';
	$version='grk2ayt';
	$id = 23147;
	$id_akhir = 31102;
	
	if (array_key_exists('o', $output) && ($output['o'] != ''))
		    $outfile = fopen($output['o'], 'w');
		else
		    $outfile = NULL;

	if (array_key_exists('b', $output) && ($output['b'] != ''))
		    $bible = $output['b'];
  if (array_key_exists('v', $output) && ($output['v'] != ''))
		    $version = $output['v'];
		    
	$link = mysql_connect($host, $name, $pass);
	mysql_selectdb($db);
	
	//$version = $_GET['version'];
	
	if($version=="grk2ayt")
		$target_table = 'pbayt_word';
	elseif($version=="grk2tb")
		$target_table = 'pbtb_word';
	else if($version=="grk2tl")
		$target_table = 'pbtl_word';
	else if($version=="grk2net")
		$target_table = 'net_words';
	else if($version=="heb2tb")
		$target_table = 'pltb_word';
	else if($version=="heb2tl")
		$target_table = 'hebtl_word';
	else if($version=="grk2net")
		$target_table = 'net_words';
		
//$id = (isset($_GET['id'])) ? $_GET['id'] : 1;

$content = file_get_contents($location.$version.'/detail.php?id='.$id.'&canvasuri=true');
die;
  $bodypattern = ".*<body>";
  $bodyendpattern = "</body>.*";

  $noheader = eregi_replace($bodypattern, "", $file);

  $noheader = eregi_replace($bodyendpattern, "", $noheader);

  //echo $id ." :: ".$file;
//echo $file;

$img = str_replace(" ","+",$content);
$file = "../../../../_temp/".$id.".png";
file_put_contents($file,base64_decode(str_replace("data:image/png;base64,","",$img)));
//echo base64_decode(str_replace("data:image/png;base64,","",$img));
//echo json_encode($file);
//if (file_exists($file)) {
//    header('Content-Description: File Transfer');
//    header('Content-Type: image/png');
//    header('Content-Disposition: attachment; filename='.basename($file));
//    header('Content-Transfer-Encoding: binary');
//    header('Expires: 0');
//    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//    header('Pragma: public');
//    header('Content-Length: ' . filesize($file));
//    ob_clean();
//    flush();
//    readfile($file);
//    exit;
//}
//else {
//    echo "$file not found";
//}




//die;
//force user to download the image
//if(file_exists('wow.png')){
//        // We'll be outputting a PNG
//        header('Content-type: image/png');
//        // It will be called wow.png
//        header('Content-Disposition: attachment; filename="wow.png"');
//        // The PDF source is in wow.png
//        readfile('wow.png');
//}

$indo = array();

$result = mysql_query("SELECT * FROM $target_table WHERE id = $id;");
$ncount = mysql_num_rows($result);
while ($row = mysql_fetch_assoc($result)) {
    $indo[] = $row['word'];
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

$ori = array();
$strong = array();
$av = array();
$result_words = mysql_query("SELECT * FROM wh_word LEFT JOIN greek ON wh_word.strong = greek.id LEFT JOIN nasb_greek ON wh_word.strong = nasb_greek.id WHERE ayat = $id GROUP BY pos;");

	while($row = mysql_fetch_array($result_words))
	{
    	$strong[] = $row['strong'];
    	$ori[]= $row['kata'];
   
    if (preg_match_all("|(.+?\s+\d+)(?:,\s+)?|", $row['av'], $match)) {
        $av_temp = $match[1][0];
        if ($match[1][1])
            $av_temp .= ", {$match[1][1]}";
    }
    else
        $av_temp = implode(', ', array_slice(explode(', ', $row['av']), 0, 3));
    $av[].= $av_temp ."<b>[{$row['kind']}; {$row['count']}]</b>";	
	}

$count = count($indo);
if(count($ori)>$count)
$count = count($ori);
if(count($av)>$count)
$count = count($av);

$html.="<html>";
$html.="<head>";
$html.="<title></title>";
$html.="<style type=\"text/css\">";
$html.="tr{height:5px;}";
$html.="td{padding-bottom:0px;padding-top:0px;}";
$html.="img{height:11em;}";
$html.=".g{font-family: olbgrk;}";
//$html.="table{border:1px solid #000;}";
$html.="</style>";
$html.="<head>";
$html.="<body>";
$html.="<table style=\"vertical-align:top;\">";
/*for($i=0;$i<$count;$i++)
{
	if($i==0)
		$html.="<tr><td>".$indo[$i]."</td><td rowspan=\"$count\"><img src=\"$img\" alt=\"linkage\"/></td><td>&lt;{$strong[$i]}&gt;</td><td class=\"g\">".$ori[$i]."</td><td>".$av[$i]."</td></tr>";
	else
		$html.="<tr><td>".$indo[$i]."</td><td>&lt;{$strong[$i]}&gt;</td><td class=\"g\">".$ori[$i]."</td><td>".$av[$i]."</td></tr>";
}*/
$html.="<tr>";
$html.="<td class=\"indo\">";
$html.="<table style=\"vertical-align:top;\">";
for($i=0;$i<count($indo);$i++)
	$html.="<tr ><td style=\"height:".(count($ori)/count($indo)*20.5)."px;\">".$indo[$i]."</td></tr>";
$html.="</table>";
$html.="</td>";
$html.="<td class=\"linear\">";
$html.="<img src=\"$file\" alt=\"linkage\"/>";
$html.="</td>";
$html.="<td class=\"ori\">";
$html.="<table style=\"vertical-align:top;\">";
for($i=0;$i<count($ori);$i++)
	$html.="<tr><td style=\"height:".(count($indo)/count($ori)*20.5)."px;\">".$ori[$i]."</td><td>".$av[$i]."</td></tr>";
$html.="</table>";
$html.="</td>";
$html.="</tr>";
$html.="</table>";
$html.="</body>";
$html.="</html>";
//$html = "<img src=\"$img\"/>";
echo $html;
//	$mpdf = new mPDF();
//	$mpdf->WriteHTML($html);
//	$mpdf->Output();
//	exit;
?>