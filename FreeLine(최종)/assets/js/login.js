$(function() {
    
    $("input#login_btn").click(function(){
        event.preventDefault();
        var id= $("#id").val();
        var pwd = $("#pwd").val();
        if(id =="아이디" || pwd=="패스워드"){
            $("#loginMsg").html("아이디와 패스워드를 모두 입력해주세요.");
        }else{
            $.ajax({
                type: "POST",
                url: "login_ok.php",
                data: {'id': id, 'password': pwd},
                success: function(msg){
                    if(msg == 'success'){
                        $("#login-modal").modal('hide');
                    }else{
                        $("#loginMsg").html(msg);
                    }
                },
                error: function(){
                    $("#loginMsg").html("안돼!");
                }
            });
        }
        return false;
    });
});