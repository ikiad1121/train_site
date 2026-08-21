<?php
    session_start();

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
        <title>山線電鉄</title>
    </head>
    <body>
        <nav></nav>
        <header></header>
        <main>
            <h1>山線</h1>
            <h2>路線図</h2>
            <table class="stations-table">
                <tbody>
                    <?php
                        echo '<tr><td class="stations-exp-top">◆</td>';
                        echo '<td class="stations-exp">急 行</td>';
                        for($i = 0; $i < count($name1); $i++){
                            if($i == 0 || $i == 6 || $i == 8 || $i == 11 || $i == 15){
                                echo '<td class="stations-exp">◆</td>';
                            }else{
                                echo '<td class="stations-exp-pass">◆</td>';
                            }
                        }
                        echo '<td class="stations-exp-bottom">◆</td></tr>';
                        echo '<tr><td class="stations-local-top">◆</td><td class="stations-local">普 通</td>';
                        for($i = 0; $i < count($name1); $i++){
                            echo '<td class="stations-local">◆</td>';
                        }
                        echo '<td class="stations-local-bottom">◆</td></tr>';
                        echo '<tr><td></td><td></td>';
                        for($i = 0; $i < count($name1); $i++){
                            echo '<td class="stations-name"><a class="stations" href="station.php?sta='.$i.'">'.$name1[$i].'</a></td>';
                        }
                        echo '<td></td></tr>';
                    ?>
                </tbody>
            </table>
            <h2>出発時刻・経路検索</h2>
            <form method="GET" action="php/search.php">
                <label for="depsta">出発駅：</label>
                <input type="text" id="depsta" name="depsta" class="form_large" />
                ⇒
                <label for="arista">到着駅：</label>
                <input type="text" id="arista" name="arista" class="form_large" />
                <br>
                <label for="times">日時：</label>
                <select id="year" name="year">
                    <option value="<?php echo (date('Y') - 1);?>"><?php echo (date('Y') - 1);?></option>
                    <option value="<?php echo (date('Y'));?>"><?php echo (date('Y'));?></option>
                    <option value="<?php echo (date('Y') + 1);?>"><?php echo (date('Y') + 1);?></option>
                </select>
                <label for="year">年</label>
                <select id="month" name="month">
                    <?php
                        for($dm = 1; $dm < 13; $dm++){
                            echo '<option value="'.$dm.'">'.$dm.'</option>';
                        }
                    ?>
                </select>
                <label for="month">月</label>
                <script>
                    document.getElementById("month").addEventListener('change', ()=>{
                        day.innerHTML = '';
                        switch(month.value){
                            case '2':
                                for(var dd = 1; dd < 29; dd++){
                                    document.getElementById("day").innerHTML += `<option value="${dd}">${dd}</option>`;
                                }
                                if(document.getElementById("year").value % 4 == 0){
                                    if(document.getElementById("year").value % 100 == 0){
                                        if(document.getElementById("year").value % 400 == 0){
                                            document.getElementById("day").innerHTML += `<option value="29">29</option>`;
                                        }
                                    }else{
                                        document.getElementById("day").innerHTML += `<option value="29">29</option>`;
                                    }
                                }
                                break;
                            case '4':
                            case '6':
                            case '9':
                            case '11':
                                for(var dd = 1; dd < 31; dd++){
                                    document.getElementById("day").innerHTML += `<option value="${dd}">${dd}</option>`;
                                }
                                break;
                            default:
                                for(var dd = 1; dd < 32; dd++){
                                    document.getElementById("day").innerHTML += `<option value="${dd}">${dd}</option>`;
                                }
                                break;
                        }
                    });
                </script>
                <select id="day" name="day">
                    <?php
                        for($dd = 1; $dd < 32; $dd++){
                            echo '<option value="'.$dd.'">'.$dd.'</option>';
                        }
                    ?>
                </select>
                <label for="day">日</label>
                <input type="time" id="times" name="times"/>
                &nbsp;｜&nbsp;
                <input type="radio" id="deptime" name="depari" value="deptime"/>
                <label for="deptime">出発</label>
                &nbsp;&nbsp;
                <input type="radio" id="aritime" name="depari" value="aritime"/>
                <label for="aritime">到着</label>
                <br>
                <?php 
                    $errbool_1 = false;
                    if(isset($_SESSION['sta'])){
                        if($_SESSION['sta'] == '1'){
                            echo '<span class="alert">出発地点と到着地点を入力してください。</span><br>';
                        }else if($_SESSION['sta'] == '2'){
                            echo '<span class="alert">出発地点を入力してください。</span><br>';
                        }else if($_SESSION['sta'] == '3'){
                            echo '<span class="alert">到着地点を入力してください。</span><br>';
                        }else if($_SESSION['sta'] == '4'){
                            echo '<span class="alert">出発地点と到着地点が一緒です。</span><br>';
                        }else{
                            $errbool_1 = true;
                        }
                    }
                    $_SESSION['sta'] = '0';
                
                    if(isset($_SESSION['inst'])){
                        if($_SESSION['inst'] == '1'){
                            echo '<span class="alert">日時は必須項目です。</span><br>';
                        }else if($_SESSION['inst'] == '2'){
                            echo '<span class="alert">時刻を入力してください。</span><br>';
                        }else if($_SESSION['inst'] == '3'){
                            echo '<span class="alert">出発か到着か選んでください。</span><br>';
                        }else{
                            if($errbool_1){
                                echo '<br>';
                            }
                        }
                    }else{
                        echo '<br>';
                    }
                    $_SESSION['inst'] = '0';
                ?>
                <button type="submit" id="btn">この内容で検索</button>
            </form>
            <p>
                ＞<a href="../index.html">ポートフォリオのページへ戻る</a>
            </p>
        </main>
        <footer></footer>
        <script src="js/script.js"></script>
    </body>
</html>