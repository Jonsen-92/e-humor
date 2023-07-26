function changeNamaVendor(vServer, vBase) {
    vServer = $('#TestingServer').val();
    vBase = $('#TestingBase').val();
    var kodemaster = $('#VendorKodeVendor').val();
    var vBase = '/' + vBase || '';

    $.ajax({
        url: "/ahmad_gozali/vendorcommps/changeNamaVendor/" + kodemaster,
        success: function (html) {
            $("#VendorNamaVendor").html(html);
        }
    });
}