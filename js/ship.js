(function(){
    /*overloading of jQuery(...) function*/
    let _$ = jQuery;
    $ = jQuery = function(){
        let args = Array.prototype.slice.call(arguments);
        if (arguments.length === 1){
            args.push(_$._defaultRoot);
        }
        return _$.apply(_$, args);
    }
    jQuery.setDefaultRoot = function(element){ _$._defaultRoot = element; }

    for (let key in _$) {
        if (_$.hasOwnProperty(key)) {
            jQuery[key] = _$[key];
        }
    }
})();

$.fn.exists = function () {
    return this.length !== 0;
}
$.fn.existsGetElse = function (e = () => {}) {
    if (this.exists()) return this;
    else return e();
}
$.fn.findWithSelf = function (s) {
    return this.find(s).addBack(s);
};
$.fn._on = function (e,s,d,h) {
    let f = '__i_sh';
    let fn = (typeof s === 'function' ? s : (typeof d === 'function' ? d : h))
    let da = this.data(f);
    if (da === undefined) da = {};
    let ev = e;
    if (!Array.isArray(e)) ev = [ e ];
    ev.reduce((a,e) => {
        if (!(e in a)) a[e] = [];
        a[e].push(fn);
        return a;
    }, da);
    this.data(f, da);
    return this.on(e,s,d,h);
};
$.fn.rebind = function () {
    let f = '__i_sh';
    this.toArray().forEach((el) => {
        let da = $(el).data(f);
        if (da === undefined) return;
        Object.keys(da).forEach((e) => {
            da[e].forEach((h) => $(el).on(e, h));
        });
    });
    return this;
};
let isString = (s) => typeof s === "string";
let isJson = function (item) {
    let value = typeof item !== "string" ? JSON.stringify(item) : item;
    try {
        value = JSON.parse(value);
    } catch (e) {
        return false;
    }
    return typeof value === "object" && value !== null;
};
Array.prototype.unique = function () { return [...new Set(this)]; }

let context = { currentTaskId: 0, currentHintId: 0, tabId: 0, tabOffset: [], windows: [], formContexts: [] };

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
})();''

let isObject = function(x) { return typeof x === 'object' && !Array.isArray(x) && x !== null; }

let downloadFile = function(data, fileName, path = true) {
    let link = document.createElement('a');
    if (path) {
        link.href = data;
    } else {
        let blob = new Blob([data], {type: 'application/octet-binary'});
        link.href = window.URL.createObjectURL(blob);
    }
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
    isZIP = false,
    call = (params) => {},
    params = {}) {
    call(params);
    return $.ajax({
        type: type,
        url: context["server"] + api + method,
        contentType: (!isCSV && !isPDF && !isZIP ? 'application/json; charset=utf-8' :
            (isCSV ? 'text/csv; charset=utf-8' : (isPDF ? 'application/pdf' : 'application/json; charset=utf-8' ))),
        data: JSON.stringify(data),
        encoding: (!isPDF ? 'UTF-8' : null),
        xhrFields: { withCredentials: true },
        cache: false,
    });
};

let gc = function () {
    request(
        { views: $('[data-id]').toArray().map(d => $(d).data("id").split(" ")).flat() },
        "POST",
        context["apiSafe"](),
        '_gc'
    );
    $('script').remove();
    $('head style').html($('style').toArray().map(d => $(d).html()).join("\n"));
    $('body style').remove();
};

let showHint = (e, content) => { let h = $(".hints"); $(h).children('.hint').remove(); let i = hId(); $(h).trigger("19", [e, content, i]); return i; };
let hideHint = (hintId) => { $(".hints").trigger("20", [hintId]);};
let addHintListener = (id, content) => {
    let hTO;
    let h = false;
    $(id).hover(function(event) {
        sp(event); if (event.target !== this) return;
        hTO = setTimeout(() => {
            if (!$(id).exists()) return;
            h = showHint(id, content);
        }, 1500);
    }, () => {
        clearTimeout(hTO);
        if (h !== false) hideHint(h);
    });
    $(id).on("30", function () {
        clearTimeout(hTO);
        if (h !== false) hideHint(h);
    });
};

