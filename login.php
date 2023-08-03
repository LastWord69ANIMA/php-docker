<?php
// DB接続設定
$dsn = 'mysql:dbname=******;host=localhost';
$user = '******';
$password = '******';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//以下、最小要件実装編とはテーブル名が異なります。

//テーブル作成
$DatabaseName = "UserTable";
$sql = "CREATE TABLE IF NOT EXISTS ".$DatabaseName
. " ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "password TEXT,"
. "log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
. ")";
$stmt = $pdo->query($sql);
    
    
    
    
    //新規登録フォーム
    if(!empty($_POST["NewUserName"])){
        $NewUserName = $_POST["NewUserName"];
    } 
    if(!empty($_POST["NewPassword"])){
        $NewPassword = $_POST["NewPassword"];
    }
    
    //入力内容を登録
    $add=false;
    if(!empty($NewUserName) && !empty($NewPassword)){
        $sql = $pdo->prepare("INSERT INTO ".$DatabaseName." (name, password) VALUES (:name, :password)");
        $sql->bindParam(':name', $name, PDO::PARAM_STR);
        
        //パスワードのハッシュ化
        $hashedPassword = password_hash($NewPassword, PASSWORD_DEFAULT);
        $sql->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        
        $name = $NewUserName;
        $password = $NewPassword;
        
        $sql -> execute();
        $add=true;
    }
    
    
    
    
    //ログインフォーム
    if(!empty($_POST["UserName"])){
        $UserName = $_POST["UserName"];
    } 
    if(!empty($_POST["Password"])){
        $Password = $_POST["Password"];
    }
    
    //パスワードが合っていたらページ遷移且つlog_dateを更新
    $success=false;
    if(!empty($UserName) && !empty($Password)){
            
        $sql = 'SELECT * FROM '.$DatabaseName.' WHERE name="'.$UserName.'"';
        $stmt = $pdo->prepare($sql);                  // 差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // その差し替えるパラメータの値を指定してから、
        $stmt->execute();                             // SQLを実行する。
        $results = $stmt->fetchAll();
        $CorrectPass = $results[0]['password'];
        
        if (password_verify($_POST["Password"], $CorrectPass) && ($results[0]['name'] == $_POST["UserName"])) {
            
            // ログイン成功時の処理
            $LoginDate = date("Y-m-d H:i:s");
            $sql = 'UPDATE '.$DatabaseName.' SET log_date=:log_date WHERE name=:name';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':log_date', $LoginDate, PDO::PARAM_STR);
            $stmt->bindParam(':name', $UserName, PDO::PARAM_STR);
            $stmt->execute();
            
            // ページ遷移
            header("Location: Mission_6-1.php"); 
            $success = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログイン画面</title>
    </head>
    <body>
        
        
        <form method="POST">
            <p>新規登録</p>
            <h4>※登録されたパスワードは本サイトでの投稿や削除にて必要となるので、ご自身で管理のほどお願いします。</h4>
            <input class="nameandpass" type="text" name="NewUserName" Placeholder='ユーザー名'><br>
            <input class='nameandpass' type='text' name='NewPassword' Placeholder='パスワード'><br>
            <button class='button' type='submit'>登録</button>
        </form>
        <?php
            if(!empty($NewUserName) && !empty($NewPassword) && $add==true){
                //echo "<h3>ユーザー名：".$NewUserName."、パスワード：".$NewPassword." を登録しました</h3>";
                echo "<h3>ユーザー名とパスワードを登録しました。</h3>";
            }
        ?>
        <br>
        
        
        
        <form method="POST">
            <p>ログイン</p>
            <input class="nameandpass" type="text" name="UserName" Placeholder='ユーザー名'><br>
            <input class='nameandpass' type='text' name='Password' Placeholder='パスワード'><br>
            <button class='button' type='submit'>ログイン</button>
        </form>
        <?php
            if(!empty($UserName) && !empty($Password) && $success==false){
                echo "<h3>ユーザー名またはパスワードが間違っています</h3>";
            }
        ?>
        
        
    </body>
</html>