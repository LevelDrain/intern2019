<?php
require 'functions.php';

session_start();
if(!isset($_POST['send'])){
    session_destroy();
    session_start();
    $_SESSION['errors'] = ['messages' => ['不正なアクセスです']];
    header('Location: index.php');
    exit;
}

    $to = 'intern-samuraki@ird-095.ahref.org';
    $from = 'From: ' . $_SESSION['email'];
    $subject = 'お問い合わせフォームからのメール';
    $lang = implode($separate, $_SESSION['lang']);
    $message = <<<EOF
    フォームからお問い合わせがありました。
    ■名前
    {$_SESSION['name']}
    
    ■メールアドレス
    {$_SESSION['email']}
    
    ■性別
    {$_SESSION['gender']}
    
    ■使用したことのある言語
    {$lang}
    
    ■メッセージ
    {$_SESSION['message']}

EOF;
    mb_send_mail($to, $subject, $message, $from);

    $db = db_connect();
    $query = $db->prepare('INSERT INTO contact (name, email, gender, lang, message, created_at) values(:name, :email, :gender, :lang, :message, :created_at )');
    $query->bindParam(':name', $_SESSION['name'],PDO::PARAM_STR);
    $query->bindParam(':email', $_SESSION['email'],PDO::PARAM_STR);
    $query->bindParam(':gender', $_SESSION['gender'],PDO::PARAM_STR);
    $query->bindParam(':lang', $lang,PDO::PARAM_STR);
    $query->bindParam(':message', $_SESSION['message'],PDO::PARAM_STR);
    $query->bindParam(':created_at',date('Y-m-d H:i:s'),PDO::PARAM_INT);
    $query->execute();

    session_destroy();
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>送信完了</title>
</head>
<body>
<main>
    <h2>送信完了</h2>
    <a href="index.php">入力フォームに戻る</a>
</main>
</body>
</html>