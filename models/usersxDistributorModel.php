<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class usersxDistributor extends DBManagerModel{
	
    public function getList($params = array()){
        $entity = $this->entity();
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;
//AND n.created_by = ".$this->currentUser->ID."
        $start = $params["limit"] * $params["page"] - $params["limit"];
        $query = "SELECT `formRegisterUserId`, n.pdcId, n.description, `positionId`, n.identification, n.phone, n.address,n.user,n.password, n.email, n.date_entered, `display_name` AS `created`,d.userwordpress,n.User_Id                                     
                        FROM  `".$entity["tableName"]."` n
                            INNER JOIN `".$this->pluginPrefix."distributionpoint` dp ON dp.pdcId = n.pdcId
                            INNER JOIN `".$this->pluginPrefix."distributor` d ON d.distributorId = dp.distributorId   
                            INNER JOIN ".$this->wpPrefix."users u ON u.ID = n.created_by
                  WHERE  n.deleted = 0 
                  
                ";
        
        if(in_array('administrator', $this->currentUser->roles)){
            $query .= "  ";
        }else{
            $query .= " AND d.`userwordpress` = ".$this->currentUser->ID." ";
        }
        
        if(array_key_exists('where', $params)){
            if (is_array( $params["where"]->rules )){
                $countRules = count($params["where"]->rules);
                for($i = 0; $i < $countRules; $i++){
                    if($params["where"]->rules[$i]->field == "created_by")
                        $params["where"]->rules[$i]->field = "display_name";
                }
            }
            $query .= " AND (". $this->buildWhere($params["where"]) .")";
        }  
      
        return $this->getDataGrid($query, $start, $params["limit"] , $params["sidx"], $params["sord"] );
    } 
    
    
    
    public function add(){      
        $rol = 'subscriber';
        unset($_POST['userwordpress']);
        $addUser = addUserWordpress($_POST,$rol);
        if($addUser>0){                        
            $this->addRecord($this->entity(), $_POST, array("date_entered" => date("Y-m-d H:i:s"), "created_by" => $this->currentUser->ID, "User_Id" =>$addUser));            
            echo $this->LastId;
        }else{
            header('Content-type: text/javascript');
                echo ' alert("'.$addUser.'"); ';
        }
    }
    public function edit(){
        $rol = 'subscriber';
        unset($_POST['userwordpress']);
        $updateUser = updateUserWordpress($_POST,$rol);        
        if($updateUser>0){           
            $this->updateRecord($this->entity(), $_POST, array("formRegisterUserId" => $_POST["formRegisterUserId"]));
        }else{
            header('Content-type: text/javascript');
                echo ' alert("'.$updateUser.'"); ';
        }        
    }
    public function del(){
        $this->delRecord($this->entity(), array("formRegisterUserId" => $_POST["id"]));
    }

    public function entity()
    {
        if(in_array('administrator', $this->currentUser->roles)){
            $wheredistripoint = " 1 ";
        }else{
            $wheredistripoint =  " distributorId IN(SELECT distributorId FROM ".$this->pluginPrefix."distributor WHERE userwordpress = ".$this->currentUser->ID.") ";
        }
        $data = array(
                         "tableName" => $this->pluginPrefix."formregisterusers"
                        ,"columnValidateEdit" => "userwordpress"
                        ,"entityConfig" => array("add" => true, "edit" => true, "del" => true, "view" => false)                        
                        ,"atributes" => array(
                             "formRegisterUserId" => array("type" => "int", "PK" => 0, "required" => false,"edithidden"=>true, "readOnly" => true, "autoIncrement" => true )
                            ,"pdcId" => array("type" => "int","label"=>"puntoacopio", "required" => true, "references" => array("table" => $this->pluginPrefix."distributionpoint", "id" => "pdcId", "text" => "description", "where" => $wheredistripoint))
                            ,"description" => array("type" => "varchar", "label" => "name", "required" => true, "update" => false)                                                                                   
                            ,"positionId" => array("type" => "int","label"=>"positions", "required" => true, "references" => array("table" => $this->pluginPrefix."position", "id" => "positionId", "text" => "description"))
                            ,"identification" => array("type" => "varchar", "required" => true) 
                            ,"phone" => array("type" => "varchar", "required" => true) 
                            ,"address" => array("type" => "varchar", "required" => true) 
                            ,"user" => array("type" => "varchar", "required" => true)
                            ,"password" => array("type" => "varchar", "edittype" => 'password', "required" => true)
                            ,"email" => array("type" => "varchar", "required" => true, "edittype" => 'email') 
                            ,"date_entered" => array("type" => "datetime", "required" => false, "readOnly" => true , "update" => false)
                            ,"created_by" => array("type" => "varchar", "required" => false, "readOnly" => true, "update" => false)
                            ,"userwordpress" => array("type" => "int", "hidden" => true, "required" => false, "readOnly" => true, "update" => false, "isTableCol" => false)                                                                                         
                            ,"User_Id" => array("type" => "int", "hidden" => true, "required" => false, "readOnly" => true, "update" => false, "isTableCol" => false) 
                        )
                );
        return $data;
    }
}
?>