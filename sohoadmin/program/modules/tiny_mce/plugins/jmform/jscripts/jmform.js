/**
 * JMForm plugin for TinyMCE
 * @author Jason McInerney
 */
 
var action;
var HF_IDs = Array();
var HF_vals = Array();

function setAutoValidate(on) {
    var formObj = document.forms[0];
    if (on) {
        formObj.elements['jmformOnsubmit'].value = 'SendRequest(this);';
        formObj.elements['jmformOnsubmit'].disabled = 'true';
        //alert('Auto-validation requires that you include the jmformproc.js script in your form page HTML and that you set required fields using the "Required" attribute in the element dialogs.');
    }
    else {
        formObj.elements['jmformOnsubmit'].value = '';
        formObj.elements['jmformOnsubmit'].disabled = '';
        //alert('Auto-validation has been turned off.  Be sure to update your "onsubmit" attribute if any action is desired.');
    }
}

function addHiddenField() {
    var formObj = document.forms[0];
	tempID = formObj.elements['jmformH0_ID'].value;
    tempVal = formObj.elements['jmformH0_Val'].value;
    
    if (tempID != '') {
        var curInd = HF_IDs.length;
        HF_IDs[curInd] = tempID;
        HF_vals[curInd] = tempVal;
        curInd = HF_IDs.length;
        newRow = '<tr><td class="column1" align="right">';
        newRow += '<input type="button" name="remHF" value="X" style="width: 15px; color: red;" onMouseUp="removeHiddenField(' + curInd + ');">';
        newRow += '&nbsp;&nbsp;' + curInd + '&nbsp;&nbsp;</td>';
        newRow += '<td class="column1">' + tempID + '</td>';
        newRow += '<td class="column1">' + tempVal + '</td></tr>';
        tableCode = document.getElementById('hiddenfields_table').innerHTML;
        re = RegExp("\<\/table\>","ig");
		tableCode = tableCode.replace(re,newRow);
        tableCode += "</table>";
        document.getElementById('hiddenfields_table').innerHTML = tableCode;
    }
}

function removeHiddenField(ind) {
    var conf = confirm('Delete hidden field ' + ind +'?');
    if (conf == true) {
        var formObj = document.forms[0];
        var newRow = '';
        HF_IDs.splice(ind-1,1);
        HF_vals.splice(ind-1,1);
        for (var i=0;i<HF_IDs.length;i++) {
            var curInd = i+1;
            newRow += '<tr><td class="column1" align="right">';
            newRow += '<input type="button" name="remHF" value="X" style="width: 15px; color: red;" onMouseUp="removeHiddenField(' + curInd + ');">';
            newRow += '&nbsp;&nbsp;' + curInd + '&nbsp;&nbsp;</td>';
            newRow += '<td class="column1">' + HF_IDs[i] + '</td>';
            newRow += '<td class="column1">' + HF_vals[i] + '</td></tr>';
        }
        newRow = "<!-- end hidden field input -->" + newRow;
        tableCode = document.getElementById('hiddenfields_table').innerHTML;
        var re = new RegExp("\\n","ig");
		tableCode = tableCode.replace(re,"");	
        re = RegExp(/\<\!\-\-\send\shidden\sfield\sinput\s\-\-\>(.)+$/ig);
        tableCode = tableCode.replace(re,newRow);
        tableCode += "</table>";
        document.getElementById('hiddenfields_table').innerHTML = tableCode;
    }
}
	
