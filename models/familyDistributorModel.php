<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class familyDistributor extends DBManagerModel{
	
    public function getList($params = array()){
        $entity = $this->entity();
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;

        $start = $params["limit"] * $params["page"] - $params["limit"];
        $query = "SELECT `FamilyInformation_Id`, n.Name, Age,Sex,Kinship,Coexistence,MaritalStatus,Occupation,Phone, n.date_entered, created_by                                        
                          FROM  `".$entity["tableName"]."` n                         
                          INNER  JOIN ".$this->wpPrefix."users u ON u.ID = n.created_by
                          WHERE  n.deleted = 0 AND n.created_by = ".$this->currentUser->ID;
         
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
        $this->updateRecord($this->entity(), $_POST, array("cityId" => $_POST["cityId"]));
    }
    public function del(){
        $this->delRecord($this->entity(), array("cityId" => $_POST["id"]));
    }

    public function entity()
    {
        $data = array(
                        "tableName" => $this->pluginPrefix."familyinformation"
                        ,"columnValidateEdit" => "created_by"
                        ,"entityConfig" => array("add" => true, "edit" => true, "del" => true, "view" => false)                        
                        ,"atributes" => array(
                            "FamilyInformation_Id" => array("type" => "int", "PK" => 0, "required" => false,"edithidden"=>true, "readOnly" => true, "autoIncrement" => true )
                            ,"name" => array("type" => "varchar", "required" => true)                                                       
                            ,"Age" => array("type" => "enum","label"=>"age", "required" => true, "enum" => array("table" => $this->pluginPrefix."familyinformation", "id" => "Age"))
                            ,"Sex" => array("type" => "enum","label"=>"sex", "required" => true, "enum" => array("table" => $this->pluginPrefix."familyinformation", "id" => "Sex"))
                            ,"Kinship" => array("type" => "enum","label"=>"kinship", "required" => true, "enum" => array("table" => $this->pluginPrefix."familyinformation", "id" => "Kinship"))
                            ,"Coexistence" => array("type" => "enum","label"=>"coexistence", "required" => true, "enum" => array("table" => $this->pluginPrefix."familyinformation", "id" => "Coexistence"))
                            ,"MaritalStatus" => array("type" => "enum","label"=>"maritalstatus", "required" => true, "enum" => array("table" => $this->pluginPrefix."familyinformation", "id" => "MaritalStatus"))
                            ,"Occupation" => array("type" => "enum","label"=>"occupation", "required" => true, "enum" => array("table" => $this->pluginPrefix."familyinformation", "id" => "Occupation"))
                            ,"Phone" => array("type" => "varchar", "required" => true) 
                            ,"date_entered" => array("type" => "datetime", "required" => false, "hidden" => true, "readOnly" => true, "update" => false, "isTableCol" => false)
                            ,"created_by" => array("type" => "varchar", "required" => false, "hidden" => true,  "update" => false, "isTableCol" => false)                                                       
                                                                                    
                        )
                );
        return $data;
    }
}
?>