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
      <div id = 'header' class = 'page-header navbar' >
        <ul class = 'nav' >
          <li class = 'nav-item' >
            <a href = '/onlinetutor/index.php' class = 'nav-link' >
              home
            </a>
          </li>
          <li class = 'nav-item' >
            <a href = '/onlinetutor/common/signup.php' class = 'nav-link' >
              signup
            </a>
          </li>
          <li class = 'nav-item'>
            <a href = '/onlinetutor/common/login.php' class = 'nav-link' >
              signin
            </a>
          </li>
        </ul>
      </div>
      <div><p id = "ajax_neutral">Processing ...</p></div>
      <div id = 'main_content'>
        <h1>
          Welcome to Online tutor
        </h1>
      </div>
      <div id = 'footer'>
      <ul class = 'nav' >
        <li class = 'nav-item' >
          <a href = '/onlinetutor/common/term-of-use.php' class = 'nav-link' >
            Terms of Use
          </a>
        </li>
        <li class = 'nav-item' >
          <a href = '/onlinetutor/common/about-us.php' class = 'nav-link' >
            About Us
          </a>
        </li>
        <li class = 'nav-item'>
          <a href = '/onlinetutor/common/contact-us.php' class = 'nav-link' >
            Contact Us
          </a>
        </li>
      </ul>
      </div>
    </div>
    <script src = 'jsfiles/bootstrap4_js/bootstrap.min.js' ></script>
    <script src = '/onlinetutor/dist/bundle.js'></script>
    <script type = "text/javascript" src = "./jsfiles/quotes.js"></script>
  </body>
</html>
block;
