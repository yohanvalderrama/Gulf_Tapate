<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once "DBManager.php"; 
if(!isset($resource)){
	require_once "resources.php";
	$resource = new resources();
}
class Grid extends DBManager
{	
	private $table;
	private $ColMolde;
        private $subColMolde;
	private $colnames = array();
	private $baseId;
	private $entity;
        private $subentity;
	private $params;
	private $loc;
        private $beforeShowForm = "";
        public $ValidateEdit = false;
	public $view;
        public $subview;
	
	function __construct($type = "table", $p, $v, $t, $s, $sp) {
		global $resource;		
                $this->view = $v;
		$this->params = $p;
                $this->subparams = $sp;
		$this->loc = $resource;
                $this->subgrid = $s;
                $this->SubGridBody = "";
		parent::__construct();               
		if($type == "table"){
			require_once $this->pluginPath."/models/".$v."Model.php";
			$this->baseId = $t;
			$this->table = $this->pluginPrefix.$t;
                        if($s == 'subGrid'){
                            $sv = $this->subparams["view"];                            
                            $this->sparams = $this->subparams["params"];
                            $this->subview = $sv;                                                      
                            require_once $this->pluginPath."/models/".$sv."Model.php";
                            $this->baseId = $sv;
                            $this->table = $this->pluginPrefix.$this->subparams["table"];                            
                            $this->SubGridBody = $this->subgridBuilderFromTable();
                        }
                        $this->entity = $v::entity(); 
                        $this->gridBuilderFromTable();
		}
                
	}
	
	function __destruct() {
	}
	
	function RelationShipData($references){
	
                if($references["where"]=="")
                    $references["where"] = " 1 ";
		$DataArray = array();               
		$query = "SELECT " . $references["id"] . " Id, " . $references["text"] . " Name FROM ". $references["table"] ." WHERE ".$references["where"]."" ;
		$Relation = $this->getDataGrid($query, null, null, $references["text"], "ASC");

		foreach ( $Relation["data"] as $k => $v ){
			$DataArray[] = "{".$Relation["data"][$k]->Id.":".htmlspecialchars($Relation["data"][$k]->Name)."}";
		}

		$replaceBlank = array('"','{','}','[');
		$replaceSemicolon = array(',',']');
		
		$DataArray = str_ireplace($replaceBlank,'',json_encode($DataArray/*, JSON_UNESCAPED_UNICODE*/));
		$DataArray = str_ireplace($replaceSemicolon,';',$DataArray);
	
		return $DataArray;
	}
        
