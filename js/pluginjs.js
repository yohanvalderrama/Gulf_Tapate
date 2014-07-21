function responsive_jqgrid(jqgrid) {
    jqgrid.find(".ui-jqgrid").addClass("clear-margin span12").css("width", "");
    jqgrid.find(".ui-jqgrid-view").addClass("clear-margin span12").css("width", "");
    jqgrid.find(".ui-jqgrid-view > div").eq(1).addClass("clear-margin span12").css("width", "").css("min-height", "0");
    jqgrid.find(".ui-jqgrid-view > div").eq(2).addClass("clear-margin span12").css("width", "").css("min-height", "0");
    jqgrid.find(".ui-jqgrid-sdiv").addClass("clear-margin span12").css("width", "");
    jqgrid.find(".ui-jqgrid-pager").addClass("clear-margin span12").css("width", "");
}

function setTextAreaForm(form, id){
    
    $tr = form.find("#"+id), 
    $label = $tr.children("td.CaptionTD"),
    $data = $tr.children("td.DataTD");
    $data.attr("colspan", "3");
    $data.children("textarea").css("width", "100%");
}