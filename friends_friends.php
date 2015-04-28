<?php
//先生のlist.php DELETE

//1.データベースに接続する
$dsn = 'mysql:dbname=Friends_System;host=localhost';//
$user = 'root';
$password = 'yoshi117';
$dbh = new PDO($dsn,$user,$password);
$dbh->query('SET NAMES utf8');
$area_id = $_GET['area_id'];
//削除フラグの状態を調べて、存在する場合削除処理を行う
if (isset($_GET['del_flag']))
{
	$del_sql = 'DELETE FROM `friends` WHERE id='.$_GET['friend_id'].';';
	$del_stmt = $dbh->prepare($del_sql);
	$del_stmt->execute();
	//別画面に遷移する処理

	header('Location: http://'.$_SERVER['HTTP_HOST'].'/friends/friends_areas.php');	
}
//都道府県名の取得
$sql = 'SELECT `name` FROM `areas` WHERE `id` = '.$area_id.";";
var_dump($sql);
//echo $sql;
$stmt = $dbh->prepare($sql);
$stmt->execute();
//データを受け取る
$area_name = $stmt->fetch(PDO::FETCH_ASSOC);
//男性の数
$number_of_male = 0;
//女性の数
$number_of_female = 0;
//2.SQLで指令をだす
$sql = 'SELECT * FROM `friends` WHERE `area_id` = '.$area_id;
$stmt = $dbh->prepare($sql);
$stmt->execute();

//配列の初期化
$friends_array = array();
//area_idが選択された都道府県と等しい友達全てを表示
while(1)
{
	$rec = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($rec == false) 
	{
		break;
	}
	$friends_array[] = $rec; 
	if ($rec['gender'] == '男')
	{
		$number_of_male += 1;
	}
	else
	{
		$number_of_female += 1;
	}
}
//3.データベースから切断する
$dbh=null;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>FriendsSystem</title>
<script type="text/javascript">
	function fnc_delbutton(area_id,friend_id)
	{
			if (confirm('削除しますか？'))
			{
				location.href='friends_friends.php?id=' + area_id + '&friend_id=' + friend_id + '&del_flag=1';
				return true;
			}
			else
			{
				return false;
			}
	}
</script>
</head>
<body>
	<h2><?php echo $area_name['name']; ?>お友達リスト</h2>
	<h3>男性:<?php echo $number_of_male; ?>名、女性:<?php echo $number_of_female; ?>名</h3>
	<?php
		echo '<ul>';
		foreach ($friends_array as $friends_each) {
			echo '<li>';
			echo $friends_each['name'].'　性別：'.$friends_each['gender'].'　年齢'.$friends_each['age'].' 歳';
			echo '<input type="button" value="編集" onclick="location.href=\'friends_update.php?id='.$friends_each['id'].'\'">';
			//echo '<input type="button" value="削除" onclick="if (confirm(\'削除しますか？\')){location.href=\'friends_list.php?id='.$area_id.'&friend_id='.$friends_each['id'].'&del_flag=1\'}">';
			echo '<input type="button" value="削除" onclick="fnc_delbutton('.$area_id.','.$friends_each['id'].');" >';
			echo '</li>';
		}
		echo '</ul>';
	?>
	<br/>
	<input type="button" value="追加" onclick="location.href='friends_add.php'">

</body>
</html>