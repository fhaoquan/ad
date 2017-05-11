/**
 * Created by heavon on 2017/5/10.
 */
! function(a) {
    function b() {
        var s = 750;	//设计稿宽度
        if(f.getBoundingClientRect().width > s){
            f.style.fontSize = "20px";
        }else{
            a.rem = f.getBoundingClientRect().width *20 / s, f.style.fontSize = a.rem + "px";
        }
    }
    var c, d = a.navigator.appVersion.match(/iphone/gi) ? a.devicePixelRatio : 1,
        e = 1 / d,
        f = document.documentElement,
        g = document.createElement("meta");
    if (a.dpr = d, a.addEventListener("resize", function() {
            clearTimeout(c), c = setTimeout(b, 0)
        }, !1), a.addEventListener("pageshow", function(a) {
            a.persisted && (clearTimeout(c), c = setTimeout(b, 0))
        }, !1), f.setAttribute("data-dpr", d), g.setAttribute("name", "viewport"), g.setAttribute("content", "initial-scale=" + e + ", maximum-scale=" + e + ", minimum-scale=" + e + ", user-scalable=no"), f.firstElementChild) ;
    else {
        var h = document.createElement("div");
        h.appendChild(g), document.write(h.innerHTML)
    }
    b()
}(window);