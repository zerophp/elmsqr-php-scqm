<?php

class Bootstrap
{
	// Start Session
	// Read Config
	
	// Include Gateways
	// Include ActionHelpers
	// Include ViewHelpers
	// Include Models
	// Include FrontFunction
	
	// Route
	// Acl
	
	private $config;
	private $env;
	private $route;
	
	public function __construct($config, $env)
	{
		$this->config=$config;
		$this->env=$env;		
		
		$this->startRegister();
		$this->readConfig();		
		$this->route=$this->router($this->config);
		$this->route=$this->acl($this->route);
		
		return $this->route;
	}
	
	protected function startRegister()
	{
		session_start();
		$_SESSION['register']=array();
		$_SESSION['app']=array();		
	}
	
	protected function readConfig()
	{
		// Read config
		$config=parse_ini_file($this->config,true);
		$config=$config['production'];
		// $config = ReadConfig ('../application/configs/config.ini', 'development');
		$_SESSION['register']['config']=$config;		
	}	
	
	protected function router()
	{
			
		$config=$this->config;	
		$controllerActions=array(
				'index'=>array('index'),
				'author'=>array('login','logout'),
				'users'=>array('insert','update','delete','select')
		);
		$parse=explode('/',$_SERVER['REQUEST_URI']);
		
		$route['controller']=$parse[1];
		@$route['action']=$parse[2];
		
		if(file_exists($config['path.controllers']."/".$route['controller'].".php"))
		if(in_array($route['action'],$controllerActions[$route['controller']]))
		{
			for($i=3;$i<sizeof($parse);$i+=2)
			{
				$_REQUEST[$parse[$i]]=$parse[$i+1];
			}
		}
		else
		{
			$route['controller']='error';
			$route['action']=NO_ACTION;
		}
		else
		{
			// 		$route['controller']='error';
			// 		$route['action']=NO_CONTROLLER;
		
			$route['controller']='index';
			$route['action']='index';
		}
		
		// 	debug($route);
		// 	debug($_REQUEST);
		
		// 	die;
		
		$this->route=$route;
	}
	
	protected function acl()
	{
		$route=$this->route;
		if(!isset($_SESSION['idrol']))
		{
			// FIXME: -8.03.2013-acl-: HARDCODE DEFAULT ROL
			$_SESSION['idrol']=4;
		}
		
		
		$permissions = array('1'=>
				array('index'.'.'.'index',
						'author'.'.'.'login',
						'author'.'.'.'logout',
						'users'.'.'.'select',
						'users'.'.'.'insert',
						'users'.'.'.'update',
						'users'.'.'.'delete'),
				'3'=>array('index'.'.'.'index',
						'author'.'.'.'login',
						'author'.'.'.'logout'),
				'4'=>array('index'.'.'.'index',
						'author'.'.'.'login',
						'author'.'.'.'logout')
		);
		
		
		if(isset($_SESSION['iduser']))
		{
			if(in_array($route['controller'].'.'.$route['action'],
					$permissions[$_SESSION['idrol']]))
			{
				return $route;
			}
		}
		elseif($_SESSION['idrol']===4)
		{
			if(in_array($route['controller'].'.'.$route['action'],
					$permissions[$_SESSION['idrol']]))
			{
				return $route;
			}
		
		}
		$route['controller']='users';
		$route['action']='select';
		
		
		$this->route=$route;
		return $this->route;
	}
	
	
	
	// Include Gateways
// 	include_once('../application/models/dataGatewayMysql.php');
	
// 	// Include actionHelpers
// 	include_once('../application/controllers/helpers/actionHelpersFunctions.php');
// 	include_once('../application/controllers/helpers/viewFunctions.php');
	
	
// 	// Include viewHelpers
// 	include_once('../application/views/helpers/helpersFunctions.php');
	
// 	// Include Models
// 	include_once('../application/models/files/functions.php');
// 	include_once('../application/models/files/filesFunctions.php');
	
// 	// Include FrontFunctions
// 	include_once('../application/frontFunctions.php');

	/**
	 * @return the $route
	 */
	public function getRoute() {
		return $this->route;
	}
	
		
}