$(document).ready(function () {
    $(document).on('change', 'select.podbor', function() {
        var href = '';
        if(!haveEmpty()) {
            href += $('#homepagefilter_category').val() + '#';
            $('.homepagefilter_feature').each(function() {
                href += '/' + $(this).val();
            });

            if(href)
                location.href = href;
        }
    })
});

function haveEmpty() {
    var empty = 0;
    $('select.podbor').each(function() {
        if(!$(this).val()) {
            empty = 1;
            return false;
        }
    });

    return empty;
}