(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-9005d7ee"],{"0010":function(t,s,a){t.exports=a.p+"img/most-box.835177d0.png"},"1e9d":function(t,s,a){t.exports=a.p+"img/money-all.8b3fb4bb.png"},"23a5":function(t,s,a){t.exports=a.p+"img/up.eb7fad83.png"},"2fb6":function(t,s,a){t.exports=a.p+"img/go-rule.b785a878.png"},"32e2":function(t,s,a){t.exports=a.p+"img/guess-up.34e78bd7.png"},"619f":function(t,s,a){t.exports=a.p+"img/up-down-btn.621f3a0a.png"},6677:function(t,s,a){"use strict";var i=a("d7f8"),n=a.n(i);n.a},"6c85":function(t,s,a){t.exports=a.p+"img/most-title.6e53f346.png"},"83d9":function(t,s,a){t.exports=a.p+"img/down.f519da8d.png"},"9ace":function(t,s,a){t.exports=a.p+"img/user.d9f0760d.png"},bb51:function(t,s,a){"use strict";a.r(s);var i=function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"home"},[i("van-nav-bar",{attrs:{title:"","left-arrow":"",border:!1},on:{"click-left":t.goBack}}),i("img",{staticClass:"go-rule",attrs:{src:a("2fb6"),alt:""},on:{click:t.goRulePage}}),t._m(0),i("div",{staticClass:"most"},[t._m(1),i("div",{staticClass:"most-box"},[i("img",{staticClass:"most-box-bg",attrs:{src:a("0010"),alt:""}}),i("div",{staticClass:"most-box-content"},[i("div",{staticClass:"most-info"},[i("div",{staticClass:"most-info-left"},[i("div",{staticClass:"most-info-img"},[i("img",{attrs:{src:t.most_data.img,alt:""}})]),i("div",{staticClass:"most-info-name"},[i("div",{staticClass:"most-info-name-username"},[t._v(t._s(t.most_data.name))]),i("div",{staticClass:"most-info-name-money"},[t._v("今日赢得："+t._s(t.most_data.money))])])]),i("div",{staticClass:"most-info-right"},[i("div",{staticClass:"most-info-text"},[t._v("连中")]),i("div",{staticClass:"most-info-times"},[t._v("x"+t._s(t.most_data.times))])])])])])]),i("div",{staticClass:"home-chart-box"},[i("div",{staticClass:"home-chart-title"},[t._v("当前BTC时价：\n\t\t\t\t"),i("span",[t._v("¥"+t._s(t.btn_data.btn_now))]),i("span",[t.btn_data.btn_up?i("span",{staticStyle:{padding:"0"}},[t._v("+")]):i("span",{staticStyle:{padding:"0"}},[t._v("-")]),t._v("\n\t\t\t\t\t"+t._s(t.btn_data.btn_percent)+"%\n\t\t\t\t")])]),i("div",{ref:"homeChart",staticClass:"home-chart"}),t._m(2),i("div",{staticClass:"home-chart-btn"},[i("img",{attrs:{src:a("32e2"),alt:""},on:{click:function(s){return t.guessFun("1")}}}),i("img",{attrs:{src:a("cac3"),alt:""},on:{click:function(s){return t.guessFun("2")}}})]),i("div",{staticClass:"home-time-text"},[t._v("距离本期预约截止时间还剩")]),t._m(3)]),i("div",{staticClass:"record"},[i("div",{staticClass:"record-title"},[t._m(4),i("div",{staticClass:"record-title-right",on:{click:t.goMore}},[t._v("\n\t\t\t\t\t更多\n\t\t\t\t\t"),i("van-icon",{attrs:{name:"arrow"}})],1)]),i("div",{staticClass:"record-content"},[i("div",{staticClass:"record-item"},[i("div",{staticClass:"record-data"},[t._v(t._s(t.record_data.money))]),i("div",{staticClass:"record-name"},[t._v("今日赚取")])]),i("div",{staticClass:"record-item"},[i("div",{staticClass:"record-data"},[t._v(t._s(t.record_data.rank))]),i("div",{staticClass:"record-name"},[t._v("排行榜")])]),i("div",{staticClass:"record-item"},[i("div",{staticClass:"record-data"},[t._v(t._s(t.record_data.true))]),i("div",{staticClass:"record-name"},[t._v("猜中")])])])]),i("van-popup",{model:{value:t.showGuessPopup,callback:function(s){t.showGuessPopup=s},expression:"showGuessPopup"}},[i("div",{staticClass:"guess-box"},["1"==t.guess_type?i("img",{attrs:{src:a("23a5"),alt:""}}):t._e(),"2"==t.guess_type?i("img",{attrs:{src:a("83d9"),alt:""}}):t._e(),i("van-icon",{staticClass:"close-guess",attrs:{name:"cross"},on:{click:t.closeGuessFun}}),i("div",{staticClass:"guess-content"},[i("div",{staticClass:"guess-title"},[t._v(t._s(t.guess_data.name))]),i("div",{staticClass:"guess-text"},[t._v("竞猜金额：")]),i("div",{staticClass:"guess-input"},[i("van-field",{staticClass:"guess-input-left",attrs:{type:"number",placeholder:"请输入竞猜金额"},model:{value:t.money,callback:function(s){t.money=s},expression:"money"}}),i("span",{staticClass:"guess-input-right"},[t._v("蚪金")])],1),i("div",{staticClass:"guess-text"},[t._v("您的蚪金金额："+t._s(t.user_info.money))]),i("div",{staticClass:"guess-item"},t._l(t.money_item,function(s,a){return i("span",{key:a},[t._v("+"+t._s(s))])}),0),i("div",{staticClass:"guess-btn-box"},[i("img",{attrs:{src:a("619f"),alt:""},on:{click:function(s){return t.confirmGuess("1")}}})])])],1)]),t.showPayPasswordPopup?i("div",{staticClass:"popup"},[i("div",{staticClass:"popup-shadow",on:{click:t.closePayPassword}}),i("div",{staticClass:"popup-password-input"},[i("div",{staticClass:"popup-password-input-title"},[i("van-icon",{attrs:{name:"cross"},on:{click:t.closePayPassword}}),i("span",[t._v("输入支付密码，以验证身份")])],1),i("van-password-input",{attrs:{value:t.payPw}}),i("van-number-keyboard",{attrs:{show:!0,"hide-on-click-outside":!1},on:{input:t.onInput,delete:t.onDelete}})],1)]):t._e()],1)},n=[function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"money-all"},[i("img",{attrs:{src:a("1e9d"),alt:""}})])},function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"most-title"},[i("img",{attrs:{src:a("6c85"),alt:""}})])},function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"home-progress"},[a("span",[t._v("42%")]),a("div",{staticClass:"home-progress-left"},[t._v("58%")])])},function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"home-time"},[a("span",[t._v("9")]),t._v("分"),a("span",[t._v("59")]),t._v("秒\n\t\t\t")])},function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",{staticClass:"record-title-left"},[a("span"),t._v("\n\t\t\t\t\t今日战绩\n\t\t\t\t")])}],e={name:"home",data:function(){return{isLogin:!1,showGuessPopup:!1,showPayPasswordPopup:!1,payPw:"",user_info:{money:"238.23"},guess_data:{name:"8:20~8:30竞猜场次",up:0,up_meney:"",down:1,down_money:"500",end_time:"600"},most_data:{name:"小明",img:a("9ace"),money:"2300",times:10},record_data:{money:"2361",rank:"26",true:"12"},money_item:[20,100,200,500],money_item_index:-1,money:"",guess_type:"1",optionline:{xAxis:{type:"category",show:!1},yAxis:{type:"value",show:!1,min:"dataMin",max:"dataMax"},series:[{data:[820,932,901,934,0,1290,1330,1320,880,890,750,260,450,760],type:"line",smooth:!0,animation:!1}],color:["#fff"]},btn_data:{btn_now:0,btn_pre:0,btn_percent:0,btn_up:!1}}},created:function(){this.$route.params.id?(this.utils.info("有id...已登录..."),this.isLogin=!0):(this.utils.info("没有id...未登录..."),this.isLogin=!1)},mounted:function(){this.getBtnFun()},methods:{goBack:function(){this.utils.info("返回app..."),this.$bridge.callhandler("goBack",{tag:"返回app"},function(t){})},goLogin:function(){this.utils.info("去登录..."),this.$bridge.callhandler("goLogin",{tag:"登录"},function(t){})},goMore:function(){this.utils.info("战绩更多"),this.$bridge.callhandler("goMore",{tag:"战绩更多"},function(t){})},goRulePage:function(){this.$router.push({name:"rule"})},showPayPassword:function(){this.showPayPasswordPopup=!0},closePayPassword:function(){this.showPayPasswordPopup=!1,this.payPw="",this.utils.showToast("您放弃了支付")},onInput:function(t){var s=this;if(this.payPw=(this.payPw+t).slice(0,6),6==this.payPw.length)s.payPw},onDelete:function(){this.payPw=this.payPw.slice(0,this.payPw.length-1)},guessFun:function(t){this.isLogin?(this.guess_type=t,this.showGuessPopup=!0):this.goLogin()},closeGuessFun:function(){this.showGuessPopup=!1,this.money=""},confirmGuess:function(){this.closeGuessFun(),this.showPayPassword()},drawLine:function(){var t=this.$echarts.init(this.$refs.homeChart);t.setOption(this.optionline)},getBtnFun:function(){var t=this;this.http.get("https://data.block.cc/api/v1/price/history?symbol_name=bitcoin&limit=50").then(function(s){if(t.utils.info("获取btn信息...",s),s.success){for(var a=s.data,i=[],n=0;n<a.length;n++)i.push(a[n][1]);t.utils.info("line_data...",i),t.optionline.series[0].data=i,t.btn_data.btn_now=i[0].toFixed(2),t.btn_data.btn_pre=i[1].toFixed(2);var e=t.btn_data.btn_now-t.btn_data.btn_pre;t.btn_data.btn_up=e>0,t.btn_data.btn_percent=(Math.abs(e)/t.btn_data.btn_pre*100).toFixed(2),t.drawLine()}})}}},o=e,c=(a("6677"),a("2877")),r=Object(c["a"])(o,i,n,!1,null,"c3cfb7c0",null);s["default"]=r.exports},cac3:function(t,s,a){t.exports=a.p+"img/guess-down.9d1f8d5f.png"},d7f8:function(t,s,a){}}]);
//# sourceMappingURL=chunk-9005d7ee.9669528b.js.map