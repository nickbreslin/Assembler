if (typeof console === 'undefined')
{
    var console = {
        log: function() {},
        error: function() {}
    };
    
}
else if (typeof console.log === 'undefined'
    || typeof console.error === 'undefined')
{
    console.log   = function() {};
    console.error = function() {};
}

if (typeof Evo === 'undefined')
{
    var Evo = {};
}

(function()
{
    this.log = function ()
    {
        if (window.console && window.console.log)
        {
            console.log(Array.prototype.slice.call(arguments));
        }
	}    
}).apply(Evo);