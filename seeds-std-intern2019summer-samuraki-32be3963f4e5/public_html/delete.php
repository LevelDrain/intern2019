<?php
require 'functions.php';

$db = db_connect();
$data = db_can_show($db, $_GET['id']);

if(isset($_POST['delete'])){
    $query = $db->prepare('DELETE FROM contact WHERE id = :id;');
    $query->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $delete = $query->execute();
    if($delete){
        $_SESSION['success'] = ['manage' => ['ID: '.$_GET['id'].'の削除が完了しました。']];
    }else{
        $_SESSION['errors'] = ['manage' => ['ID: '.$_GET['id'].'の削除に失敗しました。']];
    }
    header('Location: list.php');
    exit;
}

$post_values = current($data);
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>お問い合わせ削除</title>
</head>
<body>
<main>
    <h2>お問い合わせ削除</h2>
    <form action="delete.php?id=<?= htmlspecialchars($_GET['id']) ?>" method="post">
        <p>■ 名前 : <?= $post_values['name'] ?></p>
        <p>■ メール : <?= $post_values['email'] ?></p>
        <p>■ 性別 : <?= $post_values['gender'] ?></p>
        <p>■ 使用したことのある言語（<?= $lang_choice_num ?>個以上選択必須）: <?= $post_values['lang'] ?></p>
        <p>■ メッセージ（<?= $message_length ?> 文字以内）: </p>
        <p><?= $post_values['message'] ?></p>
        <p>
            <button type="button" onclick="history.back()">戻る</button>
            <button type="submit" id="submit" name="delete">送信する</button>
        </p>
    </form>
</main>
</body>
</html>
