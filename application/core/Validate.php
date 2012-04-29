<?php
class Core_Validate 
{
	protected $_request;
	
	/**
	 * Constructor for validation
	 * Enter description here ...
	 * @param unknown_type $the_o_request
	 */
	public function __construct($the_o_request)
	{
		$this->_request = $the_o_request;
	}
	
	/**
	 * Get validate items from ini file
	 * Enter description here ...
	 */
	public function a_fGetValidateItemsFromIniFile()
	{
		$a_ValidateItems = array();
		$sz_Controller = $this->_request->getControllerName();
		$sz_Action = $this->_request->getActionName();
		$sz_ModuleDir = Zend_Controller_Front::getInstance()->getModuleDirectory();
		/* Get file ini */
		$sz_ValidateFile = $sz_ModuleDir . DIRECTORY_SEPARATOR . 'validate/' . ucfirst($sz_Controller) . '.ini';
		if(is_file($sz_ValidateFile))
		{
			try{
			$o_ValidateObject = new Zend_Config_Ini($sz_ValidateFile, $sz_Action);
			}catch(Exception $e)
			{
			}
			if($o_ValidateObject)
			{
				$a_ValidateItems = $o_ValidateObject->toArray();
			}
		}
		return $a_ValidateItems;
	}
	
	/**
	 * Validate all items
	 * Enter description here ...
	 */
	public function o_fValidate()
	{
		/* Message Handler to validate */
		$o_MessageHandler = new Core_MessageHandle();
		/* Get validate item in ini file */
		$a_ValidateItems = $this->a_fGetValidateItemsFromIniFile();
		if($a_ValidateItems)
		{
			/* Build validate items from ini file */
			$a_Items = $this->a_fBuildValidate($a_ValidateItems);
			foreach( $a_Items as $key => $item)
			{
				/* Get value from method */
				$sz_Value = $this->getParam($key, $item['method']);
				/* Do validate for item */
				$o_ZendValidate = new Zend_Validate();
				foreach ($item['validate'] as $a_Validate)
				{
					/* If has error, set message handler to error and add validate message */
					if(!$o_ZendValidate->is($sz_Value, $a_Validate['validateClass'], (array)$a_Validate['params'], $a_Validate['Namespace']))
					{
						$o_MessageHandler->v_fSetError();
						$o_MessageHandler->addMessage($a_Validate['Message']);
					}
				}
			}
		}

		return $o_MessageHandler;
	}
	
	/**
	 * Build validate items from ini file
	 * Enter description here ...
	 * @param unknown_type $the_a_Items
	 */
	public function a_fBuildValidate( $the_a_Items )
	{
		$a_Items = array();
		if( $the_a_Items )
		{
			foreach ($the_a_Items as $sz_Key => $a_Item )
			{
				$sz_ValidateClass = '';
				$sz_Key = $a_Item['key'];
				$sz_Method = $a_Item['method'];
				/* If has required, set it is an item */
				if($a_Item['required'])
				{
					$a_Items[$sz_Key] = array(
						'method' => $sz_Method,
						'validate' => array(array(
							'validateClass' => 'NotEmpty',
							'Message' => $a_Item['requiredMessage']
						))							
					);
				}
				/* Build item from validate */
				if( $a_Item['validate'] )
				{
					foreach ($a_Item['validate'] as $item )
					{
						$sz_ValidateClass = ($item['ValidateClass'])?$item['ValidateClass']:$sz_ValidateClass;
						$sz_ValidateMessage = ($item['Message'])?$item['Message']:$sz_ValidateClass;
						$sz_ValidateNameSpace = ($item['Namespace'])?$item['Namespace']:'';
						$a_Params = ($item['Params'])?$item['Params']:array();
						$a_Items[$sz_Key]['validate'][] = array(
							'validateClass' => $sz_ValidateClass,
							'Namespace' => $sz_ValidateNameSpace,
							'params' => $a_Params,
							'Message' => $sz_ValidateMessage
						);
					}
				}
			}
		}
		return $a_Items;
	} 
	
	/**
	 * Get value from request
	 * Enter description here ...
	 * @param unknown_type $the_sz_param
	 * @param unknown_type $the_sz_default
	 */
	public function getParam($the_sz_param, $the_sz_from = '')
	{		
		if(strtolower($the_sz_from) == 'post')
		{
			/* Get from post */
			return $this->_request->getPost($the_sz_param);
		}else if( strtolower($the_sz_from) == 'get')
		{
			/* Get from get */
			return $this->_request->getQuery($the_sz_param);
		}
		return $this->_request->getParam($the_sz_param);
	} 
}