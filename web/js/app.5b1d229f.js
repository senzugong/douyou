(function(e){function n(n){for(var r,a,c=n[0],i=n[1],s=n[2],l=0,d=[];l<c.length;l++)a=c[l],o[a]&&d.push(o[a][0]),o[a]=0;for(r in i)Object.prototype.hasOwnProperty.call(i,r)&&(e[r]=i[r]);h&&h(n);while(d.length)d.shift()();return u.push.apply(u,s||[]),t()}function t(){for(var e,n=0;n<u.length;n++){for(var t=u[n],r=!0,a=1;a<t.length;a++){var c=t[a];0!==o[c]&&(r=!1)}r&&(u.splice(n--,1),e=i(i.s=t[0]))}return e}var r={},a={app:0},o={app:0},u=[];function c(e){return i.p+"js/"+({}[e]||e)+"."+{"chunk-0be695ec":"a037f83c","chunk-0c390751":"f3382124","chunk-1df8cdf0":"ab74f821","chunk-27ed77bb":"39314dec","chunk-352d818b":"8fea9ba9","chunk-35438c73":"598431b2","chunk-68414652":"479f29d4","chunk-7876b6ac":"59cbb2cc","chunk-789021ac":"4d74ccf0","chunk-7e274838":"341a566c","chunk-a1631e9e":"ec353cd7"}[e]+".js"}function i(n){if(r[n])return r[n].exports;var t=r[n]={i:n,l:!1,exports:{}};return e[n].call(t.exports,t,t.exports,i),t.l=!0,t.exports}i.e=function(e){var n=[],t={"chunk-0be695ec":1,"chunk-0c390751":1,"chunk-1df8cdf0":1,"chunk-27ed77bb":1,"chunk-352d818b":1,"chunk-35438c73":1,"chunk-68414652":1,"chunk-7876b6ac":1,"chunk-789021ac":1,"chunk-7e274838":1,"chunk-a1631e9e":1};a[e]?n.push(a[e]):0!==a[e]&&t[e]&&n.push(a[e]=new Promise(function(n,t){for(var r="css/"+({}[e]||e)+"."+{"chunk-0be695ec":"dd929b33","chunk-0c390751":"9e532ed2","chunk-1df8cdf0":"09a039ab","chunk-27ed77bb":"b5f2bc5f","chunk-352d818b":"ab0dae4f","chunk-35438c73":"c598df73","chunk-68414652":"0487ca94","chunk-7876b6ac":"7f4a86bc","chunk-789021ac":"ef0d4fa7","chunk-7e274838":"eca526a9","chunk-a1631e9e":"360b2b05"}[e]+".css",o=i.p+r,u=document.getElementsByTagName("link"),c=0;c<u.length;c++){var s=u[c],l=s.getAttribute("data-href")||s.getAttribute("href");if("stylesheet"===s.rel&&(l===r||l===o))return n()}var d=document.getElementsByTagName("style");for(c=0;c<d.length;c++){s=d[c],l=s.getAttribute("data-href");if(l===r||l===o)return n()}var h=document.createElement("link");h.rel="stylesheet",h.type="text/css",h.onload=n,h.onerror=function(n){var r=n&&n.target&&n.target.src||o,u=new Error("Loading CSS chunk "+e+" failed.\n("+r+")");u.request=r,delete a[e],h.parentNode.removeChild(h),t(u)},h.href=o;var f=document.getElementsByTagName("head")[0];f.appendChild(h)}).then(function(){a[e]=0}));var r=o[e];if(0!==r)if(r)n.push(r[2]);else{var u=new Promise(function(n,t){r=o[e]=[n,t]});n.push(r[2]=u);var s,l=document.createElement("script");l.charset="utf-8",l.timeout=120,i.nc&&l.setAttribute("nonce",i.nc),l.src=c(e),s=function(n){l.onerror=l.onload=null,clearTimeout(d);var t=o[e];if(0!==t){if(t){var r=n&&("load"===n.type?"missing":n.type),a=n&&n.target&&n.target.src,u=new Error("Loading chunk "+e+" failed.\n("+r+": "+a+")");u.type=r,u.request=a,t[1](u)}o[e]=void 0}};var d=setTimeout(function(){s({type:"timeout",target:l})},12e4);l.onerror=l.onload=s,document.head.appendChild(l)}return Promise.all(n)},i.m=e,i.c=r,i.d=function(e,n,t){i.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:t})},i.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,n){if(1&n&&(e=i(e)),8&n)return e;if(4&n&&"object"===typeof e&&e&&e.__esModule)return e;var t=Object.create(null);if(i.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var r in e)i.d(t,r,function(n){return e[n]}.bind(null,r));return t},i.n=function(e){var n=e&&e.__esModule?function(){return e["default"]}:function(){return e};return i.d(n,"a",n),n},i.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},i.p="",i.oe=function(e){throw console.error(e),e};var s=window["webpackJsonp"]=window["webpackJsonp"]||[],l=s.push.bind(s);s.push=n,s=s.slice();for(var d=0;d<s.length;d++)n(s[d]);var h=l;u.push([0,"chunk-vendors"]),t()})({0:function(e,n,t){e.exports=t("56d7")},"034f":function(e,n,t){"use strict";var r=t("64a9"),a=t.n(r);a.a},"56d7":function(e,n,t){"use strict";t.r(n);t("cadf"),t("551c"),t("f751"),t("097d");var r=t("2b0e"),a=function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",{attrs:{id:"app"}},[t("keep-alive",[e.$route.meta.keepAlive?t("router-view"):e._e()],1),e.$route.meta.keepAlive?e._e():t("router-view")],1)},o=[],u=(t("034f"),t("2877")),c={},i=Object(u["a"])(c,a,o,!1,null,null,null),s=i.exports,l=t("8c4f");r["a"].use(l["a"]);var d=new l["a"]({routes:[{path:"",redirect:"luckDraw"},{path:"/home/:id?",name:"home",component:function(){return t.e("chunk-0c390751").then(t.bind(null,"bb51"))},meta:{keepAlive:!1}},{path:"/rule",name:"rule",component:function(){return t.e("chunk-7876b6ac").then(t.bind(null,"2dd9"))},meta:{keepAlive:!1}},{path:"/luckDraw/:id?",name:"luckDraw",component:function(){return t.e("chunk-0be695ec").then(t.bind(null,"2fb0"))},meta:{keepAlive:!0}},{path:"/luckDrawRule",name:"luckDrawRule",component:function(){return t.e("chunk-789021ac").then(t.bind(null,"67ea"))},meta:{keepAlive:!1}},{path:"/agreement",name:"agreement",component:function(){return t.e("chunk-1df8cdf0").then(t.bind(null,"a6e3"))},meta:{keepAlive:!1}},{path:"/helpcenter",name:"helpcenter",component:function(){return t.e("chunk-27ed77bb").then(t.bind(null,"40dd"))},meta:{keepAlive:!1}},{path:"/about",name:"about",component:function(){return t.e("chunk-7e274838").then(t.bind(null,"f820"))},meta:{keepAlive:!1}},{path:"/list/:id?",name:"list",component:function(){return t.e("chunk-68414652").then(t.bind(null,"1a33"))},meta:{keepAlive:!1}},{path:"/numberGuess/:id?",name:"numberGuess",component:function(){return t.e("chunk-a1631e9e").then(t.bind(null,"53db"))},meta:{keepAlive:!1}},{path:"/numberGuessRule",name:"numberGuessRule",component:function(){return t.e("chunk-352d818b").then(t.bind(null,"491d"))},meta:{keepAlive:!1}},{path:"/numberGuessList",name:"numberGuessList",component:function(){return t.e("chunk-35438c73").then(t.bind(null,"0cc3"))},meta:{keepAlive:!1}}]});d.afterEach(function(e,n,t){window.scrollTo(0,0)});var h=d,f=t("2f62");r["a"].use(f["a"]);var p=new f["a"].Store({state:{user_info:{dw_money:"",token:"",pay_password:"",user_id:"",free_password:""}},mutations:{changeUserInfo:function(e,n){e.user_info=n}},actions:{}}),m=(t("499a"),t("b970")),b=(t("157a"),!0),g={showToast:function(e){Object(m["b"])({type:"text",message:e,duration:1500})},showLoading:function(e){e=e||"加载中...";m["b"].loading({mask:!0,message:e,duration:3e4,forbidClick:!0})},dismissLoading:function(){m["b"].clear()},showAlert:function(e,n){e=e,n=n||"系統提示";m["a"].alert({title:n,message:e}).then(function(){})},randomNumber:function(e){for(var n="",t=0;t<e;t++)n+=Math.floor(10*Math.random());return n},regPhone:function(e){var n=/^1[34578]\d{9}$/;return n.test(e)},info:function(){for(var e=arguments.length,n=new Array(e),t=0;t<e;t++)n[t]=arguments[t];this.log.apply(this,["[info]","#909399","#FFFFFF"].concat(n))},success:function(){for(var e=arguments.length,n=new Array(e),t=0;t<e;t++)n[t]=arguments[t];this.log.apply(this,["[success]","#67C23A","#FFFFFF"].concat(n))},warn:function(){for(var e=arguments.length,n=new Array(e),t=0;t<e;t++)n[t]=arguments[t];this.log.apply(this,["[warning]","#E6A23C","#FFFFFF"].concat(n))},err:function(){for(var e=arguments.length,n=new Array(e),t=0;t<e;t++)n[t]=arguments[t];this.log.apply(this,["[error]","#F56C6C","#FFFFFF"].concat(n))},log:function(e,n,t){for(var r,a=arguments.length,o=new Array(a>3?a-3:0),u=3;u<a;u++)o[u-3]=arguments[u];b&&(r=window.console).log.apply(r,["%c".concat(e),"background-color: ".concat(n,"; color:").concat(t,"; padding: 2px 5px; border-radius: 2px")].concat(o))},timeFormat:function(e){var n=new Date(e),t=(n.getFullYear(),n.getMonth()+1<10?n.getMonth():n.getMonth(),n.getDate()<10?n.getDate():n.getDate(),n.getHours()<10?"0"+n.getHours():n.getHours()),r=n.getMinutes()<10?"0"+n.getMinutes():n.getMinutes();n.getSeconds(),n.getSeconds();return t+":"+r}},k=t("795b"),v=t.n(k),w=t("bc3a"),y=t.n(w),F="http://dy.rmq168.com/api/";function A(e){return e&&e.data&&200==e.data.code&&(200===e.status||304===e.status||400===e.status)?{success:!0,data:e.data.data,message:""}:{success:!1,data:[],message:e.data.msg}}function _(e){var n="请求服务器错误，请稍后再试";return{success:!1,data:[],message:n}}y.a.interceptors.request.use(function(e){return e},function(e){return v.a.reject(e)}),y.a.interceptors.response.use(function(e){return e},function(e){return v.a.resolve(e.response)});var T={_HOST:F,get:function(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:sessionStorage.getItem("memebuy_httpToken")||"";return y()({method:"get",url:e,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest",Authorization:"Bearer "+n}}).then(function(e){return A(e)}).catch(function(e){return _(e)})},post:function(e,n){arguments.length>2&&void 0!==arguments[2]?arguments[2]:sessionStorage.getItem("memebuy_httpToken");return y()({method:"post",url:e,data:n,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest","Content-Type":"application/json; charset=UTF-8"}}).then(function(e){return A(e)}).catch(function(e){return _(e)})}},C=t("ec7e"),j=t.n(C);function M(e){if(window.WebViewJavascriptBridge)return e(window.WebViewJavascriptBridge);if(window.WVJBCallbacks)return window.WVJBCallbacks.push(e);window.WVJBCallbacks=[e];var n=document.createElement("iframe");n.style.display="none",n.src="https://__bridge_loaded__",document.documentElement.appendChild(n),setTimeout(function(){document.documentElement.removeChild(n)},0)}var E={callhandler:function(e,n,t){M(function(r){r.callHandler(e,n,t)})},registerhandler:function(e,n){M(function(t){t.registerHandler(e,function(e,t){n(e,t)})})}},S=t("313e"),x=t.n(S),O=t("a939"),P=t.n(O);r["a"].use(m["c"]),r["a"].prototype.utils=g,r["a"].prototype.http=T,r["a"].use(j.a),r["a"].prototype.$bridge=E,r["a"].prototype.$echarts=x.a,r["a"].use(P.a),r["a"].config.productionTip=!1,new r["a"]({router:h,store:p,render:function(e){return e(s)}}).$mount("#app")},"64a9":function(e,n,t){}});
//# sourceMappingURL=app.5b1d229f.js.map