<?php
    $json_file_path = 'assets/api/stations.json'; // JSONファイルへのパス
    $json_string = file_get_contents($json_file_path);
    $json_string = mb_convert_encoding($json_string, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $data = json_decode($json_string,true);

    for ($i = 0; $i < count($data); $i++) {
        if($data) {
            $name1[$i] = $data[$i]['name1'];
            $name2[$i] = $data[$i]['name2'];
            $name3[$i] = $data[$i]['name3'];
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="css/styles.css" />
        <link rel="stylesheet" href="css/namesign.css" />
        <link rel="stylesheet" href="css/timetable.css" />
        <title>
            <?php
                for($i = 0; $i < count($name1); $i++){
                    if($_GET['sta'] == $i){
                        echo $name1[$i].'駅';
                        $stanum = $i;
                    }
                }
            ?>&nbsp;<?php
                if($_GET['dir'] == 'down'){
                    if($_GET['sta'] < 8){
                        echo '町中・温泉方面';
                    }else{
                        echo '温泉方面';
                    }
                }else if($_GET['dir'] == 'up'){
                    if($_GET['sta'] < 9){
                        echo '都会方面';
                    }else{
                        echo '町中・都会方面';
                    }
                }
            ?>&nbsp;<?php
                if($_GET['dayname'] == 'weekday'){
                    echo '平日';
                }else if($_GET['dayname'] == 'holiday'){
                    echo '土曜・休日';
                }
            ?>&nbsp;時刻表｜山線電鉄
        </title>
    </head>
    <body>
        <nav></nav>
        <header></header>
        <main>
            <p>
                <a href="index.php">山線</a>&nbsp;>&nbsp;<?php
                    for($i = 0; $i < count($name1); $i++){
                        if($_GET['sta'] == $i){
                            echo '<a href="station.php?sta='.$i.'">'.$name1[$i].'駅</a>';
                        }
                    }
                ?>&nbsp;>&nbsp;時刻表<?php
                    if($_GET['dir'] == 'down'){
                        if($_GET['sta'] < 8){
                            echo '(町中・温泉方面';
                        }else{
                            echo '(温泉方面';
                        }
                    }else if($_GET['dir'] == 'up'){
                        if($_GET['sta'] < 9){
                            echo '(都会方面';
                        }else{
                            echo '(町中・都会方面';
                        }
                    }
                ?>&nbsp;<?php
                if($_GET['dayname'] == 'weekday'){
                    echo '平日)';
                }else if($_GET['dayname'] == 'holiday'){
                    echo '土曜・休日)';
                }
            ?></p>
            <p>
                <?php
                    //vote writers
                    $dataArr = Array();
                    $jsonf = Array();
                    $id_flug = true;

                    if($_GET['dir'] == 'down'){
                        $staArr = [
                            "sta1d",
                            "sta2d",
                            "sta3d",
                            "sta4d",
                            "sta5d",
                            "sta6d",
                            "sta7d",
                            "sta8d",
                            "sta9d",
                            "sta10d",
                            "sta11d",
                            "sta12d",
                            "sta13d",
                            "sta14d",
                            "sta15d",
                            "sta16a"
                        ];

                        if($_GET['dayname'] == 'weekday'){
                            $data = file_get_contents('assets/api/diagram1w.csv');
                        }else if($_GET['dayname'] == 'holiday'){
                            $data = file_get_contents('assets/api/diagram1h.csv');
                        }
                    }else if($_GET['dir'] == 'up'){
                        $staArr = [
                            "sta1a",
                            "sta2d",
                            "sta3d",
                            "sta4d",
                            "sta5d",
                            "sta6d",
                            "sta7d",
                            "sta8d",
                            "sta9d",
                            "sta10d",
                            "sta11d",
                            "sta12d",
                            "sta13d",
                            "sta14d",
                            "sta15d",
                            "sta16d"
                        ];
                        if($_GET['dayname'] == 'weekday'){
                            $data = file_get_contents('assets/api/diagram2w.csv');
                        }else if($_GET['dayname'] == 'holiday'){
                            $data = file_get_contents('assets/api/diagram2h.csv');
                        }
                    }
                    $temp = tmpfile();

                    fwrite($temp, $data);
                    rewind($temp);

                    $i = 0;
                    while (($data = fgetcsv($temp, 0, ",")) !== FALSE) {
                        $i = $i + 1;
                        if($i > 0){
                            array_push($dataArr,array());
                            for($ii = 0; $ii < 34; $ii++){
                                if (DateTime::createFromFormat('H:i:s', $data[$ii])) {
                                    array_push($dataArr[($i - 1)],array(
                                        "hour" => DateTime::createFromFormat('H:i:s', $data[$ii])->format('H'),
                                        "minute" => DateTime::createFromFormat('H:i:s', $data[$ii])->format('i'),
                                        "second" => DateTime::createFromFormat('H:i:s', $data[$ii])->format('s')
                                    ));
                                }else{
                                    array_push($dataArr[($i - 1)],$data[$ii]);
                                }
                            }
                            if($_GET['dir'] == 'down'){
                                array_push($jsonf, [
                                    "type" => $dataArr[($i - 1)][1],
                                    "arr" => $dataArr[($i - 1)][2],
                                    "dep" => $dataArr[($i - 1)][3],
                                    "sta1d" => $dataArr[($i - 1)][4],
                                    "sta2d" => $dataArr[($i - 1)][6],
                                    "sta3d" => $dataArr[($i - 1)][8],
                                    "sta4d" => $dataArr[($i - 1)][10],
                                    "sta5d" => $dataArr[($i - 1)][12],
                                    "sta6d" => $dataArr[($i - 1)][14],
                                    "sta7d" => $dataArr[($i - 1)][16],
                                    "sta8d" => $dataArr[($i - 1)][18],
                                    "sta9d" => $dataArr[($i - 1)][20],
                                    "sta10d" => $dataArr[($i - 1)][22],
                                    "sta11d" => $dataArr[($i - 1)][24],
                                    "sta12d" => $dataArr[($i - 1)][26],
                                    "sta13d" => $dataArr[($i - 1)][28],
                                    "sta14d" => $dataArr[($i - 1)][30],
                                    "sta15d" => $dataArr[($i - 1)][32],
                                    "sta16a" => $dataArr[($i - 1)][33]
                                ]);
                            }else if($_GET['dir'] == 'up'){
                                array_push($jsonf, [
                                    "type" => $dataArr[($i - 1)][1],
                                    "arr" => $dataArr[($i - 1)][2],
                                    "dep" => $dataArr[($i - 1)][3],
                                    "sta16d" => $dataArr[($i - 1)][4],
                                    "sta15d" => $dataArr[($i - 1)][6],
                                    "sta14d" => $dataArr[($i - 1)][8],
                                    "sta13d" => $dataArr[($i - 1)][10],
                                    "sta12d" => $dataArr[($i - 1)][12],
                                    "sta11d" => $dataArr[($i - 1)][14],
                                    "sta10d" => $dataArr[($i - 1)][16],
                                    "sta9d" => $dataArr[($i - 1)][18],
                                    "sta8d" => $dataArr[($i - 1)][20],
                                    "sta7d" => $dataArr[($i - 1)][22],
                                    "sta6d" => $dataArr[($i - 1)][24],
                                    "sta5d" => $dataArr[($i - 1)][26],
                                    "sta4d" => $dataArr[($i - 1)][28],
                                    "sta3d" => $dataArr[($i - 1)][30],
                                    "sta2d" => $dataArr[($i - 1)][32],
                                    "sta1a" => $dataArr[($i - 1)][33]
                                ]);
                            }
                        }
                    }
                    fclose($temp);
                ?>
                <table class="timetable">
                    <tbody>
                        <tr>
                            <td colspan="2" class="<?php
                                if($_GET['dayname'] == 'weekday'){
                                    echo 'weekdays';
                                }else if($_GET['dayname'] == 'holiday'){
                                    echo 'holidays';
                                }
                            ?>">
                                山線&nbsp;
                                <?php
                                    if($_GET['dir'] == 'down'){
                                        if($_GET['sta'] < 8){
                                            echo '町中・温泉方面';
                                        }else{
                                            echo '温泉方面';
                                        }
                                    }else if($_GET['dir'] == 'up'){
                                        if($_GET['sta'] < 9){
                                            echo '都会方面';
                                        }else{
                                            echo '町中・都会方面';
                                        }
                                    }
                                ?>&nbsp;<?php
                                    if($_GET['dayname'] == 'weekday'){
                                        echo '平日';
                                    }else if($_GET['dayname'] == 'holiday'){
                                        echo '土曜・休日';
                                    }
                                ?>時刻表
                            </td>
                        </tr>
                        <?php
                            for($t = 5; $t < 24; $t++){
                                if(($t % 2) == 1){
                                    echo '<tr class="odd"><td class="times">'.$t.'</td><td><span class="arrs"> </span>';
                                }else{
                                    echo '<tr class="even"><td class="times">'.$t.'</td><td><span class="arrs"> </span>';
                                }
                                for($tt = 0; $tt < count($jsonf); $tt++){
                                    if(gettype($jsonf[$tt][$staArr[$_GET['sta']]]) == "array"){
                                        if($jsonf[$tt][$staArr[$_GET['sta']]]["hour"] == $t){
                                            if($jsonf[$tt][$staArr[$_GET['sta']]]["minute"] != ""){
                                                if($jsonf[$tt]['type'] == '急行'){
                                                    echo '<span class="exp">'.intval($jsonf[$tt][$staArr[$_GET['sta']]]["minute"]).'</span>';//
                                                }else if($jsonf[$tt]['type'] == '通勤急行'){
                                                    echo '<span class="commuter-exp">'.intval($jsonf[$tt][$staArr[$_GET['sta']]]["minute"]).'</span>';//
                                                }else{
                                                    echo '<span class="lcl">'.intval($jsonf[$tt][$staArr[$_GET['sta']]]["minute"]).'</span>';//
                                                }
                                                if($jsonf[$tt]['arr'] == '学園都市'){
                                                    echo '<span class="arrs">学</span>';//
                                                }else if($jsonf[$tt]['arr'] == '町中'){
                                                    echo '<span class="arrs">町</span>';//
                                                }else if($jsonf[$tt]['arr'] == '神社前'){
                                                    echo '<span class="arrs">神</span>';//
                                                }else{
                                                    echo '<span class="arrs"> </span>';//
                                                }
                                            }
                                        }
                                    }
                                }/**/
                                echo '</td></tr>';
                            }
                            for($t = 0; $t < 1; $t++){
                                if(($t % 2) == 1){
                                    echo '<tr class="odd"><td class="times">'.$t.'</td><td><span class="arrs"> </span>';
                                }else{
                                    echo '<tr class="even"><td class="times">'.$t.'</td><td><span class="arrs"> </span>';
                                }
                                for($tt = 0; $tt < count($jsonf); $tt++){
                                    if(gettype($jsonf[$tt][$staArr[$_GET['sta']]]) == "array"){
                                        if($jsonf[$tt][$staArr[$_GET['sta']]]["hour"] == $t){
                                            if($jsonf[$tt][$staArr[$_GET['sta']]]["minute"] != ""){
                                                if($jsonf[$tt]['type'] == '急行'){
                                                    echo '<span class="exp">'.intval($jsonf[$tt][$staArr[$_GET['sta']]]["minute"]).'</span>';//
                                                }else if($jsonf[$tt]['type'] == '通勤急行'){
                                                    echo '<span class="commuter-exp">'.intval($jsonf[$tt][$staArr[$_GET['sta']]]["minute"]).'</span>';//
                                                }else{
                                                    echo '<span class="lcl">'.intval($jsonf[$tt][$staArr[$_GET['sta']]]["minute"]).'</span>';//
                                                }
                                                if($jsonf[$tt]['arr'] == '学園都市'){
                                                    echo '<span class="arrs">学</span>';//
                                                }else if($jsonf[$tt]['arr'] == '町中'){
                                                    echo '<span class="arrs">町</span>';//
                                                }else if($jsonf[$tt]['arr'] == '神社前'){
                                                    echo '<span class="arrs">神</span>';//
                                                }else{
                                                    echo '<span class="arrs"> </span>';//
                                                }
                                            }
                                        }
                                    }
                                }/**/
                                echo '</td></tr>';
                            }
                                    
                            echo '<tr class="remarks"><td colspan="2" style="border:1px solid #000000"><span class="arrs"> </span><span class="lcl">00</span>：普通<br><span class="arrs"> </span>';
                            for($tt = 0; $tt < count($jsonf); $tt++){
                                if(gettype($jsonf[$tt][$staArr[$_GET['sta']]]) == "array"){
                                    if($jsonf[$tt]['type'] == '通勤急行'){
                                        if($jsonf[$tt][$staArr[$_GET['sta']]]["minute"] != ""){
                                            echo '<span class="commuter-exp">00</span>：通勤急行';
                                            if($_GET['dir'] == 'down'){
                                                if($_GET['sta'] < 6){
                                                    echo '(停車駅：学園都市、町中～温泉の各駅)';
                                                }else if($_GET['sta'] < 8){
                                                    echo '(停車駅：町中～温泉の各駅)';
                                                }else{
                                                    echo '(各駅に止まります)';
                                                }
                                            }else if($_GET['dir'] == 'up'){
                                                if($_GET['sta'] < 7){
                                                    echo '(停車駅：終点まで止まりません)';
                                                }else if($_GET['sta'] < 9){
                                                    echo '(停車駅：学園都市)';
                                                }else if($_GET['sta'] < 10){
                                                    echo '(停車駅：町中、学園都市)';
                                                }else{
                                                    echo '(停車駅：町中までの各駅、学園都市)';
                                                }
                                            }
                                            echo '<span class="arrs"> </span><br><span class="arrs"> </span>';
                                            break;
                                        }
                                    }
                                }
                            }
                            for($tt = 0; $tt < count($jsonf); $tt++){
                                if(gettype($jsonf[$tt][$staArr[$_GET['sta']]]) == "array"){
                                    if($jsonf[$tt]['type'] == '急行'){
                                        if($jsonf[$tt][$staArr[$_GET['sta']]]["minute"] != ""){
                                            echo '<span class="exp">00</span>：急行';
                                            if($_GET['dir'] == 'down'){
                                                if($_GET['sta'] < 6){
                                                    echo '(停車駅：学園都市、町中、神社前)';
                                                }else if($_GET['sta'] < 8){
                                                    echo '(停車駅：町中、神社前)';
                                                }else if($_GET['sta'] < 11){
                                                    echo '(停車駅：神社前)';
                                                }else{
                                                    echo '(停車駅：終点まで止まりません)';
                                                }
                                            }else if($_GET['dir'] == 'up'){
                                                if($_GET['sta'] < 7){
                                                    echo '(停車駅：終点まで止まりません)';
                                                }else if($_GET['sta'] < 9){
                                                    echo '(停車駅：学園都市)';
                                                }else if($_GET['sta'] < 12){
                                                    echo '(停車駅：町中、学園都市)';
                                                }else{
                                                    echo '(停車駅：神社前、町中、学園都市)';
                                                }
                                            }
                                            echo '<br><span class="arrs"> </span>';
                                            break;
                                        }
                                    }
                                }
                            }
                            echo '<br><span class="arrs"> </span>';
                            if($_GET['dir'] == 'down'){
                                if($_GET['sta'] < 11){
                                    echo '無印：温泉行き<br><span class="arrs"> </span>神印：神社前行き';
                                    if($_GET['sta'] < 8){
                                        echo '<br><span class="arrs"> </span>町印：町中行き';
                                        if($_GET['sta'] < 6){
                                            echo '<br><span class="arrs"> </span>学印：学園都市行き';
                                        }
                                    }
                                }else{
                                    echo 'すべて温泉行き';
                                }
                            }else if($_GET['dir'] == 'up'){
                                if($_GET['sta'] < 7){
                                    echo 'すべて都会行き';
                                }else{
                                    echo '無印：都会行き';
                                    if($_GET['sta'] > 6){
                                        echo '<br><span class="arrs"> </span>学印：学園都市行き';
                                        if($_GET['sta'] > 8){
                                            echo '<br><span class="arrs"> </span>町印：町中行き';
                                        }
                                    }
                                }
                            }
                            echo '<span class="arrs"> </span></td></tr>';
                            
                        ?>
                    </tbody>
                </table>
            </p>
        </main>
        <footer></footer>
        <script src="js/script.js"></script>
    </body>
</html>