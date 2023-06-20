<?php
// DB接続設定
$dsn = 'mysql:dbname=*******;host=localhost';
$user = '******';
$password = '******';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//以下、最小要件実装編とはテーブル名が異なります。

//テーブル作成
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

//テーブル作成
//$DatabaseName = "UserTable";
$sql = "CREATE TABLE IF NOT EXISTS UserTable"
. " ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "password TEXT,"
. "log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
. ")";
$stmt = $pdo->query($sql);

//以下、投稿フォーム（ファイルではなく、DBへ保存する）
if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])) {
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y年m月d日 H時i分s秒");
    $pass = $_POST["pass"];

    $sql = 'SELECT * FROM UserTable';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    foreach ($results as $row) {
    $CorrectPass = $row['password'];
    
    if (password_verify($pass, $CorrectPass)) {
        $sql = "INSERT INTO MainTable (name, comment, date, password) VALUES (:name, :comment, :date, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
        $stmt->execute();
    }
}
}
//以下、削除フォーム（ファイルではなく、DBへ干渉する）
//$_POST["del_pass"]がpassと一致した場合のみ削除
elseif(!empty( $_POST["delete"] )&& !empty( $_POST["del_pass"] ) ){
    
    $delete = $_POST["delete"];
    $del_pass = $_POST["del_pass"];
    
    //以下、passがあっていれば実行
    //おそらく、M4-の奴を使って、$row[4]　を取得し、パスを参照する？
    $sql = 'SELECT * FROM MainTable';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
            if(  $row['password'] == $del_pass){
            
                if($row['id'] == $delete){
                    
                    $sql = 'delete from MainTable where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
                    $stmt->execute();
                    
                }
            }
    }
}

//以下、編集フォーム（編集対象番号から、元のデータを取得）
if(!empty( $_POST["edit"] )&& !empty( $_POST["edit_pass"] ) ){
    
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




//ファイルではなく、DBを出力する。
    $sql = "SELECT * FROM MainTable";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].' - ';
        echo $row['name'].', '.'<br>';
        echo nl2br($row['comment']).'  '.'<br>';
        //echo $row['password'].', ';
        echo $row['date'].'<br>';
    echo "<hr>";
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS読み込み -->
    <link rel="stylesheet" href="m6-1.css" />
    


    <title>Main</title>
</head>
<body>
    <h1 class="chapter">Mission_6-1</h1>
    
    <form action="" method="post">
        <input type="text" name="name"  placeholder="名前"><br>
        <textarea type="text" name="comment"  placeholder="コメント"></textarea><br>
        <input type="text" name="pass" placeholder="ログイン時のパスワード"><br><br>
    <input type="submit" name="submit">
    </form>
    
    <form action="" method="post">    
        <input type="number" name="delete"  placeholder="削除対象番号"><br>
        <input type="text" name="del_pass" placeholder="パスワード:必須"><br><br>
    <input type="submit" name="submit">
    </form>
    
    <form method="post" action="">
        <input type="number" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="edit_pass" placeholder="パスワード:必須"><br><br>
        <input type="submit" name="submit">
    </form>
    
     <form method="post" action="">
        <?php if (isset($edit_num)) { echo '<input type="number" name="edit_num" value="'.$edit_num.'">'; } ?><br>
        <?php if (isset($edit_name)) { echo '<input type="text" name="edit_name" value="'.$edit_name.'">'; } ?><br>
        <?php if (isset($edit_comment)) { echo '<textarea type="text" name="edit_comment" value="'.$edit_comment.'"></textarea>'; } ?><br>
        <br>
        <?php if (isset($edit_num)) { echo '<input type="submit" name="submit">'; } ?>
     </form> 

</body>
</html>