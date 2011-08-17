$(document).ready(function(){
   $("#check").click(function(){
       var code = $("#code").val();
       $.ajax({
           type: "POST",
           url: "/premium_codes/check",
           data: "code=" + code,
           dataType: "json",
           success: function(data){
               flash_message(data.msg, data.status);
           }
       });
       return false;
   }); 
   $("#inval").click(function(){
       var code = $("#code").val();
       $.ajax({
           type: "POST",
           url: "/premium_codes/invalidate",
           data: "code=" + code,
           dataType: "json",
           success: function(data){
               flash_message(data.msg, data.status);
           }
       });
       return false;
   });
   $("#renew").click(function(){
       var code = $("#code").val();
       $.ajax({
           type: "POST",
           url: "/premium_codes/renew",
           data: "code=" + code,
           dataType: "json",
           success: function(data){
               flash_message(data.msg, data.status);
           }
       });
       return false;
   });
});

function flash_message(msg, status){
   $('#notifications').jnotifyAddMessage({
       text: msg,
       permanent: true,
       type: status	
   });
}

