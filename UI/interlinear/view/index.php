<?php
require('../header_config.php');
include('../books.php');
require('config.php');

function format($string, $tipe, $stage) {
    if ($tipe != 0)
        $string = "<span class=\"t$tipe\">$string</span>";
    if ($stage != 1)
        $string = "<i>$string</i>";
    return $string;
}

/// index buku - jika 1-39 maka part 'h', setelah itu part 'g' //
$book = $_GET['book'];
if (!$book)
    $book = 40;
if ($book < 40)
    $part = 'h';
else
    $part = 'g';
    
/// nomer chapter //
$chapter = $_GET['chapter'];
if (!$chapter)
    $chapter = 1;
$chapter_info = $chapters[$books[$book]['count'] + $chapter];
$min_id = $chapter_info['count'] + 1;

/// brapa verse yang ditampilkan dalam chapter ini. jika show all maka semua ditampilkan. //
$show = $_GET['show'];
if (!$show)
    $show = 5;
if (($show != 'all') && ($chapter_info['max'] > $show))
    $max_id = $chapter_info['count'] + $show;
else
    $max_id = $chapter_info['count'] + $chapter_info['max'];

/// nomer ayat di pasal tersebut. jika verse diset maka show tidak berfungsi //
$verse = $_GET['verse'];
if ($verse) {
    $min_id = $chapter_info['count'] + $verse;
    $max_id = $min_id;
}

/// id ayat. jika ada id maka chapter //
$id = $_GET['id'];
if ($id) {
    if ($id < 23146)
        $part = 'h';
    else
        $part = 'g';
    $min_id = $id;
    $max_id = $min_id;
}

/// version - ayt, tb, tl, atau net //
$version = $_GET['version'];
if (!$version)
    $version = $default_version;

/// dir - reverse atau classic. jika reverse maka bahasa asli dibawah, jika classic maka bahasa asli diatas //
$dir = $_GET['dir'];
if (!$dir)
    $dir = 'reverse';

$link = mysql_connect($host, $name, $pass);
mysql_selectdb($db);

echo "<html>\n";
echo "<head>\n";

/// untuk title HTML page //
if ($min_id == $max_id) {
    $result = mysql_query("SELECT book, chapter, verse FROM verses WHERE id = $min_id;");
    $row = mysql_fetch_array($result);
    $book = $row['book'];
    $chapter = $row['chapter'];
    $verse = $row['verse'];
    echo "<title>{$books[$row['book']]['name']} {$row['chapter']}:{$row['verse']}</title>\n";
}
else
    echo "<title>{$books[$book]['name']} $chapter</title>\n";
echo "<link rel=\"stylesheet\" href=\"styles.css\" type=\"text/css\" />\n";
echo "<script type=\"text/javascript\" language=\"javascript\" src=\"../include/scripts.js\"></script>\n";
//echo "<link rel=\"stylesheet\" href=\"http://net.bible.org/styles/plain.css\" type=\"text/css\" />\n";
echo "</head>\n";
echo "<body>\n";

/*
echo "<div id=\"header\" class=\"header\">\n";
echo "<div id=\"header_left\" class=\"header_left\">\n";
echo "<img src=\"http://net.bible.org/images/head.gif\">\n";
echo "</div>\n";
echo "<div id=\"header_right\" class=\"header_right\">\n";
echo "<a href=\"http://net.bible.org/\">NeXtBible&trade; Learning Environment</a>\n";
echo "</div>\n";
echo "<div id=\"menu_bar\" class=\"menu_bar_left\">\n";
echo "<a href=\"http://net.bible.org/home.php\" id=\"menubar\">Home</a>\n";
echo "| <a href=\"http://www.bible.org/\" id=\"menubar\" target=\"_blank\">Home bible.org</a>\n";
echo "| <a href=\"http://www.bible.org/downloads\" id=\"menubar\" target=\"_blank\">Download</a>\n";
echo "| <a href=\"http://net.bible.org/features.php\" id=\"menubar\">Features</a>\n";
echo "| <a href=\"http://net.bible.org/fonts.php\" id=\"menubar\">Fonts</a>\n";
echo "| <a href=\"http://forum.bible.org/viewforum.php?f=94\" id=\"menubar\" target=\"_blank\">Forum</a>\n";
echo "| <a href=\"http://dev.bible.org/drupal/webmaster\" id=\"menubar\" target=\"_blank\">Webmaster Tools</a>\n";
echo "| <a href=\"http://dev.bible.org/drupal/help\" id=\"menubar\" target=\"_blank\">Help</a>\n";
echo "</div>\n";
echo "</div>\n";

echo "<div style=\"position:relative;top:70px\">\n";
*/

/// NAVIGASI //
echo "<table width=\"100%\">\n";
echo "<tr valign=\"top\">\n";
echo "<td align=\"left\">\n";
if ($min_id == $max_id) {
    if ($min_id > 1) {
        $prev_id = $min_id - 1;
        echo "<a href=\"?id=$prev_id&amp;version=$version&amp;dir=$dir\">Prev Verse</a>\n";
    }
    echo "<b><big>{$books[$row['book']]['name']} {$row['chapter']}:{$row['verse']}</big></b>\n";
    if ($min_id < 31102) {
        $next_id = $min_id + 1;
        echo "<a href=\"?id=$next_id&amp;version=$version&amp;dir=$dir\">Next Verse</a>\n";
    }
}
else {
    if ($chapter > 1) {
        $prev_book = $book;
        $prev_chapter = $chapter - 1;
        echo "<a href=\"?book=$prev_book&amp;chapter=$prev_chapter&amp;version=$version&amp;dir=$dir\">Prev Chapter</a>\n";
    }
    elseif ($book > 1) {
        $prev_book = $book - 1;
        $prev_chapter = $books[$prev_book]['max'];
        echo "<a href=\"?book=$prev_book&amp;chapter=$prev_chapter&amp;version=$version&amp;dir=$dir\">Prev Chapter</a>\n";
    }
    echo "<b><big>{$books[$book]['name']} $chapter</big></b>\n";
    if ($chapter < $books[$book]['max']) {
        $next_book = $book;
        $next_chapter = $chapter + 1;
        echo "<a href=\"?book=$next_book&amp;chapter=$next_chapter&amp;version=$version&amp;dir=$dir\">Next Chapter</a>\n";
    }
    elseif ($book < 66) {
        $next_book = $book + 1;
        $next_chapter = 1;
        echo "<a href=\"?book=$next_book&amp;chapter=$next_chapter&amp;version=$version&amp;dir=$dir\">Next Chapter</a>\n";
    }
}
echo "</td>\n";

/// Show all verses //
if ($show != 'all')
    echo "<td><a href=\"?book=$book&amp;chapter=$chapter&amp;version=$version&amp;dir=$dir&amp;show=all\">Show all verses</a></td>\n";
