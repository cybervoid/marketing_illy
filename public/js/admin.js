/**
 * Created by rafag on 3/21/15.
 */

var App = App || {};

(function ($) {
    "use strict";

    App.newHire = App.newHire || {};


    $(document).ready(function () {

        $('#users').submit(function () {
            alert('feature under development');
            return false;

            //alert('This option is under development');
            if($('#user_new_pass').val()!=$('#user_confirm_pass').val()){
                $('#user_frm_error').html("New passwords doesn't match.");
                return false;
            }

        });

        $('#admin').submit(function () {

            alert('feature under development');
            return false;

            if($('#adm_new_pass').val()!=$('#adm_confirm_pass').val()){
                $('#adm_frm_error').html("New passwords doesn't match.");
                return false;
            }
        });

        $('#upload_frm').submit(function () {
            alert('feature under development');
            return false;

        });


    });

}(jQuery));