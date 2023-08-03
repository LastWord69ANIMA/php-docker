<?php
// DB接続設定
$dsn = 'mysql:dbname=*******;host=localhost';
$user = '********';
$password = '*******';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成(掲示板画面)
$sql = "CREATE TABLE IF NOT EXISTS  MainTable"
. " ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32) ,"
. "num_comment INT ,"
. "comment TEXT ,"
. "date TEXT ,"
. "password TEXT"
. ");";
$stmt = $pdo->query($sql);

$sql = "ALTER TABLE MainTable "
."ADD image_file TEXT, "
."ADD audio_file TEXT";
$stmt = $pdo->query($sql);


//テーブル作成(ログイン画面)
$sql = "CREATE TABLE IF NOT EXISTS UserTable"
. " ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "password TEXT,"
. "log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
. ")";
$stmt = $pdo->query($sql);

// 以下、投稿フォーム
if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])) {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y年m月d日 H時i分s秒");
    
    $pass = $_POST["pass"];
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    $image_path = null;  // 画像ファイルのパスを初期化
    $audio_path = null;  // 音声ファイルのパスを初期化

    // 画像ファイルが選択されている場合は一時保存先から指定のディレクトリに移動
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        $image_path = 'uploads/' . $image['name'];
        move_uploaded_file($image['tmp_name'], $image_path);
    }

    // 音声ファイルが選択されている場合は一時保存先から指定のディレクトリに移動
    if ($_FILES['audio']['error'] === UPLOAD_ERR_OK) {
        $audio = $_FILES['audio'];
        $audio_path = 'uploads/' . $audio['name'];
        move_uploaded_file($audio['tmp_name'], $audio_path);
    }

    // 以下のデータベースへの挿入処理を実行する部分に移動するため、if文の外に移動させます。

    $sql = 'SELECT * FROM UserTable';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    foreach ($results as $row) {
        $CorrectPass = $row['password'];
    
        if (password_verify($pass, $CorrectPass)) {
            $sql = "INSERT INTO MainTable (name, comment, image_file, audio_file, date, password) VALUES (:name, :comment, :image, :audio, :date, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':image', $image_path, PDO::PARAM_STR);
            $stmt->bindParam(':audio', $audio_path, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}

//以下、削除フォーム
//$_POST["del_pass"]がpassと一致した場合のみ削除
elseif(!empty($_POST["delete"]) && !empty($_POST["del_pass"])){
    $delete = $_POST["delete"];
    $del_pass = $_POST["del_pass"];
    
    //以下、passがあっていれば実行
    $sql = 'SELECT * FROM MainTable WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
    
    if($row && password_verify($del_pass, $row['password'])){
        $sql = 'DELETE FROM MainTable WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        echo "パスワードが正しくありません。削除できません。";
    }
}

//以下、編集フォーム（編集対象番号から、元のデータを取得）
elseif(!empty( $_POST["edit"] )&& !empty( $_POST["edit_pass"] ) ){
    
    $edit = $_POST["edit"];
    $edit_pass = $_POST["edit_pass"];


    //以下、passがあっていれば実行
    $sql = 'SELECT * FROM MainTable';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    
        //編集対象番号と入力した番号が同じ場合、その番号行を取得
        if($row['password'] == $_POST["edit_pass"]){
    
            if ($row['id'] == $_POST["edit"]) {
                $edit_num = $row['id'];
                $edit_name = $row['name'];
                $edit_comment = $row['comment'];
                $edit_pass = $row['password'];
            }
        }
    }
}
else{
    if(!empty($_POST["name"]) or !empty($_POST["comment"]) or !empty($_POST["pass"]) 
    or !empty($_POST["delete"]) or !empty($_POST["del_pass"])
    or !empty( $_POST["edit"] ) or !empty( $_POST["edit_pass"] ) ){
        
        echo"入力エラーです。"."<br><br>";
    }
}

//以下、編集フォーム（取得後に、元のデータを差し替える）
if( !empty( $_POST["edit_num"] )&& !empty( $_POST["edit_name"] )&& !empty( $_POST["edit_comment"] ) ){
    
    $edit_new_num = $_POST["edit_num"];
    $edit_new_name = $_POST["edit_name"];
    $edit_new_comment = $_POST["edit_comment"];
    $date = date("Y年m月d日 H時i分s秒");
    
    $sql = 'UPDATE MainTable SET name=:name,comment=:comment  WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $edit_new_name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $edit_new_comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $edit_new_num, PDO::PARAM_INT);
    
    $stmt->execute();

}




//以下、投稿データの表示
    $sql = "SELECT * FROM MainTable";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        // テキストデータの表示
        echo $row['id'].' - ';
        echo $row['name'].', '.'<br>';
        echo nl2br($row['comment']).'  '.'<br>';
        echo $row['date'].'<br>';
    
        // 画像ファイルの表示
        if (isset($row['image_file'])) {
            echo '<img src="'.$row['image_file'].'" alt="画像ファイルがありません" class="size-image"><br>';
        }
    
        // 音声ファイルの表示
        if (!empty($row['audio_file'])) {
            echo '<audio controls>';
            echo '<source src="'.$row['audio_file'].'" type="audio/mpeg">';
            echo 'Your browser does not support the audio element.';
            echo '</audio><br>';
        }
    
        echo "<hr>";
    }
    echo "<hr>";



