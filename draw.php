<!DOCTYPE HTML>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>

        *, *:before, *:after {
              -webkit-box-sizing: border-box;
              -moz-box-sizing: border-box;
              box-sizing: border-box;
              position: relative;
              margin: 0;
              padding: 0;
              -webkit-touch-callout: none; /* iOS Safari */
                -webkit-user-select: none; /* Safari */
                 -khtml-user-select: none; /* Konqueror HTML */
                   -moz-user-select: none; /* Firefox */
                    -ms-user-select: none; /* Internet Explorer/Edge */
                        user-select: none; /* Non-prefixed version, currently
                                              supported by Chrome and Opera */
        }
        nav {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 9999;
            background: lightgray;
            width: 50px;
        }

        nav button.basic-color {
            width: 50%;
            float: left;
            cursor: pointer;
        }

        nav button div {
            border: 1px solid rgb(169, 169, 169);
            padding-top: 9px;
            padding-bottom: 9px;
        }

        nav button.tool {
            float: left;
            width: 50%;
            height: 24px;
        }

        nav button.tool span {
            position: absolute;
            top: 2px;
            left: 2px;
        }

        nav button.tool i {
            position: absolute;
            top: 0px;
            left: 0px;
            font-size: 19px;
        }

        nav button.custom-color {
            width: 100%;
        }

        input[type="color"] {
            width: 100%;
            cursor: pointer;
        }

        input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
        }

        input[type="color"]::-webkit-color-swatch {
            /*padding: 10px;*/
        }

        canvas {
            position: absolute;
            top: 0;
            left: 0;
            cursor: none;
        }

        div.cursor {
            position: absolute;
            z-index: 9998;
            background: black;
            border-radius: 100px;
            pointer-events: none;
        }

        div#overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 555;
            display: none;
        }

        div.settings-box {
            top: 10px;
            position: absolute;
            left: 33%;
            background: lightgray;
            width: 33%;
            min-width: 300px;
            display: none;
            z-index: 9998;
            padding: 10px;
        }

        div.settings-box button {
            width: 100%;
        }
    </style>
</head>
<body>
    <nav>
        <button value="draw" class="tool"><div><span class="glyphicon">&#x270f;</span></div></button>
        <button value="erase" class="tool"><div><span class="glyphicon">&#xe221;</span></div></button>
        <button value="background" class="tool"><div><i class="material-icons">&#xe3f5;</i></div></button>
        <button value="cursor" class="tool"><div><i class="material-icons">&#xe8b9;</i></div></button>
        <button value="undo" class="tool" disabled><div><i class="material-icons">&#xe166;</i></div></button>
        <button value="redo" class="tool" disabled><div><i class="material-icons">&#xe15a;</i></div></button>
        <button value="red" class="basic-color"><div style="background: red;"></div></button>
        <button value="blue" class="basic-color"><div style="background: blue;"></div></button>
        <button value="green" class="basic-color"><div style="background: green;"></div></button>
        <button value="orange" class="basic-color"><div style="background: orange;"></div></button>
        <button value="brown" class="basic-color"><div style="background: brown;"></div></button>
        <button value="pink" class="basic-color"><div style="background: pink;"></div></button>
        <button value="purple" class="basic-color"><div style="background: purple;"></div></button>
        <button value="gray" class="basic-color"><div style="background: gray;"></div></button>
        <button value="lightgray" class="basic-color"><div style="background: lightgray;"></div></button>
        <button value="white" class="basic-color"><div style="background: white;"></div></button>
        <button class="custom-color"><input type="color" id="color-picker"></button>
    </nav>
    <canvas></canvas>

    <div class="cursor"></div>

    <div id="overlay">
    </div>

    <div id="background-settings" class="settings-box">
        <h3>Background Settings</h3>
        <button id="squared-background">b</button>
        <button class="ok-btn">Close</button>
    </div>
    <div id="cursor-settings" class="settings-box">
        <h3>Stroke Settings</h3>
        Stroke size: <span id="current-stroke">10px</span>
        <input type="range" id="radius-changer" name="points" min="1" max="20" value="10">
        <button class="ok-btn">Close</button>
    </div>

