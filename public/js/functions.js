function mainmenu(){
    $(" #nav ul ").css({
        display: "none"
    }); // Opera Fix
    $(" #nav li").hover(function(){
        $(this).find('ul:first').css({
            visibility: "visible",
            display: "none"
        }).show(400);
    },function(){
        $(this).find('ul:first').css({
            visibility: "hidden"
        });
    });
}
 
$(document).ready(function(){					
    mainmenu();
});

$(document).ready(function(){
    $('div.collapsible').find('div.headline').click(function(){
        $(this).parent('div.collapsible').find('.body').slideToggle();
    });
});

$(document).ready(function(){
    $('a.remote_form').click(function(){
        $('<div />').appendTo('body').dialog({
            title: $(this).attr('title'),
            modal: true
        }).load($(this).attr('href') + ' form', function(){
            $form = $(this).find('form');
            $form.find(':text:first').focus();
            $btn = $form.find(':submit');
            var txt = $btn.val();
            $btn.remove();
            var buttons = {};
            var msg = "";
            buttons[txt] = function(){
                $.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(data){
                        if(data.status=='success'){
                            $form.html("");
                            show_msg($form,data.msg,true);
                            $(".ui-dialog-buttonset").hide();
                            setTimeout(function(){
                                location.reload(true);
                            }, 1000);
                            return false;
                        } else {
                            show_msg($form,data.msg,false);
                            return false;
                        }
                    },
                    error: function(xhr, error) {
                        show_msg($form,xhr.responseText,false);
                        return false;
                    }
                });
            }
            $(this).dialog('option', 'buttons', buttons);
            $('.ui-dialog').keydown(function(e){
                if(e.keyCode == 13){
                    $('.ui-dialog').find('button:first').trigger('click');
                    return false;
                }
            });
        });
        return false;
    }); 
});

function show_msg(parent,msg,success){
    var div_class = "";
    if(success){
        div_class = "ui-state-highlight ui-corner-all";
    } else {
        div_class = "ui-state-error ui-corner-all";
    }
    msg = '<p><span class="ui-icon ui-icon-alert" style="float:left; margin: 0 0.3em;"></span>'+msg+'</p>';
    if($('#ajax-msg').length > 0){
        $('#ajax-msg').html(msg);
    } else {
        div = '<div id="ajax-msg" class="'+div_class+'">'+msg+'</div>';
        parent.append(div);
    }
}