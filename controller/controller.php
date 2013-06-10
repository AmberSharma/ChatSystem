
<?php

require_once getcwd()."/../libraries/constant.php";
//echo SITE_URL;die;
require_once getcwd().'/../libraries/validate.php';

ini_set("display_errors", "1");

$route = array();

class MyClass {
	
	
	
	
	public function handleLogin() 
	{
//echo "hrflkjs";die;

        
        /* Validate username password */
		$obj = new validate();
		$obj->validator("username",$_POST ["username"], 'required#username#maxlength=25','Username Required# Username is not valid#Username should not be more than 25 chracter long');
		$obj->validator("password",$_POST ["password"], 'required#minlength=5#maxlength=25','Password Required#Password should not be less than 5 characters long#Password should not be more than 25 chracter long');
        //$authObject->validateLogin ();
    		$error=$obj->result();
		print_r($error);
		if(!empty($error['username']))
		{			
			header("Location:../View/comment.php?user=".$error['username']);
		}
		else if(!empty($error['password']))
		{
			header("Location:../View/comment.php?password=".$error['password']);
		}
		else
		{
			require_once SITE_PATH.'/../model/gettersettermodel.php';
        		/* Getting rid of sql injection */
				$objInitiateUser = new Register ();
				$objInitiateUser->setUsername($_POST['username']);
				$objInitiateUser->setPassword($_POST['password']);
        		
        		$a=$objInitiateUser->login () ;
        		if ($a == 1) 
			{
				$b=$objInitiateUser->updateLogged () ;
				//print_r($a);die;
            			$this->showUserPanel();
        		}
        		else 
			{
            			require_once(SITE_PATH."/../login.php");
        		}
		}
    	}
    
    /* -----------------------------------------------------
         Function to add FAQ called from faq.php
       -----------------------------------------------------
    */
    	public function loggedusers ()
	{	
		require_once SITE_PATH.'/../model/gettersettermodel.php';
		$objInitiateUser = new Register ();
		$b=$objInitiateUser->loggedUsers () ;
		//print_r($b);die;
		for($i =0 ;$i < count($b) ;$i ++)
		{
			$arr[] = $b[$i]["username"];
		}
		echo json_encode($arr);
		//require_once("../View/chat.php");
	}
	public function logout ()
	{	
		require_once SITE_PATH.'/../model/gettersettermodel.php';
		$objInitiateUser = new Register ();
		$b=$objInitiateUser->logOut () ;
		if($b == "1")
		{
			header("Location:".SITE_URL);
		}
	}
	public function comment ()
	{	
		
		require_once SITE_PATH.'/../model/gettersettermodel.php';
		$objInitiateUser = new Register ();
		$objInitiateUser->setMessage($_REQUEST['usermsg']);
		$b=$objInitiateUser->Comment () ;
		if(!empty($b))
		{
			$arr[] = $b[0]["username"];
			$arr[] = $b[0]["message"];
			
			echo json_encode($arr);
		}
		
	}
	public function showUserPanel ()
	{
		
		require_once("../View/chat.php");
	}
	


}

$request = "";
if (isset($_GET["method"])) {

    $request = $_GET["method"];
}

$obj = new MyClass();

if (!empty($request)) {
    $obj->$request();
}
?>
