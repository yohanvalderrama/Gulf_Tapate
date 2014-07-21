<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class productline extends DBManagerModel{
	
    public function getList($params = array()){
        $entity = $this->entity();
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;

        $start = $params["limit"] * $params["page"] - $params["limit"];
        $query = "SELECT `productlineId`, description, date_entered, `display_name` AS `created_by`, created_by AS `created` 
                          FROM  `".$entity["tableName"]."` n                         
                          INNER  JOIN ".$this->wpPrefix."users u ON u.ID = n.created_by
                          WHERE  n.deleted = 0 ";
         
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
        $this->addRecord($this->entity(), $_POST, array("date_entered" => date("Y-m-d H:i:s"), "created_by" => $this->currentUser->ID));
        echo $this->LastId;       
    }
    public function edit(){
        $this->updateRecord($this->entity(), $_POST, array("productlineId" => $_POST["productlineId"]));
    }
    public function del(){
        $this->delRecord($this->entity(), array("productlineId" => $_POST["id"]));
    }

    public function entity()
    {
        $data = array(
                        "tableName" => $this->pluginPrefix."productline"
                        ,"columnValidateEdit" => "created"
                        ,"entityConfig" => array("add" => true, "edit" => true, "del" => true, "view" => false)
                        ,"atributes" => array(
                                "productlineId" => array("type" => "int", "PK" => 0, "required" => false, "readOnly" => true, "autoIncrement" => true )//array("type" => "int", "PK" => 0, "required" => false,"edithidden"=>true, "readOnly" => true, "autoIncrement" => true )                            
                                ,"description" => array("type" => "varchar", "required" => true)                                       
                                ,"date_entered" => array("type" => "datetime", "required" => false, "edithidden"=> true, "readOnly" => true ,"update" => false )
                                ,"created_by" => array("type" => "varchar", "required" => false, "readOnly" => true, "update" => false)
                                ,"created" => array("type" => "int",  "required" => false, "hidden" => true, "readOnly" => true,   "update" => false, "isTableCol" => false)                                                                                       
                        )
                );
        return $data;
    }
}
?>