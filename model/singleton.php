<?php

ini_set("display_errors", "1");

/**
 * 
 * @author root
 * @access
 * @package DB
 *
 */
class DBConnection {

    private static $instance;
    private static $_host = "localhost";
    private static $_user = "root";
    private static $_password ="root";
    private static $_database = "chatsystem";
    private $_tableName = "";
    private $_join = "";
    private $_limit = "";
    private $_where = "";
    private $_as = array();
    private $_between = "";
    private $_in = "";
    private $_set = "";
    private $_having = "";
    private $_orderBy = "";
    private $_groupBy = "";
    private $_keys = array();
    private $_values = array();
    private $_insertId;
    private $_errorLog = "";
    private $_query = "";
    private $_result = "";
    private $_starttransaction = "";
    private $_commit = "";
    private $_rollback = "";

    private function __construct() {
        
    }

    public static function Connect() {
        if (is_null(DBConnection::$instance)) {
            
            $db = mysql_connect(self::$_host, self::$_user, self::$_password);
            if ($db) {
                mysql_select_db(self::$_database, $db);
                self::$instance = new DBConnection();
            }
        }
        return self::$instance;
    }

    private function validConnection() {
        if (is_null(DBConnection::$instance)) {
            echo "Could not connected!";
            exit();
        }
    }

    public function Fields($data = array()) {
        $count = count($data);
	
	    if ($count > 0) {
             
            foreach ($data as $key => $value) {

                if (!empty($value)) {
                    $this->_keys[] = $key;
		    $this->_values[] = $value;
		    
                }
            }
        }
	
		return $this;
    }

    public function From($value) {
        if (!empty($value)) {
            $this->_tableName = $value;
        }
		return $this;
    }
    
    public function Limit($value="") {
    	$this->_limit ="";
        if (!empty($value)) {
        	$this->_limit =" limit ";
            $this->_limit .=$value;
        }
        
        
		return $this;
    }
    
    public function Ast($value1 = array())
    {
	$count = count($value1);
        if ($count > 0) {

            foreach ($value1 as $key => $value) {

                if (!empty($value1)) {
                    $this->_as[$key] = $value;

                }
            }
        }

		return $this;
    }
    public function Set() {
	    $this->_set=" ALTER TABLE ".$this->_tableName." ENGINE=InnoDB;Set Autocommit=1;";
	    echo $this->_set;
            mysql_query($this->_starttransaction);
    }
    public function Start_transaction() {
	
	    $this->_starttransaction="Start transaction;";
	   
            mysql_query($this->_starttransaction);
    }
    public function transaction_commit() {
	    $this->_commit="Commit;";
            mysql_query($this->_commit);
    }
    public function transaction_rollback() {
	    $this->_rollback="rollback;";
            mysql_query($this->_rollback);
    }
    public function Between($min,$max) {
        if ((!empty($min))&&(!empty($max))) {
            $this->_between =" between ".$min." and ".$max;
        }
		return $this;
    }
    public function having($field,$op,$value) {
        if ((!empty($field))&&(!empty($op))&&(!empty($value))) {
            $this->_having =" having ".$field." ".$op." ". $value;
        }
		return $this;
    }

	public function Join($joinTable, $condition, $type="INNER"){
	    $this->_join="";
		if(!empty($this->_tableName) && !empty($joinTable) && !empty($condition) && !empty($type)){
		
			$this->_join .= strtoupper(" ".$type." JOIN ").$joinTable; 
			$this->_join .= " ON ".$condition." ";
		}
	}
    public function Where($data = array(), $raw = false, $operator = "AND") {
	$this->_where="";
        $count = count($data);
        if ($count > 0) {

            $index = 0;
            foreach ($data as $key => $value) {
			
			
              
                if ($index >= 1 || !empty($this->_where)) {
						$ope  = strtoupper($operator);
						if(in_array($ope,array("AND","OR"))){
							$this->_where .= " $ope ";
						}
                }
				
				if($raw){
						$this->_where .= " ".$value;
						break;
				}
				
				if (is_string($value)) {
                    $value = " '$value' ";
                }

				
				$op = array("=",">","<",">=","<=");
				$opMatch = false;
				for($i = 0;$i < count($op);$i++){
				
					if(strpos($key, $op[$i]) > 0){
						$opMatch  = true;
						break;
					} 
				}
				if($opMatch){
					$this->_where .= " $key $value";
				}
				elseif($this->_between){
				    $this->_where .= $key .$this->_between ;
				}
				
				else {
					$this->_where .= " $key =  $value";
				}
                $index++;
            }
        }
		return $this;
    }
	
