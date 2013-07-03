/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'soholaunch\'">' + entity + '</span>' + html;
	}
	var icons = {
			'music' : '&#x266c;',
			'soho-icon-font-15' : '&#x261c;',
			'soho-icon-font_youtube' : '&#x264a;',
			'soho-icon-font_pen' : '&#x270d;',
			'soho-icon-font_page' : '&#x25af;',
			'soho-icon-font_newsletter' : '&#x2709;',
			'soho-icon-font_members' : '&#x263b;',
			'soho-icon-font_map' : '&#x27b2;',
			'soho-icon-font_iphone' : '&#x260e;',
			'soho-icon-font_facebook' : '&#x261d;',
			'soho-icon-font_edit' : '&#x270e;',
			'soho-icon-font_cart' : '&#x24;',
			'soho-icon-font_camera' : '&#x25d9;',
			'soho-icon-font_calendar' : '&#x2328;',
			'soho-icon-font_arrowcircle' : '&#x21ba;',
			'soho-icon-font_analytics' : '&#x25d4;',
			'soho-icon-font_floatie' : '&#x2665;',
			'soho-icon-font_timer' : '&#x2611;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};