<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="welcom_site/welcome_site.css">
    <title>サイト名入力</title>
</head>
<body>
    <h1>掲示板サイト作成</h1>
    <form action="main_sub.php" method="post">
        <input type="text" name="site_name" placeholder="サイト名を入力してください" required>
        <input type="submit" value="作成">
    </form>
    
    <footer>
    <button onclick="location.href='Mission_6-1.php'">本サイトへ</button>
    <button onclick="location.href='main_sub.php'">スレッド一覧へ</button>
    <button onclick="location.href='welcom_site/welcom_site.php'">水先案内サイトへ</button>
    </footer>
</body>
</html>
