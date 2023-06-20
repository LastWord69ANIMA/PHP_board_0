<?php
$filename="mission_3-5.txt";

//以下、投稿フォーム
if( !empty( $_POST["name"] )&& !empty( $_POST["comment"] )&& !empty( $_POST["pass"] ) ){

    $num = 0;
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    foreach($lines as $line){
        $num = max($num,explode("<>", $line)[0]);
        }
    $num = $num + 1;
    
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y年m月d日 H時i分s秒");
    $pass =$_POST["pass"];

    $fp = fopen($filename,"a");
    fwrite($fp, $num."<>".$name."<>".$comment."<>".$date."<>".$pass."<>".PHP_EOL);
    fclose($fp);
} 
  

//以下、削除フォーム
elseif(!empty( $_POST["delete"] )&& !empty( $_POST["del_pass"] ) ){
    
    $delete = $_POST["delete"];
    $del_pass = $_POST["del_pass"];
    
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    $fp = fopen($filename, "w");
    fclose($fp);
    
    foreach($lines as $line) {
        $post = explode("<>", $line);
           
        //削除対象のパスと同じ場合に作動
        if($post[4] == $_POST["del_pass"]){

            //削除対象のパスと同じ且つ削除対象番号と同じ場合に書かない･･･つまり、削除
            if($post[0] == $delete)  {

            $fp = fopen($filename, "r");
            fclose($fp);
            }
             //削除対象番号と同じ且つ削除対象番号と違う場合はそのまま表示
            else{
                $fp = fopen($filename, "a");
                fwrite($fp, $line . PHP_EOL);
                fclose($fp);
                
            }
        }
        //削除対象のパスと違う場合は削除せずに書き込む(削除対象含めて)
        else{
        $fp = fopen($filename, "a");
        fwrite($fp, $line . PHP_EOL);
        fclose($fp);
        
        }
    }
}
//以下、投稿・削除・編集（データ取得）どれも機能しなかった場合
else{
   $fp = fopen($filename,"r");
    fclose($fp);
}

//以下、編集フォーム（編集対象番号から、元のデータを取得）
if(!empty( $_POST["edit"] )&& !empty( $_POST["edit_pass"] ) ){
    
    $edit = $_POST["edit"];
    $edit_pass = $_POST["edit_pass"];
    
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    $fp = fopen($filename, "r");

        
    foreach($lines as $line) {
    $post = explode("<>", $line);
    
    
        //編集対象番号と入力した番号が同じ場合、その番号行を取得
        if($post[4] == $_POST["edit_pass"]){
    
            if ($post[0] == $_POST["edit"]) {
                $edit_num = $post[0];
                $edit_name = $post[1];
                $edit_comment = $post[2];
                $edit_pass = $post[4];
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
    
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    $fp = fopen($filename, "w");
    foreach($lines as $line){
        
        $post = explode("<>", $line);
    
    //ファイル内の取得番号が一致した場合、差し替える
        if ($post[0] == $_POST["edit_num"]) {
        
            fwrite($fp, $_POST["edit_num"]."<>".$_POST["edit_name"]."<>".$_POST["edit_comment"]."<>".$date."<>".$_POST["edit_new_pass"]."<>".PHP_EOL);
        }
        else{
        fwrite($fp, $line . PHP_EOL);
        }
    }
    fclose($fp);
}
//以下、編集番号が空白であり、新規投稿として扱う場合
elseif( empty( $_POST["edit_num"] )&& !empty( $_POST["edit_name"] )&& !empty( $_POST["edit_comment"] )&&!empty($_POST["edit_new_pass"]) ){    
    $num = 0;
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    foreach($lines as $line){
        $num = max($num,explode("<>", $line)[0]);
        }
    $num = $num + 1;
    $date = date("Y年m月d日 H時i分s秒");
    
    $fp = fopen($filename,"a");
    fwrite($fp, $num."<>".$_POST["edit_name"]."<>".$_POST["edit_comment"]."<>".$date."<>".$_POST["edit_new_pass"].PHP_EOL);
    fclose($fp);
}

//以下、投稿・削除・編集（データ取得）どれも機能しなかった場合
else{
    $fp = fopen($filename,"r");
    fclose($fp);
    //echo "操作エラー"."<br>";
}

//以下、ファイルの中身を出力。
if(file_exists($filename)){
    $lines = file($filename,FILE_IGNORE_NEW_LINES);
    foreach($lines as $line){
    
    //echoにて、ファイルの各列のうち、パスワード $line[4] のみを出力しない。
    $line = explode("<>", $line);
    echo $line[0]."-".$line[1]."-".$line[2]."-".$line[3]."-"."<br>";
    }
}
    echo "<br>";
    echo "<br>";
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5☆(ありがとうございました）</title>
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