</body>
</html>

<script>

    var canvas = document.querySelector("canvas");
    var context = canvas.getContext("2d");

    var dragging = false;
    var radius = 10;

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    context.lineWidth = radius * 2;

    // Drawing functions

        var setDraw = function(e) {
            if (dragging) {
                context.lineTo(e.clientX,e.clientY);
                context.stroke();
                context.beginPath();
                context.arc(e.clientX,e.clientY,radius,0,Math.PI*2);
                context.fill();
                context.beginPath();
                context.moveTo(e.clientX,e.clientY);
            }
        }

        var startDraw = function(e) {
            dragging = true;
            setDraw(e);
        }

        var stopDraw = function() {
            dragging = false;
            context.beginPath();
        }

        canvas.addEventListener("mousedown",startDraw);
        canvas.addEventListener("mousemove",setDraw);
        canvas.addEventListener("mouseup",stopDraw);

    // Color picker functions

        var colorPicker = document.getElementById("color-picker");

        var changeColor = function(e) {
            context.strokeStyle = this.value;
            context.fillStyle = this.value;
            cursor.style.background = this.value;
            cursor.style.border = "none";
            hideOverlay();
        }

        var basicColors = document.getElementsByClassName("basic-color");

        for (var i = 0; i < basicColors.length; i++) {
            basicColors[i].addEventListener('click', changeColor, false);
        }

        colorPicker.addEventListener("change",changeColor);


    // Cursor functions
        var cursor = document.querySelector("div.cursor");

        var initCursor = function(e) {
            cursor.style.padding = radius + "px";
            cursor.style.top = e.clientY - radius + "px";
            cursor.style.left = e.clientX - radius + "px";
        }

        canvas.addEventListener("mousemove",initCursor);

    // Tool functions

        var tools = document.getElementsByClassName("tool");

        var fill = false;

        var changeStatus = function(e) {
            hideOverlay();
            if (this.value == "draw") {
                context.strokeStyle = "black";
                context.fillStyle = "black";
                cursor.style.background = "black";
                cursor.style.border = "none";
                hideOverlay();
            } else if (this.value == "erase") {
                context.strokeStyle = "white";
                context.fillStyle = "white";
                cursor.style.border = "1px solid black";
                cursor.style.background = "white";
                hideOverlay();
            } else if (this.value == "undo") {

            } else if (this.value == "redo") {

            } else if (this.value == "background") {
                fill = true;
                showOverlay("background");
            } else if (this.value == "cursor") {
                showOverlay("cursor");
            }
        }

        for (var i = 0; i < tools.length; i++) {
            tools[i].addEventListener('click', changeStatus, false);
        }

    //  Overlay function

        var overlay = document.getElementById("overlay");
        var backgroundSettings = document.getElementById("background-settings");
        var cursorSettings = document.getElementById("cursor-settings");
        var okBtns = document.getElementsByClassName("ok-btn");

        var showOverlay = function(type) {
            overlay.style.display = "block";
            if (type == "background") {
                backgroundSettings.style.display = "block";
            } else if (type == "cursor"){
                cursorSettings.style.display = "block";
            }
        }

        var hideOverlay = function() {
            overlay.style.display = "none";
            cursorSettings.style.display = "none";
            backgroundSettings.style.display = "none";
        }

        for (var i = 0; i < okBtns.length; i++) {
            okBtns[i].addEventListener('click', hideOverlay, false);
        }

        overlay.addEventListener('click', hideOverlay);

    // Settings function

        var radiusChanger = document.getElementById("radius-changer");
        var currentRadius = document.getElementById("current-stroke");

        var changeRadius = function(e) {
            radius = this.value;
            context.lineWidth = this.value * 2;
            currentRadius.innerHTML = this.value + "px";
        }

        radiusChanger.addEventListener('change', changeRadius);


        var squaredBackground = document.getElementById("squared-background");

        var changeBackground = function(e) {
            canvas.style.backgroundImage = "url('squared.png')";
        }

        squaredBackground.addEventListener('click', changeBackground);

</script>
