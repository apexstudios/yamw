<?php
getNotices();

if($this->result && $_POST['target'] == 'gallery') {
?>
<script>
// Issue the thumnails
reissueThumbnails('<?php echo $this->result ?>', 'default');
</script>

<div id="ghie"></div>
<?php
} elseif(!$this->result) {
    println('Error at uploading file <i>'.$_FILES['Datei']['name'].'</i>');
}
?>