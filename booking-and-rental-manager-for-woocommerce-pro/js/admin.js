(function ($) {
    "use strict";

    $(document).on('click', '#mp_event_add_new_form', function () {
        let empty_form_tr = $('.mp_event_custom_form_hidden table tr').clone(true);
        empty_form_tr.insertAfter('.mp_event_custom_form_table tr:last-child');
    });

    $(document).on('click', '.mp_event_remove_this_row', function () {
        $(this).parents('tr').remove();
    });

    $(document).on('change', '.mp_event_custom_form_table [name="mep_fbc_filed_type[]"]', function () {
        let value=$(this).val();
        if(value==='select' || value==='radio' || value==='checkbox'){
            $(this).parents('label').siblings('.mp_event_drop_list').slideDown(250);
        }else{
            $(this).parents('label').siblings('.mp_event_drop_list').slideUp(250);
        }

    });



    jQuery(document).ready(function ($) {
        var reg_form = jQuery('#mep_event_reg_form_list').val(); 
        if(reg_form > 0){    
        jQuery('.mp_tab_itemss').hide();
        }else if(reg_form == 'custom_form'){
            jQuery('.mp_tab_itemss').show();
        }
    });

    $(document).on('change', '#mep_event_reg_form_list', function () {
        let reg_form = jQuery(this).val();  
        if(reg_form > 0 || reg_form == '' ){    
                jQuery('.mp_tab_itemss').hide(1000);
            }else if(reg_form == 'custom_form'){
                jQuery('.mp_tab_itemss').show(1000);
            }

    });

    $(document).on('click', '.booking_calenter_table [data-date]', function (e) {
        e.preventDefault();
        let $this = $(this);

        let date = $this.data('date');

        get_calender(date);
        return false;
    });
    $(document).on('click', '#event_booking_filter_btn', function (e) {

        e.preventDefault();
        let date = $('.booking_calenter_table').find('.active').data('date');

        get_calender(date);
        return false;
    });
    function get_calender(date){
        let parent = $('.booking_calender_area');
        let event_id = $('#mep_event_id').val();
        
        if(date) {
            $.ajax({
                type: 'POST',
                url: rbmw_ajax_url,
                data: {"action": "get_rbmw_pro_booking_in_calender", "date": date,"event_id":event_id},
                beforeSend: function () {
                    //dLoader(parent);
                    //parent.html('<h5 class="mep-processing">Please wait! calender is Loading..</h5>');
                    parent.html('');
                    $('.rbfw-bc-page-ph').show();
                },
                success: function (data) {
                    $('.rbfw-bc-page-ph').hide();
                    parent.html(data);
                    parent.append('<div class="rbfw_bc_details_result" id="rbfw_bc_details_result"></div>');
                    //dLoaderRemove(parent);
                },
                error: function (response) {
                    console.log(response);
                }
            });
        }
    }

    $(document).on('click', '.mp_date_exit_event_count[data-cuttent-date]', function (e) {
        e.preventDefault();
        let parent = $(this).closest('td');
        if(parent.find('.order_list_area').length<1){
            let date =$(this).data('cuttent-date');


            let currnt_pant=$(this).closest('.allCenter');
            let event_id = $('#mep_event_id').val();
            if(date) {
                $.ajax({
                    type: 'POST',
                    url: rbmw_ajax_url,
                    data: {
                        "action": "get_rbmw_pro_booking_in_calender_list",
                        "date": date,
                        "event_id":event_id
                    },
                    beforeSend: function () {
                        dLoader(currnt_pant);
                        $('.rbfw_bc_details_result').empty();
                    },
                    success: function (data) {
                        $('.rbfw_bc_details_result').html(data);
                        dLoaderRemove(currnt_pant);
                    },
                    complete:function(data) {
                        $('html, body').animate({
                            scrollTop: $("#rbfw_bc_details_result").offset().top
                        }, 2000);   
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            }
        }else{
            if(parent.find('.order_list_area').is(':visible')){
                parent.find('.order_list_area').slideUp('fast');
            }else{
                parent.find('.order_list_area').slideDown('fast');
            }
        }
    });

    $(document).on('click', 'span.close_order_list_area', function (e) {
        e.preventDefault();
        $('.order_list_area').slideUp('fast');
    });

    $(document).on('click','button',function(){
        let attendee_id = $(this).data('attendee_id');
        let this_text_data = $(this).data('text');
        let this_text = $(this).text();
        $(this).data('text',this_text);
        $(this).text(this_text_data);
        $(".more-info-"+attendee_id).toggle();
    });


}(jQuery));