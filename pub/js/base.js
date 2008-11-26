
var RedRacer = new Class({

	Implements: Events,

	initialize: function() {
		this.assign(document);
	},

	assign: function(element) {
		element.getElements('form.styled').each(this.genericForm, this);
	},

	genericForm: function(form) {
		// focus behaviour for form fields
		form.getElements('input, textarea, select').addEvents({
			focus: function() {
				var field = this.getParent('.field');
				if (field) field.addClass('focused');
			},
			blur: function() {
				var field = this.getParent('.field');
				if (field) field.removeClass('focused');
			}
		});

		var title = form.getElement('input.project-title');
		if (title) {
			var ident = form.getElement('input.project-ident');
			var current = title.value, sync = true;
			title.addEvent('keyup', function() {
				if (sync) {
					current = ident.value = title.value.urlize().substr(0, ident.maxLength);
				}
			});
			ident.addEvent('change', function() {
				sync = (current != this.value);
			});
		}

		// hover behaviour for form fields
		form.getElements('.field').addEvents({
			mouseenter: function() {
				this.addClass('hovered');
			},
			mouseleave: function() {
				this.removeClass('hovered');
			}
		});

		// max length counter for text inputs
		form.getElements('input[maxlength], textarea[class*=maxlength]').each(function(input) {
			// fallback by classname "maxlength(xy)"
			var max = (input.get('tag') == 'textarea')
				? (input.className.match(/maxlength\((\d+)\)/) || [])[1]
				: input.getProperty('maxlength');
			if ((max = parseInt(max) || 0) < 1) return;
			var wrap = new Element('div', {'class': 'counter'}).inject(input.getParent(), 'top');
			var counter = new Element('strong').inject(wrap);
			wrap.appendText(' characters remaining');
			function update() {
				var length = this.value.length;
				// wrap.set('opacity', (length / max).max(.5));
				var left = (max - length).max(0);
				counter.innerHTML = left;
				wrap[left ? 'removeClass' : 'addClass']('over');
			}
			input.addEvent('keyup', update);
			update.call(input);
		});

		form.getElements('textarea').each(function(input) {
			new Element.AutoGrow(input);
		});

		form.getElements('select[multiple]').each(function(select) {
			var opts = select.getSelected();
			opts.removeProperty('selected');
			$(new Option('...', '')).inject(select, 'top');
			select.addClass('removable').setStyle('display', 'none');
			select.size = 1;
			select.multiple = false;
			select.selectedIndex = -1;
			var values = [], selects = [];
			function create(option) {
				var clone = $(select.cloneNode(true)).setStyle('display', '');
				clone.selectedIndex = (option) ? option.index : 0;
				var value;
				if (option) {
					option.selected = false;
					value = option.value;
					values.push(value);
				}
				selects.push(clone);
				if (!option) filterOne(clone);
				clone.addEvent('change', function() {
					var index = clone.selectedIndex;
					if (!index && clone.getNext() != select) {
						selects.erase(clone);
						filterAll();
						clone.destroy();
				  	anchor.destroy();
				  }
					if (index) {
						value = clone.get('value');
						values.push(value);
						filterAll();
						if (clone.getNext() == select) {
					  	anchor.setStyle('display', '');
					  	create(false);
					  }
					}
				}).inject(select, 'before');
				var anchor = new Element('a', {
					href: '#',
					text: 'Remove',
					'class': 'remove-select'
				}).addEvent('click', function() {
					clone.selectedIndex = 0;
					clone.fireEvent('change');
					return false;
				}).inject(clone, 'before');
				if (!option) {
					anchor.setStyle('display', 'none');
					if (option === false) clone.focus();
				}
			};
			function filterAll() {
				selects.each(filterOne);
			}
			function filterOne(select) {
				var selected = select.selectedIndex;
				new Elements(select.options).each(function(check, idx) {
					var hide = (selected != idx && values.contains(check.value));
					check.disabled = hide;
					check.setStyle('display', (hide) ? 'none' : '');
				});
			};
			opts.each(create);
			filterAll();
			create();
		});
	}

});

String.implement({

	urlize: function(separator) {
		separator = separator || '-';
		var sep_re = separator.escapeRegExp();

		var text = this.toLowerCase().replace(new RegExp('[' + String.urlizeSpecial + ']', 'g'), function(match) {
			return String.urlizeReplace.get(match);
		});

		return text.replace(/[^\x00-\x7F]+/g, '')
			.replace(/[^a-z0-9\-_\+]+/g, separator)
			.replace(new RegExp(sep_re + '{2,}', 'g'), separator)
			.replace(new RegExp('^' + sep_re + '|' + sep_re + '$', 'g'), '');
	}

});

String.urlizeSpecial = 'àáâãäåòóôõöøèéêëçìíîïùúûüÿñ';
String.urlizeReplace = new Hash('aaaaaaooooooeeeeciiiiuuuuyn'.split('').associate(String.urlizeSpecial));

document.addEvent('domready', function() {
	window.redracer = new RedRacer();
});
