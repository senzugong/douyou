(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-ef4fbf7a"],{"33a8":function(s,t,e){"use strict";e.r(t);var i=function(){var s=this,t=s.$createElement,e=s._self._c||t;return e("div",{staticClass:"guess-page"},[e("van-nav-bar",{attrs:{title:"实时猜涨跌",fixed:"","left-arrow":"",border:!1,"right-text":"历史"},on:{"click-left":s.goBack,"click-right":s.goGuessList}}),e("div",{staticClass:"bottom"},[e("van-row",[e("van-col",{staticClass:"guess-up",attrs:{span:"12"}},[e("div",{staticClass:"guess-up-box"},[0==s.guess_data.is_open?e("div",{staticClass:"guess-up-box-shadow"}):s._e(),e("div",{on:{click:function(t){return s.showGuessModalFun(1)}}},[s._v("看涨")])])]),e("van-col",{staticClass:"guess-down",attrs:{span:"12"}},[e("div",{staticClass:"guess-down-box"},[0==s.guess_data.is_open?e("div",{staticClass:"guess-down-box-shadow"}):s._e(),e("div",{on:{click:function(t){return s.showGuessModalFun(2)}}},[s._v("看跌")])])])],1)],1),s.showGuessModal?e("div",{staticClass:"modal-box"}):s._e()],1)},n=[],o=e("cebc"),u=e("59ad"),a=e.n(u),r=e("e45e"),c=e("5880"),d={name:"guess",components:{echartsDemo:r["a"]},data:function(){return{isLogin:!1,guess_money_index:0,guess_count_index:0,guess_stop_index:0,guess_data:{time:[],usdt:[],recharge:0,btc_price:"",is_open:0,opentime:{start_time:"",end_time:""}},order_data:{point:"",quiz:"",usdt:"",order_money:""},showGuessModal:!0,guess_type:1,guessingOrderList:[],btn_price_data:{vol:"",high:"",low:"",sell:"",last:"",buy:""}}},filters:{toFixedNum:function(s,t){return s?a()(s).toFixed(t):""}},computed:Object(o["a"])({},Object(c["mapState"])(["user_info"])),created:function(){this.$route.params.id?(this.isLogin=!0,this.utils.info("已登录..."),this.user_info.user_id=this.$route.params.id):this.utils.warn("未登录...")},mounted:function(){},methods:{goBack:function(){this.utils.info("返回app..."),this.$bridge.callhandler("goBack",{tag:"返回app"},function(){})},goRecharge:function(){this.utils.info("去充值..."),this.$bridge.callhandler("goRecharge",{tag:"去充值"},function(){})},goPay:function(){this.utils.info("设置支付密码..."),this.$bridge.callhandler("goPay",{tag:"设置支付密码"},function(s){})},goLogin:function(){this.utils.info("去登录..."),this.$bridge.callhandler("goLogin",{tag:"去登录"},function(s){})},getUserInfo:function(s){var t=this,e="dy"+this.user_info.user_id+this.utils.randomNumber(4);this.http.post(this.http._HOST+"login/user_detail",{user_id:e}).then(function(e){if(t.utils.info("获取用户信息...",e),e.success){var i=e.data;t.user_info.dw_money=i.dw_usdt,t.user_info.pay_password=i.pay_password,t.user_info.token=i.token,1==s&&(t.getPageData(),t.getGuessingList())}else{t.utils.err("获取用户信息...err...3s后再次请求");var n=setTimeout(function(){t.getUserInfo(s),clearTimeout(n)},3e3)}})},goGuessList:function(){if(!this.isLogin)return this.utils.showToast("请先登录..."),this.goLogin(),!1;this.$router.push({name:"guessList"})},goGuessRule:function(){this.$router.push({name:"guessRule"})},getPageData:function(){var s=this,t={type:"1",token:this.user_info.token};this.http.post(this.http._HOST+"wave/basic_usdt",t).then(function(t){if(s.utils.info("获取页面数据",t),t.success)s.guess_data=t.data;else{s.utils.err("获取页面数据失败...3s后再次请求");var e=setTimeout(function(){s.getPageData(),clearTimeout(e)},3e3)}})},getGuessingList:function(){var s=this,t={token:this.user_info.token,type:"1"};this.http.post(this.http._HOST+"wave/order_list",t).then(function(t){if(s.utils.info("持仓",t),t.success){if(s.guessingOrderList=t.data,s.guessingOrderList.length>0)var e=setTimeout(function(){s.utils.info("定时刷新持仓..."),s.getUserInfo(2),s.getGuessingList(),clearTimeout(e)},1e3)}else{s.utils.err("持仓...3s后再次请求");var i=setTimeout(function(){s.getGuessingList(),clearTimeout(i)},3e3)}})},showGuessModalFun:function(s){this.guess_type=s,this.showGuessModal=!0},closeGuessModalFun:function(){this.showGuessModal=!1},changeGuessMoney:function(s,t){if(this.guess_money_index==t)return!1;this.guess_money_index=t}}},g=d,l=(e("38b9"),e("2877")),h=Object(l["a"])(g,i,n,!1,null,"f886086c",null);t["default"]=h.exports},"38b9":function(s,t,e){"use strict";var i=e("f256"),n=e.n(i);n.a},f256:function(s,t,e){}}]);
//# sourceMappingURL=chunk-ef4fbf7a.54746fa6.js.map