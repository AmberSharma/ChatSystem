<?php
require_once 'model.php';
ini_set ( "display_error", 'on' );
class Register extends model {
	protected $username;
	protected $password;
	
	
	
	
	
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
}

?>
