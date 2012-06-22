// all dialog buttons should close their parent dialog
$(".ui-dialog button").live("click", function() {		
  $("[data-role='dialog']").dialog("close");		
});