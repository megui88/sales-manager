bussiness = new Bussiness();
$(document).ready(function(){
    $('input:not([type=hidden])').each(function(i){
        $(this).addClass('inputs');
    });

    if(typeof inputFocus != 'undefined'){
        $('#'+inputFocus).focus();
    }

    $('.inputs').keydown(function (e) {
        var input = this;
        switch (e.which){
            case 13:
                bussiness.inputs.inputMember(this)
                    .then(function (data) {
                        bussiness.inputs.memberOk(input, data);

                        if (bussiness.inputs.setMember(input, {
                                'full_name': data.last_name + ', ' + data.name,
                                'code': data.code,
                                'id': data.id,
                                'fantasy_name': data.fantasy_name
                            })) {
                            return bussiness.inputs.nextInput(input);
                        }
                    })
                    .catch(function(msg){if ($(input).prop('required')){
                        bussiness.alerts.inputIsRequired(input);
                        console.log(msg);
                    } else {
                        bussiness.inputs.nextInput(input);
                    }});
                break;
            case 115: // F4
                bussiness.users.search(this, function (err, data) {
                    if (err) {
                        console.error(err);
                        return;
                    }
                    try {
                        bussiness.inputs.memberOk(input, data);
                    }catch (e){
                        if ($(input).prop('required')){
                            bussiness.alerts.inputIsRequired(input);
                            console.info(e);
                        } else {
                            bussiness.inputs.nextInput(input);
                        }
                    }
                });
                return false;
                break;
        }
    });

});

$(window).keydown(function(e) {
    var code = e.keyCode || e.which;
    switch (code){
        case 13:
            event.preventDefault();
            return false;
            break;
        default:
            //  console.info('keydown: ' + code);
            break;
    }
});