echo "<td align=\"right\">\n";
echo "<form name=\"formBible\">\n";

//$cur_book = (isset($_POST['book'])) ? $_POST['book'] : (isset($row_bible['book'])? $row_bible['book'] : 1));
//$cur_chapter = (isset($_POST['chapter1'])) ? $_POST['chapter1'] : ((isset($_POST['chapter2'])) ? $_POST['chapter2'] : (isset($row_bible['chapter']) ? $row_bible['chapter'] : 1));
//$cur_verse = (isset($_POST['verse1'])) ? $_POST['verse1'] : ((isset($_POST['verse2'])) ? $_POST['verse2'] : (isset($row_bible['verse']) ? //$row_bible['verse'] : 1));


/// book, chapter, verse //
echo "<select name=\"book\" onchange=\"selectBookChapAll(this.selectedIndex+1, formBible.chapter.selectedIndex+1, formBible.verse.selectedIndex, formBible.chapter, formBible.verse);\">\n";
foreach ($books as $key => $val) {
    if ($key == $book)
        echo "<option value=\"$key\" selected>{$val['name']}\n";
    else
        echo "<option value=\"$key\">{$val['name']}\n";
}
echo "</select>\n";

echo "<select name=\"chapter\" id=\"chapter\" style=\"width:50px;\" onchange=\"selectChapterAll(formBible.book.selectedIndex+1, this.selectedIndex+1, formBible.verse.selectedIndex, formBible.verse);\">";
			for($i=1;$i<=$books[$book]['max'];$i++)
			{
				if($i == $chapter)
					echo "<option value=\"$i\" selected>$i</option>";
				else
					echo "<option value=\"$i\">$i</option>";
			}
echo "</select>";
	
echo "<select name=\"verse\" id=\"verse\" style=\"width:85px;\">";
echo " <option value=\"\">- Semua -</option>";
			for($i=1;$i<=$books[$book]['vmax'][$chapter-1];$i++)
			{
				if($i == $verse)
					echo "<option value=\"$i\" selected>$i</option>";
				else
					echo "<option value=\"$i\">$i</option>";
			}
echo "</select>";
//echo "<input type=\"text\" name=\"chapter\" value=\"$chapter\" size=\"3\" />\n";
//echo "<input type=\"text\" name=\"verse\" value=\"$verse\" size=\"3\" />\n";

/// version //
echo "<select name=\"version\">\n";
foreach ($versions as $key => $val) {
    if ($key == $version)
        echo "<option value=\"$key\" selected>$val\n";
    else
        echo "<option value=\"$key\">$val\n";
}
echo "</select>\n";

/// dir //
echo "<select name=\"dir\">\n";
if ($dir == 'reverse') {
    echo "<option value=\"reverse\" selected>Reverse\n";
    echo "<option value=\"classic\">Classic\n";
}
else {
    echo "<option value=\"reverse\">Reverse\n";
    echo "<option value=\"classic\" selected>Classic\n";
}
echo "</select>\n";
echo "<input type=\"submit\" value=\"View\"/>\n";
echo "</form>\n";
echo "</td>\n";
echo "</tr>\n";
echo "</table>\n";

echo "<script type=\"text/javascript\">\n";

/// jika versi net maka ada notesnya juga yang harus ditampilkan dalam tooltip //
if ($version == 'net') {
    $result = mysql_query("SELECT * FROM net_notes WHERE id BETWEEN $min_id AND $max_id;");
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
}

$av_phrases = array();
$result = mysql_query("SELECT * FROM av_phrase WHERE id BETWEEN $min_id AND $max_id;");
while ($row = mysql_fetch_assoc($result)) {
    if (array_key_exists($row['id'], $av_phrases))
        $av_phrases[$row['id']][] = $row;
    else
        $av_phrases[$row['id']] = array($row);
}

$words = array();
if ($part == 'h')
    $result = mysql_query("SELECT ayat, pos, concat('0',strong) AS strong, hebrew.word AS root, nasb_hebrew.word AS trans, '' AS parsing, count, definition, def FROM bhsstr_word LEFT JOIN hebrew ON bhsstr_word.strong = hebrew.id LEFT JOIN nasb_hebrew ON bhsstr_word.strong = nasb_hebrew.id WHERE ayat BETWEEN $min_id AND $max_id GROUP BY ayat, pos;");
else
    $result = mysql_query("SELECT ayat, pos, strong, greek.word AS root, nasb_greek.word AS trans, jenis1 AS parsing, count, definition, def FROM wh_word LEFT JOIN greek ON wh_word.strong = greek.id LEFT JOIN nasb_greek ON wh_word.strong = nasb_greek.id WHERE ayat BETWEEN $min_id AND $max_id GROUP BY ayat, pos;");
while ($row = mysql_fetch_assoc($result)) {
    if (array_key_exists($row['ayat'], $words))
        $words[$row['ayat']][] = $row;
    else
        $words[$row['ayat']] = array($row);
}

$low_max = array();
for ($id = $min_id; $id <= $max_id; $id++) {
    $local = array();
    foreach ($av_phrases[$id] as $row) {
        $local_strong = explode(' ', $row['strong']);
        foreach ($local_strong as $s) {
            $s = (int)$s;
            if (array_key_exists($s, $local))
                $local[$s][] = $row['original'];
            else
                $local[$s] = array($row['original']);
        }
    }

    foreach ($words[$id] as $row) {
        if (array_key_exists($row['strong'], $local))
            $loc = implode('<br />', $local[$row['strong']]);
        else
            $loc = '-';
        $root = str_replace("'", "\'", '<big><b>OLB:</b> ' . preg_replace(array('/\\\\\^(.+?)\\\\\^/', '/\\\\~(.+?)\\\\~/'), array("<span class=\"h\">$1</span>", "<span class=\"g\">$1</span>"), $row['root']) . '<br /><b>NASB: ' . $row['count'] . '</b> ' . $row['trans'] . '<br /><b>Parsing:</b> ' . $row['parsing'] . '<br /><b>KJV here:</b> ' . $loc . '</big>');
        $title = preg_replace(array('/\\\\\^(.+?)\\\\\^/', '/\\\\~(.+?)\\\\~/'), array("<span class=\"h\">$1</span>", "<span class=\"g\">$1</span>"), str_replace("'","\'",$row['root']))." <a class=lexlinkheader href={$alkitab_link}/strong.php?id={$row['strong']} target=_blank>(".$row['strong'].")</span>";
        echo "root{$id}_{$row['pos']} = '$root';\n";
        echo "title{$id}_{$row['pos']} = '$title';\n";
        $definition = str_replace(array("\r\n", "'"), array('<br />', "\'"), "<b>NASB:</b><br />{$row['def']}<br /><b>KJV: {$row['count']}</b><br />{$row['definition']}");
        echo "def{$id}_{$row['pos']} = '$definition';\n";
        $low_max[$id] = $row['pos'];
    }
}

