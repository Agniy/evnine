<?php
error_reporting(2046);
include_once 'evnine.php';
include_once 'debug/evnine.debug.php';

$evnine = new Controller();
$ctrlr = $evnine->getControllerForParam(
	array(
		'controller' => 'validation',
		'form_data' => $_REQUEST,
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
	value="<?php echo $ctrlr['REQUEST_OUT']['PathID']; ?>" 
	name="<?php echo $ctrlr['inURL']['default']['PathID'] ?>"
/>
<input 
	type="submit"
	value="submit"
/>
</form>


<?php

print_r2($ctrlr, "array",false);

?>
