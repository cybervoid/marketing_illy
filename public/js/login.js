/**
 * Created by rafag on 3/21/15.
 */

var App = App || {};

(function ($) {
    "use strict";

    App.newHire = App.newHire || {};

    riot.mount('login', {} );

    
    var errorMessage = $('meta[name="message"]').prop('content');

    if(errorMessage!= '')
    {
        $('#alert').attr('riot-tag', 'alert');
        riot.mount('alert', {
            'type': 'danger',
            message : errorMessage});
    }

    $(document).ready(function () {

        $('#username').blur( function(){
            if( $('#username').val().toLowerCase() === 'admin'){
                $('#administrator').prop('checked', true);
            }
        });
    });

}(jQuery));