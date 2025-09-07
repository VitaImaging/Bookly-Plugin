(function ($) {
    "use strict";

    $(window).on('load', function(){attendee_checkbox_check();});

    $(document).on('click', '[name="mp_form_builder_same_attendee"]', function () {
        attendee_info_copy($(this));
    });
    $(document).on('change', '.dada-info input:visible,.dada-info select:visible,.dada-info textarea:visible', function () {
        let target=$(this).closest('.mep-user-info-sec').find('[name="mp_form_builder_same_attendee"]');
        if(target.length>0 && target.is(":checked")){
            let parent = $(this).closest('.dada-info');
            change_attendee_info($(this),parent);
        }
    });
    $(document).on('click', '#mage_event_submit .qty_inc', function () {
        let target=$(this).closest('tr').next().find('[name="mp_form_builder_same_attendee"]');
        if(target.length>0){
            attendee_info_copy(target);
        }
    });
    $(document).on('change', '#mage_event_submit [name="option_qty[]"]', function () {
        let target=$(this).closest('tr').next().find('[name="mp_form_builder_same_attendee"]');
        if(target.length>0){
            attendee_info_copy(target);
        }else {
            $(this).closest('tr').next().find('.dada-info>div:first-child .mep-user-info-sec h5').append('<label class="mep_same_attendee"><input type="checkbox" name="mp_form_builder_same_attendee" /><span>Same Attendee.</span></label>');
        }
    });

    function attendee_checkbox_check() {
        $('#mage_event_submit').find('.user-info-sec').each(function () {
            if ($(this).find('[name="mp_form_builder_same_attendee"]').length < 1) {
                $(this).find('.dada-info>div:first-child .mep-user-info-sec h5').append('<label><input type="checkbox" name="mp_form_builder_same_attendee" /><span>Same Attendee</span></label>');
            }
        });
    }
    function attendee_info_copy($this){
        if ($this.is(":checked")) {
            $this.closest('tr').find('.dada-info>div:not(:first-child)').slideUp(250);
            let parent = $this.closest('.dada-info');
            let current_parent = $this.closest('.mep-user-info-sec');
            current_parent.find('input:visible , select:visible , textarea:visible').each(function () {
                change_attendee_info($(this),parent);
            });
        } else {
            $this.closest('.user-info-sec').find('.dada-info>div:not(:first-child)').slideDown(250);
        }
    }
    function change_attendee_info($this,parent){
        let input_name = $this.attr('name');
        let value = $this.val();
        parent.find('[name="' + input_name + '"]').each(function () {
            if ($(this).is('select')) {
                $(this).find('option[value='+value+']').attr('selected', true);
            } else {
                $(this).val(value);
            }
        });
    }

}(jQuery));