    public function Where1($data="") {
    	$this->_where.=$data;
    	}
	public function Like($key,$val, $operator = "AND"){
	
		if(!empty($key) && !empty($val)){
			if(strlen($this->_where) == 0){
				$this->_where .=  $key." LIKE \"%".$val."%\"";
			} else {
				$ope  = strtoupper($operator);
				if(in_array($ope,array("AND","OR"))){
					$this->_where .= " ".$ope ." ". $key." LIKE \"%".$val."%\"";
				}
			}
		}
		return $this;
	}
	public function In($field, $values = array(), $not = false){
//echo "hello<pre>";
//echo $field;
//	print_r($values);
		if(is_array($values) && !empty($field)){
//echo "hello<pre>";
			$n = "";
			if($not){
				$n = " NOT ";
			}
			
			if(strlen($this->_where) > 0){
				/*$ope  = strtoupper($operator);
				if(in_array($ope,array("AND","OR"))){
					$this->_between .= " ".$ope ." ";
				}*/
				$this->_in .=" ";
			}
			$this->_in.="$field $n IN (";
			//foreach ($data as $value) {
			$len=count($values);
			
			for($i=0;$i<$len;$i++){
			    if (!empty($values[$i])) {
				if (is_string($values[$i])) {
				$this->_in .="'" .$values[$i] . "'";
}
else
{
$this->_in .= $values[$i];
}
			    }
			    if($i!=$len-1)
			    {
				$this->_in .= ",";
			    }
			}
			$this->_in .=")";
		}
//echo $this->_in;
		return $this;
	}
	public function OrderBy($string = "",$val = "asc"){
		
		if(!empty($string)){
			if($this->_join)
			{
				
				$this->_orderBy = " ORDER BY " .$this->_tableName .  "." .$string. " ".$val;
			}
			else {
				
			$this->_orderBy = " ORDER BY " .$string. " ".$val;
			}
		}
		return $this;
	}
	public function GroupBy($string = ""){
		if(!empty($string)){
			if($this->_join)
			{
			$this->_groupBy = " GROUP BY ".$this->_tableName . "." .$string;
			}
			else {
			
				$this->_groupBy = " GROUP BY " .$string;
			}
		}
		return $this;
	}

    public function Select() {
        $bool = false;
        $this->_query = "";
        
        $fields = " * ";

        if (count($this->_values) > 0) {
            $fields = implode(', ', $this->_values);
        }

      	if($this->_in)
	{
		$this->_where="";
	}

        $table = $this->_tableName;

        if (!empty($table)) {
			$this->_query .= "SELECT ";
			$this->_query .= $fields;
			
            $this->_query .= " FROM " . $this->_tableName. $this->_join;
            $where = $this->_where;

            if (!empty($where)) {
                $this->_query .= " WHERE " . $where;
            }
if(!empty($this->_in) && !empty($this->_join))
{
$this->_query .= " WHERE " .$this->_tableName . "." . $this->_in;
}	
			if (!empty($this->_groupBy)) {
                $this->_query .= $this->_groupBy;
            }
			if (!empty($this->_orderBy)) {
                $this->_query .= $this->_orderBy;
            }
    //$this->_query .= ";";
	    if (!empty($this->_having)) {
                $this->_query .= $this->_having;
            }
	    if (!empty($this->_as))
	    {
                	
		foreach ($this->_as as $key => $value)
		{	
		    $q='';
		    $q=$key." ". $value;
	            $this->_query=str_replace($key,$q,$this->_query);
		}
	    }
            if (!empty($this->_limit)) {
                $this->_query .= $this->_limit;
            }
	    
            $this->_result = mysql_query($this->_query);
	    
            $bool = true;
        }
	$this->_keys=array();          $this->_values=array();    $this->_where=""; $this->_join=""; $this->_groupBy="";
        return $bool;
    }

