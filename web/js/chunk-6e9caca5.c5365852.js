(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6e9caca5"],{"02f4":function(t,n,e){var r=e("4588"),o=e("be13");t.exports=function(t){return function(n,e){var i,c,a=String(o(n)),u=r(e),l=a.length;return u<0||u>=l?t?"":void 0:(i=a.charCodeAt(u),i<55296||i>56319||u+1===l||(c=a.charCodeAt(u+1))<56320||c>57343?t?a.charAt(u):i:t?a.slice(u,u+2):c-56320+(i-55296<<10)+65536)}}},"0390":function(t,n,e){"use strict";var r=e("02f4")(!0);t.exports=function(t,n,e){return n+(e?r(t,n).length:1)}},"094a":function(t,n,e){t.exports=e.p+"img/download-top.5f96b200.png"},"0bfb":function(t,n,e){"use strict";var r=e("cb7c");t.exports=function(){var t=r(this),n="";return t.global&&(n+="g"),t.ignoreCase&&(n+="i"),t.multiline&&(n+="m"),t.unicode&&(n+="u"),t.sticky&&(n+="y"),n}},"214f":function(t,n,e){"use strict";e("b0c5");var r=e("2aba"),o=e("32e9"),i=e("79e5"),c=e("be13"),a=e("2b4c"),u=e("520a"),l=a("species"),s=!i(function(){var t=/./;return t.exec=function(){var t=[];return t.groups={a:"7"},t},"7"!=="".replace(t,"$<a>")}),d=function(){var t=/(?:)/,n=t.exec;t.exec=function(){return n.apply(this,arguments)};var e="ab".split(t);return 2===e.length&&"a"===e[0]&&"b"===e[1]}();t.exports=function(t,n,e){var f=a(t),p=!i(function(){var n={};return n[f]=function(){return 7},7!=""[t](n)}),v=p?!i(function(){var n=!1,e=/a/;return e.exec=function(){return n=!0,null},"split"===t&&(e.constructor={},e.constructor[l]=function(){return e}),e[f](""),!n}):void 0;if(!p||!v||"replace"===t&&!s||"split"===t&&!d){var g=/./[f],b=e(c,f,""[t],function(t,n,e,r,o){return n.exec===u?p&&!o?{done:!0,value:g.call(n,e,r)}:{done:!0,value:t.call(e,n,r)}:{done:!1}}),h=b[0],w=b[1];r(String.prototype,t,h),o(RegExp.prototype,f,2==n?function(t,n){return w.call(t,this,n)}:function(t){return w.call(t,this)})}}},3971:function(t,n,e){"use strict";e.r(n);var r=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"download-page"},[t._m(0),e("div",{staticClass:"download-content"},[e("div",{staticClass:"download-box"},[t._m(1),e("div",{staticClass:"download-btn"},[e("div",{staticClass:"download-btn-left",on:{click:function(n){return t.download1("ios")}}},[t._v("IOS下载")]),e("div",{staticClass:"download-btn-right",on:{click:function(n){return t.download1("android")}}},[t._v("Android下载")])])])])])},o=[function(){var t=this,n=t.$createElement,r=t._self._c||n;return r("div",{staticClass:"download-top"},[r("img",{attrs:{src:e("094a"),alt:""}})])},function(){var t=this,n=t.$createElement,r=t._self._c||n;return r("div",{staticClass:"download-img"},[r("img",{attrs:{src:e("cb21"),alt:""}})])}],i=(e("4917"),{data:function(){return{}},methods:{isWeiXin:function(){var t=window.navigator.userAgent.toLowerCase();return"micromessenger"==t.match(/MicroMessenger/i)},isIos:function(){var t=navigator.userAgent;return!!t.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/)&&(document.getElementById("downts").innerHTML="点击右上角按钮，然后在弹出菜单中，点击Safari中打开，即可安装",document.getElementById("bb").innerHTML="版本：V1.0.7（builld 7）",document.getElementById("dx").innerHTML="大小：52M",!0)},download1:function(t){this.isWeiXin()?this.utils.showAlert("点击右上角按钮，然后在弹出菜单中，点击浏览器中打开，即可安装"):window.location.href="ios"==t?"itms-services://?action=download-manifest&url=http://dy.rmq168.com/web/download/Info.plist":"http://dy.rmq168.com/web/download/douyou.apk"}}}),c=i,a=(e("536b"),e("2877")),u=Object(a["a"])(c,r,o,!1,null,"3277d020",null);n["default"]=u.exports},4917:function(t,n,e){"use strict";var r=e("cb7c"),o=e("9def"),i=e("0390"),c=e("5f1b");e("214f")("match",1,function(t,n,e,a){return[function(e){var r=t(this),o=void 0==e?void 0:e[n];return void 0!==o?o.call(e,r):new RegExp(e)[n](String(r))},function(t){var n=a(e,t,this);if(n.done)return n.value;var u=r(t),l=String(this);if(!u.global)return c(u,l);var s=u.unicode;u.lastIndex=0;var d,f=[],p=0;while(null!==(d=c(u,l))){var v=String(d[0]);f[p]=v,""===v&&(u.lastIndex=i(l,o(u.lastIndex),s)),p++}return 0===p?null:f}]})},"520a":function(t,n,e){"use strict";var r=e("0bfb"),o=RegExp.prototype.exec,i=String.prototype.replace,c=o,a="lastIndex",u=function(){var t=/a/,n=/b*/g;return o.call(t,"a"),o.call(n,"a"),0!==t[a]||0!==n[a]}(),l=void 0!==/()??/.exec("")[1],s=u||l;s&&(c=function(t){var n,e,c,s,d=this;return l&&(e=new RegExp("^"+d.source+"$(?!\\s)",r.call(d))),u&&(n=d[a]),c=o.call(d,t),u&&c&&(d[a]=d.global?c.index+c[0].length:n),l&&c&&c.length>1&&i.call(c[0],e,function(){for(s=1;s<arguments.length-2;s++)void 0===arguments[s]&&(c[s]=void 0)}),c}),t.exports=c},"536b":function(t,n,e){"use strict";var r=e("a50f"),o=e.n(r);o.a},"5f1b":function(t,n,e){"use strict";var r=e("23c6"),o=RegExp.prototype.exec;t.exports=function(t,n){var e=t.exec;if("function"===typeof e){var i=e.call(t,n);if("object"!==typeof i)throw new TypeError("RegExp exec method returned something other than an Object or null");return i}if("RegExp"!==r(t))throw new TypeError("RegExp#exec called on incompatible receiver");return o.call(t,n)}},a50f:function(t,n,e){},b0c5:function(t,n,e){"use strict";var r=e("520a");e("5ca1")({target:"RegExp",proto:!0,forced:r!==/./.exec},{exec:r})},cb21:function(t,n,e){t.exports=e.p+"img/download-ewm.7c7faddf.png"}}]);
//# sourceMappingURL=chunk-6e9caca5.c5365852.js.map