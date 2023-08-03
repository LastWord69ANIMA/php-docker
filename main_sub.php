<?php
if (isset($_POST['site_name'])) {
    $siteName = $_POST['site_name'];
    
    //既存のサイト名を取得し、配列として格納
    $siteNames = array_map('trim', file('site_name.txt', FILE_IGNORE_NEW_LINES));
    
    if (!in_array($siteName, $siteNames)) {
    file_put_contents('site_name.txt', $siteName . PHP_EOL, FILE_APPEND | LOCK_EX);

    // 掲示板サイトを作成
    $siteDirectory = 'sites/' . $siteName;
    $uploadsDirectory = $siteDirectory . '/uploads/';
        if (!file_exists($siteDirectory)) {
            mkdir($siteDirectory, 0777, true);
            mkdir('sites/' . $siteName . '/uploads/', 0777, true);
    
            // Mission_6-1.phpのコピー先ファイルを修正する
            $indexFile = $siteDirectory . '/index.php';
            $indexContent = file_get_contents('Mission_6-1.php');
            $indexContent = str_replace('Mission_6-1', $siteName, $indexContent);
            $indexContent = str_replace('MainTable', $siteName . 'Table', $indexContent);
            
            //$indexContent = str_replace('uploads', $uploadsDirectory, $indexContent);
            
            $indexContent = str_replace("main.css", '../../main.css', $indexContent);
            $indexContent = str_replace("main.js", '../../main.js', $indexContent);
            $indexContent = str_replace('main.php', '../../main.php', $indexContent);
            $indexContent = str_replace('main_sub.php', '../../main_sub.php', $indexContent);
            file_put_contents($indexFile, $indexContent);
        }
    }
}

// サイト名を取得して配列に格納
$siteNames = array_map('trim', file('site_name.txt', FILE_IGNORE_NEW_LINES));

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="welcom_site/welcome_site.css">
    <title>掲示板サイト一覧</title>
</head>
<body>
    <h1>掲示板サイト作成一覧</h1>
    <?php if (!empty($siteNames)) : ?>
        <p>以下の掲示板サイトが作成されています。</p>
        <ul>
            <?php foreach ($siteNames as $siteName) : ?>
                <?php if ($siteName !== '') : ?>
                    <li>
                        <p>サイト名: <?php echo $siteName; ?></p>
                        <p><a href="sites/<?php echo $siteName; ?>/index.php">作成した掲示板サイトへ移動する</a></p>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>作成する掲示板サイトがありません。</p>
    <?php endif; ?>
    
    
    <footer>
    <button onclick="location.href='main.php'">スレッド作成へ</button>
    <button onclick="location.href='Mission_6-1.php'">本サイトへ</button>
    <button onclick="location.href='welcom_site/welcom_site.php'">水先案内サイトへ</button>
    </footer>
    
</body>
</html>
