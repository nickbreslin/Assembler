if(typeof Evo === 'undefined')
{
    var Evo = {};
}

if(typeof Evo.Api === 'undefined')
{
    Evo.Api = {};
}

(function()
{   
    this.test = function(callback) {
        Evo.log("Api > Test");
        
        var type       = 'post';
        var collection = 'assembla';
        var action     = 'query';
        var params     = '';

        var status    = $('.i-form-status').val();
        var timeframe = $('.i-form-timeframe').val();
        var group     = $('.i-form-group').val();
         
         /*      
        var data          = [];
        data['status']    = status;
        data['timeframe'] = timeframe;
        data['group']     = group;
        */
        var data = {
        'status'    : status,
        'timeframe' : timeframe,
        'group'     : group
        };
        
        Evo.log(data);
        
        Evo.Api.call(type, collection, action, params, data, callback);
    }
}).apply(Evo.Api);