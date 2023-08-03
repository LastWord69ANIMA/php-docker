<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['agreement-checkbox'])) {
    // agreement-checkboxにチェックが入っている場合、login.phpにリダイレクトする
    header('Location: login.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>利用規約</title>
  <link rel="stylesheet" type="text/css" href="term_of_use.css">
</head>
<body>
  <div class="container">
    <h1>利用規約</h1>
    <div class="content">
      <div class="scrollable-content">
        
      <h2>当掲示板サイトの利用に際して、以下のプライバシーポリシーをお読みいただき、同意していただく必要があります。本ポリシーは、利用者の個人情報の取り扱いに関する合意事項を定めるものです。</h2>
        <h3>1. 収集する情報</h3>
        <p>1.1 利用者が当掲示板サイトを利用する際、以下の情報が自動的に収集される場合があります。<br>
        - 利用者のIPアドレスおよびデバイス情報<br>
        - ブラウザの種類および言語設定<br>
        - アクセス日時および利用者の行動に関する情報</p>
        <h3>2. 情報の利用目的</h3>
        <p>2.1 収集された情報は、以下の目的のために利用される場合があります。<br>
        - 掲示板サイトの運営および管理<br>
        - 利用者へのサービス提供およびコンテンツのカスタマイズ<br>
        - 利用者への連絡や通知の提供</p>
        <h3>3. 情報の共有</h3>
        <p>3.1 私たちは、法律による要求や正当な権限のもとで必要とされる場合を除き、利用者の個人情報を第三者と共有しません。</p>
        <h3>4. クッキーの使用</h3>
        <p>4.1 当掲示板サイトでは、利用者の利便性向上やサイトの機能向上のために、クッキーを使用する場合があります。クッキーによって収集される情報は、匿名の形式で利用されます。</p>
        <h3>5. データの保護とセキュリティ</h3>
        <p>5.1 私たちは、利用者の個人情報を適切に保護するために、適切な技術的および組織的な措置を講じます。</p>
        <h3>6. 免責事項</h3>
        <p>6.1 私たちは、第三者による利用者の個人情報の不正アクセスや漏洩について一切の責任を負いません。</p>
        <h3>7. その他の規定</h3>
        <p>7.1 本プライバシーポリシーは、利用者と私たちとの間の完全な合意事項を構成し、以前の合意や取り決めに優先します。</p>
        <p>以上が、プライバシーポリシーとなります。ご利用にあたっては、本ポリシーを十分にご理解いただき、同意されたものとみなします。</p>



</p>
        <!-- 利用規約の長い内容を適切に配置 -->
      </div>
    </div>
    <div class="agreement">
      <form method="post">
        <input type="checkbox" id="agreement-checkbox" name="agreement-checkbox">
        <label for="agreement-checkbox">利用規約に同意する</label>
        <button type="submit" id="accept-button" name="accept-button">同意する</button>
      </form>
    </div>
  </div>
</body>
</html>