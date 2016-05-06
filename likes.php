<html>
<?php
    require __DIR__ . '/vendor/autoload.php';
    use \Curl\Curl;
    $objectIdStr = '1096761983717067,1037333949679906,1223479844337140';
    $accessToken = "EAACEdEose0cBAOvPg6CBKrKpzYYSL1Jr2NBAzhoZCxHdQ5HBWYm71A9k2XwBpTYcgZCUqYV4YPupkB9WXgsLv7yfeMS2bpZBkDLfyNH208j1f6N07ykEJxWUAtMbWvuf6udhOwnrwZA7KbVRK6XMzUx4azfgZATFzCRA6MTOExQZDZD";
    $data = array();
    if(isset($_POST["accessToken"]) && isset($_POST["objectId"])) {
    
        $objectIdStr = $_POST["objectId"];
        $objectIdArr = explode(",", $_POST["objectId"]);
        $accessToken = $_POST["accessToken"];
        
        foreach($objectIdArr as $objectId) {
            
            
            
            $curl = new Curl();
            $response = $curl->get("https://graph.facebook.com/v2.3/$objectId/likes", 
                     array(
                        'fields' => 'id,pic,name,link',
                        'limit' => '1000',
                        'access_token' => $accessToken,
                      ));
            
            
            foreach($response->data as $value) {
                if(!array_key_exists($value->id, $data)) {
                     $data[$value->id] = array("data" => $value, "count" => 1);
                   
                } else {
                     $data[$value->id]["count"] =  $data[$value->id]["count"] +1;
                     
                }
            }
            
        }
        
    
    }
    
    function cmp($a, $b)
    {
        if ($a["count"] == $b["count"]) {
            return 0;
        }
        return ($a > $b) ? +1 : -1;
    }
   
    
    usort($data, "cmp");
?>
<head> <meta charset="utf-8" />
    
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container">
      <h2><img width="64px" hight="64px" src="images/square-facebook-512.png" />Facebook</h2>
      
      <div>
          
          <form action="likes.php" method="post">
          <div class="form-group">
            <label for="objectId">ObjectID</label>
            <input type="text" class="form-control" id="objectId" name="objectId" value = "<?php echo $objectIdStr; ?>" placeholder="Post ID, Page ID, photo ID">
          </div>
          
          
          <div class="form-group">
            <label for="accessToken"><a target="_blank" href='https://developers.facebook.com/tools/explorer/145634995501895/'>Access Token</a></label>
            <input type="text" class="form-control" id="accessToken" name="accessToken" value = "<?php echo $accessToken ?>">
          </div>
          <button type="submit" class="btn btn-default">Query</button>
        </form>
      </div><!-- end form -->
     
        
        <?php if(!empty($data)) { ?>
         <div>
          <h2>Result(<?php echo count($data); ?>)</h2>
          <table class="table">
            <thead>
              <tr>
                
                <th>User name</th>
                <th>Avatar</th>
                <th>Active</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data as $value) { ?>
              <tr>
                <td><a href="<?php echo $value["data"]->link; ?>"><img src="<?php echo $value["data"]->pic ?>" /></a></td>
                <td><?php echo $value["data"]->name ?></td>
                <td><?php echo $value["count"]?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          </div><!-- end result -->
          <?php } ?>
          
          
          
    </div><!-- end container -->
    <!--<pre>
    <?php
    
     /*print_r($data);*/
    ?>
    </pre>-->
</body>
</html>