<?php
$search='';
$replace='';
$basedir='.';
if(isset($_GET['search'])&&isset($_GET['replace'])){
	$search=$_GET['search'];
	$replace=$_GET['replace'];
	if(isset($_GET['basedir'])){
		$basedir=$_GET['basedir'];
		if($basedir[strlen($basedir)-1]=='/'){
			$basedir=substr($basedir,0,strlen($basedir)-1);
		}
	}
	if(empty($search)||empty($search)){
		echo 'Cannot get required string.<br/>';
	}else{
		checkdir($basedir,$search,$replace);
		echo 'Done!<br/><a href="?">Return</a><br/>';
		exit(0);
	}
}
function checkdir($basedir,$search,$replace) {
	if ($dh = opendir($basedir)) {
		while (($file = readdir($dh)) !== false) {
			if ($file != '.' && $file != '..') {
				if (!is_dir($basedir . '/' . $file)) {
					$result=file_modify($search,$replace, "$basedir/$file");
					if($result){
						echo 'Replaced file: ' . "$basedir/$file" . '<br/>';
					}else{
						echo 'Skipped file: ' . "$basedir/$file" . '<br/>';
					}
				} else {
					$dirname = $basedir . '/' . $file;
					checkdir($dirname);
				}
			}
		}
		closedir($dh);
	}
}
function file_modify($search_contents, $replace_contents, $filename) {
	$fp = file_get_contents($filename);
	if(strpos($fp,$search_contents)===FALSE){
		return FALSE;
	}
	$new_fp = str_replace($search_contents, $replace_contents, $fp);
	file_put_contents($filename, $new_fp);
	return TRUE;
}
?>
<html>
	<head>
		<title>PHP-rpl</title>
		<meta charset="UTF-8"/>
	</head>
	<body>
		<h1>PHP-rpl</h1>
		<form action="" method="get">
			Base dir:<input type="text" name="basedir" id="basedir" value="<?php echo $basedir; ?>" />($_GET['basedir']) <br />
			Search string:<input type="text" name="search" id="search" value="<?php echo $search; ?>" />($_GET['search']) <br />
			Replace string:<input type="text" name="replace" id="replace" value="<?php echo $replace; ?>" />($_GET['replace']) <br />
			<input type="submit" value="Go"/><br /><br />
		</form>
		by Github <a href="https://github.com/juzeon">@juzeon</a><br />
	</body>
</html>