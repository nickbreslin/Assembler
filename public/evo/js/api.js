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
    this.call = function(type, action, params, data, callback)
    {    
        var url = "/api/index.php";
        //url     = url + '?signed_request=' + window.User.signed_request;
        
        url = url + "?action="+action+params;
        
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
                
                if(response.responseCode == 1)
                {
                    Evo.log("Response Success");
                    
                    if(callback)
                    {
                        callback(response.results)
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