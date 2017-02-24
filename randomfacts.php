<?php
	$f_name = ("front/facts.txt");
	$fp = fopen($f_name,"r");
	$f_content= fread($fp, filesize($f_name));
	$facts = explode("\n",$f_content);
	fclose($fp);
	shuffle($facts);
	$i=0;
	while(list(,$code) = each($facts)) {
		if ($i>=1) { 
			break; 
		}
		echo utf8_encode($code);
		$i++;
	}
?>