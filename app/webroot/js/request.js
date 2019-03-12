function reasonOff(obj)
{
    var reason = obj.value;
    if(reason == 'Other'){
        document.getElementById('otherOff').style.display = "block";
    }else{
        document.getElementById('otherOff').style.display = "none";
    }
}
function reasonLeave(obj)
{
    var reason = obj.value;
    if(reason == 'Other'){
        document.getElementById('otherLeave').style.display = "block";
    }else{
        document.getElementById('otherLeave').style.display = "none";
    }
}
$(document).ready(function(){
    //profile
    var count = 1;
    $('body').on('click','.remove', function () {
        console.log('abc');
        $(this).parent().parent().remove();
    });
    $('.more').click(function () {
        count++;
        var formDate = '<div class="form-group row removeDate">' +
            '<label class="col-2 col-form-label">Date</label>' +
            '                        <div class="col-6">' +
            '                            <input class="form-control" name="date['+ count +']" type="date">' +
            '                        </div>' +
            '                        <label class="col-1 col-form-label">In</label>' +
            '                        <div class="col-2">' +
            '                            <select class="form-control" name="in['+ count +']">' +
            '                                <option>ALL</option>' +
            '                                <option>AM</option>' +
            '                                <option>PM</option>' +
            '                            </select>\n' +
            '                        </div>' +
            '<div class="col-1"><a class="remove"><span class="fa fa-remove" style="font-size:24px;color:red"></span></a></div>' +
            '</div>';
        $('#insertDate').append(formDate);
    });
});