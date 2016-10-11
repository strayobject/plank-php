$( document ).ready(function() {
    $.getJSON('/v1/boards', function(data) {
        var items = [];
        $.each(data.boards, function(key, board) {
            console.log(board);
            items.push('<li id="'+board.id+'"><a href="/boards/'+board.url+'">' + board.name + "</a></li>");
        });

        $('<ul/>', {
            'class': 'my-new-list',
            html: items.join('')
        }).appendTo('div#boardList');
    });

});
