(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-cd528c38"],{"02f4":function(t,n,o){var e=o("4588"),r=o("be13");t.exports=function(t){return function(n,o){var i,a,c=String(r(n)),l=e(o),s=c.length;return l<0||l>=s?t?"":void 0:(i=c.charCodeAt(l),i<55296||i>56319||l+1===s||(a=c.charCodeAt(l+1))<56320||a>57343?t?c.charAt(l):i:t?c.slice(l,l+2):a-56320+(i-55296<<10)+65536)}}},"0390":function(t,n,o){"use strict";var e=o("02f4")(!0);t.exports=function(t,n,o){return n+(o?e(t,n).length:1)}},"0bfb":function(t,n,o){"use strict";var e=o("cb7c");t.exports=function(){var t=e(this),n="";return t.global&&(n+="g"),t.ignoreCase&&(n+="i"),t.multiline&&(n+="m"),t.unicode&&(n+="u"),t.sticky&&(n+="y"),n}},"214f":function(t,n,o){"use strict";o("b0c5");var e=o("2aba"),r=o("32e9"),i=o("79e5"),a=o("be13"),c=o("2b4c"),l=o("520a"),s=c("species"),u=!i(function(){var t=/./;return t.exec=function(){var t=[];return t.groups={a:"7"},t},"7"!=="".replace(t,"$<a>")}),A=function(){var t=/(?:)/,n=t.exec;t.exec=function(){return n.apply(this,arguments)};var o="ab".split(t);return 2===o.length&&"a"===o[0]&&"b"===o[1]}();t.exports=function(t,n,o){var d=c(t),g=!i(function(){var n={};return n[d]=function(){return 7},7!=""[t](n)}),f=g?!i(function(){var n=!1,o=/a/;return o.exec=function(){return n=!0,null},"split"===t&&(o.constructor={},o.constructor[s]=function(){return o}),o[d](""),!n}):void 0;if(!g||!f||"replace"===t&&!u||"split"===t&&!A){var v=/./[d],E=o(a,d,""[t],function(t,n,o,e,r){return n.exec===l?g&&!r?{done:!0,value:v.call(n,o,e)}:{done:!0,value:t.call(o,n,e)}:{done:!1}}),h=E[0],w=E[1];e(String.prototype,t,h),r(RegExp.prototype,d,2==n?function(t,n){return w.call(t,this,n)}:function(t){return w.call(t,this)})}}},3971:function(t,n,o){"use strict";o.r(n);var e=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("div",{staticClass:"download-page"},[e("div",{staticClass:"download-content"},[e("div",{staticClass:"download-box"},[e("div",{staticClass:"download-img"},[e("img",{attrs:{src:t.download_ewm_img,alt:""}})]),e("div",{staticClass:"download-btn"},[e("img",{staticClass:"download-btn-left",attrs:{src:o("aa2e"),alt:""},on:{click:function(n){return t.download1("ios")}}}),e("img",{staticClass:"download-btn-right",attrs:{src:o("b7e2"),alt:""},on:{click:function(n){return t.download1("android")}}})])])])])},r=[],i=(o("4917"),{data:function(){return{download_ewm_img:this.http.download_ewm_img,download_android_url:this.http.download_android_url,download_ios_url:this.http.download_ios_url}},mounted:function(){console.log("download_ewm_img...",this.download_ewm_img),console.log("download_android_url...",this.download_android_url),console.log("download_ios_url...",this.download_ios_url)},methods:{isWeiXin:function(){var t=window.navigator.userAgent.toLowerCase();return"micromessenger"==t.match(/MicroMessenger/i)},isIos:function(){var t=navigator.userAgent;return!!t.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/)&&(document.getElementById("downts").innerHTML="点击右上角按钮，然后在弹出菜单中，点击Safari中打开，即可安装",document.getElementById("bb").innerHTML="版本：V1.0.7（builld 7）",document.getElementById("dx").innerHTML="大小：52M",!0)},download1:function(t){console.log("var...",t),console.log("isWeiXin...",this.isWeiXin()),this.isWeiXin()?this.utils.showAlert("点击右上角按钮，然后在弹出菜单中，点击浏览器中打开，即可安装"):window.location.href="ios"==t?this.download_ios_url:this.download_android_url}}}),a=i,c=(o("fb5a"),o("2877")),l=Object(c["a"])(a,e,r,!1,null,"6ee3e454",null);n["default"]=l.exports},4917:function(t,n,o){"use strict";var e=o("cb7c"),r=o("9def"),i=o("0390"),a=o("5f1b");o("214f")("match",1,function(t,n,o,c){return[function(o){var e=t(this),r=void 0==o?void 0:o[n];return void 0!==r?r.call(o,e):new RegExp(o)[n](String(e))},function(t){var n=c(o,t,this);if(n.done)return n.value;var l=e(t),s=String(this);if(!l.global)return a(l,s);var u=l.unicode;l.lastIndex=0;var A,d=[],g=0;while(null!==(A=a(l,s))){var f=String(A[0]);d[g]=f,""===f&&(l.lastIndex=i(s,r(l.lastIndex),u)),g++}return 0===g?null:d}]})},"520a":function(t,n,o){"use strict";var e=o("0bfb"),r=RegExp.prototype.exec,i=String.prototype.replace,a=r,c="lastIndex",l=function(){var t=/a/,n=/b*/g;return r.call(t,"a"),r.call(n,"a"),0!==t[c]||0!==n[c]}(),s=void 0!==/()??/.exec("")[1],u=l||s;u&&(a=function(t){var n,o,a,u,A=this;return s&&(o=new RegExp("^"+A.source+"$(?!\\s)",e.call(A))),l&&(n=A[c]),a=r.call(A,t),l&&a&&(A[c]=A.global?a.index+a[0].length:n),s&&a&&a.length>1&&i.call(a[0],o,function(){for(u=1;u<arguments.length-2;u++)void 0===arguments[u]&&(a[u]=void 0)}),a}),t.exports=a},"5f1b":function(t,n,o){"use strict";var e=o("23c6"),r=RegExp.prototype.exec;t.exports=function(t,n){var o=t.exec;if("function"===typeof o){var i=o.call(t,n);if("object"!==typeof i)throw new TypeError("RegExp exec method returned something other than an Object or null");return i}if("RegExp"!==e(t))throw new TypeError("RegExp#exec called on incompatible receiver");return r.call(t,n)}},"87c8":function(t,n,o){},aa2e:function(t,n){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQQAAABQCAMAAADIvvttAAAAOVBMVEVHcEw9ZPD////s7/3////////////////////V3fyMovZ0jvTX3vxaevKesPfBzPr4+f6wv/mAmPVBTgYCAAAACnRSTlMA////k6sH4VjOKd4oWgAABMxJREFUeNrtnOm2myAQgMVplsOwv//DFgQEjeASe9MbmT/ptQTC5+x66Don9+ft0V9OHrfnvRvleUECgcMzMvjTX1j+NAaRwrO/uFiLuD+uDuFxb4rgVOHWINy6R4Pw6BqDvm8QGoQGoUFoEBqEBuEkCEojkReHIDkhV4dAiRN2aQieAbm0T9CeAb0yBMU9BLgyhKAI4tIhEgcGeO08YbAGVNeGYBFw/Vt2xiTM/nEOBErl71EDjNkMtbmdRHgHgpICEdPuQSPn3F743PZoUWQJgqlacbdWKISoaJMD5uuGKEXDUH45BaOM6+tT2JGi+ARGcCf2FzqhFoJwwZ0ehQA8X4Lz6ZLLcCUhOnymkVqFu3NGniWSIOHZX0LmaW1YeoDgdiKPQWCkLnyJgrHX5xDsJTgPQs5jaUKvg7bWHT49BPtz6CEIwFcgLM4rCDEBQnBGitEA7GcgvPiEIbtjx8wB1xiYZXvwui/z9Fr78vsnIERfFDUBKKHh0gEI8ogeTL4OeZ5FfwhCxYZhP4Q1Yyikz4qxV01wMSpCkDaW6ey/mM4vALN6C4JSkekv6Nl3Mtujs8uMzyXECc73Q1jziiWuEHouBQgQjCz6amXIpCyzX3Nby61NCVKq3Ib/EaqSL7oZ16qdrqJpx4xhGQIfvCUSw4mQko7dKed8jZYu/8AIgdqQKgUfl0A/xCwonyCIiymLScTYEK3YIce45hbZHgjMJw8YYyWLGzJxIh1utPNEw54UhglEVJswydwnMKfvbGKPzA6VzIv9QXxYSB+AsOYS+h0QXHcWPAQ59ulUlln5vYK/YsaJtP8QY4yZZybeMbq0NhUHUzvmvRqW4pXGaHcgOQ2Tr0MwPqM3Y4Max68xv2WT5lEkJDXjr/URRXhcYQhbjA5gSKLJ0JkIBqFuHgg2cTYE3AAhG82CiZkEgY37zH2nTHv2AdVkS+HcN44h0ukaZmkJTvyDtANF/xlNoDGlh8m20iiVP8IQw5wyGZofzQmPdxX5CPElT7AhRGa9UOcKyDhIqJo1HIewyyf0BQiwCUJFAfNkKa1G3dUEQRIja9bwhmNkJ0DYpgkGMtlQO7DBG1k3EVoMyuoS0f2/CJHmBAgTn4DRJ0xHYy3XWYQA83QGCwXv+8lSSRV2QViMDtPReXRAlFuqyCE/cGHGCvgpxLGMcbV+KjSsdkFgS3nCdDSkIfplykopnXxC4Mhwf+0A5BiFXRAWM8bZaJpljGJ7P8EljYIa6rMncCuI86vImAK/BUEt1A6z0SqrHdQGCIraWMpTOLFLDa6HFhtsFQiarIt4F8JiFTkbrWLTkKotjlGFO2THa8mULS1wSBP4kX4CbICA5X4ChM+px4LZqJdmAaRyL42u9BMWzMGWTqCiT9CDwtqsWZejTK29ZtYhfPp51KpjpMGGRO39mu6NZvNK9P0vIEC8T5of6zavd1o//pAeWPnxms8ut9ym8587/EKpP4GidQjf8hJbHYLiR6qHL4NQNQiuLgKhljF9z8uMq+8niG93CFsgjBRQuPpU8O9jsOV1HeZ6EnRUfmm4/fOrXmVsr/o3CA1Cg9AgNAgNQoNQhNCODugf7RAJd4hEO06kf7aDZdzBMk0Vnu2wqXbkVmLQDqDr2lGEXfcXvqjEYRj8vQoAAAAASUVORK5CYII="},b0c5:function(t,n,o){"use strict";var e=o("520a");o("5ca1")({target:"RegExp",proto:!0,forced:e!==/./.exec},{exec:e})},b7e2:function(t,n){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQQAAABQCAMAAADIvvttAAAANlBMVEVHcEz///+KoPZ2kPT///////89ZPDu8f73+P7////U3PvBzPqcr/dfffPk6P1OcfG0wvmouPh1pRnIAAAACnRSTlMA////WAf//f2fftT5KAAABRpJREFUeNrtnGu7qioQgBMKiLv//8+eGRDQBKN2nb3WlvmwstSEl7m3Hi4XlNv1zqfTCb9fb5csVzGdVMQ1MbhPJ5b7YJAoXKeTC1jETZwdgrgNRUBVuA8I9wsfEMRlMBgQggwIA8KAMCAMCAPCixCo3bxV5IwQFN28NfKU5oDTFlIpJaHW9OScPkEabVgUOOKnhGApWwvhJ4TgHduKU6eDMLO9qJNBUMECijIYgn/lqSBwnD6JKFicPnoI99takkrlA/EyBB2dQHGNVAWtmH8ZBMZSuGd80uQ1CI7VxfyU2QnSFNWAwA3Tr0CwrCVP7EFw3msxM/EdV+3jsieojrw5wmWm1KDAsqHMAEFO1lVd++XILVbFHo8Z3CfthEAZ6bnIiN0j8AlCFzGMrt5puSz9hgtCgGk5+wkIT+KDe87pFQi2EpfJDjOpqTkPwlh4ERECuDrfBUEorBhUS2BdZNvRAryG4b0HQbg9004IO58Qlk92mQPYDTxENr2OxVDpbHtinrkPmoOd7fQOBME3msA5ZX456oAQgqI/MAcfAmZTe6XoTSy7IFTdTgcE3ZyA6YCwJAVtCDF1aGUXZjNIK2EdUavmFX+pCdE2Q5BQp1udA5ud4XLPN99QzuB9NQhsfrDQ2TyIYy4e0G9DcJhMyWLGMF6+5FspyxL5fYTAN8rFySbMrWYsSDpRhcBcLY1bea8Dv/FZCHAXDyhWUzDgJTzWINEtC4hcxEtP2ZwhQKlmaFghHs4qOJUmmmeMH4X7dA0CpdUi15VgpsPTlP06BBqHp7NrhCVycnG3Jn0ilyLVJAhl+HS5fPJJF9KM5+RpFNxX8wmY1a8bgFxK0DAno1j4RhKo2C9D4MtAbXaNJCkAzsou6148cIKQGMh8OTo2voIAoZKUM3XHiL6QiLpnJPDdFEfmvq0JWQNoUWa2BTSXvFtmCLRMJrtusajCAkEVP8NbEEInzCWKnoIlgplEmeP852pA+iiEvFp+Wca1D4sQ6GoCLkHw5RP9aFokvZQlNO0QiTZBGpHTMUwb1ZchlKggktLvIJjVGEt0KKvvt9E2f8Ma3lGeANHFlvE4ASpE8+Nk3Ro+CoEuFVus3d6AwNdp1nsQwCayQRlEWiBoNpN6evZBCLxSaP3vmrDtkULaBfEGk/1QQdJGbfdBCPBQmcVsLPqJT5DPfYJeKTLtg6AeOsSime1/EMLGXy0RbgdhFR0s20Hoig6C9UEQYTVAofCFR3OVX4YgN3WTja5xB2GVJ5A9hD/LExpJLN1qkNbie1Uk2XpeGhZ1BwGnrlIY3UEoGeNcyRh91vIXIHhmvCaQJ+A3uBgkevsJR52laj9BsO1AVJjbHgLWDtQrqAEM2UNYzjZrh3Cf7oIgCWRLrrTXQrtHodV2aELsLB2212qdpfnB80b93UPIVSTlFQi5VmxXkcZ2Qoi/HEIFrb20AosRSBOE6OonvNljtFJWPlh1A6Jzyn2BchL7CZtW0mE/QWFpZDsgcCUtTz5B6GB7gE9XLm1BkO92m/+KPHWMZvHHtDqB5o8vpsGATr8Rgl/8rdCmr9ucTNChRaFE2woHTv/I3yKt5Ac/BoWXo9svz5r+MY6RH2sJn5ABYUAYEAaEAeEVCFNOYHXPvyb8oxBIWn+bVOKEEKBoWX4XUYZRcU4IJ5EBYUAYEAaEAWFAGBDqEMbWARMfm0jgJhJjO5HpOjaWwY1lhipcx2ZTY8utwmBsQHc571aEQqStCP8DjkiWVm476X4AAAAASUVORK5CYII="},fb5a:function(t,n,o){"use strict";var e=o("87c8"),r=o.n(e);r.a}}]);
//# sourceMappingURL=chunk-cd528c38.37e68b7e.js.map