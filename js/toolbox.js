$(document).ready(function() {
	$(".TT [title], div#extra a[title], .horas_cruce[title]").tooltip({
		effect: 'slide',
		events: {
			button: "mouseover, mouseout click"
		}
	}).dynamic({
		bottom: {
			direction: 'down',
			bounce: true
		}
	});
	$("td").live({
		mouseover: function() {
			nohora=$(this).attr("class");
			if(nohora!="hora") {
				quees=$(this).text();
				$("td:contains("+quees+")").css({'background': '#FFFFB0', 'color': '#000'});
			}
		},
		mouseout: function() {
			nohora=$(this).attr("class");
			if(nohora!="hora") {
				quees=$(this).text();
				$("td:contains("+quees+")").css({'background': '', 'color': ''});
			}
		}
	});	
	$("div.course li").live({
		mouseover: function() {
			quees=$(this).attr("id");			
			$("td[curso="+quees+"]").css({'background': '#FFFFB0', 'color': '#000'});			
		},
		mouseout: function() {
			quees=$(this).attr("id");
			$("td[curso="+quees+"]").css({'background': '', 'color': ''});			
		}
	});
	$("#exp_excel p").live('click', function(event) {
		$("#datos_a_enviar").val( $("<div>").append( $("#tt_done").eq(0).clone()).html());
		$("#exp_excel").submit();
	});
});