<?php
function iterate($dir) {
	if(preg_match('/vendor$/', $dir))
		return;
	
	$folder = dir($dir);
	
	$files = array();
	
	while ($file = $folder->read()) {
		if($file == '.' || $file == '..') {
			continue;
		}
		
		$file = $dir.'/'.$file;
		
		if(is_dir($file)) {
			iterate($file);
		} elseif(preg_match('/\.php$/', $file)) {
			convert($file);
		}
	}
	closedir($folder->handle);
}

function convert($file) {
	$content = file_get_contents($file);
	$content = str_replace("\t", "    ", $content);
	file_put_contents($file, $content);
}

iterate(dirname(__FILE__).'/src');
