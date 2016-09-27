function Bussiness() {

    var SalesManager = new SalesManagerSdk();
    var bussiness = this;

    bussiness.CREDIT_MAX = 40000;
    bussiness.AGENT_ROLE = 9;
    bussiness.VENDOR_ROLE = 'proveedor';
    bussiness.MEMBER_ROLE = 'socio';
    bussiness.EMPLOYEE_ROLE = 'empleado';
    bussiness.EMPLOYEE_ADMIN_ROLE = 'administrador';
    bussiness.PERIOD_FORMAT = 'Ym';
    bussiness.PERIOD_EXP_REG = '/^(\d{4})(\d{2})+$/i';
    bussiness.PERIOD_EXP_REG_REMP = '${1}-$2-01';
    bussiness.DUE_DATE_FORMAT = 'Y-m-d';
    bussiness.DECIMALS_PLACES = 2;
    bussiness.PRINT_DECIMALS = 2;
    bussiness.PRINT_DEC_POINT = '.';
    bussiness.PRINT_THOUSANDS_SEP = ',';
    bussiness.MEMBER_AFFILIATE = 'afiliado';
    bussiness.MEMBER_DISENROLLED = 'desafiliado';

    bussiness.alerts = {
        create: function (message, type) {
            var id = bussiness.uuid();
            var $alert = $('<div>').attr('id',id).addClass('alert');
            if (type.length) {
                $alert.addClass(type);
            }
            $alert.text(message);
            $alert.append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            $('#alerts div').remove();
            $('#alerts').append($alert);
        },
        inputIsRequired: function (input) {
            var $input = $(input);
            var msg =  'El campo es obligatorio';
            if($input.attr('name') == 'payer'){
                msg = 'El comprador es incorrecto, no tiene credito o no esta afiliado';
            }else if($input.attr('name') == 'collector') {
                msg = 'El vendedor es incorrecto, faltan datos o no esta afiliado';
            }
            bussiness.alerts.create(msg, 'alert-warning');
            $input.addClass('input-error');
            $input.focus();
        }
    };

    bussiness.canSell = function(provider){
        return (provider.state == bussiness.MEMBER_AFFILIATE && provider.role == bussiness.VENDOR_ROLE && provider.administrative_expenses > 0);
    };

    bussiness.canBuy = function(member){
        return (member.state == bussiness.MEMBER_AFFILIATE && member.credit_max > 0);
    };

    bussiness.inputs = {
        memberOk: function(input, data){

            if($(input).data('sale') && data.state == bussiness.MEMBER_DISENROLLED){
                throw 'User: ' + data.id + ' -> ' + data.state;
            }
            if('payer' == $(input).attr('name') && ! bussiness.canBuy(data)) {
                throw 'User may not buy';
            }else if('collector' == $(input).attr('name') && !bussiness.canSell(data)){
                throw 'User may not sell';
            }
        },
        /**
         *
         * @param input
         * @returns {boolean}
         */
        hasGoal: function (input) {
            var $input = $(input);
            var inputName = $input.attr('name');
            return $('input[name="' + inputName + '_id"]').length;
        },
        /**
         *
         * @param input
         * @returns {*|jQuery|HTMLElement}
         */
        getGoal: function (input) {
            var $input = $(input);
            var inputName = $input.attr('name');
            return $('input[name="' + inputName + '_id"]');
        },
        setMember: function(origin, data){
            var data = {
                full_name: data.fantasy_name || data.full_name || '',
                code: data.code || '',
                id: data.id || ''
            };
            var $origin = $(origin);
            if (! bussiness.inputs.hasGoal(origin)){
                return false;
            }
            var $destination = bussiness.inputs.getGoal(origin);

            $destination.val(data.id);
            $origin.val(data.code);
            $origin.attr('title',data.full_name);
            $('#' + $origin.attr('name') + '-title').text(data.full_name);

            $origin.removeClass('input-error');
            $.removeData($origin);
            $.removeData($destination);
            delete $input;
            delete $destination;
            return true;
        },
        /**
         *
         * @param input
         * @returns {Promise}
         */
        inputMember: function (input) {
            return new Promise(function(resolver, reject){
                var $input = $(input);
                var id = $input.val();
                switch ($input.attr('name')) {
                    case 'payer':
                    case 'collector':
                    case 'member':
                        if
                        (!$.isNumeric(id))
                        {
                            reject(new Error('Debe ser numero. Valor: ' + id + ' typeof: ' + typeof id));
                            return;
                        }
                        SalesManager.users.get(id).then(function (user) {
                            if (!user.enable && $.hasData(input) && $input.data('member') == true) {
                                reject(new Error('Miembro deshabilitado'));
                                return;
                            }
                            resolver(user);
                        }).catch(function (msg) {
                            reject(msg);
                        });
                        break;
                    default:
                        reject();
                        break;
                }
            });
        },
        nextInput: function(input){
            var $input = $('.inputs');
            var index = $input.index(input) + 1;
            var $next = $input.eq(index);
            if ($next.length){
                $next.focus();
            } else{
                $(input).closest('form').submit();
            }
        }
    };

    bussiness.users = {
        search: function(input, _callback){
            var callback = (typeof _callback == 'function') ? _callback : function(){};
            var $input = $(input);
            var inputName = $input.attr('name');
            var find = function(){};
            var $modal = $('#searchUsers');
            var $inputQuery = $("#searchUsersTableQuery");
            var $tableResult = $("#searchUsersTableResults");
            var timeout;
            $tableResult.removeClass('inputs');

            if (! bussiness.inputs.hasGoal(input)){
                callback(new Error('Es necesario el input: ' + inputName + '_id para usar el buscador'));
            }
            var $goal = bussiness.inputs.getGoal(input);
            switch (inputName){
                case 'collector':
                    find = SalesManager.users.search.collector;
                    break;
                case 'payer':
                default:
                    find = SalesManager.users.search.all;
                    break;
            }

            $modal.modal('show');
            setTimeout(function(){$inputQuery.focus()},500);

            $inputQuery.keypress(function(){
                if (timeout){ clearTimeout(timeout);}
                timeout = setTimeout(function (){ find($inputQuery.val(),function (err, response) {
                    $('#' + $tableResult.attr('id') + ' tr').remove();
                    if (err){
                        console.log(err);
                        return;
                    }
                    response.data.forEach(function (data) {
                        var $tr = $('<tr>');
                        var $td = $('<td>');
                        $td.text(data.code);
                        $tr.append($td);
                        var $td = $('<td>');
                        $td.text(data.name);
                        $tr.append($td);
                        var $td = $('<td>');
                        $td.text(data.last_name);
                        $tr.append($td);
                        var full_name = data.last_name + ', ' + data.name;
                        $tr.data('full_name', full_name);
                        $tr.data('code', data.code);
                        $tr.data('id', data.id);
                        $tr.click(function(){
                            done({
                                'full_name': full_name,
                                'code': data.code,
                                'id': data.id,
                                'fantasy_name': data.fantasy_name
                            });
                        });

                        $tableResult.append($tr);
                    });

                    function done(result){
                        $inputQuery.val('');
                        $('#' + $tableResult.attr('id') + ' tr').remove();
                        var setter = new bussiness.inputs.setMember($input, result);
                        $modal.modal('hide');
                        bussiness.inputs.nextInput($input);
                        $.removeData($input);
                        $.removeData($goal);
                        $.removeData(setter);
                        delete $input;
                        delete $goal;
                        delete setter;
                        callback(null, true);
                    }
                }); },900);

            });
        }
    };

    bussiness.uuid = function(){
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
    };
    return bussiness;
}