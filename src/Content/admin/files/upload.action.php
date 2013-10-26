<?php
use Yamw\Lib\Mongo\AdvMongo;
use Yamw\Lib\UAM\UAM;

noTemplate();
hasToBeAdmin();

require_once 'upload.utilities.php';

// Upload file to DB
$grid = AdvMongo::gridFs($_POST['target']);

if ($grid->find(array('filename' => $_FILES['Datei']['name']))->count()) {
    addNotice('File <b>'.$_FILES['Datei']['name'].'</b> has already been uploaded!', 'error', true);
    $this->result = false;
    goto upload_end;
}

$blacklist = array('php', 'phtml', 'php3', 'php4', 'js', 'shtml', 'pl' , 'py', 'exe', 'cmd', 'bat', 'sh');
foreach ($blacklist as $file) {
    if (preg_match("/\.$file\$/i", $_FILES['Datei']['name'])) {
        addNotice('Executable files are not allowed! Aborting...', 'error', true);
        goto upload_end;
    }
}

$this->result = $grid->storeUpload('Datei', array('metadata' => array(
        'section' => $_POST['section'],
        'plus_hub' => isset($_POST['plus_hub']) && checkForTrue($_POST['plus_hub']),
        'downloads' => 0,
        'date_created' => new \MongoDate(),
        'date_edited' => new \MongoDate(),
        'comments' => array(),
        'uploaded_by' => array(
                'id' => UAM::getInstance()->getCurUserId(),
                'name' => UAM::getInstance()->getCurUserName()),
        )));
addNotice('Successfully uploaded <b>'.$_FILES['Datei']['name'].'</b> into the database!', 'success', true);

getLogger()->addNotice(
    'Uploaded File into '.$_POST['target'],
    array('filename' => $_FILES['Datei']['name'], 'target' => $_POST['target'], 'file' => $_FILES['Datei'])
);


$file_ext = explode('.', $_FILES['Datei']['name']);
$file_ext = $file_ext[count($file_ext) - 1];
meta('type', $file_ext);

if ($_POST['title']) {
    meta('title', $_POST['title']);
}
if ($_POST['description']) {
    meta('description', $_POST['description']);
}


switch ($_POST['target']) {
    case 'gallery':
        if ($file_ext == 'jpg') {
            $src_img = @imagecreatefromjpeg($_FILES['Datei']['tmp_name']);
            $img_type = 'jpg';
        } elseif ($file_ext == 'png') {
            $src_img = @imagecreatefrompng($_FILES['Datei']['tmp_name']);
            $img_type = 'png';
        } elseif ($file_ext == 'gif') {
            $src_img = @imagecreatefromgif($_FILES['Datei']['tmp_name']);
            $img_type = 'gif';
        } else {
            trigger_error('WTF? Security breach detected! Please contact the special squadron YouTube sent out weeks ago immediatly!!!');
        }

        if (!isset($src_img) || !$src_img) {
            trigger_error('Uploaded file does not exist? WTF?');
            return false;
        }

        meta('image_meta', array('x' => imageSX($src_img), 'y' => imageSY($src_img)));
    case 'media':
    case 'audio':
    case 'downloads':
        meta('favs', array('count' => 0, 'by' => array()));
        break;
}

$grid->update(array('_id' => $this->result), meta());

upload_end:
