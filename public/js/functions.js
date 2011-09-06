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

function banTypeChanged(){
    var val = $("#bantype").attr('value');
    switch(val){
        case "perm":
            $("#bantimebox").slideUp();
            break;
        case "time":
            $("#bantimebox").slideDown();
            $("#banreasonbox").slideDown();
            break;
        case "save":
            $("#bantimebox").slideUp();
            $("#banreasonbox").slideUp();
            break;
    }
    return false;
}

function update_over_ajax(url, target){
    $(document).ready(function(){
        target = $(target);
        $.ajax({
           url: url,
           dataType: 'text',
           beforeSend: function(){
               target.addClass('ajax_loading');
           },
           success: function(data){
               target.removeClass('ajax_loading');
               target.html(data);
           }
        });
    });
}

function img_fallback(t){
    var url = $(t).attr('fallback');
    $(t).attr('src', url);
}

function toggle(target){
    $("#" + target).slideToggle();
}