<script>
    var fnToaStr = function (string, type, opt) {
        var headerTxt = '';
        if (opt) {
            headerTxt = opt.header;
            toastr.options = {"closeButton": opt.closeButton, "debug": opt.debug, "newestOnTop": opt.newestOnTop, "progressBar": opt.progressBar, "positionClass": opt.positionClass, "preventDuplicates": opt.preventDuplicates, "onclick": opt.onclick, "showDuration": opt.showDuration, "hideDuration": opt.hideDuration, "timeOut": opt.timeOut, "extendedTimeOut": opt.extendedTimeOut, "showEasing": opt.showEasing, "hideEasing": opt.hideEasing, "showMethod": opt.showMethod, "hideMethod": opt.hideMethod};
        }
        switch (type) {
            case 'info':
                toastr.info(string, headerTxt);
                break;
            case 'success':
                toastr.success(string, headerTxt);
                break;
            case 'error':
                toastr.error(string, headerTxt);
                break;
        }
    };

    var fnSetSleep = function (ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    };

    var str_slug = function (string) {
        var str = string.replace(/\+/g, '');
        str = str.replace(/:/g, '');
        str = str.replace(/,/g, '');
        str = str.replace(/\./g, '');
        str = str.replace(/&/g, '');
        str = str.replace(/\|/g, '');
        str = str.replace(/ /g, '-');
        str = str.replace(/\--/g, '-');
        str = str.replace(/\---/g, '-');
        return str.toLowerCase();
    };

    var fnSetPaginationInfo = function (el, data) {
        if (el) {
            for (var i = 0; i < Object.keys(data).length; i++) {
                var key = Object.keys(data)[i];
                $(el).attr(key, data[key]);
            }
        }
    };

    var fnGetDateNow = function (format) {
        if (!format)
            format = 'd-m-Y h:i:s';
        return dateFormat(new Date(), format);
    };

    var fnAjaxSend = function (formdata, uri, type, header, async) {
        var result = null;
        if (formdata) {
            if (async) {
                return $.ajax({
                    url: uri,
                    type: type,
                    dataType: 'json',
                    data: formdata,
                    headers: (header) ? header : '',
                    async: true
                });
            } else {
                return $.ajax({
                    url: uri,
                    type: type,
                    dataType: 'json',
                    data: formdata,
                    headers: (header) ? header : '',
                    async: false
                });
            }
        } else {
            return $.ajax({
                url: uri,
                type: type,
                dataType: 'json',
                headers: (header) ? header : '',
                async: false
            });
        }
        return result;
    };

    var loadingImg = function (el, act, opt) {
        if (!el)
            el = 'img-loading';

        if (!opt)
            opt = 'circle-loading';
        var animation = bodymovin.loadAnimation({
            container: document.getElementById(el), // Required
            path: _path_json + '/lottie/' + opt + '.json', // Required
            renderer: 'svg', // Required
            loop: true, // Optional
            autoplay: true, // Optional
            name: "Loading cuy... sabar ye", // Name for future reference. Optional.
        });

        if (act === 'play') {
            $('#' + el).show();
            animation.play();
        } else if (act === 'destroy') {
            animation.destroy();
            $('#' + el).hide();
        }
    };

    var capitalize = function (s) {
        return s[0].toUpperCase() + s.slice(1);
    };

    var playLoadingAnimation = function (el) {
        var element = $(el);
        lottie.loadAnimation({
            container: element, // the dom element that will contain the animation
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: _path_json + '/lottie/circle-loading.json' // the path to the animation json
        });
    };

    var loadingImg = function (el, act, opt) {
        if (!el)
            el = 'loading';

        if (!opt)
            opt = 'Amani_Mgeni/lf30_H8JIIY';

        var animation = bodymovin.loadAnimation({
            container: document.getElementById(el), // Required
            path: _config_base_url + '/lottie/' + opt + '.json', // Required
            renderer: 'svg', // Required
            loop: true, // Optional
            autoplay: true, // Optional
            name: "Loading cuy... sabar ye", // Name for future reference. Optional.
            rendererSettings: {
                preserveAspectRatio: 'xMinYMin slice', // Supports the same options as the svg element's preserveAspectRatio property
                clearCanvas: false,
                progressiveLoad: true, // Boolean, only svg renderer, loads dom elements when needed. Might speed up initialization for large number of elements.
                hideOnTransparent: false, //Boolean, only svg renderer, hides elements when opacity reaches 0 (defaults to true)
            }
        });

        if (act === 'play') {
            $('#loading-lottie').show();
            $('#' + el).show();
            animation.play();
        } else if (act === 'destroy') {
            $('#loading-lottie').hide();
            animation.destroy();
            $('#' + el).hide();
        }
    };

    var GlobalAjax = function () {
        return {
            //main function to initiate the module
            init: function () {
                fnToaStr('Global js successfully load', 'success', {timeOut: 2000});
                playLoadingAnimation('.loading');
                $(document).scroll(function () {
                    if ($(document).scrollTop() > 200) {
                        $('.navbar').css('opacity', '0.7');
                    } else {
                        $('.navbar').css('opacity', '1');
                    }
                });
                
            }
        };

    }();
    jQuery(document).ready(function () {
        GlobalAjax.init();
    });



</script>