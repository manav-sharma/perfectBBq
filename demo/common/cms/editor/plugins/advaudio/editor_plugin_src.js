(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('advaudio');
	
	var allowedextension = Array("mp3","mp4");
	
	// Create Plugin
	tinymce.create('tinymce.plugins.AdvaudioPlugin', {		
		init : function(ed, url) {
			var t = this;
			t.editor = ed;
			t.url = url;
			
			// Custom Functions
			function isDocElm(n) {
				return /^(mceItemAudio)$/.test(n.className);
			};
			
			function allowedfiles(ohref)
			{				
				for(i=0;i<allowedextension.length;i++)
				{
					if(eval('/('+allowedextension[i]+'.gif)$/i.test(ohref)'))
						return allowedextension[i];
				}
				return "audio";
			}
			
			ed.onPreInit.add(function() {
				// Force in _value parameter this extra parameter is required for older Opera versions
				ed.serializer.addRules('param[name|value|_mce_value]');
			});
			
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceAdvfile');
			ed.addCommand('mceAdvaudio', function() {
				ed.windowManager.open({
					file : url + '/advaudio.htm',
					width : 480 + parseInt(ed.getLang('advaudio.delta_width', 0)),
					height : 400 + parseInt(ed.getLang('advaudio.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});
			
			// Register advfile button
			ed.addButton('advaudio', {
				title : 'advaudio.desc',
				cmd : 'mceAdvaudio',
				image : url + '/img/advaudio.gif'
			});			
			
			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('advaudio', n.nodeName == 'IMG' && isDocElm(n));
			});
			
			ed.onInit.add(function() {
				ed.selection.onSetContent.add(function() {
					t._spansToImgs(ed.getBody());
				});
				
				ed.selection.onBeforeSetContent.add(t._objectsToSpans, t);
				
				if (ed.theme.onResolveName) {
					ed.theme.onResolveName.add(function(th, o) {
						if (o.name == 'img') {
							if (ed.dom.hasClass(o.node, 'mceItemAudio')) {
								//o.name = allowedfiles(o.node.src);
								o.name = "audio";
								o.title = ed.dom.getAttrib(o.node, 'title');
								return false;
							}
						}
					});
				}
				
			});
			
			ed.onBeforeSetContent.add(t._objectsToSpans, t);
			
			ed.onSetContent.add(function() {
				t._spansToImgs(ed.getBody());
			});
			
			ed.onPreProcess.add(function(ed, o) {
				var dom = ed.dom;
				if (o.get) {
					var buttonsid = 1;
					tinymce.each(dom.select('img', o.node), function(n) {
						if(n.className == 'mceItemAudio')
						{
							dom.replace(t._buildMyObj(n,buttonsid), n, true);
							buttonsid ++;
						}
					});
				}
			});

			ed.onPostProcess.add(function(ed, o) {
				o.content = o.content.replace(/_mce_value=/g, 'value=');
			});
		},
		
		 // Private methods
		 _objectsToSpans : function(ed, o) {
			var t = this, h = o.content;
			
			h = h.replace(/<object([^>]*)>/gi, '<span class="mceItemObject" $1>');
			h = h.replace(/<embed([^>]*)\/?>/gi, '<span class="mceItemEmbed" $1></span>');
			h = h.replace(/<embed([^>]*)>/gi, '<span class="mceItemEmbed" $1>');
			h = h.replace(/<\/(object)([^>]*)>/gi, '</span>');
			h = h.replace(/<\/embed>/gi, '');
			h = h.replace(/<param([^>]*)>/gi, function(a, b) {return '<span ' + b.replace(/value=/gi, '_mce_value=') + ' class="mceItemParam"></span>'});
			h = h.replace(/\/ class=\"mceItemParam\"><\/span>/gi, 'class="mceItemParam"></span>');
						
			o.content = h;
		},
		 
		_buildMyObj : function(n,bid) {
			var ob, ed = this.editor, dom = ed.dom, linkurl, p = this._parse(n.title);
			linkurl = p.href;

			ob = dom.create('span', {
					id : "wimpy_button_"+bid,
					mce_name : 'object',
					classid : "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",
					codebase : "http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,47,0",
					width : "35",
					height : "35"
				});
			dom.add(ob, 'span', {mce_name : 'param', name : "movie", '_mce_value' : this.url+"/audioplayer.swf?theFile="+linkurl});
			dom.add(ob, 'span', {mce_name : 'param', name : "menu", '_mce_value' : "false"});
			dom.add(ob, 'span', {mce_name : 'param', name : "quality", '_mce_value' : "high"});
			dom.add(ob, 'span', {mce_name : 'param', name : "bgcolor", '_mce_value' : "#ffffff"});
			dom.add(ob, 'span', tinymce.extend({mce_name : 'embed'}, {src : this.url+"/audioplayer.swf?theFile="+linkurl,"quality" : "high","bgcolor":"#ffffff","width":"35","height":"35","name":"wimpy_button_"+bid,"type":"application/x-shockwave-flash","pluginspage":"http://www.macromedia.com/go/getflashplayer"}));
			
			return ob;
		},
		
		_spansToImgs : function(p) {
			var t = this, dom = t.editor.dom, ci;

			tinymce.each(dom.select('span', p), function(n) {
				// Convert object into image
				if (dom.getAttrib(n, 'class') == 'mceItemObject') {
					ci = dom.getAttrib(n, "classid").toLowerCase().replace(/\s+/g, '');
					dom.replace(t._createImg('mceItemAudio', n), n);
					return;
				}
			});
		},
		
		_createImg : function(cl,n) {
			var im, dom = this.editor.dom, pa = {};
			// Create image
			im = dom.create('img', {
				src : this.url + '/img/audio.gif',
				'class' : cl
			});
			
			pa['title'] =dom.getAttrib(n, "title");			
			tinymce.each(dom.select('span', n), function(n) {
														
				if (dom.hasClass(n, 'mceItemParam'))
				{
					if(dom.getAttrib(n, 'name').toLowerCase() == 'movie')
					{
						var tempattributes = dom.getAttrib(n, '_mce_value').split("?");						
						if(tempattributes.length > 1)
						{
							var querystringparams = tempattributes[1].split("&");
							for(var i=0;i<querystringparams.length;i++)
							{
								var tempparam = querystringparams[i].split("=");
								if(tempparam[0] == 'theFile')
									pa['href'] = tempparam[1];
							}
						}
					}
				}
			});
			
			im.title = this._serialize(pa);

			return im;
		},
		
		createControl : function(n, cm) {
			return null;
		},
				
		getInfo : function() {
			return {
				longname : 'Advaudio plugin',
				author : 'Akshaya',
				authorurl : 'http://tinymce.moxiecode.com',
				infourl : 'http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/example',
				version : "1.0"
			};
		},
		_parse : function(s) {
			return tinymce.util.JSON.parse('{' + s + '}');
		},

		_serialize : function(o) {
			return tinymce.util.JSON.serialize(o).replace(/[{}]/g, '');
		}
	});
	
	// Register plugin
	tinymce.PluginManager.add('advaudio', tinymce.plugins.AdvaudioPlugin);
})();