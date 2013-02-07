<div id="StaffContain" style="margin-bottom: 2em;">
<span onclick="NewStaff()" class="NewButton">Add a new Staff Member</span>
<h2>Select a staff member to edit</h2>
<table>
<tr><th>Name</th><th>Position</th></tr>
<?php foreach($this->members as $staff): ?>
<tr id="StaffMember<?php echo $staff->getId() ?>" onclick="selectStaff(<?php echo $staff->getId() ?>)">
    <td><?php echo $staff->getName() ?></td><td><?php echo $staff->getPosition() ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
<div id="EditStaff"></div>

<script>
/* <![CDATA[ */
function selectStaff(id) {
    staffLoad('about/edit/' + id);
}

function NewStaff() {
    staffLoad('about/new');
}

function staffLoad(target) {
    container = 'StaffContain';
    area = 'EditStaff';
    adLoad(target, container, area);
}

function UnselectStaff() {
    $('#EditStaff').slideUp(800);
    $('#StaffContain').slideDown(800);

}
/* ]]> */
</script>
