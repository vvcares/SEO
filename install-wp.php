<?php

echo getcwd() . "\n";
$dir = getcwd();
// This file is for..
// - Downloading latest Wordpress installer, extract it & open the WP installation as usual..
// - We removing the defaule WP license.txt, readme.html, wp-content/plugins/hello.php files, which are useless
// - For security reasons, we COPY this installer file as installer-wp.php-HourMinuteSecond & Deleting this file


	echo '<pre>';
	echo '<span style="color:blue">DOWNLOADING...</span>'.PHP_EOL;

	// Download file
	file_put_contents('wp.zip', file_get_contents('http://wordpress.org/latest.zip'));
	
	$zip = new ZipArchive();
	$res = $zip->open('wp.zip');
	if ($res === TRUE) {
		
		// Extract ZIP file
		$zip->extractTo('./');
		$zip->close();
		unlink('wp.zip');
		
		// Copy files from wordpress dir to current dir
		$files = find_all_files("wordpress");
		$source = "wordpress/";
		foreach ($files as $file) {
			$file = substr($file, strlen("wordpress/"));
			if (in_array($file, array(".",".."))) continue;
			if (!is_dir($source.$file)){
				echo '[FILE] '.$source.$file .' -> '.$file . PHP_EOL;
				rename($source.$file, $file);
			}else{
				echo '[DIR]  '.$file . PHP_EOL;
				@mkdir($file);
			}
		}


		
		// Check if copy was successful
		if(file_exists('index.php')){
		
			// Redirect to WP installation page
			echo '<meta http-equiv="refresh" content="1;url="<?php $dir/ ?>" />';
		
		}else{
			echo 'Oops, that didn\'t work...';
		}
	} else {
		echo 'Oops, that didn\'t work...';
	}
	
	function find_all_files($dir) { 
    $root = scandir($dir); 
    foreach($root as $value) { 
        if($value === '.' || $value === '..') {continue;} 
        $result[]="$dir/$value";
        if(is_file("$dir/$value")) {continue;} 
        foreach(find_all_files("$dir/$value") as $value) 
        { 
            $result[]=$value; 
        } 
    } 
    return $result; 
}

system("rm -rf ".escapeshellarg('wordpress')); // Remove wordpress dir
unlink( 'license.txt' ); // We remove licence.txt
unlink( 'readme.html' ); // We remove readme.html
unlink( 'wp-content/plugins/hello.php' ); // We remove Hello Dolly plugin

// For security reasons, we COPY this installer file as PHPx & Deleting this file
echo "For security reasons, we COPY this installer file as installer-wp.php-HourMinuteSecond & Deleting this file";
echo copy((__FILE__),((__FILE__)."-".date("his")));
unlink(__FILE__);

?>