function formatNumber(num) {
    // fungsi untuk merubah format bilangn menjadi ribuan
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
}

function getNum(num = null){
    // fungsi untuk merubah bilangan tanpa penanda ribuan (.)
    while (num != (num = num.replace(".", '')));
    return Number(num);
}
function hapus_huruf(id){
    angka = $('#'+id).val();
    angka = angka.replace(/[^0-9]/gi, '');
    document.getElementById(id).value = angka;
}

function format_ribuan(id){
    hapus_huruf(id);
    ongko = getNum($('#'+id).val());
    if(ongko > 999){
        ongko = formatNumber(ongko);
    }
    else if(ongko == 0){
        ongko = '';
    }
    $('#'+id).val(ongko);
}