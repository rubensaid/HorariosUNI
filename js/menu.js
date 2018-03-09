$(document).ready(function() {
	//fix para menu principal
	/*var direc=location.href.split("?");
	direc="?"+direc[1];*/	
	var direc=location.href;	
	var bus;
	var yata=false;
	if(direc.indexOf('?')==-1) {$("#menu li:eq(3) a").addClass("actual");}
	$("#menu li a").each(function(i) {
		bus=this.href;		
		if(direc==bus) {
			$("#menu li:eq("+i+") a").addClass("actual");
			yata = true;
		}
	})
	if(!yata) {
		$("#menu li a").each(function(i) {
			bus = this.href;
			var a = false;
			if(direc.indexOf(bus)!=-1) {
				a = i;
			}
			if(a) {
				$("#menu li:eq("+a+") a").addClass("actual");
			}
		})
	}
	$("#cleanlist").click(function() {
		$(".course ul").html("");
	});
});