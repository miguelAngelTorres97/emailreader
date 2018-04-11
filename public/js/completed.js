$(".complete").click(function(){
    readed = $(this).data('readed');
    args = {
        "email_id" : $(this).data('emailid'),
        "email_readed" : readed
    };
    element = $(this);
    $.ajax({
        data: args,
        url: "emailreader/togglecomplete",
        type:'POST',
        beforeSend: function () {
            text = readed != 1 ? label_uncomplete + ' <i class="material-icons">undo</i> ' :  label_complete + ' <i class="material-icons">done</i>';
            $(element).html(text);
        },
        success: function (data) {
            if(data['status'] === 200) {
                $(element).data('readed', data.msg);
                icon = data['msg'] === 1 ? "done" : "email";
                $(element).closest(".card").find('.seen').html(icon);
            }
        }

    });
});