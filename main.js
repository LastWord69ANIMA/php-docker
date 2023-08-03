function togglePassword() {
  var password = document.getElementsByClassName("pass")[0];
  if (password.type === "password") {
    password.type = "text";
  } else {
    password.type = "password";
  }
}

function showConfirmation() {
    alert("※画像・音声を投稿する際、コメントも付記して下さい。\n※画像・音声を投稿する際、利用規約にご留意ください。");
}

window.addEventListener('DOMContentLoaded', (event) => {
  // Ajaxリクエストを送信してエラーチェックを実行
  var xhr = new XMLHttpRequest();
  xhr.open('POST', 'error_check.php', true);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        if (xhr.responseText.trim() !== "") {
          alert(xhr.responseText);
        }
      } else {
        alert("エラーチェックに失敗しました。");
      }
    }
  }; // ここが修正された部分です
  xhr.send(); // リクエストの送信を追加
});
