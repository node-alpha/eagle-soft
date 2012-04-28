<?php
/**
 * Error handler class
 *
 * @author 		FPT.luund
 * @category 	OSS
 * @package 	Oss_App
 */
class ErrorController extends Zend_Controller_Action
{

	/**
	 * Catch all error from application
	 */
	public function errorAction ()
	{
	
        $errors = $this->_getParam('error_handler');
 
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                //$this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
                var_dump($errors->exception->getMessage());
				var_dump($errors->exception->getTraceAsString());
                break;
            default:
                var_dump($errors->exception->getMessage());
				var_dump($errors->exception->getTraceAsString());
                break;
        }
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();	
	}
}
?>