<?php
$menu = [];

function h($var)
{
    return htmlentities($var, ENT_QUOTES, 'utf-8');
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>TNK48-実験場</title>
    </head>
    <body>
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
                    <th><input type="number" name="sort[<?php echo $idx; ?>]" value="<?php echo h($val['sort']); ?>"></th>
                    <th><input type="text" name="title[<?php echo $idx; ?>]" value="<?php echo h($val['title']); ?>"></th>
                    <th><input type="text" name="link[<?php echo $idx; ?>]" value="<?php echo h($val['link']); ?>"></th>
                </tr>
            <?php } ?>
        </table>
<?php } ?>
    </body>
</html>
