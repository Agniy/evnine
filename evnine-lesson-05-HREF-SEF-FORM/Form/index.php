<?php
error_reporting(0);
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
	value="submit"
/>
</form>
<?php
print_r2($ctrlr, "array",false);
?>