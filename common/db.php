<?php
/***************************************/
// マスタDB接続
/***************************************/
function connect(){
	$con = null;
	try {
		$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_MASTER_NAME.';charset='.DB_CHARSET;
		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_STRINGIFY_FETCHES => false
		);
		$con = new PDO($dsn, DB_MASTER_USER, DB_MASTER_PASSWD, $options);
	} catch (Exception $e) {
		echo $e->GetMessage();
		$logger = new Logger();
		$logger->log($e);
		exit;
	}
	return $con;
}

$dbh = null;
if (!defined('DB_HOST') ||
    !defined('DB_CHARSET') ||
    !defined('DB_MASTER_USER') ||
    !defined('DB_MASTER_PASSWD') ||
    !defined('DB_MASTER_NAME')) {
    echo "データベース接続情報が不足しています。";
    exit;
} else {

    // PDO接続処理
    $dbh = connect();

}

function defneLabelAtColumns($dbh,$table,$SpecialColumns = []){

    $ret = [];

    $sql_c     = 'SHOW FULL COLUMNS FROM '.$table;
    $stmt      = $dbh->query($sql_c);
    $Columns   = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //foreach($Columns as $index => $column_info){
    //    if(!isset($SpecialColumns[$column_info['Field']])){
    //        $ret[$column_info['Field']] = $column_info['Comment'];
    //    }else{
    //        $ret[$column_info['Field']] = $SpecialColumns[$column_info['Field']];
    //    }
    //}

    return $Columns;

}
