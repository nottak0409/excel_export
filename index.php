<?php
include_once 'common/config.php';
include_once 'excel_download.php';
use excel\excel_download;

$menu = [];
$err_result = [];

$sql = "SELECT * FROM directory_menus ORDER BY sort";
$stmt = $dbh->prepare($sql);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $menu[$row['id']] = $row;
}

function h($var)
{
    return htmlentities($var, ENT_QUOTES, 'utf-8');
}

if(!empty($_POST["excel_download"])) {
    $data = $_POST["data"];
    $err_result = _validation($data);
    if(empty($err_result)) {
        $sql = "SHOW TABLES FROM " . DB_MASTER_NAME;
        $db = $dbh->prepare($sql);
        $db->execute();
        while($row = $db->fetch(PDO::FETCH_ASSOC)) {
            $tables[] = $row;
        }
        

        //取得したテーブル名で、該当するテーブルの構造を読む
        foreach($tables as $soezi => $value) {
            $table_columns[$value["Tables_in_" . DB_MASTER_NAME]] = defneLabelAtColumns($dbh, $value["Tables_in_" . DB_MASTER_NAME]);
        }

        $excel_obj = new \excel\excel_download();
        $excel_obj->excel_write($table_columns, $data["file_name"]);
    }
}

function _validation($data) {
    $err_message = [];
    if(empty($data["file_name"])) {
        $err_message["file_name"] = "ファイル名を入力してください";
    }

    return $err_message;
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>TNK48-実験場</title>
    </head>
    <body>
        <form action="" method="post">
            <input type="text" name="data[file_name]" placeholder="拡張子を抜いたファイル名を入力" />
            <span style="color:red;"><?php if(!empty($err_result["file_name"])) { echo $err_result["file_name"]; } ?></span>
            <button name="excel_download" value="excel_download">エクセルダウンロードするで～</button>
        </form>
<?php if (empty($menu)) { ?>
        <div>メニューはありません</div>
<?php } else { ?>
        <table>
            <tr>
                <th>リンク先へ</th>
                <th>ソート順</th>
                <th>タイトル</th>
                <th>リンク先URL</th>
            </tr>
            <?php foreach ($menu as $idx => $val) { ?>
                <tr>
                    <td><a href="<?php echo h($val['link']); ?>" class="btn btn-primary">進む</a></td>
                    <td><input type="number" name="sort[<?php echo $idx; ?>]" value="<?php echo h($val['sort']); ?>"></td>
                    <td><input type="text" name="title[<?php echo $idx; ?>]" value="<?php echo h($val['title']); ?>"></td>
                    <td><input type="text" name="link[<?php echo $idx; ?>]" value="<?php echo h($val['link']); ?>"></td>
                </tr>
            <?php } ?>
        </table>
<?php } ?>
    </body>
</html>
