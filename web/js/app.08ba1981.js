(function(e){function t(t){for(var r,o,c=t[0],i=t[1],s=t[2],d=0,l=[];d<c.length;d++)o=c[d],a[o]&&l.push(a[o][0]),a[o]=0;for(r in i)Object.prototype.hasOwnProperty.call(i,r)&&(e[r]=i[r]);f&&f(t);while(l.length)l.shift()();return u.push.apply(u,s||[]),n()}function n(){for(var e,t=0;t<u.length;t++){for(var n=u[t],r=!0,o=1;o<n.length;o++){var c=n[o];0!==a[c]&&(r=!1)}r&&(u.splice(t--,1),e=i(i.s=n[0]))}return e}var r={},o={app:0},a={app:0},u=[];function c(e){return i.p+"js/"+({}[e]||e)+"."+{"chunk-0852a5b8":"18e167e5","chunk-0d72d152":"7f8bb18c","chunk-1df8cdf0":"e51a1955","chunk-253dc9b7":"b6aaa743","chunk-27ed77bb":"50459455","chunk-2d22d746":"547042d7","chunk-7079b99f":"73ba9947","chunk-ebea5ac0":"fdde2609"}[e]+".js"}function i(t){if(r[t])return r[t].exports;var n=r[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,i),n.l=!0,n.exports}i.e=function(e){var t=[],n={"chunk-0852a5b8":1,"chunk-0d72d152":1,"chunk-1df8cdf0":1,"chunk-253dc9b7":1,"chunk-27ed77bb":1,"chunk-7079b99f":1,"chunk-ebea5ac0":1};o[e]?t.push(o[e]):0!==o[e]&&n[e]&&t.push(o[e]=new Promise(function(t,n){for(var r="css/"+({}[e]||e)+"."+{"chunk-0852a5b8":"96232501","chunk-0d72d152":"fd204a94","chunk-1df8cdf0":"09a039ab","chunk-253dc9b7":"eb93c57c","chunk-27ed77bb":"b5f2bc5f","chunk-2d22d746":"31d6cfe0","chunk-7079b99f":"4f3a0205","chunk-ebea5ac0":"1ad24eb8"}[e]+".css",a=i.p+r,u=document.getElementsByTagName("link"),c=0;c<u.length;c++){var s=u[c],d=s.getAttribute("data-href")||s.getAttribute("href");if("stylesheet"===s.rel&&(d===r||d===a))return t()}var l=document.getElementsByTagName("style");for(c=0;c<l.length;c++){s=l[c],d=s.getAttribute("data-href");if(d===r||d===a)return t()}var f=document.createElement("link");f.rel="stylesheet",f.type="text/css",f.onload=t,f.onerror=function(t){var r=t&&t.target&&t.target.src||a,u=new Error("Loading CSS chunk "+e+" failed.\n("+r+")");u.request=r,delete o[e],f.parentNode.removeChild(f),n(u)},f.href=a;var h=document.getElementsByTagName("head")[0];h.appendChild(f)}).then(function(){o[e]=0}));var r=a[e];if(0!==r)if(r)t.push(r[2]);else{var u=new Promise(function(t,n){r=a[e]=[t,n]});t.push(r[2]=u);var s,d=document.createElement("script");d.charset="utf-8",d.timeout=120,i.nc&&d.setAttribute("nonce",i.nc),d.src=c(e),s=function(t){d.onerror=d.onload=null,clearTimeout(l);var n=a[e];if(0!==n){if(n){var r=t&&("load"===t.type?"missing":t.type),o=t&&t.target&&t.target.src,u=new Error("Loading chunk "+e+" failed.\n("+r+": "+o+")");u.type=r,u.request=o,n[1](u)}a[e]=void 0}};var l=setTimeout(function(){s({type:"timeout",target:d})},12e4);d.onerror=d.onload=s,document.head.appendChild(d)}return Promise.all(t)},i.m=e,i.c=r,i.d=function(e,t,n){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},i.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)i.d(n,r,function(t){return e[t]}.bind(null,r));return n},i.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="",i.oe=function(e){throw console.error(e),e};var s=window["webpackJsonp"]=window["webpackJsonp"]||[],d=s.push.bind(s);s.push=t,s=s.slice();for(var l=0;l<s.length;l++)t(s[l]);var f=d;u.push([0,"chunk-vendors"]),n()})({0:function(e,t,n){e.exports=n("56d7")},"034f":function(e,t,n){"use strict";var r=n("64a9"),o=n.n(r);o.a},"164e":function(e,t){e.exports=echarts},3089:function(e,t){e.exports=vant},"56d7":function(e,t,n){"use strict";n.r(t);n("cadf"),n("551c"),n("f751"),n("097d");var r=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{attrs:{id:"app"}},[n("keep-alive",[e.$route.meta.keepAlive?n("router-view"):e._e()],1),e.$route.meta.keepAlive?e._e():n("router-view")],1)},o=[],a=(n("034f"),n("2877")),u={},c=Object(a["a"])(u,r,o,!1,null,null,null),i=c.exports,s=new Vuex.Store({state:{user_info:{dw_money:"",token:"",pay_password:"",user_id:"",free_password:""}},mutations:{changeUserInfo:function(e,t){e.user_info=t}},actions:{}}),d=new VueRouter({routes:[{path:"",redirect:"home"},{path:"/home/:id?",name:"home",component:function(){return n.e("chunk-ebea5ac0").then(n.bind(null,"bb51"))},meta:{keepAlive:!0}},{path:"/homeList",name:"homeList",component:function(){return n.e("chunk-253dc9b7").then(n.bind(null,"3570"))},meta:{keepAlive:!1}},{path:"/homeRule",name:"homeRule",component:function(){return n.e("chunk-7079b99f").then(n.bind(null,"71d9"))},meta:{keepAlive:!1}},{path:"/about",name:"about",component:function(){return n.e("chunk-2d22d746").then(n.bind(null,"f820"))},meta:{keepAlive:!1}},{path:"/download",name:"download",component:function(){return n.e("chunk-0d72d152").then(n.bind(null,"3971"))},meta:{keepAlive:!1}},{path:"/luckDrawRule",name:"luckDrawRule",component:function(){return n.e("chunk-0852a5b8").then(n.bind(null,"67ea"))},meta:{keepAlive:!1}},{path:"/agreement",name:"agreement",component:function(){return n.e("chunk-1df8cdf0").then(n.bind(null,"a6e3"))},meta:{keepAlive:!1}},{path:"/helpcenter",name:"helpcenter",component:function(){return n.e("chunk-27ed77bb").then(n.bind(null,"40dd"))},meta:{keepAlive:!1}}]});d.afterEach(function(e,t,n){window.scrollTo(0,0)});var l=d,f=(n("499a"),n("3089")),h=!0,p={showToast:function(e){Object(f["Toast"])({type:"text",message:e,duration:1500})},showLoading:function(e){e=e||"加载中...";f["Toast"].loading({mask:!0,message:e,duration:3e4,forbidClick:!0})},dismissLoading:function(){f["Toast"].clear()},showAlert:function(e,t){e=e,t=t||"系統提示";f["Dialog"].alert({title:t,message:e}).then(function(){})},randomNumber:function(e){for(var t="",n=0;n<e;n++)t+=Math.floor(10*Math.random());return t},regPhone:function(e){var t=/^1[34578]\d{9}$/;return t.test(e)},info:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[info]","#909399","#FFFFFF"].concat(t))},success:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[success]","#67C23A","#FFFFFF"].concat(t))},warn:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[warning]","#E6A23C","#FFFFFF"].concat(t))},err:function(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];this.log.apply(this,["[error]","#F56C6C","#FFFFFF"].concat(t))},log:function(e,t,n){for(var r,o=arguments.length,a=new Array(o>3?o-3:0),u=3;u<o;u++)a[u-3]=arguments[u];h&&(r=window.console).log.apply(r,["%c".concat(e),"background-color: ".concat(t,"; color:").concat(n,"; padding: 2px 5px; border-radius: 2px")].concat(a))},timeFormat:function(e){var t=new Date(e),n=(t.getFullYear(),t.getMonth()+1<10?t.getMonth():t.getMonth(),t.getDate()<10?t.getDate():t.getDate(),t.getHours()<10?"0"+t.getHours():t.getHours()),r=t.getMinutes()<10?"0"+t.getMinutes():t.getMinutes();t.getSeconds(),t.getSeconds();return n+":"+r}},m=n("795b"),g=n.n(m),b="http://dy.rmq168.com/",v=n("68ae"),w=b+"api/",k=b+"web/download/douyou.apk",y="itms-services://?action=download-manifest&url="+b+"web/download/Info.plist";function _(e){return e&&e.data&&200==e.data.code&&(200===e.status||304===e.status||400===e.status)?{success:!0,data:e.data.data,message:""}:{success:!1,data:[],message:e.data.msg}}function F(e){var t="请求服务器错误，请稍后再试";return{success:!1,data:[],message:t}}axios.interceptors.request.use(function(e){return e},function(e){return g.a.reject(e)}),axios.interceptors.response.use(function(e){return e},function(e){return g.a.resolve(e.response)});var A={_HOST:w,_DO_MAN:b,download_ewm_img:v,download_android_url:k,download_ios_url:y,get:function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:sessionStorage.getItem("memebuy_httpToken")||"";return axios({method:"get",url:e,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest",Authorization:"Bearer "+t}}).then(function(e){return _(e)}).catch(function(e){return F(e)})},post:function(e,t){arguments.length>2&&void 0!==arguments[2]?arguments[2]:sessionStorage.getItem("memebuy_httpToken");return axios({method:"post",url:e,data:t,timeout:1e4,headers:{"X-Requested-With":"XMLHttpRequest","Content-Type":"application/json; charset=UTF-8"}}).then(function(e){return _(e)}).catch(function(e){return F(e)})}},x=n("ec7e"),T=n.n(x);function C(e){if(window.WebViewJavascriptBridge)return e(window.WebViewJavascriptBridge);if(window.WVJBCallbacks)return window.WVJBCallbacks.push(e);window.WVJBCallbacks=[e];var t=document.createElement("iframe");t.style.display="none",t.src="https://__bridge_loaded__",document.documentElement.appendChild(t),setTimeout(function(){document.documentElement.removeChild(t)},0)}var M={callhandler:function(e,t,n){C(function(r){r.callHandler(e,t,n)})},registerhandler:function(e,t){C(function(n){n.registerHandler(e,function(e,n){t(e,n)})})}};Vue.prototype.utils=p,Vue.prototype.http=A,Vue.use(T.a),Vue.prototype.$bridge=M,Vue.config.productionTip=!1,new Vue({store:s,router:l,render:function(e){return e(i)}}).$mount("#app")},5880:function(e,t){e.exports=Vuex},"64a9":function(e,t,n){},"68ae":function(e,t,n){e.exports=n.p+"img/download-ewm-test.80b2a92f.png"}});
//# sourceMappingURL=app.08ba1981.js.map