        function EnumData($enums){
	
		$DataArray = array();               
		$query = "SHOW COLUMNS FROM ".$enums["table"]." WHERE Field = '" . $enums["id"] . "'  " ;
		$Relation = $this->getDataGrid($query, null, null, null, null);
                $DataArray = array();
                $DataArray[""] = "--Seleccione--";
                if(strpos($Relation["data"][0]->Type,'enum') !== false){
                    $enumList = explode(",", str_replace("'", "", substr($Relation["data"][0]->Type, 5, (strlen($Relation["data"][0]->Type)-6))));
                    
                    foreach($enumList as $value){
                            $DataArray[htmlspecialchars($value)] = htmlspecialchars(utf8_decode($value));
                    }
                }
             

		$replaceBlank = array('"','{','}','[');
		$replaceSemicolon = array(',',']');
		
		$DataArray = str_ireplace($replaceBlank,'',json_encode($DataArray/*, JSON_UNESCAPED_UNICODE*/));
		$DataArray = str_ireplace($replaceSemicolon,';',$DataArray);
	
		return $DataArray;
	}
        
	
    function colModelFromTable(){       
        $countCols = count($this->entity["atributes"]);
    	$j=1;
    	$k=1;
    	$numCols = 2;
        $columnValidateEdit = "";
        
        if(array_key_exists("columnValidateEdit", $this->entity)){
            $this->ValidateEdit = true;
            $this->columnValidateEdit = $this->entity["columnValidateEdit"];
        }
    	
    	foreach ($this->entity["atributes"] as $col => $value){
    		$this->colnames[] = $col;
    		$label = $col;
                if(isset($value['label'])){
                    $label = $value['label'];
                }
                
    		$hidden = (isset($value['hidden']) && $value['hidden'] == true)? true: false;
                
                $edithidden = (isset($value['edithidden']) && $value['edithidden'] == true)? true: false;
    		
    		$required = ($value['required'])? true: false;    	
               
                if($j <= $numCols){
                    $option = array('rowpos' => $k, 'colpos' => $j);
                }else{
                    $k++;
                    $j=1;
                    $option = array('rowpos' => $k, 'colpos' => $j);
                }                
    		
    		$model = array(
    				'label' => $this->loc->getWord($label),
                                'name'=> $col,
    				'index'=> $col,
    				'align' => 'center',
    				'sortable' => true,
    				'editable' => true,
    				'editrules' => array('required' => $required, "edithidden" => $edithidden),
    				'formoptions' => $option,
    				'hidden' => $hidden,
    				'classes'=> 'ellipsis'
                                
    		);
                
                if(isset($value['edittype'])){
                    if($value['edittype']=='email'){
                         $model['editrules'] =array_merge($model
                                                    ,array(
                                                        'email'=>true
                                                    )
                            );
                    }else{
                        switch ($value['edittype']){
                            case 'int':
                                $model['editrules'] =array_merge($model
                                                    ,array(
                                                        'integer'=>true
                                                    )
                            );
                            break;
                        
                        }
                        
                       /* $model = array_merge($model
                                                        ,array(
                                                           'edittype' => $value['edittype']
                                                        )
                                );*/
                    }
                }
                
    		if(array_key_exists('references', $value))
    			$colType = "Referenced";
                elseif(array_key_exists('enum', $value))
                        $colType = "Enum";
    		else
    			$colType = $value["type"];    			    		
    		
    		switch($colType){
    			case 'date':
    				$model = array_merge($model
                                                    ,array(
                                                        'sorttype' => "date",
				    			'formatter' => "date",
				    			'formatoptions' => array('newformat' => 'Y-m-d', 'srcformat' => 'Y-m-d'),
				    			'editoptions' => array('dataInit'=>"@initDateEdit@")
                                                        )
                                                    );
    				break;
    			case 'datetime':
    					$model = array_merge($model
                                                            ,array(
                                                                'sorttype' => "date",
                                                                'formatter' => "date",
                                                                'formatoptions' => array('newformat' => 'Y-m-d H:i:s', 'srcformat' => 'Y-m-d H:i:s'),
                                                                'editoptions' => array('dataInit'=>"@initDateEdit@")
                                                                )
                                                            );
    					break;
    			case 'Enum':
                            
                                $QueryData = $this->EnumData($value["enum"]);
    				$model = array_merge($model
    						,array(
                                                    'edittype' => 'select',
                                                    'formatter' => 'select',
                                                    'stype' => 'select',
                                                    'editoptions' => array( value => "@'".$QueryData.":'@", "defaultValue"=>$value["references"]["defaultvalue"] ),
                                                    'searchoptions' => array('value' => "@'".$QueryData.":'@")
    						)
                                            );
    				break;
    			case "Referenced":
    				$QueryData = $this->RelationShipData($value["references"]);
    					
    				$model = array_merge($model
    						,array(
                                                    'edittype' => 'select',
                                                    'formatter' => 'select',
                                                    'stype' => 'select',
                                                    'editoptions' => array( value => "@'".$QueryData.":'@", "defaultValue"=>$value["references"]["defaultvalue"] ),
                                                    'searchoptions' => array('value' => "@'".$QueryData.":'@")
    						)
                                            );
    				break;
    			case 'longblob':
    		
    				break;
    		}
                
                switch($col){
                    case "parentId": $model["editoptions"]["defaultValue"] = "@function(g){return this.p.postData.filter}@"; break;
                    case "parentRelationShip": $model["editoptions"]["defaultValue"] = "@function(g){return this.p.postData.parent}@"; break;
                }
                
                if((!array_key_exists('readOnly', $value) || !$value['readOnly'])
                    && ($colType == "date")){
                        $model["editoptions"]["defaultValue"] = "@function(g){return '".date("Y-m-d", time())."'}@";
                    }
                
                if((!array_key_exists('readOnly', $value) || !$value['readOnly'])
                    && ($colType == "datetime")){
                        $model["editoptions"]["defaultValue"] = "@function(g){return '".date("Y-m-d H:i:s", time())."'}@";
                    }
                
                if($value['text']){
    			$model["edittype"] = "textarea";
    			$model["editoptions"]["rows"] = 6; 
    			$model["editoptions"]["cols"] = 50;
    			
    			if($j == $numCols){
                            $k++;
                            $option = array('rowpos' => $k, 'colpos' => 1);
                            $model["formoptions"] = $option;
    			}
    			$k++;
    			$j=1;
                        
                        $this->beforeShowForm .= "setTextAreaForm(form,'tr_".$col."');";
    		}

    		if($value['readOnly'])
                    $model["editoptions"]["dataInit"] = "@function(element) { jQuery(element).attr('readonly', 'readonly');}@";
    		
    		$j++;
    		$colmodel[] = $model;
    		$model = array();
    	}
        $this->ColModel = str_ireplace('"@',"",json_encode($colmodel));
        $this->ColModel = str_ireplace('@"',"",$this->ColModel);
    }
    
