(function(e){function t(t){for(var r,o,i=t[0],c=t[1],s=t[2],l=0,f=[];l<i.length;l++)o=i[l],a[o]&&f.push(a[o][0]),a[o]=0;for(r in c)Object.prototype.hasOwnProperty.call(c,r)&&(e[r]=c[r]);d&&d(t);while(f.length)f.shift()();return u.push.apply(u,s||[]),n()}function n(){for(var e,t=0;t<u.length;t++){for(var n=u[t],r=!0,o=1;o<n.length;o++){var i=n[o];0!==a[i]&&(r=!1)}r&&(u.splice(t--,1),e=c(c.s=n[0]))}return e}var r={},o={app:0},a={app:0},u=[];function i(e){return c.p+"js/"+({}[e]||e)+"."+{"chunk-2eb13bd2":"ab529eee","chunk-716a9768":"4c1396a2"}[e]+".js"}function c(t){if(r[t])return r[t].exports;var n=r[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,c),n.l=!0,n.exports}c.e=function(e){var t=[],n={"chunk-2eb13bd2":1,"chunk-716a9768":1};o[e]?t.push(o[e]):0!==o[e]&&n[e]&&t.push(o[e]=new Promise(function(t,n){for(var r="css/"+({}[e]||e)+"."+{"chunk-2eb13bd2":"1211c862","chunk-716a9768":"30fd035f"}[e]+".css",a=c.p+r,u=document.getElementsByTagName("link"),i=0;i<u.length;i++){var s=u[i],l=s.getAttribute("data-href")||s.getAttribute("href");if("stylesheet"===s.rel&&(l===r||l===a))return t()}var f=document.getElementsByTagName("style");for(i=0;i<f.length;i++){s=f[i],l=s.getAttribute("data-href");if(l===r||l===a)return t()}var d=document.createElement("link");d.rel="stylesheet",d.type="text/css",d.onload=t,d.onerror=function(t){var r=t&&t.target&&t.target.src||a,u=new Error("Loading CSS chunk "+e+" failed.\n("+r+")");u.request=r,delete o[e],d.parentNode.removeChild(d),n(u)},d.href=a;var p=document.getElementsByTagName("head")[0];p.appendChild(d)}).then(function(){o[e]=0}));var r=a[e];if(0!==r)if(r)t.push(r[2]);else{var u=new Promise(function(t,n){r=a[e]=[t,n]});t.push(r[2]=u);var s,l=document.createElement("script");l.charset="utf-8",l.timeout=120,c.nc&&l.setAttribute("nonce",c.nc),l.src=i(e),s=function(t){l.onerror=l.onload=null,clearTimeout(f);var n=a[e];if(0!==n){if(n){var r=t&&("load"===t.type?"missing":t.type),o=t&&t.target&&t.target.src,u=new Error("Loading chunk "+e+" failed.\n("+r+": "+o+")");u.type=r,u.request=o,n[1](u)}a[e]=void 0}};var f=setTimeout(function(){s({type:"timeout",target:l})},12e4);l.onerror=l.onload=s,document.head.appendChild(l)}return Promise.all(t)},c.m=e,c.c=r,c.d=function(e,t,n){c.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},c.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},c.t=function(e,t){if(1&t&&(e=c(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(c.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)c.d(n,r,function(t){return e[t]}.bind(null,r));return n},c.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return c.d(t,"a",t),t},c.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},c.p="",c.oe=function(e){throw console.error(e),e};var s=window["webpackJsonp"]=window["webpackJsonp"]||[],l=s.push.bind(s);s.push=t,s=s.slice();for(var f=0;f<s.length;f++)t(s[f]);var d=l;u.push([0,"chunk-vendors"]),n()})({0:function(e,t,n){e.exports=n("56d7")},"034f":function(e,t,n){"use strict";var r=n("64a9"),o=n.n(r);o.a},"56d7":function(e,t,n){"use strict";n.r(t);n("cadf"),n("551c"),n("f751"),n("097d");var r=n("2b0e"),o=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{attrs:{id:"app"}},[n("router-view")],1)},a=[],u=(n("034f"),n("2877")),i={},c=Object(u["a"])(i,o,a,!1,null,null,null),s=c.exports,l=n("8c4f");r["a"].use(l["a"]);var f=new l["a"]({routes:[{path:"",redirect:"home"},{path:"/home/:id?",name:"home",component:function(){return n.e("chunk-2eb13bd2").then(n.bind(null,"bb51"))}},{path:"/rule",name:"rule",component:function(){return n.e("chunk-716a9768").then(n.bind(null,"2dd9"))}}]});f.afterEach(function(e,t,n){window.scrollTo(0,0)});var d=f,p=n("2f62");r["a"].use(p["a"]);var h=new p["a"].Store({state:{},mutations:{},actions:{}}),g=(n("499a"),n("b970")),m=(n("157a"),!0),v={showToast:function(e){Object(g["b"])({type:"text",message:e,duration:1500})},showLoading:function(e){e=e||"加载中...";g["b"].loading({mask:!0,message:e,duration:3e4,forbidClick:!0})},dismissLoading:function(){g["b"].clear()},showAlert:function(e,t){e=e,t=t||"系統提示";g["a"].alert({title:t,message:e}).then(function(){})},regPhone:function(e){var t=/^1[34578]\d{9}$/;return t.test(e)},info:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[info]","#909399","#FFFFFF"].concat(t))},success:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[success]","#67C23A","#FFFFFF"].concat(t))},warn:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[warning]","#E6A23C","#FFFFFF"].concat(t))},err:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[error]","#F56C6C","#FFFFFF"].concat(t))},log:function(e,t,n){for(var r,o=arguments.length,a=new Array(o>3?o-3:0),u=3;u<o;u++)a[u-3]=arguments[u];m&&(r=window.console).log.apply(r,["%c".concat(e),"background-color: ".concat(t,"; color:").concat(n,"; padding: 2px 5px; border-radius: 2px")].concat(a))}},b=n("795b"),w=n.n(b),y=n("bc3a"),F=n.n(y),k="https://services.memecoins.com.tw/activityManagerApi/public/api/";function A(e){return e&&e.data&&e.data.data&&(200===e.status||304===e.status||400===e.status)?{success:!0,data:e.data.data,message:""}:{success:!1,data:[],message:"請求服務器錯誤,請稍後再試"}}function T(e){var t="";return t=e.response&&e.response.data&&e.response.data.error_msg&&""!=e.response.data.error_msg?e.response.data.error_msg:"請求服務器出錯，請稍後再試",{success:!1,data:[],message:t}}F.a.interceptors.request.use(function(e){return e},function(e){return w.a.reject(e)}),F.a.interceptors.response.use(function(e){return e},function(e){return w.a.resolve(e.response)});var j={_HOST:k,get:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:sessionStorage.getItem("memebuy_httpToken")||"";return F()({method:"get",url:e,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest",Authorization:"Bearer "+t}}).then(function(e){return A(e)}).catch(function(e){return T(e)})},getBtn:function(e){return F()({method:"get",url:e,timeout:1e4}).then(function(e){return A(e)}).catch(function(e){return T(e)})},post:function(e,t){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:sessionStorage.getItem("memebuy_httpToken")||"";return F()({method:"post",url:e,data:t,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest","Content-Type":"application/json; charset=UTF-8",Authorization:"Bearer "+n}}).then(function(e){return A(e)}).catch(function(e){return T(e)})}};function _(e){return window.WebViewJavascriptBridge?e(window.WebViewJavascriptBridge):window.WVJBCallbacks?window.WVJBCallbacks.push(e):void(window.WVJBCallbacks=[e])}var C={callhandler:function(e,t,n){_(function(r){r.callHandler(e,t,n)})},registerhandler:function(e,t){_(function(n){n.registerHandler(e,function(e,n){t(e,n)})})}},x=n("313e"),O=n.n(x);r["a"].use(g["c"]),r["a"].prototype.utils=v,r["a"].prototype.http=j,r["a"].prototype.$bridge=C,r["a"].prototype.$echarts=O.a,r["a"].config.productionTip=!1,new r["a"]({router:d,store:h,render:function(e){return e(s)}}).$mount("#app")},"64a9":function(e,t,n){}});
//# sourceMappingURL=app.5717d427.js.map