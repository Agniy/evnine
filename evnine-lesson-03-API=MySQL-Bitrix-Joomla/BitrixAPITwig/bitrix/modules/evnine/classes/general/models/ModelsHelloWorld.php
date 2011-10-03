<?php
/**
 * ModelsHelloWorld
 * @package HelloWorld
 */
class ModelsHelloWorld extends ModelsBitrix
{	
	var $api;
	function __construct($api){
		$this->api=$api;
	}

	/** 
	 * ru: ��������� ������ ����� API � � �������������� ����.
	 *  
	 * @param mixed $param 
	 * @access public
	 * @return array
	 */
	function getQuery($param) {
		return $this->getFromCacheFunction($param, 'getAllElementArrayByID', /*$cache_keys=*/$param['arParams']['IBLOCK_ID']);
	}

	/**
	 * ru: ����� ����� ��������� ������ ����� API
	 * 
	 * @param array $param 
	 * @access public
	 * @return array
	 */
	function getAllElementUserNewAPI($param) {
	return $this->getAllFromAPI(
	array(
  '$arOrder'=>array()
  ,'$arFilter'=>array()
  ,'$arGroupBy'=>array()
  ,'$arNavStartParams'=>array()
  ,'$arSelectFields'=>array()
  ,'set_cache'=>false
     // ������� ������� ���� �� IBLOCK_ID
     // ����������� ������� $param['$arFilter']['IBLOCK_ID']
  ,'set_parser'=>''
     // Callback ������� ��� ��������� ������, 
     // ������� � models/ModelsBitrixInfoBlockParser.php 
  ,'get_section'=>false
     // �������� ������ ������, �� ��������� ������� ��-��
  ,'get_sef'=>''
     // ����� �� ����� ��� �������������� URL?
     // get_sef=true GetNextElement(false,false)
     // get_sef=false Fetch(false,false)
  ,'set_sef'=>''
     // ������������ ���� ����� ��� 
     // 'set_sef'=>#SECTION_ID#/#ELEMENT_ID#
     // SetUrlTemplates("", $param['set_sef']);
  ,'get_prop'=>false
     // �������� �������� ��-���, 
     // ���� ������� ������, �� 'get_prop'=false
  ,'set_key_id'=>false
     // ����� ������������ ���� ��� ������������ �������
     // ������: �� �����: 'set_key_id'=>'ID',
     // �� ������: 
     // array(
     //  555=>array('ID'='555',array(..))
     //  777=>array('ID'='7',array(..))
     // )
  ,'get_first'=>false
     // ������� ������ ��-� ������� ��� �����
     // �� ���������, �� ������:
     // array(
     //  '0'=>array('ID'='555',array(..))
     // )
     // C ������ ,'get_first'=>true,
     // �� ������:
     // array('ID'='555',array(..))
  ));
	}

	/** getAllElementArrayByID
	 *
	 * ru: ������ ������ ������� ��������� ������.
	 * 
	 * @param array $param 
	 * @access public
	 * @return array
	 */
	function getAllElementArrayByID($param) {
		return $this->api->getAllElementArrayByID(
			$id=$param['arParams']['IBLOCK_ID']
			,$idsection=''
			,$callback='parseData'
			,$arFilter=array()
			,$arSelect = array( 'IBLOCK_ID','ID','NAME')
			,$data=''
			,$key_is_id=false
			,$arOrder=array(
				'NAME' => 'ASC',
			)
			,$arGroupBy=false
			,array('nPageSize' => '10')
			,$get_prop=false
			,false/*first as array*/
			,$set_cache=true
		);
	}

	/**
	 * en: Reset any data
	 * ru: �������� ��� ������
	 */
	function setResetForTest($param){
	}
}
?>