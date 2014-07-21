<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class distributorInf extends DBManagerModel{
	
    public function getList($params = array()){
       
        $entity = $this->entity();
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;

        $start = $params["limit"] * $params["page"] - $params["limit"];
        //echo $entity["tableName"]."asasasasasas";
        $query = "SELECT   `distributorId`,`description`,`user`,`password`,`email`,`date_entered`, `display_name` AS `created_by`,userwordpress
                          FROM  `".$entity["tableName"]."` n
                          INNER  JOIN ".$this->wpPrefix."users u ON u.ID = n.created_by
                  WHERE `deleted` = 0 
                        ";
        if(in_array('administrator', $this->currentUser->roles)){
            $query .= "  ";
        }else{
            $query .= " AND `userwordpress` = ".$this->currentUser->ID." ";
        }
        
        if(array_key_exists('where', $params))
            $query .= " AND (". $this->buildWhere($params["where"]) .")";
        
        return $this->getDataGrid($query, $start, $params["limit"] , $params["sidx"], $params["sord"] );
    }     

    public function add(){ 
        $addUser = addUserWordpress($_POST);
        if($addUser>0){                        
            $this->addRecord($this->entity(), $_POST, array("date_entered" => date("Y-m-d H:i:s"), "created_by" => $this->currentUser->ID, "userwordpress" =>$addUser));            
        }else{
            header('Content-type: text/javascript');
                echo ' alert("'.$addUser.'"); ';
        }
       
    }
    public function edit(){
        $this->updateRecord($this->entity(), $_POST, array("distributorId" => $_POST["distributorId"]));
    }
    public function del(){
        $this->delRecord($this->entity(), array("distributorId" => $_POST["id"]));
    }

    public function entity()
    {
        $data = array(
                        "tableName" => $this->pluginPrefix."distributor"
                        ,"columnValidateEdit" => "userwordpress"
                        ,"entityConfig" => array("add" => false, "edit" => true, "del" => false, "view" => false)
                        ,"atributes" => array(                                        
                                        "distributorId" => array("type" => "int", "PK" => 0,"required" => false, "readOnly" => true, "autoIncrement" => true )                                        
                                       ,"description" => array("type" => "varchar", "required" => true)                                       
                                       ,"user" => array("type" => "varchar", "required" => true)
                                       ,"password" => array("type" => "varchar", "edittype" => 'password', "required" => true)
                                       ,"email" => array("type" => "varchar", "required" => true, "edittype" => 'email')
                                       ,"date_entered" => array("type" => "datetime", "required" => false, "edithidden"=> true, "readOnly" => true ,"update" => false )
                                       ,"created_by" => array("type" => "varchar","required" => false, "readOnly" => true, "update" => false)
                                       ,"userwordpress" => array("type" => "bigint","required" => false,'hidden' => true, "readOnly" => true, "update" => false)
                                    )                        
                    );
        return $data;
    }
    
    
}
?>