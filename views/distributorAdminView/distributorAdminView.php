<div class="row-fluid">
	<div id="tabs" class="span3">
		<ul id="distributorAdminTab" class="nav nav-tabs">
                <li class="active"><a href="#InfoDistributorTab" data-toggle="tab"><?php echo $resource->getWord("informationdistributor"); ?></a></li>
		<li><a href="#distributionpointxDistribuitorTab" data-toggle="tab"><?php echo $resource->getWord("puntoacopio"); ?></a></li>
                <li><a href="#userDistributorTab" data-toggle="tab"><?php echo $resource->getWord("userdistributos"); ?></a></li>
		</ul>
		<div id="TabContent" class="tab-content">                        
                        <div class="tab-pane fade active" id="InfoDistributorTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="distributorInf"></table>
                                                <div id="distributorInfPager"></div>
                                                <div id="tabs" class="span3">
                                                    <ul id="distributorFamilyAdminTab" class="nav nav-tabs">
                                                        <li class="active"><a href="#familyInfTab" data-toggle="tab"><?php echo $resource->getWord("famlilyInf"); ?></a></li>                                                        
                                                    </ul>
                                                    <div id="SubTabContent" class="tab-content">
                                                        <div class="tab-pane fade active" id="familyInfTab">
                                                            <div class="span3">
                                                                <div class="span9">
                                                                    <div class="jqGrid">
                                                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                                                            <div class="wrap">
                                                                                <table id="familyDistributor"></table>
                                                                                <div id="familyDistributorPager"></div>
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
                        <div class="tab-pane fade active" id="distributionpointxDistribuitorTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="distributionpointxDistribuitor"></table>
                                                <div id="distributionpointxDistribuitorPager"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
			</div>
                        <div class="tab-pane fade active" id="userDistributorTab">
                            <div class="span3">
                                <div class="span9">
                                    <div class="jqGrid">
                                        <div class="ui-jqgrid ui-widget ui-corner-all clear-margin span12" dir="ltr" style="">
                                            <div class="wrap">
                                                <table id="usersxDistributor"></table>
                                                <div id="usersxDistributorPager"></div>
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
      var tab = jQuery('#distributorAdminTab li:eq(0) a').attr("href");
      jQuery(tab).css("opacity", 1);
      var subtab = jQuery('#distributorFamilyAdminTab li:eq(0) a').attr("href");
      jQuery(subtab).css("opacity", 1);
   });
</script>
