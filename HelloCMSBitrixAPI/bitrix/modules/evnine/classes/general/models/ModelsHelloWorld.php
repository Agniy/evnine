<?php
/**
 * HelloWorld
 * @package HelloWorld
 * @author ev9eniy
 * @version 1.0
 * @created 04-apr-2010 11:03:41
 */
class ModelsHelloWorld
{	

	var $api;

	/**
	 * Constructor
	 */
	function __construct($api){
		$this->api=$api;//save api (Bitrix link to class)
	}

function getQuery($param) {
		return $this->api->getAllElementArrayByID(
			$id=$param['arParams']['ID']
			,$idsection=''
			,$callback='parseData'
			,$arFilter=array()
			,$arSelect = array( 'IBLOCK_ID','ID','NAME'/*,"PREVIEW_TEXT"*/)
			,$data=''
			,$key_is_id=false
			,$arOrder=array(
				'NAME' => 'ASC',
			)
			,$arGroupBy=false
			,array('nPageSize' => '10')
			,$get_prop=false
		);
	}

	function setResetForTest($param){//Reset any data
		//echo 'setResetForTest<br />';
	}
}
?>