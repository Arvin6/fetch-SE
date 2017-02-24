<html>
<head>
<title>Fetch!,Your Search engine</title>
<style>
#r{
	width:51%;
	margin:auto;
	padding:290px;
	position:absolute;
	margin-left:34px;
}
#fact{
	top:6%;
	position:absolute;
	left:370px;
	top:-20px;
}
input[type=text]
{	font-size: 17px;
	font-family:Comic Sans MS;
	height:45px;
	width:50%;
	border: 2px solid #FA8258;
	background:transparent;
	position:absolute;
}

input[type=text]:focus
{
	outline:none;
	box-shadow: 9px 10px 10px #888888;
	
}
</style>
</head>
<body background="bg-6.jpg">
<form name="search" method="get" action="results.php">
<div id="fact">
<?php include 'fact.php'; ?>
</div>
<div id="r">
<div>
<input name="qry" autocomplete=off autofocus type="text" placeholder="Hey bud,what're you searching for?" >
<input type="submit" style="display:none;"><br><br>
 </div>
 </form>
<div style="text-align:center; padding-left:190px; margin:auto; ">
<div style=" font-family:Trebuchet MS; text-align:center; font-size:20px; color:#FF6600; margin:auto; position:absolute;">
<b><ul><br>DID YOU KNOW?</ul></b><br></div>
<div style=" font-family:Trebuchet MS; text-align:center; left:-115px; top:75px; color:#FF6600; position:relative;">
<?php include'randomfacts.php'; ?></div>
</div>
</div>
</body>
</html>