
var form = {
	
	init: function() {
		
		// set form event on button click
		$('body').on('click', '.form .buttons .button', function(e) {
			$(this).parents('.form').find('.form-event .entries .entry.pos-0 input').val(this.name);
		});
		
		// cancel closes parent popover
		$('body').on('click', '.form .buttons .button.cancel', function(e) {
			$(this).parents('.popover').fadeOut().remove();
		});
		
		// focus closes suggestions
		$('body').on('focus click select', '.form input', function(e) {
			$(this).parents('.form').find('.suggestions').html('');
		});
				
		// down moves focus to suggestions
		$('body').on('keypress', '.form input', form.checkInputDown);
		
		// suggestion-click or enter sets value
		$('body').on('click', '.form .suggestions a', form.setSuggestion);
		$('body').on('keypress', '.form .suggestions a', form.checkSuggestionEnter);
		
		// up/down moved focus
		$('body').on('keypress', '.form .suggestions a', form.checkSuggestionUpDown);
		
				
		$('body').on('submit', 'form.ajax', function(e) {
			e.preventDefault();
			var el = $(this);
			$.ajax({
				url: $(this).attr('action'),
				type: $(this).attr('method'),
				dataType: "html",
				data: $(this).serialize(),
				success: function(data) {
					// response is the form
					if (data.match(/^\s*\<form/)) {
						var formId = el.attr('id');
						var prevMinHeight = parseInt(el.parent().css('min-height'));
						el.parent().css('min-height', el.parent().height());
						el.replaceWith(data);
						$('#' + formId).hide().fadeIn().parent().css('min-height', prevMinHeight ? prevMinHeight : '');
					}
				}
			});
		});
		
		$('body').on('reset', 'form', function(e) {
			//e.preventDefault();
			form.resetFields(this);
		});
		
	},
	
	initialiseFields: function() {
		$('input').each(function() {
			if ($(this).is("[data-init-value]")) return;
			$(this).attr('data-init-value', $(this).attr('value'));
		});
	},
	
	resetFields: function(form) {
		$(form).find('input').each(function() {
			if (!$(this).is("[data-init-value]")) return;
			$(this).attr('value', $(this).attr('data-init-value'));
		});
	},
	
	suggest: function(el, data) {
		var fieldName = $(el).attr('name').replace(/[\[\]]/g, '-');
		// inject suggestion div
		if (!$(el).parents('.entry').find('.suggestions.' + fieldName).length) {
			$(el).after('<div class="suggestions ' + fieldName + '" data-field="' + $(el).attr('name') + '"></div>');
		}
		$(el).parents('.entry').find('.suggestions.' + fieldName).html(data);
		//$(el).parents('.entry').find('.suggestions.' + fieldName).html(data).css('min-width', $(el).width());
	},
	
	setSuggestion: function(e, els) {
		els = els || this;
		var value = $(els).attr('data-value');
		var fieldName = $(els).parents('.suggestions').attr('data-field');
		$(els).parents('.entry').find('input[name="' + fieldName + '"]').attr('value', value).select();
	},
	
	checkInputDown: function(e) {
		var fieldName = $(this).attr('name').replace(/[\[\]]/g, '-');
		// down
		if (e.keyCode == 40) {
			e.preventDefault();
			// suggestions
			if ($(this).parents('.entry').find('.suggestions.' + fieldName).length) {
				$(this).parents('.entry').find('.suggestions.' + fieldName + ' a:first').attr('tabindex', 1).focus().removeAttr('tabindex');
			}
		}
	},
	
	checkSuggestionEnter: function(e) {
		if (e.keyCode == 13) {
			form.setSuggestion(e, this);
		}
	},
	
	checkSuggestionUpDown: function(e) {
		// up
		if (e.keyCode == 38) {
			e.preventDefault();
			if ($(this).parents('li').prev('li').length) {
				$(this).parents('li').prev('li').find('a').attr('tabindex', 1).focus().removeAttr('tabindex');
			}
			else {
				var fieldName = $(this).parents('.suggestions').attr('data-field');
				$(this).parents('.entry').find('input[name="' + fieldName + '"]').focus();
			}
		}
		// down
		if (e.keyCode == 40) {
			e.preventDefault();
			if ($(this).parents('li').next('li').length) {
				$(this).parents('li').next('li').find('a').attr('tabindex', 1).focus().removeAttr('tabindex');
			}
		}
	}
	

}

if (window['jQuery']) $(form.init);


