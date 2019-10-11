BX.ready(function() {
    let obj = new CustomTelephonySettings();
});

class CustomTelephonySettings {

    constructor(){
        let myUrl = window.location.href,
            elem = $('select[name="WORKTIME_DAYOFF[]"]');

        if((/\/telephony\/edit.php/ig).test(window.location.href) == true){
            if(elem.length > 0){
                $(elem).closest('tr').css('display','none');
            }
        }
    }

}