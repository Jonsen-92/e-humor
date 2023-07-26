$(document).ready(function(){
	$('form').submit(function () {
		checkregex('TestMasterNama');
        return true;
    });
});

function checkregex(id){
	text = $('#'+id).val();
	text = document.getElementById(id).value;
	text=text.replace(/[^a-z0-9.,]/gi,'');
	// $('#'+id).val(text);
	document.getElementById(id).value = text;
	// $('#'+id).readonly();
	// $('#'+id).attr('readonly', true);
	// document.getElementById(id).disabled = true;
}

// Sales -> Supervisor
function changeNama() {
	vServer = $('#VehicleServer').val();
	vBase = $('#VehicleBase').val();
	var kode=$('#VehicleNama').val();
	var vBase='/'+vBase || '';

	$.ajax({
		url: "/fakhri_ramdani/vehiclings/changeNama/"+kode,
		success: function(html){
			$("#VehicleAccesoris").html(html);
			console.log($('#VehicleNama').val());
		}
	});
}
