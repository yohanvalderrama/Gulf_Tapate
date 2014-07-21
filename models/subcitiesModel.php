<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class subcities extends DBManagerModel{
	public $defaultvalue;
    public function getList($params = array()){        
      
        $entity = $this->entity();
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;
        
        $this->defaultvalue = $_POST['subId'];        
        $start = $params["limit"] * $params["page"] - $params["limit"];
        $query = "SELECT `distributorCityId`, d.distributorId,c.cityId                                       
                          FROM  `".$entity["tableName"]."` n
                          INNER JOIN ".$this->pluginPrefix."distributor d ON d.distributorId = n.distributorId
                          INNER JOIN ".$this->pluginPrefix."cities c ON c.cityId = n.cityId                        
                          WHERE n.deleted = 0 AND d.distributorId = ".$this->defaultvalue."                          
                ";
        $params["sidx"] = "distributorId";
        return $this->getDataGrid($query, $start, $params["limit"] , $params["sidx"], $params["sord"] );
    }

    public function getNonConformitiesTasks($params = array()){
        $query = "SELECT  `taskId`
                          FROM  `".$this->pluginPrefix."nonConformities_tasks`
                          WHERE  `nonConformityId` = " . $params["filter"];

        $responce = $this->getDataGrid($query);

        foreach ( $responce["data"] as $k => $v ){
                $DataArray[] = $responce["data"][$k]->taskId;
        }

        $params["filter"] = implode(",", $DataArray);

        $data = $this->getList($params);
        return $data;
    }

    public function add(){        
        $this->addRecord($this->entity(), $_POST, array("date_entered" => date("Y-m-d H:i:s"), "created_by" => $this->currentUser->ID));
        echo $this->LastId;
    }
    public function edit(){
        $this->updateRecord($this->entity(), $_POST, array("distributorCityId" => $_POST["distributorCityId"]));
    }
    public function del(){
        $this->delRecord($this->entity(), array("distributorCityId" => $_POST["id"]));
    }

    public function entity()
    {
        $this->defaultvalue = $_POST['subId']; 
        $data = array(
                        "tableName" => $this->pluginPrefix."distributor_cities"
                        ,"atributes" => array(
                            "distributorCityId" => array("type" => "int", "PK" => 0, "required" => false,"edithidden"=>true, "readOnly" => true, "autoIncrement" => true )                           
                            ,"distributor" => array("type" => "int","readOnly" => true,  "required" => true, "references" => array("table" => $this->pluginPrefix."distributor", "id" => "distributorId", "text" => "description", "defaultvalue"=>  1 ))
                            ,"cities" => array("type" => "int", "required" => true, "references" => array("table" => $this->pluginPrefix."cities", "id" => "cityId", "text" => "description"))                                                
                                                                                    
                        )
                );
        return $data;
    }
}
?>