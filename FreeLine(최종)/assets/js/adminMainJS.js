$(function(){
    $("a#fullActive").click(function(){
       event.preventDefault();
        $.ajax({
           type:"POST",
            url: "fullActive.php",
            success: function(msg){
                location.reload();
            },error:function(){
                alert("에러");
            }
        });
    });
    $("a#fullUnActive").click(function(){
       event.preventDefault();
        $.ajax({
           type:"POST",
            url: "fullUnActive.php",
            success: function(msg){
                location.reload();
            },error:function(){
                alert("에러");
            }
        });
    });
});