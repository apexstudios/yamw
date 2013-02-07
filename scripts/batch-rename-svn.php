<?php
if(!($argc == 4)) die("Invalid Usage!");

$name_folder = $argv[1];
$name_from = $argv[2];
$name_to = $argv[3];

$folder = dir(__DIR__.DIRECTORY_SEPARATOR.$name_folder);

$files = array();

while ($file = $folder->read()) {
    if (preg_match("/$name_from$/", $file)) {
        echo "Renaming $name_folder".DIRECTORY_SEPARATOR."$file to $name_folder".DIRECTORY_SEPARATOR.preg_replace("/$name_from$/", $name_to, $file)." \n";
        exec("svn mv $name_folder".DIRECTORY_SEPARATOR."$file $name_folder".DIRECTORY_SEPARATOR.preg_replace("/$name_from$/", $name_to, $file));
    }
}
closedir($folder->handle);

echo "\n\nCommitting changes!\n\n";
system("svn ci $name_folder -m \"* Renaming files *$name_from to *$name_to in $name_folder\"");
