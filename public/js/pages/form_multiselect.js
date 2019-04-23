
$(function() {
    var orderCount = 0;

    // Initialize
    $('.multiselect-order-options').multiselect({
        buttonText: function(options) {
            if (options.length == 0) {
                return 'None selected';
            }
            else if (options.length > 3) {
                return options.length + ' selected';
            }
            else {
                var selected = [];
                options.each(function() {
                    selected.push([$(this).text(), $(this).data('order')]);
                });

                selected.sort(function(a, b) {
                    return a[1] - b[1];
                });

                var text = '';
                for (var i = 0; i < selected.length; i++) {
                    text += selected[i][0] + ', ';
                }

                return text.substr(0, text.length -2);
            }
        },

        onChange: function(option, checked) {
            if (checked) {
                orderCount++;
                $(option).data('order', orderCount);
            }
            else {
                $(option).data('order', '');
            }
        }
    });

    // Order selected options on button click
    $('.multiselect-order-options-button').on('click', function() {
        var selected = [];
        $('.multiselect-order-options option:selected').each(function() {
            selected.push([$(this).val(), $(this).data('order')]);
        });

        selected.sort(function(a, b) {
            return a[1] - b[1];
        });

        var text = '';
        for (var i = 0; i < selected.length; i++) {
            text += selected[i][0] + ', ';
        }
        text = text.substring(0, text.length - 2);

        alert(text);
    });


    //
    // Reset selections
    //

    // Initialize
    $('.multiselect-reset').multiselect();

    // Reset using reset button
    $('#multiselect-reset-form').on('reset', function() {
        $('.multiselect-reset option:selected').each(function() {
            $(this).prop('selected', false);
        })

        $('.multiselect-reset').multiselect('refresh');
        $.uniform.update();
    });



    // Related plugins
    // ------------------------------

    // Styled checkboxes and radios
    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

});
