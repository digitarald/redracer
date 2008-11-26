Element.AutoGrow = new Class({

	Implements: Options,

	options: {
		maxHeight: null,
		minHeight: null,
		lineHeight: null,
		tween: {}
	},

	initialize: function(element, options) {
		this.element = $(element);
		this.setOptions(options);

		['maxHeight', 'minHeight', 'lineHeight'].each(function(css) {
			if (this.options[css] != undefined) return;
			this.options[css] = parseInt(this.element.getStyle(css)) || '';
		}, this);
		if (!this.options.minHeight) this.options.minHeight = this.element.getStyle('height').toInt() || 0;

		this.bound = {
			focus: this.start.bind(this),
			blur: this.stop.bind(this)
		};
		this.build();
	},

	build: function() {
		this.element
			.setStyles({overflow: 'hidden', display: 'block'})
			.addEvents(this.bound);
		if (this.options.tween) this.element.set('tween', $merge({link: 'cancel', duration: 200}, this.options.tween));
		var styles = {
			overflowX: 'hidden',
			position: 'absolute',
			top: 0,
			left: -9999
		};
		['fontSize', 'fontFamily', 'fontWeight', 'width', 'padding', 'lineHeight'].each(function(css) {
			styles[css] = this.element.getStyle(css);
		}, this);
		this.clone = new Element('div', {html: '#', styles: styles}).inject(document.body);
		this.check();
	},

	destroy: function() {
		this.clone = this.clone.destroy();
	},

	start: function() {
		if (!this.timer) this.timer = this.check.periodical(500, this);
	},

	stop: function() {
		this.timer = $clear(this.timer);
	},

	check: function() {
		var html = this.element.value.replace(/[<>]/g, '');
		if (this.clone.innerHTML == html) return;
		html = html.replace(/\n/g, (Browser.Engine.trident) ? '<BR />new' : '<br />new');
		this.clone.innerHTML = html;
		var from = this.clone.offsetHeight, to = from + this.options.lineHeight;
		if (this.options.maxHeight && to > this.options.maxHeight) {
			this.element.setStyle('overflowY', 'auto');
			return;
		}
		this.element.setStyle('overflowY', 'hidden');
		var current = this.element.offsetHeight;
		if ((current < to || from < current) && (!this.options.minHeight || this.options.minHeight < to)) {
			this.element[(this.options.tween) ? 'tween' : 'setStyle']('height', to);
		}
	}

});
