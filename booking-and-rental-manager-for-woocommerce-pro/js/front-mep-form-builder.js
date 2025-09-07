(function($) {

    var p_f_height;
    var is_collapse = true;
    var show_row = 2;

    var me = function ( element, row, is_collapse ) {

        var p, c, c_height, init_height;

        if ( is_collapse ) {
            p = $(element);
            p_f_height = p.height();
            c = p.children();

            if (c.length > 0) {
                c_height = c.height();
                row = parseInt(row);

                // Is Mobile
                if ($(window).width() <= 425) {
                    row = row + 3;
                }

                init_height = row * c_height;
                p.height(init_height);
            }
        }
    }

    var expand_section = function(e) {
        var ele = $('.mep-event-attendee--inner');
        is_collapse = !is_collapse;

        if(is_collapse) {
            me('.mep-event-attendee--inner', show_row, is_collapse);
            $(e).text('See all');
        } else {
            ele.height(p_f_height);
            $(e).text('See less');
        }
    }

    $('#attendee--see-all').on('click', function() {
        expand_section($(this));
    });

    // init
    me('.mep-event-attendee--inner', show_row, is_collapse);


    $(document).on('click', '[data-radio]', function () {
        let target = $(this).closest('label');
        let value = $(this).attr('data-radio');
        target.find('.customRadio').removeClass('active');
        $(this).addClass('active');
        target.find('input').val(value).trigger('change');
    });
    $(document).on('click', '#mage_event_submit .customCheckboxLabel', function () {
        let parent = $(this).closest('.customCheckBoxArea');
        let value = '';
        let separator = ',';
        parent.find(' input[type="checkbox"]').each(function () {
            if ($(this).is(":checked")) {
                let currentValue = $(this).attr('data-checked');
                value = value + (value ? separator : '') + currentValue;
            }
        }).promise().done(function () {
            parent.find('input[type="text"]').val(value);
        });
    });



})(jQuery)