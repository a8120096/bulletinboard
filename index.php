<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
</head>

<body>
    <?php
    // DB接続設定
    $dsn = 'mysql:dbname='データベース名';host=localhost';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    // テーブルの削除
    // $sql = 'DROP TABLE tbbbs';
    // $stmt = $pdo->query($sql);

    // テーブルtbtestの作成
    $sql = "CREATE TABLE IF NOT EXISTS 'データベース名'"
        . " ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "passward TEXT,"
        . "created_at datetime"
        . ");";
    $stmt = $pdo->query($sql);

    // テーブルtbtestの中身の確認
    $sql = 'SHOW CREATE TABLE tbbbs';
    $result = $pdo->query($sql);
    foreach ($result as $row) {
        // echo $row[1];
    }

    if (isset($_POST["name"]) && isset($_POST["comment"]) && isset($_POST["passward"])) {
        // 編集の場合
        if ((!empty($_POST["name"])) && (!empty($_POST["comment"])) && (!empty($_POST["passward"])) && (!empty($_POST["id"]))) {
            $id = $_POST["id"];
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $passward = $_POST["passward"];
            $created_at = date("Y-m-d H:i:s");
            $sql = 'UPDATE 'データベース名' SET name=:name,comment=:comment,passward=:passward,created_at=:created_at WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':passward', $passward, PDO::PARAM_STR);
            $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }

        // 新規投稿の場合
        if ((!empty($_POST["name"])) && (!empty($_POST["comment"])) && (!empty($_POST["passward"]) && (empty($_POST["id"])))) {
            // テーブルtbtestにレコードを追加
            $sql = $pdo->prepare("INSERT INTO 'データベース名' (name, comment, passward, created_at) VALUES (:name, :comment, :passward, :created_at)");
            $sql->bindParam(':name', $name, PDO::PARAM_STR);
            $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql->bindParam(':passward', $passward, PDO::PARAM_STR);
            $sql->bindParam(':created_at', $created_at, PDO::PARAM_STR);
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $passward = $_POST["passward"];
            $created_at = date("Y-m-d H:i:s");
            $sql->execute();
        }
    }

    // 記事削除処理
    if (isset($_POST["del_num"]) && isset($_POST["del_passward"])) {
        if ((!empty($_POST["del_num"])) && (!empty($_POST["del_passward"]))) {
            // フォームから値の読み込み
            $del_num = $_POST["del_num"];
            $del_passward = $_POST["del_passward"];

            // idが$del_numのパスワードを抽出
            $sql = 'SELECT * FROM 'データベース名' WHERE id=:del_num';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':del_num', $del_num, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $passward =  $row['passward'];
            }

            // パスワードが一致するとき行を削除
            if ($passward == $del_passward) {
                $sql = 'DELETE FROM 'データベース名' WHERE id=:del_num ';
                $stmt = $pdo->prepare($sql); 
                $stmt->bindParam(':del_num', $del_num, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }

    // 記事編集処理
    if (isset($_POST["edit_num"]) && isset($_POST["edit_passward"])) {
        if ((!empty($_POST["edit_num"])) && (!empty($_POST["edit_passward"]))) {
            // フォームから値の読み込み
            $edit_num = $_POST["edit_num"];
            $edit_passward = $_POST["edit_passward"];

            // idが$edit_numのパスワードを抽出
            $sql = 'SELECT * FROM 'データベース名' WHERE id=:edit_num';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':edit_num', $edit_num, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                $passward =  $row['passward'];
            }
            if ($passward == $edit_passward) {
                $editid = $row['id'];
                $editname = $row['name'];
                $editcomment = $row['comment'];
                $editpassward = $row['passward'];
            }
        }
    }
    ?>
    <h2>テーマ:ひとこと</h2>
    <form method="POST" action="">
        <input type="name" name="name" value="<?php if (!empty($editname)) {
                                                    echo $editname;
                                                } else {
                                                    echo "名前";
                                                } ?>"><br>
        <input type="comment" name="comment" value="<?php if (!empty($editcomment)) {
                                                        echo $editcomment;
                                                    } else {
                                                        echo "コメント";
                                                    } ?>"><br>
        <input type="passward" name="passward" value="<?php if (!empty($editpassward)) {
                                                            echo $editpassward;
                                                        } else {
                                                            echo "パスワード";
                                                        } ?>"><br>
        <input type="hidden" name="id" value="<?php if (!empty($editid)) {
                                                    echo $editid;
                                                } ?>"><br>
        <input type="submit" name="submit" value="送信">
    </form>
    <form method="POST" action="">
        <input type="del_num" name="del_num" value="削除番号指定"><br>
        <input type="del_passward" name="del_passward" value="パスワード"><br>
        <input type="submit" name="delete" value="削除">
    </form>
    <form method="POST" action="">
        <input type="edit_num" name="edit_num" value="編集番号指定"><br>
        <input type="edit_passward" name="edit_passward" value="パスワード"><br>
        <input type="submit" name="edit" value="編集">
    </form>

    <?php
    // テーブルtbtestからレコードを抽出
    $sql = 'SELECT * FROM 'データベース名'';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'] . ',';
        echo $row['name'] . ',';
        echo $row['comment'] . ',';
        // echo $row['passward'] . ',';
        echo $row['created_at'] . '<br>';
        echo "<hr>";
    }
    ?>
</body>

</html>