function insertForm() {
	var formObj = document.forms[0];
	var inst = tinyMCE.selectedInstance;
	var focusElm = inst.getFocusElement();
	var jmformAction = "", jmformMethod = "POST", className;
	var jmformName = "";
	var html = '';
	
	if (!AutoValidator.validate(formObj)) {
		alert(tinyMCE.getLang('lang_invalid_data'));
		return false;
	}

	tinyMCEPopup.restoreSelection();

	// Get form data
	jmformAction = formObj.elements['jmformAction'].value;
	jmformMethod = formObj.elements['jmformMethod'].options[formObj.elements['jmformMethod'].selectedIndex].value;
	onsubmit = formObj.elements['jmformOnsubmit'].value;
	onreset = formObj.elements['jmformOnreset'].value;
	target = formObj.elements['jmformTarget'].value;
	enc = formObj.elements['jmformEnctype'].options[formObj.elements['jmformEnctype'].selectedIndex].value;
	jmformId = formObj.elements['jmformId'].value;
	jmformName = formObj.elements['jmformName'].value;
	className = formObj.elements['class'].options[formObj.elements['class'].selectedIndex].value;
	summary = formObj.elements['summary'].value;
	style = formObj.elements['style'].value;
	
	// Update form
	if (action == "update") {
        var allFields = new Array();
		inst.execCommand('mceBeginUndoLevel');
		var elm = tinyMCE.getParentElement(focusElm, "form");
		tinyMCE.setAttrib(elm, 'action', jmformAction);
		tinyMCE.setAttrib(elm, 'method', jmformMethod);
		tinyMCE.setAttrib(elm, 'onsubmit', onsubmit);
		tinyMCE.setAttrib(elm, 'onreset', onreset);
		tinyMCE.setAttrib(elm, 'target', target);
		tinyMCE.setAttrib(elm, 'enctype', enc);
		tinyMCE.setAttrib(elm, 'class', className);
		tinyMCE.setAttrib(elm, 'style', style);
		tinyMCE.setAttrib(elm, 'id', jmformId);
		tinyMCE.setAttrib(elm, 'name', jmformName);
		tinyMCE.setAttrib(elm, 'summary', summary);

        allFields = elm.getElementsByTagName("input");
    	for (var i=allFields.length-1; i>=0; i--){
    		var curField = allFields[i];
    		if (curField.type == "hidden"){
    			elm.removeChild(curField);
    		}
    	}
		//add current hidden elements
        for (var i=0;i<HF_IDs.length;i++) {
            newHF = document.createElement("input");
            newHF.type = "hidden";
            newHF.id = HF_IDs[i];
            newHF.name = HF_IDs[i];
            newHF.value = HF_vals[i];
            elm.appendChild(newHF);
        }
		tinyMCE.handleVisualAid(tinyMCE.jmformElm, false, inst.visualAid, inst);

		tinyMCE.handleVisualAid(inst.getBody(), true, inst.visualAid, inst);
		tinyMCE.triggerNodeChange();
		inst.execCommand('mceEndUndoLevel');

		tinyMCEPopup.close();
		return true;
	}

	// Create new form
	html += '<form';

	html += makeAttrib('id', jmformId);
	html += makeAttrib('name', jmformName);
	html += makeAttrib('action', jmformAction);
	html += makeAttrib('method', jmformMethod);
	html += makeAttrib('onsubmit', onsubmit);
	html += makeAttrib('onreset', onreset);
	html += makeAttrib('target', target);
	html += makeAttrib('enctype', enc);
	html += makeAttrib('class', tinyMCE.getVisualAidClass(className) + ' mceVisualAid');
	html += makeAttrib('style', style);
	html += makeAttrib('summary', summary);
    
	html += '>';
    for (var i=0;i<HF_IDs.length;i++) {
        html += '<input type="hidden" id="' + HF_IDs[i] + '" name="' + HF_IDs[i] + '" value="' + HF_vals[i] + '" />';
    }
    html += '<br /><br />';


	html += "</form>";

	inst.execCommand('mceBeginUndoLevel');
	inst.execCommand('mceInsertContent', false, html);
	tinyMCE.handleVisualAid(inst.getBody(), true, tinyMCE.settings['visual']);
	inst.execCommand('mceEndUndoLevel');

	tinyMCEPopup.close();
}

