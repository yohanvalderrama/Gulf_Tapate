<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class valueproductreference extends DBManagerModel{
	
    public function getList($params = array()){
        $entity = $this->entity();
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;

        $start = $params["limit"] * $params["page"] - $params["limit"];
        $query = "SELECT `referencevalueId`, productreferenceId,startdatetime,enddatetime,value, date_entered, `display_name` AS `created_by`, created_by AS `created` 
                          FROM  `".$entity["tableName"]."` n                         
                          INNER  JOIN ".$this->wpPrefix."users u ON u.ID = n.created_by
                    WHERE deleted = 0
                ";
         
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
        
	$this->queryType = "var";
        $query = "SELECT count(1) total
                        FROM  `".$this->pluginPrefix."valueproductreference` n                                                 
                WHERE deleted = 0 AND '".$_POST['startdatetime']."' BETWEEN startdatetime AND enddatetime
                AND productreferenceId = ".$_POST['productreferenceId']."
        ";
        $data = $this->get($query, $this->queryType);
        //echo $data["data"];
        if($data["data"] > 0){
             header('Content-type: text/javascript');
                echo ' alert("El período seleccionado ya se encuentra activo elimine el valor"); ';
        }else{                
            $this->addRecord($this->entity(), $_POST, array("date_entered" => date("Y-m-d H:i:s"), "created_by" => $this->currentUser->ID));
            echo $this->LastId;       
        }
    }
    public function edit(){
        $this->updateRecord($this->entity(), $_POST, array("referencevalueId" => $_POST["referencevalueId"]));
    }
    public function del(){
        $this->delRecord($this->entity(), array("referencevalueId" => $_POST["id"]));
    }

    public function entity()
    {
        $data = array(
                        "tableName" => $this->pluginPrefix."valueproductreference"
                        ,"columnValidateEdit" => "created"
                        ,"entityConfig" => array("add" => true, "edit" => false, "del" => true, "view" => false)
                        ,"atributes" => array(
                                "referencevalueId" => array("type" => "int", "PK" => 0, "required" => false, "readOnly" => true, "autoIncrement" => true )//array("type" => "int", "PK" => 0, "required" => false,"edithidden"=>true, "readOnly" => true, "autoIncrement" => true )                            
                                ,"productreferenceId" => array("type" => "int","label"=>"stockcode", "required" => true, "references" => array("table" => $this->pluginPrefix."productreference", "id" => "productreferenceId", "text" => "stockcode"))
                                ,"startdatetime" => array("type" => "datetime", "required" => true, "edithidden"=> true, "readOnly" => false ,"update" => false )
                                ,"enddatetime" => array("type" => "datetime", "required" => true, "edithidden"=> true, "readOnly" => false ,"update" => false )
                                ,"value" => array("type" => "int",  "required" => true, "edittype" => "int")                                                                                       
                                ,"date_entered" => array("type" => "datetime", "required" => false, "edithidden"=> true, "readOnly" => true ,"update" => false )
                                ,"created_by" => array("type" => "varchar", "required" => false, "hidden" => true, "readOnly" => true, "update" => false)
                                ,"created" => array("type" => "int",  "required" => false, "hidden" => true, "readOnly" => true,   "update" => false, "isTableCol" => false)                                                                                       
                        )
                );
        return $data;
    }
}
?>