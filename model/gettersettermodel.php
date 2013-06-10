<?php
session_start();
require_once 'model.php';
ini_set ( "display_error", 'on' );
class Register extends model {
	protected $username;
	protected $password;
	protected $message;
	
	
	
	
	/**
	 * @return the $username
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @return the $password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @param field_type $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * @param field_type $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}
		
	public function getMessage() {
		return $this->message;
	}

	public function login()
	{
		$this->db->Fields(array("username","password" ,"id"));
		$this->db->From("user");
		$this->db->where(array("username"=>$this->getUsername() , "password"=>$this->getPassword()));
		$this->db->Select ();
		$result = $this->db->resultArray ();
		if($result)
		{
			$_SESSION['uid']=$result[0]['id'];
			$_SESSION['username']=$result[0]['username'];
		}
		//echo $this->db->lastQuery();
		return count($result);
	}
	public function updateLogged()
	{
		$this->db->Fields(array("loggedin"=>"Y"));
		$this->db->From("user");
		$this->db->Where(array("username"=>$_SESSION['username']));
		$result=$this->db->Update();
		//echo $this->db->lastQuery();
		return $result;
	}
	public function logOut()
	{
		$this->db->Fields(array("loggedin"=>"N"));
		$this->db->From("user");
		$this->db->Where(array("username"=>$_SESSION['username']));
		$result=$this->db->Update();
		if($result == "1")
		{
			unset($_SESSION['uid']);
			unset($_SESSION['username']);
			return $result;
		}
	}
	public function loggedUsers()
	{
		$this->db->Fields(array("username"));
		$this->db->From("user");
		$this->db->Where(array("loggedin"=>"Y"));
		$this->db->Select ();
		$result = $this->db->resultArray ();
		return $result;
	}
	public function Comment()
	{
		$this->db->Fields(array("id"=>' ',"sender_id"=>"1","receiver_id"=>"2","message"=>$this->getMessage(),"sent_at"=>date('Y-m-d')));
		$this->db->From("chatting");
		//$this->db->Where(array("loggedin"=>"Y"));
		if($this->db->insert())
		{
			
			$id=$this->db->lastInsertId();
			$this->db->Fields(array("message"));
			$this->db->From("chatting");
			$this->db->Where(array("id"=>$id));
			$this->db->Select ();
			
			$result = $this->db->resultArray ();
			$result[0]["username"] = $_SESSION['username'];
			return $result;
			
		}
		
	}
}

?>
