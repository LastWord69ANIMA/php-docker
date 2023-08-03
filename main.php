<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>サイト名入力</title>
</head>
<body>
    <h1>掲示板サイト作成</h1>
    <form action="main_sub.php" method="post">
        <input type="text" name="site_name" placeholder="サイト名を入力してください" required>
        <input type="submit" value="作成">
    </form>
    
    <button onclick="location.href='main_sub.php'">スレッド一覧へ</button>
</body>
</html>
