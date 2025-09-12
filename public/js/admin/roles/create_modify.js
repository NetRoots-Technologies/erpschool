/**
 * Created by mustafa.mughal on 12/7/2017.
 */

//== Class definition
var FormControls = function () {
    //== Private functions

    var baseFunction = function () {

        $( "#validation-form" ).validate({
            // define validation rules
            errorElement: 'span',
            errorClass: 'help-block',
            rules: {
                name: {
                    required: true
                },
                // 'permission[]': {
                //     required: true
                // },
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") == "parent_id") {
                    error.insertAfter($('#parent_id_handler'));
                } else {
                    error.insertAfter(element);
                }
            },
            success: function (label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },
        });
    }

    var selectUnSelectSubGroup = function(selector, group) {
        var permission = group.attr('data-permission');
        var sub_permission = group.attr('data-sub_permission');

        selectUnSelectReset('#', 'sub-allow_' + sub_permission);
        selectUnSelectReset('#', 'sub-noallow_' + sub_permission);

        if(selector == 'selected') {
            // Check if parent checked or not, if not make is checked
            if (!$('.allow_'+permission).is(':checked')) {
                // Reset both buttons
                selectUnSelectReset('#', 'allow_' + permission);
                selectUnSelectReset('#', 'noallow_' + permission);
                // Make Parent Selected
                selectUnSelect('#', 'allow_' + permission, 'noallow_' + permission, true);
            }
            // Make current sub permission checked
            selectUnSelect('#', 'sub-allow_' + sub_permission, 'sub-noallow_' + sub_permission, true);

        } else {
            // Make current sub permission checked
            selectUnSelect('#', 'sub-noallow_' + sub_permission, 'sub-allow_' + sub_permission, false);

            var sub_permissiosn_selected = false;

            $('.sub-allow_' + permission).each(function () {
                var attr = $(this).attr('checked');
                if (typeof attr !== typeof undefined && attr !== false) {
                    sub_permissiosn_selected = true;
                }
            });

            // if(!sub_permissiosn_selected) {
            //     // Reset both buttons
            //     selectUnSelectReset('#', 'allow_' + permission);
            //     selectUnSelectReset('#', 'noallow_' + permission);
            //     // Make Parent De-Selected
            //     selectUnSelect('#', 'noallow_' + permission, 'allow_' + permission, true);
            // }
        }
    }

    var selectUnSelectGroup = function(selector, group) {
        var permission = group.attr('data-permission');

        // Set Sub Members
        selectUnSelectParent('.', selector, permission, 'sub-allow_' + permission, 'sub-noallow_' + permission, true);
        //selectUnSelectParent('.', selector, 'toggle', 'allow', 'noallow', true);
        // Set Group Head
        selectUnSelectParent('.', selector, permission, 'allow_' + permission, 'noallow_' + permission, false);
    }

    var selectUnSelectGlobal = function (selector) {
        selectUnSelectParent('.', selector, 'toggle', 'allow', 'noallow', true);
    }

    var selectUnSelect = function(id_or_class, allow, noallow, checkFields) {
        //console.log('id_or_class + allow : ',id_or_class + allow , ' h1  : ',checkFields);
        $(id_or_class + allow).each(function (ele) {
            $(this).parent().addClass('btn-info');
            $(this).parent().addClass('active');
            //console.log('checkFields :', checkFields)
            if(checkFields) {

                $(this).attr('checked',true);

            }
        });
        $(id_or_class + noallow).each(function (ele) {
            $(this).parent().addClass('btn-default');
        });
    }

    var selectUnSelectReset = function(id_or_class, toogle) {
        $(id_or_class + toogle).each(function (ele) {

            $(this).removeAttr( "checked" )

            $(this).parent().removeClass('active');
            $(this).parent().removeClass('btn-info');
            $(this).parent().removeClass('btn-default');
        });
    }

    var selectUnSelectParent = function(id_or_class, selector, toogle, allow, noallow, applytoggle) {
        // Apply Reset of Button colors
        if(applytoggle) {
            selectUnSelectReset(id_or_class, toogle);
        }
//console.log('selector: ',selector,id_or_class, allow, noallow)
        if(selector == 'selected') {
            selectUnSelect(id_or_class, allow, noallow, true);
        } else {
            selectUnSelect(id_or_class, noallow, allow, false);
        }
    }


    var checkAll = function(obj){

        $('.allow_all').not(this).prop('checked', obj.checked);
        if(obj.checked){
            //console.log('obj checked');
            setElementColor('.label_all');
        }else{
            unsetElementColor('.label_all');
        }

    }
    var checkMyModule = function (obj, selector) {

        $('.sub-'+selector+'').not(this).prop('checked', obj.checked);
        if(obj.checked){
            setElementColor('.id-'+selector+'');

        }else{
            unsetElementColor('.id-'+selector+'');
        }


    }
    var checkMyParent = function (obj, parent_class, sub_class, value) {
        console.log('obj :', obj.checked, parent_class)
        if(! obj.checked){
            unsetElementColor('#id-'+value);
        }else{
            setElementColor('#id-'+value);
        }
       // make parent of this module unchecked
       if($('.'+sub_class+':checked').length == 0){

            $('.'+parent_class+'').prop('checked', false);
           unsetElementColor('.p-id-'+parent_class);
       }else{
           // make parent of this module checked
           $('.'+parent_class+'').prop('checked', true);
           setElementColor('.p-id-'+parent_class);
       }

    }
    var setElementColor = function(selector){

        $(selector).addClass('active');
        $(selector).addClass('btn-info');
        $(selector).removeClass('btn-default');
    }
    var unsetElementColor = function(selector){

        $(selector).removeClass('active');
        $(selector).removeClass('btn-info');
        $(selector).addClass('btn-default');
    }

    return {
        // public functions
        init: function() {
            baseFunction();
        },
        selectUnSelectGlobal: selectUnSelectGlobal,
        selectUnSelectGroup: selectUnSelectGroup,
        selectUnSelectSubGroup: selectUnSelectSubGroup,
        checkAll : checkAll,
        checkMyModule: checkMyModule,
        checkMyParent: checkMyParent,
        unsetElementColor : unsetElementColor,
    };
}();

jQuery(document).ready(function() {
    FormControls.init();
});