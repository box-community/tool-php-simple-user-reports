<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    
    /*
     * One-page script for automatically getting reports from box and displaying or dumping 
     * them into a database. Feel free to break this out into its components 
     * as you see fit. I just figured keeping it all on one page would be easier 
     * for people who need this kind of thing AND want to keep it really simple.
     */

    /*
     * CHANGE THE VALUES IN THIS SECTION
     * 
     */

    // database connection
    function dbConnectionArray() {
        return array(
            'database'      => 'box-reports',
            'username'      => 'local',
            'password'      => 'local',
            'host'          => 'localhost',
            'port'          => '3306',
        );
    }
    

    // app config
    function myAppConfigArray() {
        return array(
            'baseUrl'   => 'http://localhost/box-reports', // the URL where this app is installed
        );
    }
    
    function boxAppConfigArray() {
        
        $myAppConfigArray = myAppConfigArray();
        
        return array(
            'clientId'      => '', // from box dev console for your app
            'clientSecret'  => '', // from box dev console for your app
            'csrfPreventionString' => '', // for checking to make sure the returned information came from you and not a man-in-the-middle
            'redirectUri'   => $myAppConfigArray['baseUrl'] . '/index.php?page=exchange-code-for-token', // the URL to return to, to convert the code for an access & refresh token set
            'apiBaseUrl'    => 'https://api.box.com/2.0/', // base URL for the box api calls
        );
    }
    
    /*
     * STOP!!!
     * 
     * YOU DO NOT NEED TO CHANGE ANYTHING UNDER THIS LINE (UNLESS YOU WANT TO)
     * 
     */
    
  
?>

<?php $thisPage = filter_input(INPUT_GET, 'page'); // check which page is being requested ?>

