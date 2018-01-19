<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.js"></script>
</head>
<body id="thebody" style="background-color:#bbb; margin: 0;">
    <span id="daHead" style="background-color:#bbb; margin: 0;"></span>
    <span id="daBod" style="background-color:#bbb; margin: 0;"></span>
    <span id="daTimes" style="background-color:#bbb; margin: 0;"></span>
    <span id="daFoot" style="background-color:#bbb; margin: 0;"></span>

<!--<canvas id="canvas" width="400" height="400" style="background-color:#bbb"></canvas>-->

<script>
var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
var count = 5;
var clockSize = w/count;
var initTime = new Date();
var zoneoffset = initTime.getTimezoneOffset();
var zoneH = Math.floor(Math.abs(zoneoffset/60));
var zoneM = Math.floor(zoneoffset%60);
var zoneSign = zoneoffset < 0;
var zonePlus = '';
var zoneMinus = 'selected="selected"';
if(zoneoffset < 0){
    zonePlus = 'selected="selected"';
    zoneMinus = '';
}

for(i = 0; i < count; i++){
    var labelString ="label "+i;
    if(i == 0){labelString = "Current Location";}
    $("#daHead").append('<span contenteditable="true" style="background-color:#ddd; font-size:28px; width:'+clockSize+'px; display:inline-block; text-align:center;">'+labelString+'</span>');
    $("#daBod").append('<canvas id="canvas'+i+'" width="'+clockSize+'" height="'+clockSize+'" style="background-color:#bbb"></canvas>');
    $("#daTimes").append('<span id="time'+i+'" style="background-color:#FFFFFF; font-size:28px; width:'+clockSize+'px; display:inline-block; text-align:center;"></span>');
    $("#daFoot").append('<div  style="background-color:#ddd; font-size:18px; width:'+clockSize+'px; display:inline-block; text-align:center;">UTC <select id="sign'+i+'">  <option value="1" '+zonePlus+'>+</option>  <option value="0" '+zoneMinus+'>-</option> </select>h: <input id="hour'+i+'" value="'+zoneH+'" style="width:3em" type="number" maxlength="2" size="2" min="0" max="12"> m: <input id="minute'+i+'" value="'+zoneM+'" style="width:3em" type="number" maxlength="2" size="2" min="0" max="59"> </div>');

    setTimeout(initClock(i), 1000);
}
function initClock(id){
    var canvas = document.getElementById("canvas"+id);
    var ctx = canvas.getContext("2d");
    var radius = canvas.height / 2;
    ctx.translate(radius, radius);
    radius = radius * 0.90
    //drawClock(ctx, radius);
    setInterval(function(){drawClock(ctx, radius, id);}, 1000);
}
    
function drawClock(ctx, radius, id) {
    drawFace(ctx, radius);
    drawNumbers(ctx, radius);
    drawTime(ctx, radius, id);
}

function drawFace(ctx, radius) {
    var grad;

    ctx.beginPath();
    ctx.arc(0, 0, radius, 0, 2*Math.PI);
    ctx.fillStyle = 'white';
    ctx.fill();

    grad = ctx.createRadialGradient(0,0,radius*0.95, 0,0,radius*1.05);
    grad.addColorStop(0, '#653');
    grad.addColorStop(0.5, '#ffb');
    grad.addColorStop(1, '#653');
    ctx.strokeStyle = grad;
    ctx.lineWidth = radius*0.1;
    ctx.stroke();

    ctx.beginPath();
    ctx.arc(0, 0, radius*0.1, 0, 2*Math.PI);
    ctx.fillStyle = '#002';
    ctx.fill();
}

function drawNumbers(ctx, radius) {
    var ang;
    var num;
    ctx.font = radius*0.15 + "px arial";
    ctx.textBaseline="middle";
    ctx.textAlign="center";
    for(num= 1; num < 13; num++){
        ang = num * Math.PI / 6;
        ctx.rotate(ang);
        ctx.translate(0, -radius*0.85);
        ctx.rotate(-ang);
        ctx.fillText(num.toString(), 0, 0);
        ctx.rotate(ang);
        ctx.translate(0, radius*0.85);
        ctx.rotate(-ang);
    }
}
function drawTime(ctx, radius, id){
    var now = new Date();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    console.log($("#hour"+id).val());
    console.log(zoneH);
    if($("#sign"+id).val() == 1){
        hour = hour + zoneH + parseInt($("#hour"+id).val());
        hour = hour + ($("#minute"+id).val() - zoneM);
    }else{
        hour = hour + zoneH - $("#hour"+id).val();
        minute = minute - ($("#minute"+id).val() - zoneM);
    }
    if(minute < 0){
        hour--;
        minute += 60;
    }
    if(hour < 0){
        hour += 24;
    }
    if(hour >= 24){
        hour -= 24;
    }
    $("#time"+id).text(("0"+hour).slice(-2)+" : "+("0"+minute).slice(-2)+" : "+("0"+second).slice(-2));
    //hour
    ctx.strokeStyle = '#002';
    hour=hour%12;
    hour=(hour*Math.PI/6)+(minute*Math.PI/(6*60))+(second*Math.PI/(360*60));
    drawHand(ctx, hour, radius*0.5, radius*0.07);
    //minute
    ctx.strokeStyle = '#002';
    minute=(minute*Math.PI/30)+(second*Math.PI/(30*60));
    drawHand(ctx, minute, radius*0.8, radius*0.07);
    // second
    ctx.strokeStyle = '#b30';
    second=(second*Math.PI/30);
    drawHand(ctx, second, radius*0.9, radius*0.02);
}

function drawHand(ctx, pos, length, width) {
    ctx.beginPath();
    ctx.lineWidth = width;
    ctx.lineCap = "round";
    ctx.moveTo(0,0);
    ctx.rotate(pos);
    ctx.lineTo(0, -length);
    ctx.stroke();
    ctx.rotate(-pos);
}

</script>

</body>
</html>