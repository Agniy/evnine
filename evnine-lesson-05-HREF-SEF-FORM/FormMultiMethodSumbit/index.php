<?php
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new EvnineController();
$ctrlr = $evnine->getControllerForParam(
	array(
		'controller' => 'validation',
		'REQUEST' => $_REQUEST,
		'ajax' => 'ajax',
	)
);
?>
<form 
	method="get" 
	action=<?php echo $ctrlr['inURL']['default']['pre'].$ctrlr['inURL']['post']; ?>
>
<?php echo $ctrlr['inURL']['default']['inputs']; ?>
<input type="text" 
	value="<?php echo $ctrlr['REQUEST_OUT']['TestID']; ?>" 
	name="<?php echo $ctrlr['inURL']['default']['TestID'] ?>"
/>
<input 
	type="submit"
	name="<?php echo $ctrlr['inURL']['default']['submit'] ?>"
	value="submit default"
/>

<input 
	type="submit"
	name="<?php echo $ctrlr['inURL']['submit_1']['submit'] ?>"
	value="Method: submit_1"
/>
<input 
	type="submit"
	name="<?php echo $ctrlr['inURL']['submit_2']['submit'] ?>"
	value="Method: submit_2"
/>
<input 
	type="submit"
	name="<?php echo $ctrlr['inURL']['submit_3']['submit'] ?>"
	value="Method: submit_3"
/>
<input 
	type="submit"
	name="<?php echo $ctrlr['inURL']['submit_4']['submit'] ?>"
	value="Method: submit_4"
/>
</form>
<?php
print_r2($ctrlr);
?>