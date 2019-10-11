BX.ready(function(){

    getCall_missed();
    function getCall_missed() {
        var call_missed = document.querySelectorAll("#voximplant_statistic_detail_table tr td span");
        if(call_missed.length > 0) {
            for(i = 0; i < call_missed.length; i++) {
                if(call_missed[i].innerText == 'Пропущенный звонок') {
                    //console.log(call_missed[i]);
                    var td_par = call_missed[i].parentNode;
                    var tr_par = td_par.parentNode;
                    if(tr_par) {
                        tr_par.classList.add('active_missed');
                    }
                }
            }
        }
    }



    BX.addCustomEvent("onAjaxSuccess", function(data, config)
    {
        getCall_missed();
        //console.log(config);
    });

});
