/**
 * Created by heavon on 2017/5/15.
 */
function setInputText(name, text) {
    $('input[name="'+name+'"]').val(text);
}
function editHandle(event) {
    var type = event.data.type;
    switch (type) {
        case 'editSummary': {
            var summary = $(this).val();
            if ((summary != 'undefined') && (summary != '')) {
                editObj.editSummary(summary);
            } else {
                editObj.editSummary(null);
            }
        }
            break;
        case 'editName': {
            editObj.editName();
        }
            break;
        case 'editDate': {
            var date = $(this).val();
            var newDate;
            if ((date != 'undefined') && (date != '')) {
                newDate = editObj.editDate(date);
            } else {
                newDate = editObj.editDate(null);
            }
            alert(newDate);
            editObj.toast(newDate);
            $(this).val(newDate);
        }
            break;
        default:
            break;
    }
}
function editPost() {
    var form = $('#edit_form');
    var url = form.attr("action");
    var data = form.serializeArray();
    if (checkForm(data)) {
        $.post(url, data, function (s) {
            //回调
            if (s.error) {
                alert(s.data);
                editObj.toast(s.data);
            } else {
                //保存成功
                alert(s.data);
                editObj.save();
            }
        });
    }else{
//				alert('参数有误');
        editObj.toast('参数有误');
    }
}
function checkForm(data) {
    var json = {};
    $.each(data, function(i, field){
        if(field.value != ''){
            json[field.name] = field.value;
        }
    });

    if(typeof (json['name']) == 'undefined'){
        alert('名称不能为空');
        return false;
    }
    if (typeof (json['periods']) != 'undefined' && isNaN(json['periods'])) {
        alert('请填写数字');
        return false;
    }
    return true;
}
function getObjectURL(file) {
    var url = null;
    if (window.createObjectURL != undefined) { // basic
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}

$(window).load(function () {
    //初始化滑动图片
    var heights = $('.swiper-slide img').height();
    $('.swiper-container').css({height: heights}); //获取图片自适应高度赋值
    photoSwiper = new Swiper('.photo', {
        direction: 'horizontal',
        freeMode: true,
        slidesPerView: 'auto',
        spaceBetween: 8,
    });
    previewSwiper = new Swiper('.preview', {
        direction: 'horizontal',
        freeMode: true,
        slidesPerView: 'auto',
        spaceBetween: 8,
    });
    //加载结束
    $("#loading").fadeOut(300);
    //
    $('.content input[name="summary"]').click({type: 'editSummary'}, editHandle);
    $('.show_thumb').click(function () {
        $('#thumb_file').click();
    });
    $('.preview .add_file').click(function () {
        $('#preview_file').click();
    });
    $('.photo .add_file').click(function () {
        $('#photo_file').click();
    });
//			$('#thumb').on('change', showThumb);

//			$('.content input[name="summary"]').on('click', {type: 'editSummary', summary: $(this).val()}, editHandle);
//			$('.content .date').on('click', {type: 'editDate'}, editHandle);

});
function showThumb(url) {
    //上传图片
//			var files = this.files[0];
//			var thumb = getObjectURL(this.files[0]);
    //设置加载动画
    $('.show_thumb .loading').show();
    //上传图片
    $.ajaxFileUpload({
            url: url,
            secureuri: false,
            fileElementId: 'thumb_file',
            dataType: 'json',
            success: function (data, status)  //服务器成功响应处理函数
            {
                if (typeof (data.error) != 'undefined') {
                    if (!data.error) {
                        //图片链接
                        var img = data.data;
                        if (img) {
                            $('.show_thumb img').load(function () {
                                //图片加载完成结束加载动画
                                $('.show_thumb .loading').hide();
                            })
                            $('.show_thumb img').attr('src', img);
                            $('#thumb').val(img);
                            return true;
                        }
                    } else {
                        alert(data.data);
                    }
                }
                //结束加载动画
                $('.show_thumb .loading').hide();
            },
            error: function (data, status, e)//服务器响应失败处理函数
            {
                alert(e);
                //结束加载动画
                $('.show_thumb .loading').hide();
            }
        }
    );
//			var thumb = getObjectURL(this.files[0]);
//			if(thumb){
//				$('.show_thumb img').attr('src', thumb);
//			}
}
function showPhoto(url) {
    //上传图片
    $.ajaxFileUpload({
            url: url,
            secureuri: false,
            fileElementId: 'photo_file',
            dataType: 'json',
            success: function (data, status)  //服务器成功响应处理函数
            {
                if (typeof (data.error) != 'undefined') {
                    if (!data.error) {
                        //图片链接
                        var img = data.data;
                        if (img) {
                            photoSwiper.appendSlide('<div class="swiper-slide"><img src="'+img+'"></div>');
                            $('#photo').val($('#photo').val()+img+'`');
                            return true;
                        }
                    } else {
                        alert(data.data);
                    }
                }
            },
            error: function (data, status, e)//服务器响应失败处理函数
            {
                alert(e);
            }
        }
    );
}

function showPreview(url) {
    //上传视频
    previewSwiper.appendSlide('<div class="swiper-slide"><img src="'+loadingSrc+'"/></div>');
    $.ajaxFileUpload({
            url: url,
            secureuri: false,
            fileElementId: 'preview_file',
            dataType: 'json',
            success: function (data, status)  //服务器成功响应处理函数
            {
                if (typeof (data.error) != 'undefined') {
                    if (!data.error) {
                        //图片链接
                        var video = data.data;
                        if (video) {
                            previewSwiper.getLastSlide().html('<video src="'+video+'"></video>');
                            // previewSwiper.appendSlide('<div class="swiper-slide"><video src="'+video+'"></video></div>');
                            $('#preview').val($('#preview').val()+video+'`');
                            return true;
                        }
                    } else {
                        alert(data.data);
                        previewSwiper.removeSlide(previewSwiper.slides.length-1);
                    }
                }
            },
            error: function (data, status, e)//服务器响应失败处理函数
            {
                alert(e);
                previewSwiper.removeSlide(previewSwiper.slides.length-1);
            }
        }
    );
}