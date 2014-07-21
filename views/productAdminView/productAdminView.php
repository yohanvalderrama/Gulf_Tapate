<div class="row-fluid">
	<div id="tabs" class="span6">
		<ul id="productAdminTab" class="nav nav-tabs">
                <li class="active"><a href="#chartProductTab" data-toggle="tab"><?php echo $resource->getWord("productAdminTapate"); ?></a></li>
		<li><a href="#productReferencesTab" data-toggle="tab"><?php echo $resource->getWord("productReferences"); ?></a></li>
                <li><a href="#valueproductreferenceTab" data-toggle="tab"><?php echo $resource->getWord("valueproductreference"); ?></a></li>
                <li><a href="#codeproducttotalTab" data-toggle="tab"><?php echo $resource->getWord("codeproduct"); ?></a></li>
		</ul>
		<div id="TabContent" class="tab-content">
                        <div class="tab-pane fade active" id="chartProductTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap"> 
                                                <div class="row">                                                   
                                                    <div id="TotalCodesChart" style="width:50%; height:250px" class="col-md-3"></div>
						    <div id="TotalValidateCodesChart" style="width:50%; height:250px" class="col-md-3"></div>                                                   
                                                </div>
						<div class="row">
							Total c&oacute;digos validados por ciudades
                                                    <div id="geoCodesChart" style="width:100%; height:300px" class="col-md-10"></div>						    
                                                </div>
                                            </div>
                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>						
                        <div class="tab-pane fade active" id="productReferencesTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="productreference"></table>
                                                <div id="productreferencePager"></div>
                                                
                                                
                                                <div id="tabs" class="span6">
                                                    <ul id="systemProductTab" class="nav nav-tabs active">
                                                        <li class="active_tab"><a href="#productlineTab" data-toggle="tab"><?php echo $resource->getWord("line"); ?></a></li>                                                        
                                                        <li><a href="#producttypeTab" data-toggle="tab"><?php echo $resource->getWord("type"); ?></a></li>  
                                                        <li><a href="#productpresentationTab" data-toggle="tab"><?php echo $resource->getWord("presentation"); ?></a></li>
                                                    </ul>
                                                    <div id="TabContent" class="tab-content">
                                                        <div class="tab-pane fade active" id="productlineTab">
                                                            <div class="span3">
                                                                <div class="span9">
                                                                    <div class="jqGrid">
                                                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                                                            <div class="wrap">
                                                                                <table id="productline"></table>
                                                                                <div id="productlinePager"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade active" id="producttypeTab">
                                                            <div class="span3">
                                                                <div class="span9">
                                                                    <div class="jqGrid">
                                                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                                                            <div class="wrap">
                                                                                <table id="producttype"></table>
                                                                                <div id="producttypePager"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade active" id="productpresentationTab">
                                                            <div class="span3">
                                                                <div class="span9">
                                                                    <div class="jqGrid">
                                                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                                                            <div class="wrap">
                                                                                <table id="productpresentation"></table>
                                                                                <div id="productpresentationPager"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                
                                                
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
			</div>
                        <div class="tab-pane fade active" id="valueproductreferenceTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="valueproductreference"></table>
                                                <div id="valueproductreferencePager"></div>
                                             </div>                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade active" id="codeproducttotalTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="codeproducttotal"></table>
                                                <div id="codeproducttotalPager"></div>
                                                
					     </div>                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                </div>
	</div> 
</div>

<script>
    jQuery(function () {
      var tab = jQuery('#productAdminTab li:eq(0) a').attr("href");      
      jQuery(tab).css("opacity", 1);
      
   });
</script>