<?php
require 'functions.php';

$db = db_connect();
$data = db_get_data($db, $_GET['id']);

if(isset($_POST['delete'])){
    //先に画像ファイル参照
    $delete_id = $db->prepare("SELECT image FROM diary_table WHERE id = :id;");
    $delete_id->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $delete_id->execute();
    $img_file = $delete_id->fetchColumn();

    //画像ファイルあれば削除
    if($img_file !== null){
        unlink("img/".$img_file);
    }

    //DBのデータ削除
    $query_d = $db->prepare('DELETE FROM diary_table WHERE id = :id;');
    $query_d->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $delete = $query_d->execute();

    header('Location: index.php');
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
    <link rel="stylesheet" href="css/index.css">
    <title>わすれる | ひみつにっき</title>
</head>
<body>
<main>
    <div id="container">
    <h2>わすれる</h2>
        <form action="delete.php?id=<?= htmlspecialchars($_GET['id']) ?>" method="post">
            <p>
                <?= isset($post_values['image']) ? '<img src="img/'.htmlspecialchars($post_values['image']).'" width="300px">' : '' ?>
            </p>
            <p>
                <label>■ タイトル :<br><?= $post_values['name'] ?? '' ?>
                </label>
            </p>
            <p>
                <label>
                    <span>■ 今日のできごと :<br>
                    <?= $post_values['message'] ?? '' ?>
                    </span>
                </label>
            </p>
            <p>
                <button type="button" onclick="history.back()">もどる</button>
                <button type="submit" id="submit" name="delete">わすれる</button>
            </p>
        </form>
    </div>
</main>
</body>
</html>