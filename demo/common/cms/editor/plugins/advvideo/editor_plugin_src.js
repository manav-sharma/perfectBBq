/**
 * ## Advance File
 *
 */
(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('advvideo');
	
	var allowedextension = Array("mp3","mp4","flv","swf","swf","rm","rmvb","ra","mov","wmv","wav","wma","mpg","mpeg");
	
	// Create Plugin
	tinymce.create('tinymce.plugins.AdvvideoPlugin', {
		
		init : function(ed, url) {
			var t = this;
			t.editor = ed;
			t.url = url;
			
			// Custom Functions
			function isDocElm(n) {
				return /^(mceItemVideo)$/.test(n.className);
			};
			
			function allowedfiles(ohref)
			{				
				for(i=0;i<allowedextension.length;i++)
				{
					if(eval('/('+allowedextension[i]+'.gif)$/i.test(ohref)'))
						return allowedextension[i];
				}
				return "video";
			}
			
			ed.onPreInit.add(function() {
				// Force in _value parameter this extra parameter is required for older Opera versions
				ed.serializer.addRules('param[name|value|_mce_value]');
			});
			
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceAdvfile');
			ed.addCommand('mceAdvvideo', function() {
				ed.windowManager.open({
					file : url + '/advvideo.htm',
					width : 480 + parseInt(ed.getLang('advvideo.delta_width', 0)),
					height : 400 + parseInt(ed.getLang('advvideo.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});
			
			// Register advfile button
			ed.addButton('advvideo', {
				title : 'advvideo.desc',
				cmd : 'mceAdvvideo',
				image : url + '/img/advvideo.gif'
			});			
			
			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('advvideo', n.nodeName == 'IMG' && isDocElm(n));
			});
			
			ed.onInit.add(function() {
				ed.selection.onSetContent.add(function() {
					t._spansToImgs(ed.getBody());
				});
				
				ed.selection.onBeforeSetContent.add(t._objectsToSpans, t);
				
				if (ed.theme.onResolveName) {
					ed.theme.onResolveName.add(function(th, o) {
						if (o.name == 'img') {
							if (ed.dom.hasClass(o.node, 'mceItemVideo')) {
								o.name = allowedfiles(o.node.src);
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
					tinymce.each(dom.select('img', o.node), function(n) {
						if(n.className == 'mceItemVideo')
							dom.replace(t._buildMyObj(n,url), n);
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
			h = h.replace(/<a (.*)([^>]*)><img (.*)src=\"(.*)\/editor\/plugins\/advvideo\/img\/video\.gif\"(.*)([^>]*)\/?><\/a([^>]*)\/?>/gi, '<span class="mceItemVideo" $1></span>');
			o.content = h;
		},
		 
		_buildMyObj : function(n,url) {
			var ob, ed = this.editor, dom = ed.dom, linkurl, p = this._parse(n.title);
			linkurl = ed.convertURL(p.href, 'href', n);
						
			ob = dom.create('a', {
					mce_name : 'a',
					href : url+'/videoplayer.php?height='+p.height+'&width='+p.width+'&media_type='+p.media_type+'&href='+p.href,
					'class' : "cmsEd_lunchMedia",					
					title : p.title
				});
			dom.add(ob, 'img', {mce_name : 'img', src : url+"/img/video.gif", border : "0"});
			return ob;
		},
		
		_spansToImgs : function(p) {
			var t = this, dom = t.editor.dom, ci;

			tinymce.each(dom.select('span', p), function(n) {
				// Convert object into image
				if (dom.getAttrib(n, 'class') == 'mceItemVideo') {
					ohref = dom.getAttrib(n, "href").toLowerCase().replace(/\s+/g, '');
					var tempattributes = ohref.split("?");
					if(tempattributes.length > 1)
					{
						var querystringparams = tempattributes[1].split("&");
						for(var i=0;i<querystringparams.length;i++)
						{
							var tempparam = querystringparams[i].split("=");
							if(tempparam[0] == "href")
								ohref = tempparam[1];
						}
					}
					dom.replace(t._createImg('mceItemVideo', n), n);
					return;
				}
			});
		},
		
		_createImg : function(cl,n) {
			var im, dom = this.editor.dom, pa = {};
			// Create image
			im = dom.create('img', {
				src : this.url + '/img/video.gif',
				'class' : cl
			});
			pa['title'] =dom.getAttrib(n, "title");
			var tempattributes = dom.getAttrib(n, 'href').split("?");
			if(tempattributes.length > 1)
			{
				var querystringparams = tempattributes[1].split("&");
				for(var i=0;i<querystringparams.length;i++)
				{
					var tempparam = querystringparams[i].split("=");
					if(tempparam.length > 1)
						pa[tempparam[0]] = tempparam[1];
				}
			}
			im.title = this._serialize(pa);
			return im;
		},
		
		createControl : function(n, cm) {
			return null;
		},
				
		getInfo : function() {
			return {
				longname : 'Advvideo plugin',
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
	tinymce.PluginManager.add('advvideo', tinymce.plugins.AdvvideoPlugin);
})();