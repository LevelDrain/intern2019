<?php
require 'functions.php';

$db = db_connect();
$threads = $db->query('SELECT * FROM contact;');

session_start();
if($_SESSION){
    $error_message = $_SESSION['errors']['manage'] ?? [];
    $success_message = $_SESSION['errors']['manage'] ?? [];
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>お問い合わせ一覧</title>
    <style>
        .txt-center {
            text-align: center;
        }
    </style>
</head>
<body>
<main>
    <h2>お問い合わせ一覧</h2>
    <?php if (isset($success_message) && count($success_message)): ?>
        <div class="success">
            <?php if (isset($success_message) && count($success_message)): ?>
                <?php foreach ($success_message as $text):?>
                    <p><?= $text ?></p>
                <?php endforeach;?>
            <?php endif?>
        </div>
    <?php endif ?>
    <?php if (isset($error_message) && count($error_message)): ?>
        <div class="errors">
            <p>エラーが発生しました。</p>
            <?php if (isset($error_message) && count($error_message)): ?>
                <?php foreach ($error_message as $text):?>
                    <p><?= $text ?></p>
                <?php endforeach;?>
            <?php endif?>
        </div>
    <?php endif ?>
    <table class="txt-center">
        <tr>
            <?php foreach ($db_columns as $column):?>
                <th><?= $column ?></th>
            <?php endforeach;?>
            <td>操作</td>
        </tr>
        <?php foreach ($threads as $thread):?>
            <tr>
                <?php foreach ($db_columns as $key => $column):?>
                    <td><?= isset($thread[$key]) ? htmlspecialchars($thread[$key]) : '' ?></td>
                <?php endforeach;?>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($thread['id']) ?>">編集</a>
                    <a href="delete.php?id=<?= htmlspecialchars($thread['id']) ?>">削除</a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
</main>
</body>
</html>