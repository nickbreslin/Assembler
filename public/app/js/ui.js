if(typeof Evo === 'undefined')
{
    var Evo = {};
}

if(typeof Evo.UI === 'undefined')
{
    Evo.UI = {};
}

(function()
{   
    this.init = function() {
        $('.test-button').click(function(){
           Evo.log("test1"); 
           Evo.Api.test();
        });
    }
}).apply(Evo.UI);

$(function() {
    Evo.UI.init();
});