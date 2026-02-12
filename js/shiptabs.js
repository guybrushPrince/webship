let createNewTab = function (event, id, hD = 1, tc = '.tab', tcc = '.tab-content',
                             thc = '.tab-header', ac = 'active') {
    if ($(event.target).parent('.' + $(id).data('id')).length > 0) {
        let max = Math.max.apply(Math, $(id).children(tc).map((i, c) => {
            return parseInt($(c).css("z-index"))
        }));
        let width = $(id).children(tc).children(thc).toArray().reduce((acc, c) => {
            return acc + parseFloat($(c).css("width")) + hD
        }, 0) - hD;
        $(event.target).css("z-index", max + 1);
        $(id).children(tc).removeClass(ac);
        $(event.target).addClass(ac);
        $(event.target).attr("tabId", "t" + context.tabId++);
        let tabHeader = $(event.target).children(thc);
        let w = (width - parseFloat($(tabHeader).css("width")));
        $(tabHeader).css("left", w === 0 ? -2 : w - 1);
        let conOld = $(id).children(tc).not(event.target).children(tcc);
        conOld.filter(":visible").toArray().forEach((e) => {
            let p = $(e).parent();
            context.tabOffset[p.attr("tabId")] = p.scrollTop();
        });
        conOld.hide();
    }
};
let closeTab = function (event, id, dE, hD = 1, tc = '.tab', tcc = '.tab-content',
                         thc = '.tab-header', ac = 'active') {
    if ($(event.target).parent('.' + $(id).data('id')).length > 0) {
        let missing = parseFloat($(event.target).children(".tab-header").css("width")) + hD;
        let next = $(event.target).nextAll();
        $(event.target).trigger(dE).remove();
        next.children(thc).each((i, t) => $(t).css("left", parseFloat($(t).css("left")) - missing));
        let childrenWithZ = $(id).children(tc).map((i, c) => {
            return {v: c, z: parseFloat($(c).css("z-index"))};
        });
        let max = Math.max.apply(Math, childrenWithZ.map((i, c) => c.z));
        $(id).children(tc).removeClass(ac);
        childrenWithZ.each((i, c) => (c.z === max ? (() => {
            $(c.v).children(tcc).show();
            $(c.v).addClass(ac);
            let tabId = $(c.v).attr("tabId");
            if (tabId in context.tabOffset) $(c.v).scrollTop(context.tabOffset[tabId]);
        })() : ""));
    }
};
let initTab = function (id, hD = 1, tc = '.tab', tcc = '.tab-content',
                        thc = '.tab-header', ac = 'active') {
    let that = $(id);
    that.children(tc).each((i, tab) => {
        let max = Math.max.apply(Math, $(tab).prevAll(tc).add(tab).map((i, c) => parseInt($(c).css("z-index"))));
        let width = $(tab).prevAll(tc).add(tab).children(thc).toArray().reduce((acc, c) => acc + parseFloat($(c).css("width")) + hD, 0) - hD;
        $(tab).css("z-index", max + 1);
        $(tab).attr("tabId", "t" + context.tabId++);
        let tabHeader = $(tab).children(thc);
        let w = (width - parseFloat($(tabHeader).css("width")));
        $(tabHeader).css("left", w === 0 ? -2 : w - 1);
    });
    let f = that.children(tc).first();
    let max = Math.max.apply(Math, $(that).children(tc).map((i, c) => parseInt($(c).css("z-index"))));
    $(f).css("z-index", max + 1);
    $(f).addClass(ac);
    let conOld = $(that).children(tc).children(tcc);
    conOld.filter(":visible").toArray().forEach((e) => {
        let p = $(e).parent();
        context.tabOffset[p.attr("tabId")] = p.scrollTop();
    });
    conOld.hide();
    let conNew = $(f).children(tcc);
    conNew.show();
    let p = conNew.parent();
    let tabId = p.attr("tabId");
    if (tabId in context.tabOffset) p.scrollTop(context.tabOffset[tabId]);
};
let focusTab = function (id, ctE, tc = '.tab', tcc = '.tab-content',
                         thc = '.tab-header', ac = 'active') {
    let that = $(id);
    that.parent().trigger(ctE);
    let tabs = that.parent().parent().children(tc);
    tabs.removeClass(ac);
    let max = Math.max.apply(Math, tabs.map((i, c) => parseInt($(c).css("z-index"))));
    that.parent().css("z-index", max + 1);
    that.parent().addClass(ac);
    let conOld = tabs.children(tcc);
    conOld.filter(":visible").toArray().forEach((e) => {
        let p = $(e).parent();
        context.tabOffset[p.attr("tabId")] = p.scrollTop();
    });
    conOld.hide();
    let conNew = that.parent().children(tcc);
    conNew.show();
    let p = conNew.parent();
    let tabId = p.attr("tabId");
    if (tabId in context.tabOffset) p.scrollTop(context.tabOffset[tabId]);
};