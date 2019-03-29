/*
 * @Author: Paco
 * @Date:   2017-07-19
 * @lastModify 2017-07-24
 * +----------------------------------------------------------------------
 * | jqadmin [ jq酷打造的一款懒人后台模板 ]
 * | Copyright (c) 2017 http://jqadmin.jqcool.net All rights reserved.
 * | Licensed ( http://jqadmin.jqcool.net/licenses/ )
 * | Author: Paco <admin@jqcool.net>
 * +----------------------------------------------------------------------
 */

layui.define(['jquery', "jqtable", 'jqbind', 'jqajax', 'jqdate', 'upload', 'jqform'], function(exports) {
    var $ = layui.jquery,
        table = layui.jqtable,
        jqbind = layui.jqbind,
        form = layui.jqform,
        list = new table();

    list.init({ tplid: "#list-tpl" });
    top.global[list.options.dataName] = list;
    form.init();

    form.on("select(pid-select)", function(data) {
        var level = $(data.elem).find("option:selected").data("level");
        $(data.elem).parents("form").find("input[name=level]").val(level + 1);
    })


   /* if(list.options.dataName == "database"){
        var $form = $("#export-form"), $export = $("#export"), tables, $optimize = $("#optimize"), $repair = $("#repair");
        $optimize.add($repair).click(function () {
            $.post($(this).attr('url'), $form.serialize(), function (data) {
                if (data.status) {
                    layer.msg(data.msg,{icon:1,time:2000,shade: 0.1,});
                } else {
                    layer.msg(data.msg,{icon:2,time:2000,shade: 0.1,});
                }
            });
            return false;
        });*/

       /* $export.click(function () {
            $export.parent().children().prop('disabled', true);
            $export.html("正在发送备份请求...");

            $.post(
                $form.attr("action"),
                $form.serialize(),
                function (data) {
                    if (data.status == 200) {
                        tables = data.data.tables;
                        $export.html(data.msg + "开始备份，请不要关闭本页面！");
                        backup(data.data.tab);
                        window.onbeforeunload = function () {
                            return "正在备份数据库，请不要关闭！";
                        };
                    } else {
                        layer.msg(data.msg,{icon:2,time:2000,shade: 0.1,});
                        $export.html("立即备份");
                        setTimeout(function () {
                            $export.parent().children().prop('disabled', false);
                        }, 1500);
                    }
                });
            return false;
        });*/

      /*  function backup(tab, status) {

            status && showmsg(tab.id, "开始备份...(0%)");
            $.get($form.attr("action"), tab, function (data) {
                if (data.status == 200) {
                    showmsg(tab.id, data.msg);
                    if (!$.isPlainObject(data.data.tab)) {
                        $export.parent().children().prop('disabled', false);
                        $export.html("备份完成，点击重新备份");
                        window.onbeforeunload = function () {
                            return null;
                        };
                        return;
                    }
                    backup(data.data.tab, tab.id != data.data.tab.id);
                } else {
                    layer.msg(data.msg, 0);
                    $export.html("立即备份");
                    setTimeout(function () {
                        $export.parent().children().prop('disabled', false);
                    }, 1500);
                }
            });
        }

        function showmsg(id, msg) {
            $form.find("input[value=" + tables[id] + "]").closest("tr").find("#info").html(msg);
        }*/
  //  }
    //如果是文章列表，则提前取出分类数据缓存到本地
  /*  if (list.options.dataName == "article") {
        var locationData = layui.data("articleCat"),
            record = locationData.list ? locationData.list : "";
        if (!record) {
            var jqajax = layui.jqajax,
                ajax = new jqajax();
            ajax.options.url = "./data/cat.json";
            ajax.ajax(ajax.options);
            ajax.complete = function(ret, options) {
                if (ret.status = 200) {
                    record = ret.data.list;
                    layui.data("articleCat", {
                        key: "list",
                        value: record
                    });
                }
            }
        }
    }*/

    //自定义表单填充
    // jqbind.setVal = function(options) {
    //     options.content.find("form")[0].reset();
    //     $.each(options.data, function(i, n) {
    //         options.content.find("select[name=" + i + "]").val(n);
    //         if (i == "level") {
    //             if (options.content.find("input[name=" + i + "]").length > 0) {
    //                 options.content.find("input[name=" + i + "]").val(n);
    //             } else {
    //                 var html = "<input type='hidden' name='" + i + "' value=" + n + " />";
    //                 options.content.children("form").append(html);
    //             }

    //         }
    //     })
    // }

    //上传文件设置
    layui.upload({
        url: '/admin/upload/uploadArticleImage',
        before: function(input) {
            box = $(input).parent('form').parent('div').parent('.layui-input-block');
            if (box.next('div').length > 0) {
                box.next('div').html('<div class="imgbox"><p>上传中...</p></div>');
            } else {
                box.after('<div class="layui-input-block"><div class="imgbox"><p>上传中...</p></div></div>');
            }
        },
        success: function(res) {
            if (res.status == 200) {
                box.next('div').find('div.imgbox').html('<img src="' + res.image_name + '" alt="..." class="img-thumbnail">');
                box.find('input[type=hidden]').val(res.image_name);
                //form.check(box.find('input[type=hidden]'));
            } else {
                box.next('div').find('p').html('上传失败...')
            }
        }
    });


    exports('list', {});
});