    public function Insert() {
        $bool = false;

        $this->_query = "";
       
        // print_r($this->_keys);
	//print_r($this->_values);
        $countKey = count($this->_keys);
        $countValue = count($this->_values);
        $data = array();

        if (($countKey > 0 && $countValue > 0) && $countKey == $countValue) {
			$this->_query .= "INSERT INTO  ";
            $fields = " ";
            $valuesData = "";

            for ($i = 0; $i < $countKey; $i++) {

                $val = $this->_values [$i];
                if (is_string($val)) {
                    $val = " \"$val\" ";
                }
                $valuesData [] = $val;
            }

            $fields = implode(", ", $this->_keys);
            $values = implode(", ", $valuesData);

            $table = $this->_tableName;

            if (!empty($table)) {
                $this->_query .= "$table ($fields) values($values)";

                $sql = mysql_query($this->_query);

                $this->_insertId = mysql_insert_id();
                if ($this->_insertId > 0) {
                    $bool = true;
                }
            }
        }
	$this->_keys=array();          $this->_values=array();      $this->_where="";
        return $bool;
    }

    public function Update() {
        $bool = false;

        $this->_query = "";

        $countKey = count($this->_keys);
        $countValue = count($this->_keys);
        $data = array();

        if (($countKey > 0 && $countValue > 0) && $countKey == $countValue) {
			$this->_query .= "UPDATE ";
            $fields = "";

            for ($i = 0; $i < $countKey; $i++) {
                $val = $this->_values [$i];
                if (is_string($val)&&!strpos($val,"+1")) {
                    $val = " '$val' ";
                }
                $data [] = "`" . $this->_keys[$i] . "` = " . $val;
            }

            if (count($data) > 0) {
                $fields .= " SET " . implode(", ", $data);
            }

            $table = $this->_tableName;

            if (!empty($table) && strlen($fields) > 0) {
                $this->_query .= $this->_tableName . $fields;
                $where = $this->_where;
                if (!empty($where)) {
                    $this->_query .= " WHERE " . $where;
                }
		if(!empty($this->_in))
                {
                	$this->_query .= " WHERE ". $this->_in;
                
                }

                $sql = mysql_query($this->_query);
                if($sql)
		{
                	$bool = true;
		//	$this->_query =  '';
		}
            }
        }
	$this->_keys=array();          $this->_values=array();  $this->_in=array();
        return $bool;
    }

    public function Delete() {
        $bool = false;

        $this->_query = "";
      

        $table = $this->_tableName;
        $where = $this->_where;

        if (!empty($table) && strlen($where) > 0) {
			$this->_query .= "DELETE FROM ";
            $this->_query .= $this->_tableName;

            if (!empty($where)) {
                $this->_query .= " WHERE " . $where;
            }

            $sql = mysql_query($this->_query);
            $bool = true;
        }

        return $bool;
    }

    public function lastQuery() {
        return $this->_query;
    }

    public function affectedRows() {
        return mysql_affected_rows();
    }

    public function lastInsertId() {
        return $this->_insertId;
    }

    private function _keys($data = array()) {
        $this->_keys = $data;
    }

    private function _value($data = array()) {
        $this->_values = $data;
    }

    public function resultArray() {
        $records = array();
        if (!empty($this->_result)) {
// 			$records = mysql_fetch_assoc ( $this->_result );
            while ($row = mysql_fetch_assoc($this->_result)) {
                $records[] = $row;
            }
        }
	
	//print_r ($records);
        return $records;
    }

}
