<?php require("config.php"); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="//cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.6/darkly/bootstrap.min.css" rel="stylesheet" />
	<title>Pokemon GO Server Status</title>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
  </head>
  <body>

    <div class="container"><!-- container -->

      <div class="header">
        <h1 class="text-muted"><a href="index.php">Pokemon GO Server Status</a></h1>
        <hr>
      </div>
<?php

$monitor_keys = array(
	"m777999387-4c97237abb441b7e1ecfeae6",
	"m777999393-a3efd5e7bfdf2c23a000e2c6",
	"m777999390-47b6ca4973d90cea4b3650ff",
	"m777999395-ccb5fd395e2fb2231672dc5f",
);

foreach($monitor_keys as $monitor_key){


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.uptimerobot.com/getMonitors?apiKey=".$monitor_key."&customUptimeRatio=7&responseTimes=1&responseTimesLimit=24&responseTimesAverage=360&format=json");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$response = str_replace("jsonUptimeRobotApi(", "", $response);
$response = substr($response, 0, -1);

$uptime_data = json_decode($response, true);


foreach($uptime_data['monitors']['monitor'] as $monitor){
	switch ($monitor['status']) {
		// paused
	    case "0":
	    	$emoji = "⏸";
	    	$statusText = _("Paused");
	        break;
		// not checked yet
	    case "1":
	    	$emoji = "🔄";
	    	$statusText = _("Not checked yet");
	        break;
		// up
	    case "2":
	    	$emoji = "✅";
	    	$statusText = _("Online");
	        break;
		// seems down
	    case "8":
	    	$emoji = "⭕️";
	    	$statusText = _("Seems down");
	        break;
		// down
	    case "9":
	    	$emoji = "🚫";
	    	$statusText = _("DOWN");
	        break;
	}
	echo "<h2>".$emoji." ".$monitor['friendlyname']."</h2>";
	echo "<p>Status: <b>".$statusText."</b></p>";
	
	echo "<h3>"._("Uptime")."</h3>";
	?>
	<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $monitor['customuptimeratio']; ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: <?php echo $monitor['customuptimeratio']; ?>%;"><?php echo $monitor['customuptimeratio']; ?>%</div></div>
	<?php
	echo "<p>"._("Last 7 days").": <b>".$monitor['customuptimeratio']."%</b><br>";
	echo _("Alltime").": ".$monitor['alltimeuptimeratio']."% </p>";
	
	if( !empty($monitor['responsetime'][0]['value']) ){
		echo "<h3>"._("Response Time")."</h3>";
		echo "<p>"._("Average in last 6 hours").": <b>".$monitor['responsetime'][0]['value']." "._("Milliseconds")."</b>";
		
		if( !empty($monitor['responsetime'][1]['value']) ){
			echo "<br>"._("Average previous 6 hours").": ".$monitor['responsetime'][1]['value']." "._("Milliseconds");
		}
		if( !empty($monitor['responsetime'][2]['value']) ){
			echo "<br>"._("Average previous 6 hours").": ".$monitor['responsetime'][2]['value']." "._("Milliseconds");
		}
		if( !empty($monitor['responsetime'][3]['value']) ){
			echo "<br>"._("Average previous 6 hours").": ".$monitor['responsetime'][3]['value']." "._("Milliseconds");
		}
		echo "</p>";
	}

} // end foreach $uptime_data['monitors']['monitor']

} // end foreach $monitor_keys

?>


<div class="footer">
 <hr>
 <p>Pokémon and Pokémon character names are trademarks of Nintendo. Other trademarks are the property of their respective owners.</p>
 <p class="text-center"><?php echo _("Powered by"); ?> <a href="https://uptimerobot.com/" target="_blank">Uptime Robot</a>.</p>

</div>
</div><!-- /container -->
</body>
</html>