<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Init autoload for site
	 * Enter description here ...
	 */
	protected function _initAutoload() 
	{		
		$autoloader = new Zend_Application_Module_Autoloader(array( 
				'namespace' => '', 
				'basePath' => APPLICATION_PATH,
				'resourceTypes' => array(
		        'model' => array(
		            'path'      => 'models/',
		            'namespace' => 'Model',
		        ),
		        'core' => array(
		            'path'      => 'core/',
		            'namespace' => 'Core',
		        )        
		    )
		));
		return $autoloader;
	}
	
	/**
	 * Init configuration for site
	 * Enter description here ...
	 */
	protected function _initConfig()
	{
		$o_Config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
		Zend_Registry::set('config', $o_Config);
	}
	
	/**
	 * Init database connection
	 */
	protected function _initDb()
	{
		$o_Config = Zend_Registry::get('config');
		$db = Zend_Db::factory($o_Config->db->adapter,
							array (
								'host' => $o_Config->db->host, 
								'username' => $o_Config->db->username, 
								'password' => $o_Config->db->password, 
								'dbname' => $o_Config->db->db_name
							)); 
		$db->setFetchMode(Zend_Db::FETCH_OBJ);
		Zend_Db_Table::setDefaultAdapter($db); 
	}
}
?>