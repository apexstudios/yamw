<?php
set_slot('title', 'Login');

global $Request;

if ($this->showForm) {
?>
<h2>Login</h2>
<h3 style="display: none">Notice: You will get redirected to the homepage after
successfully logging in</h3>
<form action="user/login" method="post" id="RegForm">
<table>
    <tr>
        <td>Nickname:</td>
        <td><input type="text" name="name" id="RegForm_name" /></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input type="password" name="pw" id="RegForm_pw" /></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td><input type="submit" value="Login" class="submitbutton" /></td>
    </tr>
</table>
<input type="hidden" name="prev_site" id="RegForm_prev_site"
    value="<?= (@$Request->Id) ? $Request->Id : base64_encode(getAbsPath()) ?>" />
    <input type="hidden" name="ajax" value="0" id="RegForm_ajax" />
</form>

<div id="response"></div>

<script type="text/javascript">

$('#RegForm').submit(function() {
    $(this).ajaxSubmit({
        target: '#response',
        url: 'user/login/nt',
        beforeSerialize: function (obj, b) {
            if($('#RegForm_pw').val() == '' || $('#RegForm_name').val() == '') {
                $('#response').html('<font color="#CC8888">Sorry, but you '+
                    'have to fill out the form BEFORE you send the data.</font>');
                return false;
            }

            // To tell the action that we are using ajax
            $('#RegForm_ajax').val(1);

            $('#response').html('<font color="#115588">Sending...</font>');
        }
    });
    return false;
});

</script>
<?php
}
if (!$this->showForm) {
    if ($this->result == 'success') {
        print_c('You have been logged in successfully!', COLOR_SUCCESS);
        println('<a href="'.$prev_site.'">You should be redirected to the homepage now. If not, click here</a>');
    } else {
        print_c($this->result, COLOR_ERROR);
    }
}
