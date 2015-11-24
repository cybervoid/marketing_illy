/**
 * Created by rafag on 3/21/15.
 */

var App = App || {};

(function ($) {
    "use strict";

    App.newHire = App.newHire || {};


    $(document).ready(function () {

        $('#username').blur( function(){
            if( $('#username').val().toLowerCase() === 'admin'){
                $('#administrator').prop('checked', true);
            }
        });
    });

}(jQuery));