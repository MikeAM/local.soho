/**
 *
 * @author Jason McInerney
 * For TinyMCE
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('jmform');

var TinyMCE_JMFormPlugin = {
	getInfo : function() {
		return {
			longname : 'JMForm',
			author : 'Jason McInerney',
			authorurl : '',
			infourl : '',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	initInstance : function(inst) {
		if (tinyMCE.isGecko) {
			var doc = inst.getDoc();
			tinyMCE.addEvent(doc, "mouseup", TinyMCE_TablePlugin._mouseDownHandler);
		}
	},

	/**
	 * Returns the HTML contents of the form control.
	 */
	getControlHTML : function(control_name) {
		var controls = new Array(
			['jmform', 'jmform.gif', 'lang_jmform_desc', 'mceInsertJMForm', true],
			['delete_jmform', 'jmform_delete.gif', 'lang_jmform_del', 'mceJMFormDelete'],
			['jmform_insert_input', 'jmform_insert_input.gif', 'lang_jmform_insert_input', 'mceJMFormInsertInput', true],
			['jmform_insert_select', 'jmform_insert_select.gif', 'lang_jmform_insert_select', 'mceJMFormInsertSelect', true],
			['jmform_insert_textarea', 'jmform_insert_textarea.gif', 'lang_jmform_insert_textarea', 'mceJMFormInsertTextarea', true]
		);

		// Render form control
		for (var i=0; i<controls.length; i++) {
			var but = controls[i];
			var cmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'' + but[3] + '\', ' + (but.length > 4 ? but[4] : false) + (but.length > 5 ? ', \'' + but[5] + '\'' : '') + ');return false;';

			if (but[0] == control_name)
				return tinyMCE.getButtonHTML(control_name, but[2], '{$pluginurl}/images/'+ but[1], but[3], (but.length > 4 ? but[4] : false));
		}

		// Special form controls
		if (control_name == "jmformcontrols") {
			var html = "";

			html += tinyMCE.getControlHTML("jmform");
			html += tinyMCE.getControlHTML("jmform_insert_input");
			html += tinyMCE.getControlHTML("jmform_insert_select");
			html += tinyMCE.getControlHTML("jmform_insert_textarea");
			html += tinyMCE.getControlHTML("delete_jmform");
			return html;
		}

		return "";
	},

	/**
	 * Executes the form commands.
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		// Is form command
		switch (command) {
			case "mceInsertJMForm":
			case "mceJMFormInsertInput":
			case "mceJMFormInsertSelect":
			case "mceJMFormInsertTextarea":
			case "mceJMFormDelete":
				var inst = tinyMCE.getInstanceById(editor_id);

				inst.execCommand('mceBeginUndoLevel');
				TinyMCE_JMFormPlugin._doExecCommand(editor_id, element, command, user_interface, value);
				inst.execCommand('mceEndUndoLevel');

				return true;
		}

		// Pass to next handler in chain
		return false;
	},

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
		var jmformAction = "", jmformMethod = "POST", inpElm;

		var inst = tinyMCE.getInstanceById(editor_id);

		// Reset form controls
		tinyMCE.switchClass(editor_id + '_jmform', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_delete_jmform', 'mceButtonDisabled');
		tinyMCE.switchClass(editor_id + '_jmform_insert_input', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_jmform_insert_select', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_jmform_insert_textarea', 'mceButtonNormal');

		// Within form
		if (tinyMCE.getParentElement(node, "form")) {
			tinyMCE.switchClass(editor_id + '_jmform', 'mceButtonSelected');
			tinyMCE.switchClass(editor_id + '_jmform_insert_input', 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_jmform_insert_select', 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_jmform_insert_textarea', 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_delete_jmform', 'mceButtonNormal');
			
			jmformAction = tinyMCE.getAttrib(inpElm, "action");
			jmformMethod = tinyMCE.getAttrib(inpElm, "element");

			jmformAction = jmformAction == "" ? "" : jmformAction;
			jmformMethod = jmformMethod == "" ? "POST" : jmformMethod;
		}
		// Within a form input element
		if (inpElm = tinyMCE.getParentElement(node, "input")) {
			tinyMCE.switchClass(editor_id + '_jmform', 'mceButtonSelected');
			tinyMCE.switchClass(editor_id + '_jmform_insert_input', 'mceButtonSelected');
			tinyMCE.switchClass(editor_id + '_jmform_insert_select', 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_jmform_insert_textarea', 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_delete_jmform', 'mceButtonNormal');
		}
		// Within a form select element
		if (inpElm = tinyMCE.getParentElement(node, "select")) {
			tinyMCE.switchClass(editor_id + '_jmform', 'mceButtonSelected');
			tinyMCE.switchClass(editor_id + '_jmform_insert_input', 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_jmform_insert_select', 'mceButtonSelected');
			tinyMCE.switchClass(editor_id + '_jmform_insert_textarea', 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_delete_jmform', 'mceButtonNormal');
		}
		// Within a form textarea element
		if (inpElm = tinyMCE.getParentElement(node, "textarea")) {
			tinyMCE.switchClass(editor_id + '_jmform', 'mceButtonSelected');
			tinyMCE.switchClass(editor_id + '_jmform_insert_input', 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_jmform_insert_select', 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_jmform_insert_textarea', 'mceButtonSelected');
			tinyMCE.switchClass(editor_id + '_delete_jmform', 'mceButtonNormal');
		}
	},

	// Private plugin internal methods

	_mouseDownHandler : function(e) {
		var elm = tinyMCE.isMSIE ? event.srcElement : e.target;
		var focusElm = tinyMCE.selectedInstance.getFocusElement();
	},

	/**
	 * Executes the jmform commands.
	 */
	_doExecCommand : function(editor_id, element, command, user_interface, value) {
		var inst = tinyMCE.getInstanceById(editor_id);
		var focusElm = inst.getFocusElement();
		
		var doc = inst.contentWindow.document;

		// ------- Inner functions ---------
		function inArray(ar, v) {
			for (var i=0; i<ar.length; i++) {
				// Is array
				if (ar[i].length > 0 && inArray(ar[i], v))
					return true;

				// Found value
				if (ar[i] == v)
					return true;
			}

			return false;
		}

		function makeInp() {
			var newInp = doc.createElement("input");
			newInp.innerHTML = "&nbsp;";
		}

		function prevElm(node, name) {
			while ((node = node.previousSibling) != null) {
				if (node.nodeName == name)
					return node;
			}

			return null;
		}

		function nextElm(node, names) {
			var namesAr = names.split(',');

			while ((node = node.nextSibling) != null) {
				for (var i=0; i<namesAr.length; i++) {
					if (node.nodeName.toLowerCase() == namesAr[i].toLowerCase() )
						return node;
				}
			}

			return null;
		}

		function deleteMarked(frm) {
			if (frm.inps == 0)
				return;

			var inp = frm.inps[0];
			do {
				var nextinp = nextElm(inp, "input,select,textarea");
				if (inp._delete)
							inp.parentNode.removeChild(inp);
			} while ((inp = nextinp) != null);
		}

		// ---- Commands -----

		// Handle commands
		switch (command) {
			case "mceJMFormInsertTextarea":
				var textElm = tinyMCE.getParentElement(focusElm, "textarea");
				if (textElm == undefined || textElm == null)
					value = "insert";
				else
					value = "update";
					
				if (user_interface) {
					// Setup template
					var template = new Array();

					template['file'] = '../../plugins/jmform/textarea.htm';
					template['width'] = 380;
					template['height'] = 480;

					// Language specific width and height addons
					template['width'] += tinyMCE.getLang('lang_jmform_elemprops_delta_width', 0);
					template['height'] += tinyMCE.getLang('lang_jmform_elemprops_delta_height', 0);

					// Open window
					tinyMCE.openWindow(template, {editor_id : inst.editorId, inline : "yes", action : value});
				}

				return true;
				
			case "mceJMFormInsertSelect":
				var selElm = tinyMCE.getParentElement(focusElm, "select");
				if (selElm == undefined || selElm == null)
					value = "insert";
				else
					value = "update";
					
				if (user_interface) {
					// Setup template
					var template = new Array();

					template['file'] = '../../plugins/jmform/select.htm';
					template['width'] = 380;
					template['height'] = 510;

					// Language specific width and height addons
					template['width'] += tinyMCE.getLang('lang_jmform_elemprops_delta_width', 0);
					template['height'] += tinyMCE.getLang('lang_jmform_elemprops_delta_height', 0);

					// Open window
					tinyMCE.openWindow(template, {editor_id : inst.editorId, inline : "yes", action : value});
				}

				return true;
				
			case "mceJMFormInsertInput":
				var inpElm = tinyMCE.getParentElement(focusElm, "input");
				if (inpElm == undefined || inpElm == null)
					value = "insert";
				else
					value = "update";
					
				if (user_interface) {
					// Setup template
					var template = new Array();

					template['file'] = '../../plugins/jmform/input.htm';
					template['width'] = 380;
					template['height'] = 480;

					// Language specific width and height addons
					template['width'] += tinyMCE.getLang('lang_jmform_elemprops_delta_width', 0);
					template['height'] += tinyMCE.getLang('lang_jmform_elemprops_delta_height', 0);

					// Open window
					tinyMCE.openWindow(template, {editor_id : inst.editorId, inline : "yes", action : value});
				}

				return true;

			case "mceInsertJMForm":
				var jmformElm = tinyMCE.getParentElement(focusElm, "form");
				if (jmformElm == undefined || jmformElm == null)
					value = "insert";
				else
					value = "update";
					
				if (user_interface) {
					// Setup template
					var template = new Array();

					template['file'] = '../../plugins/jmform/jmform.htm';
					template['width'] = 380;
					template['height'] = 400;

					// Language specific width and height addons
					template['width'] += tinyMCE.getLang('lang_jmform_elemprops_delta_width', 0);
					template['height'] += tinyMCE.getLang('lang_jmform_elemprops_delta_height', 0);
					
					// Open window
					tinyMCE.openWindow(template, {editor_id : inst.editorId, inline : "yes", action : value});
				}

				return true;

			case "mceJMFormDelete":
				var jmform = tinyMCE.getParentElement(inst.getFocusElement(), "form");
				if (jmform) {
					jmform.parentNode.removeChild(jmform);
					inst.repaint();
				}
				return true;
				
			return true;
		}

		// Pass to next handler in chain
		return false;
	}
};

tinyMCE.addPlugin("jmform", TinyMCE_JMFormPlugin);
