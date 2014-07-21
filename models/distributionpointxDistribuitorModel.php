<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class distributionpointxDistribuitor extends DBManagerModel{
	
    public function getList($params = array()){
       
        $entity = $this->entity();
        
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;

        $start = $params["limit"] * $params["page"] - $params["limit"];
        
        $query = "SELECT   `pdcId`,n.`description`,n.distributorId AS 'distributorId',cityId,n.`date_entered`, `display_name` AS `created_by`, d.userwordpress
                          FROM  `".$entity["tableName"]."` n 
                          INNER JOIN ".$this->wpPrefix."users u ON u.ID = n.created_by
                          INNER JOIN ".$this->pluginPrefix."distributor d ON d.distributorId = n.distributorId
                  WHERE n.`deleted` = 0 
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
        $this->addRecord($this->entity(), $_POST, array("date_entered" => date("Y-m-d H:i:s"), "created_by" => $this->currentUser->ID));       
    }
    public function edit(){
       $this->updateRecord($this->entity(), $_POST, array("pdcId" => $_POST["pdcId"]));
    }
    public function del(){
       $this->delRecord($this->entity(), array("pdcId" => $_POST["id"]));
    }

    public function entity()
    {
        
        if(in_array('administrator', $this->currentUser->roles)){
            $wheredistributor = " 1 ";
        }else{
            $wheredistributor =  " distributorId IN(SELECT distributorId FROM ".$this->pluginPrefix."distributor WHERE userwordpress = ".$this->currentUser->ID.")";
        }
        $data = array(
                        "tableName" => $this->pluginPrefix."distributionpoint"
                        ,"columnValidateEdit" => "userwordpress"
                        ,"entityConfig" => array("add" => true, "edit" => true, "del" => false, "view" => false)
                        ,"atributes" => array(                                        
                                        "pdcId" => array("type" => "int", "PK" => 0,"required" => false, "readOnly" => true, "autoIncrement" => true )                                        
                                       ,"description" => array("type" => "varchar", "required" => true)                                       
                                       ,"distributorId" => array("type" => "int","label"=>"distributor", "required" => true, "references" => array("table" => $this->pluginPrefix."distributor", "id" => "distributorId", "text" => "description", "where" => $wheredistributor ), "update" => false)
                                       ,"cityId" => array("type" => "int","label"=>"cities", "required" => true, "references" => array("table" => $this->pluginPrefix."cities", "id" => "cityId", "text" => "description"))                                                
                                       ,"date_entered" => array("type" => "datetime", "required" => false, "edithidden"=> true, "readOnly" => true ,"update" => false )
                                       ,"created_by" => array("type" => "varchar","required" => false, "readOnly" => true, "update" => false)
                                       ,"userwordpress" => array("type" => "int","required" => false, "hidden" => true, "isTableCol" => false, "update" => false)
                                       
                            )
                       
                    );
        return $data;
    }
}
?>