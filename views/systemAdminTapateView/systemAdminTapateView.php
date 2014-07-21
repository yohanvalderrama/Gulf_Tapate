<div class="row-fluid">
	<div id="tabs" class="span6">
		<ul id="systemAdminTapateTab" class="nav nav-tabs">
                <li class="active"><a href="#chartTab" data-toggle="tab"><?php echo $resource->getWord("systemAdminTapate"); ?></a></li>
		<li><a href="#zoneTab" data-toggle="tab"><?php echo $resource->getWord("zone"); ?></a></li>     
		<li><a href="#citiesTab" data-toggle="tab"><?php echo $resource->getWord("cities"); ?></a></li>  
		<li><a href="#distributorTab" data-toggle="tab"><?php echo $resource->getWord("distributor"); ?></a></li>
                <li><a href="#puntoacopioTab" data-toggle="tab"><?php echo $resource->getWord("puntoacopio"); ?></a></li>
                <li><a href="#positionTab" data-toggle="tab"><?php echo $resource->getWord("positions"); ?></a></li>                
		</ul>
		<div id="TabContent" class="tab-content">
                        <div class="tab-pane fade active" id="chartTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap"> 
                                                <div class="row">
                                                    <div id="distributionPointChart" style="width:60%; height:300px" class="col-md-7"></div>
                                                    <div id="distributorChart" style="width:40%; height:300px" class="col-md-3"></div>                                                   
                                                </div>                                                
                                            </div>
                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>			
			<div class="tab-pane fade active" id="zoneTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="zone"></table>
                                                <div id="zonePager"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
			</div>
			<div class="tab-pane fade active" id="citiesTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="cities"></table>
                                                <div id="citiesPager"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
			</div>
			<div class="tab-pane fade active" id="distributorTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="distributor"></table>
                                                <div id="distributorPager"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
			</div>
                        <div class="tab-pane fade active" id="puntoacopioTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="puntoacopio"></table>
                                                <div id="puntoacopioPager"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
			</div>
                        <div class="tab-pane fade active" id="positionTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="position"></table>
                                                <div id="positionPager"></div>
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
      var tab = jQuery('#systemAdminTapateTab li:eq(0) a').attr("href");
      jQuery(tab).css("opacity", 1);
   });
</script>