    function subgridBuilderFromTable() {
        $v = $this->subview;
        $this->entity = $v::entity();
        $this->colModelFromTable();
    	$title = $this->table;        
        $this->subgrid;
        $subgrid = $this->subgrid;
        if(isset($subgrid))
        {   
                $subgridBody = '                   
                    subGridRowExpanded: function(subgrid_id, row_id) {
                        var subgrid_table_id, pager_id;
                        subgrid_table_id = subgrid_id + "_t";
                        pager_id = "p_" + subgrid_table_id;                       
                        id = row_id;
                        jQuery("#"+subgrid_id).html("<table id="+subgrid_table_id+" class=scroll></table><div id=" + pager_id + " class=scroll></div>");
                        jQuery("#"+subgrid_table_id).jqGrid({ 
                            url:"admin-ajax.php",
                            datatype: "json",
                            mtype: "POST",
                            postData : {
                                    action: "action",
                                    subview: "'.$v.'",                                    
                                    subId: id
                            },                           
                            colModel:'.$this->ColModel.',
                            //autowidth: true,
                            height: "100%",
                            rowNum:'. $this->sparams["numRows"].',
                            rowList: ['. $this->sparams["numRows"] .', '. ($this->sparams["numRows"] * 2) .', '. ($this->sparams["numRows"] * 3) .'],
                            sortname: "'. $this->sparams["sortname"].'",
                            sortorder: "desc",
                            viewrecords: true,
                            gridview: true,
                            pager: pager_id,
                            caption: "' . $this->loc->getWord($v) . '",
                            hidegrid: false,
                            ignoreCase: true,
                            editurl: "'.$this->pluginURL.'edit.php?controller='.$v.'"
                        }).jqGrid("navGrid","#"+pager_id, {edit:true,add:true,del:true},
                                                { // edit options
                                                    recreateForm: true,
                                                    viewPagerButtons: true,
                                                    width:"99%",
                                                    reloadAfterSubmit:true,
                                                    closeAfterEdit: true
                                                    ,beforeShowForm:function($form){
                                                        $form.find(".FormElement[readonly]")
                                                        .prop("disabled", true)
                                                        .addClass("ui-state-disabled")
                                                        .closest(".DataTD")
                                                        .prev(".CaptionTD")
                                                        .prop("disabled", true)
                                                        .addClass("ui-state-disabled");
                                                        '.$this->beforeShowForm.'
                                                    }
                                                },{//add options
                                                    recreateForm: true,
                                                    viewPagerButtons: false,
                                                    width:"99%",
                                                    reloadAfterSubmit:true,
                                                    closeAfterAdd: true
                                                    ,beforeShowForm:function($form){
                                                        $form.find(".FormElement[readonly]")
                                                        .prop("disabled", true)
                                                        .addClass("ui-state-disabled")
                                                        .closest(".DataTD")
                                                        .prev(".CaptionTD")
                                                        .prop("disabled", true)
                                                        .addClass("ui-state-disabled");
                                                        '.$this->beforeShowForm.'
                                                    }
                                                },{//del option
                                                    mtype:"POST",
                                                    reloadAfterSubmit:true
                                                },{multipleSearch:true
                                                    , multipleGroup:false
                                                    , showQuery: false
                                                    , sopt: ["eq", "ne", "lt", "le", "gt", "ge", "bw", "bn", "ew", "en", "cn", "nc", "nu", "nn", "in", "ni"]
                                                    , width:"99%"
                                                })
                    },';            
        }else{
            $subgridBody = "";
        }
        
        return $subgridBody;
    }
    
