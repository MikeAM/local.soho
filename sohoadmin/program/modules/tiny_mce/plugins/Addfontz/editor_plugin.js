////tinyMCE.importPluginLanguagePack('Addfontz');
//var TinyMCE_AddfontzPlugin={getInfo:function(){return{longname:'Addfontz',author:'Moxiecode Systems AB',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/Addfontz',version:tinyMCE.majorVersion+"."+tinyMCE.minorVersion}},getControlHTML:function(cn){switch(cn){case"Addfontz":return tinyMCE.getButtonHTML(cn,'lang_Addfontz_desc','{$pluginurl}/images/addfontz.gif','mceAddFontz')}return""},execCommand:function(editor_id,element,command,user_interface,value){switch(command){case"mceAddFontz":var template=new Array();template['file']='../../plugins/Addfontz/camsuperfonts.php';template['width']=790;template['height']=470;template['width']+=tinyMCE.getLang('lang_Addfontz_delta_width',0);template['height']+=tinyMCE.getLang('lang_Addfontz_delta_height',0);tinyMCE.openWindow(template,{editor_id:editor_id,inline:"yes"});return true}return false}};
//tinyMCE.addPlugin('Addfontz',TinyMCE_AddfontzPlugin);
//
//var TinyMCE_AddfontzPlugin={getInfo:function(){
//	return{longname:'Addfontz',author:'Moxiecode Systems AB',authorurl:'http://tinymce.moxiecode.com',infourl:'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/Addfontz',version:tinyMCE.majorVersion+"."+tinyMCE.minorVersion}}
//		,getControlHTML:function(cn){switch(cn){
//			case"Addfontz":return tinyMCE.getButtonHTML(cn,'lang_Addfontz_desc','{$pluginurl}/images/addfontz.gif','mceAddFontz')}return""},
//			execCommand:function(editor_id,element,command,user_interface,value){switch(command){
//				case"mceAddFontz":var template=new Array();template['file']='../../plugins/Addfontz/camsuperfonts.php';
//				template['width']=790;template['height']=470;template['width']+=tinyMCE.getLang('lang_Addfontz_delta_width',0);
//				template['height']+=tinyMCE.getLang('lang_Addfontz_delta_height',0);
//	tinyMCE.openWindow(template,{editor_id:editor_id,inline:"yes"});
//	return true}
//	return false}};

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('Addfontz');

	tinymce.create('tinymce.plugins.AddfontzPlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceAddfontz');
			ed.addCommand('mceAddfontz', function() {
				ed.windowManager.open({
					file : url + '../../Addfontz/camsuperfonts.php',
					width : 790 + parseInt(ed.getLang('Addfontz.delta_width', 0)),
					height : 470 + parseInt(ed.getLang('Addfontz.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
					//some_custom_arg : return tinyMCE.getButtonHTML(cn,'lang_Addfontz_desc','{$pluginurl}/images/addfontz.gif','mceAddFontz') // Custom argument
				});
			});

			// Register Addfontz button
			ed.addButton('Addfontz', {
				title : 'Add fonts',
				cmd : 'mceAddfontz',
				image : url + '/images/addfontz.gif'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('Addfontz', n.nodeName == 'IMG');
			});
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'Addfontz plugin',
				author : 'Some author',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/Addfontz',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('Addfontz', tinymce.plugins.AddfontzPlugin);
})();