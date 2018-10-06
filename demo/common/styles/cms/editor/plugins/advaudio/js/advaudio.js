tinyMCEPopup.requireLangPack();
var allowedextension = Array("mp3","mp4");
var AdvaudioDialog = {
	init : function() {
		tinyMCEPopup.resizeToInnerSize();
		
		var pl = "",f = document.forms[0];
		ed = tinyMCEPopup.editor;
		fe = ed.selection.getNode();
		
		
		if (ed.dom.getAttrib(fe, 'class') == 'mceItemAudio') {
			pl = fe.title;
			f.insert.value = ed.getLang('update', 'Insert', true); 
		}
		if (pl != "") {
			pl = tinyMCEPopup.editor.plugins.advaudio._parse(pl);
			setStr(pl, null, 'href');
			setStr(pl, null, 'title');
		}
		document.getElementById('hrefbrowsercontainer').innerHTML = getBrowserHTML('hrefbrowsercontainer','href','advaudio','advaudio');
	},

	insert : function() {
		if(document.forms[0].elements["href"].value == '')
			tinyMCEPopup.alert("Please entere File/URL.",function(s) {return false;});
		else if(!(/(.mp3)$/i.test(document.forms[0].elements["href"].value)))
			tinyMCEPopup.alert("The Filetype is not supported. You need to select a .mp3 file for this method.",function(s) {return false;});
		else
		{
			// Insert the contents from the input into the document
			h = '<img src="' + tinyMCEPopup.getWindowArg("plugin_url") + '/img/audio.gif" class="mceItemAudio"';
			h += ' title="' + serializeParameters() + '" />';
			ed.execCommand('mceInsertContent', false, h);
			tinyMCEPopup.close();
		}
	}
};

function serializeParameters() {
	var d = document, f = d.forms[0], s = '';
	s += getStr(null, 'href');
	s += getStr(null, 'title');
		
	s = s.length > 0 ? s.substring(0, s.length - 1) : s;
	return s;
}

function setBool(pl, p, n) {
	if (typeof(pl[n]) == "undefined")
		return;

	document.forms[0].elements[p + "_" + n].checked = pl[n] != 'false';
}

function setStr(pl, p, n) {
	var f = document.forms[0], e = f.elements[(p != null ? p + "_" : '') + n];

	if (typeof(pl[n]) == "undefined")
		return;

	if (e.type == "text")
		e.value = pl[n];
	else
		selectByValue(f, (p != null ? p + "_" : '') + n, pl[n]);
}

function getBool(p, n, d, tv, fv) {
	var v = document.forms[0].elements[p + "_" + n].checked;

	tv = typeof(tv) == 'undefined' ? 'true' : "'" + jsEncode(tv) + "'";
	fv = typeof(fv) == 'undefined' ? 'false' : "'" + jsEncode(fv) + "'";

	return (v == d) ? '' : n + (v ? ':' + tv + ',' : ":\'" + fv + "\',");
}

function getStr(p, n, d) {
	var e = document.forms[0].elements[(p != null ? p + "_" : "") + n];
	var v = e.type == "text" ? e.value : e.options[e.selectedIndex].value;

	if (n == 'src')
		v = tinyMCEPopup.editor.convertURL(v, 'src', null);

	return ((n == d || v == '') ? '' : n + ":'" + jsEncode(v) + "',");
}

function getInt(p, n, d) {
	var e = document.forms[0].elements[(p != null ? p + "_" : "") + n];
	var v = e.type == "text" ? e.value : e.options[e.selectedIndex].value;

	return ((n == d || v == '') ? '' : n + ":" + v.replace(/[^0-9]+/g, '') + ",");
}

function jsEncode(s) {
	s = s.replace(new RegExp('\\\\', 'g'), '\\\\');
	s = s.replace(new RegExp('"', 'g'), '\\"');
	s = s.replace(new RegExp("'", 'g'), "\\'");

	return s;
}

function getBrowserHTML(id, target_form_element, type, prefix) {
	var option = prefix + "_" + type + "_browser_callback", cb, html;
	cb = tinyMCEPopup.getParam(option, tinyMCEPopup.getParam("file_browser_callback"));

	if (!cb)
		return "";

	html = "";
	html += '<a id="' + id + '_link" href="javascript:openBrowser(\'' + id + '\',\'' + target_form_element + '\', \'' + type + '\',\'' + option + '\');" onmousedown="return false;" class="browse">';
	html += '<span id="' + id + '" title="' + tinyMCEPopup.getLang('browse') + '">&nbsp;</span></a>';

	return html;
}
tinyMCEPopup.onInit.add(AdvaudioDialog.init, AdvaudioDialog);