    function gridBuilderFromTable() {
    	$this->colModelFromTable();
    	$title = $this->table;     
        $subgridBody = "";
        $subGrid = 'false';
    	if($this->subgrid=='subGrid'){
            $subGrid = 'true';
            $subgridBody =$this->SubGridBody;
        }
    	if(array_key_exists('postData', $this->params)){
    		if(is_array($this->params['postData']))
    		{	
    			$pd = array();
    			foreach ( $this->params['postData'] as $k => $v ){
    				$pd[] = '"'. $k .'":"'. $v .'"';
	    		}
	    		$postData = ",". implode(",", $pd);
    		}
    		else 
    			$postData = "";
    	}
    	else
            $postData = "";               
        
                if($this->ValidateEdit){
                    $scriptEditing = 'var row = jQuery(this).jqGrid("getRowData", rowid);
                                        if(row.'.$this->columnValidateEdit.' != '.$this->currentUser->ID.'){
                                        jQuery("#del_' . $this->view . '").hide();
                                        jQuery("#edit_' . $this->view . '").hide();
                                        }
                                        else{
                                        jQuery("#del_' . $this->view . '").show();
                                        jQuery("#edit_' . $this->view . '").show();
                                        };';
                                if(is_array($this->params["actions"])){
                                    $countParams = count($this->params["actions"]);
                                    $addUpdateFunction = "add";
                                    for($i = 0; $i < $countParams; $i++){
                                        if($this->params["actions"][$i]["type"] == "onSelectRow"){
                                            $addUpdateFunction = "update";
                                            $content = explode("{",$this->params["actions"][$i]["function"]);
                                            $paramsFunction = explode(",",str_replace(array("function","(",")"), "", $content[0]));

                                            if(count($paramsFunction) > 0)
                                            {
                                                $rowid = $paramsFunction[0];
                                                $scriptEditing = str_replace("rowid", $rowid, $scriptEditing);
                                                $content[1] = $scriptEditing . $content[1];
                                                $this->params["actions"][$i]["function"] = implode("{",$content);
                                            }
                                            break;
                                        }
                                    }
                                }
                                else
                                    $addUpdateFunction = "add";

                                if($addUpdateFunction == "add"){
                                    $this->params["actions"][]=array("type" => "onSelectRow"
                                                                    ,"function" => 'function(rowid, e){
                    '. $scriptEditing .'
                    }');
            }
            
            
        }
        
        $this->beforeShowForm .= 'form.find(".FormElement[readonly]")'
                . '.prop("disabled", true)'
                . '.addClass("ui-state-disabled")'
                . '.closest(".DataTD")'
                . '.prev(".CaptionTD")'
                . '.prop("disabled", true)'
                . '.addClass("ui-state-disabled");';                
               
            $grid = 'jQuery(document).ready(function($){
                        $grid = jQuery("#' . $this->view . '"),
                                        initDateEdit = function (elem) {
                                                setTimeout(function () {
                                                        $(elem).datepicker({
                                                                dateFormat: "yy-m-dd",
                                                                autoSize: true,
                                                                showOn: "button", 
                                                                changeYear: true,
                                                                changeMonth: true,
                                                                showButtonPanel: true,
                                                                showWeek: true
                                                        });        
                                                }, 100);
                                        },
                                        numberTemplate = {formatter: "number", align: "right", sorttype: "number",
                                        editrules: {number: true, required: true}
                                };
                        $grid.jqGrid({						
                                        url:"admin-ajax.php",
                                        datatype: "json",
                                        mtype: "POST",
                                        postData : {
                                                action: "action",
                                                id: "' . $this->view . '"
                                                '. $postData.'
                                        },
                                        //colNames:'.json_encode($this->colnames).',					
                                        colModel:'.$this->ColModel.',
                                        rowNum:'. $this->params["numRows"].',
                                        rowList: ['. $this->params["numRows"] .', '. ($this->params["numRows"] * 2) .', '. ($this->params["numRows"] * 3) .'],
                                        pager: "#' . $this->view . 'Pager",						
                                        sortname: "'. $this->params["sortname"].'",
                                        viewrecords: true,
                                        sortorder: "desc",
                                        viewrecords: true,
                                        gridview: true,
                                        height: "100%",
                                        excel: true,
                                        autowidth: true,
                                        subGrid:'.$subGrid.',
                                        '.$subgridBody.'
                                        editurl: "'.$this->pluginURL.'edit.php?controller='.$this->view.'",
                                        caption:"' . $this->loc->getWord($this->view) . '",
                                        beforeRequest: function() {
                                            responsive_jqgrid(jQuery(".jqGrid"));
                                        }';

                                if(array_key_exists('actions', $this->params))
                                {
                                        foreach ($this->params['actions'] as $key => $value){
                                                $grid .= ',' . $value["type"] .': '. $value["function"];
                                        }
                                }						    

                                $grid .= '});
                                        jQuery("#' . $this->view . '").jqGrid("navGrid","#' . $this->view . 'Pager",
                                            {   edit:'.(($this->entity["entityConfig"]["edit"])? "true" : "false").'
                                                ,add:'.(($this->entity["entityConfig"]["add"])? "true" : "false").'
                                                ,del:'.(($this->entity["entityConfig"]["del"])? "true" : "false").'
                                                
                                            }';
                                
                                    if($this->entity["entityConfig"]["edit"]){
                                            $grid .= ',{ // edit options
                                                            recreateForm: true,
                                                            viewPagerButtons: true,
                                                            width:"99%",
                                                            reloadAfterSubmit:true,
                                                            closeAfterEdit: true
                                                            ,afterShowForm:function(form){'.$this->beforeShowForm.' ;}
                                                        }';
                                    }
                                    else
                                        $grid .= ',{}';
                                    
                                    if($this->entity["entityConfig"]["add"]){
                                            $grid .= ',{//add options
                                                            recreateForm: true,
                                                            viewPagerButtons: false,
                                                            width:"99%",
                                                            reloadAfterSubmit:true,
                                                            closeAfterAdd: true
                                                            ,afterShowForm:function(form){'.$this->beforeShowForm.' ;}
                                                        }';
                                    }else
                                        $grid .= ',{}';
                                    
                                    if($this->entity["entityConfig"]["add"]){
                                            $grid .= ',{//del option
                                                            mtype:"POST",
                                                            reloadAfterSubmit:true
                                                            ,beforeShowForm:function(form){'.$this->beforeShowForm.'}
                                                        }';
                                    }else
                                        $grid .= ',{}';
                                    
                                          
                                            $grid .= ',{multipleSearch:true
                                                            , multipleGroup:false
                                                            , showQuery: false
                                                            , sopt: ["eq", "ne", "lt", "le", "gt", "ge", "bw", "bn", "ew", "en", "cn", "nc", "nu", "nn", "in", "ni"]
                                                            , width:"99%"
                                                        })';
                                            if($this->entity["entityConfig"]["csv"]){
                                                $grid .= '.navButtonAdd("#' . $this->view . 'Pager",{
                                                            caption:"Export to csv",
							    id:"csv_' . $this->view . '",
                                                            onClickButton : function () {
								var rowid = jQuery("#' . $this->view . '").jqGrid("getGridParam", "selrow");
								var postData = $("#' . $this->view . '").jqGrid("getGridParam","postData");
                                                                if(rowid){
									$.post( "'.$this->pluginURL.'edit.php?controller='.$this->view.'", {"oper":"excel", "filter":rowid} )
										.done(function( datavalue ) {
											var data = eval(datavalue);//eval(datavalue);//"AOYVIGJ\nZVZUKXV\nAEKEJUB\n";//eval(datavalue);
											var csvContent = "data:text/csv;charset=utf-8,"+data;											
											/*data.forEach(function(infoArray, index){	
											   dataString = infoArray.join(",");											   
											   csvContent += index < infoArray.length ? dataString+ "\n" : dataString;											   
	
											});*/
																							
											    var encodedUri = encodeURI(csvContent);
												var link = document.createElement("a");
												link.setAttribute("href", encodedUri);
												link.setAttribute("download", "my_data.csv");
												jQuery("#csv_codeproducttotal").hide();
												link.click();
												window.open(link);
										});
								}
                                                            }
                                                        })
                                                    '; 
                                            }
                                                if($this->entity["entityConfig"]["view"]){     
                                                    $grid .= '.navSeparatorAdd("#' . $this->view . 'Pager").navButtonAdd("#' . $this->view . 'Pager",{
                                                            caption:"", 
                                                            title: $.jgrid.nav.viewtitle,
                                                            buttonicon:"ui-icon-document", 
                                                            onClickButton: function(){ 
                                                                /*var str = "";
                                                                for(xx in id){
                                                                    str += xx + " -> " + id[xx] + "<br/>";
                                                                }*/
                                                                var rowid = jQuery("#' . $this->view . '").jqGrid("getGridParam", "selrow");
                                                                if(rowid){
                                                                    $.get( "'.$this->pluginURL.'views/'.$this->view.'View/'.$this->view.'Detail.php" )
                                                                        .done(function( data ) {
                                                                        
                                                                            var rowData = jQuery("#' . $this->view . '").jqGrid("getRowData", rowid);
                                                                            var colModel = jQuery("#' . $this->view . '").jqGrid("getGridParam","colModel");
                                                                            
                                                                            for(i = 0; i < colModel.length; i++){
                                                                                data = data.replace("{"+colModel[i].name+"-Label}", colModel[i].label);
                                                                                var valReplace = rowData[colModel[i].name];
                                                                                if(colModel[i].editoptions && jQuery.type(colModel[i].editoptions["value"]) == "string" && colModel[i].editoptions["value"] != ""){
                                                                                    var selectOptions = colModel[i].editoptions["value"].split(";");
                                                                                    
                                                                                    for(var selOp in selectOptions){
                                                                                        selOpArray = selectOptions[selOp].split(":");
                                                                                        if(selOpArray[0] == valReplace)
                                                                                            valReplace = selOpArray[1];
                                                                                    }
                                                                                }
                                                                                data = data.replace("{"+colModel[i].name+"}", valReplace);
                                                                            }
                                                                           
                                                                            jQuery("<div>"+data+"</div>").dialog({
                                                                                height: 400,
                                                                                width: "95%",
                                                                                modal: true,
                                                                                title: $.jgrid.nav.viewtitle
                                                                              });
                                                                    });
                                                                }
                                                            }, 
                                                            position:"last"
                                                         })';
                                                }
                            $grid .= '})';

            echo  $grid;
	}
}
?>