let ms = (s,e) => {
    try {
        let w = s.width(), d = $(document).width();
        let l = $(e.target).is("textarea") || $(e.target).is("iframe") ? 0 : $(e.target).scrollLeft();
        if (e.pageX !== undefined) l += e.pageX;
        else l += $(e.target).offset().left + 50;
        l = (l + w > d ? d - w : l);
        let ta = $(e.target).is("textarea") || $(e.target).is("iframe") ? 0 : $(e.target).scrollTop();
        s.css("top", (e.pageY !== undefined ? e.pageY : $(e.target).offset().top + 50) + ta).css("left", l);
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
let clearPaste = (e) => {
    e = (e.originalEvent || e);
    let paste = (e.clipboardData || window.clipboardData).getData('text');
    let i = e.target;
    let st = i.selectionStart;
    let en = i.selectionEnd;
    let val = '';
    if ('value' in i) val = i.value;
    else val = i.innerHTML;
    val = val.substring(0, st) + paste + val.substring(en, val.length);
    if ('value' in i) i.value = val;
    else i.innerHTML = val;
    e.preventDefault();
};
let resizeHeight = function(e) {
    if ('target' in e) e = this;
    let min = 14.5
    $(e).height(min + "px");
    $(e).height(Math.max(min, e.scrollHeight) + "px");
};
let asSel = function(v) {
    if (v === undefined || v === null) return $();
    if (typeof v === 'object') v = Object.values(v);
    return (!Array.isArray(v) ? v : v.join(','));
};

let drag = function(p, d, info, im = "in-move", mcl = "movable", h = "hover", o = "over",
                    mp = "move-position") {
    $(d).on('mousedown', (e) => p.draggable = true);
    $(d).on('mouseup', (e) => p.draggable = false);

    $(p).on('dragstart', dragMouseDown);
    $(p).on('drag', elementDrag);
    $(p).on('drop', dropDragElement);
    $(p).on('dragend', closeDragElement);
    $(p).on('dragover', overMovePosition);
    $(p).on('dragenter', enterMovePosition);
    $(p).on('dragleave', leaveMovePosition);

    function dragMouseDown(e) {
        if ($(p).has($(e.target)).length >= 1 || $(p).is($(e.target))) {
            $(p).addClass(im);
            $(p).trigger('32');
            if (e.dataTransfer === undefined) e.dataTransfer = e.originalEvent.dataTransfer;
            e.dataTransfer.setData('related', info.related);
        }
    }
    function elementDrag(e) {
        if ($(p).has($(e.target)).length >= 1) {}
    }
    function dropDragElement(e) {
        e.preventDefault();
        let realT;
        let t = realT = $(e.target);
        t.removeClass(h);
        if (!t.hasClass(mcl)) t = t.closest('.' + mcl);
        t.removeClass(o);
        t.removeClass('not-bottom');
        t.removeClass('not-top');

        if (realT.hasClass(mp)) {
            if (e.dataTransfer === undefined) e.dataTransfer = e.originalEvent.dataTransfer;
            let related = e.dataTransfer.getData('related');
            let top = realT.hasClass('top');
            $(p).trigger('33', {
                direction: (top ? 'before' : 'after'),
                which: related,
                where: t.data('related')
            });
        }
    }
    function closeDragElement(e) {
        let t = $(e.target);
        if (!t.hasClass(mcl)) t = t.closest('.' + mcl);
        t.removeClass(im);
        p.draggable = false;
    }
    function enterMovePosition(e) {
        let t = $(e.target);
        if (!t.hasClass(im) && t.parents('.' + im).length === 0) {
            let n;
            if (!t.hasClass(mcl)) n = $(e.target).closest('.' + mcl);
            else n = t;
            if (n.length >= 1) {
                e.preventDefault();
                n.addClass(o);
                if (n.next('.' + im).length >= 1) n.addClass('not-bottom');
                if (n.prev('.' + im).length >= 1) n.addClass('not-top');
            }
            if (t.hasClass(mp)) t.addClass(h);
        }
    }
    function overMovePosition(e) {
        let t = $(e.target);
        let n;
        if (!t.hasClass(mcl)) n = $(e.target).closest('.' + mcl);
        else n = t;
        if (n.length >= 1) {
            e.preventDefault();
       }
    }
    function leaveMovePosition(e) {
        let t = $(e.target);
        if (t.hasClass(mcl) || t.parents('.' + mcl).length >= 1) {
            let rT = $(e.relatedTarget);
            if (!rT.hasClass(mcl)) {
                let c = rT.closest('.' + mcl);
                if (c.length >= 1) rT = c;
            }
            let same = false;
            if (!t.hasClass(mcl)) {
                t.removeClass(h);
                if ($('> .' + mp, rT).length >= 1 && rT.has(t).length >= 1) same = true;
                t = $(t).closest('.' + mcl);
            }
            if (!(same || rT.eq(t).length >= 1)) {
                t.removeClass(o);
            }
        }
    }
};
let openWindow = function (url, name, handler, takeView) {
    let w = {
        window: open(url, name, "location=no,toolbar=no,menubar=no,status=no"),
        handler: handler
    };
    if (takeView !== null) w["takeView"] = takeView;
    context["windows"].push(w);
    checkWindowClosed();
    return context["windows"][context["windows"].length - 1].window;
};
let checkWindowClosed = (function() {
    this.interval = false;
    let that = this;
    return function() {
        if (that.interval === false && context["windows"].length > 0) {
            that.interval = setInterval(function () {
                context["windows"] = context["windows"].filter((w) => {
                    if ('takeView' in w &&
                        'document' in w.window && w.window.document !== null && w.window.document !== undefined) {
                        let wi = w.window.document;
                        if ($(wi).find("body").html().trim() !== "") {
                            $(wi).find("body > div").replaceWith(w.takeView);
                            delete w.takeView;
                        }
                    }
                    if (w.window.closed) {
                        w.handler(w.window);
                        return false;
                    } else return true;
                });
                let docs = [ document ].concat(context["windows"].map(wi => {
                    return wi.window.document;
                }));
                $.setDefaultRoot(docs);
                if (context["windows"].length === 0) {
                    clearInterval(that.interval);
                    that.interval = false;
                }
            }, 1000);
        }
    };
})();
let eventBus = (function () {
    function EventBus() {
        this.registry = {};
        this.bind = function (view, objId, event, fun = (data) => {}, igData = null, scope = undefined) {
            event = "e" + event;
            if (scope === undefined) scope = 'undefined';
            let reg;
            if (!this.registry.hasOwnProperty(objId)) {
                reg = {};
                this.registry[objId] = reg;
            } else reg = this.registry[objId];
            let sc;
            if (!reg.hasOwnProperty(scope)) {
                sc = [];
                reg[scope] = sc;
            } else sc = reg[scope];
            let ev;
            if (!sc.hasOwnProperty(event)) {
                ev = [];
                sc[event] = ev;
            } else ev = sc[event];
            sc[event] = ev = ev.filter((el) => {
                return $('[data-id="' + el.view.attr('data-id') + '"]').length > 0;
            });
            igData = (typeof igData === 'object' && igData !== null && igData !== undefined) ? Object.values(igData) : igData;
            igData = (igData === undefined) ? null : igData;
            ev.push({ view: view, fun: fun, igData: igData });
        };
        this.unbind = function (view, objId, event, scope = undefined) {
            event = "e" + event;
            if (scope === undefined) scope = 'undefined';
            if (!this.registry.hasOwnProperty(objId)) return;
            let reg = this.registry[objId];
            if (!reg.hasOwnProperty(scope)) return;
            let sc = reg[scope];
            if (!sc.hasOwnProperty(event)) return;
            sc[event] = sc[event].filter((el) => {
                return !$(el.view).is($(view));
            });
        };
        this.trigger = function (objId, event, callee = undefined, scopes = undefined, data = undefined) {
            event = "e" + event;
            if (this.registry.hasOwnProperty(objId)) {
                let reg = this.registry[objId];
                if (scopes !== undefined) {
                    if (typeof scopes === 'object') scopes = Object.values(scopes);
                    if (!Array.isArray(scopes)) scopes = [ scopes ];
                }
                else scopes = Object.getOwnPropertyNames(reg);
                scopes.forEach((scope) => {
                    if (reg.hasOwnProperty(scope)) {
                        let sc = reg[scope];
                        if (sc.hasOwnProperty(event)) {
                            let ev = sc[event];
                            ev.forEach((b) => {
                                let locData = data;
                                if (data !== undefined) {
                                    locData = Object.assign({}, data);
                                    if (b.igData !== null && b.igData !== undefined) {
                                        for (let f of b.igData) {
                                            delete locData[f];
                                        }
                                    }
                                    locData = Object.assign(locData, {_object: objId});
                                } else locData = { _object: objId };

                                console.log(['Event Bus', event, objId, scope, locData, b, b.view.toArray()]);
                                b.fun(Object.assign(locData, {_view: b.view}));
                            });
                        }
                    }
                });
            }
        };
    }
    return new EventBus();
})();
let reheight = function(el = undefined) {
    let v = el;
    if (el === undefined) v = this;
    if ('target' in v) v = v.target;
    v = $(v);
    if (!v.is('textarea')) v = v.find('textarea').toArray();
    else v = [v];
    v.forEach(function (v) {
        v = $(v);
        let pt = v.css("padding-top");
        let pb = v.css("padding-bottom");
        pt = parseInt(pt.substring(0, pt.length - 2));
        pb = parseInt(pb.substring(0, pb.length - 2));
        v.height('1px');
        v.height(v.prop("scrollHeight") - (pt + pb));
    });
};

let checkIfEmpty = function(el, clazz) {
    $(el).toArray().forEach((e) => {
        if ($(e).children('span').toArray().filter((s) => $.trim(($(s).html()))).length === 0) {
            $(e).addClass(clazz);
        } else $(e).removeClass(clazz);
    });
};

let scaleChildren = function (el) {
    el = $(el);
    let h = el.parent().outerHeight();
    $(el).children('div').toArray().forEach(function (c) {
        c = $(c);
        let o = $(c.children().toArray()[0]);
        let hc = o.outerHeight();
        let wc = o.outerWidth();
        let r = Math.min(1, h / hc);
        let nw = wc * r;
        let nh = hc * r;
        c.css('--closed-scale', r);
        c.css('--closed-translate', ((-1/2) * (hc - nh)) + 'px ' + ((-1/2) * (wc - nw)) + 'px');
    });
};

let addConSelectFunctionality = function (el,max = 1,def = -1) {
    def = '' + def;
    el = $(el);
    let li = el.find('.c-list');
    let sel = $(el).find('select');
    let ens = li.children('div');
    if (sel.length > 0 && ens.length > 0) {
        if (max === null) max = ens.length - 1;
        ens.click(function (ev) {
            let t = $(this);
            let tv = t.children('[data-opt-value]').data('opt-value');
            if (t.hasClass('def')) { // Please select
                if (t.hasClass('selected') && max >= 2) {
                    if (ens.filter('.selected').length >= 2) {
                        t.removeClass('selected');
                        sel.val([]).trigger('change');
                    } else {
                        el.removeClass('open');
                    }
                } else {
                    ens.removeClass('selected');
                    t.addClass('selected');
                    sel.val(tv).trigger('change');
                    el.removeClass('open');
                }
            } else if (max === 1) { // Please select
                ens.removeClass('selected');
                t.addClass('selected');
                sel.val(tv).trigger('change');
                el.removeClass('open');
            } else {
                let ov = sel.val();
                if (!t.hasClass('selected')) {
                    if (ov.includes(def)) {
                        ens.filter('.def.selected').removeClass('selected');
                        ov = [];
                    }
                    ov.push(tv);
                    if (ens.filter('.selected').length < max) {
                        t.addClass('selected');
                        sel.val(ov).trigger('change');
                        if (ens.filter('.selected').length >= max) {
                            ens.filter(':not(.selected):not(.def)').addClass('max-reached');
                        }
                    }
                } else {
                    ov = ov.filter((v) => v !== tv);
                    t.removeClass('selected');
                    if (ov.length === 0) {
                        ov.push(def);
                        ens.filter(':not(.selected).def').addClass('selected');
                    }
                    sel.val(ov).trigger('change');
                    ens.removeClass('max-reached');
                }
            }
        });
    }
};

let setOpenSelectPosition = function(el) {
    el = $(el);
    el.addClass('open');
    let li = el.find('.c-list');
    let o = el.offset();
    let y = o.top, l = o.left, dw = $(document).width(), lw = li.outerWidth();
    let x = (l + lw) <= dw ? l : dw - lw;
    li.css('top', y).css('left', x);
};

let applySelectChanges = function (el) {
    el = $(el);
    el.on('change', function () {
        let vals = el.val();
        if (vals === null) return;
        let cs = el.parent();
        if (!Array.isArray(vals)) vals = [ vals ];
        cs.find('.c-list').children().removeClass('selected');
        vals.forEach((v) => {
            cs.find('[data-opt-value="' + v + '"]').parent().addClass('selected');
        });
    });
    el.trigger('change');
}