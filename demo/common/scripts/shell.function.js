// JavaScript Document
var newwin;
function opennewpopup(url,w,h)
{
	if(newwin)
		newwin.close();
	
	var width = w;
	var height = h;
	var left = (screen.width - width)/2;
	var top = (screen.height - height)/2;
	var params = 'width='+width+', height='+height;
	params += ', top='+top+', left='+left;
	params += ', directories=no';
	params += ', location=no';
	params += ', menubar=no';
	params += ', resizable=no';
	params += ', scrollbars=yes';
	params += ', status=no';
	params += ', toolbar=no';
	newwin=window.open(url,'Cha1', params);
	if (window.focus) {newwin.focus()}
	return false;
}

function trim(par)
 {
	var y = par.length;
	var ret = '';
	var ex=0;
	var bl=0;
	for (i=0;i<y;i++)
	{
		if (par.charAt(i) == ' ')
			bl = bl+1
		else
		{
			ret=ret+par.charAt(i);
			ex = ex+1
		}
	}
	if (bl==y)
		return true;
	else 
		return false;
	
}
function validateEmail(email){
	if (email == ""){return false;}
	badStuff = ";:/,' \"\\";
	for (i=0; i<badStuff.length; i++)
	{
		badCheck = badStuff.charAt(i)
		if (email.indexOf(badCheck,0) != -1){return false;}
	}
	
	posOfAtSign = email.indexOf("@",1)
	if (posOfAtSign == -1){return false;}
		
	if (email.indexOf("@",posOfAtSign+1) != -1){return false;}
	
	posOfPeriod = email.indexOf(".", posOfAtSign)
	if (posOfPeriod == -1){return false;}
	
	if (posOfPeriod+2 > email.length){return false;}
	
	return true;
}

function appMessage(msg, type){	
	if(type == "neg"){
		$.jGrowl(msg, { header: 'Error!', life: 4000 });
	}
	
	if(type == "pos"){
		$.jGrowl(msg, { header: 'Success!', life: 4000 });
	}
	
	if(type == "pre"){
		$('.alertBox p').html(msg);
		$('.alertBox h2').html("Please Wait!");

		$('.alertBox').removeClass('preloader positive negative');
		$('.alertBox').addClass('preloader');
		
		$('#okBtn').removeClass("btn");
		$('#okBtn').addClass("hidden");
		
		$.blockUI({
			message: $('.alertBox'),
			css: {width: '400px', height:'auto', cursor: 'inherit', border:'none', background:'none', 'text-align':'left'}
		});
	}
	if(type == "prepos"){
		$('.alertBox p').html(msg);
		$('.alertBox h2').html("Success!");
		
		$('.alertBox').removeClass('preloader positive negative');
		$('.alertBox').addClass('positive');
		
		$('#okBtn').addClass("btn");
		$('#okBtn').removeClass("hidden");
	}
	if(type == "preneg"){
		$('.alertBox p').html(msg);
		$('.alertBox h2').html("Success!");
		
		$('.alertBox').removeClass('preloader positive negative');
		$('.alertBox').addClass('positive');
		
		$('#okBtn').addClass("btn");
		$('#okBtn').removeClass("hidden");
	}
}

function destoryMessage()
{
	$.unblockUI();
}

/* Content Editors */
function advancedEditor(cmswidth,cmsheight,elem)
{
	tinyMCE.init({
		mode : "exact",
		elements : elem,
		theme : "advanced",
		plugins : "safari,pagebreak,style,table,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,template,wordcount,advfile,advimage,advvideo,advaudio",
		theme_advanced_buttons1 : "template,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,forecolor,backcolor,|,sub,sup,|,ltr,rtl,|,styleprops,attribs,removeformat,|,styleselect",
		theme_advanced_buttons2 : "undo,redo,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,|,advfile,image,advvideo,advaudio,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons3 : "tablecontrols,|,moveforward,movebackward,|,insertdate,inserttime,emotions,advhr,pagebreak,charmap,|,visualaid,iespell,|,preview,print,fullscreen,|,code,cleanup",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		file_browser_callback : MadFileBrowser,
		onchange_callback : function (inst){contentchangedflag = true;},
		relative_urls : false,
		remove_script_host : false,
		theme_advanced_resizing : false,
		//template_external_list_url : "templates/template_list.js",
		content_css : "styles/cms.css",
		width:cmswidth,
		height:cmsheight
	});
}

