<?php
require 'functions.php';
$error_columns=[];
$error_message=[];

$name = "";
$message = "";
$data_array=[];

session_start();
$db = db_connect();
if(isset($_POST['send'])) {
    list($post_values,$error_columns,$error_message) = validate($db_columns,$name_length,$message_length);

    if(!(count($error_columns)||count($error_message))) {
        $query_i = $db->prepare('INSERT INTO diary_table (name, message, created_at,image) VALUES (:name, :message, :created_at, :image)');
        $query_i->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
        $query_i->bindParam(':message', $_POST['message'], PDO::PARAM_STR);
        $query_i->bindParam(':created_at', date('Y-m-d H:i:s'), PDO::PARAM_INT);
        if($_FILES['file_upload']['name'] !== ''){
            $get_id = $db->query("SELECT auto_increment FROM information_schema.tables WHERE table_name = 'diary_table';");
            $get_id->execute();
            $db_id = $get_id->fetchColumn();
            $img_upload = img_upload($_FILES['file_upload'], $db_id);

            $query_i->bindParam(':image', $img_upload['name'], PDO::PARAM_STR);
            $upload_error = $img_upload['uploaded'];
        }else{
            $query_i->bindValue(':image', null, PDO::PARAM_NULL);
        }
        $query_i->execute();
        //var_dump($data_array);

        $post_values = [];
    }
}

//失敗したポイント：POSTがあってもなくても自動で読み込まれるように上と順序を変えた
$query_s = $db->query('SELECT * FROM diary_table');
$query_s->execute();
$data_array = $query_s->fetchAll();

?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/index.css">
    <title>できごと一覧 | ひみつにっき</title>
</head>

<body>
<main>
<div id="container">
    <h1>ひみつにっき</h1>
    <p class="small_text">夏休みの宿題「1行日記」のチートツールです。</p>
    <?php if (isset($upload_error)||isset($_SESSION['uploaded'])){?>
        <p class="small_text">アップロードできるのは、png, jpg, jpeg, gif だけです。</p>
        <?php
        session_destroy();
    } ?>
    <hr>
    <?php if ((isset($error_columns) || isset($error_message))  && (count($error_columns) || count($error_message))): ?>
        <div class="errors">
            <p class="small_text">エラー_:(´ཀ`」 ∠):</p>
            <?php if (isset($error_columns) && count($error_columns)): ?>
                <p class="small_text"><?= implode(' と ', $error_columns) ?> は必ず書いてね。</p>
            <?php endif?>
            <hr>
        </div>
    <?php endif ?>

    <div class="txt-center">
        <?php foreach ($data_array as $data_col):?>
        <table>
            <tr>
                <th><?= $db_columns['message'] ?></th>
                <th><?= isset($data_col['name']) ? htmlspecialchars($data_col['name']) : '' ?></th>
            </tr>
            <tr class="small_text">
                <td>投稿ID : <?= isset($data_col['id']) ? htmlspecialchars($data_col['id']) : '' ?></td>
                <td><?= isset($data_col['created_at']) ? htmlspecialchars($data_col['created_at']) : '' ?></td>
            </tr>
            <tr>
                <td class="image_center"><?= isset($data_col['image']) ? '<img src="img/'.htmlspecialchars($data_col['image']).'" width="250px">' : '' ?></td>
                <td><?= isset($data_col['message']) ? htmlspecialchars($data_col['message']) : '' ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href="edit.php?id=<?= htmlspecialchars($data_col['id']) ?>">かきなおす</a> |
                    <a href="delete.php?id=<?= htmlspecialchars($data_col['id']) ?>">わすれる</a>
                </td>
            </tr>
        </table>
        <?php endforeach; ?>
    </div>

    <hr>
    <div class="form_area" >

    <form action="index.php" enctype="multipart/form-data" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
        <p>
            <label>■ がぞう : <input name="file_upload" type="file"></label>
            <br><span class="small_text">※ アップロードできるもの png, jpg, jpeg, gif</span>
        </p>
        <p>
            <label>■ タイトル(<?= $name_length ?>もじ) : <input type="text" name="name" value="<?= $post_values['name'] ?? '' ?>"></label>
            <?php if (isset($error_columns['name'])): ?><span class="d-block"><small>かならず書いてね</small></span><?php endif;?>
            <?php if (isset($error_message['name'])): ?><span class="d-block"><small><?= $error_message['name'] ?></small></span><?php endif;?>
        </p>
        <p>
            <label>
                <span class="d-block">■ 今日のできごと(<?= $message_length ?>もじ) :<br>
                <textarea name="message" rows="2" cols="100"><?= $post_values['message'] ?? null ?></textarea>
                <?php if (isset($error_columns['message'])): ?><span class="d-block"><small>かならず書いてね</small></span><?php endif;?>
                <?php if (isset($error_message['message'])): ?><span class="d-block"><small><?= $error_message['message'] ?></small></span><?php endif;?>
                </span>
            </label>
        </p>
        <p><button type="submit" id="submit" name="send">送信する</button></p>
    </form>
    </div>
</main>

</div>

</body>
</html>