echo "</script>\n";

$net_max = array();
switch ($version) {
    case 'net':
        $result = mysql_query("SELECT ayat AS id, MAX(pos) AS pos FROM net_chop WHERE ayat BETWEEN $min_id AND $max_id GROUP BY ayat;");
        break;
    case 'tb':
        if ($part == 'h')
            $result = mysql_query("SELECT id, MAX(pos) AS pos FROM pltb_word WHERE id BETWEEN $min_id AND $max_id GROUP BY id;");
        else
            $result = mysql_query("SELECT id, MAX(pos) AS pos FROM pbtb_word WHERE id BETWEEN $min_id AND $max_id GROUP BY id;");
        break;
    case 'tl':
        if ($part == 'h')
            $result = mysql_query("SELECT id, MAX(pos) AS pos FROM pltl_word WHERE id BETWEEN $min_id AND $max_id GROUP BY id;");
        else
            $result = mysql_query("SELECT id, MAX(pos) AS pos FROM pbtl_word WHERE id BETWEEN $min_id AND $max_id GROUP BY id;");
        break;
    case 'ayt':
        if ($part == 'h')
            $result = mysql_query("SELECT id, MAX(pos) AS pos FROM playt_word WHERE id BETWEEN $min_id AND $max_id GROUP BY id;");
        else
            $result = mysql_query("SELECT id, MAX(pos) AS pos FROM pbayt_word WHERE id BETWEEN $min_id AND $max_id GROUP BY id;");
        break;
}
while ($row = mysql_fetch_assoc($result)) {
    $net_max[$row['id']] = $row['pos'];
}

