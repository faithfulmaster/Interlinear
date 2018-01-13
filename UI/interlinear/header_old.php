<?php
//include('../header_config.php');
//include('../books.php');
//include('../MPDF56/mpdf.php');

$id = $_GET['id'];
if (!$id)
    $id = 1;
$strong = $_GET['s'];
if (!$strong)
    $strong = 0;

//url//
$req_uri_view_piece = explode("/",$_SERVER[PHP_SELF]);
$req_uri_view = $req_uri_view_piece[1]."/view/";
$version = $req_uri_view_piece[2];
$filename = $req_uri_view_piece[3];

$req_uri_old = str_replace("grk","heb",$_SERVER[PHP_SELF]);
$req_uri_new = str_replace("heb","grk",$_SERVER[PHP_SELF]);

if($id<23146 && substr($version,0,3) == "grk")
{
	header("location:".$req_uri_old."?id=".$id);
}
else if($id>23145 && substr($version,0,3) == "heb")
{
	header("location:".$req_uri_new."?id=".$id);
}
else if($id<1)
{
	header("location:".$req_uri_old."?id=1");
}
else if($id>31102)
{	
	header("location:".$req_uri_new."?id=23146");
}

$link = mysql_connect($host, $name, $pass);
mysql_selectdb($db);

$prev_id = $id - 1;
$next_id = $id + 1;

$result = mysql_query("SELECT book, chapter, verse, av_st.text as av, nasb_strong.text as nasb_strong, nasb.text as nasb FROM verses LEFT JOIN av_st USING (id) LEFT JOIN nasb_strong USING (id) LEFT JOIN nasb USING (id) WHERE verses.id=$id;");
$row_bible = mysql_fetch_array($result);

//jika browser tidak support canvas maka load halaman yang lama//
if($filename!="detail_old.php")
	if($_GET['nocanvas']==true)
	{
		$page = file_get_contents($location.$version.'/detail_old.php?id='.$id);
		echo $page;
		die;
	}

$header = '';
echo "<!DOCTYPE html>\n";
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\" />\n";
echo "<meta content=\"True\" name=\"HandheldFriendly\">\n";
echo "<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;\">\n";
echo "<meta name=\"viewport\" content=\"width=device-width\">\n";
echo "<title>{$books[$row_bible['book']]['name']} {$row_bible['chapter']}:{$row_bible['verse']}</title>\n";
echo "<link rel=\"stylesheet\" href=\"../include/styles.css\" type=\"text/css\" />\n";
echo "<script type=\"text/javascript\" language=\"javascript\" src=\"wz_dragdrop.js\"></script>\n";
echo "<script type=\"text/javascript\" language=\"javascript\" src=\"../include/scripts.js\"></script>\n";
echo "<script type=\"text/javascript\" language=\"javascript\" src=\"../jsPDF/jspdf.js\"></script>\n";
//$req_uri = str_replace("detail_old","detail",$_SERVER[REQUEST_URI]);
//echo "<script type=\"text/javascript\">";
//echo "window.history.pushState('page2', 'Title', '".$req_uri."');";
//echo "</script>"; 

echo "</head>\n";
echo "<body ondblclick=\"clearBox();\" onload=\"checkBrowser($id);\">\n";

if ($strong)
    echo "<a href=\"$strong_page?s=$strong\">Back to #$strong</a>\n";


echo "<input type=\"hidden\" id=\"uri_old\" value=\"$req_uri_old\" />";
echo "<input type=\"hidden\" id=\"uri_new\" value=\"$req_uri_new\" />";

$cur_book = (isset($_POST['book1'])) ? $_POST['book1'] : ((isset($_POST['book2'])) ? $_POST['book2'] : (isset($row_bible['book'])? $row_bible['book'] : 1));
$cur_chapter = (isset($_POST['chapter1'])) ? $_POST['chapter1'] : ((isset($_POST['chapter2'])) ? $_POST['chapter2'] : (isset($row_bible['chapter']) ? $row_bible['chapter'] : 1));
$cur_verse = (isset($_POST['verse1'])) ? $_POST['verse1'] : ((isset($_POST['verse2'])) ? $_POST['verse2'] : (isset($row_bible['verse']) ? $row_bible['verse'] : 1));

