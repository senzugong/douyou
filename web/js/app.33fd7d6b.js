(function(e){function n(n){for(var r,a,c=n[0],i=n[1],s=n[2],l=0,f=[];l<c.length;l++)a=c[l],o[a]&&f.push(o[a][0]),o[a]=0;for(r in i)Object.prototype.hasOwnProperty.call(i,r)&&(e[r]=i[r]);d&&d(n);while(f.length)f.shift()();return u.push.apply(u,s||[]),t()}function t(){for(var e,n=0;n<u.length;n++){for(var t=u[n],r=!0,a=1;a<t.length;a++){var c=t[a];0!==o[c]&&(r=!1)}r&&(u.splice(n--,1),e=i(i.s=t[0]))}return e}var r={},a={app:0},o={app:0},u=[];function c(e){return i.p+"js/"+({}[e]||e)+"."+{"chunk-02cb7e0c":"db2c4282","chunk-0852a5b8":"18e167e5","chunk-0c04fefa":"95dca50d","chunk-1ae6a39a":"f3d71459","chunk-1df8cdf0":"e51a1955","chunk-27ed77bb":"50459455","chunk-28933c3f":"ba76dbc9","chunk-2d0d643a":"1dc71835","chunk-2d22d746":"547042d7","chunk-3ffe4a0e":"c5a99f6c","chunk-4620af4c":"9c6dac97","chunk-08ba6a04":"5242932a","chunk-ef4fbf7a":"54746fa6","chunk-7079b99f":"73ba9947"}[e]+".js"}function i(n){if(r[n])return r[n].exports;var t=r[n]={i:n,l:!1,exports:{}};return e[n].call(t.exports,t,t.exports,i),t.l=!0,t.exports}i.e=function(e){var n=[],t={"chunk-02cb7e0c":1,"chunk-0852a5b8":1,"chunk-0c04fefa":1,"chunk-1ae6a39a":1,"chunk-1df8cdf0":1,"chunk-27ed77bb":1,"chunk-28933c3f":1,"chunk-3ffe4a0e":1,"chunk-4620af4c":1,"chunk-08ba6a04":1,"chunk-ef4fbf7a":1,"chunk-7079b99f":1};a[e]?n.push(a[e]):0!==a[e]&&t[e]&&n.push(a[e]=new Promise(function(n,t){for(var r="css/"+({}[e]||e)+"."+{"chunk-02cb7e0c":"6dbea885","chunk-0852a5b8":"96232501","chunk-0c04fefa":"4716dc8b","chunk-1ae6a39a":"9ce291e6","chunk-1df8cdf0":"09a039ab","chunk-27ed77bb":"b5f2bc5f","chunk-28933c3f":"7bdcb346","chunk-2d0d643a":"31d6cfe0","chunk-2d22d746":"31d6cfe0","chunk-3ffe4a0e":"6a7063eb","chunk-4620af4c":"935d6af5","chunk-08ba6a04":"2aa24996","chunk-ef4fbf7a":"16ebbd4e","chunk-7079b99f":"4f3a0205"}[e]+".css",o=i.p+r,u=document.getElementsByTagName("link"),c=0;c<u.length;c++){var s=u[c],l=s.getAttribute("data-href")||s.getAttribute("href");if("stylesheet"===s.rel&&(l===r||l===o))return n()}var f=document.getElementsByTagName("style");for(c=0;c<f.length;c++){s=f[c],l=s.getAttribute("data-href");if(l===r||l===o)return n()}var d=document.createElement("link");d.rel="stylesheet",d.type="text/css",d.onload=n,d.onerror=function(n){var r=n&&n.target&&n.target.src||o,u=new Error("Loading CSS chunk "+e+" failed.\n("+r+")");u.request=r,delete a[e],d.parentNode.removeChild(d),t(u)},d.href=o;var h=document.getElementsByTagName("head")[0];h.appendChild(d)}).then(function(){a[e]=0}));var r=o[e];if(0!==r)if(r)n.push(r[2]);else{var u=new Promise(function(n,t){r=o[e]=[n,t]});n.push(r[2]=u);var s,l=document.createElement("script");l.charset="utf-8",l.timeout=120,i.nc&&l.setAttribute("nonce",i.nc),l.src=c(e),s=function(n){l.onerror=l.onload=null,clearTimeout(f);var t=o[e];if(0!==t){if(t){var r=n&&("load"===n.type?"missing":n.type),a=n&&n.target&&n.target.src,u=new Error("Loading chunk "+e+" failed.\n("+r+": "+a+")");u.type=r,u.request=a,t[1](u)}o[e]=void 0}};var f=setTimeout(function(){s({type:"timeout",target:l})},12e4);l.onerror=l.onload=s,document.head.appendChild(l)}return Promise.all(n)},i.m=e,i.c=r,i.d=function(e,n,t){i.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:t})},i.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,n){if(1&n&&(e=i(e)),8&n)return e;if(4&n&&"object"===typeof e&&e&&e.__esModule)return e;var t=Object.create(null);if(i.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var r in e)i.d(t,r,function(n){return e[n]}.bind(null,r));return t},i.n=function(e){var n=e&&e.__esModule?function(){return e["default"]}:function(){return e};return i.d(n,"a",n),n},i.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},i.p="",i.oe=function(e){throw console.error(e),e};var s=window["webpackJsonp"]=window["webpackJsonp"]||[],l=s.push.bind(s);s.push=n,s=s.slice();for(var f=0;f<s.length;f++)n(s[f]);var d=l;u.push([0,"chunk-vendors"]),t()})({0:function(e,n,t){e.exports=t("56d7")},"034f":function(e,n,t){"use strict";var r=t("64a9"),a=t.n(r);a.a},"164e":function(e,n){e.exports=echarts},3089:function(e,n){e.exports=vant},"56d7":function(e,n,t){"use strict";t.r(n);t("cadf"),t("551c"),t("f751"),t("097d");var r=function(){var e=this,n=e.$createElement,t=e._self._c||n;return t("div",{attrs:{id:"app"}},[t("keep-alive",[e.$route.meta.keepAlive?t("router-view"):e._e()],1),e.$route.meta.keepAlive?e._e():t("router-view")],1)},a=[],o=(t("034f"),t("2877")),u={},c=Object(o["a"])(u,r,a,!1,null,null,null),i=c.exports,s=new Vuex.Store({state:{user_info:{dw_money:"",token:"",pay_password:"",user_id:"",free_password:""}},mutations:{changeUserInfo:function(e,n){e.user_info=n}},actions:{}}),l=new VueRouter({routes:[{path:"",redirect:"home"},{path:"/home/:id?",name:"home",component:function(){return Promise.all([t.e("chunk-4620af4c"),t.e("chunk-08ba6a04")]).then(t.bind(null,"bb51"))},meta:{keepAlive:!0}},{path:"/homeList",name:"homeList",component:function(){return t.e("chunk-3ffe4a0e").then(t.bind(null,"3570"))},meta:{keepAlive:!1}},{path:"/homeRule",name:"homeRule",component:function(){return t.e("chunk-7079b99f").then(t.bind(null,"71d9"))},meta:{keepAlive:!1}},{path:"/guess/:id?",name:"guess",component:function(){return Promise.all([t.e("chunk-4620af4c"),t.e("chunk-ef4fbf7a")]).then(t.bind(null,"33a8"))},meta:{keepAlive:!0}},{path:"/guessList",name:"guessList",component:function(){return t.e("chunk-2d0d643a").then(t.bind(null,"728a"))},meta:{keepAlive:!1}},{path:"/guessRule",name:"guessRule",component:function(){return t.e("chunk-02cb7e0c").then(t.bind(null,"0295"))},meta:{keepAlive:!1}},{path:"/about",name:"about",component:function(){return t.e("chunk-2d22d746").then(t.bind(null,"f820"))},meta:{keepAlive:!1}},{path:"/download",name:"download",component:function(){return t.e("chunk-1ae6a39a").then(t.bind(null,"3971"))},meta:{keepAlive:!1}},{path:"/luckDrawRule",name:"luckDrawRule",component:function(){return t.e("chunk-0852a5b8").then(t.bind(null,"67ea"))},meta:{keepAlive:!1}},{path:"/agreement",name:"agreement",component:function(){return t.e("chunk-1df8cdf0").then(t.bind(null,"a6e3"))},meta:{keepAlive:!1}},{path:"/agreement1",name:"agreement1",component:function(){return t.e("chunk-0c04fefa").then(t.bind(null,"0e78"))},meta:{keepAlive:!1}},{path:"/helpcenter",name:"helpcenter",component:function(){return t.e("chunk-27ed77bb").then(t.bind(null,"40dd"))},meta:{keepAlive:!1}},{path:"/register/:id?",name:"register",component:function(){return t.e("chunk-28933c3f").then(t.bind(null,"73cf"))},meta:{keepAlive:!1}}]});l.afterEach(function(e,n,t){window.scrollTo(0,0)});var f=l,d=(t("499a"),t("3089")),h=!0,p={showToast:function(e){Object(d["Toast"])({type:"text",message:e,duration:1500})},showLoading:function(e){e=e||"加载中...";d["Toast"].loading({mask:!0,message:e,duration:3e4,forbidClick:!0})},dismissLoading:function(){d["Toast"].clear()},showAlert:function(e,n){e=e,n=n||"系统提示";d["Dialog"].alert({title:n,message:e}).then(function(){})},randomNumber:function(e){for(var n="",t=0;t<e;t++)n+=Math.floor(10*Math.random());return n},regPhone:function(e){var n=/^[1][0-9]{10}$/;return n.test(e)},info:function(){for(var e=arguments.length,n=new Array(e),t=0;t<e;t++)n[t]=arguments[t];this.log.apply(this,["[info]","#909399","#FFFFFF"].concat(n))},success:function(){for(var e=arguments.length,n=new Array(e),t=0;t<e;t++)n[t]=arguments[t];this.log.apply(this,["[success]","#67C23A","#FFFFFF"].concat(n))},warn:function(){for(var e=arguments.length,n=new Array(e),t=0;t<e;t++)n[t]=arguments[t];this.log.apply(this,["[warning]","#E6A23C","#FFFFFF"].concat(n))},err:function(){for(var e=arguments.length,n=new Array(e),t=0;t<e;t++)n[t]=arguments[t];this.log.apply(this,["[error]","#F56C6C","#FFFFFF"].concat(n))},log:function(e,n,t){for(var r,a=arguments.length,o=new Array(a>3?a-3:0),u=3;u<a;u++)o[u-3]=arguments[u];h&&(r=window.console).log.apply(r,["%c".concat(e),"background-color: ".concat(n,"; color:").concat(t,"; padding: 2px 5px; border-radius: 2px")].concat(o))},timeFormat:function(e){var n=new Date(e),t=(n.getFullYear(),n.getMonth()+1<10?n.getMonth():n.getMonth(),n.getDate()<10?n.getDate():n.getDate(),n.getHours()<10?"0"+n.getHours():n.getHours()),r=n.getMinutes()<10?"0"+n.getMinutes():n.getMinutes();n.getSeconds(),n.getSeconds();return t+":"+r}},m=t("795b"),g=t.n(m),b="http://dy.rmq168.com/",k=t("68ae"),v=b+"api/",w=b+"web/download/douyou.apk",y="itms-services://?action=download-manifest&url="+b+"web/download/Info.plist";function _(e){return e&&e.data&&200==e.data.code&&(200===e.status||304===e.status||400===e.status)?{success:!0,data:e.data.data,message:""}:{success:!1,data:[],message:e.data.msg}}function A(e){var n="请求服务器错误，请稍后再试";return{success:!1,data:[],message:n}}axios.interceptors.request.use(function(e){return e},function(e){return g.a.reject(e)}),axios.interceptors.response.use(function(e){return e},function(e){return g.a.resolve(e.response)});var F={_HOST:v,_DO_MAN:b,download_ewm_img:k,download_android_url:w,download_ios_url:y,get:function(e){var n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:sessionStorage.getItem("memebuy_httpToken")||"";return axios({method:"get",url:e,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest",Authorization:"Bearer "+n}}).then(function(e){return _(e)}).catch(function(e){return A(e)})},post:function(e,n){arguments.length>2&&void 0!==arguments[2]?arguments[2]:sessionStorage.getItem("memebuy_httpToken");return axios({method:"post",url:e,data:n,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest","Content-Type":"application/json; charset=UTF-8"}}).then(function(e){return _(e)}).catch(function(e){return A(e)})}},x=t("ec7e"),T=t.n(x);function C(e){if(window.WebViewJavascriptBridge)return e(window.WebViewJavascriptBridge);if(window.WVJBCallbacks)return window.WVJBCallbacks.push(e);window.WVJBCallbacks=[e];var n=document.createElement("iframe");n.style.display="none",n.src="https://__bridge_loaded__",document.documentElement.appendChild(n),setTimeout(function(){document.documentElement.removeChild(n)},0)}var M={callhandler:function(e,n,t){C(function(r){r.callHandler(e,n,t)})},registerhandler:function(e,n){C(function(t){t.registerHandler(e,function(e,t){n(e,t)})})}};Vue.prototype.utils=p,Vue.prototype.http=F,Vue.use(T.a),Vue.prototype.$bridge=M,Vue.config.productionTip=!1,new Vue({store:s,router:f,render:function(e){return e(i)}}).$mount("#app")},5880:function(e,n){e.exports=Vuex},"64a9":function(e,n,t){},"68ae":function(e,n,t){e.exports=t.p+"img/download-ewm-test.80b2a92f.png"}});
//# sourceMappingURL=app.33fd7d6b.js.map