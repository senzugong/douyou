(function(e){function t(t){for(var r,a,c=t[0],i=t[1],s=t[2],l=0,d=[];l<c.length;l++)a=c[l],o[a]&&d.push(o[a][0]),o[a]=0;for(r in i)Object.prototype.hasOwnProperty.call(i,r)&&(e[r]=i[r]);f&&f(t);while(d.length)d.shift()();return u.push.apply(u,s||[]),n()}function n(){for(var e,t=0;t<u.length;t++){for(var n=u[t],r=!0,a=1;a<n.length;a++){var c=n[a];0!==o[c]&&(r=!1)}r&&(u.splice(t--,1),e=i(i.s=n[0]))}return e}var r={},a={app:0},o={app:0},u=[];function c(e){return i.p+"js/"+({}[e]||e)+"."+{"chunk-21fac5bb":"1c222bec","chunk-4da0716e":"5824027b","chunk-508967a2":"6998220e","chunk-59289a02":"455ade66","chunk-bc6a7e1a":"7fb50d6e","chunk-db7d927c":"376ecbb5"}[e]+".js"}function i(t){if(r[t])return r[t].exports;var n=r[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,i),n.l=!0,n.exports}i.e=function(e){var t=[],n={"chunk-21fac5bb":1,"chunk-4da0716e":1,"chunk-508967a2":1,"chunk-59289a02":1,"chunk-bc6a7e1a":1,"chunk-db7d927c":1};a[e]?t.push(a[e]):0!==a[e]&&n[e]&&t.push(a[e]=new Promise(function(t,n){for(var r="css/"+({}[e]||e)+"."+{"chunk-21fac5bb":"d2a730db","chunk-4da0716e":"53605ecc","chunk-508967a2":"ae32a23e","chunk-59289a02":"49317d00","chunk-bc6a7e1a":"c03629c7","chunk-db7d927c":"09a039ab"}[e]+".css",o=i.p+r,u=document.getElementsByTagName("link"),c=0;c<u.length;c++){var s=u[c],l=s.getAttribute("data-href")||s.getAttribute("href");if("stylesheet"===s.rel&&(l===r||l===o))return t()}var d=document.getElementsByTagName("style");for(c=0;c<d.length;c++){s=d[c],l=s.getAttribute("data-href");if(l===r||l===o)return t()}var f=document.createElement("link");f.rel="stylesheet",f.type="text/css",f.onload=t,f.onerror=function(t){var r=t&&t.target&&t.target.src||o,u=new Error("Loading CSS chunk "+e+" failed.\n("+r+")");u.request=r,delete a[e],f.parentNode.removeChild(f),n(u)},f.href=o;var p=document.getElementsByTagName("head")[0];p.appendChild(f)}).then(function(){a[e]=0}));var r=o[e];if(0!==r)if(r)t.push(r[2]);else{var u=new Promise(function(t,n){r=o[e]=[t,n]});t.push(r[2]=u);var s,l=document.createElement("script");l.charset="utf-8",l.timeout=120,i.nc&&l.setAttribute("nonce",i.nc),l.src=c(e),s=function(t){l.onerror=l.onload=null,clearTimeout(d);var n=o[e];if(0!==n){if(n){var r=t&&("load"===t.type?"missing":t.type),a=t&&t.target&&t.target.src,u=new Error("Loading chunk "+e+" failed.\n("+r+": "+a+")");u.type=r,u.request=a,n[1](u)}o[e]=void 0}};var d=setTimeout(function(){s({type:"timeout",target:l})},12e4);l.onerror=l.onload=s,document.head.appendChild(l)}return Promise.all(t)},i.m=e,i.c=r,i.d=function(e,t,n){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},i.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)i.d(n,r,function(t){return e[t]}.bind(null,r));return n},i.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="",i.oe=function(e){throw console.error(e),e};var s=window["webpackJsonp"]=window["webpackJsonp"]||[],l=s.push.bind(s);s.push=t,s=s.slice();for(var d=0;d<s.length;d++)t(s[d]);var f=l;u.push([0,"chunk-vendors"]),n()})({0:function(e,t,n){e.exports=n("56d7")},"034f":function(e,t,n){"use strict";var r=n("64a9"),a=n.n(r);a.a},"56d7":function(e,t,n){"use strict";n.r(t);n("cadf"),n("551c"),n("f751"),n("097d");var r=n("2b0e"),a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{attrs:{id:"app"}},[n("keep-alive",[e.$route.meta.keepAlive?n("router-view"):e._e()],1),e.$route.meta.keepAlive?e._e():n("router-view")],1)},o=[],u=(n("034f"),n("2877")),c={},i=Object(u["a"])(c,a,o,!1,null,null,null),s=i.exports,l=n("8c4f");r["a"].use(l["a"]);var d=new l["a"]({routes:[{path:"",redirect:"luckDraw"},{path:"/home/:id?",name:"home",component:function(){return n.e("chunk-508967a2").then(n.bind(null,"bb51"))},meta:{keepAlive:!0}},{path:"/rule",name:"rule",component:function(){return n.e("chunk-59289a02").then(n.bind(null,"2dd9"))},meta:{keepAlive:!1}},{path:"/luckDraw/:id?",name:"luckDraw",component:function(){return n.e("chunk-bc6a7e1a").then(n.bind(null,"2fb0"))},meta:{keepAlive:!0}},{path:"/luckDrawRule",name:"luckDrawRule",component:function(){return n.e("chunk-21fac5bb").then(n.bind(null,"67ea"))},meta:{keepAlive:!1}},{path:"/agreement",name:"agreement",component:function(){return n.e("chunk-db7d927c").then(n.bind(null,"a6e3"))},meta:{keepAlive:!1}},{path:"/about",name:"about",component:function(){return n.e("chunk-4da0716e").then(n.bind(null,"f820"))},meta:{keepAlive:!1}}]});d.afterEach(function(e,t,n){window.scrollTo(0,0)});var f=d,p=n("2f62");r["a"].use(p["a"]);var h=new p["a"].Store({state:{user_info:{dw_money:"",token:"",pay_password:"",user_id:"",free_password:""}},mutations:{changeUserInfo:function(e,t){e.user_info=t}},actions:{}}),g=(n("499a"),n("b970")),m=(n("157a"),!0),b={showToast:function(e){Object(g["b"])({type:"text",message:e,duration:1500})},showLoading:function(e){e=e||"加载中...";g["b"].loading({mask:!0,message:e,duration:3e4,forbidClick:!0})},dismissLoading:function(){g["b"].clear()},showAlert:function(e,t){e=e,t=t||"系統提示";g["a"].alert({title:t,message:e}).then(function(){})},randomNumber:function(e){for(var t="",n=0;n<e;n++)t+=Math.floor(10*Math.random());return t},regPhone:function(e){var t=/^1[34578]\d{9}$/;return t.test(e)},info:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[info]","#909399","#FFFFFF"].concat(t))},success:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[success]","#67C23A","#FFFFFF"].concat(t))},warn:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[warning]","#E6A23C","#FFFFFF"].concat(t))},err:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[error]","#F56C6C","#FFFFFF"].concat(t))},log:function(e,t,n){for(var r,a=arguments.length,o=new Array(a>3?a-3:0),u=3;u<a;u++)o[u-3]=arguments[u];m&&(r=window.console).log.apply(r,["%c".concat(e),"background-color: ".concat(t,"; color:").concat(n,"; padding: 2px 5px; border-radius: 2px")].concat(o))},timeFormat:function(e){var t=new Date(e),n=t.getFullYear(),r=t.getMonth()+1<10?"0"+(t.getMonth()+1):t.getMonth()+1,a=t.getDate()<10?"0"+t.getDate():t.getDate(),o=t.getHours()<10?"0"+t.getHours():t.getHours(),u=t.getMinutes()<10?"0"+t.getMinutes():t.getMinutes(),c=t.getSeconds()<10?"0"+t.getSeconds():t.getSeconds();return n+"-"+r+"-"+a+" "+o+":"+u+":"+c}},v=n("795b"),k=n.n(v),w=n("bc3a"),y=n.n(w),F="http://dy.rmq168.com/api/";function A(e){return e&&e.data&&200==e.data.code&&(200===e.status||304===e.status||400===e.status)?{success:!0,data:e.data.data,message:""}:{success:!1,data:[],message:e.data.msg}}function _(e){var t="请求服务器错误，请稍后再试";return{success:!1,data:[],message:t}}y.a.interceptors.request.use(function(e){return e},function(e){return k.a.reject(e)}),y.a.interceptors.response.use(function(e){return e},function(e){return k.a.resolve(e.response)});var T={_HOST:F,get:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:sessionStorage.getItem("memebuy_httpToken")||"";return y()({method:"get",url:e,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest",Authorization:"Bearer "+t}}).then(function(e){return A(e)}).catch(function(e){return _(e)})},post:function(e,t){arguments.length>2&&void 0!==arguments[2]?arguments[2]:sessionStorage.getItem("memebuy_httpToken");return y()({method:"post",url:e,data:t,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest","Content-Type":"application/json; charset=UTF-8"}}).then(function(e){return A(e)}).catch(function(e){return _(e)})}},j=n("ec7e"),M=n.n(j);function S(e){return window.WebViewJavascriptBridge?e(window.WebViewJavascriptBridge):window.WVJBCallbacks?window.WVJBCallbacks.push(e):void(window.WVJBCallbacks=[e])}var C={callhandler:function(e,t,n){S(function(r){r.callHandler(e,t,n)})},registerhandler:function(e,t){S(function(n){n.registerHandler(e,function(e,n){t(e,n)})})}},x=n("313e"),O=n.n(x),E=n("a939"),P=n.n(E);r["a"].use(g["c"]),r["a"].prototype.utils=b,r["a"].prototype.http=T,r["a"].use(M.a),r["a"].prototype.$bridge=C,r["a"].prototype.$echarts=O.a,r["a"].use(P.a),r["a"].config.productionTip=!1,new r["a"]({router:f,store:h,render:function(e){return e(s)}}).$mount("#app")},"64a9":function(e,t,n){}});
//# sourceMappingURL=app.051b2116.js.map