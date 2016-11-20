$(document).ready(function(){

    var s = Snap("#map-svg");
    var g = s.group();
    var point = s.group();

    //load svg file
    var tux = Snap.load("../media/map/CSIE_1F.svg",  function ( loadedFragment ) {
                                        var tmp = loadedFragment.select("#map").attr();
                                        s.attr("viewBox", loadedFragment.select("#map").attr("viewBox"));
                                        g.append( loadedFragment );
                                } );

    //init the location point
    var location = point.circle(0, 0, 10);
    location.attr({id: "point", fill:"red"});

    //get current mouse position
    $("#map-container").mousemove(function (e) {
        var loc = cursorPoint(e);
        $("#coord").text("x:"+loc.x+", y:"+loc.y);
        location.attr({cx: loc.x, cy: loc.y});
    });

    $("#map-container").mousedown(function (e) {
        var loc = cursorPoint(e);
        var pos = $("#position");
        pos.find("#x").val(loc.x);
        pos.find("#y").val(loc.y);
    });

    // Create an SVGPoint for future math
    var pt = s.node.createSVGPoint();

    // Get point in global SVG space
    function cursorPoint(evt){
        pt.x = evt.clientX; pt.y = evt.clientY;
        return pt.matrixTransform(s.node.getScreenCTM().inverse());
    }

    $(":file").filestyle({
        size: "nr",
        input: false,
        buttonText: "上傳圖片",
        buttonName: "btn-info",
        iconName: "glyphicon glyphicon-camera"
    });

    $("#img_path").change(function() {
        readURL(this, '#img_pathImg');
    });
});

function fieldValidation(field) {
    if ($.trim($(field).val()) == '') {
        $(field + 'Div').addClass('has-error');
        $(field + 'Div').val('');
        return false;
    } else {
        $(field + 'Div').removeClass('has-error');
    }
    return true;
}

function formValidation() {
    if (fieldValidation('#name') && fieldValidation('#introduction') && fieldValidation('#x') && fieldValidation('#y') && fieldValidation('#teacher') && fieldValidation('#email')) {
        return true;
    } else {
        BootstrapDialog.alert('請填寫完整資料');
    }

    return false;
}

function readURL(input, id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $(id).attr('src', e.target.result);
        }
        $(id).css('display', '');
        reader.readAsDataURL(input.files[0]);
    }
}