<?php
// DB接続設定
$dsn = 'mysql:dbname=tb250013db;host=localhost';
$user = 'tb-250013';
$password = '9gXew6CWgd';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS table51"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32) NOT NULL,"
."comment TEXT NOT NULL,"
."date TEXT NOT NULL,"
."pass TEXT NOT NULL"
.");";
$stmt = $pdo->query($sql);

//テーブルのカラム入れ忘れなどは、いったん削除する必要
//作成文の中の , があるorないかは確認すること

//以下、投稿フォーム（ファイルではなく、DBへ保存する）
if( !empty( $_POST["name"] )&& !empty( $_POST["comment"] )&& !empty( $_POST["pass"] ) ){

    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y年m月d日 H時i分s秒");
    $pass =$_POST["pass"];

    $sql = $pdo -> prepare("INSERT INTO table51
 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    $sql -> execute();
    
} 
  

//以下、削除フォーム（ファイルではなく、DBへ干渉する）
//$_POST["del_pass"]がpassと一致した場合のみ削除
elseif(!empty( $_POST["delete"] )&& !empty( $_POST["del_pass"] ) ){
    
    $delete = $_POST["delete"];
    $del_pass = $_POST["del_pass"];
    
    //以下、passがあっていれば実行
    //おそらく、M4-の奴を使って、$row[4]　を取得し、パスを参照する？
    $sql = 'SELECT * FROM table51';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
            if(  $row['pass'] == $del_pass){
            
                if($row['id'] == $delete){
                    
                    $sql = 'delete from table51 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
                    $stmt->execute();
                    
                }
            }
    }
}

//（ファイルではなく、DBへ干渉する）
//以下、編集フォーム（編集対象番号から、元のデータを取得）
if(!empty( $_POST["edit"] )&& !empty( $_POST["edit_pass"] ) ){
    
    $edit = $_POST["edit"];
    $edit_pass = $_POST["edit_pass"];


    //以下、passがあっていれば実行
    //おそらく、M4-の奴を使って、$row[4]　を取得し、パスを参照する？
    $sql = 'SELECT * FROM table51';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    
        //編集対象番号と入力した番号が同じ場合、その番号行を取得
        if($row['pass'] == $_POST["edit_pass"]){
    
            if ($row['id'] == $_POST["edit"]) {
                $edit_num = $row['id'];
                $edit_name = $row['name'];
                $edit_comment = $row['comment'];
                $edit_pass = $row['pass'];
            }
        }
    }
}

//以下、編集フォーム（取得後に、元のデータを差し替える）
if( !empty( $_POST["edit_num"] )&& !empty( $_POST["edit_name"] )&& !empty( $_POST["edit_comment"] )&&!empty( $_POST["edit_new_pass"] ) ){
    
    $edit_new_num = $_POST["edit_num"];
    $edit_new_name = $_POST["edit_name"];
    $edit_new_comment = $_POST["edit_comment"];
    $edit_new_pass = $_POST["edit_new_pass"];
    $date = date("Y年m月d日 H時i分s秒");
    
    $sql = 'UPDATE table51
 SET name=:name,comment=:comment,pass=:pass  WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $edit_new_name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $edit_new_comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $edit_new_num, PDO::PARAM_INT);
    $stmt -> bindParam(':pass', $_POST["edit_new_pass"], PDO::PARAM_STR);
    //↑の$stmt は $spl ではないのか？
    $stmt->execute();
}

//以下、編集番号が空白であり、新規投稿として扱う場合
elseif( empty( $_POST["edit_num"] )&& !empty( $_POST["edit_name"] )&& !empty( $_POST["edit_comment"] )&&!empty($_POST["edit_new_pass"]) ){    
    
    $date = date("Y年m月d日 H時i分s秒");
    

    $sql = $pdo -> prepare("INSERT INTO table51
 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
    $sql -> bindParam(':name', $_POST["edit_name"], PDO::PARAM_STR);
    $sql -> bindParam(':comment', $_POST["edit_comment"], PDO::PARAM_STR);
    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
    $sql -> bindParam(':pass', $_POST["edit_new_pass"], PDO::PARAM_STR);
    $sql -> execute();

}


//ファイルではなく、DBを出力する。
    $sql = "SELECT * FROM table51";
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].' - ';
        echo $row['name'].' - ';
        echo $row['comment'].' - ';
        echo $row['date'].'<br>';
    echo "<hr>";
    }


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name"  placeholder="名前"><br>
        <input type="text" name="comment"  placeholder="コメント"><br>
        <input type="text" name="pass" placeholder="パスワード"><br><br>
    <input type="submit" name="submit">
    </form>
    
    <form action="" method="post">    
        <input type="text" name="delete"  placeholder="削除対象番号"><br>
        <input type="text" name="del_pass" placeholder="パスワード:必須"><br><br>
    <input type="submit" name="submit">
    </form>
    
    <form method="post" action="">
        <input type="text" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="edit_pass" placeholder="パスワード:必須"><br><br>
        <input type="submit" name="submit">
    </form>
    
     <form method="post" action="">
        <?php if (isset($edit_num)) { echo '<input type="text" name="edit_num" value="'.$edit_num.'">'; } ?><br>
        <?php if (isset($edit_name)) { echo '<input type="text" name="edit_name" value="'.$edit_name.'">'; } ?><br>
        <?php if (isset($edit_comment)) { echo '<input type="text" name="edit_comment" value="'.$edit_comment.'">'; } ?><br>
        <?php if (isset($edit_num)) { echo '<input type="text" name="edit_new_pass" placeholder="パスワード">'; } ?><br><br>
        <?php if (isset($edit_num)) { echo '<input type="submit" name="submit">'; } ?>
     </form> 
</body>
</html>