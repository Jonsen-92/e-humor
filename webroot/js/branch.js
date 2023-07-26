function validasiangka(e) {
    if (!/^[0-9a-zA-Z-]+$/.test(e.value)) {
	    e.value = e.value.substring(0, e.value.length - e.length);
    }
}