<?php
function checkLang($post_values,$target){
    return isset($post_values['lang']) && is_array($post_values['lang']) && (array_search($target, $post_values['lang']) !== false);
    //厳密な比較　===
    //厳密に違う !==
}

function db_connect() {
    $db_host = 'db';
    $db_name = 'intern-form';
    $db_user = 'intern';
    $db_pass = 'intern';
    try {
        $db = new PDO(
            'mysql:host=' . $db_host .
            ';dbname=' . $db_name .
            ';charset=utf8',
            $db_user,
            $db_pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $db;
    } catch (PDOException $e) {
        exit('DB接続失敗 ' . $e->getMessage());
    }
}

function db_can_show($db, $id){
    $data = [];
    if ($id) {
        $query = $db->prepare('SELECT * FROM contact WHERE id = :id;');
        $query->bindParam(':id',$id,PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetchAll();
    }
    if (!count($data)) {
        session_start();
        $_SESSION['errors'] = ['manage' => ['該当するお問い合わせが見つかりませんでした']];
        header('Location: list.php');
        exit;
    }
    return $data;
}

function validate($columns, $message_length, $lang_choice_num){
    $error_columns = [];
    $error_message = [];
    $post_values = [];

    foreach ($columns as $key => $name) {
        $post_values[$key] = null;
        if (empty($_POST[$key])) {
            $error_columns[$key] = $name;
        } else {
            $post_values[$key] = $_POST[$key];
            if ($key === 'email') {
                if (!filter_var($_POST[$key], FILTER_VALIDATE_EMAIL)) {
                    $error_message[$key] = 'メールアドレスの形式が正しくありません。';
                }

                // 正規表現を使うタイプのバリデーション
                //  if(!preg_match('/^\w+([-+.\']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/i', $_POST['email'])){
                //      $error_message[$key] = 'メールアドレスの形式が正しくありません。';
                //  }
            } elseif ($key === 'message') {
                if (mb_strlen($_POST[$key]) > $message_length) {
                    $error_message[$key] = $columns[$key] . 'が' . $message_length . '文字を超えています。';
                }
            } elseif ($key === 'lang') {
                if (count($_POST[$key]) < 2) {
                    $error_message[$key] = $columns[$key] . 'は' . $lang_choice_num . '以上選択する必要があります。';
                }
            }
        }
    }
    return [
        $post_values,
        $error_columns,
        $error_message,
    ];
}

$message_length = 250;
$lang_choice_num = 2;

$columns = [
    'name' => '名前',
    'email' => 'メールアドレス',
    'gender' => '性別',
    'lang' => '使用したことのある言語',
    'message' => 'メッセージ',
];

$db_columns = $columns + ['created_at' => '送信日時'];
$separate = ' / ';