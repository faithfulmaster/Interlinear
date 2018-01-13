<?php

require('config.php');

$strong = $_POST['h_strong'];

$link = mysql_connect($host, $name, $pass);
mysql_selectdb($db);

mysql_query("LOCK TABLES $linkage_table WRITE;");
foreach ($_POST as $key => $value) {
    switch ($key[0]) {
        case 'c':
            switch ($key[1]) {
                case '1':
                    if ($value == 'on') {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $ayat = (int)($link / 10000);
                            $wh = (int)(($link % 10000) / 100);
                            $net = (int)(($link % 10000) % 100);
//                            $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                            if ($row = mysql_fetch_assoc($result)) {
//                                $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET stage = 1 WHERE ayat = $ayat AND wh = $wh AND net = $net;");
//                            }
                        }
                    }
                    break;
                case '2':
                    if ($value == 'on') {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $l = explode('|', $link);
                            $link = array_shift($l);
                            $ayat = (int)($link / 10000);
                            $wh = (int)(($link % 10000) / 100);
                            $net = (int)(($link % 10000) % 100);
//                            $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                            if ($row = mysql_fetch_assoc($result)) {
//                                $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET stage = 1 WHERE ayat = $ayat AND wh = $wh AND net = $net;");
                                foreach ($l as $net)
                                    mysql_query("UPDATE $linkage_table SET stage = 1 WHERE ayat = $ayat AND wh = $wh AND net = $net;");
//                            }
                        }
                    }
                    break;
                case '3':
                    if ($value == 'on') {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $ayat = (int)($link / 100);
                            $wh = (int)($link % 100);
//                            $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                            if ($row = mysql_fetch_assoc($result)) {
//                                $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET stage = 1 WHERE ayat = $ayat AND wh = $wh AND net = 0;");
//                            }
                        }
                    }
                    break;
                case '4':
                    if ($value == 'on') {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $ayat = (int)($link / 100);
                            $wh = (int)($link % 100);
//                            $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                            if ($row = mysql_fetch_assoc($result)) {
//                                $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET stage = 1 WHERE ayat = $ayat AND wh = $wh AND net = 255;");
//                            }
                        }
                    }
                    break;
                case '5':
                    if ($value == 'on') {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $ayat = (int)($link / 100);
                            $wh = (int)($link % 100);
//                            $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                            if ($row = mysql_fetch_assoc($result)) {
//                                $pair = $row['pair'];
                                mysql_query("INSERT INTO $linkage_table VALUES ($ayat, 255, NULL, $wh, $strong, 0, 0);");
//                            }
                        }
                    }
                    break;
            }
            break;
        case 'u':
            switch ($key[1]) {
                case '1':
                    if ($value == 'on') {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $ayat = (int)($link / 10000);
                            $wh = (int)(($link % 10000) / 100);
                            $net = (int)(($link % 10000) % 100);
//                            $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                            if ($row = mysql_fetch_assoc($result)) {
//                                $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET stage = 0 WHERE ayat = $ayat AND wh = $wh AND net = $net;");
//                            }
                        }
                    }
                    break;
                case '2':
                    if ($value == 'on') {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $l = explode('|', $link);
                            $link = array_shift($l);
                            $ayat = (int)($link / 10000);
                            $wh = (int)(($link % 10000) / 100);
                            $net = (int)(($link % 10000) % 100);
//                            $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                            if ($row = mysql_fetch_assoc($result)) {
//                                $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET stage = 0 WHERE ayat = $ayat AND wh = $wh AND net = $net;");
                                foreach ($l as $net)
                                    mysql_query("UPDATE $linkage_table SET stage = 0 WHERE ayat = $ayat AND wh = $wh AND net = $net;");
//                            }
                        }
                    }
                    break;
                case '3':
                    if ($value == 'on') {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $ayat = (int)($link / 100);
                            $wh = (int)($link % 100);
//                            $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                            if ($row = mysql_fetch_assoc($result)) {
//                                $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET stage = 0 WHERE ayat = $ayat AND wh = $wh AND net = 0;");
//                            }
                        }
                    }
                    break;
                case '4':
                    if ($value == 'on') {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $ayat = (int)($link / 100);
                            $wh = (int)($link % 100);
//                            $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                            if ($row = mysql_fetch_assoc($result)) {
//                                $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET stage = 0 WHERE ayat = $ayat AND wh = $wh AND net = 255;");
//                            }
                        }
                    }
                    break;
            }
            break;
        case 's':
            switch ($key[1]) {
                case '1':
                    if ($value != 0) {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $ayat = (int)($link / 10000);
                            $wh = (int)(($link % 10000) / 100);
                            $net = (int)(($link % 10000) % 100);
//                        $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                        if ($row = mysql_fetch_assoc($result)) {
//                            $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET tipe = $value WHERE ayat = $ayat AND wh = $wh AND net = $net;");
//                        }
                        }
                    }
                    break;
                case '2':
                    if ($value != 0) {
                        $links = explode('_', substr($key, 3));
                        foreach ($links as $link) {
                            $l = explode('|', $link);
                            $link = array_shift($l);
                            $ayat = (int)($link / 10000);
                            $wh = (int)(($link % 10000) / 100);
                            $net = (int)(($link % 10000) % 100);
//                        $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                        if ($row = mysql_fetch_assoc($result)) {
//                            $pair = $row['pair'];
                                mysql_query("UPDATE $linkage_table SET tipe = $value WHERE ayat = $ayat AND wh = $wh AND net = $net;");
                                foreach ($l as $net)
                                    mysql_query("UPDATE $linkage_table SET tipe = $value WHERE ayat = $ayat AND wh = $wh AND net = $net;");
//                        }
                        }
                    }
                    break;
                case '3':
                    if ($value == 0)
                        $kata = '""';
                    else
                        $kata = 'NULL';
                    $links = explode('_', substr($key, 3));
                    foreach ($links as $link) {
                        $ayat = (int)($link / 100);
                        $wh = (int)($link % 100);
//                        $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                        if ($row = mysql_fetch_assoc($result)) {
//                            $pair = $row['pair'];
                            mysql_query("UPDATE $linkage_table SET net = $value, kata = $kata WHERE ayat = $ayat AND wh = $wh AND net = 0;");
//                        }
                    }
                    break;
                case '4':
                    if ($value == 0)
                        $kata = '""';
                    else
                        $kata = 'NULL';
                    $links = explode('_', substr($key, 3));
                    foreach ($links as $link) {
                        $ayat = (int)($link / 100);
                        $wh = (int)($link % 100);
//                        $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                        if ($row = mysql_fetch_assoc($result)) {
//                            $pair = $row['pair'];
                            mysql_query("UPDATE $linkage_table SET net = $value, kata = $kata WHERE ayat = $ayat AND wh = $wh AND net = 255;");
//                        }
                    }
                    break;
            }
            break;
        case 'd':
            switch ($key[1]) {
                case '1':
                    $links = explode('_', substr($key, 3));
                    foreach ($links as $link) {
                        $ayat = (int)($link / 10000);
                        $wh = (int)(($link % 10000) / 100);
                        $net = (int)(($link % 10000) % 100);
//                        $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                        if ($row = mysql_fetch_assoc($result)) {
//                            $pair = $row['pair'];
                            $result2 = mysql_query("SELECT kata FROM $linkage_table WHERE ayat = $ayat AND wh = $wh AND net = $net;");
                            if ($row2 = mysql_fetch_assoc($result2)) {
                                $kata = $row2['kata'];
                                mysql_query("DELETE FROM $linkage_table WHERE ayat = $ayat AND wh = $wh AND net = $net;");
                                $result2 = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $ayat AND wh = $wh;");
                                if (!mysql_num_rows($result2))
                                    mysql_query("INSERT INTO $linkage_table VALUES ($ayat, 255, NULL, $wh, $strong, 0, 0);");
                                $result2 = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $ayat AND net = $net;");
                                if (!mysql_num_rows($result2))
                                    mysql_query("INSERT INTO $linkage_table VALUES ($ayat, $net, \"$kata\", 255, NULL, 0, 0);");
                            }
//                        }
                    }
                    break;
                case '2':
                    $links = explode('_', substr($key, 3));
                    foreach ($links as $link) {
                        $l = explode('|', $link);
                        $link = array_shift($l);
                        $ayat = (int)($link / 10000);
                        $wh = (int)(($link % 10000) / 100);
                        $net = (int)(($link % 10000) % 100);
//                        $result = mysql_query("SELECT pair FROM $pair_table WHERE ayat = $ayat;");
//                        if ($row = mysql_fetch_assoc($result)) {
//                            $pair = $row['pair'];
                            $result2 = mysql_query("SELECT kata FROM $linkage_table WHERE ayat = $ayat AND wh = $wh AND net = $net;");
                            if ($row2 = mysql_fetch_assoc($result2)) {
                                $kata = $row2['kata'];
                                mysql_query("DELETE FROM $linkage_table WHERE ayat = $ayat AND wh = $wh AND net = $net;");
                                $result2 = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $ayat AND wh = $wh;");
                                if (!mysql_num_rows($result2))
                                    mysql_query("INSERT INTO $linkage_table VALUES ($ayat, 255, NULL, $wh, $strong, 0, 0);");
                                $result2 = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $ayat AND net = $net;");
                                if (!mysql_num_rows($result2))
                                    mysql_query("INSERT INTO $linkage_table VALUES ($ayat, $net, \"$kata\", 255, NULL, 0, 0);");
                            }
                            foreach ($l as $net) {
                                $result2 = mysql_query("SELECT kata FROM $linkage_table WHERE ayat = $ayat AND wh = $wh AND net = $net;");
                                if ($row2 = mysql_fetch_assoc($result2)) {
                                    $kata = $row2['kata'];
                                    mysql_query("DELETE FROM $linkage_table WHERE ayat = $ayat AND wh = $wh AND net = $net;");
                                    $result2 = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $ayat AND wh = $wh;");
                                    if (!mysql_num_rows($result2))
                                        mysql_query("INSERT INTO $linkage_table VALUES ($ayat, 255, NULL, $wh, $strong, 0, 0);");
                                    $result2 = mysql_query("SELECT * FROM $linkage_table WHERE ayat = $ayat AND net = $net;");
                                    if (!mysql_num_rows($result2))
                                        mysql_query("INSERT INTO $linkage_table VALUES ($ayat, $net, \"$kata\", 255, NULL, 0, 0);");
                                }
                            }
//                        }
                    }
                    break;
            }
            break;
    }
}
mysql_query("UNLOCK TABLES;");

header("Location: $location$strong_page?s=$strong");

?>
