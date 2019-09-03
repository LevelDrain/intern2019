<?php
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

function db_get_data($db, $id){
    $data = [];
    if($id){
        $query_s = $db->prepare('SELECT * FROM diary_table WHERE id = :id;');
        $query_s->bindParam(':id',$id,PDO::PARAM_INT);
        $query_s->execute();
        $data = $query_s->fetchAll();
    }
    if(!count($data)){
        header('Location: index.php');
        exit;
    }
    return $data;
}


function validate($db_columns, $name_length, $message_length){
    $error_columns = [];
    $error_message = [];
    $post_values = [];

    foreach ($db_columns as $key => $value){
        if($key !== 'id' && $key !=='created_at' && $key!=='image') {
            $post_values[$key] = null;

            if (empty($_POST[$key])) {
                //POSTがない場合はエラー変数に代入
                $error_columns[$key] = $value;
            } else {
                //存在する場合はpost_valueに値を保持させる
                $post_values[$key] = htmlspecialchars($_POST[$key]);

                if ($key === 'name') {
                    if (mb_strlen($_POST[$key]) > $name_length) {
                        $error_message[$key] = '「' . $db_columns[$key] . '」は' . $name_length . '文字までです。';
                    }
                }elseif ($key === 'message') {
                    if (mb_strlen($_POST[$key]) > $message_length) {
                        $error_message[$key] = '「' . $db_columns[$key] . '」は' . $message_length . '文字までです。';
                    }
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

function img_upload($uploaded_img,$db_id){
    $return = [
        'name' => null,
        'uploaded' => false,
    ];
    $filename = $uploaded_img['name'];
    //echo end(explode('.',$filename));
    //https://blog.takahash.com/entry/20110228/p1
    $extension = end(explode('.',$filename));

    //画像ファイル以外は受け入れない
    if(preg_match('/png$|jpg$|jpeg$|gif$/i',$extension)){
        $renamed = $db_id.'.'.$extension;//ID + 拡張子
        $upload = 'img/' .$renamed;

        if (move_uploaded_file($uploaded_img['tmp_name'], $upload)) {
            //echo '<img src="' . $upload . '" width="150px">';
            $return['uploaded'] = true;
            $return['name'] = $renamed;
        }
    }

    return $return;
}

$db_columns=[
    'id' => '投稿ID',
    'name' => 'タイトル',
    'message' => 'できごと',
    'created_at' => 'いつのこと？',
    'image' => 'がぞう'
];

$name_length = 20;
$message_length = 255;
$separate = ',';
