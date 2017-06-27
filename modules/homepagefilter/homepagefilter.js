$(document).ready(function () {
    $(document).on('change', '.tires-calc-col__input, .tires-calc-col__select', function() {
        var href = '';
        if(!haveEmpty()) {
            href += $('#tire-calc-category-url').val() + '#';
            $('.tires-calc-col__select').each(function() {
                href += '/' + $(this).val();
            });
            $('.tires-calc-col__input:checked').each(function() {
                href += '/' + $(this).val();
            });

            if(href)
                location.href = href;
        }
    })
});

function haveEmpty() {
    var empty = 0;
    $('.tires-calc-col__select').each(function() {
        if(!$(this).val()) {
            empty = 1;
            return false;
        }
    });

    empty = empty || $('.tires-calc-col__input:checked').length != 3;

    return empty;
}