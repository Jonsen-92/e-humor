function addIsParam(p){
	$('#IsParamMdKeterangan').val($('#deskripsi'+p).html());
	$('#IsParamMdLevel').val($('#acc_level'+p).html()).attr('readonly',true);	
}
