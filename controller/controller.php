<script src="http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js"></script>
<?php
require_once '../libraries/constant.php';
require_once SITEPATH.'/libraries/validate.php';

ini_set("display_errors", "1");

$route = array();

class MyClass {
	
	
	
	
	public function handleLogin() 
	{
        
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
			require_once SITEPATH.'/model/gettersettermodel.php';
        		/* Getting rid of sql injection */
				$objInitiateUser = new Register ();
				$objInitiateUser->setUsername($_POST['username']);
				$objInitiateUser->setPassword($_POST['password']);
        		
        		$a=$objInitiateUser->login () ;
        		if ($a == 1) 
				{
            			$this->showUserPanel();
        		}
        		else 
			{
            			require_once(SITEPATH."View/login.php");
        		}
		}
    	}
    
    /* -----------------------------------------------------
         Function to add FAQ called from faq.php
       -----------------------------------------------------
    */
    
	public function showUserPanel ()
	{
		
		require_once("../View/chat.php");
	}
	public function rolefetch ()
	{
	
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->findRole();
		echo "Select Role:<select name='rolename' id='role' onchange='setrole()'>";
		echo "<option value='-1'>---Select---</option>";
		for($i =0 ; $i < count($fetch) ; $i++)
		{
		echo "<option value='" . $fetch[$i]['id'] ."'>" . $fetch[$i]['role'] ."</option>";
		    }
				echo "</select>";
				echo "<br />";
				echo "<br />";
				//echo "<input type='button' value='".$lang->ASSIGN."' onclick='assign()'/>";
	}
	
	public function findusers ()
	{
	
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->findUser();
		echo "Select User:<select name='rolename' id='user' onchange='setuser()' >";
		echo "<option value='-1'>---Select---</option>";
		for($i =0 ; $i < count($fetch) ; $i++)
		{
		echo "<option value='" . $fetch[$i]['id'] ."'>" . ucfirst($fetch[$i]['name']) ."</option>";
		}
		echo "</select>";
		echo "<br />";
		echo "<br />";
		echo "<input type='button' value='Add Permission' onclick='addpermissionform()'/>";
		echo "<input type='button' value='Add Role' onclick='addrole()'/>";
		echo "<input type='button' value='Delete Role' onclick='deleterole()'/>";
		echo "<input type='button' value='Delete Default' onclick='deletedefault()'/>";
		echo "<input type='button' value='Fetch Roles' onclick='fetchrole()'/>";
	}
	
	public function findscreen ()
	{
	
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->findScreen();
		
		require_once("../View/findScreen.php");
		
		
		
	}

	public function addrole ()
	{
		
		if(($_REQUEST['role'] == 'undefined') || ($_REQUEST['user'] == 'undefined'))
		{
			die ("Either Role or User Not Specified!!!");
		}
		else
		{
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->addRole();
		if($fetch == "1")
		{
			echo "Inserted";
		}
		}
		
		
	}

	public function deleterole ()
	{
		
		if($_REQUEST['user'] == 'undefined')
		{
			die ("User Not Specified!!!");
		}
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->deleteRole();
		if($fetch == "1")
		{
			echo "Deleted";
		}
		
		
		
	}

	public function deletedefault ()
	{
		
		if($_REQUEST['user'] == 'undefined')
		{
			die ("User Not Specified!!!");
		}
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->deleteDefault();
		if($fetch == "1")
		{
			echo "Deleted";
		}
		
		
		
	}

	public function addpermissionform ()
	{
	
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->addpermissionform();
		
		require_once("../View/finddefaultScreen.php");
		
		
		
	}

	public function addpermission ()
	{
	
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->addpermission($_REQUEST);
		
		//require_once("../View/finddefaultScreen.php");
		
		
		
	}

	public function assignselection ()
	{
		
		
		require_once("../model/gettersettermodel.php");
		
		$find=new Register();
		$fetch = $find->assignRole($_REQUEST);
		
	}
	
	
	public function adduser ()
	{
		
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->addUser();
		if($fetch=="updated")
		{
			echo $fetch;
			
		}
	}
	
	public function fetchroles ()
	{
	
		if($_REQUEST['user'] == 'undefined')
		{
			die ("User Not Specified!!!");
		}
		require_once("../model/gettersettermodel.php");
		$find=new Register();
		$fetch = $find->fetchRoles();
		require_once("../View/userscreen.php");
		
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
