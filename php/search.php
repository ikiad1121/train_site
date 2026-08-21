<?PHP
    session_start();
    $_SESSION['sta'] = '0';
    $_SESSION['inst'] = '0';

    $json_file_path = '../assets/api/stations.json'; // JSONファイルへのパス
    $json_string = file_get_contents($json_file_path);
    $json_string = mb_convert_encoding($json_string, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');// phpフォルダにあるphp.iniを開き、「extension=mbstring」のコメントアウトを外してください。
    $data = json_decode($json_string,true);

    $depstanum = -1;
    $aristanum = -1;

    for ($i = 0; $i < count($data); $i++) {
        if($data) {
            $name1[$i] = $data[$i]['name1'];
        }
    }

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        if(
            $_GET['times'] &&
            isset($_GET['depari'])
        ){
            $time = $_GET['times'];
            $depari = $_GET['depari'];

            $year = $_GET['year'];
            $month = $_GET['month'];
            $day = $_GET['day'];

            date_default_timezone_set('Asia/Tokyo');
            $date_string = $year.'-'.$month.'-'.$day; // 入力したい日付
            $timestamp = strtotime($date_string); // 文字列をタイムスタンプに変換
            $week_num = date('w', $timestamp);

            $time_h = explode(":", $time)[0] > 2 ? explode(":", $time)[0] : explode(":", $time)[0] + 24;
            $time_m = explode(":", $time)[1];
        }else{
            if(isset($_GET['depari'])){
                if(!$_GET['times']){
                    $_SESSION['inst'] = '2';
                }
            }else{
                if(!$_GET['times']){
                    $_SESSION['inst'] = '1';
                }else{
                    $_SESSION['inst'] = '3';
                }
            }
        }

        if($_GET['depsta'] && $_GET['arista']){
            if($_GET['depsta'] == $_GET['arista']){
                $_SESSION['sta'] = '4';
            }else{
                for ($i = 0; $i < count($name1); $i++) {
                    if($name1[$i] == $_GET['depsta']) {
                        $depstanum = $i;
                    }
                    if($name1[$i] == $_GET['arista']) {
                        $aristanum = $i;
                    }
                }
            }
        }else{
            if($_GET['depsta']){
                $_SESSION['sta'] = '3';
            }else if($_GET['arista']){
                $_SESSION['sta'] = '2';
            }else{
                $_SESSION['sta'] = '1';
            }
        }

        if(
            $_SESSION['sta'] != '0'||
            $_SESSION['inst'] != '0'
        ){
            header('location:form.php');
        }else{
            unset($_SESSION['sta']);
            unset($_SESSION['inst']);
        }
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="../css/styles.css" />
        <link rel="stylesheet" href="../css/search.css" />
        <title>
            <?php
                if($time_h > 23){
                    echo ($time_h - 24);
                }else{
                    echo ($time_h + 24 - 24);
                }
                echo ':'.$time_m;
                if($depari == 'deptime'){
                    echo '出発';
                }else if($depari == 'aritime'){
                    echo '到着';
                }
            ?>&nbsp;
            <?php echo htmlspecialchars($_GET['depsta']);?>駅から
            <?php echo htmlspecialchars($_GET['arista']);?>駅への
            検索結果｜山線電鉄
        </title>
    </head>
    <body>
        <nav></nav>
        <header></header>
        <main>
            <h1>時刻表検索</h1>
                <p>
                    <a href="../index.php">山線</a>&nbsp;>&nbsp;
                    <?php
                        if($time_h > 23){
                            echo ($time_h - 24);
                        }else{
                            echo ($time_h + 24 - 24);
                        }
                        echo ':'.$time_m;
                        if($depari == 'deptime'){
                            echo '出発';
                        }else if($depari == 'aritime'){
                            echo '到着';
                        }
                    ?>&nbsp;
                    <?php echo htmlspecialchars($_GET['depsta']);?>駅から
                    <?php echo htmlspecialchars($_GET['arista']);?>駅への
                    検索結果
                </p>
                <p>検索条件：
                    <?php
                        $stanum1 = 0;
                        if($time_h > 23){
                            echo ($time_h - 24);
                        }else{
                            echo ($time_h + 24 - 24);
                        }
                        echo ':'.$time_m;
                        if($depari == 'deptime'){
                            echo '出発';
                            $stanum1 = $depstanum;
                        }else if($depari == 'aritime'){
                            echo '到着';
                            $stanum1 = $aristanum;
                        }
                    ?>&nbsp;
                    <?php echo htmlspecialchars($_GET['depsta']);?>駅⇒
                    <?php echo htmlspecialchars($_GET['arista']);?>駅
                </p>
                <?php
                    //vote writers
                    $dataArr = Array();
                    $jsonf = Array();
                    $id_flug = true;

                    /*$reader = fopen('../API/data1.csv', 'r');
                    $reader = mb_convert_encoding($reader, 'UTF-8');*/
                    $staArrD = [
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
                        "sta16d"
                    ];
                    $staArrA = [
                        "sta1a",
                        "sta2a",
                        "sta3a",
                        "sta4a",
                        "sta5a",
                        "sta6a",
                        "sta7a",
                        "sta8a",
                        "sta9a",
                        "sta10a",
                        "sta11a",
                        "sta12a",
                        "sta13a",
                        "sta14a",
                        "sta15a",
                        "sta16a"
                    ];

                    if(
                        ($depstanum > -1) && 
                        ($aristanum > -1)
                    ){
                        if($depstanum < $aristanum){
                            if($week_num == 0){
                                $data = file_get_contents('../assets/api/diagram1h.csv');
                            }else if($week_num == 1){
                                if($time_h > 23){
                                    $data = file_get_contents('../assets/api/diagram1h.csv');
                                }else{
                                    $data = file_get_contents('../assets/api/diagram1w.csv');
                                }
                            }else if($week_num == 6){
                                if($time_h > 23){
                                    $data = file_get_contents('../assets/api/diagram1w.csv');
                                }else{
                                    $data = file_get_contents('../assets/api/diagram1h.csv');
                                }
                            }else{
                                $data = file_get_contents('../assets/api/diagram1w.csv');
                            }
                        }else{
                            if($week_num == 0){
                                $data = file_get_contents('../assets/api/diagram2h.csv');
                            }else if($week_num == 1){
                                if($time_h > 23){
                                    $data = file_get_contents('../assets/api/diagram2h.csv');
                                }else{
                                    $data = file_get_contents('../assets/api/diagram2w.csv');
                                }
                            }else if($week_num == 6){
                                if($time_h > 23){
                                    $data = file_get_contents('../assets/api/diagram2w.csv');
                                }else{
                                    $data = file_get_contents('../assets/api/diagram2h.csv');
                                }
                            }else{
                                $data = file_get_contents('../assets/api/diagram2w.csv');
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
                                            "hour" => 
                                                DateTime::createFromFormat('H:i:s', $data[$ii])->format('H') > 2 ? 
                                                DateTime::createFromFormat('H:i:s', $data[$ii])->format('H') : 
                                                DateTime::createFromFormat('H:i:s', $data[$ii])->format('H') + 24,
                                            "minute" => DateTime::createFromFormat('H:i:s', $data[$ii])->format('i'),
                                            "second" => DateTime::createFromFormat('H:i:s', $data[$ii])->format('s')
                                        ));
                                    }else{
                                        array_push($dataArr[($i - 1)],$data[$ii]);
                                    }
                                }

                                if($depstanum < $aristanum){
                                    array_push($jsonf, [
                                        "type" => $dataArr[($i - 1)][1],
                                        "arr" => $dataArr[($i - 1)][2],
                                        "dep" => $dataArr[($i - 1)][3],
                                        "sta1d" => $dataArr[($i - 1)][4],
                                        "sta2a" => $dataArr[($i - 1)][5],
                                        "sta2d" => $dataArr[($i - 1)][6],
                                        "sta3a" => $dataArr[($i - 1)][7],
                                        "sta3d" => $dataArr[($i - 1)][8],
                                        "sta4a" => $dataArr[($i - 1)][9],
                                        "sta4d" => $dataArr[($i - 1)][10],
                                        "sta5a" => $dataArr[($i - 1)][11],
                                        "sta5d" => $dataArr[($i - 1)][12],
                                        "sta6a" => $dataArr[($i - 1)][13],
                                        "sta6d" => $dataArr[($i - 1)][14],
                                        "sta7a" => $dataArr[($i - 1)][15],
                                        "sta7d" => $dataArr[($i - 1)][16],
                                        "sta8a" => $dataArr[($i - 1)][17],
                                        "sta8d" => $dataArr[($i - 1)][18],
                                        "sta9a" => $dataArr[($i - 1)][19],
                                        "sta9d" => $dataArr[($i - 1)][20],
                                        "sta10a" => $dataArr[($i - 1)][21],
                                        "sta10d" => $dataArr[($i - 1)][22],
                                        "sta11a" => $dataArr[($i - 1)][23],
                                        "sta11d" => $dataArr[($i - 1)][24],
                                        "sta12a" => $dataArr[($i - 1)][25],
                                        "sta12d" => $dataArr[($i - 1)][26],
                                        "sta13a" => $dataArr[($i - 1)][27],
                                        "sta13d" => $dataArr[($i - 1)][28],
                                        "sta14a" => $dataArr[($i - 1)][29],
                                        "sta14d" => $dataArr[($i - 1)][30],
                                        "sta15a" => $dataArr[($i - 1)][31],
                                        "sta15d" => $dataArr[($i - 1)][32],
                                        "sta16a" => $dataArr[($i - 1)][33]
                                    ]);
                                }else{
                                    array_push($jsonf, [
                                        "type" => $dataArr[($i - 1)][1],
                                        "arr" => $dataArr[($i - 1)][2],
                                        "dep" => $dataArr[($i - 1)][3],
                                        "sta16d" => $dataArr[($i - 1)][4],
                                        "sta15a" => $dataArr[($i - 1)][5],
                                        "sta15d" => $dataArr[($i - 1)][6],
                                        "sta14a" => $dataArr[($i - 1)][7],
                                        "sta14d" => $dataArr[($i - 1)][8],
                                        "sta13a" => $dataArr[($i - 1)][9],
                                        "sta13d" => $dataArr[($i - 1)][10],
                                        "sta12a" => $dataArr[($i - 1)][11],
                                        "sta12d" => $dataArr[($i - 1)][12],
                                        "sta11a" => $dataArr[($i - 1)][13],
                                        "sta11d" => $dataArr[($i - 1)][14],
                                        "sta10a" => $dataArr[($i - 1)][15],
                                        "sta10d" => $dataArr[($i - 1)][16],
                                        "sta9a" => $dataArr[($i - 1)][17],
                                        "sta9d" => $dataArr[($i - 1)][18],
                                        "sta8a" => $dataArr[($i - 1)][19],
                                        "sta8d" => $dataArr[($i - 1)][20],
                                        "sta7a" => $dataArr[($i - 1)][21],
                                        "sta7d" => $dataArr[($i - 1)][22],
                                        "sta6a" => $dataArr[($i - 1)][23],
                                        "sta6d" => $dataArr[($i - 1)][24],
                                        "sta5a" => $dataArr[($i - 1)][25],
                                        "sta5d" => $dataArr[($i - 1)][26],
                                        "sta4a" => $dataArr[($i - 1)][27],
                                        "sta4d" => $dataArr[($i - 1)][28],
                                        "sta3a" => $dataArr[($i - 1)][29],
                                        "sta3d" => $dataArr[($i - 1)][30],
                                        "sta2a" => $dataArr[($i - 1)][31],
                                        "sta2d" => $dataArr[($i - 1)][32],
                                        "sta1a" => $dataArr[($i - 1)][33]
                                    ]);
                                }
                            }
                        }
                        fclose($temp);
                        /*$time_h1 = '';
                        $time_m1 = '';
                        $time_h2 = '';
                        $time_m2 = '';*/

                        $time_arr = 0;
                        $time_h1 = Array();
                        $time_m1 = Array();
                        $time_h2 = Array();
                        $time_m2 = Array();

                        $type = Array();
                        $arr = Array();
                        $dep = Array();
                        $time_hits = false;

                        if($stanum1 == $depstanum){
                            for($ts = 0; $ts < count($jsonf); $ts++){
                                if(gettype($jsonf[$ts][$staArrD[$depstanum]]) == "array"){
                                    if($jsonf[$ts][$staArrD[$depstanum]]["hour"] == $time_h){
                                        if($jsonf[$ts][$staArrD[$depstanum]]["minute"] >= explode(":", $time)[1]){
                                            if(
                                                $jsonf[$ts][$staArrD[$depstanum]] &&
                                                $jsonf[$ts][$staArrA[$aristanum]]
                                            ){
                                                if($name1[$aristanum] == $jsonf[$ts]["arr"]){
                                                    $time_hits = true;
                                                }else{
                                                    if($jsonf[$ts][$staArrD[$aristanum]]){
                                                        $time_hits = true;
                                                    }
                                                }
                                            }
                                        }
                                    }else if($jsonf[$ts][$staArrD[$depstanum]]["hour"] > $time_h){
                                        if(
                                            $jsonf[$ts][$staArrD[$depstanum]]&&
                                            $jsonf[$ts][$staArrA[$aristanum]]
                                        ){
                                            if($name1[$aristanum] == $jsonf[$ts]["arr"]){
                                                $time_hits = true;
                                            }else{
                                                if($jsonf[$ts][$staArrD[$aristanum]]){
                                                    $time_hits = true;
                                                }
                                            }
                                        }
                                    }

                                    if($time_hits){
                                        $time_h1[$time_arr] = $jsonf[$ts][$staArrD[$depstanum]]["hour"];
                                        $time_m1[$time_arr] = $jsonf[$ts][$staArrD[$depstanum]]["minute"];
                                        $time_h2[$time_arr] = $jsonf[$ts][$staArrA[$aristanum]]["hour"];
                                        $time_m2[$time_arr] = $jsonf[$ts][$staArrA[$aristanum]]["minute"];

                                        $type[$time_arr] = $jsonf[$ts]["type"];
                                        $arr[$time_arr] = $jsonf[$ts]["arr"];
                                        $dep[$time_arr] = $jsonf[$ts]["dep"];
                                        $time_arr++;
                                        if($time_arr >= 6){
                                            break;
                                        }
                                        $time_hits = false;
                                    }
                                }
                            }
                        }else{
                            for($ts = (count($jsonf) - 1); $ts > -1; $ts--){
                                if(gettype($jsonf[$ts][$staArrA[$aristanum]]) == "array"){
                                    if($jsonf[$ts][$staArrA[$aristanum]]["hour"]){
                                        if(
                                            $jsonf[$ts][$staArrA[$aristanum]]["hour"] < $time_h
                                        ){
                                            if(
                                                $jsonf[$ts][$staArrD[$depstanum]]&&
                                                $jsonf[$ts][$staArrA[$aristanum]]
                                            ){
                                                if(
                                                    $name1[$aristanum] == $jsonf[$ts]["arr"]
                                                ){
                                                    $time_hits = true;
                                                }else{
                                                    if($jsonf[$ts][$staArrD[$aristanum]]){
                                                        $time_hits = true;
                                                    }
                                                }
                                            }
                                        }else if($jsonf[$ts][$staArrA[$aristanum]]["hour"] == $time_h){
                                            if($jsonf[$ts][$staArrA[$aristanum]]["minute"] <= explode(":", $time)[1]){
                                                if(
                                                    $jsonf[$ts][$staArrD[$depstanum]]&&
                                                    $jsonf[$ts][$staArrA[$aristanum]]
                                                ){
                                                    if(
                                                        $name1[$aristanum] == $jsonf[$ts]["arr"]
                                                    ){
                                                        $time_hits = true;
                                                    }else{
                                                        if($jsonf[$ts][$staArrD[$aristanum]]){
                                                            $time_hits = true;
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        if($time_hits){
                                            echo '<script>console.log("'.$name1[$depstanum].'：")</script>';
                                            echo '<script>console.log("'.$jsonf[$ts]["dep"].'：")</script>';
                                            echo '<script>console.log("'.$jsonf[$ts]["type"].'/'.$jsonf[$ts]["dep"].'/'.$jsonf[$ts]["arr"].'：")</script>';
                                            echo '<script>console.log("'.$jsonf[$ts][$staArrA[$aristanum]]["hour"].'：'.$jsonf[$ts][$staArrA[$aristanum]]["minute"].'")</script>';
                                            echo '<script>console.log("'.$jsonf[$ts][$staArrD[$depstanum]]["hour"].'：'.$jsonf[$ts][$staArrD[$depstanum]]["minute"].'")</script>';
                                            $time_h1[$time_arr] = $jsonf[$ts][$staArrD[$depstanum]]["hour"];
                                            $time_m1[$time_arr] = $jsonf[$ts][$staArrD[$depstanum]]["minute"];
                                            $time_h2[$time_arr] = $jsonf[$ts][$staArrA[$aristanum]]["hour"];
                                            $time_m2[$time_arr] = $jsonf[$ts][$staArrA[$aristanum]]["minute"];

                                            $type[$time_arr] = $jsonf[$ts]["type"];
                                            $arr[$time_arr] = $jsonf[$ts]["arr"];
                                            $dep[$time_arr] = $jsonf[$ts]["dep"];
                                            $time_arr++;
                                            if($time_arr >= 6){
                                                break;
                                            }
                                            $time_hits = false;
                                        }
                                    }
                                }
                            }
                        }
                    }
                ?>
                <?php
                    $hits = false;
                    if(
                        ($depstanum > -1) && 
                        ($aristanum > -1)
                    ){
                        /*if($time_hits){
                            $hits = true;
                        }*/
                        if(count($time_h1)){
                            $hits = true;
                        }/**/
                    }

                    if($hits){
                        if(isset($_GET['sorts'])){
                            $sort_bool = false;
                            for($tas = 0; $tas < count($time_h1); $tas++){
                                if($_GET['sorts'] == 'arisort'){
                                    if(isset($time_h2[$tas + 1])){
                                        if($time_h2[$tas + 1] < $time_h2[$tas]){
                                            $sort_bool = true;
                                        }else if($time_h2[$tas + 1] == $time_h2[$tas]){
                                            if($time_m2[$tas + 1] < $time_m2[$tas]){
                                                $sort_bool = true;
                                            }
                                        }
                                    }
                                }else{
                                    if(isset($time_h1[$tas + 1])){
                                        if($time_h1[$tas + 1] > $time_h1[$tas]){
                                            $sort_bool = true;
                                        }else if($time_h1[$tas + 1] == $time_h1[$tas]){
                                            if($time_m1[$tas + 1] > $time_m1[$tas]){
                                                $sort_bool = true;
                                            }
                                        }
                                    }
                                }

                                if($sort_bool){
                                    $sort1 = $time_h1[$tas + 1];
                                    $time_h1[$tas + 1] = $time_h1[$tas];
                                    $time_h1[$tas] = $sort1;
                                    $sort1 = $time_m1[$tas + 1];
                                    $time_m1[$tas + 1] = $time_m1[$tas];
                                    $time_m1[$tas] = $sort1;
                                    $sort1 = $time_h2[$tas + 1];
                                    $time_h2[$tas + 1] = $time_h2[$tas];
                                    $time_h2[$tas] = $sort1;
                                    $sort1 = $time_m2[$tas + 1];
                                    $time_m2[$tas + 1] = $time_m2[$tas];
                                    $time_m2[$tas] = $sort1;

                                    $sort2 = $type[$tas + 1];
                                    $type[$tas + 1] = $type[$tas];
                                    $type[$tas] = $sort2;
                                    $sort2 = $dep[$tas + 1];
                                    $dep[$tas + 1] = $dep[$tas];
                                    $dep[$tas] = $sort2;
                                    $sort2 = $arr[$tas + 1];
                                    $arr[$tas + 1] = $arr[$tas];
                                    $arr[$tas] = $sort2;

                                    $sort_bool = false;
                                }
                            }
                        }

                        for($tar = 0; $tar < count($time_h1); $tar++){
                            if($tar > 2){
                                break;
                            }

                            echo '<div class="headline">ルート'.($tar + 1).'：<span class="timeapp">';
                            if($time_h1[$tar] > 23){
                                echo ($time_h1[$tar] - 24);
                            }else{
                                echo ($time_h1[$tar] + 24 - 24);
                            }
                            echo ':'.$time_m1[$tar].'</span>発&nbsp;⇒&nbsp;<span class="timeapp">';
                            if($time_h2[$tar] > 23){
                                echo ($time_h2[$tar] - 24);
                            }else{
                                echo ($time_h2[$tar] + 24 - 24);
                            }
                            echo ':'.$time_m2[$tar].'</span>着</div><table style="width:90%;"><tbody><tr><td class="home" colspan="2"><span class="timeapp">';
                            if($time_h1[$tar] > 23){
                                echo ($time_h1[$tar] - 24);
                            }else{
                                echo ($time_h1[$tar] + 24 - 24);
                            }
                            echo ':'.$time_m1[$tar].'</span>発 <span class="staapp">'.$name1[$depstanum].'駅</span>
                            <a class="mini" href="../station.php?sta='.$depstanum.'">駅情報</a></td></tr><tr><td style="width:36px;">
                            <div class="allow"> </div>
                            </td><td><div class="train_fr">';
                            if($type[$tar] == '普通'){
                                echo '<span class="local">';
                            }else{
                                echo '<span class="express">';
                            }
                            echo $type[$tar].'</span> '.$arr[$tar].'行き<br>
                            所要時間：';
                            if($time_m2[$tar] < $time_m1[$tar]){
                                echo (($time_m2[$tar] + 60) - $time_m1[$tar]);
                            }else{
                                echo ($time_m2[$tar] - $time_m1[$tar]);
                            }
                            echo'分</div>
                            </td></tr><tr><td class="home" colspan="2"><span class="timeapp">';
                            if($time_h2[$tar] > 23){
                                echo ($time_h2[$tar] - 24);
                            }else{
                                echo ($time_h2[$tar] + 24 - 24);
                            }
                            echo ':'.$time_m2[$tar].'</span>着 <span class="staapp">'.$name1[$aristanum].'駅</span>
                            <a class="mini" href="../station.php?sta='.$aristanum.'">駅情報</a></td></tr></tbody></table><br>';
                        }
                        echo '<form method="GET" action="search.php">';
                        if(isset($_GET['sorts'])){
                            if($_GET['sorts'] == 'arisort'){
                                echo '<input type="radio" id="depsort" name="sorts" value="depsort" />';
                            }else{
                                echo '<input type="radio" id="depsort" name="sorts" value="depsort" checked />';
                            }
                        }else{
                            if($depari == '到着'){
                                echo '<input type="radio" id="depsort" name="sorts" value="depsort" />';
                            }else{
                                echo '<input type="radio" id="depsort" name="sorts" value="depsort" checked/>';
                            }
                        }
                        echo '<label for="depsort">出発順に並べ替える</label>
                                <br>';
                        if(isset($_GET['sorts'])){
                            if($_GET['sorts'] == 'arisort'){
                                echo '<input type="radio" id="depsort" name="sorts" value="depsort" checked />';
                            }else{
                                echo '<input type="radio" id="arisort" name="sorts" value="arisort" />';
                            }
                        }else{
                            if($depari == '到着'){
                                echo '<input type="radio" id="arisort" name="sorts" value="arisort" checked />';
                            }else{
                                echo '<input type="radio" id="arisort" name="sorts" value="arisort"/>';
                            }
                        }
                        echo '<label for="arisort">到着順に並べ替える</label>
                                <br>
                                <input type="hidden" id="depsta" name="depsta" class="form_large" value="'.htmlspecialchars($_GET['depsta']).'" />
                                <input type="hidden" id="arista" name="arista" class="form_large" value="'.htmlspecialchars($_GET['arista']).'" />
                                <input type="hidden" id="year" name="year" class="form_large" value="'.htmlspecialchars($_GET['year']).'" />
                                <input type="hidden" id="month" name="month" class="form_large" value="'.htmlspecialchars($_GET['month']).'" />
                                <input type="hidden" id="day" name="day" class="form_large" value="'.htmlspecialchars($_GET['day']).'" />
                                <input type="hidden" id="times" name="times" value="'.htmlspecialchars($_GET['times']).'" />
                                <input type="hidden" id="deptime" name="depari" value="'.htmlspecialchars($_GET['depari']).'"/>
                                <button type="submit" id="btn">この内容で再検索</button>
                            </form>';
                    }else{
                        echo '検索結果が見つかりませんでした。<br>
                        検索条件を変えてやり直してください。';
                    }
                ?>
            <p><a href="../index.php">メインページへ</a></p>
        </main>
        <footer></footer>
        <script src="../js/script.js"></script>
    </body>
</html>