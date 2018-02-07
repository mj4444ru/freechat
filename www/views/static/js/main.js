/* global jQuery */
var FCEvent;
(function (FCEvent) {
    'use strict';
    FCEvent.events = {};
    FCEvent.handlers = {};
    $(document).on('click', '[data-fc-click]', function (event) {
        event.preventDefault();
        var $target = $(event.currentTarget);
        var handlerName = $target.attr('data-fc-click');
        if (handlerName && FCEvent.handlers[handlerName]) {
            return FCEvent.handlers[handlerName].call(this, event, $target);
        }
        else {
            var eventName = 'fc-click-' + $(event.currentTarget).attr('data-fc-click');
            $('body').trigger($.Event(eventName, {
                originalEvent: event
            }));
        }
    });
})(FCEvent || (FCEvent = {}));
var FCString;
(function (FCString) {
    'use strict';
    function isValidErrorText(text) {
        return (typeof text === 'string') && text && text.length < 100;
    }
    FCString.isValidErrorText = isValidErrorText;
})(FCString || (FCString = {}));
var FCData;
(function (FCData) {
    'use strict';
    function isAuthData(data) {
        return $.isPlainObject(data) && (typeof data.topMenu === 'string') && $.isPlainObject(data.userInfo);
    }
    FCData.isAuthData = isAuthData;
})(FCData || (FCData = {}));
var FCUtils;
(function (FCUtils) {
    'use strict';
    function refreshPage() {
        window.location.reload(true);
    }
    FCUtils.refreshPage = refreshPage;
})(FCUtils || (FCUtils = {}));
var FCAjax;
(function (FCAjax) {
    'use strict';
    function isRedirect(jqXHR) {
        return jqXHR && jqXHR.getResponseHeader('X-Redirect');
    }
    FCAjax.isRedirect = isRedirect;
    function getCsrfParam() {
        return $('meta[name=csrf-param]').attr('content');
    }
    function getCsrfToken() {
        return $('meta[name=csrf-token]').attr('content');
    }
    $.ajaxPrefilter(function (options, originalOptions, xhr) {
        if (!options.crossDomain && getCsrfParam()) {
            xhr.setRequestHeader('X-CSRF-Token', getCsrfToken());
        }
    });
    $(document).ajaxComplete(function (event, xhr) {
        var url = xhr && xhr.getResponseHeader('X-Redirect');
        if (url) {
            window.location.assign(url);
        }
    });
})(FCAjax || (FCAjax = {}));
var FCPjax;
(function (FCPjax) {
    'use strict';
    if ('pushState' in window.history) {
        var fbCount_1 = 0;
        $(function () {
            $.fancybox.defaults.hash = false;
        });
        $(document).pjax('a', '#pjax-container');
        $(document).on('pjax:end', function () {
            FCTopMenu.updateActive();
        });
        $(document).on('beforeShow.fb', function (e, instance, slide) {
            fbCount_1++;
            var opts = instance ? (instance.current ? instance.current.opts : instance.opts) : {};
            var hash = opts.$orig ? opts.$orig.data('fancybox') : '';
            var href = window.location.pathname + window.location.search + (hash ? '#' + hash : window.location.hash);
            var pushState = false;
            if (window.history.state) {
                pushState = !window.history.state.fbindex || window.history.state.fbindex < fbCount_1;
            }
            else {
                pushState = !hash || ('#' + hash) != window.location.hash;
            }
            if (pushState) {
                window.history.pushState({ fb: true, fbhref: href, fbindex: fbCount_1 }, document.title, href);
            }
            var title = opts.$orig ? opts.$orig.text() : (opts.title ? opts.title : '');
            if (title) {
                document.title = title;
            }
        });
        $(document).on('afterClose.fb', function (e, instance, slide) {
            fbCount_1--;
            if (window.history.state.fbindex && window.history.state.fbindex > fbCount_1) {
                history.go(fbCount_1 - window.history.state.fbindex);
            }
        });
        $(window).on('popstate.fb', function (event) {
            if (fbCount_1 && (!event.state || !event.state.fbindex || (event.state.fbindex < fbCount_1))) {
                $.fancybox.close();
                if (event.state && event.state.title) {
                    document.title = event.state.title;
                }
            }
        });
    }
})(FCPjax || (FCPjax = {}));
var FCRequired;
(function (FCRequired) {
    'use strict';
    $(document).on('blur', 'input.fds-required', function (event) {
        validateRequiredInput($(event.currentTarget));
    });
    function validateRequiredInput($input) {
        var val = $input.val();
        val = val && val.trim ? val.trim() : val;
        if (val) {
            $input.removeClass('is-invalid').addClass('is-valid');
            return val;
        }
        else {
            $input.removeClass('is-valid').addClass('is-invalid');
            return false;
        }
    }
    FCRequired.validateRequiredInput = validateRequiredInput;
})(FCRequired || (FCRequired = {}));
var FCPopover;
(function (FCPopover) {
    'use strict';
    FCEvent.handlers.popover = function (event, $target) {
        var config = {
            title: $target.attr('title'),
            content: $target.attr('data-content'),
            style: '',
            "class": 'flatcard-teal',
            btnCloseCaption: $target.attr('data-btn-close'),
            btnCloseClass: 'btn-flat-teal'
        };
        var maxWidth = $target.attr('data-max-width');
        if (maxWidth) {
            config.style += 'max-width:' + maxWidth + ';';
        }
        showPopover(config);
    };
    function showPopover(config) {
        config["class"] = (config["class"] == undefined) ? 'card-teal' : config["class"];
        config.style = (config.style == undefined) ? '' : config.style;
        config.btnCloseClass = (config.btnCloseClass == undefined) ? 'btn-teal' : config.btnCloseClass;
        config.btnCloseCaption = (config.btnCloseCaption == undefined) ? 'OK' : config.btnCloseCaption;
        config.btnCloseCaption = '<div class="card-btn-line"><button type="button" class="btn ' + config.btnCloseClass + '" data-fancybox-close>' + config.btnCloseCaption + '</button></div>';
        var title = '<div class="card-header">' + config.title + '</div>';
        var content = '<div class="card-body"><p class="card-text">' + config.content + '</p>' + config.btnCloseCaption + '</div>';
        var dialog = '<div class="card modal ' + config["class"] + '" tabindex="-1" role="dialog" style="' + config.style + '">' + title + content + '</div>';
        $.fancybox.open(dialog, { title: config.title });
    }
    FCPopover.showPopover = showPopover;
})(FCPopover || (FCPopover = {}));
var FCTopMenu;
(function (FCTopMenu) {
    'use strict';
    function updateActive() {
        var $topMenu = $('#top-menu');
        var $activeNavLink = $topMenu.find('a.active');
        var pathname = window.location.pathname + window.location.search;
        if ($activeNavLink.attr('href') !== pathname) {
            $activeNavLink.removeClass('active');
            $topMenu.find('a[href="' + pathname + '"]').addClass('active');
        }
    }
    FCTopMenu.updateActive = updateActive;
    function update(content) {
        var $topMenu = $('#top-menu');
        $topMenu.fadeOut(function () {
            $topMenu.html(content);
            updateActive();
            $topMenu.fadeIn();
        });
    }
    FCTopMenu.update = update;
})(FCTopMenu || (FCTopMenu = {}));
var FCLoginForm;
(function (FCLoginForm) {
    'use strict';
    FCEvent.handlers.loginForm = function (event, $target) {
        var $form = $target.closest('form');
        var login = FCRequired.validateRequiredInput($form.find('input.login'));
        var password = FCRequired.validateRequiredInput($form.find('input.password'));
        var $badPwd = $form.find('.bad-password');
        if (!login || !password) {
            $badPwd.text($badPwd.attr('data-msg-empty')).fadeIn();
            return;
        }
        $badPwd.hide();
        $form.find('.loading').show();
        var action = $form.attr('action');
        $.post(action, { login: login, password: password, url: window.location.toString() }).done(ajaxDone).fail(ajaxFail);
        function ajaxDone(data) {
            $form.find('.loading').hide();
            if (FCData.isAuthData(data)) {
                FCEvent.events.onLogin(data);
                $form.parents('.modal').modal('hide');
                return;
            }
            else if (data === false) {
                $badPwd.text($badPwd.attr('data-msg-bad')).fadeIn();
            }
            else if (FCString.isValidErrorText(data)) {
                $badPwd.text(data).fadeIn();
            }
            else {
                $badPwd.text($badPwd.attr('data-msg-error')).fadeIn();
            }
        }
        function ajaxFail(jqXHR) {
            $form.find('.loading').hide();
            if (!FCAjax.isRedirect(jqXHR)) {
                $badPwd.text($badPwd.attr('data-msg-error')).fadeIn();
            }
        }
    };
    FCEvent.handlers.loginFormChange = function (event, $target) {
        $target.parents('form').find('.bad-password:visible').fadeOut();
    };
})(FCLoginForm || (FCLoginForm = {}));
var FCGuestRegisterForm;
(function (FCGuestRegisterForm) {
    'use strict';
    var guestFormData = { block: '1', step: '1', params: [] };
    FCEvent.handlers.guestForm = function (event, $target) {
        var param = $target.attr('data-param');
        var action = $target.attr('data-action');
        var nextBlock = $target.attr('data-next-block');
        var nextStep = $target.attr('data-next-step');
        if (param) {
            guestFormData.params.push(param);
        }
        if (action) {
            //$target.attr('disabled', 'disabled')//mj4444
            $.post(action, { params: guestFormData.params, url: window.location.toString() }).done(ajaxDone).fail(ajaxFail);
        }
        else if (nextBlock) {
            $('#guest-register-form div[data-block=' + guestFormData.block + ']').fadeOut(function () {
                $('#guest-register-form div[data-step=' + nextStep + ']').show();
                $('#guest-register-form div[data-block=' + nextBlock + ']').fadeIn();
                guestFormData.step = nextStep;
                guestFormData.block = nextBlock;
            });
        }
        else if (nextStep) {
            $('#guest-register-form div[data-step=' + guestFormData.step + ']').fadeOut(function () {
                $('#guest-register-form div[data-step=' + nextStep + ']').fadeIn();
                guestFormData.step = nextStep;
            });
        }
        function ajaxDone(data) {
            if (FCData.isAuthData(data)) {
                FCEvent.events.onLogin(data);
            }
            else {
                alert(FCString.isValidErrorText(data) ? data : FCMain.defErrorMessage);
                FCUtils.refreshPage();
            }
        }
        function ajaxFail(jqXHR) {
            alert(FCMain.defErrorMessage);
            FCUtils.refreshPage();
        }
    };
})(FCGuestRegisterForm || (FCGuestRegisterForm = {}));
var FCMain;
(function (FCMain) {
    'use strict';
    FCMain.defErrorMessage = 'Error';
    FCEvent.events.onLogin = function (data) {
        FCTopMenu.update(data.topMenu);
        $('.guest-block:visible').fadeOut(function () {
            if ($('.guest-block:visible').length === 0) {
                $('.user-block').fadeIn();
            }
        });
        //data.messages && updateMsgHistory(data.messages);//TODO:
    };
})(FCMain || (FCMain = {}));
//namespace Main {
//    'use strict';
//
//    const defErrorMessage = 'Error';
//
//    $(document).ready(function () {
//        $(document).on('click', '[data-fc-click]', function (event) {
//            event.preventDefault();
//            const eventName = 'fc-click-' + ($(event.currentTarget).attr('data-event') as string);
////            $('body').trigger(.Event(eventName, {
////                originalEvent: event,
////            });
//        });
//
////        $(document).on('click', '.fds-btn[data-event]', function (event) {
////            event.preventDefault();
////            const $target = $(event.currentTarget);
////            const handlerName = $target.attr('data-event') as string;
////            if (handlerName && handlers[handlerName]) {
////                return handlers[handlerName].call(this, event, $target);
////            } else {
////                $('body').trigger($.Event('fds-btn-' + handlerName, {
////                    originalEvent: event,
////                }));
////            }
////        });
//        $(document).on('blur', 'input.fds-required', function (event) {
//            validateRequiredInput($(event.currentTarget));
//        });
//        $(document).on('change', 'input.fds-change', function (event) {
//            const $target = $(event.currentTarget);
//            const handlerName = $target.attr('data-event-change');
//            if (handlerName && handlers[handlerName]) {
//                return handlers[handlerName].call(this, event, $target);
//            } else {
//                $('body').trigger($.Event('fds-btn-' + handlerName, {
//                    originalEvent: event,
//                }));
//            }
//        });
//        $(document).on('keypress', 'input.fds-keypress', function (event) {
//            const $target = $(event.currentTarget);
//            const handlerName = $target.attr('data-event-keypress') || $target.attr('data-event-change');
//            if (handlerName && handlers[handlerName]) {
//                return handlers[handlerName].call(this, event, $target);
//            }
//        });
//        $(document).on('paste', '[contenteditable]', function (e) {
//            e.preventDefault();
//            const clp = ((e.originalEvent || e) as any).clipboardData;
//            if (clp === undefined || clp === null) {
//                const text = (window as any).clipboardData.getData('text') || '';
//                if (text !== '') {
//                    if (window.getSelection) {
//                        const newNode = document.createElement('span');
//                        newNode.innerHTML = text;
//                        window.getSelection().getRangeAt(0).insertNode(newNode);
//                    } else {
//                        (document as any).selection.createRange().pasteHTML(text);
//                    }
//                }
//            } else {
//                const text = clp.getData('text/plain') || '';
//                if (text !== '') {
//                    document.execCommand('insertText', false, text);
//                }
//            }
//        });
//        $(document).on('keydown', '[contenteditable]', function (e) {
//            if ((e.keyCode || (e as any).witch) === 13) {
//                if (e.ctrlKey) {
//                    e.preventDefault();
//                    $(e.currentTarget).parent().find('.message-send').click();
//                }
//            }
//        });
////        if ((window as any).userMessages) {
////            updateMsgHistory((window as any).userMessages);
////        }
//    });
//
//    interface PopoverData {
//        title: string;
//        content: string;
//        class?: string;
//        style?: string;
//        btnCloseClass?: string;
//        btnCloseCaption?: string;
//    }
//
//    interface AuthData {
//        topMenu: string;
//        userInfo: {
//            id?: string;
//        }
//        messages?: any;
//    }
//
//    interface GuestFormData {
//        block: string;
//        step: string;
//        params: string[];
//    }
//
//    let guestFormData: GuestFormData = {block: '1', step: '1', params: []};
//
//    let handlers: {[key: string]: Function} = {
//        popover: function (event: JQueryEventObject, $target: JQuery) {
//            let config: PopoverData = {
//                title: $target.attr('title'),
//                content: $target.attr('data-content'),
//                style: '',
//                class: 'flatcard-teal',
//                btnCloseCaption: $target.attr('data-btn-close'),
//                btnCloseClass: 'btn-flat-teal'
//            };
//            const maxWidth = $target.attr('data-max-width');
//            if (maxWidth) {
//                config.style += 'max-width:' + maxWidth + ';';
//            }
//            showPopover(config);
//        },
//        loginForm: function (event: JQueryEventObject, $target: JQuery) {
//            const $form = $target.closest('form');
//            const login = validateRequiredInput($form.find('input.login'));
//            const password = validateRequiredInput($form.find('input.password'));
//            const $badPwd = $form.find('.bad-password');
//            if (!login || !password) {
//                $badPwd.text($badPwd.attr('data-msg-empty')).fadeIn();
//                return;
//            }
//            $badPwd.hide();
//            $form.find('.loading').show();
//            const action = $form.attr('action');
//            $.post(action, {login: login, password: password, url: window.location.toString()}).done(ajaxDone).fail(ajaxFail);
//            function ajaxDone(data: AuthData | boolean | string) {
//                $form.find('.loading').hide();
//                if (!$.isPlainObject(data) || !(data as AuthData).topMenu) {
//                    if (data === false) {
//                        $badPwd.text($badPwd.attr('data-msg-bad')).fadeIn();
//                    } else if (errorIsValidText(data)) {
//                        $badPwd.text(data).fadeIn();
//                    } else {
//                        $badPwd.text($badPwd.attr('data-msg-error')).fadeIn();
//                    }
//                    return;
//                }
//                events.onLogin(data as AuthData);
//                $form.parents('.modal').modal('hide');
//            }
//            function ajaxFail(jqXHR: JQueryXHR) {
//                $form.find('.loading').hide();
//                if (!ajaxIsRedirect(jqXHR)) {
//                    $badPwd.text($badPwd.attr('data-msg-error')).fadeIn();
//                }
//            }
//        },
//        loginFormChange: function (event: JQueryEventObject, $target: JQuery) {
//            const $form = $target.parents('form');
//            const $pwd = $form.find('.bad-password:visible');
//            if ($pwd.length) {
//                $form.find('.bad-password').fadeOut();
//            }
//        },
//        guestForm: function (event: JQueryEventObject, $target: JQuery) {
//            const param = $target.attr('data-param');
//            const action = $target.attr('data-action');
//            const nextBlock = $target.attr('data-next-block');
//            const nextStep = $target.attr('data-next-step');
//            if (param) {
//                guestFormData.params.push(param);
//            }
//            if (action) {
//                //$target.attr('disabled', 'disabled')//mj4444
//                $.post(action, {params: guestFormData.params, url: window.location.toString()}).done(ajaxDone).fail(ajaxFail);
//            } else if (nextBlock) {
//                $('#guest-register-form div[data-block=' + guestFormData.block + ']').fadeOut(function () {
//                    $('#guest-register-form div[data-step=' + nextStep + ']').show();
//                    $('#guest-register-form div[data-block=' + nextBlock + ']').fadeIn();
//                    guestFormData.step = nextStep;
//                    guestFormData.block = nextBlock;
//                });
//            } else if (nextStep) {
//                $('#guest-register-form div[data-step=' + guestFormData.step + ']').fadeOut(function () {
//                    $('#guest-register-form div[data-step=' + nextStep + ']').fadeIn();
//                    guestFormData.step = nextStep;
//                });
//            }
//            function ajaxDone(data: AuthData) {
//                if (!$.isPlainObject(data) || !data.topMenu) {
//                    alert(errorIsValidText(data) ? data : defErrorMessage);
//                    refreshPage();
//                    return false;
//                }
//                events.onLogin(data);
//                return true;
//            }
//            function ajaxFail(jqXHR: JQueryXHR) {
//                alert(defErrorMessage);
//                refreshPage();
//            }
//        }
//
//    };
//
//    namespace events {
//        export function onLogin(data: AuthData) {
//            if (data.topMenu) {
//                var $topMenu = $('#top-menu');
//                var activeHref = $topMenu.find('.nav-link.active').attr('href');
//                $topMenu.fadeOut(function () {
//                    $topMenu.html(data.topMenu);
//                    if (activeHref) {
//                        $topMenu.find('.nav-link').each(function (i, m) {
//                            const $m = $(m);
//                            if ($m.attr('href') === activeHref) {
//                                $m.addClass('active');
//                            }
//                        });
//                    }
//                    $topMenu.fadeIn();
//                });
//            }
//            $('.guest-block:visible').fadeOut(function () {
//                if ($('.guest-block:visible').length === 0) {
//                    $('.user-block').fadeIn();
//                }
//            });
//            //data.messages && updateMsgHistory(data.messages);//TODO:
//        };
//    }
//
//    function errorIsValidText(text: any): text is string {
//        return (typeof text === 'string') && text && text.length < 100;
//    }
//
//    function trim(text: string): string {
//        return (text || '').replace(/^\s+|\s+$/g, '');
//    }
//
//    function clean(text: string): string {
//        return text ? text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;') : '';
//    }
//
//    function getCsrfParam(): string {
//        return $('meta[name=csrf-param]').attr('content');
//    }
//
//    function getCsrfToken(): string {
//        return $('meta[name=csrf-token]').attr('content');
//    }
//
//    function ajaxIsRedirect(jqXHR: JQueryXHR): string {
//        return jqXHR && jqXHR.getResponseHeader('X-Redirect');
//    }
//    function ajaxFail(jqXHR: JQueryXHR) {
//        if (!ajaxIsRedirect(jqXHR)) {
//            alert(defErrorMessage);
//        }
//    }
//
//    function refreshPage() {
//        window.location.reload(true);
//    }
//
//    function validateRequiredInput($input: JQuery) {
//        let val = $input.val() as string;
//        val = val && val.trim ? val.trim() : val;
//        if (val) {
//            $input.removeClass('is-invalid').addClass('is-valid');
//            return val;
//        } else {
//            $input.removeClass('is-valid').addClass('is-invalid');
//            return false;
//        }
//    }
//
//    $.ajaxPrefilter(function (options, originalOptions, xhr) {
//        if (!options.crossDomain && getCsrfParam()) {
//            xhr.setRequestHeader('X-CSRF-Token', getCsrfToken());
//        }
//    });
//
//    $(document).ajaxComplete(function (event, xhr) {
//        const url = xhr && xhr.getResponseHeader('X-Redirect');
//        if (url) {
//            window.location.assign(url);
//        }
//    });
//
//    function showPopover(config: PopoverData) {
//        config.class = (config.class == undefined) ? 'card-teal' : config.class;
//        config.style = (config.style == undefined) ? '' : config.style;
//        config.btnCloseClass = (config.btnCloseClass == undefined) ? 'btn-teal' : config.btnCloseClass;
//        config.btnCloseCaption = (config.btnCloseCaption == undefined) ? 'OK' : config.btnCloseCaption;
//        config.btnCloseCaption = '<div class="card-btn-line"><button type="button" class="btn ' + config.btnCloseClass + '" data-fancybox-close>' + config.btnCloseCaption + '</button></div>';
//        const title = '<div class="card-header">' + config.title + '</div>';
//        const content = '<div class="card-body"><p class="card-text">' + config.content + '</p>' + config.btnCloseCaption + '</div>';
//        const dialog = '<div class="card modal ' + config.class + '" tabindex="-1" role="dialog" style="' + config.style + '">' + title + content + '</div>';
//        $.fancybox.open(dialog as any, {title: config.title});
//    }
//
//
//
//
//
////        var events = {};
//
//    //    var flatPage = {};
//    //    var modalPage = {
//    //        textLoading: 'text-loading'
//    //    };
//
////
////
////
////    events.sendMessageGenderForm = function (event, $target) {
////        var $parent = $target.parent();
////        var action = $parent.attr('data-action');
////        var params = $target.attr('data-params');
////        $.post(action, {params: params, url: window.location.toString()}).done(ajaxDone).fail(ajaxFail);
////        function ajaxDone(data) {
////            if (events.guestForm.registerAjaxDone(data)) {
////                debugger;
////                var $messageForm = $parent.parent().parent();
////                var $buttonSend = $('.message-send', $messageForm);
////                //                data.userId && $buttonSend.data('from', data.userId);
////                $buttonSend.removeAttr('data-toggle');
////                setTimeout(function () {
////                    $('#message-editor').focus();
////                    $buttonSend.click();
////                }, 10);
////            }
////        }
////    };
////
////    events.flatPage = function (event, $target) {
////        var url = $target.attr('href');
////        if (!url) {
////            return;
////        }
////        modalPage.show(modalPage.textLoading);
////    };
////
////    events.sendMessage = function (event, $target) {
////        var $messageForm = $target.parent().parent();
////        var $errorMsg = $('.message-send-error', $messageForm).hide();
////        var $loadingMsg = $('.message-send-loading', $messageForm);
////        var $editor = $('.message-editor', $messageForm);
////        var $buttonSend = $('.message-send', $messageForm);
////        if ($buttonSend.attr('data-toggle') === 'dropdown') {
////            $editor.removeAttr('contenteditable');
////            setTimeout(function () {
////                $editor.attr('contenteditable', true);
////            }, 10);
////            return;
////        }
////        var text = trim(Emoji.val($editor.get(0)));
////        if (!text) {
////            setTimeout(function () {
////                $editor.focus();
////            }, 10);
////            return;
////        }
////        var action = $buttonSend.attr('data-action');
////        var $msgHistory = $('#messages-history');
////        var from = $msgHistory.data('from');
////        var to = $msgHistory.data('to');
////        var lastMsgId = $msgHistory.data('last-msg-id');
////        if (from <= 0) {
////            return;
////        }
////        $editor.removeAttr('contenteditable').attr('readonly', true);
////        $buttonSend.attr('disabled', true);
////        $loadingMsg.fadeIn();
////        $.post(action, {from: from, to: to, text: text, lastMsgId: lastMsgId}).done(ajaxDone).fail(ajaxFail);
////        function ajaxDone(data) {
////            var success = $.isPlainObject(data) && data.messages;
////            $loadingMsg.hide();
////            if (success) {
////                $editor.empty();
////                updateMsgHistory(data.messages);
////            } else {
////                if (data === false) {
////                    $errorMsg.text($errorMsg.data('msg-deny')).fadeIn();
////                } else if ($.type(data) === 'string' && data && data.length < 100) {
////                    $errorMsg.text(data).fadeIn();
////                } else {
////                    $errorMsg.text($errorMsg.data('msg-error')).fadeIn();
////                }
////            }
////            setTimeout(function () {
////                $editor.removeAttr('readonly').attr('contenteditable', true).focus();
////                $buttonSend.removeAttr('disabled');
////            }, 10);
////        }
////        function ajaxFail(jqXHR) {
////            $loadingMsg.hide();
////            if (!ajaxIsRedirect(jqXHR)) {
////                $errorMsg.text($errorMsg.data('msg-error')).fadeIn();
////            }
////            setTimeout(function () {
////                $editor.removeAttr('readonly').attr('contenteditable', true).focus();
////                $buttonSend.removeAttr('disabled');
////            }, 10);
////        }
////    };
//
//    /*    modalPage.show = function (text) {
//            if (modalPage.isVisible) {
//                alert('Error in modalPage::show');
//                return;
//            }
//            var $page = $('#modal-page .page');
//            if (text.substring(0, 5) === 'text-') {
//                $page.text($page.data(text));
//            } else {
//                $page.text(text);
//            }
//    //        $('body > div.container').addClass('disable-scroll');
//            //        document.body.style.overflow = 'hidden';
//            $('#modal-page').show();
//            modalPage.isVisible = true;
//    //text-loading
//
//    //        flatPage.init();
//    //
//    //        flatPage.isLoadingPage = true;
//    //        var $page = $('<div class="lading-page-overlay"><div class="container"><div class="page">Loading...</div></div></div>');
//    //        var $page = $('<div class="lading-page-overlay"><div class="page">Loading...</div></div>');
//    //        $('body').append($page);
//        };
//
//        modalPage.hide = function () {
//            if (!modalPage.isVisible) {
//                alert('Error in modalPage::hide');
//                return;
//            }
//            $('#modal-page').hide();
//            modalPage.isVisible = false;
//        };
//
//        flatPage.init = function () {
//            if (flatPage.data) {
//                return;
//            }
//            flatPage.data = {currentPage: undefined, isLoadingPage: false};
//    //        var flatHeader;
//    //        flatHeader = '<li class="nav-item"><a class="nav-link active" href="#" tabindex="-1"><img src="/static/images/logo.png" width="27" height="20" alt="" border="0"></a></li>';
//    //        flatHeader += '<li class="nav-item">И</li>';
//    //        flatHeader += '<li class="nav-item">Заголовок окна</li>';
//    //        flatHeader += '<li class="nav-item ml-auto">М</li>';
//    //        flatHeader = '<ul class="container nav-top navbar-nav d-flex flex-row flex-wrap bg-primary" role="navigation">' + flatHeader + '</ul>';
//    //        flatHeader = '<header id="top-menu" class="navbar-inverse">' + flatHeader + '</header>';
//    //        var $page = $('<div class="flat-page-overlay"><div class="container">' + flatHeader + '</div></div>');
//    //        flatPage.$root = flatPage.$currentLevel = $('<div class="flat-page-root"></div>');
//    //        $('body').append(flatPage.$root);
//        };
//
//        function createMsgHistoryItem(msg) {
//            var $e = $('<div class="message-wrap d-flex flex-row"><div class="message"><div class="text"></div></div><div class="time"></div></div>');
//            var $text = $e.find('.text').text(msg.text);
//            $text.html($text.html().replace(new RegExp(String.fromCharCode(10) ,'g'), '<br>'));
//            $e.find('.time').text(msg.date);
//            $e.addClass('message-' + msg.direct);
//            $e.data('id', msg.id);
//            return $e;
//        }
//
//        function updateMsgHistory(content) {
//            var $msgHistory = $('#messages-history');
//            var oldLastMsgId = $msgHistory.data('last-msg-id');
//            $msgHistory.data('last-msg-id', content.lastMsgId);
//            var list = content.list;
//            if (!list.length) {
//                return;
//            }
//            if (oldLastMsgId <= 0) {
//                $msgHistory.empty();
//            }
//            $.each(list.reverse(), function(i, v) {
//                $msgHistory.prepend(createMsgHistoryItem(v));
//            });
//        }
//    */
//
////    var Emoji = {
////        getCode: function (obj) {
////            var code = false;
////            if (obj.className === 'emoji_css') {
////                code = obj.getAttribute('emoji');
////            } else if (obj.className.indexOf('emoji') !== -1) {
////                var m = obj.src && obj.src.match(/\/([a-zA-Z0-9]+)(_2x)?.png/);
////                code = m ? m[1] : obj.getAttribute('emoji');
////            }
////            return code;
////        },
////        codeToChr: function (code) {
////            var len = Math.round(code.length / 4);
////            var chr = '';
////            var i = 0;
////            while (len--) {
////                chr += String.fromCharCode(parseInt(code.substr(i, 4), 16));
////                i += 4;
////            }
////            return chr;
////        },
////        val: function (cont, opts) {
////            var el = cont.firstChild;
////            var v = '';
////            var contTag = new RegExp('^(DIV|P|LI|OL|TR|TD|BLOCKQUOTE)$');
////            while (el) {
////                switch (el.nodeType) {
////                    case 3:
////                        var str = el.data.replace(/^\n|\n$/g, ' ').replace(/[\n\xa0]/g, ' ').replace(/[ ]+/g, ' ');
////                        v += str;
////                        break;
////                    case 1:
////                        var str = Emoji.val(el);
////                        if (el.tagName && el.tagName.match(contTag) && str) {
////                            if (str.substr(-1) !== '\n') {
////                                str += '\n';
////                            }
////                            var prev = el.previousSibling;
////                            while (prev && prev.nodeType === 3 && trim(prev.nodeValue) === '') {
////                                prev = prev.previousSibling;
////                            }
////                            if (prev && !(prev.tagName && (prev.tagName.match(contTag) || prev.tagName === 'BR'))) {
////                                str = '\n' + str;
////                            }
////                        } else if (el.tagName === 'IMG') {
////                            var code = Emoji.getCode(el);
////                            if (code) {
////                                str += Emoji.codeToChr(code);
////                            }
////                        } else if (el.tagName === 'BR') {
////                            str += '\n';
////                        }
////                        v += str;
////                        break;
////                }
////                el = el.nextSibling;
////            }
////            return v;
////        }
////    };
//
//
//
//
//    //            $('#genderModalFormContainer').modal('show');
//    //            return;
//
//    //$('.simple-overlay').click(function (){
//    //    alert();
//    //    $('.simple-popup, .simple-overlay').css({'opacity': 0, 'visibility': 'hidden'});
//    //});
//    //$('.simple-popup, .simple-overlay').css({'opacity': 1, 'visibility': 'visible'});
//    //$('#genderModalFormContainer').css({'opacity': 1, 'visibility': 'visible'});
//    //$($buttonSend).dropdown();
//    //$($buttonSend).dropdown('show');
//
//}
