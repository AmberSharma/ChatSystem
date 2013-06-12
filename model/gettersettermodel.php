<?php
session_start();
require_once 'model.php';
ini_set ( "display_error", 'on' );
class Register extends model {
	protected $username;
	protected $password;
	protected $message;
	protected $recid;
	
	
	
	
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
	
	public function setRecid($recid) {
		$this->recid = $recid;
	}
		
	public function getRecid() {
		return $this->recid;
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
		$this->db->Fields(array("username" , "id"));
		$this->db->From("user where loggedin='Y' and username <> '".$_SESSION['username']."'");
		//$this->db->Where(array("loggedin"=>"Y"));
		$this->db->Select ();
		//echo $this->db->lastQuery();
		$result = $this->db->resultArray ();
		return $result;
	}
	public function Comment()
	{
		
		if($this->getMessage())
		{
			$this->db->Fields(array("id"));
			$this->db->From("user");
			$this->db->Where(array("username"=>$_SESSION['username']));
			$this->db->Select ();
			$id = $this->db->resultArray ();
			//print_r($id);die;
			$this->db->Fields(array("id"=>' ',"sender_id"=>$id[0]['id'],"receiver_id"=>$this->getRecid(),"message"=>$this->getMessage(),"sent_at"=>date('Y-m-d')));
			$this->db->From("chatting");
			//$this->db->Where(array("loggedin"=>"Y"));
			if($this->db->insert())
			{
				$_SESSION["lastid"]=$this->db->lastInsertId();
				$this->db->Fields(array("message"));
				$this->db->From("chatting");
				$this->db->Where(array("id"=>$_SESSION["lastid"]));
				$this->db->Select ();
				//echo $this->db->lastQuery();
				$result = $this->db->resultArray ();
				if(!empty($result))
				{
					$result[0]["username"] = $_SESSION['username'];
					return $result;
				}
			}
			
			
		}
		else
		{
			$this->db->Fields(array("id"));
			$this->db->From("chatting");
			$this->db->Where(array("receiver_id"=>$_SESSION['uid']));
			$this->db->OrderBy("id","DESC");
			$this->db->Limit("1");
			$this->db->Select ();
			$id = $this->db->resultArray ();
			//echo $this->db->lastQuery();die;
			if(!empty($id))
			{
				if($_SESSION["lastid"] < $id[0]['id'])
				{
					$_SESSION["lastid"] = $id[0]['id'];
					$this->db->Fields(array("message","sender_id"));
					$this->db->From("chatting");
					$this->db->Where(array("id"=>$id[0]['id']));
					$this->db->Select ();
					$result = $this->db->resultArray ();
				
					$this->db->Fields(array("username"));
					$this->db->From("user");
					$this->db->Where(array("id"=>$result[0]['sender_id']));
					$this->db->Select ();
					//echo $this->db->lastQuery();
					$uname = $this->db->resultArray ();
					if(!empty($result))
					{
						$result[0]["username"] = $uname[0]['username'];
						return $result;
					}
				}
			}
		}
		
	}
}

?>
