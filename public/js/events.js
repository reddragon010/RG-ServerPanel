$(document).ready(function(){
    register_collapse_box_click();
    register_remote_form_click();
    register_img_fallback();
    register_link_confirm_click();
    register_mainmenu();
    register_notifications();
});

function register_collapse_box_click(){
    $('div.collapsible').find('div.headline').click(function(){
        $(this).parent('div.collapsible').find('.body').slideToggle();
    });
}

function register_remote_form_click(){
    $('a.remote_form').click(function(){
        $('<div />').appendTo('body').dialog({
            title: $(this).attr('title'),
            modal: true,
            width: $(this).attr('width'),
            close: function(){$(this).remove();}
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
                if(e.keyCode == 13 && !e.shiftKey){
                    $('.ui-dialog').find('button:first').trigger('click');
                    return false;
                }
            });
        });
        return false;
    }); 
}

function register_img_fallback(){
    $('img.withfallback')
        .error(function(){
            var url = $(this).attr('fallback');
            $(this).attr('src', url);
        });
}

function register_link_confirm_click(){
    $('a.confirm').click(function(){
        return confirm("Are You Sure?");
    });
}

function register_mainmenu(){
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

function register_notifications(){
    $('#notifications').jnotifyInizialize({
        //oneAtTime: true
    })
}