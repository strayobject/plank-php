$(document).ready(function() {
    $('#submitTask').on('click', function() {
        $.post('/boards/'+boardUrl+'/tasks', $('#addTask').serialize()).done(function(data) {
            console.log('done');
            console.log(data);
            $(':input','#addTask').not(':button, :submit, :reset, :hidden').val('');
        });
    });
});
