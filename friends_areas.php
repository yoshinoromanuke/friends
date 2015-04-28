<?php
//1.データベースに接続する

$dsn = 'mysql:dbname=Friends_System;host=localhost';
$user = 'root';
$password = 'yoshi117';
$dbh = new PDO($dsn,$user,$password);
//文字化け解消
$dbh->query('SET NAMES utf8');
//以下の２文を上の一行で実行。
// $sql = "SET NAMES utf8";
// $result = mysql_query($sql);

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">  
<html>
<head>
	<meta http-equiv="Content-Type"content="text/html;charset=UTF-8"> 
	<title>都道府県友達リスト</title>
</head>
<body>
	<?php //echo $_SERVER['HTTP_USER_AGENT']; ?>
<h1>都道府県友達リスト</h1>
		<?php
		//2.SQLで指令をだす
		
		$sql ='SELECT areas.*,count(friends.id) AS `friends_count` ';
		$sql .='FROM areas LEFT OUTER JOIN friends';
		$sql .= ' ON areas.id = friends.area_id ';
		$sql .= 'GROUP BY areas.id';
		//var_dump($sql);
		//echo $sql;
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		echo '<table style="border:1px;">';
		while(1){
			$rec = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($rec == false) 
			{
				break;
			}
				if ($rec['friends_count']==0)
				{
					echo '<tr>';
					echo '<td>'.$rec['id'].'</td>';
					echo '<td>'.$rec['name'].'</td>';
					echo '<td>'.$rec['friends_count'].'</td>';
					echo '</tr>';
				}
				else
				{
					echo '<tr>';
					echo '<td>'.$rec['id'].'</td>';
					echo '<td><a href="friends_friends.php?area_id='.$rec['id'].'">'.$rec['name'].'</a></td>';
					echo '<td>'.$rec['friends_count'].'</td>';
					echo '</tr>';
				}
			}
		echo '</table>';
		//3.データベースから切断する
		$dbh=null;
	?>




	<?php
	//3.SQL文で都道府県リストをデータベースから取得し、表示させる。
	// $sql ='SELECT * FROM `areas`;';
	// 	//FROMでメインのテーブルを指定。表を結合を左側or右側からを指定し、結合させるテーブルを指定。今回は不要
	// 	//$sql .='FROM areas LEFT OUTER JOIN friends';
	// 	//メインのテーブルと結合させるテーブルの何が一致しているのか明示。
	// 	//$sql .= 'ON areas.id = friends.area_id.areas.id ';
	// 	//
	// 	//$sql .= 'GROUP BY areas.id';
	// 	//echo $sql;
	// 	$stmt = $dbh->prepare($sql);
	// 	$stmt->execute();
	// 	echo '<table style="border:1px;">';
	// 	while(1){
	// 		$rec = $stmt->fetch(PDO::FETCH_ASSOC);
	// 		//var_dump($rec);
	// 		if ($rec == false) {
	// 			break;
	// 		}
	// 		echo '<tr>';
	// 		echo '<td>'.$rec['id'].'</td>';
	// 		echo '<td><a href="friends_friends.php?area_id='.$rec['id'].'">'.$rec['name'].'</a></td>';
	// 		echo '</tr>';
	// 	}
	// 	echo '</table>';
	// 	//3.データベースから切断する
	// 	$dbh=null;
	?>

</body>
</html>
