$.fn.exists = function () {
    return this.length !== 0;
}
$.fn.existsGetElse = function (e = () => {}) {
    if (this.exists()) return this;
    else return e();
}
let context = { currentTaskId: 0, currentHintId: 0, tabId: 0, tabOffset: [] };

let lastDelay = [];
let delay = (function () {
    let timer = 0;
    return function (callback, time) {
        if (Array.isArray(callback)) {
            if (callback.length > 0) {
                callback = callback.pop();
                lastDelay = [];
            } else {
                callback = false;
            }
        }
        clearTimeout(timer);
        if (callback !== false) timer = setTimeout(callback, time);
    }
})();

let downloadFile = function(data, fileName) {
    let blob = new Blob([data], {type: 'application/octet-binary'});
    let link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = fileName;
    link.click();
};

let sp = function(e) {
    if (typeof e.stopPropagation === "function") { e.stopPropagation(); }
};

let nId = function() {
    return context.currentTaskId++;
};

let hId = function() {
    return context.currentHintId++;
};

let request = function(
    data = {},
    type = "GET",
    api = '/',
    method = '',
    isCSV = false,
    isPDF = false,
    call = (params) => {},
    params = {}) {
    call(params);
    return $.ajax({
        type: type,
        url: context["server"] + api + method,
        contentType: (!isCSV && !isPDF ? 'application/json; charset=utf-8' :
            (isCSV ? 'text/csv; charset=utf-8' : 'application/pdf')),
        data: JSON.stringify(data),
        encoding: (!isPDF ? 'UTF-8' : null),
        xhrFields: { withCredentials: true },
        cache: false,
    });
};

let showHint = (e, content) => { let i = hId(); $(".hints").trigger("19", [e, content, i]); return i; };
let hideHint = (hintId) => { $(".hints").trigger("20", [hintId]);};

let ms = (s,e) => {
    try {
        let w = s.width(), d = $(document).width();
        let l = $(e.target).scrollTop();
        if (e.pageX !== undefined) l += e.pageX;
        else l += $(e.target).offset().left + 50;
        l = (l + w > d ? d - w : l);
        s.css("top", (e.pageY !== undefined ? e.pageY : $(e.target).offset().top + 50) + $(e.target).scrollTop()).css("left", l);
    } catch (e) {}
}

let slug = (z) => {
    z = z.toLowerCase();
    z = z.replace(/[^a-z0-9 -]+/, '');
    z = z.trim();
    z = z.replace(' ', '-');
    return z;
}

let isFunction = (f) => { return typeof f === 'function'; };
let addScript = (res) => {
    let s = $('<script>' + res + '</script>');
    $("body").append(s);
    s.remove();
};

$(document).bind("DOMNodeRemoved", function(e) {
    $(e.target).trigger("remove");
});