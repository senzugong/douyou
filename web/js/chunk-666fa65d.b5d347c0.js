(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-666fa65d"],{"014b":function(t,e,s){"use strict";var n=s("e53d"),a=s("07e3"),i=s("8e60"),o=s("63b6"),r=s("9138"),c=s("ebfd").KEY,u=s("294c"),l=s("dbdb"),p=s("45f2"),f=s("62a0"),d=s("5168"),h=s("ccb9"),m=s("6718"),g=s("47ee"),v=s("9003"),y=s("e4ae"),_=s("f772"),b=s("36c3"),w=s("1bc3"),x=s("aebd"),A=s("a159"),P=s("0395"),k=s("bf0b"),C=s("d9f6"),O=s("c3a1"),S=k.f,H=C.f,M=P.f,I=n.Symbol,j=n.JSON,F=j&&j.stringify,L="prototype",B=d("_hidden"),E=d("toPrimitive"),R={}.propertyIsEnumerable,D=l("symbol-registry"),G=l("symbols"),T=l("op-symbols"),W=Object[L],z="function"==typeof I,N=n.QObject,K=!N||!N[L]||!N[L].findChild,U=i&&u(function(){return 7!=A(H({},"a",{get:function(){return H(this,"a",{value:7}).a}})).a})?function(t,e,s){var n=S(W,e);n&&delete W[e],H(t,e,s),n&&t!==W&&H(W,e,n)}:H,V=function(t){var e=G[t]=A(I[L]);return e._k=t,e},q=z&&"symbol"==typeof I.iterator?function(t){return"symbol"==typeof t}:function(t){return t instanceof I},Q=function(t,e,s){return t===W&&Q(T,e,s),y(t),e=w(e,!0),y(s),a(G,e)?(s.enumerable?(a(t,B)&&t[B][e]&&(t[B][e]=!1),s=A(s,{enumerable:x(0,!1)})):(a(t,B)||H(t,B,x(1,{})),t[B][e]=!0),U(t,e,s)):H(t,e,s)},Z=function(t,e){y(t);var s,n=g(e=b(e)),a=0,i=n.length;while(i>a)Q(t,s=n[a++],e[s]);return t},Y=function(t,e){return void 0===e?A(t):Z(A(t),e)},X=function(t){var e=R.call(this,t=w(t,!0));return!(this===W&&a(G,t)&&!a(T,t))&&(!(e||!a(this,t)||!a(G,t)||a(this,B)&&this[B][t])||e)},J=function(t,e){if(t=b(t),e=w(e,!0),t!==W||!a(G,e)||a(T,e)){var s=S(t,e);return!s||!a(G,e)||a(t,B)&&t[B][e]||(s.enumerable=!0),s}},$=function(t){var e,s=M(b(t)),n=[],i=0;while(s.length>i)a(G,e=s[i++])||e==B||e==c||n.push(e);return n},tt=function(t){var e,s=t===W,n=M(s?T:b(t)),i=[],o=0;while(n.length>o)!a(G,e=n[o++])||s&&!a(W,e)||i.push(G[e]);return i};z||(I=function(){if(this instanceof I)throw TypeError("Symbol is not a constructor!");var t=f(arguments.length>0?arguments[0]:void 0),e=function(s){this===W&&e.call(T,s),a(this,B)&&a(this[B],t)&&(this[B][t]=!1),U(this,t,x(1,s))};return i&&K&&U(W,t,{configurable:!0,set:e}),V(t)},r(I[L],"toString",function(){return this._k}),k.f=J,C.f=Q,s("6abf").f=P.f=$,s("355d").f=X,s("9aa9").f=tt,i&&!s("b8e3")&&r(W,"propertyIsEnumerable",X,!0),h.f=function(t){return V(d(t))}),o(o.G+o.W+o.F*!z,{Symbol:I});for(var et="hasInstance,isConcatSpreadable,iterator,match,replace,search,species,split,toPrimitive,toStringTag,unscopables".split(","),st=0;et.length>st;)d(et[st++]);for(var nt=O(d.store),at=0;nt.length>at;)m(nt[at++]);o(o.S+o.F*!z,"Symbol",{for:function(t){return a(D,t+="")?D[t]:D[t]=I(t)},keyFor:function(t){if(!q(t))throw TypeError(t+" is not a symbol!");for(var e in D)if(D[e]===t)return e},useSetter:function(){K=!0},useSimple:function(){K=!1}}),o(o.S+o.F*!z,"Object",{create:Y,defineProperty:Q,defineProperties:Z,getOwnPropertyDescriptor:J,getOwnPropertyNames:$,getOwnPropertySymbols:tt}),j&&o(o.S+o.F*(!z||u(function(){var t=I();return"[null]"!=F([t])||"{}"!=F({a:t})||"{}"!=F(Object(t))})),"JSON",{stringify:function(t){var e,s,n=[t],a=1;while(arguments.length>a)n.push(arguments[a++]);if(s=e=n[1],(_(e)||void 0!==t)&&!q(t))return v(e)||(e=function(t,e){if("function"==typeof s&&(e=s.call(this,t,e)),!q(e))return e}),n[1]=e,F.apply(j,n)}}),I[L][E]||s("35e8")(I[L],E,I[L].valueOf),p(I,"Symbol"),p(Math,"Math",!0),p(n.JSON,"JSON",!0)},"0395":function(t,e,s){var n=s("36c3"),a=s("6abf").f,i={}.toString,o="object"==typeof window&&window&&Object.getOwnPropertyNames?Object.getOwnPropertyNames(window):[],r=function(t){try{return a(t)}catch(e){return o.slice()}};t.exports.f=function(t){return o&&"[object Window]"==i.call(t)?r(t):a(n(t))}},"06db":function(t,e,s){"use strict";var n=s("23c6"),a={};a[s("2b4c")("toStringTag")]="z",a+""!="[object z]"&&s("2aba")(Object.prototype,"toString",function(){return"[object "+n(this)+"]"},!0)},1310:function(t,e,s){t.exports=s.p+"img/guess-succ.f6d80c9d.png"},"23a5":function(t,e,s){t.exports=s.p+"img/up.3cb8b3c6.png"},"268f":function(t,e,s){t.exports=s("fde4")},"2fb6":function(t,e,s){t.exports=s.p+"img/go-rule.b785a878.png"},"32a6":function(t,e,s){var n=s("241e"),a=s("c3a1");s("ce7e")("keys",function(){return function(t){return a(n(t))}})},"32e2":function(t,e,s){t.exports=s.p+"img/guess-up.34e78bd7.png"},"355d":function(t,e){e.f={}.propertyIsEnumerable},"454f":function(t,e,s){s("46a7");var n=s("584a").Object;t.exports=function(t,e,s){return n.defineProperty(t,e,s)}},"46a7":function(t,e,s){var n=s("63b6");n(n.S+n.F*!s("8e60"),"Object",{defineProperty:s("d9f6").f})},"47ee":function(t,e,s){var n=s("c3a1"),a=s("9aa9"),i=s("355d");t.exports=function(t){var e=n(t),s=a.f;if(s){var o,r=s(t),c=i.f,u=0;while(r.length>u)c.call(t,o=r[u++])&&e.push(o)}return e}},"4be0":function(t,e,s){"use strict";var n=s("6ca3"),a=s.n(n);a.a},"5d6b":function(t,e,s){var n=s("e53d").parseInt,a=s("a1ce").trim,i=s("e692"),o=/^[-+]?0[xX]/;t.exports=8!==n(i+"08")||22!==n(i+"0x16")?function(t,e){var s=a(String(t),3);return n(s,e>>>0||(o.test(s)?16:10))}:n},"619f":function(t,e){t.exports="data:image/png;base64,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"},6718:function(t,e,s){var n=s("e53d"),a=s("584a"),i=s("b8e3"),o=s("ccb9"),r=s("d9f6").f;t.exports=function(t){var e=a.Symbol||(a.Symbol=i?{}:n.Symbol||{});"_"==t.charAt(0)||t in e||r(e,t,{value:o.f(t)})}},"6abf":function(t,e,s){var n=s("e6f3"),a=s("1691").concat("length","prototype");e.f=Object.getOwnPropertyNames||function(t){return n(t,a)}},"6ca3":function(t,e,s){},7445:function(t,e,s){var n=s("63b6"),a=s("5d6b");n(n.G+n.F*(parseInt!=a),{parseInt:a})},"83d9":function(t,e,s){t.exports=s.p+"img/down.9d2da92c.png"},"85f2":function(t,e,s){t.exports=s("454f")},"89da":function(t,e,s){t.exports=s.p+"img/guess-fail.2e6d26e1.png"},"8aae":function(t,e,s){s("32a6"),t.exports=s("584a").Object.keys},9003:function(t,e,s){var n=s("6b4c");t.exports=Array.isArray||function(t){return"Array"==n(t)}},"9aa9":function(t,e){e.f=Object.getOwnPropertySymbols},a1ce:function(t,e,s){var n=s("63b6"),a=s("25eb"),i=s("294c"),o=s("e692"),r="["+o+"]",c="​",u=RegExp("^"+r+r+"*"),l=RegExp(r+r+"*$"),p=function(t,e,s){var a={},r=i(function(){return!!o[t]()||c[t]()!=c}),u=a[t]=r?e(f):o[t];s&&(a[s]=u),n(n.P+n.F*r,"String",a)},f=p.trim=function(t,e){return t=String(a(t)),1&e&&(t=t.replace(u,"")),2&e&&(t=t.replace(l,"")),t};t.exports=p},a4bb:function(t,e,s){t.exports=s("8aae")},ac6a:function(t,e,s){for(var n=s("cadf"),a=s("0d58"),i=s("2aba"),o=s("7726"),r=s("32e9"),c=s("84f2"),u=s("2b4c"),l=u("iterator"),p=u("toStringTag"),f=c.Array,d={CSSRuleList:!0,CSSStyleDeclaration:!1,CSSValueList:!1,ClientRectList:!1,DOMRectList:!1,DOMStringList:!1,DOMTokenList:!0,DataTransferItemList:!1,FileList:!1,HTMLAllCollection:!1,HTMLCollection:!1,HTMLFormElement:!1,HTMLSelectElement:!1,MediaList:!0,MimeTypeArray:!1,NamedNodeMap:!1,NodeList:!0,PaintRequestList:!1,Plugin:!1,PluginArray:!1,SVGLengthList:!1,SVGNumberList:!1,SVGPathSegList:!1,SVGPointList:!1,SVGStringList:!1,SVGTransformList:!1,SourceBufferList:!1,StyleSheetList:!0,TextTrackCueList:!1,TextTrackList:!1,TouchList:!1},h=a(d),m=0;m<h.length;m++){var g,v=h[m],y=d[v],_=o[v],b=_&&_.prototype;if(b&&(b[l]||r(b,l,f),b[p]||r(b,p,v),c[v]=f,y))for(g in n)b[g]||i(b,g,n[g],!0)}},b9e9:function(t,e,s){s("7445"),t.exports=s("584a").parseInt},bb51:function(t,e,s){"use strict";s.r(e);var n=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"home"},[n("van-nav-bar",{attrs:{title:"","left-arrow":"",border:!1},on:{"click-left":t.goBack}}),n("img",{staticClass:"go-rule",attrs:{src:s("2fb6"),alt:""},on:{click:t.goRulePage}}),n("div",{staticClass:"space"}),n("div",{staticClass:"home-chart-box"},[n("div",{staticClass:"home-chart-title"},[t._v("第"+t._s(t.page_data.btc_result.btc_id)+"期BTC价格 \n\t\t\t\t"),n("span",[t._v("$"+t._s(t.page_data.btc_result.btc_price))]),parseFloat(t.page_data.btc_result.change)<0?n("span",{staticStyle:{color:"#ec0000"}},[t._v(t._s(t.page_data.btc_result.change)+"%")]):t._e(),parseFloat(t.page_data.btc_result.change)>=0?n("span",{staticStyle:{color:"#00da3c"}},[t._v(t._s(t.page_data.btc_result.change)+"%")]):t._e()]),n("div",{staticClass:"home-chart"},[n("div",{ref:"homeChartContent",staticClass:"home-chart-content"})]),n("div",{staticClass:"k-type"},[n("span",{class:{active:1==t.k_type},on:{click:function(e){return t.kTypeChange(1)}}},[t._v("1M")]),n("span",{class:{active:2==t.k_type},on:{click:function(e){return t.kTypeChange(2)}}},[t._v("5M")]),n("span",{class:{active:3==t.k_type},on:{click:function(e){return t.kTypeChange(3)}}},[t._v("15M")]),n("span",{class:{active:4==t.k_type},on:{click:function(e){return t.kTypeChange(4)}}},[t._v("30M")])]),n("div",{staticClass:"home-progress"},[n("span",[t._v(t._s(t.down_percent)+"%")]),n("div",{ref:"homeProgressLeft",staticClass:"home-progress-left"},[t._v(t._s(t.up_percent)+"%")])]),t.page_data.user_postbtc?t._e():n("div",[n("div",{staticClass:"home-chart-btn"},[n("img",{attrs:{src:s("32e2"),alt:""},on:{click:function(e){return t.guessFun("1")}}}),n("img",{attrs:{src:s("cac3"),alt:""},on:{click:function(e){return t.guessFun("2")}}})]),n("div",{staticClass:"home-time-text"},[t._v("您还未参与竞猜哦~")]),n("div",{staticClass:"home-time-text"},[t._v("距离第"+t._s(t.page_data.btc_result.btc_id+1)+"期竞猜结束还剩"),n("span",[t._v(t._s(t.end_minute))]),t._v("分"),n("span",[t._v(t._s(t.end_second))]),t._v("秒")])]),t.page_data.user_postbtc?n("div",[n("div",{staticClass:"home-time-text"},[t._v("\n\t\t\t\t\t您已竞猜第"+t._s(t.page_data.user_postbtc.btc_id)+"期\n\t\t\t\t\t"),1==t.page_data.user_postbtc.type?n("span",{staticStyle:{"font-size":"14px"}},[t._v("看涨")]):t._e(),2==t.page_data.user_postbtc.type?n("span",{staticStyle:{"font-size":"14px"}},[t._v("看跌")]):t._e()]),n("div",{staticClass:"home-time-text"},[t._v("竞猜金额 "+t._s(t.page_data.user_postbtc.dw_money))]),n("div",{staticClass:"home-time-text"},[t._v("预计将在"+t._s(t.draw_time)+"揭晓结果")])]):t._e()]),n("div",{staticClass:"record"},[n("div",{staticClass:"record-title"},[t._m(0),n("div",{staticClass:"record-title-right",on:{click:t.goMore}},[t._v("\n\t\t\t\t\t预测记录\n\t\t\t\t\t"),n("van-icon",{attrs:{name:"arrow"}})],1)]),n("div",{staticClass:"record-content"},[n("div",{staticClass:"record-item"},[n("div",{staticClass:"record-data"},[t._v(t._s(t.page_data.win_num))]),n("div",{staticClass:"record-name"},[t._v("今日赚取")])]),n("div",{staticClass:"record-item"},[n("div",{staticClass:"record-data"},[t._v(t._s(t.page_data.rank))]),n("div",{staticClass:"record-name"},[t._v("排行榜")])]),n("div",{staticClass:"record-item"},[n("div",{staticClass:"record-data"},[t._v(t._s(t.page_data.win_count))]),n("div",{staticClass:"record-name"},[t._v("猜中")])])])]),n("van-popup",{on:{close:t.closeGuessFun},model:{value:t.showGuessPopup,callback:function(e){t.showGuessPopup=e},expression:"showGuessPopup"}},[n("div",{staticClass:"guess-box"},["1"==t.guess_type?n("img",{attrs:{src:s("23a5"),alt:""}}):t._e(),"2"==t.guess_type?n("img",{attrs:{src:s("83d9"),alt:""}}):t._e(),n("van-icon",{staticClass:"close-guess",attrs:{name:"cross"},on:{click:t.closeGuessFun}}),n("div",{staticClass:"guess-content"},[n("div",{staticClass:"guess-title"},[t._v("第"+t._s(t.page_data.btc_result.btc_id+2)+"期")]),n("div",{staticClass:"guess-text"},[t._v("竞猜金额：")]),n("div",{staticClass:"guess-input"},[n("van-field",{staticClass:"guess-input-left",attrs:{type:"number",placeholder:"请输入竞猜金额"},on:{input:t.moneyInputFun},model:{value:t.money,callback:function(e){t.money=e},expression:"money"}}),n("span",{staticClass:"guess-input-right"},[t._v("蚪金")])],1),n("div",{staticClass:"guess-text"},[t._v("您的蚪金金额："+t._s(t.user_info.dw_money))]),n("div",{staticClass:"guess-item"},t._l(t.money_item,function(e,s){return n("span",{key:s,class:{active:t.money_item_index==s},on:{click:function(n){return t.moneyItemClick(e,s)}}},[t._v(t._s(e))])}),0),n("div",{staticClass:"guess-btn-box"},[n("img",{attrs:{src:s("619f"),alt:""},on:{click:t.confirmGuess}})])])],1)]),n("van-popup",{model:{value:t.showGuessResultPopup,callback:function(e){t.showGuessResultPopup=e},expression:"showGuessResultPopup"}},[n("div",{staticClass:"guess-result-box"},[n("div",{staticClass:"guess-result-title"},[n("div",{staticClass:"guess-result-title-line"}),n("div",{staticClass:"guess-result-title-text"},[t._v("\n\t\t\t\t\t\t第"+t._s(t.guess_result.btc_id)+"期\n\t\t\t\t\t\t"),1==t.guess_result.type?n("span",[t._v("涨")]):t._e(),2==t.guess_result.type?n("span",[t._v("跌")]):t._e()]),n("div",{staticClass:"guess-result-title-line"})]),n("van-icon",{staticClass:"guess-result-close",attrs:{name:"cross"},on:{click:t.closeGuessResult}}),1==t.guess_result.status?n("div",{staticClass:"guess-result-text"},[t._v("恭喜竞猜正确，您本期获得奖励：")]):t._e(),2==t.guess_result.status?n("div",{staticClass:"guess-result-text"},[n("div",{staticStyle:{"padding-bottom":"5px"}},[t._v("很遗憾~")]),n("div",[t._v("您本期竞猜错误！")])]):t._e(),n("div",{staticClass:"guess-result-img"},[1==t.guess_result.status?n("img",{attrs:{src:s("1310"),alt:""}}):t._e(),2==t.guess_result.status?n("img",{attrs:{src:s("89da"),alt:""}}):t._e()]),1==t.guess_result.status?n("div",{staticClass:"guess-result-money"},[t._v("\n\t\t\t\t\t"+t._s(parseFloat(t.guess_result.win_dy))+"蚪金\n\t\t\t\t")]):t._e(),n("div",{staticClass:"guess-result-btn"},[n("img",{attrs:{src:s("c300"),alt:""},on:{click:t.closeGuessResult}})]),1==t.guess_result.status?n("div",{staticClass:"guess-result-tip"},[t._v("\n\t\t\t\t\t温馨提示：奖励将稍后直接发放，可在账单中查询\n\t\t\t\t")]):t._e()],1)]),t.showPayPasswordPopup?n("div",{staticClass:"popup"},[n("div",{staticClass:"popup-shadow"}),n("div",{staticClass:"popup-password-input"},[n("div",{staticClass:"popup-password-input-title"},[n("van-icon",{attrs:{name:"cross"},on:{click:t.closePayPassword}}),n("span",[t._v("输入支付密码，以验证身份")])],1),n("van-password-input",{attrs:{value:t.payPw}}),n("van-number-keyboard",{attrs:{show:!0,"hide-on-click-outside":!1},on:{input:t.onInput,delete:t.onDelete}})],1)]):t._e()],1)},a=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"record-title-left"},[s("span"),t._v("\n\t\t\t\t\t今日战绩\n\t\t\t\t")])}],i=(s("ac6a"),s("06db"),s("e814")),o=s.n(i),r=s("cebc"),c=s("2f62"),u=s("b970"),l={name:"home",data:function(){return{showGuessPopup:!1,showPayPasswordPopup:!1,payPw:"",timeInterval:"",timeResultInterval:"",page_data:{btc_result:{add_time:"",btc_id:"",btc_price:"",change:"",dispaly_name:""},user_postbtc:{add_time:"",btc_id:"",dw_money:"",id:"",status:"",type:""},fall:0,rank:0,risa:0,win_count:0,win_num:0,next_time:"",best_money:{user_avatar:"",user_name:"",best_dw:"",raword_num:""}},next_time_tmp:0,game_time_tmp:0,money_item:[20,100,200,400],money_item_index:-1,money:"",money_temp:"",moneyTemp:"",guess_type:"1",k_type:1,chartline:null,k_interval:null,showGuessResultPopup:!1,guess_result:{status:2,btc_id:1,dw_money:"200",win_dy:"100",type:1}}},computed:Object(r["a"])({},Object(c["b"])(["user_info"]),{end_minute:function(){var t=o()(Math.abs(this.page_data.next_time)/60);return t<10&&(t="0"+t),t},end_second:function(){var t=Math.abs(this.page_data.next_time)%60;return t<10&&(t="0"+t),t},draw_time:function(){var t=(new Date).getTime(),e=new Date(t+1e3*Math.abs(this.game_time_tmp)),s=e.getHours()<10?"0"+e.getHours():e.getHours(),n=e.getMinutes()<10?"0"+e.getMinutes():e.getMinutes();return s+":"+n},up_percent:function(){return 0==this.page_data.risa&&0==this.page_data.fall?50:this.page_data.risa},down_percent:function(){return 0==this.page_data.risa&&0==this.page_data.fall?50:this.page_data.fall}}),created:function(){var t=this;this.utils.info("user_info...",this.user_info),this.$route.params.id?(this.utils.info("有id...已登录..."),this.user_info.user_id=this.$route.params.id,this.getUserInfo("1")):(this.utils.info("没有id...未登录..."),u["a"].alert({title:"系统提示",message:"请先登录..."}).then(function(){t.utils.info("返回app..."),t.$bridge.callhandler("goBack",{tag:"返回app"},function(t){})})),this.utils.info("user_info...",this.user_info),this.chartline&&this.chartline.clear()},mounted:function(){var t=this;this.drawKLine(),this.getBtnFun(this.k_type,1),this.k_interval=setInterval(function(){t.getBtnFun(t.k_type,2)},1e4)},methods:{goBack:function(){this.utils.info("返回app..."),this.$bridge.callhandler("goBack",{tag:"返回app"},function(){})},goMore:function(){this.$bridge.callhandler("goMore",{tag:"战绩更多"},function(){})},goRulePage:function(){this.$router.push({name:"rule"})},showPayPassword:function(){this.showPayPasswordPopup=!0},closePayPassword:function(){this.showPayPasswordPopup=!1,this.payPw=""},onInput:function(t){var e=this;if(this.payPw=(this.payPw+t).slice(0,6),6==this.payPw.length){var s={pay_password:this.payPw,token:this.user_info.token,money:this.moneyTemp,type_id:this.guess_type};this.utils.showLoading(),this.closePayPassword(),this.http.post(this.http._HOST+"wave/buy",s).then(function(t){e.utils.info("猜涨跌...",t),e.utils.dismissLoading(),t.success?(e.utils.showToast("竞猜成功"),e.getUserInfo("1")):e.utils.showAlert(t.message)})}},onDelete:function(){this.payPw=this.payPw.slice(0,this.payPw.length-1)},guessFun:function(t){var e=this;if(""==this.user_info.pay_password)return u["a"].alert({title:"系统提示",message:"请先设置支付密码"}).then(function(){e.utils.info("设置支付密码..."),e.$bridge.callhandler("goPay",{tag:"设置支付密码"},function(t){})}),!1;this.guess_type=t,this.showGuessPopup=!0},closeGuessFun:function(){this.showGuessPopup=!1,this.money="",this.money_item_index=-1},moneyInputFun:function(){this.utils.info("money1111...",this.money),this.money_item_index>-1&&(this.money_item_index=-1),""!=this.money?(this.money=o()(this.money)+"",o()(this.money)>400&&(this.money="400"),o()(this.money)>o()(this.user_info.dw_money)&&(this.money=o()(this.user_info.dw_money)+"")):this.money="0",this.utils.info("money2222...",this.money)},moneyItemClick:function(t,e){return this.money_item_index!=e&&(o()(t)>o()(this.user_info.dw_money)?(this.utils.showToast("您的蚪金金额不足"),!1):(this.money_item_index=e,void(this.money=t)))},confirmGuess:function(){if(""==this.money||"0"==this.money)return this.utils.showToast("请输入竞猜金额"),!1;this.moneyTemp=this.money,this.closeGuessFun(),this.showPayPassword()},getBtnFun:function(t,e){var s=this,n="";1==t&&(n="1min"),2==t&&(n="5min"),3==t&&(n="15min"),4==t&&(n="30min"),this.chartline&&e&&"1"==e&&this.chartline.showLoading();var a="http://api.bitkk.com/data/v1/kline?market=btc_usdt&type="+n+"&size=40";this.$jsonp(a).then(function(t){s.utils.info("获取K线图信息...",t);for(var e=t.data,n=[],a=0;a<e.length;a++){var i=[];i.push(s.utils.timeFormat(e[a][0])),i.push(e[a][1]),i.push(e[a][4]),i.push(e[a][3]),i.push(e[a][2]),n.push(i)}var o=s.splitData(n);if(s.chartline){s.utils.info("k线图数据更新..."),s.chartline.hideLoading();var r="#00da3c",c="#008F28",u="#ec0000",l="#8A0000",p={tooltip:{trigger:"axis",axisPointer:{type:"cross"}},legend:{data:["日K","MA5","MA10","MA20"],selectedMode:!1,left:0,top:0,itemGap:10},grid:{left:"40",right:"0",bottom:"30",top:"40"},xAxis:{type:"category",data:o.categoryData,scale:!0,boundaryGap:!1,axisLine:{onZero:!1},splitLine:{show:!1},splitNumber:30,min:"dataMin",max:"dataMax"},yAxis:{scale:!0,splitArea:{show:!0}},dataZoom:[{type:"inside",start:50,end:100}],series:[{name:"日K",type:"candlestick",data:o.values,itemStyle:{normal:{color:r,color0:u,borderColor:c,borderColor0:l}}},{name:"MA5",type:"line",data:s.calculateMA(o,5),smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA10",type:"line",data:s.calculateMA(o,10),smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA20",type:"line",data:s.calculateMA(o,20),smooth:!0,lineStyle:{normal:{opacity:.5}}}]};s.chartline.setOption(p)}}).catch(function(t){s.utils.info("获取K线图信息err...",t)})},splitData:function(t){for(var e=[],s=[],n=0;n<t.length;n++)e.push(t[n].splice(0,1)[0]),s.push(t[n]);return{categoryData:e,values:s}},calculateMA:function(t,e){for(var s=[],n=0,a=t.values.length;n<a;n++)if(n<e)s.push("-");else{for(var i=0,o=0;o<e;o++)i+=t.values[n-o][1];s.push((i/e).toFixed(4))}return s},getUserInfo:function(t){var e=this,s="dy"+this.user_info.user_id+this.utils.randomNumber(4);this.http.post(this.http._HOST+"login/user_detail",{user_id:s}).then(function(s){if(e.utils.info("获取用户信息...",s),s.success){var n=s.data;e.user_info.dw_money=n.dw_money,e.user_info.pay_password=n.pay_password,e.user_info.token=n.token,"1"==t&&e.getPageInfo()}})},getPageInfo:function(){var t=this,e={token:this.user_info.token};this.http.post(this.http._HOST+"wave/show_btc",e).then(function(e){t.utils.info("获取页面数据...",e),e.success&&(t.page_data=e.data,0==t.page_data.risa&&0==t.page_data.fall?t.$refs.homeProgressLeft.style.width="50%":t.$refs.homeProgressLeft.style.width=t.page_data.risa+"%",t.timeInterval&&clearInterval(t.timeInterval),t.timeResultInterval&&clearInterval(t.timeResultInterval),t.page_data.user_postbtc&&(t.page_data.user_postbtc.game_time=o()(Math.abs(t.page_data.user_postbtc.game_time))+30,t.game_time_tmp=o()(Math.abs(t.page_data.user_postbtc.game_time))+30,t.timeResultInterval=setInterval(function(){t.page_data.user_postbtc.game_time--,0==t.page_data.user_postbtc.game_time&&(t.utils.info("开奖..."),t.getDrawResult(t.page_data.user_postbtc.btc_id),clearInterval(t.timeResultInterval),t.getPageInfo())},1e3)),t.page_data.next_time=o()(Math.abs(t.page_data.next_time))+30,t.next_time_tmp=o()(Math.abs(t.page_data.next_time))+30,t.timeInterval=setInterval(function(){t.page_data.next_time--,0==t.page_data.next_time&&(t.utils.info("时间结束，获取新期数信息..."),clearInterval(t.timeInterval),t.getPageInfo())},1e3))})},getDrawResult:function(t){var e=this,s={btc_id:t,token:this.user_info.token};this.http.post(this.http._HOST+"wave/new_result",s).then(function(t){e.utils.info("获取开奖结果...",t),t.success&&(e.guess_result=t.data,e.showGuessResultPopup=!0)})},drawKLine:function(){var t="#ec0000",e="#8A0000",s="#00da3c",n="#008F28",a={tooltip:{trigger:"axis",axisPointer:{type:"cross"}},legend:{data:["日K","MA5","MA10","MA20"],selectedMode:!1,left:0,top:0,itemGap:10},grid:{left:"40",right:"0",bottom:"30",top:"40"},xAxis:{type:"category",data:[],scale:!0,boundaryGap:!1,axisLine:{onZero:!1},splitLine:{show:!1},splitNumber:30,min:"dataMin",max:"dataMax"},yAxis:{scale:!0,splitArea:{show:!0}},dataZoom:[{type:"inside",start:50,end:100}],series:[{name:"日K",type:"candlestick",data:[],itemStyle:{normal:{color:t,color0:s,borderColor:e,borderColor0:n}}},{name:"MA5",type:"line",data:[],smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA10",type:"line",data:[],smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA20",type:"line",data:[],smooth:!0,lineStyle:{normal:{opacity:.5}}}]};this.chartline=this.$echarts.init(this.$refs.homeChartContent),this.chartline.setOption(a)},kTypeChange:function(t){if(this.k_type==t)return!1;this.k_type=t,this.chartline&&this.chartline.clear(),this.getBtnFun(this.k_type,1)},getDefaultImg:function(t){var e=s("dcaf");t.target.src=e},closeGuessResult:function(){this.showGuessResultPopup=!1}},beforeDestroy:function(){this.utils.warn("页面即将销毁...请清除定时器..."),this.k_interval&&clearInterval(this.k_interval),this.timeInterval&&clearInterval(this.timeInterval),this.timeResultInterval&&clearInterval(this.timeResultInterval)}},p=l,f=(s("4be0"),s("2877")),d=Object(f["a"])(p,n,a,!1,null,"44f87315",null);e["default"]=d.exports},bf0b:function(t,e,s){var n=s("355d"),a=s("aebd"),i=s("36c3"),o=s("1bc3"),r=s("07e3"),c=s("794b"),u=Object.getOwnPropertyDescriptor;e.f=s("8e60")?u:function(t,e){if(t=i(t),e=o(e,!0),c)try{return u(t,e)}catch(s){}if(r(t,e))return a(!n.f.call(t,e),t[e])}},bf90:function(t,e,s){var n=s("36c3"),a=s("bf0b").f;s("ce7e")("getOwnPropertyDescriptor",function(){return function(t,e){return a(n(t),e)}})},c300:function(t,e){t.exports="data:image/png;base64,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"},cac3:function(t,e,s){t.exports=s.p+"img/guess-down.9d1f8d5f.png"},ccb9:function(t,e,s){e.f=s("5168")},ce7e:function(t,e,s){var n=s("63b6"),a=s("584a"),i=s("294c");t.exports=function(t,e){var s=(a.Object||{})[t]||Object[t],o={};o[t]=e(s),n(n.S+n.F*i(function(){s(1)}),"Object",o)}},cebc:function(t,e,s){"use strict";var n=s("268f"),a=s.n(n),i=s("e265"),o=s.n(i),r=s("a4bb"),c=s.n(r),u=s("85f2"),l=s.n(u);function p(t,e,s){return e in t?l()(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}function f(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{},n=c()(s);"function"===typeof o.a&&(n=n.concat(o()(s).filter(function(t){return a()(s,t).enumerable}))),n.forEach(function(e){p(t,e,s[e])})}return t}s.d(e,"a",function(){return f})},dcaf:function(t,e,s){t.exports=s.p+"img/default-user.4fcb948c.png"},e265:function(t,e,s){t.exports=s("ed33")},e692:function(t,e){t.exports="\t\n\v\f\r   ᠎             　\u2028\u2029\ufeff"},e814:function(t,e,s){t.exports=s("b9e9")},ebfd:function(t,e,s){var n=s("62a0")("meta"),a=s("f772"),i=s("07e3"),o=s("d9f6").f,r=0,c=Object.isExtensible||function(){return!0},u=!s("294c")(function(){return c(Object.preventExtensions({}))}),l=function(t){o(t,n,{value:{i:"O"+ ++r,w:{}}})},p=function(t,e){if(!a(t))return"symbol"==typeof t?t:("string"==typeof t?"S":"P")+t;if(!i(t,n)){if(!c(t))return"F";if(!e)return"E";l(t)}return t[n].i},f=function(t,e){if(!i(t,n)){if(!c(t))return!0;if(!e)return!1;l(t)}return t[n].w},d=function(t){return u&&h.NEED&&c(t)&&!i(t,n)&&l(t),t},h=t.exports={KEY:n,NEED:!1,fastKey:p,getWeak:f,onFreeze:d}},ed33:function(t,e,s){s("014b"),t.exports=s("584a").Object.getOwnPropertySymbols},fde4:function(t,e,s){s("bf90");var n=s("584a").Object;t.exports=function(t,e){return n.getOwnPropertyDescriptor(t,e)}}}]);
//# sourceMappingURL=chunk-666fa65d.b5d347c0.js.map