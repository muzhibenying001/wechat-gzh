<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!-- 视窗 大家日后只要是移动站点的网页都加上此句 -->
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<title>Document</title>
	<style>
		body{
			font-size: 14px;
		}
	</style>
</head>
<body>
	<h3>你好世界</h3>
	<img src="<?php echo $_SESSION['userinfo']['headimgurl'] ?>" style="width: 200px;">
</body>
</html>