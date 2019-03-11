$(document).ready(function(){
    $('.inputText').click(function(){
        $(this).parent().parent().find(".line").css("width","100%");
    });
    $(".inputText").focusout(function(){
        $(this).parent().parent().find(".line").css("width","0%");
    });
    $('.status').each(function(){
        if ($(this).attr("data") == 'WAITING') {
            // $(this).css("background-color","#fcf8e3");
            $(this).find(".colorStatus").addClass("text-warning");
        }
        if ($(this).attr("data") == 'APPROVED') {
            $(this).css("background-color","#dff0d8");
            $(this).find(".colorStatus").addClass("text-success");
        }
        if ($(this).attr("data") == 'DENY') {
            $(this).css("background-color","#f2dede");
            $(this).find(".colorStatus").addClass("text-danger");
        }
    });
    $('.delete').click(function(){
        var idPost = $(this).attr("data");
        console.log(idPost);
        var infoPost = $(this).attr("data-info");
        console.log(infoPost);
        $.ajax({
            type:"POST",
            url: "/chatwork/request/delete",
            data : {
                "idPost" : idPost,
                "infoPost" : infoPost
            },
            success: function (data_success) {
                console.log(data_success);
                alert("You successfully deleted");
                location.reload();
            },
            error: function(data){
                alert(data['responseText']);
            }
        });
    });
    $('.accept').click(function(){
        var idPost = $(this).attr("data");
        var infoPost = $(this).attr("data-info");
        $.ajax({
            type:"POST",
            url: "/request/accept",
            data : {
                "id" : idPost,
                "info" : infoPost,
                "status" : 1
            },
            success: function (data_success) {
                alert("success");
                location.reload();
            },
            error: function(data){
                alert(data['responseText']);
            }
        });
    });
    $('.denny').click(function(){
        var idPost = $(this).attr("data");
        var infoPost = $(this).attr("data-info");
        $.ajax({
            type:"POST",
            url: "/request/accept",
            data : {
                "id" : idPost,
                "info" : infoPost,
                "status" : 3
            },
            success: function (data_success) {
                alert("success");
                location.reload();
            },
            error: function(data){
                alert(data['responseText']);
            }
        });
    });
});