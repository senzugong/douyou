(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-6e9caca5"],{"02f4":function(t,n,e){var r=e("4588"),i=e("be13");t.exports=function(t){return function(n,e){var o,a,c=String(i(n)),l=r(e),u=c.length;return l<0||l>=u?t?"":void 0:(o=c.charCodeAt(l),o<55296||o>56319||l+1===u||(a=c.charCodeAt(l+1))<56320||a>57343?t?c.charAt(l):o:t?c.slice(l,l+2):a-56320+(o-55296<<10)+65536)}}},"0390":function(t,n,e){"use strict";var r=e("02f4")(!0);t.exports=function(t,n,e){return n+(e?r(t,n).length:1)}},"094a":function(t,n,e){t.exports=e.p+"img/download-top.5f96b200.png"},"0bfb":function(t,n,e){"use strict";var r=e("cb7c");t.exports=function(){var t=r(this),n="";return t.global&&(n+="g"),t.ignoreCase&&(n+="i"),t.multiline&&(n+="m"),t.unicode&&(n+="u"),t.sticky&&(n+="y"),n}},"214f":function(t,n,e){"use strict";e("b0c5");var r=e("2aba"),i=e("32e9"),o=e("79e5"),a=e("be13"),c=e("2b4c"),l=e("520a"),u=c("species"),s=!o(function(){var t=/./;return t.exec=function(){var t=[];return t.groups={a:"7"},t},"7"!=="".replace(t,"$<a>")}),f=function(){var t=/(?:)/,n=t.exec;t.exec=function(){return n.apply(this,arguments)};var e="ab".split(t);return 2===e.length&&"a"===e[0]&&"b"===e[1]}();t.exports=function(t,n,e){var d=c(t),p=!o(function(){var n={};return n[d]=function(){return 7},7!=""[t](n)}),v=p?!o(function(){var n=!1,e=/a/;return e.exec=function(){return n=!0,null},"split"===t&&(e.constructor={},e.constructor[u]=function(){return e}),e[d](""),!n}):void 0;if(!p||!v||"replace"===t&&!s||"split"===t&&!f){var S=/./[d],h=e(a,d,""[t],function(t,n,e,r,i){return n.exec===l?p&&!i?{done:!0,value:S.call(n,e,r)}:{done:!0,value:t.call(e,n,r)}:{done:!1}}),k=h[0],b=h[1];r(String.prototype,t,k),i(RegExp.prototype,d,2==n?function(t,n){return b.call(t,this,n)}:function(t){return b.call(t,this)})}}},3971:function(t,n,e){"use strict";e.r(n);var r=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"download-page"},[t._m(0),e("div",{staticClass:"download-content"},[e("div",{staticClass:"download-box"},[t._m(1),e("div",{staticClass:"download-btn"},[e("div",{staticClass:"download-btn-left",on:{click:function(n){return t.download1("ios")}}},[t._v("IOS下载")]),e("div",{staticClass:"download-btn-right",on:{click:function(n){return t.download1("android")}}},[t._v("Android下载")])])])])])},i=[function(){var t=this,n=t.$createElement,r=t._self._c||n;return r("div",{staticClass:"download-top"},[r("img",{attrs:{src:e("094a"),alt:""}})])},function(){var t=this,n=t.$createElement,r=t._self._c||n;return r("div",{staticClass:"download-img"},[r("img",{attrs:{src:e("cb21"),alt:""}})])}],o=(e("4917"),{data:function(){return{}},methods:{isWeiXin:function(){var t=window.navigator.userAgent.toLowerCase();return"micromessenger"==t.match(/MicroMessenger/i)},isIos:function(){var t=navigator.userAgent;return!!t.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/)&&(document.getElementById("downts").innerHTML="点击右上角按钮，然后在弹出菜单中，点击Safari中打开，即可安装",document.getElementById("bb").innerHTML="版本：V1.0.7（builld 7）",document.getElementById("dx").innerHTML="大小：52M",!0)},download1:function(t){this.isWeiXin()?this.utils.showAlert("点击右上角按钮，然后在弹出菜单中，点击浏览器中打开，即可安装"):window.location.href="ios"==t?"itms-services://?action=download-manifest&url=http://dy.rmq168.com/web/download/Info.plist":"http://dy.rmq168.com/web/download/douyou.apk"}}}),a=o,c=(e("536b"),e("2877")),l=Object(c["a"])(a,r,i,!1,null,"3277d020",null);n["default"]=l.exports},4917:function(t,n,e){"use strict";var r=e("cb7c"),i=e("9def"),o=e("0390"),a=e("5f1b");e("214f")("match",1,function(t,n,e,c){return[function(e){var r=t(this),i=void 0==e?void 0:e[n];return void 0!==i?i.call(e,r):new RegExp(e)[n](String(r))},function(t){var n=c(e,t,this);if(n.done)return n.value;var l=r(t),u=String(this);if(!l.global)return a(l,u);var s=l.unicode;l.lastIndex=0;var f,d=[],p=0;while(null!==(f=a(l,u))){var v=String(f[0]);d[p]=v,""===v&&(l.lastIndex=o(u,i(l.lastIndex),s)),p++}return 0===p?null:d}]})},"520a":function(t,n,e){"use strict";var r=e("0bfb"),i=RegExp.prototype.exec,o=String.prototype.replace,a=i,c="lastIndex",l=function(){var t=/a/,n=/b*/g;return i.call(t,"a"),i.call(n,"a"),0!==t[c]||0!==n[c]}(),u=void 0!==/()??/.exec("")[1],s=l||u;s&&(a=function(t){var n,e,a,s,f=this;return u&&(e=new RegExp("^"+f.source+"$(?!\\s)",r.call(f))),l&&(n=f[c]),a=i.call(f,t),l&&a&&(f[c]=f.global?a.index+a[0].length:n),u&&a&&a.length>1&&o.call(a[0],e,function(){for(s=1;s<arguments.length-2;s++)void 0===arguments[s]&&(a[s]=void 0)}),a}),t.exports=a},"536b":function(t,n,e){"use strict";var r=e("a50f"),i=e.n(r);i.a},"5f1b":function(t,n,e){"use strict";var r=e("23c6"),i=RegExp.prototype.exec;t.exports=function(t,n){var e=t.exec;if("function"===typeof e){var o=e.call(t,n);if("object"!==typeof o)throw new TypeError("RegExp exec method returned something other than an Object or null");return o}if("RegExp"!==r(t))throw new TypeError("RegExp#exec called on incompatible receiver");return i.call(t,n)}},a50f:function(t,n,e){},b0c5:function(t,n,e){"use strict";var r=e("520a");e("5ca1")({target:"RegExp",proto:!0,forced:r!==/./.exec},{exec:r})},cb21:function(t,n){t.exports="data:image/png;base64,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"}}]);
//# sourceMappingURL=chunk-6e9caca5.ea50a356.js.map