function makeAttrib(attrib, value) {
	var formObj = document.forms[0];
	var valueElm = formObj.elements[attrib];

	if (typeof(value) == "undefined" || value == null) {
		value = "";

		if (valueElm)
			value = valueElm.value;
	}

	if (value == "")
		return "";

	// XML encode it
	value = value.replace(/&/g, '&amp;');
	value = value.replace(/\"/g, '&quot;');
	value = value.replace(/</g, '&lt;');
	value = value.replace(/>/g, '&gt;');

	return ' ' + attrib + '="' + value + '"';
}

function init() {
	tinyMCEPopup.resizeToInnerSize();
    HF_IDs = new Array();
    HF_vals = new Array();
	var jmformAction = "", jmformMethod = "POST", target = "", enc = "", jmformName = "";
	var jmformId = "", summary = "", style = "", className = "", onsubmit = "", onreset = "";
	var inst = tinyMCE.selectedInstance;
	var formObj = document.forms[0];
	var jmformElm = tinyMCE.getParentElement(inst.getFocusElement(), "form");
    var allFields = new Array();
    
	action = tinyMCE.getWindowArg('action');
	if (action == null)
		action = jmformElm ? "update" : "insert";

	if (jmformElm != undefined && action == "update") {
		st = tinyMCE.parseStyle(tinyMCE.getAttrib(jmformElm, "style"));
		jmformAction = tinyMCE.getAttrib(jmformElm, 'action');
		jmformMethod = tinyMCE.getAttrib(jmformElm, 'method');
		jmformName = tinyMCE.getAttrib(jmformElm, 'name');
		onsubmit = tinyMCE.getAttrib(jmformElm, 'onsubmit');
		onreset = tinyMCE.getAttrib(jmformElm, 'onreset');
		target = tinyMCE.getAttrib(jmformElm, 'target');
		enc = tinyMCE.getAttrib(jmformElm, 'enctype');
		className = tinyMCE.getVisualAidClass(tinyMCE.getAttrib(jmformElm, 'class'), false);
		jmformId = tinyMCE.getAttrib(jmformElm, 'id');
		summary = tinyMCE.getAttrib(jmformElm, 'summary');
		style = tinyMCE.serializeStyle(st);
        
        allFields = jmformElm.getElementsByTagName("input")
    	for (var i=0; i<allFields.length; i++){
    		var curField = allFields[i];
    		if (curField.type == "hidden"){
    			HF_IDs.push(curField.name);
    			HF_vals.push(curField.value);
    		}
    	}
	}

	addClassesToList('class', "jmform_styles");

	// Update form
	selectByValue(formObj, 'class', className);
    if (onsubmit == 'SendRequest(this);') {
        formObj.jmformAuto.checked = true;
        setAutoValidate(true);
    }
	formObj.jmformAction.value = jmformAction;
	formObj.jmformMethod.value = jmformMethod;
	formObj.jmformName.value = jmformName;
	formObj.jmformOnsubmit.value = onsubmit;
	formObj.jmformOnreset.value = onreset;
	formObj.jmformTarget.value = target;
	formObj.jmformEnctype.value = enc;
	formObj.jmformId.value = jmformId;
	formObj.summary.value = summary;
	formObj.style.value = style;
	formObj.insert.value = tinyMCE.getLang('lang_' + action, 'Insert', true);
    var newRow = '';
    if (HF_IDs.length > 0) {
        for (var i=0;i<HF_IDs.length;i++) {
            var curInd = i+1;
            newRow += '<tr><td class="column1" align="right">';
            newRow += '<input type="button" name="remHF" value="X" style="width: 15px; color: red;" onMouseUp="removeHiddenField(' + curInd + ');">';
            newRow += '&nbsp;&nbsp;' + curInd + '&nbsp;&nbsp;</td>';
            newRow += '<td class="column1">' + HF_IDs[i] + '</td>';
            newRow += '<td class="column1">' + HF_vals[i] + '</td></tr>';
        }
        newRow = "<!-- end hidden field input -->" + newRow;
        tableCode = document.getElementById('hiddenfields_table').innerHTML;
        var re = new RegExp("\\n","ig");
		tableCode = tableCode.replace(re,"");	
        re = RegExp(/\<\!\-\-\send\shidden\sfield\sinput\s\-\-\>(.)+$/ig);
        tableCode = tableCode.replace(re,newRow);
        tableCode += "</table>";
        document.getElementById('hiddenfields_table').innerHTML = tableCode;
    }
}

function changedStyle() {
	var formObj = document.forms[0];
	var st = tinyMCE.parseStyle(formObj.style.value);

	if (st['width'])
		formObj.width.value = trimSize(st['width']);

	if (st['height'])
		formObj.height.value = trimSize(st['height']);

	if (st['background-color']) {
		formObj.bgcolor.value = st['background-color'];
		updateColor('bgcolor_pick','bgcolor');
	}

	if (st['border-color']) {
		formObj.bordercolor.value = st['border-color'];
		updateColor('bordercolor_pick','bordercolor');
	}
}
