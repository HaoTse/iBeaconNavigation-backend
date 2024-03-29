$(document).ready(function() {

    var s = Snap("#map-svg");
    var g = s.group();
    var point = s.group();

    //load svg file
    var tux = Snap.load("../media/map/CSIE_1F.svg", function(loadedFragment) {
        var tmp = loadedFragment.select("#map").attr();
        s.attr("viewBox", loadedFragment.select("#map").attr("viewBox"));
        g.append(loadedFragment);
    });

    //init the location point
    var location = point.circle(0, 0, 3);
    location.attr({
        id: "point",
        fill: "red"
    });

    var location_check = point.circle(0, 0, 0);

    //get current mouse position
    $("#map-container").mousemove(function(e) {
        var loc = cursorPoint(e);
        $("#coord").text("x:" + loc.x + ", y:" + loc.y);
        location.attr({
            cx: loc.x,
            cy: loc.y
        });
    });

    $("#map-container").mousedown(function(e) {
        var loc = cursorPoint(e);
        var pos = $("#position");
        pos.find("#x").val(loc.x);
        pos.find("#y").val(loc.y);

        location_check.attr({
            cx: loc.x,
            cy: loc.y,
            r: 3,
            fill: "red"
        });
    });

    // Create an SVGPoint for future math
    var pt = s.node.createSVGPoint();

    // Get point in global SVG space
    function cursorPoint(evt) {
        pt.x = evt.clientX;
        pt.y = evt.clientY;
        return pt.matrixTransform(s.node.getScreenCTM().inverse());
    }

    var texts = s.group();
    // Use jquery ajax to get point data
    var points = s.group();
    $.ajax({
        url: "detectPointController.php",
        type: "GET",
        dataType: "json",
        data: "action=getPointData",
        complete: function(data) {
            var obj = $.parseJSON(data.responseText);
            $.each(obj, function(i, item) {
                var point_location = points.circle(item.x, item.y, 3);
                point_location.attr({
                    id: item.point_id,
                    fill: "green"
                });
                var name = texts.text(item.x, item.y, item.point_id);
                name.attr({
                    id: "point_id_" + item.point_id
                });
            });
        }
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
    if (fieldValidation('#x') && fieldValidation('#y')) {
        return true;
    } else {
        BootstrapDialog.alert('請填寫完整資料');
    }

    return false;
}
