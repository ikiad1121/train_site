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
        <title>
            <?php
                for($i = 0; $i < count($name1); $i++){
                    if($_GET['sta'] == $i){
                        echo $name1[$i].'駅';
                        $stanum = $i;
                    }
                }
            ?>｜山線電鉄
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
                            echo $name1[$i].'駅';
                            $stanum = $i;
                        }
                    }
                ?>
            </p>
            <div class="namesign">
                <table style="width:100%">
                    <tbody>
                        <?php
                            for($i = 0; $i < count($name1); $i++){
                                if($_GET['sta'] == $i){
                                    if(count(mb_str_split($name1[$i])) == 2){
                                        echo '<tr><td colspan="3"><p class="ns-main">'.implode("&nbsp;&nbsp;&nbsp;", mb_str_split($name1[$i])).'</p><p class="ns-sub">'.$name2[$i].' / '.$name3[$i].'</p></td></tr>';
                                        echo '<tr><td colspan="3" style="background-color:#3399ff; height:10px;"></td></tr><tr>';
                                    }else{
                                        echo '<tr><td colspan="3"><p class="ns-main">'.implode(" ", mb_str_split($name1[$i])).'</p><p class="ns-sub">'.$name2[$i].' / '.$name3[$i].'</p></td></tr>';
                                        echo '<tr><td colspan="3" style="background-color:#3399ff; height:10px;"></td></tr><tr>';
                                    }
                                    if(($i - 1) > -1){
                                        echo '<td><p class="ns-left"><a class="stations" href="station.php?sta='.($i - 1).'">'.implode(" ", mb_str_split($name1[$i - 1])).'</a><br><span>'.$name2[$i - 1].'</span></p></td><td></td>';
                                    }else{
                                        echo '<td></td><td></td>';
                                    }
                                    if(($i + 1) < 16){
                                        echo '<td><p class="ns-right"><a class="stations" href="station.php?sta='.($i + 1).'">'.implode(" ", mb_str_split($name1[$i + 1])).'</a><br><span>'.$name2[$i + 1].'</span></p></td>';
                                    }else{
                                        echo '<td></td>';
                                    }
                                    echo '</tr>';
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <p>
                <a id="btn1" class="btn" onclick="sce(this)" href="#">駅構内図</a>
                <a id="btn2" class="btn" onclick="sce(this)" href="#">時刻表</a>
                <script>
                    <?php
                        if($_GET['sta'] == 0){
                            echo '
                                function sce(e){
                                    if(e.id === "btn1"){
                                        document.getElementById("articles").innerHTML = `
                                            <p>2面2線</p>
                                        `;

                                        document.getElementById("btn1").classList.add("nobtn");
                                        document.getElementById("btn2").classList.add("btn");
                                        document.getElementById("btn1").classList.remove("btn");
                                        document.getElementById("btn2").classList.remove("nobtn");
                                    }else if(e.id === "btn2"){
                                        document.getElementById("articles").innerHTML = `
                                            <table class="frame">
                                                <tbody>
                                                    <tr>
                                                        <td class="f-down">
                                                            温泉方面
                                                        </td>
                                                        <td>
                                                            <a href="timetable.php?sta='.$stanum.'&dir=down&dayname=weekday">平日</a>｜
                                                            <a href="timetable.php?sta='.$stanum.'&dir=down&dayname=holiday">土休日</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        `;

                                        document.getElementById("btn1").classList.add("btn");
                                        document.getElementById("btn2").classList.add("nobtn");
                                        document.getElementById("btn1").classList.remove("nobtn");
                                        document.getElementById("btn2").classList.remove("btn");
                                    }
                                }
                            ';
                        }else if($_GET['sta'] == (count($name1) - 1)){
                            echo '
                                function sce(e){
                                    if(e.id === "btn1"){
                                        document.getElementById("articles").innerHTML = `
                                            <p>2面2線</p>
                                        `;

                                        document.getElementById("btn1").classList.add("nobtn");
                                        document.getElementById("btn2").classList.add("btn");
                                        document.getElementById("btn1").classList.remove("btn");
                                        document.getElementById("btn2").classList.remove("nobtn");
                                    }else if(e.id === "btn2"){
                                        document.getElementById("articles").innerHTML = `
                                            <table class="frame">
                                                <tbody>
                                                    <tr>
                                                        <td class="f-up">
                                                            都会方面
                                                        </td>
                                                        <td>
                                                            <a href="timetable.php?sta='.$stanum.'&dir=up&dayname=weekday">平日</a>｜
                                                            <a href="timetable.php?sta='.$stanum.'&dir=up&dayname=holiday">土休日</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        `;

                                        document.getElementById("btn1").classList.add("btn");
                                        document.getElementById("btn2").classList.add("nobtn");
                                        document.getElementById("btn1").classList.remove("nobtn");
                                        document.getElementById("btn2").classList.remove("btn");
                                    }
                                }
                            ';
                        }else{
                            echo '
                                function sce(e){
                                    if(e.id === "btn1"){
                                        document.getElementById("articles").innerHTML = `
                                            <p>2面2線</p>
                                        `;

                                        document.getElementById("btn1").classList.add("nobtn");
                                        document.getElementById("btn2").classList.add("btn");
                                        document.getElementById("btn1").classList.remove("btn");
                                        document.getElementById("btn2").classList.remove("nobtn");
                                    }else if(e.id === "btn2"){
                                        document.getElementById("articles").innerHTML = `
                                            <table class="frame">
                                                <tbody>
                                                    <tr>
                                                        <td class="f-up">
                                                            都会方面
                                                        </td>
                                                        <td>
                                                            <a href="timetable.php?sta='.$stanum.'&dir=up&dayname=weekday">平日</a>｜
                                                            <a href="timetable.php?sta='.$stanum.'&dir=up&dayname=holiday">土休日</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="f-down">
                                                            温泉方面
                                                        </td>
                                                        <td>
                                                            <a href="timetable.php?sta='.$stanum.'&dir=down&dayname=weekday">平日</a>｜
                                                            <a href="timetable.php?sta='.$stanum.'&dir=down&dayname=holiday">土休日</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        `;

                                        document.getElementById("btn1").classList.add("btn");
                                        document.getElementById("btn2").classList.add("nobtn");
                                        document.getElementById("btn1").classList.remove("nobtn");
                                        document.getElementById("btn2").classList.remove("btn");
                                    }
                                }
                            ';
                        }
                    ?>
                </script>
                <div id="articles">
                    <p>2面2線</p>
                </div>
            </p>
        </main>
        <footer></footer>
        <script src="js/script.js"></script>
    </body>
</html>