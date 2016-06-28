<?php
require( "config.php" );
if( isset( $_POST['reception'] ) ) {
    include( "ajax.php" );
    die;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Reception Survey</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type='text/javascript' src='http://www.bing.com/api/maps/mapcontrol?callback=GetMap'></script>
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<link href='https://fonts.googleapis.com/css?family=Amiri&subset=arabic,latin' rel='stylesheet' type='text/css' />
<style type="text/css">
body, html {
    padding:0;
    margin:0;
    width: 100%;
    height:100%;
}
#map {
    width: 100%;
    height: 100%;
}
.intro {
    padding: 0.5em;
}
p {
    margin: 0 0 0.5em;
    padding: 0;
}
.intro p, .intro label, a.send_results {
    font-family: 'Open Sans', sans-serif;
    font-size: 1em;
    /*font-family: 'Amiri', serif;*/
}

label { cursor: pointer }

.flexbox { border-collapse: collapse; }
.flexbox td { vertical-align: top}
.flexbox_right_top { vertical-align: middle !important }
.intro_send {
    text-align: center;
}
.send_results {
    cursor: pointer;
    margin:0;
    padding:0;
    display: block;
    background-color: #0074D9;
    color: white;
    padding:0.5em;
    border-radius:1em;
    text-decoration: none;
}

.emp { font-weight: bold }

