<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class files extends DBManagerModel{
	
    public function getList($params = array()){

            if(!array_key_exists('filter', $params))
                    $params["filter"] = 0;

            $start = $params["limit"] * $params["page"] - $params["limit"];
            $query = "SELECT `fileId`, `fileName`, `created` as date_entered, `ext`, `size`
                              FROM  `".$this->pluginPrefix."files` n
                              WHERE  `deleted` = 0 AND `fileId` IN ( ". $params["filter"] ." )";

            return $this->getDataGrid($query, $start, $params["limit"] , $params["sidx"], $params["sord"] );
    }

    public function getNonConformitiesFiles($params = array()){
            $query = "SELECT  `fileId`
                              FROM  `".$this->pluginPrefix."nonConformities_files`
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
    }
    public function edit(){
    }
    public function del(){

    }

    public function entity()
    {
        $data = array(
                    "tableName" => $this->pluginPrefix."files"
                    ,"atributes" => array(
                        "fileId" => array("type" => "int", "download" => true, "PK" => 0, "required" => false, readOnly => true )
                        ,"fileName" => array("type" => "varchar", "required" => true)
                        ,"date_entered" => array("type" => "datetime", "required" => false, "readOnly" => true )
                        ,"ext" => array("type" => "varchar", "required" => false, "readOnly" => true)
                        ,"size" => array("type" => "bigint", "required" => false, "readOnly" => true)
                    )
                );
            return $data;
    }
}
?>