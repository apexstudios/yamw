<div id="UnitContain" style="margin-bottom: 2em;">
<span onclick="NewUnit()" class="NewButton">Add a new Unit</span>
<h2>Select a unit to edit</h2>
<?php foreach($this->units as $unit): ?>
<div id="Unit<?php echo $unit->getId() ?>" onclick="selectUnit(<?php echo $unit->getId() ?>)">
    <div style="font-size: 1.2em;"><?php echo $unit->getName() ?></div>
    <span style="font-size: 0.8em;">Affiliation: <?php echo $unit->getAffiliation() ?>; Type: <?php echo $unit->getLayer() ?></span>
</div>
<?php endforeach; ?>
</div>
<div id="EditUnit"></div>

<script type="text/javascript">

function selectUnit(id) {
    unitLoad('admin/<?= $module ?>/edit/' + id + '/nt');
}

function UnselectUnit() {
    $('#EditUnit').slideUp(800);
    $('#UnitContain').slideDown(800);
}

function NewUnit() {
    unitLoad('admin/<?= $module ?>/new/nt');
}

function UnselectUnit() {
    $('#EditUnit').slideUp(800);
    $('#UnitContain').slideDown(800);
}

function unitLoad(target) {
    adLoad(target, 'UnitContain', 'EditUnit');
}

</script>
