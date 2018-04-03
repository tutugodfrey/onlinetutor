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
  </head>
  <body>
    <div>
      <div id = "header-div">
      </div>
      <div><p id = "ajax_neutral">Processing ...</p></div> 
      <div id = "content-area" class = "container-fluid">
      </div>
      <div id = "footer-div">
      </div>
    </div>
    <script src = "./dist/bundle.js"></script>
  </body>
</html>
block;
