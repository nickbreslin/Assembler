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
        
        Evo.appendToLog('warning', Array.prototype.slice.call(arguments));
        
	}
	
	this.appendToLog = function(type, message)
	{
	    trType = (type == "warning") ? "alert" : type;
	    
	    $('.debug-table tbody').append('<tr class='+trType+'><td><span class="label label-'+type+'">'+type+'</span></td><td>'+message+'</td></tr>');
    }
}).apply(Evo);