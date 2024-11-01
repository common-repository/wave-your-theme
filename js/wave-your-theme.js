/**
 *@author QiQiBoY
 *@website http://www.qiqiboy.com
 *@version 1.2.1 | 2010-09-24
*/
(function() {
	
	var Class = {
		create: function() {
			return function() {
				this.init.apply(this, arguments);
			}
		}
	}
	var WYT=Class.create();

	WYT.prototype={

		init:function(){//初始化构造
			this.node;
			this.addListener(window,'load',this.bind(this.start,this),false);
			this.flag = false;
		},
		
		$:function(id){
			return document.getElementById(id)
		},

		$$:function(c, t, p) {
			var at = p.getElementsByTagName(t);
			var ms = new Array();
			for (var i = 0; i < at.length; i++)
				if (new RegExp("(?:^|\\s+)" + c + "(?:\\s+|$)").test(at[i].className))
					ms.push(at[i]);
			return ms;
		},
		
		isset:function(id){
			return this.$(id)||0;
		},
		
		bind:function(f,o){
			return function(){
				return f.apply(o,arguments);
			}
		},
		
		addListener:function(e, n, o, u) {
			if(e.addEventListener) {
				e.addEventListener(n, o, u);
				return true;
			} else if(e.attachEvent) {
				e['e' + n + o] = o;
				e[n + o] = function() {
					e['e' + n + o](window.event);
				};
				e.attachEvent('on' + n, e[n + o]);
				return true;
			}
			return false;
		},
		
		createxmlHttp:function() {
			var xmlHttp;
			try {
				xmlHttp = new XMLHttpRequest()
			} catch(e) {
				try {
					xmlHttp = new ActiveXObject("Microsoft.XMLHTTP")
				} catch(e) {
					try {
						xmlHttp = new ActiveXObject("Msxml2.XMLHTTP")
					} catch(e) {
						myAlert("Your browser does not support ajax!");
						return false
					}
				}
			}
			return xmlHttp
		},
		
		getStyle:function(element,property) {
			var value = element.style[property];
			if (!value) {
				if (document.defaultView && document.defaultView.getComputedStyle) {
					var css = document.defaultView.getComputedStyle(element, null);
					value = css ? css.getPropertyValue(property) : null;
				} else if (element.currentStyle) {
					value = element.currentStyle[property];
				}
			}
			return value == 'auto' ? '' : value;
		},
		
		getObjPoint:function(o){
			var x=y=0;
			do {
				x += o.offsetLeft || 0;
				y += o.offsetTop  || 0;
				o = o.offsetParent;
			} while (o);

			return {'x':x,'y':y};
		},
		
		baseurl:function(){//文件目录获取
			var baseurl="http://"+window.location.host+"/";
			var finds=document.getElementsByTagName('link');
			for(var i=0;i<finds.length;i++){
				if(finds[i].href.indexOf('wp-content')>0){
					baseurl=finds[i].href.substring(0,finds[i].href.indexOf('wp-content')+11);
					break;
				}
			}
			return baseurl;
		},
		
		start:function(){//预处理
			this.options=!window.WYT_options?{//一些配置
				image:'icons.png',
				title:'preview theme from here',
				width:22,
				height:22,
				custom:0,
				location:1,
				x:20,
				y:0,
				id:'WYT-theme',
				tips:'Theme switch was successful, the page will automatically refresh, please wait!',
				tips2:'Oops, failed to change theme.',
				tips3:'Enter the key',
				tips4:'You can\'t preview the theme!',
				zIndex:0
			}:window.WYT_options;
			if(this.isset(this.options.id)){
				this.node=this.$(this.options.id);
				this.node.onclick=this.bind(this.preview_theme,this);
				this.options.position=this.getStyle(this.node,'position')=='fixed'?'fixed':'absolute';
			}else{
				this.options.custom=0;
				this.options.position="fixed";
			};
			this.x='left';this.y='top';
			if(!this.options.custom){
				var a=document.createElement('a');
				a.id=this.options.id;
				a.href="javascript:;";
				switch(this.options.location){
					case 0: this.x='left';this.y='top';
							break;
					case 1:	this.x='right';this.y='top';
							break;
					case 2:	this.x='left';this.y='bottom';
							break;
					case 3:	this.x='right';this.y='bottom';
							break;
					default:break;
				}
				a.style[this.x]=this.options.x+'px';a.style[this.y]=this.options.y+'px';
				a.style.position=this.options.position;
				a.style.width=this.options.width+'px';a.style.height=this.options.height+'px';
				a.style.display='block';if(this.options.zIndex)a.style.zIndex=''+this.options.zIndex;
				a.title=this.options.title;
				a.style.background='url('+this.baseurl()+'plugins/wave-your-theme/img/'+this.options.image+') no-repeat';
				a.style.backgroundPosition=this.options.image=='icons.png'?'0 -38px':'center center';
				document.body.appendChild(a);
				this.node=a;
				this.node.onclick=this.bind(this.preview_theme,this);
				
			}
		},
		
		preview_theme:function(){//向服务器查询主题列表
			this.node.onclick=null;//去除事件绑定，防止再次点击
			var xmlHttp = this.createxmlHttp(),
				url = '?action=WYT_getAllThemes';
			this.node.style.background='url('+this.baseurl()+'plugins/wave-your-theme/img/loading.gif) no-repeat center center';
			xmlHttp.open("GET", url, true);
			xmlHttp.setRequestHeader("Content-type", "charset=UTF-8");
			xmlHttp.onreadystatechange = this.bind(function() {
				if (xmlHttp.readyState == 4 || xmlHttp.readyState=="complete") {
					if (xmlHttp.status == 200) {
						var _json=eval("("+xmlHttp.responseText+")");
						var data='<ol>';
						for(var i=0;i<_json.length;i++){
							data+='<li><a class="WYT_pr_theme '+_json[i].name+'" href="'+_json[i].href+'">'+_json[i].name+'</a></li>';
						}
						data+='</ol>';
						this.loadAlltheme(data);
						this.node.onclick=this.bind(function(){//绑定另一个动作
								this.togglethemepanel('WYT_themepanel')
							},this);
						this.addThemeChange();
						this.flag=true;
					}else{
						alert(this.options.tips2);
						this.node.onclick=this.bind(this.preview_theme,this);//数据获取失败就重新绑定主题获取函数
					}
					this.node.style.background='url('+this.baseurl()+'plugins/wave-your-theme/img/'+this.options.image+') no-repeat';
					this.node.style.backgroundPosition=this.options.image=='icons.png'?'0 -38px':'center center';
				}
			},this);
			xmlHttp.send(null);
		},
		
		addThemeChange:function(){//给生成的主题列表绑定事件
			var themes=this.$$('WYT_pr_theme','a',this.$('WYT_themepanel'));
			for(var i=0;i<themes.length;i++){
				(this.bind(function(){
					var _i=i;
					themes[_i].onclick=this.bind(function(){
						this.setTheme(themes[_i]);
						return false;
					},this);
				},this))();
			}
		},
		
		setTheme:function (a,key){//向服务器发送请求，切换主题
			var xmlHttp = this.createxmlHttp(),
				url = '?action=WYT_set_theme&WYT_theme='+a.className.replace(/\s|WYT_pr_theme|active/gi,'')+(key?'&key='+key:'');
			a.className+=' active';
			xmlHttp.open("GET", url, true);
			xmlHttp.setRequestHeader("Content-type", "charset=UTF-8");
			xmlHttp.onreadystatechange = this.bind(function() {
				if (xmlHttp.readyState == 4 || xmlHttp.readyState=="complete") {
					if (xmlHttp.status == 200) {
						alert(this.options.tips);
						window.location.href=window.location.href.replace(/(\?preview_theme=|#|\?wptheme=).*/i,'');
					}else if(xmlHttp.status == 403){
						alert(xmlHttp.responseText);
						var key = prompt(this.options.tips3, '***');
						if(key)this.setTheme(a,key);
						else alert(this.options.tips4);
					}else{
						alert(xmlHttp.responseText);
					}
					a.className=a.className.replace(/\sactive/gi,'');
				}
			},this);
			xmlHttp.send(null);
		},
		
		loadAlltheme:function(data){
			var r=document.createElement('div'),a=this.getObjPoint(this.node),b={x:this.node.offsetWidth,y:this.node.offsetHeight};
			r.id="WYT_themepanel";
			r.innerHTML=data+'<div class="WYT_arrow"></div><div class="WYT_closethemepanel"><a id="WYT_closethemepanel" href="javascript:;">X</a></div>';
			document.body.appendChild(r);
			r.style.position=this.options.position;
			this.$('WYT_closethemepanel').onclick=this.bind(function(){this.togglethemepanel('WYT_themepanel')},this);
			var l={x:document.documentElement.clientWidth||document.body.clientWidth,y:document.documentElement.clientHeight||document.body.clientHeight};
			r.className=this.x+' '+this.y;
			r.style[this.x]=(this.x=='left'?a.x+b.x/2-20:l.x-a.x-b.x/2-20)+'px';
			r.style[this.y]=(this.y=='top'?a.y+b.y+13:l.y-a.y+13)+'px';		
		},
		
		togglethemepanel:function (id){//控制面板的显隐
			this.$(id).style.visibility=='hidden'?this.$(id).style.visibility='visible':this.$(id).style.visibility='hidden';
		}
	}
	new WYT();
})();