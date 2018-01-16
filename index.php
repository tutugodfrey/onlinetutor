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
    <link type = 'text/css' rel = 'stylesheet' href = 'mylecapp_css/bootstrap4_css/bootstrap.min.css' />
    <link type = 'text/css' rel = 'stylesheet' href = 'mylecapp_css/style1.css' />
  </head>
  <body>
    <div>
      <div id = 'header-div' class = 'page-header navbar' >
      </div>
      <div><p id = "ajax_neutral">Processing ...</p></div>
      <div id = 'content-area'>
        
      </div>
      <div id = 'footer-div'>
      </div>
    </div>
    <script src = 'jsfiles/bootstrap4_js/bootstrap.min.js' ></script>
    <script src = '/dist/bundle.js'></script>
  </body>
</html>
block;
