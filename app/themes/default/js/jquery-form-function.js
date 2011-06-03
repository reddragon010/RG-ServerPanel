function updateTips( t ) {
	tips
		.text( t )
		.addClass( "ui-state-highlight" );
	setTimeout(function() {
		tips.removeClass( "ui-state-highlight", 1500 );
	}, 500 );
}

function checkLength( o, n, min, max ) {
	if ( o.val().length > max || o.val().length < min ) {
		o.addClass( "ui-state-error" );
		updateTips( "Length of " + n + " must be between " +
			min + " and " + max + "." );
		return false;
	} else {
		return true;
	}
}

function checkRegexp( o, regexp, n ) {
	if ( !( regexp.test( o.val() ) ) ) {
		o.addClass( "ui-state-error" );
		updateTips( n );
		return false;
	} else {
		return true;
	}
}

function checkEmail(o,n) {
	return checkRegexp( o, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, n );
}

function checkUsername(o, n) {
	return checkRegexp( o, /^[a-z]([0-9a-z_])+$/i, n );
}

$(document).ready(function() {
  $('a.modalform').click(function() {
    $('<div />').appendTo('body').dialog({
      title: $(this).attr('title'),
      modal: true,
			resizable: false,
			height: $(this).attr('form-height'),
			width: $(this).attr('form-width')
    }).load($(this).attr('href') + ' form', function() {
      $form = $(this).find('form')
      $form.find(':text:first').focus();
      $btn = $form.find(':submit');
      var txt = $btn.val();
      $btn.remove();
      var buttons = {};
      buttons[txt] = function() {
				if($('#form-status-msg').length == 0){
					$form.append('<div id="form-status-msg" class="loading">Loading...</div>');
				} else {
					$('#form-status-msg').removeClass().addClass('loading');
					$('#form-status-msg').html('Loading...');
				}
        $.ajax({
          type: $form.attr('method'),
          url: $form.attr('action'),
          data: $form.serialize(),
          dataType: 'json',
          success: function(data) {
            if(data.status=='error'){
							$('#form-status-msg').removeClass('loading').addClass('error');
							$('#form-status-msg').html(data.msg);
							return false;
						}else{
							$form.html("");
							$('#form-status-msg').removeClass('loading').addClass('success');
							$('#form-status-msg').html(data.msg);
							$(".ui-dialog-buttonset").hide();
							location.reload(true);
            	return false;
						}
          },
					error: function(xhr, textStatus, error){
						$('#form-status-msg').removeClass('loading').addClass('error');
						$('#form-status-msg').html(xhr.responseText);
          	return false;
					}
        });
      };
      $(this).dialog('option','buttons', buttons );
			$('.ui-dialog').keydown(function(e){
				if (e.keyCode == 13) {              
		  		$('.ui-dialog').find('button:first').trigger('click');
					return false;
		  	}
			});
    });
    return false;
  });
});