?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS読み込み -->
    <link rel="stylesheet" href="main.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    


    <title>Mission_6-1</title>
</head>
<body>
<div class="container">
    <h1 class="chapter">Mission_6-1</h1>
    <p>※画像・音声を投稿する際、コメントも付記して下さい。</p>
    <p>※画像・音声を投稿する際、利用規約にご留意ください。</p>
</div>

    <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
    <form action="" method="post" enctype="multipart/form-data">
    <input id="name" type="text" name="name" placeholder="名前" required><br>
    
    <textarea id="cooment"　type="text" name="comment" placeholder="コメント(必須)" required autocorrect="on" wrap="hard" cols="25"></textarea><br><br> 
    
    <label for="image" class="filelabel">画像ファイル選択</label>
    <input type="file" name="image" id="image" class="fileinput"><br><br> 
    
    <label for="audio" class="filelabel">音声ファイル選択</label>
    <input type="file" name="audio" id="audio" class="fileinput"><br><br>  
    
    <input type="password" name="pass" placeholder="ログイン時のパスワード" class="pass"><br><br>
    <input type="submit" name="submit" class="btn_passview" onclick="togglePassword()">
    </form>
    </div>

    <div class="d-flex justify-content-center align-items-center" style="height: 20vh;">
    <form action="" method="post">    
        <input type="number" name="delete"  placeholder="削除対象番号"><br>
        <input type="text" name="del_pass" placeholder="パスワード:必須" class="pass"><br><br>
    <input type="submit" name="submit" class="btn_passview" onclick="togglePassword()">
    </form>
    </div>

    <div class="d-flex justify-content-center align-items-center" style="height: 20vh;">
    <form method="post" action="">
        <input type="number" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="edit_pass" placeholder="パスワード:必須" class="pass"><br><br>
        <input type="submit" name="submit" class="btn_passview" onclick="togglePassword()">
    </form>
    </div>

    <div class="d-flex justify-content-center align-items-center" style="height: 10vh;">
    <form method="post" action="">
        <?php if (isset($edit_num)) { echo '<input type="number" name="edit_num" value="'.$edit_num.'">'; } ?><br>
        <?php if (isset($edit_name)) { echo '<input type="text" name="edit_name" value="'.$edit_name.'">'; } ?><br>
        <?php if (isset($edit_comment)) { echo '<textarea type="text" name="edit_comment" value="'.$edit_comment.'"></textarea>'; } ?><br>
        <br>
        <?php if (isset($edit_num)) { echo '<input type="submit" name="submit">'; } ?>
    </form> 
</div>
    <script src="main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <button onclick="location.href='main.php'">スレッド作成へ</button>
    <button onclick="location.href='main_sub.php'">スレッド一覧へ</button>
        
</body>
</html>

