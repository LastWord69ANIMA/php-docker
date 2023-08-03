<?php
// DB接続設定
$dsn = 'mysql:dbname=*****;host=localhost';
$user = '*******';
$password = '*******';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//以下、最小要件実装編とはテーブル名が異なります。

//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS UserTable"
. " ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "password TEXT,"
. "log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
. ")";
$stmt = $pdo->query($sql);


// 3ヶ月前の日時を計算
$threeMonthsAgo = date("Y-m-d H:i:s", strtotime("-3 months"));


//以下が動作するか不明のため、調べる。
// 3ヶ月間ログインしていないユーザーを検索
$sql = 'SELECT * FROM UserTable WHERE log_date < :log_date';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':log_date', $threeMonthsAgo, PDO::PARAM_STR);
$stmt->execute();
$results = $stmt->fetchAll();

// ユーザーを削除
foreach ($results as $row) {
    $userId = $row['id'];
    // 削除処理のコードを追加
    
    try {
        //ユーザーテーブルから該当ユーザーを削除
        $deleteSql = 'DELETE FROM '.$DatabaseName.' WHERE id=:id';
        $deleteStmt = $pdo->prepare($deleteSql);
        $deleteStmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $deleteStmt->execute();
    } catch (PDOException $e) {
        // エラーメッセージの表示やエラーログの出力など、適切なエラーハンドリング処理
        echo 'ユーザー削除エラー: ' . $e->getMessage();
    }
}

?>
