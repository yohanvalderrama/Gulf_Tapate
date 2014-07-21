<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class productAdmin extends DBManagerModel{
   
    public function getList($params = array()){
        $entity = $this->entity();
        $start = $params["limit"] * $params["page"] - $params["limit"];
        $query = "SELECT  `nonConformityId`, `name`, `description` 
                            , `estadonc`, `nombre_del_clientenc`
                            , `telefononc`, `fuentenc`, `generalidadnc`, `sedenc`
                            , `gestion`, `clasificacion_nc`, created_by, `assigned_user_id`
                          FROM ".$entity["tableName"]."
                          WHERE `deleted` = 0";
        return $this->getDataGrid($query, $start, $params["limit"] , $params["sidx"], $params["sord"]);
    }

    public function add(){
        $this->addRecord($this->entity(), $_POST, array("date_entered" => date("Y-m-d H:i:s"), "created_by" => $this->currentUser->ID));
    }
    public function edit(){
        $this->updateRecord($this->entity(), $_POST, array("nonConformityId" => $_POST["nonConformityId"]));
    }
    public function del(){
        $this->delRecord($this->entity(), array("nonConformityId" => $_POST["id"]));
    }

    public function entity()
    {
            $data = array(
                            "tableName" => $this->pluginPrefix."nonConformities"
                            ,"atributes" => array(
                                "nonConformityId" => array("type" => "int", "PK" => 0, "required" => false, "readOnly" => true, "autoIncrement" => true )
                                ,"name" => array("type" => "varchar", "required" => true)
                                ,"description" => array("type" => "text", "required" => true, "text" => true)
                                ,"estadonc" => array("type" => "tinyint", "required" => true, "references" => array("table" => $this->pluginPrefix."status", "id" => "statusid", "text" => "status"))
                                ,"nombre_del_clientenc" => array("type" => "varchar", "required" => true)
                                ,"telefononc" => array("type" => "varchar", "required" => true)
                                ,"fuentenc" => array("type" => "tinyint", "required" => true, "references" => array("table" => $this->pluginPrefix."sources", "id" => "sourceId", "text" => "source"))
                                ,"generalidadnc" => array("type" => "tinyint", "required" => true, "references" => array("table" => $this->pluginPrefix."generalities", "id" => "generalityId", "text" => "generality"))
                                ,"sedenc" => array("type" => "tinyint", "required" => true, "references" => array("table" => $this->pluginPrefix."offices", "id" => "officeId", "text" => "office"))
                                ,"gestion" => array("type" => "tinyint", "required" => true, "references" => array("table" => $this->pluginPrefix."managements", "id" => "managementId", "text" => "management")
                                ,"clasificacion_nc" => array("type" => "tinyint", "required" => true, "references" => array("table" => $this->pluginPrefix."classifications", "id" => "classificationId", "text" => "classification"))
                                ,"created_by" => array("type" => "bigint", "hidden" => true, "required" => false)
                                ,"assigned_user_id" => array("type" => "char", "hidden" => true, "required" => false))
                            )
                    );
            return $data;
    }
}
?>