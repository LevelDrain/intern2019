<?php
require 'functions.php';

session_start();
if (!isset($_POST['send'])) {
    session_destroy();
    session_start();
    $_SESSION['errors'] = ['messages' => ['不正なアクセスです']];
    header('Location: index.php');
    exit;
}

// バリデーションする
list($post_values, $error_columns, $error_message) = validate($columns, $message_length, $lang_choice_num);

// postから来たhtmlに出力する内容はすべてエスケープしてセッションに格納しておく
foreach ($post_values as $key => $value) {
    if (is_array($value)) {
        $_SESSION[$key] = [];
        foreach ($value as $val_key => $col) {
            $_SESSION[$key][$val_key] = htmlspecialchars($col);
        }
    } else {
        $_SESSION[$key] = htmlspecialchars($value);
    }
}

if (count($error_columns) || count($error_message)) {
    $_SESSION['errors'] = [
        'columns'  => $error_columns,
        'messages' => $error_message,
    ];
    header('Location: index.php');
    exit;
}
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>確認画面</title>
</head>
<body>
<main>
    <h1>確認画面</h1>
    <form action="complete.php" method="post">
        <p>■ 名前: <?= $_SESSION['name'] ?></p>
        <p>■ メール: <?= $_SESSION['email'] ?></p>
        <p>■ 性別: <?= $_SESSION['gender'] ?></p>
        <p>■ 使用したことのある言語(<?= $lang_choice_num ?>個以上選択必須): <?= implode($separate, $_SESSION['lang'])?></p>
        <p>■ メッセージ (<?= $message_length ?> 文字以内):</p>
        <p><?= $_SESSION['message'] ?></p>
        <p>
            <button type="button" onclick="history.back()">戻る</button>
            <button type="submit" id="submit" name="send">送信する</button>
        </p>
    </form>
</main>
</body>
</html>