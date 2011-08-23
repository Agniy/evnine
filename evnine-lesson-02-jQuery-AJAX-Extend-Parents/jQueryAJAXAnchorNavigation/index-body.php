<a id="" href="/">/</a>                   
<br/>

<a id="clicked" href="index.php">index.php</a>                   
<br/>
<a id="test_href" href="index.php?c=param1&m=param2">index.php?c=param1&m=param2</a>
<br/>
<a id="param_href" href="index.php?param=param&data[]=1&data[]=1&data[]=1&data[]=1&data[]=1&data[]=1&data[]=1">index.php?param=param&data[]=1</a>
<br/>
<a href="index.html">index.html - SEF</a>
<br/>
<a class="json" href="index.php">index.php class="json"</a>                   
<br/>
<a class="json" href="index.html">index.html class="json"</a>                   
<br/>

<a class="body" href="index.php">index.php class="body"</a>                   
<br/>
<a class="body" href="index.html">index.html class="body"</a>                   
<br/>


<a href="http://google.com">google.com</a>
<a href="hop">hop</a>
<form 
	method="post" 
	action=<?php echo $ctrlr['inURL']['default']['pre'].$ctrlr['inURL']['post']; ?>
>
<?php echo $ctrlr['inURL']['default']['inputs']; ?>
<input id="submit_0" type="text" 
	value="<?php echo $ctrlr['REQUEST_OUT']['PathID']; ?>" 
	name="<?php echo $ctrlr['inURL']['default']['PathID'] ?>"
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