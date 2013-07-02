/**
 * $Id: editor_plugin_src.js 201 2007-02-12 15:56:56Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('upload_file');

// Plucin static class
var TinyMCE_EmotionsPlugin = {
	getInfo : function() {
		return {
			longname : 'upload_file',
			author : 'Joe Lain',
			authorurl : 'http://info.soholaunch.com',
			infourl : 'http://info.soholaunch.com',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	/**
	 * Returns the HTML contents of the emotions control.
	 */
	getControlHTML : function(cn) {
		switch (cn) {
			case "upload_file":
				return tinyMCE.getButtonHTML(cn, 'lang_emotions_desc', '{$pluginurl}/images/emotions.gif', 'mceUploadFile');
		}

		return "";
	},

	/**
	 * Executes the mceUploadFile command.
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "mceUploadFile":
				var template = new Array();

				template['file'] = '../../plugins/add_fontz/emotions.htm'; // Relative to theme
				template['width'] = 250;
				template['height'] = 160;

				// Language specific width and height addons
				template['width'] += tinyMCE.getLang('lang_emotions_delta_width', 0);
				template['height'] += tinyMCE.getLang('lang_emotions_delta_height', 0);

				tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes"});
				var fontHTML='<select id="{$editor_id}_fontNameSelect" name="{$editor_id}_fontNameSelect" onfocus="tinyMCE.addSelectAccessibility(event, this, window);" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'FontName\',false,this.options[this.selectedIndex].value);" class="mceSelectList"><option value="">{$lang_theme_fontdefault}</option>';var iFonts='Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,times new roman,times,serif;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times,serif;Verdana=verdana,arial,helvetica,sans-serif;Impact=impact;WingDings=wingdings';var nFonts='Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sand;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats';var fonts=tinyMCE.getParam("theme_advanced_fonts",nFonts).split(';');for(i=0;i<fonts.length;i++){if(fonts[i]!=''){var parts=fonts[i].split('=');fontHTML+='<option value="'+parts[1]+'">'+parts[0]+'</option>'}}fontHTML+='</select>';return fontHTML;
				return true;
		}

		// Pass to next handler in chain
		return false;
	}
};

// Register plugin
tinyMCE.addPlugin('upload_file', TinyMCE_EmotionsPlugin);
