<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('DBManagerModel.php');
class codeproducttotal extends DBManagerModel{
	
    public function getList($params = array()){
        $entity = $this->entity();
        if(!array_key_exists('filter', $params))
                $params["filter"] = 0;

        $start = $params["limit"] * $params["page"] - $params["limit"];
        $query = "SELECT `codeproducttotalid`, productreferenceId,total, date_entered, `display_name` AS `created_by`, created_by AS `created` 
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
        $idData = $this->LastId;
        for($i=1;$i<=$_POST['total'];$i++){
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

    public function getChart($params = array()){
        switch ($params["queryId"])
        {
            case "pieCodes": 
                $query = "
                    SELECT p.stockcode,SUM(c.total)		    
		    FROM `wp_tpt_codeproducttotal` c
		    INNER JOIN `wp_tpt_productreference` p ON p.productreferenceId = c.productreferenceId
		   /* WHERE c.dateTime
		    BETWEEN DATE_FORMAT( CURDATE( ) , '%Y-%m-01' )
		    AND CURDATE( ) */
		    GROUP BY p.stockcode 
                ";
            break;
            case "pieValidateCodes": 
                $query = "
                    SELECT p.stockcode, COUNT( r.codeRegisteredId ) Total
		    FROM `wp_tpt_codeproducttotal` c
		    INNER JOIN `wp_tpt_productreference` p ON p.productreferenceId = c.productreferenceId
		    INNER JOIN `wp_tpt_codeproduct` cp ON cp.codeproducttotalid = c.codeproducttotalid
		    INNER JOIN `wp_tpt_registeredcodes` r ON r.codeRegisteredId = cp.codesproductid
		   /* WHERE r.dateTime
		    BETWEEN DATE_FORMAT( CURDATE( ) , '%Y-%m-01' )
		    AND CURDATE( ) */
                ";
            break;
	    case "geoCodes": 
                $query = "
		    SELECT c.Description,COUNT(rc.codeRegisteredId) total
		    FROM `wp_tpt_registeredcodes` rc
		    INNER JOIN `wp_tpt_formregisterusers` ru ON ru.User_Id = rc.UserId
		    INNER JOIN `wp_tpt_distributionpoint` dp ON dp.pdcId = ru.pdcId
		    INNER JOIN `wp_tpt_cities` c ON c.cityId = dp.cityId
		   /* WHERE rc .dateTime
		    BETWEEN DATE_FORMAT( CURDATE( ) , '%Y-%m-01' )
		    AND CURDATE( )*/
		    GROUP BY c.Description                   
                ";
            break;
        }       
        return $this->getDataGrid($query);
    }
    
    public function entity()
    {
        $data = array(
                        "tableName" => $this->pluginPrefix."codeproducttotal"
                        ,"columnValidateEdit" => "created"
                        ,"entityConfig" => array("add" => true, "edit" => false, "del" => false, "view" => false,"csv"=>true)
                        ,"atributes" => array(
                                "codeproducttotalid" => array("type" => "int", "PK" => 0, "required" => false, "readOnly" => true, "autoIncrement" => true )//array("type" => "int", "PK" => 0, "required" => false,"edithidden"=>true, "readOnly" => true, "autoIncrement" => true )                            
                                ,"productreferenceId" => array("type" => "int","label"=>"stockcode", "required" => true, "references" => array("table" => $this->pluginPrefix."productreference", "id" => "productreferenceId", "text" => "stockcode"))
                                ,"total" => array("type" => "int",  "required" => true, "edittype" => "int")          
                                ,"date_entered" => array("type" => "datetime", "required" => false, "edithidden"=> true, "readOnly" => true ,"update" => false )
                                ,"created_by" => array("type" => "varchar", "required" => false, "readOnly" => true, "update" => false)
                                ,"created" => array("type" => "int",  "required" => false, "hidden" => true, "readOnly" => true,   "update" => false, "isTableCol" => false)                                                                                       
                        )
                );
        return $data;
    }
    
    public function subentity()
    {
        $data = array(
                        "tableName" => $this->pluginPrefix."codeproduct"
                        ,"columnValidateEdit" => "created"
                        ,"entityConfig" => array("add" => true, "edit" => true, "del" => true, "view" => false)
                        ,"atributes" => array(
                                "codesproductid" => array("type" => "int", "PK" => 0, "required" => false, "readOnly" => true, "autoIncrement" => true )//array("type" => "int", "PK" => 0, "required" => false,"edithidden"=>true, "readOnly" => true, "autoIncrement" => true )                            
                                ,"codeproducttotalid" => array("type" => "int","label"=>"stockcode", "required" => true)
                                ,"codex" => array("type" => "varchar", "required" => true,"update" => false )
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
         
        $entity = $this->subentity();
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
	    $dataValue = "";
            foreach ($data['data'] as $key =>$value){
               foreach($value as $k => $v){
                  //$datavalue[] = array($v);
		  $dataValue .= $v.'\n';
	       }
            }
	    echo '"'.$dataValue.'"';
           // $replaceBlank = array('[[',']]"');

           // $DataArray1 = str_ireplace('[[','[',json_encode($datavalue));
	    //$DataArray = str_ireplace(']]',']',$DataArray1);
	    $DataArray = $datavalue;
            $this->conn->update( 
                    'wp_tpt_codeproduct', 
                    array( 
                            'download' => '1'
                    ), 
                    array( 'codeproducttotalid' => $params["filter"] )
            );

            echo $datavalue;
        }
       
        
    }
    
    public function count($params = array()){        
        $params = $_POST;
	$entity = $this->subentity();
        $this->queryType = "var";
	$query = "SELECT COUNT(1) 
                          FROM  `".$entity["tableName"]."` n
                          WHERE  download = 0 AND `codeproducttotalid` IN ( ". $params["filter"] ." )";
	$data = $this->get($query, $this->queryType);
	echo $data["data"];
	    
	
    }
       
        
    
}
?>