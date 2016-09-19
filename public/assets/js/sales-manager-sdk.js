function SalesManagerSdk() {
    var SalesManager = this;

    SalesManager.users = {
        /**
         *
         * @param {Number} id
         * @param {Function} callback
         * @param {Object} filters
         * @returns {Promise}
         */
        get: function(id, callback, filters){
            var callback = (typeof callback == 'function') ? callback : function(){};
            var filters = filters || {};
            return SalesManager.ajax({
                endpoint: '/users/' + id,
                data: {
                    filters: filters
                }
            },callback);
        },
        search: {
            /**
             * @param {String} q
             * @param {Function} callback
             * @param {Object} filters
             * @returns {Promise}
             */
            all: function(q, callback, filters){
                var callback = (typeof callback == 'function') ? callback : function(){};
                var filters = filters || {};
                filters.q = q;
                filters.enable = 'true';
                return SalesManager.ajax({
                    endpoint: '/users',
                    data: {
                        filters: filters
                    }
                },callback);
            },
            /**
             * @param {String} q
             * @param {Function} callback
             * @param {Object} filters
             * @returns {Promise}
             */
            collector: function(q, callback, filters){
                var callback = (typeof callback == 'function') ? callback : function(){};
                var filters = filters || {};
                filters.role = 'proveedor';
                //filters.enable = 'true';
                filters.state = bussiness.MEMBER_AFFILIATE;
                filters.q = q;
                return SalesManager.ajax({
                    endpoint: '/users',
                    data: {
                        filters: filters
                    }
                },callback);
            },
            /**
             * @param {String} q
             * @param {Function} callback
             * @param {Object} filters
             * @returns {Promise}
             */
            member: function(q, callback, filters){
                var callback = (typeof callback == 'function') ? callback : function(){};
                var filters = filters || {};
                filters.role = 'socio';
                filters.enable = 'true';
                filters.q = q;
                return SalesManager.ajax({
                    endpoint: '/users',
                    data: {
                        filters: filters
                    }
                },callback);
            }
        }
    };

    SalesManager.periods = {
        search: {
            /**
             * @param {String} q
             * @param {Function} callback
             * @param {Object} filters
             * @returns {Promise}
             */
            all: function(q, callback, filters){
                var callback = (typeof callback == 'function') ? callback : function(){};
                var filters = filters || {};
                filters.q = q;
                filters.enable = 'true';
                return SalesManager.ajax({
                    endpoint: '/periods',
                    data: {
                        filters: filters
                    }
                },callback);
            }
        }
    };

    /**
     *
     * @param {Object} options
     * @param {Function} callback
     * @returns {Promise}
     */
    SalesManager.ajax = function(options, callback){
        var callback = (typeof callback == 'function') ? callback : function(){};
        return new Promise(function(resolver, reject){
            if(typeof options.endpoint == 'undefined'){
                callback(new Error('Endpoint no definido'));
                reject('Endpoint no definido');
                return;
            }
            var request = $.ajax({
                method: options.method || 'GET',
                url: "http://sm.lo/" + options.endpoint,
                data: options.data || [],
                dataType: 'json'
            });
            request.fail(function(resp) {
                callback(resp);
                reject(resp.status);
            });
            request.done(function(response) {
                callback(null, response);
                resolver(response);
            });
        });
    };

    return SalesManager;
}