$(document).ready(function(){
   $("#check").click(function(){
       var code = $("#code").val();
       $.ajax({
           type: "POST",
           url: "/premium_codes/check",
           data: "code=" + code,
           dataType: "json",
           success: function(data){
               if(data.data != undefined){
                   $('#userid').html(data.data.userid);
                   $('#codestring').html(data.data.code);
                   $('#for').html(data.data.type);
                   $('#code_display').slideDown();
                   if(data.status == "error"){
                       $("#renew").hide();
                       $("#inval").hide();
                       $('#react').show();
                   } else {
                       $("#renew").show();
                       $("#inval").show();
                       $('#react').hide();
                   }
                   $('#result').hide();
               }
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
               if(data.status == "success"){
                    $('#code_display').slideUp();
               }
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
               if(data.status == "success"){
                   $('#code_display').slideUp();
                   $('#result').html(data.data);
                   $('#result').slideDown();
               }
               flash_message(data.msg, data.status);
           }
       });
       return false;
   });
   $("#react").click(function(){
       var code = $("#code").val();
       $.ajax({
           type: "POST",
           url: "/premium_codes/reactivate",
           data: "code=" + code,
           dataType: "json",
           success: function(data){
               if(data.status == "success"){
                   $('#code_display').slideUp();
               }
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

