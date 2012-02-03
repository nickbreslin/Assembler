$(function() {
    $('.main').tooltip({
        selector: "i[rel=tooltip]"
    })
    
    $('.sidebar a').click(function() {
        console.log("ee");
        $('#myModal').modal({'keyboard':false});
        });
    //$('.sidebar').delay(1000).fadeIn();
});