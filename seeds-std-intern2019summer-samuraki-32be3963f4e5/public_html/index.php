<?php
require 'functions.php';

session_start();
if($_SESSION){
    $post_values = $_SESSION;
    $error_columns = $_SESSION['errors']['columns'] ?? [];
    $error_message = $_SESSION['errors']['messages'] ?? [];
}
?>

<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>お問い合わせ</title>
    <style>
        .d-block {
            display:block;
        }
    </style>
</head>
<body>
<main>
	<h1>お問い合わせフォーム</h1>
    <?php if (isset($success)): ?>
        <div><p><?= $success ?></p></div>
    <?php endif ?>

    <?php if ((isset($error_columns) || isset($error_message)) && (count($error_columns)||count($error_message))): ?>
        <div class="error">
            <p>エラーが発生しました。</p>
            <?php if (isset($error_message) && count($error_message)): ?>
                <?php foreach ($error_message as $text): ?>
                    <p><?= $text ?></p>
                <?php endforeach;?>
            <?php endif?>

            <?php if (isset($error_columns) && count($error_columns)): ?>
                <p>下記項目が未入力です。</p>
                <p><?= implode('、',$error_columns) ?></p>
            <?php endif?>
        </div>
    <?php endif ?>

    <form action="confirm.php" method="post">
        <p><label>■ 名前 <input type="text" name="name" value="<?= $post_values['name'] ?? '' ?>"></label></p>
        <?php if (isset($error_columns['name'])): ?><span class="d-block"><small>入力必須です。</small></span><?php endif;?>
        <?php if (isset($error_message['name'])): ?><span class="d-block"><small><?= $error_message['name'] ?></small></span><?php endif;?>

        <p><label>■ メール <input type="text" name="email" value="<?= $post_values['email'] ?? '' ?>"</label></p>
        <?php if (isset($error_columns['email'])): ?><span class="d-block"><small>入力必須です。</small></span><?php endif;?>
        <?php if (isset($error_message['email'])): ?><span class="d-block"><small><?= $error_message['email'] ?></small></span><?php endif;?>

        <p>
            <span>■ 性別</span>
            <label><input type="radio" name="gender" value="男性"<?= $post_values['gender'] === '男性' ? ' checked' : '' ?>>男性</label>
            <label><input type="radio" name="gender" value="女性"<?= $post_values['gender'] === '女性' ? ' checked' : '' ?>>女性</label>
            <label><input type="radio" name="gender" value="その他"<?= $post_values['gender'] === 'その他' ? ' checked' : '' ?>>その他</label>
            <label><input type="radio" name="gender" value="回答しない"<?= $post_values['gender'] === '回答しない' || empty($post_values['gender']) ? ' checked' : '' ?>>回答しない</label>
        </p>

        <p>
            <span class="d-block">■ 使用したことのある言語(<?= $lang_choice_num ?>個以上選択必須)</span>
            <label><input type="checkbox" name="lang[]" value="Java"<?= checkLang($post_values, 'Java') ? ' checked' : '' ?>>Java</label>
            <label><input type="checkbox" name="lang[]" value="C++"<?= checkLang($post_values, 'C++') ? ' checked' : '' ?>>C++</label>
            <label><input type="checkbox" name="lang[]" value="JS"<?= checkLang($post_values, 'JS') ? ' checked' : '' ?>>JavaScript</label>
            <label><input type="checkbox" name="lang[]" value="PHP"<?= checkLang($post_values, 'PHP') ? ' checked' : '' ?>>PHP</label>
            <label><input type="checkbox" name="lang[]" value="Python"<?= checkLang($post_values, 'Python') ? ' checked' : '' ?>>Python</label>
            <label><input type="checkbox" name="lang[]" value="Ruby"<?= checkLang($post_values, 'Ruby') ? ' checked' : '' ?>>Ruby</label>
            <label><input type="checkbox" name="lang[]" value="その他"<?= checkLang($post_values, 'その他') ? ' checked' : '' ?>>その他</label>
            <?php if (isset($error_columns['lang'])): ?><span class="d-block"><small>入力必須です。</small></span><?php endif;?>
            <?php if (isset($error_message['lang'])): ?><span class="d-block"><small><?= $error_message['lang'] ?></small></span><?php endif;?>
        </p>

        <p>
            <label>
                <span class="d-block">■ メッセージ(<?= $message_length ?> 文字以内)</span>
                <textarea name="message"><?= $post_values['message'] ?? '' ?></textarea>
                <?php if (isset($error_columns['message'])): ?><span class="d-block"><small>入力必須です。</small></span><?php endif;?>
                <?php if (isset($error_message['message'])): ?><span class="d-block"><small><?= $error_message['message'] ?></small></span><?php endif;?>
            </label>
        </p>

		<p><button type="submit" id="submit" name="send">送信する</button></p>
	</form>
</main>
</body>
</html>
