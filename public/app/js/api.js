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
    this.test = function() {
        Evo.log("Api > Test");
        
        var type       = 'get';
        var action     = '';
        var params     = '';
        
        Evo.Api.call(type, action, params);
    }
}).apply(Evo.Api);