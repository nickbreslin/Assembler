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
        
        var type       = 'get';
        var collection = 'assembla';
        var action     = 'query';
        var params     = '';
        
        Evo.Api.call(type, collection, action, params, [], callback);
    }
}).apply(Evo.Api);