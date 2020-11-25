let context = { currentTaskId: 0 };

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