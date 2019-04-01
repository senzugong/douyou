/*
 * @Author: Paco
 * @Date:   2017-02-22
 * +----------------------------------------------------------------------
 * | jqadmin [ jq酷打造的一款懒人后台模板 ]
 * | Copyright (c) 2017 http://jqadmin.jqcool.net All rights reserved.
 * | Licensed ( http://jqadmin.jqcool.net/licenses/ )
 * | Author: Paco <admin@jqcool.net>
 * +----------------------------------------------------------------------
 */

layui.define(['echarts','jqbind'], function(exports) {
    var echarts = layui.echarts,
      $ = layui.jquery;

    var member = echarts.init(document.getElementById('member'));
    var userData=[];
    var username =[];
    var sex=[];

    var articleName=[];
    var articleData=[];
    $.ajax({
        url: "/admin/user/getdata",
        dataType: "json",
        type: "POST",
        success: function(data){
            if(data){
                for(var i = 0 ; i < data.user.length; i++){

                    username.push(data.user[i].date);
                    userData.push(data.user[i].num);
                }
                for(var i = 0 ; i < data.sex.length; i++){
                    sex.push(data.sex[i]);
                }
                for(var i=0;i< data.article.length; i++){
                    articleName.push(data.article[i].date);
                    articleData.push(data.article[i].num);
                }
                var option = {
                    title: {
                        text: '用户注册数',
                        subtext: '实时统计数据',
                        x: 'center'
                    },
                    color: ['#3398DB'],
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: { // 坐标轴指示器，坐标轴触发有效
                            type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: [{
                        type: 'category',
                        data: username,
                        axisTick: {
                            alignWithLabel: true
                        }
                    }],
                    yAxis: [{
                        type: 'value'
                    }],
                    series: [{
                        name: '活跃度',
                        type: 'bar',
                        barWidth: '60%',
                        data: userData
                    }]
                };
                member.setOption(option);

                var  option = {
                    title: {
                        text: '注册用户男女比例',
                        subtext: '真实数据统计',
                        x: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left',
                        data: ['男', '女']
                    },
                    series: [{
                        name: '人数',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: sex,
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }]
                };
                var sexobj = echarts.init(document.getElementById('sex'));
                sexobj.setOption(option);

                // var article = echarts.init(document.getElementById('article'));
                // var option = {
                //     title: {
                //         text: '最近七天发布文章数',
                //         subtext: '文章数',
                //         x: 'center'
                //     },
                //     color: ['#3398DB'],
                //     tooltip: {
                //         trigger: 'axis',
                //         axisPointer: { // 坐标轴指示器，坐标轴触发有效
                //             type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                //         }
                //     },
                //     grid: {
                //         left: '3%',
                //         right: '4%',
                //         bottom: '3%',
                //         containLabel: true
                //     },
                //     xAxis: [{
                //         type: 'category',
                //         data: articleName,
                //         axisTick: {
                //             alignWithLabel: true
                //         }
                //     }],
                //     yAxis: [{
                //         type: 'value'
                //     }],
                //     series: [{
                //         name: '活跃度',
                //         type: 'bar',
                //         barWidth: '60%',
                //         data: articleData
                //     }]
                // };
                // // 使用刚指定的配置项和数据显示图表。
                // article.setOption(option);

            }

        },
        error:function(data)
        {
            if(data.responseText!='')
                alert(data.responseText);
        }
    });


    exports('member', {});
});