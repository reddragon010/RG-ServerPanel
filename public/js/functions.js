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
            $("#bandetailedtimebox").slideUp();
            $("#bantimebox").slideUp();
            break;
        case "time":
            $("#bandetailedtimebox").slideUp();
            $("#bantimebox").slideDown();
            $("#banreasonbox").slideDown();
            break;
        case "detailedtime":
            $("#bantimebox").slideUp();
            $("#bandetailedtimebox").slideDown();
            $("#banreasonbox").slideDown();
            break;
        case "save":
            $("#bandetailedtimebox").slideUp();
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
               register_remote_form_click();
           },
           error: function(data, status){
               target.removeClass('ajax_loading');
               target.addClass('ajax_error');
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

function check_account(source, target){
    var s = $("#" + source);
    var t = $("#" + target);
    $.ajax({
        url: '/accounts/index?type=json&username=' + escape(s.val()),
        dataType: 'json',
        beforeSend: function(){
            t.removeClass('account_check_success');
            t.removeClass('account_check_failed');
            t.addClass('account_check_loading');
        },
        success: function( data ) {
            if (typeof(data) == "object" && data.length == 1 && data[0].data.username == s.val()){
                t.removeClass('account_check_loading');
                t.addClass('account_check_success');
                return true;
            } else {
                t.removeClass('account_check_loading');
                t.addClass('account_check_failed');
            }
        },
        error: function(){
            t.removeClass('account_check_loading');
            t.addClass('account_check_failed');
        }
    });        
    return false;
}