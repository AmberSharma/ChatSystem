<?php

require_once "../libraries/constant.php";
require_once SITEPATH.'model/singleton.php';


abstract class model {

    protected $db = "";

    function __construct() {
        $this->db = DBConnection::Connect();
    }

}


