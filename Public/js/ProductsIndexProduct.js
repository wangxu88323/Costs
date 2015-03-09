/**
 * Created by Administrator on 2014-9-30.
 */
$(function () {
    $('#myTab a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    })
});