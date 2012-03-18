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
    this.recordStream = function() {
        Evo.log("Api > Test");
        
        var type       = 'get';
        var action     = '';
        var params     = '';
        
        Evo.Api.call(type, controller, action, params);
    }
}).apply(Evo.Api);