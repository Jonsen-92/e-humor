function changeBranch(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_aging_stoks/getWarehouse/'+val;
	$('#MotorMesinWarehouseCode').load(url);
}

function getCabangBpkbLeas() {
    var cabang=$('#InvoiceBranchCode').val();
    $('#InvoiceJualId'+'Button').attr('alt', $('#InvoiceJualId'+'Button').attr('rel')+'/'+cabang); 
}

function changeBranchSupplier(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_purchase_orders/getSupplier/'+val;
	$('#PurchaseOrderSuplNomor').load(url);
}

function changeBranchTrmMotor(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_aging_stoks/getWarehouse/'+val;
	$('#TerimaMotorWarehouseCode').load(url);
}

function changeBranchSales(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_penjualan_salesmen/getSalesman/'+val;
	$('#InvoiceSlsKode').load(url);
}

function changeBranchSaldo(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_aging_stoks/getWarehouse/'+val;
	$('#ReportSaldoMutasiMotorWarehouseCode').load(url);
}

function changeBranchKartuStok(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_aging_stoks/getWarehouse/'+val;
	$('#ReportKartuStokMotorWarehouseCode').load(url);
}

function changeBranchGlobalStok(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_aging_stoks/getWarehouse/'+val;
	$('#SaldoMotorWarehouseCode').load(url);
}

function changeBranchAccsStok(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_aging_stoks/getWarehouse/'+val;
	$('#ReportPerlengkapanMotorWarehouseCode').load(url);
}

function changeBranchCustomer(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_ar_cards/getCustomer/'+val;
	$('#ReportArCardCustId').load(url);
}

function changeBranchSupplier2(server,base,val){
    if (base!='') base='/'+base;
    var url=window.location.protocol+'//'+server+base+'/reports/report_ap_cards/getSupplier2/'+val;
	$('#ReportApCardSuplNomor').load(url);
}

function changeCustomer(server, base, val){
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';	
	$('#ReportArCardCustCode').load(url+'getCustomersByBranch/'+val);
//	$('#ReportArCardCustCode').attr('alt', $('#ReportArCardCustCode').attr('rel')+'/'+val);
}

function changeCustomer2(server, base, val){
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';	
	$('#SalesOrderKiosKode').attr('disabled', false);
	$('#SalesOrderKiosKode').load(url+'getKiosByBranch2/'+cabang+'/'+kios);
	$('#SalesOrderCustCode'+'Button').attr('alt', $('#SalesOrderCustCode'+'Button').attr('rel')+'/'+cabang+'/'+kios);
}

function changeMotor(server, base, val){
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';	
	$('#ReportStockCardMotorwarna').load(url+'getTypeMotor/'+val);
}

function changeCabang(server, base, val){
	var base='/'+base || '';
	var url=window.location.protocol+'//'+server+base;
	url+='/ajax/';	
	$('#ReportStokRangkaMesinWarehouseCode').load(url+'getaWarehouse/'+val);
}



