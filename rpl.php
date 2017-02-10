<?php
$search='';
$replace='';
$basedir='.';
$log=FALSE;
$cs=1;
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
		checkdir($basedir,$search,$replace,$log);
		echo 'Done!<br/><a href="?">Return</a><br/>';
		exit(0);
	}
}
function checkdir($basedir,$search,$replace,$log) {
	$logfile=FALSE;
	if(!empty($log)){
		$logfile=fopen($log,'w');
	}
	if ($dh = opendir($basedir)) {
		while (($file = readdir($dh)) !== false) {
			if ($file != '.' && $file != '..') {
				if (!is_dir($basedir . '/' . $file)) {
					$result=file_modify($search,$replace, "$basedir/$file");
					if($result){
						echo time() . ': Replaced file: ' . "$basedir/$file" . '<br/>';
						if(!empty($logfile)){
							fwrite($logfile,time() . ': Replaced file: ' . "$basedir/$file" . PHP_EOL);
						}
					}else{
						echo time() . ': Skipped file: ' . "$basedir/$file" . '<br/>';
						if(!empty($logfile)){
							fwrite($logfile,time() . ': Skipped file: ' . "$basedir/$file" . PHP_EOL);
						}
					}
				} else {
					$dirname = $basedir . '/' . $file;
					checkdir($dirname,$search,$replace,$log);
				}
			}
		}
		closedir($dh);
	}
	if(!empty($logfile)){
		fclose($logfile);
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
		Replace all strings in all files easily. <br />
		<hr />
		<h2>Settings:</h2>
		<form action="" method="get">
			Base dir: <input type="text" name="basedir" id="basedir" value="<?php echo $basedir; ?>" />($_GET['basedir']) <br />
			Search string: <input type="text" name="search" id="search" value="<?php echo $search; ?>" />($_GET['search']) <br />
			Replace string: <input type="text" name="replace" id="replace" value="<?php echo $replace; ?>" />($_GET['replace']) <br />
			<input type="submit" value="Go"/><br />
			<hr />
			<h2>Advanced:</h2>
			Case sensitive：<input type="checkbox" checked="true" name="cs" value="<?php echo $cs; ?>" />($_GET['cs'])<br />
			Log to file: <input type="text" name="log" id="log" value="<?php echo $log; ?>" />($_GET['log']) example:<?php echo time().'.log'; ?><br />
		</form>
		by Github <a href="https://github.com/juzeon">@juzeon</a><br />
	</body>
</html>