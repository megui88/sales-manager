bussiness = new Bussiness();
$(document).ready(function(){
    $('input:not([type=hidden])').each(function(i){
        $(this).addClass('inputs');
    });

    $('.inputs').keydown(function (e) {
        switch (e.which){
            case 13:
                var input = this;
                bussiness.inputs.inputMember(this)
                    .then(function (data) {
                        if(bussiness.inputs.setMember(input, {
                            'full_name': data.last_name + ', ' + data.name,
                            'code': data.code,
                            'id': data.id
                        })) {
                            return bussiness.inputs.nextInput(input);
                        }
                    })
                    .catch(function(msg){
                        if($(input).prop('required')){
                            bussiness.alerts.inputIsRequired(input);
                            console.log(msg);
                        } else {
                            bussiness.inputs.nextInput(input);
                        }
                    });
                break;
            case 115: // F4
                bussiness.users.search(this,function (err, data) {
                    if(err){
                        console.error(err);
                        return;
                    }
                    console.log(data);
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
