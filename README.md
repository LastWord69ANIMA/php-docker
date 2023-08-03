# Git-command-tutorial
他のリポジトリでは直接ファイルをアップしていましたが、ここではgit(vscode)からstageやcommitやpush等の操作を行い、アップしています。
※当リポジトリを作る際、既にある第一回要件定義のリポジトリをそのままコピーしたため、初めだけ7月で他は全部8月頃に変更されているように見えます。しかし、要件定義（1stとか2ndとかの区切り）に取り組んだ実際の時期は7月前半→後半→8月前半･･･ぐらいです。

#first requirement
第一回要件定義では（PHPとMysqlを用いて）「掲示板サイト」「ログイン画面（アカウント作成）」「アカウント自動削除」の3つを実装しています。

#second requirement
第二弾要件定義では新たに「掲示板サイトにて画像・音声などのファイル投稿」「CSSとjsによる装飾」「利用規約ページ」「スレッド作成機能」を実装しています。
ファイル投稿について、main.php(スレッドを作成するページ)にてスレッド名を入れてもらった後、フォルダ（ここではsampleやsample2）を作り、それをsitesへ格納しています。
フォルダ作成につき、Mission_6-1.phpのコードをコピーして掲示板作成（ファイル名を入力された奴に変更）＆ファイル投稿にて使うuploadsフォルダ作成（フォルダ名は入力された奴/uploads）という手順にて行います。
※ここではフォルダ作成に伴うuploadsが作成されていませんが、実際には問題なく作成されます。

#third requirement
第三弾要件定義では新たに「水先案内サイト（掲載してあるサイトは作成者様に許可を得ています）」「cssとjsによる装飾」「利用規約の改正」「本サイト以外の装飾」を実装しています。
この行程にてbranch作成・pullrequest・merge等を行っています。
※今後の予定はpull requestsをご覧下さい。
※次回にて、直接書き込んであるstyleとscriptをどうにかしておきます。
