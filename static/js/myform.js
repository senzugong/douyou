/*
 * @Author: Paco
 * @Date:   2017-02-15
 * +----------------------------------------------------------------------
 * | jqadmin [ jq酷打造的一款懒人后台模板 ]
 * | Copyright (c) 2017 http://jqadmin.jqcool.net All rights reserved.
 * | Licensed ( http://jqadmin.jqcool.net/licenses/ )
 * | Author: Paco <admin@jqcool.net>
 * +----------------------------------------------------------------------
 */

layui.define(['jquery', 'jqtags', 'laytpl', 'jqform', 'upload'], function(exports) {
    var $ = layui.jquery,
        tpl = layui.laytpl,
        box = "",
        ueditor = layui.ueditor,
        form = layui.jqform,
        jqtags = layui.jqtags;
    jqtags.init();

    /**
     * 在绑定数据前，初始化分类下拉框
     */
    form.beforeBind = function(jqtable, params, options) {
        var locationData = layui.data("articleCat"),
            record = locationData.list ? locationData.list : "";
        if (record) {
            var data = {
                list: record
            }
            var getTpl = $("#select-tpl").html();
            var obj = $("#select-cat");
            tpl(getTpl).render(data, function(html) {
                obj.html(html);
            })
        }
    }

    form.init({
        "form": "#form1"
    });

    form.verify({
        username: function(value) {
            if(value ==""){
                return "不能为空";
            }
        },
        pass: [
            /^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格'
        ]
    });


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
                form.check(box.find('input[type=hidden]'));
            } else {
                box.next('div').find('p').html('上传失败...')
            }
        }
    });

    exports('myform', {});
});