//retrieve database linkage
$linkages = array();
switch ($version) {
    case 'net':
        if ($part == 'h') {
            if ($dir == 'reverse')
                $result = mysql_query("SELECT giza_ot_pairmap.ayat, net, bhs AS low, tanda AS word, concat('0',bhsstr_word.strong) AS strong, bhsstr_word.kata, catatan, tipe, stage FROM giza_ot_linkage_clean LEFT JOIN giza_ot_pairmap USING (pair) LEFT JOIN net_chop ON giza_ot_pairmap.ayat = net_chop.ayat AND giza_ot_linkage_clean.net = net_chop.pos LEFT JOIN bhsstr_word ON giza_ot_pairmap.ayat = bhsstr_word.ayat AND giza_ot_linkage_clean.bhs = bhsstr_word.pos WHERE giza_ot_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_ot_pairmap.ayat, net, bhs;");
            else
                $result = mysql_query("SELECT giza_ot_pairmap.ayat, net, bhs AS low, tanda AS word, concat('0',bhsstr_word.strong) AS strong, bhsstr_word.kata, catatan, tipe, stage FROM giza_ot_linkage_clean LEFT JOIN giza_ot_pairmap USING (pair) LEFT JOIN net_chop ON giza_ot_pairmap.ayat = net_chop.ayat AND giza_ot_linkage_clean.net = net_chop.pos LEFT JOIN bhsstr_word ON giza_ot_pairmap.ayat = bhsstr_word.ayat AND giza_ot_linkage_clean.bhs = bhsstr_word.pos WHERE giza_ot_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_ot_pairmap.ayat, bhs, net;");
            $site = 'heb2net';
        }
        else {
            if ($dir == 'reverse')
                $result = mysql_query("SELECT giza_linkage_grk2net.ayat, net, wh AS low, tanda AS word, wh_word.strong, wh_word.kata, catatan, tipe, stage FROM giza_linkage_grk2net LEFT JOIN net_chop ON giza_linkage_grk2net.ayat = net_chop.ayat AND giza_linkage_grk2net.net = net_chop.pos LEFT JOIN wh_word ON giza_linkage_grk2net.ayat = wh_word.ayat AND giza_linkage_grk2net.wh = wh_word.pos WHERE giza_linkage_grk2net.ayat BETWEEN $min_id AND $max_id ORDER BY giza_linkage_grk2net.ayat, net, wh;");
            else
                $result = mysql_query("SELECT giza_linkage_grk2net.ayat, net, wh AS low, tanda AS word, wh_word.strong, wh_word.kata, catatan, tipe, stage FROM giza_linkage_grk2net LEFT JOIN net_chop ON giza_linkage_grk2net.ayat = net_chop.ayat AND giza_linkage_grk2net.net = net_chop.pos LEFT JOIN wh_word ON giza_linkage_grk2net.ayat = wh_word.ayat AND giza_linkage_grk2net.wh = wh_word.pos WHERE giza_linkage_grk2net.ayat BETWEEN $min_id AND $max_id ORDER BY giza_linkage_grk2net.ayat, wh, net;");
            $site = 'grk2net';
        }
        break;
    case 'tb':
        if ($part == 'h') {
            if ($dir == 'reverse')
                $result = mysql_query("SELECT giza_tb_pairmap.ayat, net, bhs AS low, word, concat('0',bhsstr_word.strong) AS strong, bhsstr_word.kata, tipe, stage FROM giza_tb_linkage_clean LEFT JOIN giza_tb_pairmap USING (pair) LEFT JOIN pltb_word ON giza_tb_pairmap.ayat = pltb_word.id AND giza_tb_linkage_clean.net = pltb_word.pos LEFT JOIN bhsstr_word ON giza_tb_pairmap.ayat = bhsstr_word.ayat AND giza_tb_linkage_clean.bhs = bhsstr_word.pos WHERE giza_tb_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_tb_pairmap.ayat, net, bhs;");
            else
                $result = mysql_query("SELECT giza_tb_pairmap.ayat, net, bhs AS low, word, concat('0',bhsstr_word.strong) AS strong, bhsstr_word.kata, tipe, stage FROM giza_tb_linkage_clean LEFT JOIN giza_tb_pairmap USING (pair) LEFT JOIN pltb_word ON giza_tb_pairmap.ayat = pltb_word.id AND giza_tb_linkage_clean.net = pltb_word.pos LEFT JOIN bhsstr_word ON giza_tb_pairmap.ayat = bhsstr_word.ayat AND giza_tb_linkage_clean.bhs = bhsstr_word.pos WHERE giza_tb_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_tb_pairmap.ayat, bhs, net;");
            $site = 'heb2tb';
        }
        else {
            if ($dir == 'reverse')
                $result = mysql_query("SELECT giza_linkage_grk2tb.ayat, net, wh AS low, word, wh_word.strong, wh_word.kata, tipe, stage FROM giza_linkage_grk2tb LEFT JOIN pbtb_word ON giza_linkage_grk2tb.ayat = pbtb_word.id AND giza_linkage_grk2tb.net = pbtb_word.pos LEFT JOIN wh_word ON giza_linkage_grk2tb.ayat = wh_word.ayat AND giza_linkage_grk2tb.wh = wh_word.pos WHERE giza_linkage_grk2tb.ayat BETWEEN $min_id AND $max_id ORDER BY giza_linkage_grk2tb.ayat, net, wh;");
            else
                $result = mysql_query("SELECT giza_linkage_grk2tb.ayat, net, wh AS low, word, wh_word.strong, wh_word.kata, tipe, stage FROM giza_linkage_grk2tb LEFT JOIN pbtb_word ON giza_linkage_grk2tb.ayat = pbtb_word.id AND giza_linkage_grk2tb.net = pbtb_word.pos LEFT JOIN wh_word ON giza_linkage_grk2tb.ayat = wh_word.ayat AND giza_linkage_grk2tb.wh = wh_word.pos WHERE giza_linkage_grk2tb.ayat BETWEEN $min_id AND $max_id ORDER BY giza_linkage_grk2tb.ayat, wh, net;");
            $site = 'grk2tb';
        }
        break;
    case 'tl':
        if ($part == 'h') {
            if ($dir == 'reverse')
                $result = mysql_query("SELECT giza_tl_pairmap.ayat, net, bhs AS low, word, concat('0',bhsstr_word.strong) AS strong, bhsstr_word.kata, tipe, stage FROM giza_tl_linkage_clean LEFT JOIN giza_tl_pairmap USING (pair) LEFT JOIN pltl_word ON giza_tl_pairmap.ayat = pltl_word.id AND giza_tl_linkage_clean.net = pltl_word.pos LEFT JOIN bhsstr_word ON giza_tl_pairmap.ayat = bhsstr_word.ayat AND giza_tl_linkage_clean.bhs = bhsstr_word.pos WHERE giza_tl_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_tl_pairmap.ayat, net, bhs;");
            else
                $result = mysql_query("SELECT giza_tl_pairmap.ayat, net, bhs AS low, word, concat('0',bhsstr_word.strong) AS strong, bhsstr_word.kata, tipe, stage FROM giza_tl_linkage_clean LEFT JOIN giza_tl_pairmap USING (pair) LEFT JOIN pltl_word ON giza_tl_pairmap.ayat = pltl_word.id AND giza_tl_linkage_clean.net = pltl_word.pos LEFT JOIN bhsstr_word ON giza_tl_pairmap.ayat = bhsstr_word.ayat AND giza_tl_linkage_clean.bhs = bhsstr_word.pos WHERE giza_tl_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_tl_pairmap.ayat, bhs, net;");
            $site = 'heb2tl';
        }
        else {
            if ($dir == 'reverse')
                $result = mysql_query("SELECT giza_pbtl_pairmap.ayat, net, wh AS low, word, wh_word.strong, wh_word.kata, tipe, stage FROM giza_pbtl_linkage_clean LEFT JOIN giza_pbtl_pairmap USING (pair) LEFT JOIN pbtl_word ON giza_pbtl_pairmap.ayat = pbtl_word.id AND giza_pbtl_linkage_clean.net = pbtl_word.pos LEFT JOIN wh_word ON giza_pbtl_pairmap.ayat = wh_word.ayat AND giza_pbtl_linkage_clean.wh = wh_word.pos WHERE giza_pbtl_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_pbtl_pairmap.ayat, net, wh;");
            else
                $result = mysql_query("SELECT giza_pbtl_pairmap.ayat, net, wh AS low, word, wh_word.strong, wh_word.kata, tipe, stage FROM giza_pbtl_linkage_clean LEFT JOIN giza_pbtl_pairmap USING (pair) LEFT JOIN pbtl_word ON giza_pbtl_pairmap.ayat = pbtl_word.id AND giza_pbtl_linkage_clean.net = pbtl_word.pos LEFT JOIN wh_word ON giza_pbtl_pairmap.ayat = wh_word.ayat AND giza_pbtl_linkage_clean.wh = wh_word.pos WHERE giza_pbtl_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_pbtl_pairmap.ayat, wh, net;");
            $site = 'grk2tl';
        }
        break;
		case 'ayt':
    if ($part == 'h') {
        if ($dir == 'reverse')
            $result = mysql_query("SELECT giza_ayt_pairmap.ayat, net, bhs AS low, word, concat('0',bhsstr_word.strong) AS strong, bhsstr_word.kata, tipe, stage FROM giza_ayt_linkage_clean LEFT JOIN giza_ayt_pairmap USING (pair) LEFT JOIN playt_word ON giza_ayt_pairmap.ayat = playt_word.id AND giza_ayt_linkage_clean.net = playt_word.pos LEFT JOIN bhsstr_word ON giza_ayt_pairmap.ayat = bhsstr_word.ayat AND giza_ayt_linkage_clean.bhs = bhsstr_word.pos WHERE giza_ayt_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_ayt_pairmap.ayat, net, bhs;");
        else
            $result = mysql_query("SELECT giza_ayt_pairmap.ayat, net, bhs AS low, word, concat('0',bhsstr_word.strong) AS strong, bhsstr_word.kata, tipe, stage FROM giza_ayt_linkage_clean LEFT JOIN giza_ayt_pairmap USING (pair) LEFT JOIN playt_word ON giza_ayt_pairmap.ayat = playt_word.id AND giza_ayt_linkage_clean.net = playt_word.pos LEFT JOIN bhsstr_word ON giza_ayt_pairmap.ayat = bhsstr_word.ayat AND giza_ayt_linkage_clean.bhs = bhsstr_word.pos WHERE giza_ayt_pairmap.ayat BETWEEN $min_id AND $max_id ORDER BY giza_ayt_pairmap.ayat, bhs, net;");
        $site = 'heb2ayt';
    }
    else {
        if ($dir == 'reverse')
            $result = mysql_query("SELECT giza_linkage_grk2ayt.ayat, net, wh AS low, word, wh_word.strong, wh_word.kata, tipe, stage FROM giza_linkage_grk2ayt LEFT JOIN pbayt_word ON giza_linkage_grk2ayt.ayat = pbayt_word.id AND giza_linkage_grk2ayt.net = pbayt_word.pos LEFT JOIN wh_word ON giza_linkage_grk2ayt.ayat = wh_word.ayat AND giza_linkage_grk2ayt.wh = wh_word.pos WHERE giza_linkage_grk2ayt.ayat BETWEEN $min_id AND $max_id ORDER BY giza_linkage_grk2ayt.ayat, net, wh;");
        else
            $result = mysql_query("SELECT giza_linkage_grk2ayt.ayat, net, wh AS low, word, wh_word.strong, wh_word.kata, tipe, stage FROM giza_linkage_grk2ayt LEFT JOIN pbayt_word ON giza_linkage_grk2ayt.ayat = pbayt_word.id AND giza_linkage_grk2ayt.net = pbayt_word.pos LEFT JOIN wh_word ON giza_linkage_grk2ayt.ayat = wh_word.ayat AND giza_linkage_grk2ayt.wh = wh_word.pos WHERE giza_linkage_grk2ayt.ayat BETWEEN $min_id AND $max_id ORDER BY giza_linkage_grk2ayt.ayat, wh, net;");
        $site = 'grk2ayt';
    }
    break; 
    
}
while ($row = mysql_fetch_assoc($result)) {
    if (array_key_exists($row['ayat'], $linkages))
        $linkages[$row['ayat']][] = $row;
    else
        $linkages[$row['ayat']] = array($row);
}

