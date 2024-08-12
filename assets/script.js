window.history.pushState({
        'html': $('table.layout').html(),
        'title': document.title
    }, '', window.location.href);

window.addEventListener('popstate', (event) => {
    document.title = event.state.title;
    $('table.layout').html(event.state.html);
});

$(document.body).on('click', 'a.view, a.edit', function(e) {
    e.preventDefault();

    console.log('ajax');

    let _this = $(this);

    let url = _this.attr("href"),
    id = _this.parent('.user').data('id');
    title = 'Мой блог - ' + ( _this.hasClass('view') ? 'просмотр пользователя ' : 'редактирование пользователя ' ) + id;

    $.get( url + '?ajax=1', function(data) {
            $('table.layout').html(data);
            document.title = title;
            window.history.pushState({'html': data, 'title': title},'', url);
        })
        .fail(function(err) {
            console.log(err);
        })
});

$(document.body).on('click', 'a.delete', function(e) {
    e.preventDefault();

    console.log('ajax');

    let _this = $(this);

    let url = _this.attr("href"),
    user = _this.parent('.user');

    $.get( url, function() {
            user.remove();
            window.history.replaceState({'html': $('table.layout').html(), 'title': document.title},'', window.location.href);
        })
        .fail(function(err) {
            console.log(err);
        })
});

$(document.body).on('click', 'a:not(.view):not(.edit):not(.delete)', function(e) {
    e.preventDefault();
    let _this = $(this);
    let url = _this.attr("href");

    console.log('ajax');

    $.get( url + '?ajax=1', function(data) {
            let tmp = $(data),
            title = tmp.find('h1').text();

            tmp.remove();

            $('table.layout').html(data);

            document.title = title;
            window.history.pushState({'html': data, 'title': title},'', url);
        })
        .fail(function(err) {
            console.log(err);
        })
});

$(document.body).on('submit', 'form#update, form#create, #form#login', function(e) {

    e.preventDefault();

    console.log('submit');

    let form = $(this),
    url = $(this).attr('id') == 'login' ? '/' : window.location.href,
    actionUrl = form.attr('action');
    
    $.ajax({
        type: "POST",
        url: actionUrl + '?ajax=1',
        data: form.serialize(),
        success: function(data)
        {
            let tmp = $(data),
            title = tmp.find('h1').text();

            tmp.remove();

            $('table.layout').html(data);

            document.title = title;
            window.history.pushState({'html': data, 'title': title},'', url);
        }
    })
    .fail(function(err){
        console.log(err);
    });
    
});