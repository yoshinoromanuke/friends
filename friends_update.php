<?php
//データベースに接続	
$dsn = 'mysql:dbname=Friends_System;host=localhost';
$user = 'root';
$password = 'yoshi117';
$dbh = new PDO($dsn,$user,$password);
//文字化け解消
$dbh->query('SET NAMES utf8');
//ボタンを押された時の機能を付与
if (isset($_POST['name']))
{
	//echo 'POST送信された！';
	//Update文
	$sql = "UPDATE `friends` SET `name` = '".$_POST['name']."',`gender` = '".$_POST['gender']."',`age` = '".$_POST['age'];
	$sql .= "' WHERE `id` = ".$_POST['id'];
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$dbh=null;
	//処理が全て終わった後、都道府県一覧に戻る
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/friends/friends_areas.php');
}
else
{
	echo 'POST送信されてない';
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">  
<html>
<head>
	<meta http-equiv="Content-Type"content="text/html;charset=UTF-8"> 
	<title>都道府県友達リスト</title>
</head>
<body>
	<?php
	//2.SQLで指令をだす.友達を表示
		$sql_friends = 'SELECT * FROM `friends` WHERE `id` = '.$_GET['id'];
		$stmt_friends = $dbh->prepare($sql_friends);
		$stmt_friends->execute();
		//得られたデータを格納
		$rec_friends = $stmt_friends->fetch(PDO::FETCH_ASSOC);
		$id = $rec_friends['id'];
		$area_id = $rec_friends['area_id'];
		$name = $rec_friends['name'];
		$gender = $rec_friends['gender'];
		$age = $rec_friends['age'];
		$sql = 'SELECT * FROM `areas`';
		//echo $sql;
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		$dbh=null;
	?>
	<h2>お友達の編集</h2>
	<form method="post" >
		名前
		<input name="name" type="text" style="width:100px;height:30px;" maxlength="20" value="<?php echo $name;//初期表示 ?>"><br />
		出身
		<select name="area_id">
			<?php
//while文で<option value="idの数字">$rec['name']</option>を実行。値によって変わるのはidの数字のみなのでそこだけif文で囲む。
				while(1)
				{
					$rec = $stmt->fetch(PDO::FETCH_ASSOC);
					if ($rec == false)
					{
						break;
					}
					if ($area_id == $rec['id'])
					{
						echo '<option value="'.$rec['id'].'" selected>';
					}
					else
					{
						echo '<option value="'.$rec['id'].'">';						
					}
					echo $rec['name'];
					echo '</option>';
				}

			?>
		</select><br />
		性別
		<select name="gender">
			<?php 
//ふた通りくらいならwhile文を用いずに、簡単にかける。
				if ($gender == '男'){
					echo '<option value="男" selected>男性</option>';
					echo '<option value="女">女性</option>';
				}else{
					echo '<option value="男">男性</option>';
					echo '<option value="女" selected>女性</option>';					
				}
			?>
		</select><br />
		年齢
		<input name="age" type="text" style="width:100px;height:30px;" maxlength="10" value="<?php echo $age; ?>"><br />
		<input name="id" type="hidden" value="<?php echo $id; ?>">
		<br />		
		<input type="submit" value="保存" >
	</form>
</body>
</html>