//echo $cur_book."<br/>";
//echo $cur_chapter."<br/>";
//echo $books[$cur_book]['vmax'][$cur_chapter]; 
if($filename!="pdf.php")
{

/** atas **/
echo "<div id=\"top-nav\" style=\"position:absolute;\">";
echo "<form action=\"\" method=\"post\" name=\"formBible1\" id=\"formBible1\" onsubmit=\"goto(1);\" \">";
echo "<a href=\"../view/?version=ayt&dir=reverse&book=".$row_bible['book']."&chapter=".$row_bible['chapter']."\">Go Up &uarr;</a>&nbsp;";
if($prev_id == 23145)
		echo "<a href=\"$req_uri_old?id=$prev_id\">&lt;&lt;</a>\n";
else if($prev_id > 0)
    echo "<a href=\"?id=$prev_id\">&lt;&lt;</a>\n";

echo "<b><a href=$alkitab_link/verse.php?book={$books[$row_bible['book']]['abbr']}&chapter={$row_bible['chapter']}&verse={$row_bible['verse']}>{$books[$row_bible['book']]['name']} {$row_bible['chapter']}:{$row_bible['verse']}</a></b>\n";

if($next_id == 23146)
		echo "<a href=\"$req_uri_new?id=$next_id\">&gt;&gt;</a>\n";
else if($next_id <= 31102)
    echo "<a href=\"?id=$next_id\">&gt;&gt;</a>\n";

echo "<select name=\"sbook1\" id=\"sbook1\" style=\"width:200px;\" onchange=\"selectBookChapAll(this.selectedIndex+1, formBible1.schapter1.selectedIndex+1, formBible1.sverse1.selectedIndex, formBible1.schapter1, formBible1.sverse1);\">";
			foreach($books as $kb => $b){
				if($kb == $cur_book)
					echo "<option value=\"$kb\" selected>$b[name]</option>";
				else	
					echo "<option value=\"$kb\">$b[name]</option>";
			}
echo "		</select>";
echo "		<select name=\"schapter1\" id=\"schapter1\" style=\"width:50px;\" onchange=\"selectChapterAll(formBible1.sbook1.selectedIndex+1, this.selectedIndex+1, formBible1.sverse1.selectedIndex, formBible1.sverse1);\">";
			for($i=1;$i<=$books[$cur_book]['max'];$i++)
			{
				if($i == $cur_chapter)
					echo "<option value=\"$i\" selected>$i</option>";
				else
					echo "<option value=\"$i\">$i</option>";
			}
echo "		</select>";
	
echo "		<select name=\"sverse1\" id=\"sverse1\" style=\"width:50px;\">";
			for($i=1;$i<=$books[$cur_book]['vmax'][$cur_chapter-1];$i++)
			{
				if($i == $cur_verse)
					echo "<option value=\"$i\" selected>$i</option>";
				else
					echo "<option value=\"$i\">$i</option>";
			}
echo "</select>";
echo "<input type=\"submit\" class=\"button\" value=\"Go\" />";

echo "<input type=\"button\" value=\"KJV+NASB\" id=\"KJVNASB1\" class=\"button toogle\" onclick=\"showText();\" title=\"show KJV and NASB\">";

/** save **/
echo "<select class=\"button\" onchange=\"setType(this)\">\n";
foreach ($warna as $key => $value)
    if ($key != 0)
        echo "<option style=\"color:#FFFFFF;background-color:$value\" value=\"$key\">Type {$types[$key]}\n";
echo "</select>\n";

echo "<input id=\"reset\" type=\"button\" name=\"reset\" value=\"Reset\" class=\"button\" onclick=\"resetCanvas()\" title=\"reset links to original state\">\n";
$result = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $id AND stage = 0;");
if (mysql_num_rows($result)) {
    echo "<input id=\"done\" type=\"button\" name=\"done\" value=\"Save &amp; Check\" class=\"redbutton\" onclick=\"saveCanvas('$detail_proc_page', $id, $strong, 1)\" title=\"save links to database and mark as checked\">\n";
    $checked = FALSE;
}
else {
    echo "<input id=\"done\" type=\"button\" name=\"done\" value=\"Save &amp; Check\" class=\"button\" onclick=\"saveCanvas('$detail_proc_page', $id, $strong, 1)\" title=\"save links to database and mark as checked\">\n";
    $checked = TRUE;
}

echo "<input id=\"save\" type=\"button\" name=\"save\" value=\"Save\" class=\"button\" onclick=\"saveCanvas('$detail_proc_page', $id, $strong, 0)\" title=\"save links to database\">";
/** end save **/
//echo "<input type=\"button\" value=\"PDF\" id=\"PDF1\" class=\"button\" onclick=\"showPDF('".$version."')\" title=\"generate PDF\">";
//echo "<img src=\"../include/images/circle-i.gif\" alt=\"\" width=\"16px\" id=\"info\" />";
echo "</form>";
echo "</div>";
/** end atas **/


/** bawah **/
echo "<div id=\"bottom-nav\" style=\"position:absolute;\">";
echo "<form action=\"\" method=\"post\" name=\"formBible2\" id=\"formBible2\" onsubmit=\"goto(2);\" \">";
echo "<a href=\"../view/?version=ayt&dir=reverse&book=".$row_bible['book']."&chapter=".$row_bible['chapter']."\">Go Up &uarr;</a>&nbsp;";
if($prev_id == 23145)
		echo "<a href=\"$req_uri_old?id=$prev_id\">&lt;&lt;</a>\n";
else if($prev_id > 0)
    echo "<a href=\"?id=$prev_id\">&lt;&lt;</a>\n";

echo "<b><a href=$alkitab_link/verse.php?book={$books[$row_bible['book']]['abbr']}&chapter={$row_bible['chapter']}&verse={$row_bible['verse']}>{$books[$row_bible['book']]['name']} {$row_bible['chapter']}:{$row_bible['verse']}</a></b>\n";

if($next_id == 23146)
		echo "<a href=\"$req_uri_new?id=$next_id\">&gt;&gt;</a>\n";
else if($next_id <= 31102)
    echo "<a href=\"?id=$next_id\">&gt;&gt;</a>\n";

echo "<select name=\"sbook2\" id=\"sbook2\" style=\"width:200px;\" onchange=\"selectBookChapAll(this.selectedIndex+1, formBible2.schapter2.selectedIndex+1, formBible2.sverse2.selectedIndex, formBible2.schapter2, formBible2.sverse2);\">";
			foreach($books as $kb => $b){
				if($kb == $cur_book)
					echo "<option value=\"$kb\" selected>$b[name]</option>";
				else	
					echo "<option value=\"$kb\">$b[name]</option>";
			}
echo "		</select>";
echo "		<select name=\"schapter2\" id=\"schapter2\" style=\"width:50px;\" onchange=\"selectChapterAll(formBible2.sbook2.selectedIndex+1, this.selectedIndex+1, formBible2.sverse2.selectedIndex, formBible2.sverse2);\">";
			for($i=1;$i<=$books[$cur_book]['max'];$i++)
			{
				if($i == $cur_chapter)
					echo "<option value=\"$i\" selected>$i</option>";
				else
					echo "<option value=\"$i\">$i</option>";
			}
echo "		</select>";
	
echo "		<select name=\"sverse2\" id=\"sverse2\" style=\"width:50px;\">";
			for($i=1;$i<=$books[$cur_book]['vmax'][$cur_chapter-1];$i++)
			{
				if($i == $cur_verse)
					echo "<option value=\"$i\" selected>$i</option>";
				else
					echo "<option value=\"$i\">$i</option>";
			}
echo "</select>";

echo "<input type=\"submit\" class=\"button\" value=\"Go\" />";

echo "<input type=\"button\" value=\"KJV+NASB\" id=\"KJVNASB2\" class=\"button toogle\" onclick=\"showText();\" title=\"show KJV and NASB\">";
//echo "<input type=\"button\" value=\"PDF\" class=\"button\" onclick=\"showPDF()\" title=\"generate PDF\">";
//echo "<img src=\"../include/images/circle-i.gif\" alt=\"\" width=\"16px\" id=\"info\" />";
echo "</form>";
/*if($req_uri_view_piece[3]=="detail.php")
{
	echo "<div class=\"warning\">This page is using HTML5 Canvas. Please make sure that your browser supports HTML5 Canvas.</div>";
	echo "<div class=\"warning\">Halaman ini menggunakan HTML5 Canvas. Pastikan browser anda mendukung HTML5 Canvas.</div>";
}*/
echo "</div>";
/** end bawah **/
}


//echo "<script type=\"text/javascript\">";
//echo "changeBook('".$row_bible['chapter']."');";
//echo "changeChapter('".$row_bible['verse']."');";
//echo "</script>";

//include('tooltip.php');


?>