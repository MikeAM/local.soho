/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'soholaunch\'">' + entity + '</span>' + html;
	}
	var icons = {
			'music' : '&#x6d;',
			'soho-icon-font_youtube' : '&#x76;',
			'soho-icon-font_pen' : '&#x62;',
			'soho-icon-font_page' : '&#x70;',
			'soho-icon-font_newsletter' : '&#x65;',
			'soho-icon-font_members' : '&#x75;',
			'soho-icon-font_map' : '&#x61;',
			'soho-icon-font_iphone' : '&#x72;',
			'soho-icon-font_facebook' : '&#x66;',
			'soho-icon-font_edit' : '&#x64;',
			'soho-icon-font_cart' : '&#x63;',
			'soho-icon-font_camera' : '&#x68;',
			'soho-icon-font_calendar' : '&#x6c;',
			'soho-icon-font_arrowcircle' : '&#x77;',
			'soho-icon-font_analytics' : '&#x73;'
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