echo "<div id=\"view\">";
$note_offset = 0;
for ($id = $min_id; $id <= $max_id; $id++) {
    $unit_list = array();
    $empty_low = array();
    $empty_net = array();
    $error_list = array();
    foreach ($linkages[$id] as $row) {
        $node = array('net' => $row['net'], 'low' => $row['low'], 'word' => $row['word'], 'strong' => $row['strong'], 'lower' => $row['kata'], 'notes' => $row['catatan'], 'tipe' => $row['tipe'], 'stage' => $row['stage']);
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
            if ($dir == 'reverse') {
                if (($row['net'] != 255) && ($row['net'] != 0))
                    $unit_list[] = array('net' => array($row['net']), 'low' => array($row['low']), 'node' => array($node));
                else
                    if (($row['low'] != 255) && ($row['low'] != 0))
                        $empty_low[$row['low']] = $node;
            }
            else {
                if (($row['low'] != 255) && ($row['low'] != 0))
                    $unit_list[] = array('net' => array($row['net']), 'low' => array($row['low']), 'node' => array($node));
                else
                    if (($row['net'] != 255) && ($row['net'] != 0))
                        $empty_net[$row['net']] = $node;
            }
        }
    }
    ksort($empty_low);
    ksort($empty_net);

    $result = mysql_query("SELECT book, chapter, verse FROM verses WHERE id = $id;");
    $row = mysql_fetch_array($result);
    /*number verse*/
    if (count($error_list)) {
        if (($part == 'h') && ($dir != 'reverse'))
            echo "<div class=\"bad unithebrew no\"><big><b><a href=\"$location$site/detail.php?id=$id\">{$row['verse']}</a></b></big></div>";
        else
            echo "<div class=\"bad unit\" no><big><b><a href=\"$location$site/detail.php?id=$id\">{$row['verse']}</a></b></big></div>";
    }
    else {
        if (($part == 'h') && ($dir != 'reverse'))
            echo "<div class=\"unithebrew no\"><big><b><a href=\"$location$site/detail.php?id=$id\">{$row['verse']}</a></b></big></div>";
        else
            echo "<div class=\"unit no\"><big><b><a href=\"$location$site/detail.php?id=$id\">{$row['verse']}</a></b></big></div>";
    }
    if ($dir == 'reverse') {
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
                            $merged_word[$low_node['net']] = '';
                            $merged_lower[$low_node['low']] = format("<span onmouseover=\"showTip(root{$id}_{$low_node['low']},title{$id}_{$low_node['low']},200,200)\" onmouseout=\"UnTip()\"><sub><small>{$low_node['low']}</small></sub>{$low_node['lower']}</span>", $low_node['tipe'], $low_node['stage']);
                            $merged_strong[$low_node['low']] = format("<a href=\"$location$site/strong.php?s={$low_node['strong']}\" onmouseover=\"showTip(def{$id}_{$low_node['low']},title{$id}_{$low_node['low']},200,200)\" onmouseout=\"UnTip()\">&lt;{$low_node['strong']}&gt;</a>", $low_node['tipe'], $low_node['stage']);
                            $empty_low[$low_key]['merged'] = TRUE;
                        }
                    }
                }
                if ($node['net'] == $net + 1)
                    $net++;
                if ($node['net'] == $net) {
                    if ($node['notes'] != '') {
                        $notes = explode(' ', $node['notes']);
                        $notes_array = array();
                        foreach ($notes as $note) {
                            $n = $note + $note_offset;
                            $notes_array[] = "<sup><small><a href=\"#\" onmouseover=\"showTip(note$n,'',200,200)\" onmouseout=\"UnTip()\">$n</a></small></sup>";
                            $max_note = $n;
                        }
                        $notes = implode(' ', $notes_array);
                    }
                    else
                        $notes = '';
                    if (($node['low'] != 255) && ($node['low'] != 0)) {
                        $word_list[$node['net']] = format("{$node['word']}$notes", $node['tipe'], $node['stage']);
                        $lower_list[$node['low']] = format("<span onmouseover=\"showTip(root{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\"><sub><small>{$node['low']}</small></sub>{$node['lower']}</span>", $node['tipe'], $node['stage']);
                        $strong_list[$node['low']] = format("<a href=\"$location$site/strong.php?s={$node['strong']}\" onmouseover=\"showTip(def{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">&lt;{$node['strong']}&gt;</a>", $node['tipe'], $node['stage']);
                    }
                    else {
                        $merged_word[$node['net']] = format("{$node['word']}$notes", $node['tipe'], $node['stage']);
                        $merged_lower[$node['low']] = '';
                        $merged_strong[$node['low']] = '';
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
                    if ($word_out == '') $word_out = '&nbsp;';
                    if ($part == 'h') {
                        $merged_lower = array_reverse($merged_lower);
                        $merged_strong = array_reverse($merged_strong);
                    }
                    $lower_out = implode(' ', $merged_lower);
                    if ($lower_out == '') $lower_out = '&nbsp;';
                    $strong_out = implode(' ', $merged_strong);
                    if ($strong_out == '') $strong_out = '&nbsp;';
                    echo "<div class=\"unit\"><span class=\"fuzzy\">$word_out</span><br /><span class=\"fuzzy\"><span class=\"$part\">$lower_out</span><br />$strong_out</span></div>\n";
                    $merged_word = array();
                    $merged_lower = array();
                    $merged_strong = array();
                }
                $word_out = implode(' ', $word_list);
                if ($word_out == '') $word_out = '&nbsp;';
                if ($part == 'h') {
                    $lower_list = array_reverse($lower_list);
                    $strong_list = array_reverse($strong_list);
                }
                $lower_out = implode(' ', $lower_list);
                if ($lower_out == '') $lower_out = '&nbsp;';
                $strong_out = implode(' ', $strong_list) . $split_after;
                if ($strong_out == '') $strong_out = '&nbsp;';
                if ($bad_link)
                    echo "<div class=\"bad unit\">$word_out<br /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
                else
                    echo "<div class=\"unit\">$word_out<br /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
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
                                $merged_word[$low_node['net']] = '';
                                $merged_lower[$low_node['low']] = format("<span onmouseover=\"showTip(root{$id}_{$low_node['low']},title{$id}_{$low_node['low']},200,200)\" onmouseout=\"UnTip()\"><sub><small>{$low_node['low']}</small></sub>{$low_node['lower']}</span>", $low_node['tipe'], $low_node['stage']);
                                $merged_strong[$low_node['low']] = format("<a href=\"$location$site/strong.php?s={$low_node['strong']}\" onmouseover=\"showTip(def{$id}_{$low_node['low']},title{$id}_{$low_node['low']},200,200)\" onmouseout=\"UnTip()\">&lt;{$low_node['strong']}&gt;</a>", $low_node['tipe'], $low_node['stage']);
                                $empty_low[$low_key]['merged'] = TRUE;
                            }
                        }
                    }
                    if ($node['net'] == $net + 1)
                        $net++;
                    if ($node['net'] == $net) {
                        if ($node['notes'] != '') {
                            $notes = explode(' ', $node['notes']);
                            $notes_array = array();
                            foreach ($notes as $note) {
                                $n = $note + $note_offset;
                                $notes_array[] = "<sup><small><a href=\"#\" onmouseover=\"showTip(note$n,'',200,200)\" onmouseout=\"UnTip()\">$n</a></small></sup>";
                                $max_note = $n;
                            }
                            $notes = implode(' ', $notes_array);
                        }
                        else
                            $notes = '';
                        $split_before = "&lt;== ";
                        if (($node['low'] != 255) && ($node['low'] != 0)) {
                            $word_list[$node['net']] = format("{$node['word']}$notes", $node['tipe'], $node['stage']);
                            $lower_list[$node['low']] = format("<span onmouseover=\"showTip(root{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\"><sub><small>{$node['low']}</small></sub>{$node['lower']}</span>", $node['tipe'], $node['stage']);
                            $strong_list[$node['low']] = format("<a href=\"$location$site/strong.php?s={$node['strong']}\" onmouseover=\"showTip(def{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">&lt;{$node['strong']}&gt;</a>", $node['tipe'], $node['stage']);
                        }
                        else {
                            $merged_word[$node['net']] = format("{$node['word']}$notes", $node['tipe'], $node['stage']);
                            $merged_lower[$node['low']] = '';
                            $merged_strong[$node['low']] = '';
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
                        if ($word_out == '') $word_out = '&nbsp;';
                        if ($part == 'h') {
                            $merged_lower = array_reverse($merged_lower);
                            $merged_strong = array_reverse($merged_strong);
                        }
                        $lower_out = implode(' ', $merged_lower);
                        if ($lower_out == '') $lower_out = '&nbsp;';
                        $strong_out = implode(' ', $merged_strong);
                        if ($strong_out == '') $strong_out = '&nbsp;';
                        echo "<div class=\"unit\"><span class=\"fuzzy\">$word_out</span><br /><span class=\"fuzzy\"><span class=\"$part\">$lower_out</span><br />$strong_out</span></div>\n";
                        $merged_word = array();
                        $merged_lower = array();
                        $merged_strong = array();
                    }
                    $word_out = implode(' ', $word_list);
                    if ($word_out == '') $word_out = '&nbsp;';
                    if ($part == 'h') {
                        $lower_list = array_reverse($lower_list);
                        $strong_list = array_reverse($strong_list);
                    }
                    $lower_out = implode(' ', $lower_list);
                    if ($lower_out == '') $lower_out = '&nbsp;';
                    $strong_out = $split_before . implode(' ', $strong_list) . $split_after;
                    if ($strong_out == '') $strong_out = '&nbsp;';
                    if ($bad_link)
                        echo "<div class=\"bad unit\">$word_out<br /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
                    else
                        echo "<div class=\"unit\">$word_out<br /><span class=\"$part\">$lower_out</span><br />$strong_out</div>\n";
                }
            }
        }
        $low = $low_max[$id];
        for ($l = $low - 1; $l > 0; $l--) {
            if (array_key_exists($l, $empty_low))
                $low = $l;
            else
                break;
        }
        foreach ($empty_low as $low_key => $low_node) {
            if (($low_key >= $low) && (!$low_node['merged'])) {
                $merged_word[$low_node['net']] = '';
                $merged_lower[$low_node['low']] = format("<span onmouseover=\"showTip(root{$id}_{$low_node['low']},title{$id}_{$low_node['low']},200,200)\" onmouseout=\"UnTip()\"><sub><small>{$low_node['low']}</small></sub>{$low_node['lower']}</span>", $low_node['tipe'], $low_node['stage']);
                $merged_strong[$low_node['low']] = format("<a href=\"$location$site/strong.php?s={$low_node['strong']}\" onmouseover=\"showTip(def{$id}_{$low_node['low']},title{$id}_{$low_node['low']},200,200)\" onmouseout=\"UnTip()\">&lt;{$low_node['strong']}&gt;</a>", $low_node['tipe'], $low_node['stage']);
                $empty_low[$low_key]['merged'] = TRUE;
            }
        }
        if (count($merged_word) || count($merged_lower)) {
            $word_out = implode(' ', $merged_word);
            if ($word_out == '') $word_out = '&nbsp;';
            if ($part == 'h') {
                $merged_lower = array_reverse($merged_lower);
                $merged_strong = array_reverse($merged_strong);
            }
            $lower_out = implode(' ', $merged_lower);
            if ($lower_out == '') $lower_out = '&nbsp;';
            $strong_out = implode(' ', $merged_strong);
            if ($strong_out == '') $strong_out = '&nbsp;';
            echo "<div class=\"unit\"><span class=\"fuzzy\">$word_out</span><br /><span class=\"fuzzy\"><span class=\"$part\">$lower_out</span><br />$strong_out<span></div>\n";
        }
        $note_offset = $max_note;
    }
    else {
        $low = 0;
        $split_low = array();
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
                if (($node['net'] != 255) && ($node['net'] != 0)) {
                    $net = $node['net'];
                    for ($n = $net - 1; $n > 0; $n--) {
                        if (array_key_exists($n, $empty_net))
                            $net = $n;
                        else
                            break;
                    }
                    foreach ($empty_net as $net_key => $net_node) {
                        if (($net_key < $node['net']) && ($net_key >= $net) && (!$net_node['merged'])) {
                            if ($net_node['notes'] != '') {
                                $notes = explode(' ', $net_node['notes']);
                                $notes_array = array();
                                foreach ($notes as $note) {
                                    $n = $note + $note_offset;
                                    $notes_array[] = "<sup><small><a href=\"#\" onmouseover=\"showTip(note$n,'',200,200)\" onmouseout=\"UnTip()\">$n</a></small></sup>";
                                    $max_note = $n;
                                }
                                $notes = implode(' ', $notes_array);
                            }
                            else
                                $notes = '';
                            $merged_word[$net_node['net']] = format("<sub><small>{$net_node['net']}</small></sub>{$net_node['word']}$notes", $net_node['tipe'], $net_node['stage']);
                            $merged_lower[$net_node['low']] = '';
                            $merged_strong[$net_node['low']] = '';
                            $empty_net[$net_key]['merged'] = TRUE;
                        }
                    }
                }
                if ($node['low'] == $low + 1)
                    $low++;
                if ($node['low'] == $low) {
                    if ($node['notes'] != '') {
                        $notes = explode(' ', $node['notes']);
                        $notes_array = array();
                        foreach ($notes as $note) {
                            $n = $note + $note_offset;
                            $notes_array[] = "<sup><small><a href=\"#\" onmouseover=\"showTip(note$n,'',200,200)\" onmouseout=\"UnTip()\">$n</a></small></sup>";
                            $max_note = $n;
                        }
                        $notes = implode(' ', $notes_array);
                    }
                    else
                        $notes = '';
                    if (($node['net'] != 255) && ($node['net'] != 0)) {
                        $word_list[$node['net']] = format("<sub><small>{$node['net']}</small></sub>{$node['word']}$notes", $node['tipe'], $node['stage']);
                        $lower_list[$node['low']] = format("<span onmouseover=\"showTip(root{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">{$node['lower']}</span>", $node['tipe'], $node['stage']);
                        $strong_list[$node['low']] = format("<a href=\"$location$site/strong.php?s={$node['strong']}\" onmouseover=\"showTip(def{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">&lt;{$node['strong']}&gt;</a>", $node['tipe'], $node['stage']);
                    }
                    else {
                        $merged_word[$node['net']] = '';
                        $merged_lower[$node['low']] = format("<span onmouseover=\"showTip(root{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">{$node['lower']}</span>", $node['tipe'], $node['stage']);
                        $merged_strong[$node['low']] = format("<a href=\"$location$site/strong.php?s={$node['strong']}\" onmouseover=\"showTip(def{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">&lt;{$node['strong']}&gt;</a>", $node['tipe'], $node['stage']);
                    }
                }
                elseif ($node['low'] > $low) {
                    if (!array_key_exists($node['low'], $split_low))
                        $split_low[$node['low']] = $key;
                    $split_after = ' ==&gt;';
                }
                foreach ($error_list as $error) {
                    if (($node['low'] == $error['low']) && ($node['net'] == $error['net']))
                        $bad_link = TRUE;
                }
            }
            if (count($word_list)) {
                if (count($merged_word) || count($merged_lower)) {
                    $word_out = implode(' ', $merged_word);
                    if ($word_out == '') $word_out = '&nbsp;';
                    if ($part == 'h') {
                        $merged_lower = array_reverse($merged_lower);
                        $merged_strong = array_reverse($merged_strong);
                    }
                    $lower_out = implode(' ', $merged_lower);
                    if ($lower_out == '') $lower_out = '&nbsp;';
                    $strong_out = implode(' ', $merged_strong);
                    if ($strong_out == '') $strong_out = '&nbsp;';
                    if ($part == 'h')
                        echo "<div class=\"unithebrew\"><span class=\"fuzzy\"><span class=\"$part\">$lower_out</span><br />$strong_out</span><br /><span class=\"fuzzy\">$word_out</span></div>\n";
                    else
                        echo "<div class=\"unit\"><span class=\"fuzzy\"><span class=\"$part\">$lower_out</span><br />$strong_out</span><br /><span class=\"fuzzy\">$word_out</span></div>\n";
                    $merged_word = array();
                    $merged_lower = array();
                    $merged_strong = array();
                }
                $word_out = implode(' ', $word_list);
                if ($word_out == '') $word_out = '&nbsp;';
                if ($part == 'h') {
                    $lower_list = array_reverse($lower_list);
                    $strong_list = array_reverse($strong_list);
                }
                $lower_out = implode(' ', $lower_list);
                if ($lower_out == '') $lower_out = '&nbsp;';
                $strong_out = implode(' ', $strong_list) . $split_after;
                if ($strong_out == '') $strong_out = '&nbsp;';
                if ($bad_link) {
                    if ($part == 'h')
                        echo "<div class=\"bad unithebrew\"><span class=\"$part\">$lower_out</span><br />$strong_out<br />$word_out</div>\n";
                    else
                        echo "<div class=\"bad unit\"><span class=\"$part\">$lower_out</span><br />$strong_out<br />$word_out</div>\n";
                }
                else {
                    if ($part == 'h')
                        echo "<div class=\"unithebrew\"><span class=\"$part\">$lower_out</span><br />$strong_out<br />$word_out</div>\n";
                    else
                        echo "<div class=\"unit\"><span class=\"$part\">$lower_out</span><br />$strong_out<br />$word_out</div>\n";
                }
            }
            while (array_key_exists($low + 1, $split_low)) {
                $low++;
                $word_list = array();
                $lower_list = array();
                $strong_list = array();
                $split_before = '';
                $split_after = '';
                $bad_link = FALSE;
                foreach ($unit_list[$split_low[$low]]['node'] as $node) {
                    if (($node['net'] != 255) && ($node['net'] != 0)) {
                        $net = $node['net'];
                        for ($n = $net - 1; $n > 0; $n--) {
                            if (array_key_exists($n, $empty_net))
                                $net = $n;
                            else
                                break;
                        }
                        foreach ($empty_net as $net_key => $net_node) {
                            if (($net_key < $node['net']) && ($net_key >= $net) && (!$net_node['merged'])) {
                                if ($net_node['notes'] != '') {
                                    $notes = explode(' ', $net_node['notes']);
                                    $notes_array = array();
                                    foreach ($notes as $note) {
                                        $n = $note + $note_offset;
                                        $notes_array[] = "<sup><small><a href=\"#\" onmouseover=\"showTip(note$n,'',200,200)\" onmouseout=\"UnTip()\">$n</a></small></sup>";
                                        $max_note = $n;
                                    }
                                    $notes = implode(' ', $notes_array);
                                }
                                else
                                    $notes = '';
                                $merged_word[$net_node['net']] = format("<sub><small>{$net_node['net']}</small></sub>{$net_node['word']}$notes", $net_node['tipe'], $net_node['stage']);
                                $merged_lower[$net_node['low']] = '';
                                $merged_strong[$net_node['low']] = '';
                                $empty_net[$net_key]['merged'] = TRUE;
                            }
                        }
                    }
                    if ($node['low'] == $low + 1)
                        $low++;
                    if ($node['low'] == $low) {
                        if ($node['notes'] != '') {
                            $notes = explode(' ', $node['notes']);
                            $notes_array = array();
                            foreach ($notes as $note) {
                                $n = $note + $note_offset;
                                $notes_array[] = "<sup><small><a href=\"#\" onmouseover=\"showTip(note$n,'',200,200)\" onmouseout=\"UnTip()\">$n</a></small></sup>";
                                $max_note = $n;
                            }
                            $notes = implode(' ', $notes_array);
                        }
                        else
                            $notes = '';
                        $split_before = "&lt;== ";
                        if (($node['net'] != 255) && ($node['net'] != 0)) {
                            $word_list[$node['net']] = format("<sub><small>{$node['net']}</small></sub>{$node['word']}$notes", $node['tipe'], $node['stage']);
                            $lower_list[$node['low']] = format("<span onmouseover=\"showTip(root{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">{$node['lower']}</span>", $node['tipe'], $node['stage']);
                            $strong_list[$node['low']] = format("<a href=\"$location$site/strong.php?s={$node['strong']}\" onmouseover=\"showTip(def{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">&lt;{$node['strong']}&gt;</a>", $node['tipe'], $node['stage']);
                        }
                        else {
                            $merged_word[$node['net']] = '';
                            $merged_lower[$node['low']] = format("<span onmouseover=\"showTip(root{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">{$node['lower']}</span>", $node['tipe'], $node['stage']);
                            $merged_strong[$node['low']] = format("<a href=\"$location$site/strong.php?s={$node['strong']}\" onmouseover=\"showTip(def{$id}_{$node['low']},title{$id}_{$node['low']},200,200)\" onmouseout=\"UnTip()\">&lt;{$node['strong']}&gt;</a>", $node['tipe'], $node['stage']);
                        }
                    }
                    elseif ($node['low'] > $low) {
                        if (!array_key_exists($node['low'], $split_low))
                            $split_low[$node['low']] = $key;
                        $split_after = " ==&gt;";
                    }
                    foreach ($error_list as $error) {
                        if (($node['low'] == $error['low']) && ($node['net'] == $error['net']))
                            $bad_link = TRUE;
                    }
                }
                if (count($word_list)) {
                    if (count($merged_word) || count($merged_lower)) {
                        $word_out = implode(' ', $merged_word);
                        if ($word_out == '') $word_out = '&nbsp;';
                        if ($part == 'h') {
                            $merged_lower = array_reverse($merged_lower);
                            $merged_strong = array_reverse($merged_strong);
                        }
                        $lower_out = implode(' ', $merged_lower);
                        if ($lower_out == '') $lower_out = '&nbsp;';
                        $strong_out = implode(' ', $merged_strong);
                        if ($strong_out == '') $strong_out = '&nbsp;';
                        if ($part == 'h')
                            echo "<div class=\"unithebrew\"><span class=\"fuzzy\"><span class=\"$part\">$lower_out</span><br />$strong_out</span><br /><span class=\"fuzzy\">$word_out</span></div>\n";
                        else
                            echo "<div class=\"unit\"><span class=\"fuzzy\"><span class=\"$part\">$lower_out</span><br />$strong_out</span><br /><span class=\"fuzzy\">$word_out</span></div>\n";
                        $merged_word = array();
                        $merged_lower = array();
                        $merged_strong = array();
                    }
                    $word_out = implode(' ', $word_list);
                    if ($word_out == '') $word_out = '&nbsp;';
                    if ($part == 'h') {
                        $lower_list = array_reverse($lower_list);
                        $strong_list = array_reverse($strong_list);
                    }
                    $lower_out = implode(' ', $lower_list);
                    if ($lower_out == '') $lower_out = '&nbsp;';
                    $strong_out = $split_before . implode(' ', $strong_list) . $split_after;
                    if ($strong_out == '') $strong_out = '&nbsp;';
                    if ($bad_link) {
                        if ($part == 'h')
                            echo "<div class=\"bad unithebrew\"><span class=\"$part\">$lower_out</span><br />$strong_out<br />$word_out</div>\n";
                        else
                            echo "<div class=\"bad unit\"><span class=\"$part\">$lower_out</span><br />$strong_out<br />$word_out</div>\n";
                    }
                    else {
                        if ($part == 'h')
                            echo "<div class=\"unithebrew\"><span class=\"$part\">$lower_out</span><br />$strong_out<br />$word_out</div>\n";
                        else
                            echo "<div class=\"unit\"><span class=\"$part\">$lower_out</span><br />$strong_out<br />$word_out</div>\n";
                    }
                }
            }
        }
        $net = $net_max[$id];
        for ($n = $net - 1; $n > 0; $n--) {
            if (array_key_exists($n, $empty_net))
                $net = $n;
            else
                break;
        }
        foreach ($empty_net as $net_key => $net_node) {
            if (($net_key >= $net) && (!$net_node['merged'])) {
                if ($net_node['notes'] != '') {
                    $notes = explode(' ', $net_node['notes']);
                    $notes_array = array();
                    foreach ($notes as $note) {
                        $n = $note + $note_offset;
                        $notes_array[] = "<sup><small><a href=\"#\" onmouseover=\"showTip(note$n,'',200,200)\" onmouseout=\"UnTip()\">$n</a></small></sup>";
                        $max_note = $n;
                    }
                    $notes = implode(' ', $notes_array);
                }
                else
                    $notes = '';
                $merged_word[$net_node['net']] = format("<sub><small>{$net_node['net']}</small></sub>{$net_node['word']}$notes", $net_node['tipe'], $net_node['stage']);
                $merged_lower[$net_node['low']] = '';
                $merged_strong[$net_node['low']] = '';
                $empty_net[$net_key]['merged'] = TRUE;
            }
        }
        if (count($merged_word) || count($merged_lower)) {
            $word_out = implode(' ', $merged_word);
            if ($word_out == '') $word_out = '&nbsp;';
            if ($part == 'h') {
                $merged_lower = array_reverse($merged_lower);
                $merged_strong = array_reverse($merged_strong);
            }
            $lower_out = implode(' ', $merged_lower);
            if ($lower_out == '') $lower_out = '&nbsp;';
            $strong_out = implode(' ', $merged_strong);
            if ($strong_out == '') $strong_out = '&nbsp;';
            if ($part == 'h')
                echo "<div class=\"unithebrew\"><span class=\"fuzzy\"><span class=\"$part\">$lower_out</span><br />$strong_out<span><br /><span class=\"fuzzy\">$word_out</span></div>\n";
            else
                echo "<div class=\"unit\"><span class=\"fuzzy\"><span class=\"$part\">$lower_out</span><br />$strong_out<span><br /><span class=\"fuzzy\">$word_out</span></div>\n";
        }
        $note_offset = $max_note;
    }
}

echo "</div>\n";
echo "<script type=\"text/javascript\" language=\"javascript\" src=\"../include/wz_tooltip.js\"></script>\n";
//echo "</div>\n";
echo "</body>\n";
echo "</html>\n";

?>
