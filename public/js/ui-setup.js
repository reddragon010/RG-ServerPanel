$(document).ready(function(){
    $(".menu_button")
        .button({
            icons: {
                primary: "ui-icon-gear",
                //secondary: "ui-icon-triangle-1-s"
            },
            text: false
        });
    $('#login_button')
        .button({
            text: "Login"
        });
    $(".menu_button").each(function(){
        var mb = this;
        var items = $.contextMenu.fromMenu($('#' + mb.id + "_content"));
        $.contextMenu({
            selector: "#" + mb.id,
            items: items,
            trigger: "left"
        });
    });
    $("a.remote").each(function(){
        $(this).click(function(){
            if(this.href != undefined && this.href != "")
                window.location = this.href;
        })
    });
});

