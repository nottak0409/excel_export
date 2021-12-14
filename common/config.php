<?php
//
// 本番・開発切替： 本番false 開発true
//
define('APP_DEBUG', false);

//
// 文字コード
//
mb_language("japanese");
mb_internal_encoding("utf-8");
// mb_http_input("auto");
mb_http_output("utf-8");

//
// 環境定数・環境変数
//
include_once dirname(__FILE__) . "/../.env.php";

//
// マスタDB
//
include_once dirname(__FILE__) . "/db.php";// $dbh接続
