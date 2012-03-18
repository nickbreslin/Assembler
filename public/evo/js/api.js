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
    this.call = function(type, collection, action, params, data, callback)
    {    
        var url = "/api/index.php";
        //url     = url + '?signed_request=' + window.User.signed_request;
        
        url = url + "?"+collection+"="+action+params;
        
        Evo.log("Api > Url: " + url);
        
        $.ajax(
        {
            type:     type,
            url:      url,
            dataType: 'json',
            data:     data,
            cache:    false,
            success: function(response)
            {
                Evo.log("Api > Call > ("+action+") Success", response);
                
                if(response.code == 1)
                {
                    Evo.log("Response Success");
                    
                    if(callback)
                    {
                        callback(response.data)
                    }
                }
                
                if(response.debug)
                {
                    if(response.debug.data)
                    {
                        for(var i in response.debug.data)
                        {
                            var entry = response.debug.data[i];
                            Evo.appendToLog(entry.type, entry.message);
                        }
                    }
                }
            },
            error: function(request, textStatus, error)
            {
                Evo.log("Api > Call > ("+action+") Error", error);
            }
        });
    }
}).apply(Evo.Api);