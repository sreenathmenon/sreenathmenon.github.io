;(function(global, $){
    //es5 strict mode
    "use strict";

    var Updater = global.Updater = global.Updater || {};
    Updater.queries = [];

    // ----------------------------------------------------------------------

    Updater.init = function(){

        Updater.Wrap = $('#updater');

        Updater.uploadInit();
        Updater.processInit();
        Updater.settingsInit();

        Updater.Wrap.delegate('.js-test_settings', 'click', testSettings);
        Updater.Wrap.delegate('.js-show_error', 'click', showError);

        // Send a test AJAX request
        //Updater.TestAJAX(Updater.MCP_AJAX_URL);
        Updater.TestAJAX(Updater.ACT_URL);

        cleanTempDirs();
    };

    // ----------------------------------------------------------------------

    Updater.generateRandomString = function() {
        return Math.random().toString(36).substring(2);
    };

    // ----------------------------------------------------------------------

    Updater.getXID = function() {
        if (!Updater.XID) {
            return EE.XID;
        } else {
            return Updater.XID;
        }
    };

    // ----------------------------------------------------------------------

    Updater.setXID = function(xhr) {
        var xid = xhr.getResponseHeader('X-Updater-XID');
        if (!xid) xid = null;
        Updater.XID = xid;
    };

    // ----------------------------------------------------------------------

    Updater.TestAJAX = function(action_url){
        var test_ajax = $('#test_ajax_error');

        $.ajax({url:action_url+'&task=test_ajax_call',
            dataType: 'json', type: 'POST',
            data: {XID: Updater.getXID(), auth_key:Updater.AUTH_KEY},
            error: function(xhr, textStatus, errorThrown){
                Updater.setXID(xhr);
                test_ajax.show();
                test_ajax.find('a.url').attr('href', action_url).html(action_url);
                test_ajax.find('.error textarea').html( global.btoa(xhr.responseText) );

                if (xhr.status === 0) {
                    test_ajax.find('.error .inner').html('<strong>Response Code:</strong> ' + xhr.status + '&nbsp;&nbsp;&nbsp;<strong>Response Text</strong>: (Probably Cross-Domain AJAX Error)');
                }
                else if (xhr.status >= 200) {
                    test_ajax.find('.error .inner').html('<strong>Response Code:</strong> ' + xhr.status + '&nbsp;&nbsp;&nbsp;<strong>Response Text</strong>: ' + xhr.statusText);
                }
            },
            success: function(rData, textStatus, xhr){
                Updater.setXID(xhr);
                if (!rData) test_ajax.show();
            }
        });

    };

    // ----------------------------------------------------------------------

    function cleanTempDirs(){
        $.ajax({
            url: Updater.SITE_URL+'#clean_temp_dirs',
            type: 'POST',
            data: {XID: Updater.getXID(), auth_key:Updater.AUTH_KEY, ACT: Updater.ACT_ID, task:'clean_temp_dirs'},
            error: function(xhr) {
                Updater.setXID(xhr);
            },
            success: function(rdata, textStatus, xhr) {
                Updater.setXID(xhr);
            }
        });
    }

    // ----------------------------------------------------------------------

    function testSettings(e){
        e.preventDefault();
        var modal = $('#test_transfer_method');

        modal.css({width:'800px', 'margin-left': function () {
                return -($(this).width() / 2);
        }}).modal().find('.loading').show();
        modal.find('.wrapper').empty();

        $.ajax({
            url: Updater.MCP_AJAX_URL,
            type: 'POST',
            data: {XID: Updater.getXID(), auth_key:Updater.AUTH_KEY, ACT: Updater.ACT_ID, task:'test_transfer_method'},
            success: function(rdata, textStatus, xhr) {
                Updater.setXID(xhr);
                modal.find('.loading').hide();
                modal.find('.wrapper').html(rdata);
            },
            error: function(xhr) {
                Updater.setXID(xhr);
            }
        });
    }

    // ----------------------------------------------------------------------

    function showError(e){
        e.preventDefault();
        var error_log = $('#error_log');
        var html = global.atob( $(e.target).closest('.error').find('textarea').val() ) ;

        error_log.slideDown();
        error_log.find('body').empty();

        $('html, body').stop().animate({
            scrollTop: error_log.offset().top
        }, 1000);

        $('<iframe id="updater_error_iframe" style="width:100%;height:300px"/>').load(function(){
            $('#updater_error_iframe').contents().find('body').append(html);
        }).appendTo(error_log.find('.body'));
    }

    // ----------------------------------------------------------------------

}(window, jQuery));

// ----------------------------------------------------------------------

$(document).ready(function() {
    Updater.init();
});
