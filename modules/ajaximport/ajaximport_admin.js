function Counter() {
    this.count = 0;
    this.total = 0;
    this.stepCount = 0;
    this.step = 0;
}

Counter.prototype = {
    setTotal: function(total) {
        this.total = total;
    },
    upCount: function(count) {
        this.count += count;
    },
    setStep: function(step) {
        this.step = step;
    },
    setStepCount: function(stepCount) {
        this.stepCount = stepCount;
    }
};

$(document).ready(function(){
    $(document).on('submit', 'form', function(e) {
        var formData = new FormData(),
            omega = $('#omega')[0].files[0],
            continental = $('#continental')[0].files[0],
            countObject = new Counter();

        if(omega || continental)
            e.preventDefault();
        else
            return true;

        if(typeof omega !== 'undefined')
            formData.append('omega', omega);
        if(typeof continental !== 'undefined')
            formData.append('continental', continental);

        importFile(formData, countObject);
    });


    function importFile(formData, object) {
        console.log('run');
        formData.append('step_count', object.stepCount);
        formData.append('step', object.step);

        $.ajax({
            url : '/modules/ajaximport/ajaximport-ajax.php',
            type : 'POST',
            data : formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success : function(data) {
                if(data.answer) {
                    object.setTotal(data.total);
                    object.upCount(data.count);
                    object.setStep(data.step);
                    object.setStepCount(data.step_count);

                    $('#progress').prepend(data.answer);
                    $('#progressbar').attr('max', object.total);
                    $('#progressbar').val(object.count);

                    if(object.count < object.total)
                        importFile(formData, object);
                }
            }
        });

        return object;
    }
});