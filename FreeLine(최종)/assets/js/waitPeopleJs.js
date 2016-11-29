$(function(){
   $("input#plus_btn").click(function(){
      event.preventDefault();
       var customerName = $('#customerName').val();
       var customerTel = $('#customerTel').val();
       var customerNumberOfPeople = $('#customerNumberOfPeople').val();
       if(customerName=="" || customerNumberOfPeople==""){
           $('#plusMsg').html("이름과 인원수는 필수항목입니다.");
       }else{
           $.ajax({
               type : "POST",
               url : "addWaitingPeopleRegister.php",
               data : {'customerName' : customerName, 'customerTel': customerTel, 'customerNumberOfPeople': customerNumberOfPeople},
               success: function(msg){
                   if(msg=='success'){
                        location.reload();
                       $('#plus-modal').modal('hide');
                   }else{
                       $("#plusMsg").html(msg);
                   }
               },error:function(){
                   $("#plusMsg").html('error입니다!!');
               }
                   
           });
       }
       return false; 
   }); 
    
    $("a#nextBtn").click(function(){
       event.preventDefault();
        $.ajax({
           type:"POST",
            url: "waitNum.php",
            success: function(msg){
                location.reload();
            },error:function(){
                alert("에러");
            }
        });
    });
});