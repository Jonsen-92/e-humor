function clearBranch(vValue,vBranch){
	if (vValue == '-1') {
		$('#'+vBranch).attr('disabled',false);
	}
	else {
		$('#'+vBranch).attr('disabled',true);
		//$('#'+vBranch).attr('value','-1');
	}
}

function clearRegion(vValue,vRegion){
	if (vValue == '-1') {
		$('#'+vRegion).attr('disabled',false);
	}
	else {
		$('#'+vRegion).attr('disabled',true);
		//$('#'+vRegion).attr('value','-1');
	}
}

function getUserType(server, base,val){
    var base='/'+base || '';
    var val2=escape($('#UserAccountCompanyId').val());
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';
	$('#RegionRegion').load(url+'getRegionsByCompany/'+val2);
	$('#BranchBranch').load(url+'getBranchesByCompany/'+val2);
	if(val==1){
		$('#RegionRegion').attr('disabled', true);
		$('#RegionRegion').val(-1);
		$('#BranchBranch').attr('disabled', true);
		$('#BranchBranch').val(-1);
		$('#UserAccountKiosId').attr('disabled', true);
		$('#UserAccountKiosId').val(-1);
		$('#SalesmanSalesman').attr('disabled', false);
        $('#SalesmanSalesman').load(url+'getSalesmenByCompany/'+val2);

	}
	else if (val==2){
		$('#RegionRegion').attr('disabled', false);
		$('#BranchBranch').attr('disabled', true);
		$('#SalesmanSalesman').attr('disabled', true);
		$('#BranchBranch').val(-1);
	}
	else if (val==3){
		$('#RegionRegion').attr('disabled', true);
		$('#RegionRegion').val(-1);
		$('#BranchBranch').attr('disabled', false);
		$('#SalesmanSalesman').attr('disabled', true);
	}
}

function changeCompany(server, base, val){
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';
	$('#RegionRegion').load(url+'getRegionsByCompany/'+val);
	$('#BranchBranch').load(url+'getBranchesByCompany/'+val);
	$('#SalesmanSalesman').load(url+'getSalesmenByCompany/'+val);
	getUserType(server, base,val);
}

function getSalesman(server, base, val){
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';
	$('#SalesmanSalesman').attr('disabled', false);
	$('#SalesmanSalesman').load(url+'getSalesmenByBranch/'+val);
}

function getSalesmanKios(server, base, val){
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';
	$('#SalesmanSalesman').attr('disabled', false);
	$('#SalesmanSalesman').load(url+'getSalesmenKiosByBranch/'+val);
}

function getKios(server, base, val){
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';
	$('#UserAccountKiosId').attr('disabled', false);
	$('#UserAccountKiosId').load(url+'getKiosByBranch/'+val);
}

function getSalesmanRegion(server, base, val){
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';
	$('#SalesmanSalesman').attr('disabled', false);
	$('#SalesmanSalesman').load(url+'getSalesmenByRegion/'+val);
}

function disabledAwal(){
	var typeAkses=$("input[name='data[UserAccount][type_akses]']:checked").val();
	if (typeAkses==1) {
		$('#BranchBranch').attr('disabled', true);
		$('#UserAccountKiosId').attr('disabled', true);
	}else{
		$('#BranchBranch').attr('disabled', false);
		$('#UserAccountKiosId').attr('disabled', true);
	}
}

function getDivisi(server, base, val)
{
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';
	$('#UserAccountIdDivisi').load(url+'getIdDivisi/'+val);
	$('#UserAccountUnitBisnis').load(url+'getDivisiUnit/'+val);
	$('#UserAccountNamaDivisi').load(url+'getNamaDivisi/'+val);
}
