<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class codeproduct extends DBManagerModel{
	
    public function getList($params = array()){
        $entity = $this->entity();
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;

        if($params["page"]==0)
            $params["page"] = 1;
        $start = $params["limit"] * $params["page"] - $params["limit"];
        $query = "SELECT `codesproductid`, c.productreferenceId,codex,download,validated, n.date_entered, `display_name` AS `created_by`, n.created_by AS `created` 
                          FROM  `".$entity["tableName"]."` n                         
                          INNER JOIN ".$this->pluginPrefix."codeproducttotal c ON c.codeproducttotalid = n.codeproducttotalid
                          INNER  JOIN ".$this->wpPrefix."users u ON u.ID = n.created_by
                          WHERE  n.deleted = 0 AND n.`codeproducttotalid` IN ( ". $params["filter"] ." ) LIMIT 0";
         
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
        $idData = $this->LastId;
        $total = $_POST['total'];
        for($i=1;$i<$total;$i++){
            echo $i.'asas';
            $objDateTime = date('YmdHis');       
            $codevalue = khash($objDateTime.$i.$_POST['productreferenceId']);
            
                $dataArray = array("codeproducttotalid"=>$idData
                                    ,"codex"=>$codevalue
                                    ,"date_entered"=>date("Y-m-d H:i:s")
                                    ,"created_by"=>$this->currentUser->ID);

                $this->conn->insert( "wp_tpt_codeproduct", $dataArray );  
            
        }
    }
    public function edit(){
        $this->updateRecord($this->entity(), $_POST, array("codeproducttotalid" => $_POST["codeproducttotalid"]));
    }
    public function del(){
        $this->delRecord($this->entity(), array("codeproducttotalid" => $_POST["id"]));
    }
    
    public function entity()
    {
        $data = array(
                        "tableName" => $this->pluginPrefix."codeproduct"
                        ,"columnValidateEdit" => "created"
                        ,"entityConfig" => array("add" => false, "edit" => false, "del" => false, "view" => false,"csv"=>true)
                        ,"atributes" => array(
                                "codesproductid" => array("type" => "int", "PK" => 0, "required" => false, "readOnly" => true, "autoIncrement" => true )//array("type" => "int", "PK" => 0, "required" => false,"edithidden"=>true, "readOnly" => true, "autoIncrement" => true )                            
                                ,"productreferenceId" => array("type" => "int","label"=>"stockcode", "required" => true, "references" => array("table" => $this->pluginPrefix."productreference", "id" => "productreferenceId", "text" => "stockcode"))
                                ,"codex" => array("type" => "varchar","label" =>"code", "required" => true,"update" => false )       
                                ,"download" => array("type" => "int",  "required" => true, "edittype" => "int","update" => false )          
                                ,"validated" => array("type" => "int",  "required" => true, "edittype" => "int","update" => false )
                                ,"date_entered" => array("type" => "datetime", "required" => false, "edithidden"=> true, "readOnly" => true ,"update" => false )
                                ,"created_by" => array("type" => "varchar", "required" => false, "readOnly" => true, "update" => false)
                                ,"created" => array("type" => "int",  "required" => false, "hidden" => true, "readOnly" => true,   "update" => false, "isTableCol" => false)                                                                                       
                        )
                );
        return $data;
    }
    
    public function excel($params = array()){        
        $params = $_POST;
         
        $entity = $this->entity();
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;
        if($params["page"]==0)
            $params["page"] = 1;
        $start = $params["limit"] * $params["page"] - $params["limit"];
        $query = "SELECT codex 
                          FROM  `".$entity["tableName"]."` n
                          WHERE  download = 0 AND `codeproducttotalid` IN ( ". $params["filter"] ." )";
                
        
        $data = $this->getDataGrid($query, null, null , null, null );        
        if($data['totalRows']==0){
             header('Content-type: text/javascript');
                echo ' alert("Estos códigos ya fueron descargados"); ';
        }else{
            foreach ($data['data'] as $key =>$value){
               foreach($value as $k => $v)
                  $datavalue[] = array($v); 
            }

            $replaceBlank = array('"]','["');

            $DataArray = str_ireplace($replaceBlank,'"',json_encode($datavalue));

            $this->conn->update( 
                    'wp_tpt_codeproduct', 
                    array( 
                            'download' => '1'
                    ), 
                    array( 'codeproducttotalid' => $params["filter"] )
            );

            echo $DataArray;
        }
       
        
    }
}
?>