.intro_td_1 { }
.intro_td_3 { }
.intro_td_2 { }
.intro_td_active { background-color:#DDDDDD; opacity: 1 }
.intro_td_inactive { opacity: 0; }
#intro_1 { }
#intro_2 { }
#intro_3 { }
#thanks { opacity: 0; display:none }
</style>
</head>
<body onload="GetMap()">
<table class="flexbox" style="width:100%; height:100%">
    <tr>
        <td>
            <table style="width:100%" dir="ltr">
                <tr>
                    <td colspan="3">
                        <div class="intro">
                            <p class="emp">VOA Persian would like to know about your satellite reception quality.</p>
                            <p>Taking this survey will help improve our broadcasts and programming.  While we would like your surveyed location to be as near you as possible (in your neighborhood), we will not permanently store or share this information with anyone.</p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="intro_td intro_td_1 intro_td_inactive intro_td_active" style="width:40%">
                        <div id="intro_1" class="intro intro_where">
                            <p>Where are you located?</p>
                            <p>Click or tap map to place marker, use map zoom buttons to enlarge.</p>
                        </div>
                    </td>
                    <td class="intro_td intro_td_2 intro_td_inactive" style="width:40%">
                        <div id="intro_2" class="intro intro_cansee">
                            <p>From there, can you watch VOA Persian with a satellite dish?</p>
                            <input id="cansee_y" type="radio" name="cansee" value="y" /><label for="cansee_y">Yes</label>
                            <input id="cansee_n" type="radio" name="cansee" value="n" /><label for="cansee_n">No</label>
                        </div>
                    </td>
                    <td class="intro_td intro_td_3 intro_td_inactive" style="width:20%" class="flexbox_right_top">
                        <div id="intro_3" class="intro intro_send">
                            <a href="#" id="send_results" class="send_results">Click to<br/>Send<br/>Results</a>
                            <div id="thanks">
                                <p>Thank you for participating.</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="height:100%">
        <td>
            <div id="map"></div>
        </td>
    </tr>
</table>

<script type="text/javascript">
var map;
var pin = false;
var reception;
var sending = false;

function createPoint(lat, lng) {
    var center = new Microsoft.Maps.Location(
        lat, lng
    );

    var img =
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAcCAYAAACUJBTQAAA'+
        'AGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyppVFh0WE1MOmNvbS5h'+
        'ZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6c'+
        'mVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIH'+
        'g6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMi1jMDAxIDYzLjEzOTQzOSwgMjAxMC8xMC8'+
        'xMi0wODo0NTozMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3'+
        'LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvb'+
        'iByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLj'+
        'AvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1'+
        'sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJj'+
        'ZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIEVsZW1lbnRzIDExL'+
        'jAgV2luZG93cyIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpGRTlDMEYwRDNENTgxMU'+
        'U2OTExMUNBRjdDMTAyNEU0MyIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpGRTlDMEY'+
        'wRTNENTgxMUU2OTExMUNBRjdDMTAyNEU0MyI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJl'+
        'ZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkZFOUMwRjBCM0Q1ODExRTY5MTExQ0FGN0MxMDI0R'+
        'TQzIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkZFOUMwRjBDM0Q1ODExRTY5MTExQ0'+
        'FGN0MxMDI0RTQzIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXB'+
        'tZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+LXPMpgAAAW9JREFUeNpi/A8EDDQGLCBCU1aW'+
        'ZhZcf/wYYgkIbIgNp7oFAYtXgmkmBjqAUUsGqSWf/v0buj6BOR6eT7g/vKNtjgfnzI9fR'+
        'lPXwFoixcJMZ5/AbByNk0FhCSOojjeVl8Mq2WZjQbRBVUdOYBU//fARxBJcGkGWE2MRyA'+
        'KQYTiLlcsTYhh1N93G5Yr/hCyCWfD60T8G0QRLRnT5dzNmMTBxCongdqKTOSPIAFxBAbP'+
        'g8uGvWC0A+4LpHwPTs+eP8IcFDotgFlxc/5lBt96JEZd2jn/fGJjszPUIxyqaRfA4AIrr'+
        'T3ZhxKf1/fXTDCzXbt1h0CIm+UAsAscRzAJitP379ISB5Re7OMPxKFEGhr8sDH9+fmX48'+
        '/UNAx83E4OykizDV5ZPDCbBsxguPWxlUFetZuBktGR4+v8RAzs7I+ODB20MBkpVDOv7vR'+
        'iM1EwYvnz7wfD27VsGTjZGhucP7zOw/mdkkJaQZmBi/MoAEGAARJKZROb30e8AAAAASUV'+
        'ORK5CYII=';

    // Create custom Pushpin using a base64 image string.
    pin = new Microsoft.Maps.Pushpin(center, {
        icon: img,
        anchor: new Microsoft.Maps.Point(12, 28)
    });

    map.entities.push(pin);

    $(".intro_td_2").animate({opacity: 1}, 500);
    highlight_part(2);
}

function highlight_part(index) {
    $(".intro_td").removeClass("intro_td_active");
    $(".intro_td_" + index).addClass("intro_td_active");
}

function setPoint(lat, lng) {
    if( pin === false ) createPoint( lat, lng );
    if( sending !== false ) return;

    pin.setLocation( new Microsoft.Maps.Location(lat, lng ) );
}

function GetMap() {

    map = new Microsoft.Maps.Map('#map', {
        credentials: '<?php echo MAP_API_KEY ?>',
        center: new Microsoft.Maps.Location(32.143370, 54.338379),
        zoom: 5,
        enableClickableLogo: false,
        showMapTypeSelector: false
    });

    Microsoft.Maps.Events.addHandler(map, 'click', function(e) {
        setPoint( e.location.latitude, e.location.longitude );
    });

    $(".intro_td_1").animate({opacity: 1}, 500);
}

$("#cansee_y, #cansee_n").on("click change", function() {
    if( $("#cansee_y").prop("checked") === true ) {
        reception = "yes";
    } else {
        reception = "no";
    }
    $(".intro_td_3").animate({opacity: 1}, 500);
    highlight_part(3);
});

$("#send_results").on("click", function(e) {
    e.preventDefault();

    if( sending !== false ) return;

    sending = true;
    $("#cansee_y, #cansee_n").attr("disabled", true);

    $.post("?post", {
        reception: reception,
        lat: pin.getLocation().latitude,
        lng: pin.getLocation().longitude,
        zoom: map.getZoom()
    },
    function() {
        $("#cansee_y, #cansee_n").attr("disabled", false);
        sending = false;

        $("#send_results").animate({opacity: 0}, 500, function() {
            $("#send_results").remove();
            $("#thanks").show();
            $("#thanks").animate({ opacity: 1}, 500 );
        });
    });
});

</script>
</body>
</html>
