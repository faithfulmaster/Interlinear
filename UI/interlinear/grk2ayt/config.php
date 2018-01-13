<?php
include('../header_config.php');
include('../books.php');

$location = $location.'grk2ayt/';

$linkage_table = 'giza_linkage_grk2ayt';
//$pair_table = '';
$found_table = 'giza_phrase_grk2ayt';
$status_table = 'greek_status_ayt';

$target_table = 'pbayt_word';

$strong_page = 'strong.php';
$strong_proc_page = 'strong_proc.php';

$detail_page = 'detail_old.php';
$detail_proc_page = 'detail_proc.php';

$combo_page = 'combo.php';
$combo_proc_page = 'combo_proc.php';

$update_proc_page = 'update_proc.php';
$update_all_proc_page = 'update_all_proc.php';

$warna = array(0 => '', 1 => '#000', 2 => '#00F', 3 => '#0C0', 4 => '#F00', 253 => '#8C8', 255 => '#888');

$types = array(0 => '', 1 => '1', 2 => '2', 3 => '3', 4 => '4', 253 => '(-3)', 255 => '(-1)');

$stages = array(0 => 'UNCHECKED', 1 => 'CHECKED');

$index_count = 100;

?>