function miniEditor(cmswidth,cmsheight,elem)
{
	tinyMCE.init({
		mode : "exact",
		elements : elem,
		theme : "simple",
		plugins : "safari,pagebreak,style,table,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,template,wordcount,advfile,advimage,advvideo,advaudio",
		theme_advanced_buttons1 : "template,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent",
		theme_advanced_buttons2 : "forecolor,backcolor,|,ltr,rtl,|,styleprops,attribs,|,link,unlink,anchor,|,removeformat,advfile,advhr,iespell,fullscreen,code",
		theme_advanced_buttons3 : "formatselect,styleselect,fontselect,fontsizeselect",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		file_browser_callback : MadFileBrowser,
		onchange_callback : function (inst){contentchangedflag = true;},
		relative_urls : false,
		remove_script_host : false,
		theme_advanced_resizing : false,
		//template_external_list_url : templatePath,
		content_css : "style/cms.css",
		width:cmswidth,
		height:cmsheight
	});
}

function simpleEditor(cmswidth,cmsheight,elem)
{
	tinyMCE.init({
		mode : "exact",
		elements : elem,
		theme : "advanced",
		plugins : "safari,pagebreak,style,table,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,template,wordcount,advfile,advimage,advvideo,advaudio",
		theme_advanced_buttons1 : "template,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent",
		theme_advanced_buttons2 : "forecolor,backcolor,|,ltr,rtl,|,styleprops,attribs,|,link,unlink,anchor,image,|,removeformat,advfile,advhr,iespell,fullscreen,code",
		theme_advanced_buttons3 : "formatselect,styleselect,fontselect,fontsizeselect",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		file_browser_callback : MadFileBrowser,
		onchange_callback : function (inst){contentchangedflag = true;},
		relative_urls : false,
		remove_script_host : false,
		theme_advanced_resizing : false,
//		template_external_list_url : templatePath,
		forced_root_block : false,
		force_br_newlines : true,
		force_p_newlines : false,
		content_css : "styles/cms.css",
		width:cmswidth,
		height:cmsheight
	});
}

function MadFileBrowser(field_name, url, type, win) {
  url = url.replace("http://www.myprojectdemonstration.com","");
  tinyMCE.activeEditor.windowManager.open({file : "../cms/mfm.php?field=" + field_name + "&url=" + url + "&type="+ type +"",title : "File1 Manager",width : 640, height : 450,resizable : "no",inline : "yes",close_previous : "no" },{window : win,input : field_name});
  return false;
}


/*
var myURL = parseURL('http://abc.com:8080/dir/index.html?id=255&m=hello#top');
myURL.file;     // = 'index.html'
myURL.hash;     // = 'top'
myURL.host;     // = 'abc.com'
myURL.query;    // = '?id=255&m=hello'
myURL.params;   // = Object = { id: 255, m: hello }
myURL.path;     // = '/dir/index.html'
myURL.segments; // = Array = ['dir', 'index.html']
myURL.port;     // = '8080'
myURL.protocol; // = 'http'
myURL.source;   // = 'http://abc.com:8080/dir/index.html?id=255&m=hello#top'
*/

function parseURL(url) {
    var a =  document.createElement('a');
    a.href = url;
    return {
        source: url,
        protocol: a.protocol.replace(':',''),
        host: a.hostname,
        port: a.port,
        query: a.search,
        params: (function(){
            var ret = {},
                seg = a.search.replace(/^\?/,'').split('&'),
                len = seg.length, i = 0, s;
            for (;i<len;i++) {
                if (!seg[i]) { continue; }
                s = seg[i].split('=');
                ret[s[0]] = s[1];
            }
            return ret;
        })(),
        file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],
        hash: a.hash.replace('#',''),
        path: a.pathname.replace(/^([^\/])/,'/$1'),
        relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],
        segments: a.pathname.replace(/^\//,'').split('/')
    };
}

function isAdminSection()
{
	var currentURL  = parseURL($(location).attr('href'));
	if(searchInArray("admincp", currentURL.segments))
		return true;
	else
		return false;
}

//Remove variables from the query string.
function removeVariable(str,mstr)
{
	var tmp=str.split('&');
	var newstr = '';
	for(loop=0;loop<tmp.length;loop++)
	{
		var temp = tmp[loop].split('=');
		if(temp[0] == mstr)
			continue;
		else
			newstr += tmp[loop]+"&";
	}
	if(newstr.length > 0)
		newstr = newstr.substring(0, newstr.length-1);
	return newstr;
}

