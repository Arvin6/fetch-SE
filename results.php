<?php
$con=mysqli_connect ( "localhost", "root", "arvind","fetchpublic" );
if(!$con)
{die("DB not connected");}
?>
<?php
$search = strip_tags($_GET['qry']);
$search_exploded = explode ( " ", $search );
 $x = 0;
 ?>
 
 
  <?php
 $qry="Select Distinct u.url,u.title from urls u 		
 join word_url wu 
 join words w 
 on w.id = wu.word_id and wu.url_id = u.id
 where ";											// Query here
 foreach($search_exploded as $search_each)
 {
$x++;
if($x==1)
 {
	 $qry .= "w.word like \"$search_each\" ";
 }
 else
 {	
	 $qry .="or w.word like \"$search_each\" ";
 }	 
 }
 $runs = mysqli_query( $con,$qry ) or die("<b>Query not executed1</b>");
 $total_recs=mysqli_num_rows($runs);
 ?>
 
<html>
<head><title>Fetch results</title></head>
<style>
#hotlinks{
	border:1px solid #FA8258;
	margin-top:10%;
	margin-left:70%;
	margin-right:30%
	width:auto;
	height:auto;
	position:fixed;
}

#fix{
	position:fixed;
}
#content{
	margin-top:10%;
	margin-bottom:10%;
	margin-left:10%;
	margin-right:10%;
}

input[type=text]
{	font-size: 17px;
	font-family:Comic Sans MS;
	height:35px;
	width:45%;
	border: 2px solid #FA8258;
	background:transparent;
}

input[type=text]:focus
{
	outline:none;
}

div.r {
    text-indent: 125px;
	padding-top:5px;
}

a:link {
    text-decoration: none;
	color:#FA8258;
}

a:visited {
    text-decoration: none;
	color:#088A68;
}
a:hover {
    text-decoration: underline;
}
a:active {
    text-decoration: none;
}
</style>
<body background="bg-6.jpg">
<br>
<div name="results">
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
<div class="r" name="input">
<input name="qry" autocomplete=on type="text" value="<?php echo "$search"; ?>" >
 </div>
</form>

<div name="contents" style="position:absolute;">

<?php
  if ((isset($_GET['qry']))and ($search!=""))
 {
	 echo "<div id=\"hotlinks\">  ";
	 $hot=strip_tags($_GET['qry']);
	 $t_qr="SELECT Distinct u.title,u.url FROM `urls` u WHERE title like \"%$hot%\" Limit 10 ";
	 $sug=mysqli_query($con,$t_qr) or die("No Suggestions");
	 $nu=mysqli_num_rows($sug);
	 if($nu>0)
	 { 
	 echo"<div id=\"content\" >";
	 echo "<div style=\"color:#585858;font-size:18px;margin-top:7px\"> Are you looking for one of these? (hotlinks)</div>";
	 while($row=mysqli_fetch_array($sug))
	 {
		echo "<div> -> <a style=\"text-indent:120px;\" href=\"".$row['url']."\">".$row['title']."</a> </div>";
	 }
	 echo "<div style=\"margin-right:50%;margin-left:45%;margin-top:14px;\"><button type=\"button\" onclick=\"hide()\" > <img src=\"hide2.png\" height=\"25\"></button></div>";
	 echo "</div>";
	 }
	 echo "</div>";
 }?>
 
 
 <?php
 $num_rec=28;
 if (isset($_GET["page"])) 
{ 
$page = $_GET["page"];
} 
else { $page=1; }
$start=($page-1)* $num_rec ;
$x=0;  
$qry="Select Distinct u.url,u.title from urls u 		
 join word_url wu 
 join words w 
 on w.id = wu.word_id and wu.url_id = u.id
 where ";											// Query here
 foreach($search_exploded as $search_each)
 {
$x++;
if($x==1)
 {
	 $qry .= "w.word like \"$search_each\" ";
 }
 else
 {	
	 $qry .="or w.word like \"$search_each\" ";
 }	 
 }
 $qry.="Limit $start,$num_rec";
  //echo $qry;
  $run = mysqli_query( $con,$qry ) or die("<b>Query not executed2</b>");
  $num=mysqli_num_rows($run);
?>

 
 <?php
 echo "<br>";
 echo "<div class=\"r\" style=\"color:#585858\" > Showing page ".$page." and ".$num." results for <b style=\"color:#F78181\">\"$search\"</b> out of ".$total_recs." results </div>";

 ?>
 <hr>

 <?php
 if($num>0)
 {
$resu=array();
 while($rs=mysqli_fetch_array($run))
 {	 
$resu{$rs['url']}=$rs['title'];
 }
 	echo "<div class=\"r\" style=\" font-size:16px; color:#088A68; font-family:sans-serif; padding-top:5px;\" > <table>";
	 foreach ($resu as $url => $title)
	 {
	echo "<tr><h4><a href=\"".$url."\" data-href=".$url.">".$title."</a><br>"; 
	echo "<cite style=\"padding-top:1px;padding-left:130px;color:#A4A4A4;font-style:italic; font-size:14px;\">".$url."</h4></tr>"; 
	 }
	echo "</table></div>";
	$total_pages = ceil($total_recs/ $num_rec);?>
	<div id=Pages style="margin-left:32%;margin-bottom:20px;margin-top:10px;position:absolute;">
<br><hr>
<table cellspacing=6px" style="position:relative; word-wrap:break-word;font-size:19px;width:550px;">
<tr><td style="text-align:left;">
<a href="results.php?qry=<?php echo $search;?>&page=1"><b>Go-first</b></a></td>
<td style="text-align:left;"><a href="results.php?qry=<?php echo $search;?>&page=<?php if($page>1){echo $page-1;}else echo $page; ?>"><b><?php echo "<<" ?></b></a></td>
<?php
	if($total_pages<10)
	{
		$stpg=1;
		$endpg=$total_pages;
	}
	else
	{
		if(($page-5)>0)
		{
		$stpg=$page-5;
		}else{$stpg=1;}
		if(($page+5)>$total_pages)
		{$endpg=$total_pages;}
		else
		{
			if($total_pages>10)
			{
			if(($page>0)and($page<5))
			{$endpg=11;}
			else{$endpg=$page+5;}
		}
			else
			{
				$endpg=$total_pages;
			}
		}
	}
	for($i=$stpg; $i<$endpg+1; $i++)
	{
		echo "<td style=\"text-align:center;\"><a href='results.php?qry=".$search."&page=".$i."' >".$i."</a></td> ";
	}

?>
<td style="text-align:right;"><a href="results.php?qry=<?php echo $search;?>&page=<?php if($page<$total_pages){echo $page+1;}else echo $page; ?>"><b><?php echo ">>" ?></b></a></td>
<td style="text-align:right;"><a href="results.php?qry=<?php echo $search;?>&page=<?php echo $total_pages;?>"><b>Go-last</b></a></td></tr></table><br><hr>
</div>
	<?php
 }

 else
 {
	 echo "<div style=\"text-align:center;\">";
	 echo "<p style=\"font-size:22;\">Oops,seems like \"$search\" isn't a keyword,or";
	 echo " this keyword isn't indexed yet.<br>";
	 echo "<p style=\"font-size:17; \">why not try searching for a little more generalised keyword?<br>";
	 echo " <p style=\"font-size:17;\">Try again fella...</div>";
 }
 
 ?>
</div>
</div>

<script>
function hide()
{
	document.getElementById("hotlinks").style.visibility="hidden";
}
</script>

</body>
</html>