<div id="response"></div>

<div id="progressbar">
    <div id="bar"></div>
    <div id="percent"></div>
</div>

<div><form enctype="multipart/form-data" method="post" action="admin/files/upload" id="upload" class="Form">
    <h2>Upload a file (Gamma stage)</h2>
    <label>Choose the target of the uploaded file and specify its destination:<br />
    <select name="target" id="uplTarget" style="width: 15em;" size="6">
        <option value="gallery">Image Gallery</option>
        <option value="media">Media (Video)</option>
        <option value="audio">Media (Audio)</option>
        <option value="downloads">Downloads</option>
        <option value="other" selected="selected">other</option>
    </select></label>
    <label><select name="section" size="6">
        <option value="caw">CaW</option>
        <option value="sotp">SotP</option>
        <option value="hf">Homefront</option>
        <option value="other">Other</option>
        <option value="all">All (does not require checkbox)</option>
        <option value="none" selected="selected">None</option>
    </select></label><br />
    <label>Include the content in the Hub? <acronym title="No effect when All or None selected"><input type="checkbox" name="plus_hub" value="ttrue" /></acronym></label><br /><br />
    <h4>Notice</h4>
    <p>You should only upload the proper media types to the specified location! e.g. into the gallery you should only upload images and into media (audio) only audio files.</p>
    <p>Else you are going to have trouble getting the content displayed!</p>
    <label>Optional:<br />
    Title: <input type="text" name="title" /></label><br />
    <label>Description:<br /><textarea name="description" style="width:50%;" rows="10"></textarea></label>
    <br /><br />
    <label>Please select the file for upload: <input name="Datei" type="file" /></label><br /><br />
    <input type="Submit" value="Submit" />
</form>

<style>
#progressbar { position:relative; width:400px; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
#bar { background-color: #123456; width:0%; height:20px; border-radius: 3px; }
#percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>

<script type="text/javascript">
var bar = $('#bar');
var progressbar = $('#progressbar');
var percent = $('#percent');

$('#upload').ajaxForm({
    beforeSubmit: function(a,f,o) {
        $.noticeAdd({text: 'File is being uploaded', type: 'info'});
        var percentVal = '0%';
        bar.width(percentVal);
        percent.html(percentVal);
    },
        uploadProgress: function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        bar.width(percentVal);
        percent.html(percentVal);
    },
    complete: function (xhr) {
        $('#response').html(xhr.responseText);
        // $('#response').slideUp(400).html(xhr.responseText).slideDown(400);
    }
});
</script>
</div>
