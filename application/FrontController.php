<?php

namespace FrontController\application;

use Commons\utils\Logger;
use FrontController\application\business\domain\IRequest;
use FrontController\application\business\domain\IResponse;
use FrontController\application\business\domain\objects\HTTPResponse;
use FrontController\application\persistence\dao\UserDAO;



class FrontController {
	const cookie = "raumklang";
	public function __construct() {
		
		// Project name of application
		//$this->apps ["administration"] = "Administration";
		
		// Path routes - Project name of the application
		$this->routes ["/"] = "website";
		$this->routes [""] = "website";
		$this->routes ["/mail"] = "website";
		$this->routes ["/impressum"] = "website";
	}
	private function buildUserContext(IRequest $request) {
		/**
		 * read cookie if available and create user context
		 */
		$userContext = array ();
		$userContext ["username"] = "GUEST";
		
		$session = filter_input ( INPUT_COOKIE, FrontController::cookie );
		
		if ($session) {
			$userDAO = new UserDAO ();
			$user = $userDAO->checkForSession ( $session );
			
			if ($user) {
				// overwrite current session id
				session_id ( $session );
				$_SESSION ['username'] = $user->getUsername ();
			} else {
				unset ( $_SESSION ['username'] );
			}
		}
		
		return $userContext;
	}
	
	/**
	 */
	public function dispatchRequest(IRequest $request) {
		Logger::log ( "--------------------------------------------------------------------------------------------" );
		Logger::log ( "URL PATH: " . $request->getPath () );
		
		$paramList = print_r ( $request->getParamList (), true );
		Logger::log ( "PARAM LIST: " . $paramList );
		Logger::log ( "--------------------------------------------------------------------------------------------" );
		
		$nodes = array_filter ( explode ( "/", $request->getPath () ) );
		
		$userContext = array ();
		$userContext ["user"] = "GUEST";
		
		$response = new HTTPResponse ();
		$applicationPath = null;
		
		if (isset ( $this->routes [$request->getPath ()] )) {
			$applicationPath = $this->routes [$request->getPath ()];
		} else {
			if (sizeof ( $nodes ) > 1) {
				$first = $nodes [1];
					
				$userContext = $this->buildUserContext ( $request );
					
				if ("admin" === $first) {
					$appname = $nodes [2];
					if (isset ( $this->apps [$appname] )) {
						$applicationPath = $this->apps [$appname];
					}
				}
			}			
		}
		

		if ($applicationPath == null) {
// 			// nothing found --> error page
// 			include ("at/akuehne/views/main/errorpage.php");
// 			Logger::log ( "No page found for: " . $request->getPath () );
// 			exit ();
			$applicationPath = $this->routes ["/"];
		}
		
		if ($applicationPath) {
			// we found an application --> call it
			$this->callApplicationFrontController ( $applicationPath, $request, $response, $userContext );
		}
	}
	
	/**
	 */
	private function callApplicationFrontController($applicationPath, IRequest &$request, IResponse &$response, array $userContext) {
		$cls = $applicationPath . "\\application\\ApplicationFrontController";
		
		$r = new \ReflectionClass ( $cls );
		$obj = $r->newInstanceArgs ( array (
				$userContext 
		) );
		
		$reflectionMethod = new \ReflectionMethod ( $cls, 'dispatchRequest' );
		$reflectionMethod->invoke ( $obj, $request, $response );
	}
	private function startsWith($haystack, $needle) {
		return $needle === "" || strpos ( $haystack, $needle ) === 0;
	}
	private function endsWith($haystack, $needle) {
		return $needle === "" || substr ( $haystack, - strlen ( $needle ) ) === $needle;
	}
	private function ListIn($dir, $fileEnding, $prefix = '') {
		$dir = rtrim ( $dir, '\\/' );
		$result = array ();
		
		foreach ( scandir ( $dir ) as $f ) {
			if ($f !== '.' and $f !== '..') {
				if (is_dir ( "$dir/$f" )) {
					$result = array_merge ( $result, $this->ListIn ( "$dir/$f", $fileEnding, "$prefix$f/" ) );
				} else {
					if ($this->endsWith ( $f, $fileEnding )) {
						$result [] = $prefix . $f;
					}
				}
			}
		}
		
		return $result;
	}
}
