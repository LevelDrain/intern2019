<?php
    try{
        $pdo = new PDO(
            'mysql:host=db;dbname=intern-form;charset=utf8',
            'intern',
            'intern',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
    }catch(PDOException $e){
        exit('データベース接続失敗'.$e -> getMessage());
    }

    $sql = "SELECT * FROM contact";
    $res = $pdo->query($sql);

    $name_ary=[];
    $email_ary=[];
    //$resの中にDBの連想配列が入っているので取り出す
    foreach($res as $value){
        //echo($value['name']."<br>");
        $name_ary[] = $value['name'];
        $email_ary[] = $value['email'];
    }
    //var_dump($name);

    $columns = [
        'name' => 'お名前',
        'email' => 'メールアドレス',
    ];

    $dbh = null;

    ?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        table{
            border-color: black;
            border-style: solid;
            border-collapse: collapse;
        }
        th,td{
            border-color: black;
            border-style: solid;
        }
    </style>
    <title>DB管理画面</title>
</head>
<body>
<main>
    <table>
        <tr>
            <th>お名前</th>
            <th>メールアドレス</th>
        </tr>

        <?php foreach ($name_ary as $name): ?>
        <tr>
            <td><?= $name ?></td>
        </tr>
        <?endforeach;?>

        <?php foreach ($email_ary as $email): ?>
        <th>
            <td><?= $email ?></td>
        </tr>
        <?endforeach;?>
    </table>
</main>
</body>
</html>
