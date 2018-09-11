$(document).ready(function() {
	var uni_active=false;
	var facu_active=false;
	var profe_active=false;
	var uni_prev;
	var facu_prev;
	var profe_prev;
	var cuales;	
	var c_put;
	var aux;
	$("div span").live('click', function() {
		//var text = $(this).parent().get(0).css("class");				
		cuales = $(this).parent().attr("class");	
		//Universidades
		if(cuales == "uni") {
			if(!uni_active) {
				uni_active=true;
				uni_prev=$(this).parent().html();
				$(this).append("<img src='images/loader.gif' />");
				$.get('steps/universidad.php?uid='+u_sel, function(data) {
					$('.uni').html(data).hide().fadeIn("slow");
				});
			} else {
				uni_active=false;
				$('.uni').html(uni_prev).hide().fadeIn("slow");
			}
		}
		//Facultades
		if(cuales == "facu") {
			if(!facu_active) {
				facu_active=true;
				facu_prev=$(this).parent().html();
				$(this).append("<img src='images/loader.gif' />");
				$.get('steps/facultad.php?fid='+f_sel+'&uid='+u_sel, function(data) {
					$('.facu').html(data).hide().fadeIn("slow");
				});
			} else {
				facu_active=false;
				$('.facu').html(facu_prev).hide().fadeIn("slow");
			}
		}
		//Cursos
		if(cuales == "course") {
			c_put=$("input#code_input").val().replace(" ","").toUpperCase();
			if(c_put!="CODIGODEL CURSO" && c_put.length>=5) {
				aux=true;
				for(var i=0; i<$(".course ul li").length; i++) {
					if($(".course ul li").eq(i).attr("id").indexOf(c_put)!=-1) {aux=false;}
				}
				if(aux) {
					$(".course ul").append("<li id='"+c_put+"'>"+c_put+" <span>&#215; remover</span> <span class='profe'>&#8962; profesor</span> <span class='fixed'>&#8595; fijar</span> <span class='manual'>&#164; secci&oacute;n</span></li>");
					aux=$(".course ul li").length-1;
					$(".course ul li").eq(aux).hide().fadeIn("slow");  
					$("input#code_input").val("").focus();
				} else {
					alert("El curso ya se encuentra en la lista.");
					$("input#code_input").val("").hide().fadeIn("slow").focus();
				}
			} else {
				$("input#code_input").hide().fadeIn("slow");
			}
		}
	});
	$(".course ul li span").live('click', function() {
		if($(this).attr("class")=="profe") {
			if(!profe_active) {
				profe_active=true;
				profe_prev=$(this).parent().html();
				$(this).append("<img src='images/loader-li.gif' />");
				aux=$(this).parent().attr("id").substr(0,5);
				$.get('steps/profe.php?code='+aux+'&p_sel='+$("li[id*="+aux+"] p").attr("id"), function(data) {
					$("li[id*="+aux+"]").html(data).hide().fadeIn("slow");										
				});
			} else {
				$("li[id*="+aux+"]").hide().fadeIn("slow");
			}
		} else if($(this).attr("class")=="sel") {
			if(profe_active) {
				profe_active=false;
				$(this).parent().html(profe_prev).hide().fadeIn("slow");
			}
		} else if($(this).attr("class")=="manual") {
			aux=prompt("Ingrese la secci\xf3n (A, B, C, ...)");
			$(this).parent().html(quees+" <p id='"+aux+"' esto='sec'>Secci&oacute;n "+aux+"</p>  <span>&#215; remover</span> <span class='fixed' did='true'>&#8593; liberar</span>").hide().fadeIn("slow");
		} else if($(this).attr("class")=="fixed") {
			if(is_done) {
				quees=$(this).parent().attr("id");
				if($(this).attr("did")!="true") {					
					aux=$("td[curso="+quees+"]").attr("seccion");
					if($("td[curso="+quees+"]").length) {
						$(this).parent().html(quees+" <p id='"+aux+"' esto='sec'>Secci&oacute;n "+aux+"</p>  <span>&#215; remover</span> <span class='fixed' did='true'>&#8593; liberar</span>").hide().fadeIn("slow");
					} else {
						alert("Ha ocurrido un error. Vuelva a generar el Horario.");
						aux=prompt("Puede ingresar manualmente la secci\xf3n (A, B, C, ...)");
						$(this).parent().html(quees+" <p id='"+aux+"' esto='sec'>Secci&oacute;n "+aux+"</p>  <span>&#215; remover</span> <span class='fixed' did='true'>&#8593; liberar</span>").hide().fadeIn("slow");
					}
				} else {
					$(this).parent().html(quees+" <span>&#215; remover</span> <span class='profe'>&#8962; profesor</span> <span class='fixed'>&#8595; fijar</span> <span class='manual'>&#164; secci&oacute;n</span>").hide().fadeIn("slow");
				}
			} else {
				alert("Primero genere un horario.");
			}
		} else {
			aux=confirm("\xbfConfirma remover este curso?")
			if (aux) {
				$(this).parent().eq(0).remove();
			}
		}
	});
	//Especial para elegir Universidad
	$(".uni select#uni").live('change', function() {
		uni_active=false;
		u_sel=$(this).val();		
		$(".uni").html($("select#uni option:selected").html()+" <span>cambiar</span>").hide().fadeIn("slow");
	});
	//Fin Codigo Especial Universidad
	//Especial para elegir Facultad
	$(".facu select#facu").live('change', function() {
		facu_active=false;
		f_sel=$(this).val();	
		$(".facu").html($("select#facu option:selected").html()+" <span>cambiar</span>").hide().fadeIn("slow");		
	});
	//Fin Codigo Especial Facultad
	//Especial para elegir Profesor
	$(".course select#profe").live('change', function() {
		profe_active=false;		
		if($("select#profe option:selected").html()!="- Cualquier Profesor -") {
			$("li[id*="+aux+"]").html(aux+"<p id='"+$(this).val()+"' esto='profe'>"+$("select#profe option:selected").html()+"</p> <span>&#215; remover</span> <span class='profe'>&#8962; profesor</span> <span class='fixed'>&#8595; fijar</span> <span class='manual'>&#164; secci&oacute;n</span>").hide().fadeIn("slow");
		} else {
			$("li[id*="+aux+"]").html(aux+ " <span>&#215; remover</span> <span class='profe'>&#8962; profesor</span> <span class='fixed'>&#8595; fijar</span> <span class='manual'>&#164; secci&oacute;n</span>").hide().fadeIn("slow");
		}
	});
	//Fin Codigo Especial Profesor
	//Especial para agregar Cursos
	$("input#code_input").live({
		click: function() {
			c_put=$(this).val();
			if(c_put=="C\xf3digo del Curso") {
				$(this).val("");
			}
		},
		blur: function() {
			c_put=$(this).val();
			if(c_put=="") {
				$(this).val('C\xf3digo del Curso');
			}			
		},
		keypress: function(e) {
			if(e.which == 13) {
				c_put=$("input#code_input").val().replace(" ","").toUpperCase();
				if(c_put.length<5) {
					$("input#code_input").hide().fadeIn("slow");
					return false;
				}
				if(c_put!="") {
					aux=true;
					for(var i=0; i<$(".course ul li").length; i++) {
						if($(".course ul li").eq(i).attr("id").indexOf(c_put)!=-1) {aux=false;}
					}
					if(aux) {
						$(".course ul").append("<li id='"+c_put+"'>"+c_put+" <span>&#215; remover</span> <span class='profe'>&#8962; profesor</span> <span class='fixed'>&#8595; fijar</span> <span class='manual'>&#164; secci&oacute;n</span></li>");
						aux=$(".course ul li").length-1;
						$(".course ul li").eq(aux).hide().fadeIn("slow");  
						$("input#code_input").val("").focus();
					} else {
						alert("El curso ya se encuentra en la lista.");
						$("input#code_input").val("").hide().fadeIn("slow").focus();
					}
				} else {
					$("input#code_input").hide().fadeIn("slow");
				}
			}
			if(e.which == 32) {
				$("input#code_input").hide().fadeIn("slow");
			}
		}
	});	
	//Fin Codigo Especial Cursos
	//Habilitar Cruces
	$("input#horas_cruce").change(function(){
		aux=$(this).is(':checked');
		if(aux) {
			$("input#horas_input").val("0");
		} else {
			$("input#horas_input").val("")
		}
	});
	//Fin Cruces
	/* Codigo para mandar a generar el Horario */
	$("button#makeTT").click(function() {
		if($(".course ul li").length==0) {			
			alert("Primero debe agregar los cursos.");
			$("input#code_input").hide().fadeIn("slow");
			$("input#code_input").val("").focus();
		} else {
			for(var i=0; i<$(".course ul li").length; i++) {			
				c_sel[i]=$(".course ul li").eq(i).attr("id");
				p_sel[i]=$(".course ul li[id*="+c_sel[i]+"] p[esto=profe]").attr("id");
				s_sel[i]=$(".course ul li[id*="+c_sel[i]+"] p[esto=sec]").attr("id");
			}
			for(i=$(".course ul li").length; i<c_sel.length; i++) {			
				delete c_sel[i];
				delete p_sel[i];
				delete s_sel[i];
			}
			if($("input#horas_cruce").is(':checked')) {
				aux='&horas='+$("input#horas_input").val();
			} else {aux='';}
			$('#TimeTable').html("<img src='images/loader-tt.gif' /><br />Generando horario...");			
			$.get('steps/timetable.php?u_sel='+u_sel+'&f_sel='+f_sel+'&c_sel='+c_sel.toString().replace(/,/g,"-")+'&p_sel='+p_sel.toString().replace(/,/g,"-")+'&s_sel='+s_sel.toString().replace(/,/g,"-")+aux, function(data) {
				$('#TimeTable').html(data).hide().fadeIn("slow");
				start=true;
				$('td[title]').tooltip({
					tipClass: 'tooltip_td',
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
			});
			is_done=true;
		}
	});
	/* Fin Codigo makeTT */
});