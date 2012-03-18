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
            $('.i-loading-modal').modal({
                keyboard : false
            });
            Evo.Api.test(function(data) {
                $('.i-results').html(data);
                $('.i-loading-modal').modal('hide');
            });
        });
        
        $('.main').tooltip({
            selector: "i[rel=tooltip]"
        })
    }
}).apply(Evo.UI);

$(function() {
    Evo.init();
    Evo.UI.init();
});