<?php if (empty($thisPage)): ?>

    <!-- SECTION FOR STARTING THE PROCESS THE FIRST TIME ONLY -->
    <?php $boxAppConfigArray = boxAppConfigArray(); ?>
    <html lang="en">
        <head>
          <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
          <meta name="description" content="">
          <meta name="author" content="">
          <link rel="icon" href="../../favicon.ico">

          <title>Box Reports Simple Example</title>

          <!-- Bootstrap -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
            <link rel="stylesheet" href="style.css">
              <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
              <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
              <![endif]-->
        </head>
        <body>
          <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Box Reports - Simple Example</a>
              </div>
              <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="active"><a href="index.php">Home</a></li>
                  <li><a href="index.php?page=getOneTimeToken">Get One-Time Token</a></li>
                  <li><a href="index.php?page=getAllUsersLive">Get All Users (Live)</a></li>
                  <li><a href="index.php?page=getAllUsersLive&updateDatabaseToo=1">Get All Users (Live) & Update DB</a></li>
                  <li><a href="index.php?page=getAllUsers">View DB Records</a></li>
                </ul>
              </div><!--/.nav-collapse -->
            </div>
          </nav>
          <div class="container">
              <h1>Simple Box Reports</h1>
              <p>Everything is in one file (sure, it's busy, but it's easy to understand... i think.</p>
              <h2>Pre-Requisites</h2>
              <ul>
                      <li>MySQL Database</li>
                      <li>PHP 5.3+</li>
                      <li>Box Developer Console Access (<a href="http://developers.box.com" title="Box Developer Console">developers.box.com</a>)</li>
              </ul>
              <h2>Configuration & Initial Set up</h2>
              <ol>
                    <li>We're going to assume that you'll install this for testing and run it from http://localhost/box-reports/index.php</li>
                    <li>Create a database for this file to put data into</li>
                    <li>Import the install.sql file into that database. You should end up with 3 tables</li>
                    <li>
                        Visit the Box Developer Console and set up a new app to use <a href="http://developers.box.com" title="Box Developer Console">developers.box.com</a>)
                        <ul>
                            <li>Call the app anything you want. How about "Box Reports - Simple App" or something like that?</li>
                            <li>In the "scope" checkbox section, check the boxes for "Manage an enterprise" and "Manage an enterprise's managed users"</li>
                            <li>In the Redirect URI field, put "http://localhost/box-reports/index.php?page=exchange-code-for-token"</li>
                            <li>Save your app, but keep the page open, because you'll need the ClientId and Client Secret in a few seconds</li>
                        </ul>
                    </li>
                    <li>Open index.php in a text editor / IDE of your choice (I like Sublime Text 2 or Netbeans)</li>
                    <li>Update the database information, and box app information (ie: database name, host, client_id from Box etc.</li>
              </ol>
              <h2>First time token retrieval</h2>
              <ol>
                    <li>The first time you want to run this, you'll need to get a token, and that means clicking some things in a browser.</li>
                    <li>Click on the menu item above for "Get One-Time Token"</li>
                    <li>Then click the button provided, which will send you to Box for Authorization.</li>
                    <li>It will then ask you to login (use a Box Admin or Co-Admin account)</li>
                    <li>After that flow is complete, you'll be redirected to a page with all of your box users (which will also be copied into your database)</li>
              </ol>
              <h2>Manual, or Automatic.... choose wisely (not really)</h2>
              <ul>
                    <li>You can use the menu items above to use this tool manually, OR</li>
                    <li>Set up a cron-job that runs every day/week to visit http://localhost/box-reports/index.php?getAllUsersLive&updateDatabaseToo=1 </li>
                    <li>As long as you perform a live users lookup in this tool once every 60 days, you won't have to do the "one time token" step again.</li>
              </ul>
          </div><!-- /.container -->


          <!-- Bootstrap core JavaScript
          ================================================== -->
          <!-- Placed at the end of the document so the pages load faster -->
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        </body>
      </html>
    
<?php elseif ($thisPage == 'getOneTimeToken'): ?>

    <!-- SECTION FOR STARTING THE PROCESS THE FIRST TIME ONLY -->
    <?php $boxAppConfigArray = boxAppConfigArray(); ?>
    <html lang="en">
        <head>
          <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
          <meta name="description" content="">
          <meta name="author" content="">
          <link rel="icon" href="../../favicon.ico">

          <title>Box Reports Simple Example</title>

          <!-- Bootstrap -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
            <link rel="stylesheet" href="style.css">
              <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
              <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
              <![endif]-->
        </head>
        <body>
          <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Box Reports - Simple Example</a>
              </div>
              <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="active"><a href="index.php">Home</a></li>
                  <li><a href="index.php?page=getOneTimeToken">Get One-Time Token</a></li>
                  <li><a href="index.php?page=getAllUsersLive">Get All Users (Live)</a></li>
                  <li><a href="index.php?page=getAllUsersLive&updateDatabaseToo=1">Get All Users (Live) & Update DB</a></li>
                  <li><a href="index.php?page=getAllUsers">View DB Records</a></li>
                </ul>
              </div><!--/.nav-collapse -->
            </div>
          </nav>
          <div class="container">

            <div class="starter-template">
              <h1>One-Time Get Box Code</h1>
                <p class="lead">Click the button below to begin the process. You will be redirected to login to Box (use an Admin/Co-Admin acct). Then when the process is finished, an access token and a refresh token will be stored in the local database for continued use.</p>
                <p class="lead">Note: You should only do this if the existing token is not working (expired/invalidated/revoked etc). This manual step should only be required the very first time you want to run this script, but then not again (as long as you use it once every 60 days).</p>
                <form action="<?php echo "https://app.box.com/api/oauth2/authorize?response_type=code&client_id=" . $boxAppConfigArray['clientId'] . "&state=" . $boxAppConfigArray['csrfPreventionString']; ?>" method="POST" title="Get Box Dev Token form">
                    <p><input class="btn btn-success" type="submit" name="submit" value="Begin Box Token Process"></p>
                </form>
            </div>
          </div><!-- /.container -->


          <!-- Bootstrap core JavaScript
          ================================================== -->
          <!-- Placed at the end of the document so the pages load faster -->
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        </body>
      </html>
    
<?php

    elseif($thisPage == 'exchange-code-for-token'):

        $state = filter_input(INPUT_GET, 'state');
        $code = filter_input(INPUT_GET, 'code');

        $boxAppConfigArray = boxAppConfigArray();
        $myAppConfigArray = myAppConfigArray();
        
        if($state != $boxAppConfigArray['csrfPreventionString']) {
            die('not the correct csrfPreventionString. Something went wrong on the return trip.');
        }

        $result = getBoxTokenFromCode($code, $boxAppConfigArray);

        // get access_token and refresh token from database
        $accessToken = getAccessTokenFromDatabase();
        $refreshToken = getRefreshTokenFromDatabase();
        
        if(!$accessToken) {
            updateAccessTokenInDatabase($result->access_token, 'insert');
        } else {
            updateAccessTokenInDatabase($result->access_token, 'update');
        }

        if(!$refreshToken) {
            updateRefreshTokenInDatabase($result->refresh_token, 'insert');
        } else {
            updateRefreshTokenInDatabase($result->refresh_token, 'update');
        }
        
        ?>
        
        <html lang="en">
        <head>
          <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
          <meta name="description" content="">
          <meta name="author" content="">
          <link rel="icon" href="../../favicon.ico">

          <title>Box Reports Simple Example</title>

          <!-- Bootstrap -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
            <link rel="stylesheet" href="style.css">
              <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
              <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
              <![endif]-->
        </head>
        <body>
          <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Box Reports - Simple Example</a>
              </div>
              <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="active"><a href="index.php">Home</a></li>
                  <li><a href="index.php?page=getOneTimeToken">Get One-Time Token</a></li>
                  <li><a href="index.php?page=getAllUsersLive">Get All Users (Live)</a></li>
                  <li><a href="index.php?page=getAllUsersLive&updateDatabaseToo=1">Get All Users (Live) & Update DB</a></li>
                  <li><a href="index.php?page=getAllUsers">View DB Records</a></li>
                </ul>
              </div><!--/.nav-collapse -->
            </div>
          </nav>
          <div class="container">

            <div class="starter-template">
                    <h1>You got a token! He got a token!<br />EVERYONE GOT A TOKENNNNNNN!!!</h1>
                    <p class="lead">Now you are ready to look up your users. Use the links above.</p>
            </div>
          </div><!-- /.container -->
        </body>
      </html>
      
      <?php

        elseif($thisPage == 'getAllUsersLive'):

            $updateDatabaseToo = filter_input(INPUT_GET, 'updateDatabaseToo');

            $allUsers = getAllUsers($updateDatabaseToo);
            if(empty($allUsers)): 
                die('no users found'); 
            endif; 

        elseif($thisPage == 'getAllUsers'):

            $thisTimestamp = filter_input(INPUT_GET, 'timestamp');

            $allUsers = getUserRecordsFromDatabase($thisTimestamp);
            if(empty($allUsers)): 
                die('no users found'); 
            endif; 
        
    ?> 
    <!-- page to display all users --> 
    <html lang="en">
        <head>
          <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
          <meta name="description" content="">
          <meta name="author" content="">
          <link rel="icon" href="../../favicon.ico">

          <title>Box Reports Simple Example</title>

          <!-- Bootstrap -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
            <link rel="stylesheet" href="style.css">
              <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
              <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
              <![endif]-->
        </head>
        <body>
          <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Box Reports - Simple Example</a>
              </div>
              <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="active"><a href="index.php">Home</a></li>
                  <li><a href="index.php?page=getOneTimeToken">Get One-Time Token</a></li>
                  <li><a href="index.php?page=getAllUsersLive">Get All Users (Live)</a></li>
                  <li><a href="index.php?page=getAllUsersLive&updateDatabaseToo=1">Get All Users (Live) & Update DB</a></li>
                  <li><a href="index.php?page=getAllUsers">View DB Records</a></li>
                </ul>
              </div><!--/.nav-collapse -->
            </div>
          </nav>
          <div class="container">

            <div class="starter-template">
              
                <?php if($thisPage == 'getAllUsersLive'): ?>
                    <h1>Display All Users (Live Lookup in Box via API)</h1>
                    <h3>Summary</h3>
                    <p>Total Storage Used: <?php echo $allUsers['totalStorage'] / (1024 * 1024 * 1024) . ' GB.'; ?></p>
                    <p>Total Users: <?php echo $allUsers['totalUsers']; ?></p>
                <?php elseif($thisPage == 'getAllUsers' && $thisTimestamp): ?>
                    <h1>Display All Users: <?php echo date('Y-m-d H:i A', $thisTimestamp); ?>)</h1>
                    <h3>Summary</h3>
                    <p>Total Storage Used: <?php echo $allUsers['totalStorage'] / (1024 * 1024 * 1024) . ' GB.'; ?></p>
                    <p>Total Users: <?php echo $allUsers['totalUsers']; ?></p>
                <?php elseif($thisPage == 'getAllUsers' && !$thisTimestamp): ?>
                    <h1>Select which time to get stats for</h1>
                <?php endif; ?>
                    <?php if($thisPage == 'getAllUsers' && !$thisTimestamp): ?>
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>timestamp</th>
                            </thead>
                            <tbody>        
                                <?php foreach ($allUsers as $user): ?>
                                        <tr>
                                            <td>
                                                <a href="index.php?page=getAllUsers&timestamp=<?php echo $user['timestamp']; ?>" title="View users from <?php echo date('Y-m-d H:i A', $user['timestamp']) ;?>">
                                                    <?php echo date('Y-m-d H:i A', $user['timestamp']); ?></td>
                                                </a>
                                        </tr>
                                <?php endforeach; ?>   
                            </tbody> 
                        </table>
                    <?php else: ?>
                        <table class="table table-hover table-striped">
                            <thead>
                                <th>login</th>
                                <th>name</th>
                                <th>created_at</th>
                                <th>modified_at</th>
                                <th>space_amount</th>
                                <th>space_used</th>
                                <th>status</th>
                            </thead>
                            <tbody>        
                                <?php foreach ($allUsers['users'] as $user): ?>
                                    <?php if($thisPage == 'getAllUsersLive'): ?>
                                    <tr>
                                        <td><?php echo $user->login; ?></td>
                                        <td><?php echo $user->name; ?></td>
                                        <td><?php echo $user->created_at; ?></td>
                                        <td><?php echo $user->modified_at; ?></td>
                                        <td><?php echo $user->space_amount; ?></td>
                                        <td><?php echo $user->space_used; ?></td>
                                        <td><?php echo $user->status; ?></td>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td><?php echo $user['login']; ?></td>
                                        <td><?php echo $user['name']; ?></td>
                                        <td><?php echo $user['created_at']; ?></td>
                                        <td><?php echo $user['modified_at']; ?></td>
                                        <td><?php echo $user['space_amount']; ?></td>
                                        <td><?php echo $user['space_used']; ?></td>
                                        <td><?php echo $user['status']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>   
                            </tbody> 
                        </table>
                    <?php endif; ?>
            </div>
          </div><!-- /.container -->


          <!-- Bootstrap core JavaScript
          ================================================== -->
          <!-- Placed at the end of the document so the pages load faster -->
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        </body>
      </html>
    
    <?php endif; ?>

    
    <?php 
    
    function getBoxTokenFromCode($code, $boxAppConfigArray) {

        $parametersArray = array(
            'grant_type'    => 'authorization_code',
            'code'          => $code,
            'client_id'     => $boxAppConfigArray['clientId'],
            'client_secret' => $boxAppConfigArray['clientSecret']
        );
        
        $curlParams = http_build_query($parametersArray);
        
        $endpoint = 'https://app.box.com/api/oauth2/token';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlParams);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        
        $result = curl_exec($ch);
        
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        //$header = substr($result, 0, $header_size);
        $body = json_decode(substr($result, $header_size));
        $header = substr($result, 0, $header_size);
        
        if (isset($body->errors)) {

            die('errors in the returned information from getBoxTokenFromCode function');
        }
        /*
          echo '<pre>';
          print_r($body);
          die();
        */
        return $body;
    }
    
    function refreshToken($refreshToken = false) {

        
        // endpoint sample:
        /*
        curl https://app.box.com/api/oauth2/token \ -d 'grant_type=refresh_token&refresh_token={valid refresh token}&client_id={your_client_id}&client_secret={your_client_secret}' \
         *  -X POST
         * *
         */
        
        $boxAppConfigArray = boxAppConfigArray();
        
        if(!$refreshToken) {
            $refreshToken = getRefreshTokenFromDatabase();
        }
        
        $parametersArray = array(
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id'     => $boxAppConfigArray['clientId'],
            'client_secret' => $boxAppConfigArray['clientSecret']
        );
        
        $curlParams = http_build_query($parametersArray);
        
        $endpoint = 'https://app.box.com/api/oauth2/token';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlParams);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        
        $result = curl_exec($ch);
        
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        //$header = substr($result, 0, $header_size);
        $body = json_decode(substr($result, $header_size));
        $header = substr($result, 0, $header_size);
        
        if (isset($body->errors)) {

            die('errors in the returned information from refreshToken function');
        }
        /*
          echo '<pre>';
          print_r($body);
          die();
        */
        
        // update records in DB
        updateAccessTokenInDatabase($body->access_token, 'update');
        updateRefreshTokenInDatabase($body->refresh_token, 'update');
        
        return $body;
    }
    
    function getAllUsers($updateDatabaseToo = false) {
        
        // first refresh token
        $refreshToken = refreshToken();
        $accessToken = getAccessTokenFromDatabase();
        
        $boxAppConfigArray = boxAppConfigArray();
        
        $returnArray = array(
            'users' => array(),
            'totalUsers' => 0,
            'totalStorage' => 0,
        );

        $limit = 1000;
        $offset = 0;
        $totalRetrieved = 0;

        do {

            $endpoint = $boxAppConfigArray['apiBaseUrl'] . '/users?limit=' . $limit . '&offset=' . $offset;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $accessToken
            ));
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $result = curl_exec($ch);

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            //$header = substr($result, 0, $header_size);
            $body = json_decode(substr($result, $header_size));
            $header = substr($result, 0, $header_size);

            if (isset($body->errors)) {
                die('errors in the returned information from refreshToken function');
            }
            
            $returnArray['users'] = array_merge($returnArray['users'], $body->entries);
            
            $offset = $offset + $limit;
            
            $totalRetrieved = $totalRetrieved + count($body->entries);
        
        } while ($totalRetrieved < $body->total_count);
        
        if(!empty($returnArray)) {
            $now = time();
            $returnArray['totalUsers'] = 1;
            foreach($returnArray['users'] as $u) {

                $returnArray['totalUsers']++;
                $returnArray['totalStorage'] = $returnArray['totalStorage'] + $u->space_used;

                // update database if requested
                if($updateDatabaseToo) {
                    $conn = dbConnect();
                    $result = $conn->query("INSERT INTO `user_stats` (name, login, created_at, modified_at, space_amount, space_used, status, timestamp) VALUES "
                        . "('$u->name', '$u->login', '$u->created_at', '$u->modified_at', '$u->space_amount', '$u->space_used', '$u->status', '$now')");
                    
                }
            }
            
            if($updateDatabaseToo) {
            
                // and update the service_stats table with summary info too
                $result = $conn->query("INSERT INTO `service_stats` (totalUsers, totalStorage, timestamp) VALUES "
                        . "('" . $returnArray['totalUsers'] . "', '" . $returnArray['totalStorage'] . "', '$now')");
            
            }
            
        }
        
        return $returnArray;
        
    }
    
    function dbConnect()
    {
        
        $dbConnectionArray = dbConnectionArray();
        
        $conn = new mysqli($dbConnectionArray['host'], $dbConnectionArray['username'], $dbConnectionArray['password'], $dbConnectionArray['database']);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        return $conn;
        
    }
    
    function getUserRecordsFromDatabase($timestamp = false)
    {
        
        // Create connection
        $conn = dbConnect();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        if($timestamp) {
            $sql = "SELECT * FROM user_stats WHERE timestamp = '$timestamp'";
            $result = $conn->query($sql);

            $returnArray = array(
                'users' => array(),
                'totalUsers' => 0,
                'totalStorage' => 0,
            );

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $returnArray['users'][] = $row;
                    $returnArray['totalUsers']++;
                    $returnArray['totalStorage'] = $returnArray['totalStorage'] + $row['space_used'];
                }
            } else {
                return false;
            }
        } else {
            $sql = "SELECT timestamp FROM user_stats GROUP BY timestamp";
            $result = $conn->query($sql);
            $returnArray = array();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $returnArray[] = $row;
                }
            } else {
                return false;
            }
        }
        
        $conn->close();
        
        //echo '<pre>'; print_r($returnArray); die('ghfuysdhajfkds');
        
        return $returnArray;
        
    }
    
    function getAccessTokenFromDatabase()
    {
        
        // Create connection
        $conn = dbConnect();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "SELECT * FROM `tokens` WHERE varName = 'access_token'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return $row["value"];
            }
        } else {
            return false;
        }
        $conn->close();
        
    }
    
    function getRefreshTokenFromDatabase()
    {
        
        // Create connection
        $conn = dbConnect();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "SELECT * FROM `tokens` WHERE varName = 'refresh_token'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return $row["value"];
            }
        } else {
            return false;
        }
        $conn->close();
        
    }
    
    function updateRefreshTokenInDatabase($tokenString)
    {
        
        // Create connection
        $conn = dbConnect();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $result = $conn->query("UPDATE `tokens` SET value = '$tokenString' WHERE varName = 'refresh_token'");
        
        return $result;
        
    }
    
    function updateAccessTokenInDatabase($tokenString)
    {
        
        // Create connection
        $conn = dbConnect();
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $result = $conn->query("UPDATE `tokens` SET value = '$tokenString' WHERE varName = 'access_token'");
        
        return $result;
        
    }