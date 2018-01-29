/**
 * Created by heavon on 2018/1/29.
 */
$(function(){
    $('video').each(function(){
        addVideoListener($(this)[0]);
    });

    $('.play_btn').click(function(){
        var video = $(this).siblings('video');
        video.trigger('play');
        requestFullScreen(video[0]);
    });
});

function addVideoListener(video){
    //添加点击事件
    video.addEventListener('click', function(){
        if(video.fullscreen||video.mozFullScreen||video.webkitDisplayingFullscreen||video.msFullscreenElement){
            exitFullScreen(video);
            video.pause();
        }
    });
    //全屏监听
    addFullScreenListener(video);
}
//添加全屏监听
function addFullScreenListener(video){
    video.addEventListener('fullscreenchange', function(){
        if(video.fullscreen){
            console.log('全屏');
        }else{
            video.pause();
        }
    });
    video.addEventListener('mozfullscreenchange', function(){
        if(video.mozFullScreen){
            console.log('全屏');
        }else{
            video.pause();
        }
    });
    video.addEventListener('webkitfullscreenchange', function(){
        if(video.webkitDisplayingFullscreen){
            console.log('全屏');
        }else{
            video.pause();
        }
    });
    video.addEventListener('msfullscreenchange', function(){
        if(video.msFullscreenElement){
            console.log('全屏');
        }else{
            video.pause();
        }
    });
}

//进入全屏
function requestFullScreen(video) {
    if (video.requestFullscreen) {
        video.requestFullscreen();
    } else if (video.mozRequestFullScreen) {
        video.mozRequestFullScreen();
    } else if (video.webkitRequestFullScreen) {
        video.webkitRequestFullScreen();
    }
}
//退出全屏
function exitFullScreen(video) {
    if (video.exitFullscreen) {
        video.exitFullscreen();
    } else if (video.mozCancelFullScreen) {
        video.mozCancelFullScreen();
    } else if (video.webkitCancelFullScreen) {
        video.webkitCancelFullScreen();
    } else if (video.webkitExitFullScreen) {
        video.webkitExitFullScreen();
    }
}