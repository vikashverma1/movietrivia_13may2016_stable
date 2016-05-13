<?php

//===================================================================================
/* Front controller - Every pages of the website pass through index.php */
//===================================================================================

	#error_reporting(E_ALL);
	ini_set('display_errors',0);
	session_set_cookie_params(0);
	session_start();
	ob_start();
	$server = $_SERVER['HTTP_HOST'];
	#print_r($_SERVER);die;
	//$port = ($_SERVER['SERVER_PORT']=='80') ?'http://':'https://';
	$port = "http://";
	$rootpath = dirname(__FILE__)."/";
	$basePath = $port.$server;
	/* ====== array of available host ========= */
	$arr_avail_host = array(
                             'goapp.dev',
                             '209.160.65.49:1049'
                            /* 'scooprmedia.com'*/
                            );
	
	
	#--- this function is to get the required host(1)----------------
	/*
	 *PARAMS : server http host, array of possible hosts 
	 *RETURN : required host name 
	*/
	function getHost($server_http_host = '', array $avail_host = array())
	{
	   	foreach( $avail_host as $value)
	   	{
	      		$host = stripos($server_http_host, $value); 
	      		if($host !== false)
	      		{
	         		return $value;
	         		exit();
	      		}     
	   	}
	}
	#----------------------(/1)---------------------------------------
	


	#--- this function is to get the browser information----------------
	/*
	 *PARAMS : blank 
	 *RETURN : browser name, version etc...
	*/
	function getBrowser() 
    	{ 
		$u_agent  = $_SERVER['HTTP_USER_AGENT']; 
		$bname    = 'Unknown';
		$platform = 'Unknown';
		$version  = "";

	   	//First get the platform?
	    	if (preg_match('/linux/i', $u_agent)) {
	        $platform = 'linux';
	    	} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
	        $platform = 'mac';
	    	} elseif (preg_match('/windows|win32/i', $u_agent)) {
	        $platform = 'windows';
	    	}
	
	    	// Next get the name of the useragent yes seperately and for good reason
	    	if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
	    	{ 
	        	$bname = 'Internet Explorer'; 
	        	$ub = "MSIE"; 
	    	} 
	    	elseif(preg_match('/Firefox/i',$u_agent)) 
	    	{ 
	        	$bname = 'Mozilla Firefox'; 
	        	$ub = "Firefox"; 
	    	} 
	    	elseif(preg_match('/Chrome/i',$u_agent)) 
	    	{ 
	        	$bname = 'Google Chrome'; 
	        	$ub = "Chrome"; 
	    	} 
	    	elseif(preg_match('/Safari/i',$u_agent)) 
	    	{ 
	        	$bname = 'Apple Safari'; 
	        	$ub = "Safari"; 
	    	} 
	    	elseif(preg_match('/Opera/i',$u_agent)) 
	    	{ 
	        	$bname = 'Opera'; 
	        	$ub = "Opera"; 
	    	} 
	    	elseif(preg_match('/Netscape/i',$u_agent)) 
	    	{ 
	        	$bname = 'Netscape'; 
	       		$ub = "Netscape"; 
	    	} 

    		// finally get the correct version number
    		$known = array('Version', $ub, 'other');
    		$pattern = '#(?<browser>' . join('|', $known) .')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    		if (!preg_match_all($pattern, $u_agent, $matches)) {
        		// we have no matching number just continue
    		}

    		// see how many we have
    		$i = count($matches['browser']);
		if ($i != 1) 
		{
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
        		if (strripos($u_agent,"Version") < strripos($u_agent,$ub))
			{
            			$version= $matches['version'][0];
        		}
        		else 
			{
            			$version= $matches['version'][1];
        		}
    		}
    		else 
		{
        		$version= $matches['version'][0];
    		}

    		// check if we have a number
    		if ($version==null || $version=="") {$version="?";}

    		return array(
				'userAgent' => $u_agent,
				'name'      => $bname,
				'version'   => $version,
				'platform'  => $platform,
				'pattern'    => $pattern
    			   );
   	} 
	#----------------------(/2)---------------------------------------

	
	
	$server_host  = getHost($server, $arr_avail_host); 
	
	switch($server_host)
	{
		
		case '209.160.65.49:1049':
			$siteUrl    = $port.$server."/";	
			$localPath  = "";
			$dbRequest  = "staging";
			$siteHost   = '209.160.65.49:1049';
			break;

		/*case 'scooprmedia.com':
			$siteUrl    = $port.$server."/";	
			$localPath  = "";
			$dbRequest  = "live";
			$siteHost   = 'scooprmedia.com';
			break;*/
		
		default: 
			$siteUrl    = $port.$server."/";
			$localPath  = "";
			$dbRequest  = "local";
			$siteHost   = 'localhost';
			break;		
			
		
	}

    #---- code to check if the browser is compatible for our site or not----
    
    
	#-- get the user agent and browser details f it (get browser)----
	$userAgent         = $_SERVER['HTTP_USER_AGENT']; 
	$browser_details   = getBrowser();
	#-----------------------(/ get browser)-----------------------------
	
	#------- put up the condition (conditon)------
	$browser            = $browser_details['name'];
        $version            = $browser_details['version'];
	
	#-------------- (/condition)-------------------                      
   
   	#------------------ end code --------------------------------------------------  
    			
	//Define the basic setting of the project
	define("DB_REQUEST",$dbRequest);
	define("BASE_PATH", $basePath);
	define("PATH", $siteUrl);
	define("ROOT_PATH", $rootpath);
	define("LOCAL_PATH",$localPath);
        define("SITE_HOST",$siteHost);
	define('BROWSER', $browser);
	define("MEMCACHE_TIMING_DEFAULT",1200); //1200 IN SECONDS
	define("MEMCACHE_TIMING_TEN_MINUTES",600); //600 IN SECONDS
	define("MEMCACHE_TIMING_FIVE_MINUTES",300); //300 IN SECONDS
	define("MEMCACHE_TIMING_TWO_MINUTES",120); //120 IN SECONDS
	define("MEMCACHE_TIMING_ONE_MINUTE",60); //60 IN SECONDS
	define("MEMCACHE_TIMING_FIVE_SECONDS",5); //5 IN SECONDS
	define("MEMCACHE_TIMING_TEN_SECONDS",10); //10 IN SECONDS
	define("MEMCACHE_TIMING_THIRTY_SECONDS",30); //10 IN SECONDS
	//Retieve local path of the directory
	$url = $_SERVER['REQUEST_URI'];	
	$url = 	str_replace(LOCAL_PATH,"",$url);
	
	
	
	
	
	$array_tmp_uri = preg_split('[\\/]', $url, -1, PREG_SPLIT_NO_EMPTY);
        $control = ($array_tmp_uri[0] == "") ? "index" : $array_tmp_uri[0];
	//Deifferentiate controller , method and parameters
	$array_uri['controller'] 	= $control; //a class

	$array_uri['method']		= $array_tmp_uri[1]; //a function
	$array_uri['var']           = $array_tmp_uri[2]; //a variable	
	//Include file which is heart of the project
	
	require_once("application/base.php");

	require_once("application/lib/macros.php");
	require_once("application/lib/common_class.php");
	require_once("application/lib/tableMacros.php");

	#### File included from webservice folder ####
	require_once("webservice/classes/GeneralFunction.php");	
	#### File included from webservice folder ####	
		

	#---------- SSL certification (ssl for live site)----------------------------------
    	if($_SERVER['SERVER_PORT'] == 80 && (SITE_HOST == 'insuremycompany.net'))
	{
		$http   = "https";
		$host   = $_SERVER['HTTP_HOST'];
		$remote = $_SERVER['REQUEST_URI'];
		$newURl = $http."://".$host.$remote;
		header('location:'.$newURl);
		exit();
	}
	#-----------------------(/ssl for live site)---------------------------------------
	
	
	// creating object of a base,php class
	$application = new Application($array_uri);

	// calling the requested controller 
	$application->loadController($array_uri['controller']);	
?>
