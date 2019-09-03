<?php
require 'functions.php';

$db = db_connect();
$data = db_get_data($db, $_GET['id']);

$post_values = current($data);//1レコード分のデータ

if (isset($_POST['edit'])) {
    list($post_values,$error_columns,$error_message) = validate($db_columns,$name_length,$message_length);
    if(!(count($error_columns)||count($error_message))) {
        //先に画像ファイル参照
        $delete_id = $db->prepare("SELECT image FROM diary_table WHERE id = :id;");
        $delete_id->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $delete_id->execute();
        $img_file = $delete_id->fetchColumn();
        $img_upload = img_upload($_FILES['file_upload'], $_GET['id']);

        //画像ファイルあれば削除
        if($img_file !== null){
            unlink("img/".$img_file);
        }

        $column_sql = [];
        foreach ($db_columns as $key => $column) {
            if ($key !== 'id' && $key !== 'created_at') {
                $column_sql[] = ' ' . $key . ' = :' . $key;
            }
        }
        $sql = 'UPDATE diary_table SET' . implode(',', $column_sql) . ' WHERE id = :id;';
        $query_u = $db->prepare($sql);
        $query_u->bindParam(':id', $_GET['id'], PDO::PARAM_INT);

        $bind_values = [];
        foreach ($db_columns as $key => $column) { //1レコード分の処理
            if ($key !== 'id' && $key !== 'created_at' && $key !=='image') {
                $bind_values[$key] = $post_values[$key];
                $query_u->bindParam(':' . $key, $bind_values[$key]);
            }

            if($key === 'image'){
                if(isset($_FILES['file_upload'])) {
                    if($img_upload['uploaded'] === false){
                        session_start();
                        $_SESSION['uploaded'] = 'Upload Error';
                    }
                    $query_u->bindParam(':' . $key, $img_upload['name']);
                }
            }
        }
        $edit = $query_u->execute();
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/index.css">
    <title>かきなおす | ひみつにっき</title>
</head>
<body>
<main>
    <div id="container">
    <h2>かきなおす</h2>
    <?php if ((isset($error_columns) || isset($error_message))  && (count($error_columns) || count($error_message))): ?>
        <div class="errors">
            <p>エラー_:(´ཀ`」 ∠):</p>
            <?php if (isset($error_columns) && count($error_columns)): ?>
                <p><?= implode(' と ', $error_columns) ?> は必ず書いてね。</p>
            <?php endif?>
        </div>
    <?php endif ?>

    <form action="edit.php?id=<?= htmlspecialchars($_GET['id']) ?>" enctype="multipart/form-data" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
        <p>
            <?= isset($post_values['image']) ? '<img src="img/'.htmlspecialchars($post_values['image']).'" width="300px">' : '' ?>
        </p>
        <p>
            <label>■ がぞう :<br><input name="file_upload" type="file"></label>
            <br><span class="small_text">※ アップロードできるもの png, jpg, jpeg, gif</span>
        </p>
        <p>
            <label>■ タイトル(<?= $name_length ?>もじ) :<br><input type="text" name="name" value="<?= $post_values['name'] ?? '' ?>"></label>
            <?php if (isset($error_columns['name'])): ?><span class="d-block"><small>かならず書いてね</small></span><?php endif;?>
            <?php if (isset($error_message['name'])): ?><span class="d-block"><small><?= $error_message['name'] ?></small></span><?php endif;?>
        </p>

        <p>
            <label>
                <span>■ 今日のできごと (<?= $message_length ?> もじ) :<br>
                <textarea name="message" rows="5" cols="50"><?= $post_values['message'] ?? '' ?></textarea>
                <?php if (isset($error_columns['message'])): ?><span class="d-block"><small>かならず書いてね</small></span><?php endif;?>
                <?php if (isset($error_message['message'])): ?><span class="d-block"><small><?= $error_message['message'] ?></small></span><?php endif;?>
                </span>
            </label>
        </p>
        <p>
            <button type="button" onclick="history.back()">もどる</button>
            <button type="submit" id="submit" name="edit">かきなおす</button>
        </p>
    </form>
    </div>
</main>
</body>
</html>
