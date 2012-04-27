<?php
require_once 'Zend/Controller/Action.php';

class BaseController extends Zend_Controller_Action
{
	public function init()
	{
		$this->view->headLink()->appendStylesheet($this->sz_fGetPublicHost() . 'css/style.css');
		$this->view->headLink()->appendStylesheet($this->sz_fGetPublicHost() . '/js/style2.css');
		$this->view->headScript ()->appendFile ( $this->sz_fGetPublicHost() . 'js/jquery.min.js', 'text/javascript');		
		$this->view->headScript ()->appendFile ( $this->sz_fGetPublicHost() . 'js/jquery.cookie.js', 'text/javascript');		
		$this->view->headScript ()->appendFile ( $this->sz_fGetPublicHost() . 'js/jquery.treeview.js', 'text/javascript');		
		$this->view->headScript ()->appendFile ( $this->sz_fGetPublicHost() . 'js/core-ajax.js', 'text/javascript');		
		$this->view->headScript ()->appendFile ( $this->sz_fGetPublicHost() . 'js/common.js', 'text/javascript');		
		$this->view->headScript ()->appendFile ( $this->sz_fGetPublicHost() . 'js/ajax-loading.js', 'text/javascript');		
		$this->view->headScript ()->appendFile ( $this->sz_fGetPublicHost() . 'js/demo.js', 'text/javascript');		
		$this->view->assign('sz_PublicHost', $this->sz_fGetPublicHost());
		parent::init();
	}
	
	public function b_fIsAjaxRequest()
	{
		return (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && ($_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
	}
	
	public function sz_fGetHost()
	{
		return 'http://' . $_SERVER['HTTP_HOST'];
	}
	
	public function sz_fGetPublicHost()
	{
		return $this->sz_fGetHost() . '/public/';
	}
	
	/**
	 * Trim string or arary
	 * @param $the_a_Data
	 * @return array
	 */
	public function v_fClean($the_a_Data)
	{
		if (isset($the_a_Data))
		{
			if (is_array($the_a_Data))
			{
				foreach ($the_a_Data as &$sz_Data)
				{
					if (is_array($sz_Data))
					{
						$this->v_fClean($sz_Data);
					}
					else
					{
						$sz_Data = trim($sz_Data);
					}
				}
			}
			else
			{
				$the_a_Data = trim($the_a_Data);
			}
		}
		return $the_a_Data;
	}
	
}