(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-07a2177d"],{"014b":function(t,e,s){"use strict";var n=s("e53d"),i=s("07e3"),a=s("8e60"),o=s("63b6"),r=s("9138"),c=s("ebfd").KEY,u=s("294c"),l=s("dbdb"),p=s("45f2"),f=s("62a0"),d=s("5168"),h=s("ccb9"),_=s("6718"),m=s("47ee"),g=s("9003"),v=s("e4ae"),y=s("f772"),b=s("36c3"),w=s("1bc3"),x=s("aebd"),C=s("a159"),P=s("0395"),k=s("bf0b"),S=s("d9f6"),M=s("c3a1"),O=k.f,I=S.f,L=P.f,T=n.Symbol,A=n.JSON,F=A&&A.stringify,G="prototype",j=d("_hidden"),R=d("toPrimitive"),D={}.propertyIsEnumerable,E=l("symbol-registry"),N=l("symbols"),$=l("op-symbols"),H=Object[G],K="function"==typeof T,B=n.QObject,V=!B||!B[G]||!B[G].findChild,z=a&&u(function(){return 7!=C(I({},"a",{get:function(){return I(this,"a",{value:7}).a}})).a})?function(t,e,s){var n=O(H,e);n&&delete H[e],I(t,e,s),n&&t!==H&&I(H,e,n)}:I,J=function(t){var e=N[t]=C(T[G]);return e._k=t,e},Z=K&&"symbol"==typeof T.iterator?function(t){return"symbol"==typeof t}:function(t){return t instanceof T},U=function(t,e,s){return t===H&&U($,e,s),v(t),e=w(e,!0),v(s),i(N,e)?(s.enumerable?(i(t,j)&&t[j][e]&&(t[j][e]=!1),s=C(s,{enumerable:x(0,!1)})):(i(t,j)||I(t,j,x(1,{})),t[j][e]=!0),z(t,e,s)):I(t,e,s)},W=function(t,e){v(t);var s,n=m(e=b(e)),i=0,a=n.length;while(a>i)U(t,s=n[i++],e[s]);return t},Y=function(t,e){return void 0===e?C(t):W(C(t),e)},q=function(t){var e=D.call(this,t=w(t,!0));return!(this===H&&i(N,t)&&!i($,t))&&(!(e||!i(this,t)||!i(N,t)||i(this,j)&&this[j][t])||e)},Q=function(t,e){if(t=b(t),e=w(e,!0),t!==H||!i(N,e)||i($,e)){var s=O(t,e);return!s||!i(N,e)||i(t,j)&&t[j][e]||(s.enumerable=!0),s}},X=function(t){var e,s=L(b(t)),n=[],a=0;while(s.length>a)i(N,e=s[a++])||e==j||e==c||n.push(e);return n},tt=function(t){var e,s=t===H,n=L(s?$:b(t)),a=[],o=0;while(n.length>o)!i(N,e=n[o++])||s&&!i(H,e)||a.push(N[e]);return a};K||(T=function(){if(this instanceof T)throw TypeError("Symbol is not a constructor!");var t=f(arguments.length>0?arguments[0]:void 0),e=function(s){this===H&&e.call($,s),i(this,j)&&i(this[j],t)&&(this[j][t]=!1),z(this,t,x(1,s))};return a&&V&&z(H,t,{configurable:!0,set:e}),J(t)},r(T[G],"toString",function(){return this._k}),k.f=Q,S.f=U,s("6abf").f=P.f=X,s("355d").f=q,s("9aa9").f=tt,a&&!s("b8e3")&&r(H,"propertyIsEnumerable",q,!0),h.f=function(t){return J(d(t))}),o(o.G+o.W+o.F*!K,{Symbol:T});for(var et="hasInstance,isConcatSpreadable,iterator,match,replace,search,species,split,toPrimitive,toStringTag,unscopables".split(","),st=0;et.length>st;)d(et[st++]);for(var nt=M(d.store),it=0;nt.length>it;)_(nt[it++]);o(o.S+o.F*!K,"Symbol",{for:function(t){return i(E,t+="")?E[t]:E[t]=T(t)},keyFor:function(t){if(!Z(t))throw TypeError(t+" is not a symbol!");for(var e in E)if(E[e]===t)return e},useSetter:function(){V=!0},useSimple:function(){V=!1}}),o(o.S+o.F*!K,"Object",{create:Y,defineProperty:U,defineProperties:W,getOwnPropertyDescriptor:Q,getOwnPropertyNames:X,getOwnPropertySymbols:tt}),A&&o(o.S+o.F*(!K||u(function(){var t=T();return"[null]"!=F([t])||"{}"!=F({a:t})||"{}"!=F(Object(t))})),"JSON",{stringify:function(t){var e,s,n=[t],i=1;while(arguments.length>i)n.push(arguments[i++]);if(s=e=n[1],(y(e)||void 0!==t)&&!Z(t))return g(e)||(e=function(t,e){if("function"==typeof s&&(e=s.call(this,t,e)),!Z(e))return e}),n[1]=e,F.apply(A,n)}}),T[G][R]||s("35e8")(T[G],R,T[G].valueOf),p(T,"Symbol"),p(Math,"Math",!0),p(n.JSON,"JSON",!0)},"0395":function(t,e,s){var n=s("36c3"),i=s("6abf").f,a={}.toString,o="object"==typeof window&&window&&Object.getOwnPropertyNames?Object.getOwnPropertyNames(window):[],r=function(t){try{return i(t)}catch(e){return o.slice()}};t.exports.f=function(t){return o&&"[object Window]"==a.call(t)?r(t):i(n(t))}},"06db":function(t,e,s){"use strict";var n=s("23c6"),i={};i[s("2b4c")("toStringTag")]="z",i+""!="[object z]"&&s("2aba")(Object.prototype,"toString",function(){return"[object "+n(this)+"]"},!0)},1310:function(t,e,s){t.exports=s.p+"img/guess-succ.3fd1112f.png"},"23a5":function(t,e,s){t.exports=s.p+"img/up.eb7fad83.png"},"268f":function(t,e,s){t.exports=s("fde4")},"2fb6":function(t,e,s){t.exports=s.p+"img/go-rule.b785a878.png"},"32a6":function(t,e,s){var n=s("241e"),i=s("c3a1");s("ce7e")("keys",function(){return function(t){return i(n(t))}})},"32e2":function(t,e,s){t.exports=s.p+"img/guess-up.34e78bd7.png"},"355d":function(t,e){e.f={}.propertyIsEnumerable},"454f":function(t,e,s){s("46a7");var n=s("584a").Object;t.exports=function(t,e,s){return n.defineProperty(t,e,s)}},"46a7":function(t,e,s){var n=s("63b6");n(n.S+n.F*!s("8e60"),"Object",{defineProperty:s("d9f6").f})},"47ee":function(t,e,s){var n=s("c3a1"),i=s("9aa9"),a=s("355d");t.exports=function(t){var e=n(t),s=i.f;if(s){var o,r=s(t),c=a.f,u=0;while(r.length>u)c.call(t,o=r[u++])&&e.push(o)}return e}},"56eb":function(t,e,s){},"5d6b":function(t,e,s){var n=s("e53d").parseInt,i=s("a1ce").trim,a=s("e692"),o=/^[-+]?0[xX]/;t.exports=8!==n(a+"08")||22!==n(a+"0x16")?function(t,e){var s=i(String(t),3);return n(s,e>>>0||(o.test(s)?16:10))}:n},"619f":function(t,e,s){t.exports=s.p+"img/up-down-btn.621f3a0a.png"},6718:function(t,e,s){var n=s("e53d"),i=s("584a"),a=s("b8e3"),o=s("ccb9"),r=s("d9f6").f;t.exports=function(t){var e=i.Symbol||(i.Symbol=a?{}:n.Symbol||{});"_"==t.charAt(0)||t in e||r(e,t,{value:o.f(t)})}},"6abf":function(t,e,s){var n=s("e6f3"),i=s("1691").concat("length","prototype");e.f=Object.getOwnPropertyNames||function(t){return n(t,i)}},7445:function(t,e,s){var n=s("63b6"),i=s("5d6b");n(n.G+n.F*(parseInt!=i),{parseInt:i})},"83d9":function(t,e,s){t.exports=s.p+"img/down.f519da8d.png"},"85f2":function(t,e,s){t.exports=s("454f")},"89da":function(t,e,s){t.exports=s.p+"img/guess-fail.040e02fa.png"},"8aae":function(t,e,s){s("32a6"),t.exports=s("584a").Object.keys},9003:function(t,e,s){var n=s("6b4c");t.exports=Array.isArray||function(t){return"Array"==n(t)}},"943d":function(t,e,s){"use strict";var n=s("56eb"),i=s.n(n);i.a},"9aa9":function(t,e){e.f=Object.getOwnPropertySymbols},a1ce:function(t,e,s){var n=s("63b6"),i=s("25eb"),a=s("294c"),o=s("e692"),r="["+o+"]",c="​",u=RegExp("^"+r+r+"*"),l=RegExp(r+r+"*$"),p=function(t,e,s){var i={},r=a(function(){return!!o[t]()||c[t]()!=c}),u=i[t]=r?e(f):o[t];s&&(i[s]=u),n(n.P+n.F*r,"String",i)},f=p.trim=function(t,e){return t=String(i(t)),1&e&&(t=t.replace(u,"")),2&e&&(t=t.replace(l,"")),t};t.exports=p},a4bb:function(t,e,s){t.exports=s("8aae")},ac6a:function(t,e,s){for(var n=s("cadf"),i=s("0d58"),a=s("2aba"),o=s("7726"),r=s("32e9"),c=s("84f2"),u=s("2b4c"),l=u("iterator"),p=u("toStringTag"),f=c.Array,d={CSSRuleList:!0,CSSStyleDeclaration:!1,CSSValueList:!1,ClientRectList:!1,DOMRectList:!1,DOMStringList:!1,DOMTokenList:!0,DataTransferItemList:!1,FileList:!1,HTMLAllCollection:!1,HTMLCollection:!1,HTMLFormElement:!1,HTMLSelectElement:!1,MediaList:!0,MimeTypeArray:!1,NamedNodeMap:!1,NodeList:!0,PaintRequestList:!1,Plugin:!1,PluginArray:!1,SVGLengthList:!1,SVGNumberList:!1,SVGPathSegList:!1,SVGPointList:!1,SVGStringList:!1,SVGTransformList:!1,SourceBufferList:!1,StyleSheetList:!0,TextTrackCueList:!1,TextTrackList:!1,TouchList:!1},h=i(d),_=0;_<h.length;_++){var m,g=h[_],v=d[g],y=o[g],b=y&&y.prototype;if(b&&(b[l]||r(b,l,f),b[p]||r(b,p,g),c[g]=f,v))for(m in n)b[m]||a(b,m,n[m],!0)}},b9e9:function(t,e,s){s("7445"),t.exports=s("584a").parseInt},bb51:function(t,e,s){"use strict";s.r(e);var n=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"home"},[n("van-nav-bar",{attrs:{title:"","left-arrow":"",border:!1},on:{"click-left":t.goBack}}),n("img",{staticClass:"go-rule",attrs:{src:s("2fb6"),alt:""},on:{click:t.goRulePage}}),n("div",{staticClass:"space"}),n("div",{staticClass:"home-chart-box"},[n("div",{staticClass:"home-chart-title"},[t._v("当前BTC时价：\n\t\t\t\t"),n("span",[t._v("$"+t._s(t.page_data.btc_result.btc_price))]),parseFloat(t.page_data.btc_result.change)<0?n("span",{staticStyle:{color:"green"}},[t._v(t._s(t.page_data.btc_result.change)+"%")]):t._e(),parseFloat(t.page_data.btc_result.change)>=0?n("span",{staticStyle:{color:"red"}},[t._v(t._s(t.page_data.btc_result.change)+"%")]):t._e()]),n("div",{staticClass:"home-chart"},[n("div",{ref:"homeChartContent",staticClass:"home-chart-content"})]),n("div",{staticClass:"k-type"},[n("span",{class:{active:1==t.k_type},on:{click:function(e){return t.kTypeChange(1)}}},[t._v("1M")]),n("span",{class:{active:2==t.k_type},on:{click:function(e){return t.kTypeChange(2)}}},[t._v("5M")]),n("span",{class:{active:3==t.k_type},on:{click:function(e){return t.kTypeChange(3)}}},[t._v("15M")]),n("span",{class:{active:4==t.k_type},on:{click:function(e){return t.kTypeChange(4)}}},[t._v("30M")])]),n("div",{staticClass:"home-progress"},[n("span",[t._v(t._s(t.down_percent)+"%")]),n("div",{ref:"homeProgressLeft",staticClass:"home-progress-left"},[t._v(t._s(t.up_percent)+"%")])]),t.page_data.user_postbtc?t._e():n("div",[n("div",{staticClass:"home-chart-btn"},[n("img",{attrs:{src:s("32e2"),alt:""},on:{click:function(e){return t.guessFun("1")}}}),n("img",{attrs:{src:s("cac3"),alt:""},on:{click:function(e){return t.guessFun("2")}}})]),n("div",{staticClass:"home-time-text"},[t._v("您还未参与竞猜哦~")]),n("div",{staticClass:"home-time-text"},[t._v("距离第"+t._s(t.page_data.btc_result.btc_id+1)+"期竞猜结束还剩"),n("span",[t._v(t._s(t.end_minute))]),t._v("分"),n("span",[t._v(t._s(t.end_second))]),t._v("秒")])]),t.page_data.user_postbtc?n("div",[n("div",{staticClass:"home-time-text"},[t._v("\n\t\t\t\t\t您已竞猜第"+t._s(t.page_data.user_postbtc.btc_id)+"期\n\t\t\t\t\t"),1==t.page_data.user_postbtc.type?n("span",{staticStyle:{"font-size":"14px"}},[t._v("看涨")]):t._e(),2==t.page_data.user_postbtc.type?n("span",{staticStyle:{"font-size":"14px"}},[t._v("看跌")]):t._e()]),n("div",{staticClass:"home-time-text"},[t._v("竞猜金额 "+t._s(t.page_data.user_postbtc.dw_money))]),n("div",{staticClass:"home-time-text"},[t._v("预计将在"+t._s(t.draw_time)+"揭晓结果")])]):t._e()]),n("div",{staticClass:"record"},[n("div",{staticClass:"record-title"},[t._m(0),n("div",{staticClass:"record-title-right",on:{click:t.goMore}},[t._v("\n\t\t\t\t\t预测记录\n\t\t\t\t\t"),n("van-icon",{attrs:{name:"arrow"}})],1)]),n("div",{staticClass:"record-content"},[n("div",{staticClass:"record-item"},[n("div",{staticClass:"record-data"},[t._v(t._s(t.page_data.win_num))]),n("div",{staticClass:"record-name"},[t._v("今日赚取")])]),n("div",{staticClass:"record-item"},[n("div",{staticClass:"record-data"},[t._v(t._s(t.page_data.rank))]),n("div",{staticClass:"record-name"},[t._v("排行榜")])]),n("div",{staticClass:"record-item"},[n("div",{staticClass:"record-data"},[t._v(t._s(t.page_data.win_count))]),n("div",{staticClass:"record-name"},[t._v("猜中")])])])]),n("van-popup",{on:{close:t.closeGuessFun},model:{value:t.showGuessPopup,callback:function(e){t.showGuessPopup=e},expression:"showGuessPopup"}},[n("div",{staticClass:"guess-box"},["1"==t.guess_type?n("img",{attrs:{src:s("23a5"),alt:""}}):t._e(),"2"==t.guess_type?n("img",{attrs:{src:s("83d9"),alt:""}}):t._e(),n("van-icon",{staticClass:"close-guess",attrs:{name:"cross"},on:{click:t.closeGuessFun}}),n("div",{staticClass:"guess-content"},[n("div",{staticClass:"guess-title"},[t._v("第"+t._s(t.page_data.btc_result.btc_id+2)+"期")]),n("div",{staticClass:"guess-text"},[t._v("竞猜金额：")]),n("div",{staticClass:"guess-input"},[n("van-field",{staticClass:"guess-input-left",attrs:{type:"number",placeholder:"请输入竞猜金额"},on:{input:t.moneyInputFun},model:{value:t.money,callback:function(e){t.money=e},expression:"money"}}),n("span",{staticClass:"guess-input-right"},[t._v("蚪金")])],1),n("div",{staticClass:"guess-text"},[t._v("您的蚪金金额："+t._s(t.user_info.dw_money))]),n("div",{staticClass:"guess-item"},t._l(t.money_item,function(e,s){return n("span",{key:s,class:{active:t.money_item_index==s},on:{click:function(n){return t.moneyItemClick(e,s)}}},[t._v(t._s(e))])}),0),n("div",{staticClass:"guess-btn-box"},[n("img",{attrs:{src:s("619f"),alt:""},on:{click:t.confirmGuess}})])])],1)]),n("van-popup",{model:{value:t.showGuessResultPopup,callback:function(e){t.showGuessResultPopup=e},expression:"showGuessResultPopup"}},[n("div",{staticClass:"guess-result-box"},[n("div",{staticClass:"guess-result-title"},[n("div",{staticClass:"guess-result-title-line"}),n("div",{staticClass:"guess-result-title-text"},[t._v("\n\t\t\t\t\t\t第"+t._s(t.guess_result.btc_id)+"期\n\t\t\t\t\t\t"),1==t.guess_result.type?n("span",[t._v("涨")]):t._e(),2==t.guess_result.type?n("span",[t._v("跌")]):t._e()]),n("div",{staticClass:"guess-result-title-line"})]),n("van-icon",{staticClass:"guess-result-close",attrs:{name:"cross"},on:{click:t.closeGuessResult}}),1==t.guess_result.status?n("div",{staticClass:"guess-result-text"},[t._v("恭喜竞猜正确，您本期获得奖励：")]):t._e(),2==t.guess_result.status?n("div",{staticClass:"guess-result-text"},[n("div",{staticStyle:{"padding-bottom":"5px"}},[t._v("很遗憾~")]),n("div",[t._v("您本期竞猜错误！")])]):t._e(),n("div",{staticClass:"guess-result-img"},[1==t.guess_result.status?n("img",{attrs:{src:s("1310"),alt:""}}):t._e(),2==t.guess_result.status?n("img",{attrs:{src:s("89da"),alt:""}}):t._e()]),1==t.guess_result.status?n("div",{staticClass:"guess-result-money"},[t._v("\n\t\t\t\t\t"+t._s(parseFloat(t.guess_result.win_dy))+"蚪金\n\t\t\t\t")]):t._e(),n("div",{staticClass:"guess-result-btn"},[n("img",{attrs:{src:s("c300"),alt:""},on:{click:t.closeGuessResult}})]),1==t.guess_result.status?n("div",{staticClass:"guess-result-tip"},[t._v("\n\t\t\t\t\t温馨提示：奖励将稍后直接发放，可在账单中查询\n\t\t\t\t")]):t._e()],1)]),t.showPayPasswordPopup?n("div",{staticClass:"popup"},[n("div",{staticClass:"popup-shadow"}),n("div",{staticClass:"popup-password-input"},[n("div",{staticClass:"popup-password-input-title"},[n("van-icon",{attrs:{name:"cross"},on:{click:t.closePayPassword}}),n("span",[t._v("输入支付密码，以验证身份")])],1),n("van-password-input",{attrs:{value:t.payPw}}),n("van-number-keyboard",{attrs:{show:!0,"hide-on-click-outside":!1},on:{input:t.onInput,delete:t.onDelete}})],1)]):t._e()],1)},i=[function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"record-title-left"},[s("span"),t._v("\n\t\t\t\t\t今日战绩\n\t\t\t\t")])}],a=(s("ac6a"),s("06db"),s("e814")),o=s.n(a),r=s("cebc"),c=s("2f62"),u=s("b970"),l={name:"home",data:function(){return{showGuessPopup:!1,showPayPasswordPopup:!1,payPw:"",timeInterval:"",timeResultInterval:"",page_data:{btc_result:{add_time:"",btc_id:"",btc_price:"",change:"",dispaly_name:""},user_postbtc:{add_time:"",btc_id:"",dw_money:"",id:"",status:"",type:""},fall:0,rank:0,risa:0,win_count:0,win_num:0,next_time:"",best_money:{user_avatar:"",user_name:"",best_dw:"",raword_num:""}},next_time_tmp:0,game_time_tmp:0,money_item:[20,100,200,400],money_item_index:-1,money:"",money_temp:"",moneyTemp:"",guess_type:"1",k_type:1,chartline:null,k_interval:null,showGuessResultPopup:!1,guess_result:{status:2,btc_id:1,dw_money:"200",win_dy:"100",type:1}}},computed:Object(r["a"])({},Object(c["c"])(["user_info"]),{end_minute:function(){var t=o()(Math.abs(this.page_data.next_time)/60);return t<10&&(t="0"+t),t},end_second:function(){var t=Math.abs(this.page_data.next_time)%60;return t<10&&(t="0"+t),t},draw_time:function(){var t=(new Date).getTime(),e=new Date(t+1e3*Math.abs(this.game_time_tmp)),s=e.getHours()<10?"0"+e.getHours():e.getHours(),n=e.getMinutes()<10?"0"+e.getMinutes():e.getMinutes();return s+":"+n},up_percent:function(){return 0==this.page_data.risa&&0==this.page_data.fall?50:this.page_data.risa},down_percent:function(){return 0==this.page_data.risa&&0==this.page_data.fall?50:this.page_data.fall}}),created:function(){var t=this;this.utils.info("user_info...",this.user_info),this.$route.params.id?(this.utils.info("有id...已登录..."),this.user_info.user_id=this.$route.params.id,this.getUserInfo("1")):(this.utils.info("没有id...未登录..."),u["a"].alert({title:"系统提示",message:"请先登录..."}).then(function(){t.utils.info("返回app..."),t.$bridge.callhandler("goBack",{tag:"返回app"},function(t){})})),this.utils.info("user_info...",this.user_info),this.chartline&&this.chartline.clear()},mounted:function(){var t=this;this.drawKLine(),this.getBtnFun(this.k_type,1),this.k_interval=setInterval(function(){t.getBtnFun(t.k_type,2)},1e4)},methods:{goBack:function(){this.utils.info("返回app..."),this.$bridge.callhandler("goBack",{tag:"返回app"},function(t){})},goMore:function(){this.utils.info("战绩更多"),this.$bridge.callhandler("goMore",{tag:"战绩更多"},function(t){})},goRulePage:function(){this.$router.push({name:"rule"})},showPayPassword:function(){this.showPayPasswordPopup=!0},closePayPassword:function(){this.showPayPasswordPopup=!1,this.payPw=""},onInput:function(t){var e=this;if(this.payPw=(this.payPw+t).slice(0,6),6==this.payPw.length){var s={pay_password:this.payPw,token:this.user_info.token,money:this.moneyTemp,type_id:this.guess_type};this.utils.showLoading(),this.closePayPassword(),this.http.post(this.http._HOST+"wave/buy",s).then(function(t){e.utils.info("猜涨跌...",t),e.utils.dismissLoading(),t.success?(e.utils.showToast("竞猜成功"),e.getUserInfo("1")):e.utils.showAlert(t.message)})}},onDelete:function(){this.payPw=this.payPw.slice(0,this.payPw.length-1)},guessFun:function(t){if(""==this.user_info.pay_password)return this.utils.showAlert("请先设置支付密码"),!1;this.guess_type=t,this.showGuessPopup=!0},closeGuessFun:function(){this.showGuessPopup=!1,this.money="",this.money_item_index=-1},moneyInputFun:function(){this.utils.info("money1111...",this.money),this.money_item_index>-1&&(this.money_item_index=-1),""!=this.money?(this.money=o()(this.money)+"",o()(this.money)>400&&(this.money="400"),o()(this.money)>o()(this.user_info.dw_money)&&(this.money=o()(this.user_info.dw_money)+"")):this.money="0",this.utils.info("money2222...",this.money)},moneyItemClick:function(t,e){return this.money_item_index!=e&&(o()(t)>o()(this.user_info.dw_money)?(this.utils.showToast("您的蚪金金额不足"),!1):(this.money_item_index=e,void(this.money=t)))},confirmGuess:function(){if(""==this.money||"0"==this.money)return this.utils.showToast("请输入竞猜金额"),!1;this.moneyTemp=this.money,this.closeGuessFun(),this.showPayPassword()},getBtnFun:function(t,e){var s=this,n="";1==t&&(n="1min"),2==t&&(n="5min"),3==t&&(n="15min"),4==t&&(n="30min"),this.chartline&&e&&"1"==e&&this.chartline.showLoading();var i="http://api.bitkk.com/data/v1/kline?market=btc_usdt&type="+n+"&size=40";this.$jsonp(i).then(function(t){s.utils.info("获取K线图信息...",t);for(var e=t.data,n=[],i=0;i<e.length;i++){var a=[];a.push(s.utils.timeFormat(e[i][0])),a.push(e[i][1]),a.push(e[i][4]),a.push(e[i][3]),a.push(e[i][2]),n.push(a)}var o=s.splitData(n);if(s.chartline){console.log("k线图数据更新..."),s.chartline.hideLoading();var r="#00da3c",c="#008F28",u="#ec0000",l="#8A0000",p={tooltip:{trigger:"axis",axisPointer:{type:"cross"}},legend:{data:["日K","MA5","MA10","MA20"],selectedMode:!1,left:0,top:0,itemGap:10},grid:{left:"40",right:"0",bottom:"30",top:"40"},xAxis:{type:"category",data:o.categoryData,scale:!0,boundaryGap:!1,axisLine:{onZero:!1},splitLine:{show:!1},splitNumber:30,min:"dataMin",max:"dataMax"},yAxis:{scale:!0,splitArea:{show:!0}},dataZoom:[{type:"inside",start:50,end:100}],series:[{name:"日K",type:"candlestick",data:o.values,itemStyle:{normal:{color:r,color0:u,borderColor:c,borderColor0:l}}},{name:"MA5",type:"line",data:s.calculateMA(o,5),smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA10",type:"line",data:s.calculateMA(o,10),smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA20",type:"line",data:s.calculateMA(o,20),smooth:!0,lineStyle:{normal:{opacity:.5}}}]};s.chartline.setOption(p)}}).catch(function(t){s.utils.info("获取K线图信息err...",t)})},splitData:function(t){for(var e=[],s=[],n=0;n<t.length;n++)e.push(t[n].splice(0,1)[0]),s.push(t[n]);return{categoryData:e,values:s}},calculateMA:function(t,e){for(var s=[],n=0,i=t.values.length;n<i;n++)if(n<e)s.push("-");else{for(var a=0,o=0;o<e;o++)a+=t.values[n-o][1];s.push((a/e).toFixed(4))}return s},getUserInfo:function(t){var e=this,s="dy"+this.user_info.user_id+this.utils.randomNumber(4);this.http.post(this.http._HOST+"login/user_detail",{user_id:s}).then(function(s){if(e.utils.info("获取用户信息...",s),s.success){var n=s.data;e.user_info.dw_money=n.dw_money,e.user_info.pay_password=n.pay_password,e.user_info.token=n.token,"1"==t&&e.getPageInfo()}})},getPageInfo:function(){var t=this,e={token:this.user_info.token};this.http.post(this.http._HOST+"wave/show_btc",e).then(function(e){t.utils.info("获取页面数据...",e),e.success&&(t.page_data=e.data,0==t.page_data.risa&&0==t.page_data.fall?t.$refs.homeProgressLeft.style.width="50%":t.$refs.homeProgressLeft.style.width=t.page_data.risa+"%",t.timeInterval&&clearInterval(t.timeInterval),t.timeResultInterval&&clearInterval(t.timeResultInterval),t.page_data.user_postbtc&&(t.page_data.user_postbtc.game_time=o()(Math.abs(t.page_data.user_postbtc.game_time))+30,t.game_time_tmp=o()(Math.abs(t.page_data.user_postbtc.game_time))+30,t.timeResultInterval=setInterval(function(){t.page_data.user_postbtc.game_time--,0==t.page_data.user_postbtc.game_time&&(t.utils.info("开奖..."),t.getDrawResult(t.page_data.user_postbtc.btc_id),clearInterval(t.timeResultInterval),t.getPageInfo())},1e3)),t.page_data.next_time=o()(Math.abs(t.page_data.next_time))+30,t.next_time_tmp=o()(Math.abs(t.page_data.next_time))+30,t.timeInterval=setInterval(function(){t.page_data.next_time--,0==t.page_data.next_time&&(t.utils.info("时间结束，获取新期数信息..."),clearInterval(t.timeInterval),t.getPageInfo())},1e3))})},getDrawResult:function(t){var e=this,s={btc_id:t,token:this.user_info.token};this.http.post(this.http._HOST+"wave/new_result",s).then(function(t){e.utils.info("获取开奖结果...",t),t.success&&(e.guess_result=t.data,e.showGuessResultPopup=!0)})},drawKLine:function(){var t="#ec0000",e="#8A0000",s="#00da3c",n="#008F28",i={tooltip:{trigger:"axis",axisPointer:{type:"cross"}},legend:{data:["日K","MA5","MA10","MA20"],selectedMode:!1,left:0,top:0,itemGap:10},grid:{left:"40",right:"0",bottom:"30",top:"40"},xAxis:{type:"category",data:[],scale:!0,boundaryGap:!1,axisLine:{onZero:!1},splitLine:{show:!1},splitNumber:30,min:"dataMin",max:"dataMax"},yAxis:{scale:!0,splitArea:{show:!0}},dataZoom:[{type:"inside",start:50,end:100}],series:[{name:"日K",type:"candlestick",data:[],itemStyle:{normal:{color:t,color0:s,borderColor:e,borderColor0:n}}},{name:"MA5",type:"line",data:[],smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA10",type:"line",data:[],smooth:!0,lineStyle:{normal:{opacity:.5}}},{name:"MA20",type:"line",data:[],smooth:!0,lineStyle:{normal:{opacity:.5}}}]};this.chartline=this.$echarts.init(this.$refs.homeChartContent),this.chartline.setOption(i)},kTypeChange:function(t){if(this.k_type==t)return!1;this.k_type=t,this.chartline&&this.chartline.clear(),this.getBtnFun(this.k_type,1)},getDefaultImg:function(t){var e=s("dcaf");t.target.src=e},closeGuessResult:function(){this.showGuessResultPopup=!1}},beforeRouteEnter:function(t,e,s){console.log("to...",t),console.log("from...",e),s()},beforeDestroy:function(){this.utils.warn("页面即将销毁...请清除定时器..."),this.k_interval&&clearInterval(this.k_interval),this.timeInterval&&clearInterval(this.timeInterval),this.timeResultInterval&&clearInterval(this.timeResultInterval)}},p=l,f=(s("943d"),s("2877")),d=Object(f["a"])(p,n,i,!1,null,"408720dc",null);e["default"]=d.exports},bf0b:function(t,e,s){var n=s("355d"),i=s("aebd"),a=s("36c3"),o=s("1bc3"),r=s("07e3"),c=s("794b"),u=Object.getOwnPropertyDescriptor;e.f=s("8e60")?u:function(t,e){if(t=a(t),e=o(e,!0),c)try{return u(t,e)}catch(s){}if(r(t,e))return i(!n.f.call(t,e),t[e])}},bf90:function(t,e,s){var n=s("36c3"),i=s("bf0b").f;s("ce7e")("getOwnPropertyDescriptor",function(){return function(t,e){return i(n(t),e)}})},c300:function(t,e,s){t.exports=s.p+"img/guess-again.8a7e09b0.png"},cac3:function(t,e,s){t.exports=s.p+"img/guess-down.9d1f8d5f.png"},ccb9:function(t,e,s){e.f=s("5168")},ce7e:function(t,e,s){var n=s("63b6"),i=s("584a"),a=s("294c");t.exports=function(t,e){var s=(i.Object||{})[t]||Object[t],o={};o[t]=e(s),n(n.S+n.F*a(function(){s(1)}),"Object",o)}},cebc:function(t,e,s){"use strict";var n=s("268f"),i=s.n(n),a=s("e265"),o=s.n(a),r=s("a4bb"),c=s.n(r),u=s("85f2"),l=s.n(u);function p(t,e,s){return e in t?l()(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}function f(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{},n=c()(s);"function"===typeof o.a&&(n=n.concat(o()(s).filter(function(t){return i()(s,t).enumerable}))),n.forEach(function(e){p(t,e,s[e])})}return t}s.d(e,"a",function(){return f})},dcaf:function(t,e,s){t.exports=s.p+"img/default-user.4fcb948c.png"},e265:function(t,e,s){t.exports=s("ed33")},e692:function(t,e){t.exports="\t\n\v\f\r   ᠎             　\u2028\u2029\ufeff"},e814:function(t,e,s){t.exports=s("b9e9")},ebfd:function(t,e,s){var n=s("62a0")("meta"),i=s("f772"),a=s("07e3"),o=s("d9f6").f,r=0,c=Object.isExtensible||function(){return!0},u=!s("294c")(function(){return c(Object.preventExtensions({}))}),l=function(t){o(t,n,{value:{i:"O"+ ++r,w:{}}})},p=function(t,e){if(!i(t))return"symbol"==typeof t?t:("string"==typeof t?"S":"P")+t;if(!a(t,n)){if(!c(t))return"F";if(!e)return"E";l(t)}return t[n].i},f=function(t,e){if(!a(t,n)){if(!c(t))return!0;if(!e)return!1;l(t)}return t[n].w},d=function(t){return u&&h.NEED&&c(t)&&!a(t,n)&&l(t),t},h=t.exports={KEY:n,NEED:!1,fastKey:p,getWeak:f,onFreeze:d}},ed33:function(t,e,s){s("014b"),t.exports=s("584a").Object.getOwnPropertySymbols},fde4:function(t,e,s){s("bf90");var n=s("584a").Object;t.exports=function(t,e){return n.getOwnPropertyDescriptor(t,e)}}}]);
//# sourceMappingURL=chunk-07a2177d.0eab94b4.js.map