<?php

/**
* A PHP Class which simplifies developing Facebook IFrame Applications
*
* @author Daniele Ghidoli <wp@bigthink.it>
* @version 1.2
* @access public
* @copyright Copyright (c) 2010, Daniele Ghidoli
*
*/ 

/*  Copyright 2010  Daniele Ghidoli  (email : wp@bigthink.it)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once 'facebook.php';

class FB{

	/**
	* Facebook Object
	*
	* @var Facebook
	* @access private
	*/ 
	private $facebook;	
	
	/**
	* Session
	*
	* @var array
	* @access private
	*/ 
	private $session;
	
	/**
	* Facebook User ID
	*
	* @var integer
	* @access private
	*/ 
	private $uid;
	
	/**
	* Facebook User Info
	*
	* @var array
	* @access private
	*/ 
	private $fbme;	
	
	/**
	* The Constructor create the Facebook object and check the Session
	*
	* @param string $perms required extended permissions
	* @access public
	*
	*/ 
	public function __construct($perms = ''){
		$this->facebook = new Facebook(
			array(
			  'appId'  => APP_ID,
			  'secret' => SECRET,
			  'cookie' => true
			));
		
		// Useful for CURL issues	
		//Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
		//Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;
		
		if(isset($_REQUEST['session'])){
			$this->session = json_decode($_REQUEST['session']);
			$this->uid = $this->session->uid;
		}
		if(empty($this->uid)){
			$this->setSession(); 
			$this->checkSession($perms);
		}
	}
	
	/**
	* Save the Session
	*
	* @access private
	*
	*/ 
	private function setSession(){
		$this->session = $this->facebook->getSession();
	}	
	
	/**
	* Return the Session
	*
	* @access public
	*
	*/ 
	public function getSession(){
		return $this->session;
	}
	
	/**
	* Check the Session and redirect the user if not valid
	*
	* @param string $perms required extended permissions
	* @access public
	*
	*/ 
	public function checkSession($perms){
		$loginUrl = $this->facebook->getLoginUrl(
			array(
				'canvas'    => 1,
				'fbconnect' => 0,
				'req_perms' => $perms,
				'next' => MY_URL . '/'
			)
		);
		if(!$this->session){
//            die($loginUrl);
			echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
			exit;
		}else{
			try{
				$this->uid = $this->facebook->getUser();
				$this->setUserInfo();				
			}catch(FacebookApiException $e){
				echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
				exit;
			}
		}
	}
	
	/**
	* Make a Graph API call
	*
	* @param string $path the Graph Path
	* @access public
	* @return mixed
	*
	*/
	public function api($path){
		return $this->facebook->api($path);
	}	
	
	/**
	* Set the User Info
	*
	* @access public
	*
	*/
	public function setUserInfo(){		
		$this->fbme = $this->api('/me');
	}
	
	/**
	* Get the Facebook User ID
	*
	* @access public
	* @return int $uid
	*
	*/
	public function getUserId(){
		return $this->uid;
	}
	
	/**
	* Get the User Email
	*
	* @access public
	* @return string $email
	*
	*/
	public function getEmail(){
		if(!$this->fbme){
			$this->setUserInfo();
		}
		return $this->fbme['email'];
	}	
	
	/**
	* Make an OLD FQL Query call
	*
	* @param string $fql the FQL Query
	* @access public
	* @return array
	*
	*/
	public function fql($fql){
		$param  =   array(
			'method'    => 'fql.query',
			'query'     => $fql,
			'callback'  => ''
		);
		return $this->api($param);
	}
	
	/**
	* Get the Info of the given User
	*
	* @param int $uid the Facebook User ID
	* @access public
	* @return array
	*
	*/
	public function getUserInfo($uid = 'me'){
		if($uid == 'me'){
			if(!$this->fbme){
				$this->setUserInfo();
			}
			return $this->fbme;
		}
		return $this->api("/$uid");
	}
	
	/**
	* Get the Friends List
	*
	* @param int $limit number of Friends to be returned
	* @access public
	* @return array
	*
	*/
	public function getFriends($limit = null){
		$rs = $this->api('/me/friends' . (isset($limit) ? '?limit=' . $limit : ''));
		return $rs['data'];
	}
	
	/**
	* Check if the User is Fan of the given Page
	*
	* @param id $page_id Facebook Page ID
	* @access public
	* @return bool
	*
	*/
	public function isFan($page_id){
		$param  =   array(
		   'method'  => 'pages.isFan',
		   'page_id' => $page_id,
		   'uid'     => $this->uid
		);
		return $this->api($param);
	}
	
	/**
	* Update the User status
	*
	* @param string $status the Status to post
	* @param id $uid the target User ID
	* @access public
	*
	*/
	public function updateStatus($status, $uid = 'me'){
		$status = htmlentities($status, ENT_QUOTES);
		$this->facebook->api("/$uid/feed", 'post', array('message'=> $status, 'cb' => ''));
	}
	
	/**
	* Publish on the User Stream
	*
	* @param string $title Title of the post
	* @param string $link Link of the post
	* @param string $message Custom User Message
	* @param string $image Image URl of the image to attach
	* @param id $uid the target User ID
	* @access public
	*
	*/
	public function streamPublish($title, $link, $message, $image, $uid = 'me'){
		$attachment =  array(
			'name' => $title,
			'link' => $link,
			'description' => $message,
			'picture' => $image
		);		
		$this->facebook->api("/$uid/feed", 'POST', $attachment);
	}

}



?>