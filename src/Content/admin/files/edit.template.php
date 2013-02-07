<h1>You have been rick rolled!</h1>
<h2>Don't click ANYTHING!</h2>
<form enctype="multipart/form-data" method="post" action="admin/files/update" id="upload" class="Form">
    <h2>Upload a file (Gamma stage)</h2>
    <label>Choose the target of the uploaded file and specify its destination:
    <br />
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
    </select></label>
    <br />
    <label>Include the content in the Hub?
    <acronym title="No effect when All or None selected"><input type="checkbox" name="plus_hub" value="ttrue" /></acronym></label>
    <br /><br />

    <label>Optional (please leave empty if unused):<br />
    Title: <input type="text" name="title" /></label><br />
    <label>Description:<br /><textarea name="description" style="width:50%;" rows="10"></textarea></label>
</form>