function getQueryValue(v)
{
	var currentURL  = parseURL($(location).attr('href'));
	var tq = currentURL.query
	tq = tq.substring(1, tq.length);
	var tmp = tq.split('&');
	var newstr = '';
	for(loop=0;loop<tmp.length;loop++)
	{
		var temp = tmp[loop].split('=');
		if(temp[0] == v)
			return temp[1]; 
	}
	return "";
}

function formGetSubmit(frm,elm)
{
	var myurl  = parseURL($(location).attr('href'));
	var qeryString = myurl.query;
	if(elm != '')
	{
		if(elm == "filtering")
		{
			if(qeryString.length > 0)
				qeryString = qeryString.substring(1, qeryString.length);
				
			qeryString = removeVariable(qeryString,"pgn");
			qeryString = removeVariable(qeryString,"pgn");
			
			if(qeryString.length > 0)
				qeryString = "?"+qeryString;
		}
		$(frm).find('.'+elm).each(function (){
			if(qeryString.length > 0)
				qeryString = qeryString.substring(1, qeryString.length);	
				
			qeryString = removeVariable(qeryString,$(this).attr('name'));
			if(qeryString == '')
				qeryString = "?"+$(this).attr('name')+"="+$(this).attr("value");
			else
				qeryString = "?"+qeryString+"&"+$(this).attr('name')+"="+$(this).attr("value");
		});
	}
	$(location).attr('href',qeryString);
	return false;
}

function sorting(elm)
{
//	var parameter = $(elm).attr("href").substring(1, $(elm).attr("href").length);	
//	var parameters = parameter.split('&');	
//	var currentURL  = parseURL($(location).attr('href'));
//	var qeryString = currentURL.query;
//
//	for(i=0;i<parameters.length;i++)
//	{
//		var valuePair = parameters[i].split('=');
//		if(qeryString.length > 0)
//			qeryString = qeryString.substring(1, qeryString.length);	
//
//		qeryString = removeVariable(qeryString,valuePair[0]);
//		
//		if(qeryString == '')
//			qeryString = "?"+valuePair[0]+"="+valuePair[1];
//		else
//			qeryString = "?"+qeryString+"&"+valuePair[0]+"="+valuePair[1];
//	}
//	
//	$(location).attr('href',qeryString);
//	return false;
return true;
}

function aHref(parameter)
{
	var parameters = parameter.split('&');	
	var currentURL  = parseURL($(location).attr('href'));
	var qeryString = currentURL.query;

	for(i=0;i<parameters.length;i++)
	{
		var valuePair = parameters[i].split('=');
		if(qeryString.length > 0)
			qeryString = qeryString.substring(1, qeryString.length);	

		qeryString = removeVariable(qeryString,valuePair[0]);
		
		if(qeryString == '')
			qeryString = "?"+valuePair[0]+"="+valuePair[1];
		else
			qeryString = "?"+qeryString+"&"+valuePair[0]+"="+valuePair[1];
	}
	
	return qeryString;
}


function delAction(elm)
{
	var idvname = $(elm).attr("href").split('&');
	var id = idvname[0].split('=');
	var vname = idvname[1].split('=');
	
	$('.checkme').each(function (){
		if($(this).attr("value") == id[1])
		{
			$(this).attr("checked","checked");
			$("#cmbActions").attr("value",vname[1]);
			$(".formList").attr("method","post").submit();
			return true;				
		}
	});
	return false;
}

function delCmbAction(id)
{
	$('.selectRow').each(function (){
		if($(this).attr("value") == id)
		{
			$(this).attr("checked","checked");
			$("#cmbActions").attr("value","d");
			$(".formList").attr("method","post").submit();
			return true;				
		}
	});
	return false;
}

function cmbAction(id,a)
{
	var flag = false;
	$('.selectRow').each(function (){
		if($(this).attr("value") == id)
		{
			$(this).attr("checked","checked");
			flag = true;
		}
		else
			$(this).attr("checked","");
	});
	if(flag)
	{
		$("#cmbActions").attr("value",a);
		$(".formList").attr("method","post").submit();
		return true;				
	}

	return false;
}

