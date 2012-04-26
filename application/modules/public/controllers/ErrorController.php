<?php
/**
 * Error handler class
 *
 * @author 		FPT.luund
 * @category 	OSS
 * @package 	Oss_App
 */
class ErrorController extends Nid_Oss_Controller_Action
{

	/**
	 * Catch all error from application
	 * 
	 * @author FPT.DungHT
	 */
	public function errorAction ()
	{
		/* Header bottom text */
		$sz_HeaderBottomText = $this->view->translate('NISS_TITLE_3');
		Zend_Registry::set('sz_HeaderBottomText', $sz_HeaderBottomText);
		
		$errors = $this->_getParam('error_handler');
		$this->_session['COMMON']->errors = $errors;
		
		switch ( $errors->type )
		{
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				$this->_forward('not-found');
				break;
			default:
				switch ( get_class($errors->exception) )
				{
					case 'Nid_Oss_Exception_WebService':
						$this->_forward('webservice-error');
						break;
					case 'Nid_Oss_Exception_Database':
						$this->_forward('database-error');
						break;
					case 'Nid_Oss_Exception_NotAuthorized':
						$this->_forward('not-authorized');
						break;
					case 'Nid_Oss_Exception_System':
						$this->_forward('system-error');
						break;
					default:
						$this->_forward('application-error');
						break;
				}
				break;
		}
		$this->getResponse()->clearBody();
		$this->view->exception = $errors->exception;
		$this->view->request = $errors->request;
	}

	/**
	 * this action is called if 404 error caught
	 * 
	 * @author FPT.DungHT
	 */
	public function notFoundAction ()
	{
		$errors = $this->_session['COMMON']->errors;
		$a_ExceptionMetaData = Nid_Oss_View_Helper_Exception::buildResponseData($errors->exception);
		
		if ( !Zend_Registry::get('debug_mode') )
		{
			//Make message to be more comprehensive for end-user 
			$a_ExceptionMetaData->message = 'Error 404: Page not found!';
		}
		//log this error into database
		Nid_Oss_Log::v_fError($a_ExceptionMetaData->message);
		
		//if current request is an ajax request, just display message
		if ( $this->_request->isXmlHttpRequest() )
		{
			$this->_responseAjaxContext($a_ExceptionMetaData);
		}
		else
		{
			$this->_jumpToErrorPage($a_ExceptionMetaData);
		}
		$this->_cleanError();
	}

	/**
	 * this action is called if DatabaseException thrown
	 * 
	 * @author FPT.DungHT
	 */
	public function databaseErrorAction ()
	{
		$errors = $this->_session['COMMON']->errors;
		$a_ExceptionMetaData = Nid_Oss_View_Helper_Exception::buildResponseData($errors->exception);
		
		//log this error into database
		Nid_Oss_Log::v_fError($a_ExceptionMetaData->message);
		
		//if current request is an ajax request, just display message
		if ( $this->_request->isXmlHttpRequest() )
		{
			$this->_responseAjaxContext($a_ExceptionMetaData);
		}
		else
		{
			$this->_jumpToErrorPage($a_ExceptionMetaData);
		}
		$this->_cleanError();
	}

	/**
	 * this action is called if permission denied for user
	 * 
	 * @author FPT.DungHT
	 */
	public function notAuthorizedAction ()
	{
		
		$errors = $this->_session['COMMON']->errors;
		$a_ExceptionMetaData = Nid_Oss_View_Helper_Exception::buildResponseData($errors->exception);
		
		//log this error into database
		Nid_Oss_Log::v_fError($a_ExceptionMetaData->message);
		
		//if current request is an ajax request, just display message
		if ( $this->_request->isXmlHttpRequest() )
		{
			$this->_responseAjaxContext($a_ExceptionMetaData);
		}
		else
		{
			$this->_jumpToErrorPage($a_ExceptionMetaData);
		}
		$this->_cleanError();
	}

	/**
	 * This action will be called when we have access permission exception 
	 * 
	 * @author FPT.DatGS
	 */
	public function notAuthorizedMenuAction ()
	{
		try
		{
			throw new Nid_Oss_Exception_NotAuthorized('You have no permission to access this area');
		}
		catch ( Exception $e )
		{
			
			$a_ExceptionMetaData = Nid_Oss_View_Helper_Exception::buildResponseData($e);
			
			//log this error into database
			Nid_Oss_Log::v_fError($e->getMessage());
			
			$this->view->exception = $e;
			$this->view->request = $this->_request;
			
			//if current request is an ajax request, just display message
			if ( $this->_request->isXmlHttpRequest() )
			{
				$this->_responseAjaxContext($a_ExceptionMetaData);
			}
			else
			{
				$this->_jumpToErrorPage($a_ExceptionMetaData);
			}
		}
	}

	/**
	 * this action is called if WebService Exception thrown
	 * 
	 * @author FPT.DungHT
	 */
	public function webserviceErrorAction ()
	{

	}

	/**
	 * this action is called if ApplicationException thrown
	 * 
	 * @author FPT.DungHT
	 */
	public function applicationErrorAction ()
	{
		
		$errors = $this->_session['COMMON']->errors;
		$a_ExceptionMetaData = Nid_Oss_View_Helper_Exception::buildResponseData($errors->exception);
		
		//log this error into database
		Nid_Oss_Log::v_fError($a_ExceptionMetaData->message);
		
		//if current request is an ajax request, just display message
		if ( $this->_request->isXmlHttpRequest() )
		{
			$this->_responseAjaxContext($a_ExceptionMetaData);
		}
		else
		{
			$this->_jumpToErrorPage($a_ExceptionMetaData);
		}
		$this->_cleanError();
	}

	/**
	 * response if request is in Ajax context
	 * 
	 * @author FPT.DungHT
	 * @param $a_ExceptionMetaData : information of exception
	 */
	private function _responseAjaxContext ($a_ExceptionMetaData)
	{
		$a_Response = new stdClass();
		$a_Response->i_Code = 0;
		$a_Response->errors = $a_ExceptionMetaData;
		print_r(Zend_Json::encode($a_Response));
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	}

	/**
	 * jump to error page if request is in none-ajax context
	 * 
	 * @author FPT.DungHT
	 * @param $a_ExceptionMetaData : information of exception
	 */
	private function _jumpToErrorPage ($a_ExceptionMetaData)
	{
		$this->view->assign('exception', $a_ExceptionMetaData);
		if ( Zend_Registry::get('debug_mode') )
		{
			$this->render('debug');
		}
		else
		{
			$this->render('error');
		}
	}

	/**
	 * clean error from session
	 * 
	 * @author FPT.DungHT
	 */
	private function _cleanError ()
	{
		unset($this->_session['COMMON']->errors);
	}
}
?>