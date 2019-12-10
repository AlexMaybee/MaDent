BX.ready(function() {
    let obj = new CustomTelephonySettings();
});

class CustomTelephonySettings {

    constructor(){
        let myUrl = window.location.href,
            elem,selects,inputs;

        if((/\/telephony\/edit.php/ig).test(window.location.href) == true){
            elem = $('select[name="WORKTIME_DAYOFF[]"]');
            selects = $('select');
            inputs = $('input');

            if(elem.length > 0){
                // $(elem).closest('tr').css('display','none');
                $.each(selects,function (index,val) {
                    if (['FORWARD_LINE','WORKTIME_DAYOFF_RULE'].indexOf($(val).prop('name')) > -1) return;
                    else $(val).css({'pointer-events':'none','opacity':'0.5'});
                });

                $.each(inputs,function (index,val) {
                    if ($(val).prop('name') == 'WORKTIME_DAYOFF_NUMBER') return;
                    else $(val).css({'pointer-events':'none','opacity':'0.5'});
                });
            }
        }
    }

}