function autoGeneratedPassword(minLength, maxLength, upercaseLength, numericLength, symbolLength)
{
	var length = 6;
	var upercaseLength = parseInt(parseFloat(upercaseLength));
	var numericLength = parseInt(parseFloat(numericLength));
	var symbolLength = parseInt(parseFloat(symbolLength));
	var maxLength = parseInt(parseFloat(maxLength));
	var minLength = parseInt(parseFloat(minLength));

	if((upercaseLength + numericLength + symbolLength) > maxLength)
		length = upercaseLength + numericLength + symbolLength;
	else if(maxLength > 0)
		length = maxLength;
		
	var lowerCaseAlphabats = Array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	var uperCaseAlphabats = Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	var numeric = Array(1,2,3,4,5,6,7,8,9,0);
	var symbols = Array('@','.');
	
	var password = Array();
	for(x=0;x<upercaseLength;x++)
	{
		i = Math.floor(Math.random()*26)
		password.push(uperCaseAlphabats[i]);
	}
	
	for(x=0;x<numericLength;x++)
	{
		i = Math.floor(Math.random()*10)
		password.push(numeric[i]);
	}
	
	for(x=0;x<symbolLength;x++)
	{
		i = Math.floor(Math.random()*2)
		password.push(symbols[i]);
	}
	
	if(length < minLength)
		length = minLength;
	
	for(x=0;x < length - (upercaseLength + numericLength + symbolLength);x++)
	{
		i = Math.floor(Math.random()*26)
		password.push(lowerCaseAlphabats[i]);
	}
	
	password = shuffle(password);
	return password.join("");;
}

function shuffle(array) {
    var tmp, current, top = array.length;

    if(top) while(--top) {
        current = Math.floor(Math.random() * (top + 1));
        tmp = array[current];
        array[current] = array[top];
        array[top] = tmp;
    }

    return array;
}

function searchInArray(v, array)
{
	for(x=0; x<array.length; x++)
	{
		if(v == array[x])
			return true;
	}
	return false;
}

function quickAjaxProcess(obel,actionURl,valuePairs)
{
	var values = '';
	for(var i=0;i<valuePairs.length;i++)
	{
		values += valuePairs[i][0]+"="+valuePairs[i][1]+"&";
	}
	
	obel.addClass("hidden");
	obel.parent().append("<img src='media/images/icons/preloader.gif' style='margin:0 0 0 2px;'>");
	$.ajax({
	   type: "POST",
	   url: actionURl,
	   data: values,
	   success: function(msg){			  
			obel.removeClass("hidden");
			obel.next().remove();
			window.parent.appMessage(msg,'pos');
			//$(".warning", top.document).removeClass('hidden').addClass("pos").html(msg);
	   }
	 });
}

/** START - Code used for adjusting the iframe height **/
function getElement(aID)
{
	return (document.getElementById) ? document.getElementById(aID) : document.all[aID];
}

function getIFrameDocument(aID)
{ 
	var rv = null; 
	var frame=getElement(aID);
	
	if (frame.contentDocument)
		rv = frame.contentDocument;
	else
		rv = document.frames[aID].document;
	return rv;
}

function resetIframeHeight(id)
{
	var frame = getElement(id);
	var frameDoc = getIFrameDocument(id);
	frame.height = frameDoc.body.offsetHeight;
}

function addfancybox(){
	$("a.fancybox").fancybox({
    	'modal': true
    });
}
/** END - Code used for adjusting the iframe height **/


/** END - Code used for adjusting the iframe height **/
function explode (delimiter, string, limit) {

    if ( arguments.length < 2 || typeof delimiter == 'undefined' || typeof string == 'undefined' ) return null;
	if ( delimiter === '' || delimiter === false || delimiter === null) return false;
	if ( typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object'){
		return { 0: '' };
	}
	if ( delimiter === true ) delimiter = '1';
	
	// Here we go...
	delimiter += '';
	string += '';
	
	var s = string.split( delimiter );
	

	if ( typeof limit === 'undefined' ) return s;
	
	// Support for limit
	if ( limit === 0 ) limit = 1;
	
	// Positive limit
	if ( limit > 0 ){
		if ( limit >= s.length ) return s;
		return s.slice( 0, limit - 1 ).concat( [ s.slice( limit - 1 ).join( delimiter ) ] );
	}

	// Negative limit
	if ( -limit >= s.length ) return [];
	
	s.splice( s.length + limit );
	return s;
}

function is_array_object(string)
{
    var regex = /[\[\]']+/;
    if(regex.test(string))
        {
            return true;
        }
    else
        {
            return false;
        }
        
}
function single(string)
{
    var sing = /[a-zA-Z]\['\d*'\]/;
    if(sing.test(string))
        {
            return true
        }
}
function doub(string)
{
    var sing = /[a-zA-Z]\["\d*"\]/;
    if(sing.test(string))
        {
            return true
        }
}
function withoutQuotes(string)
{
    
    var sing = /[a-zA-Z]\[\d+\]/;
    if(sing.test(string))
        {
            return true
        }
}