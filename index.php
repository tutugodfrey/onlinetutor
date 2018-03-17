<?php
echo <<<block
<!DOCTYPE html>
<html>
  <head>
    <title>
      welcome
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel = "shortcut icon" href = "/images/onlinetutor.ico" type = "image/x-icon" />
    <link type = "text/css" rel = "stylesheet" href = "mylecapp_css/bootstrap4_css/bootstrap.min.css" />
    <link type = "text/css" rel = "stylesheet" href = "mylecapp_css/style1.css" />
  </head>
  <body>
    <div class = "container">
      <div id = "header-div">
      </div>
      <div><p id = "ajax_neutral">Processing ...</p></div>
      <div id = "content-area" class = ""> 
      </div>
      <div id = "footer-div" class = "navbar">
      </div>
    </div>
    <script src = "jsfiles/tether-1.3.3/dist/js/tether.min.js" ></script>
    <script src = "jsfiles/jquery-3.1.0.js" ></script>
    <script src = "jsfiles/bootstrap4_js/bootstrap.min.js" ></script>
    <script src = "./dist/bundle.js"></script>
  </body>
</html>
block;
