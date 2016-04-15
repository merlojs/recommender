var general = {
    
	init: function(){
		this.checkAll();
		this.numeric();
		this.alertExtend();
		this.mensajes();
	},
	numeric: function(){
		// Inicialliza componente Numeric
		$('.numeric').numeric({
			negative : false
		});
		$('.positive-integer').numeric({
			decimal: false, 
			negative: false
		});

	},
	checkAll: function(){

		/* Funci�n para seleccionar todas las filas juntas */
		$("#check_all").click(function() { 
			$(".sch").each(function(){
				if($("#check_all:checked").val()=="on"){
					$(".sch").attr("checked","checked");
					$(".sch").parent().parent().addClass("rowHover");
				} else {
					$(".sch").removeAttr("checked");
					$(".sch").parent().parent().removeClass("rowHover");
				}
			});
		})
	
		/* Funci�n para seleccionar una fila en particular */
		$(".sch").click(function() { 
			if($(this).parent().parent().attr("class")=="rowHover"){
				$(this).parent().parent().removeClass("rowHover");
			} else {
				$(this).parent().parent().addClass("rowHover");
			}
		})

	},
	setCursor : function (busy) {
		if (busy) {
			$('*').css({
				'cursor':'wait'
			});
		} else {
			$('*').css({
				'cursor':'auto'
			});
		}
	},
	alertExtend: function(){
		$.extend({
			alert: function (message, title) {
				$("<div></div>").dialog( {
					buttons: {
						"Ok": function () {
							$(this).dialog("close");
						}
					},
					close: function (event, ui) {
						$(this).remove();
					},
					resizable: false,
					title: title,
					modal: true
				}).text(message);
			}
		});
	},
    
	mensajes: function(){
        
		if( $('#mensaje').val()){
			$.alert($('#mensaje').val());
		}
	}
   
}