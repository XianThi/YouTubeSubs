<html>
<head>
  <meta charset="UTF-8">

<style>
	.box {font-family: Arial, sans-serif;background-color: #F1F1F1;border:0;width:340px;webkit-box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3);box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.3);margin: 0 auto 25px;text-align:center;padding:10px 0px;}
	.box img{padding: 10px 0px;}
	.box a{color: #427fed;cursor: pointer;text-decoration: none;}
	.heading {text-align:center;padding:10px;font-family: 'Open Sans', arial;color: #555;font-size: 18px;font-weight: 400;}
	.circle-image{width:100px;height:100px;-webkit-border-radius: 50%;border-radius: 50%;}
	.welcome{font-size: 16px;font-weight: bold;text-align: center;margin: 10px 0 0;min-height: 1em;}
	.oauthemail{font-size: 14px;}
	.logout{font-size: 13px;text-align: right;padding: 5px;margin: 20px 5px 0px 5px;border-top: #CCCCCC 1px solid;}
	.logout a{color:#8E009C;}
	.output{min-height:300px;border:#F0F0F0 1px solid;padding:15px;}
</style>
<script
  src="https://code.jquery.com/jquery-2.2.4.min.js"
  integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
  crossorigin="anonymous"></script>
<script>
function getSubs(channel_id) {
	$('.output').html('<img src="images/LoaderIcon.gif" />');
	jQuery.ajax({
		url: "subs.php",
		data:'channel_id='+channel_id,
		type: "POST",
		success:function(data){$('.output').html(data);}
	});
}
</script>
</HEAD>
<BODY>
<div class="heading">YoutubeSubs</div>
<div class="box">
  <div>
	<!-- Show Login if the OAuth Request URL is set -->
    <?php if (isset($authUrl)): ?>
	  <img src="images/subs.png" width="100px" size="100px" /><br/>
	  Click image for login with YouTube!
      <a class='login' href='<?php echo $authUrl; ?>'><img class='login' src="images/loginyoutube.jpg" width="250px" size="54px" /></a>
	<!-- Show User Profile otherwise-->
    <?php else: ?>
	  <img class="circle-image" src="<?php echo $userData["snippet"]["thumbnails"]["default"]["url"]; ?>" width="100px" size="100px" /><br/>
	  <p class="welcome">Welcome <?php echo $userData["snippet"]["title"]; ?> !</p>
	  <p class="oauthemail"><a href="https://www.youtube.com/c/<?php echo $userData["snippet"]["customUrl"]; ?>" target="_blank" /><?php echo $userData["snippet"]["customUrl"]; ?></a></p>
	  <p class="oauthemail">Subscriber : <?php echo $userData["statistics"]["subscriberCount"]; ?></p>
	  <p class="oauthemail">Credit : <?php echo $userData["credit"]; ?></p>
	  <div class='getSubs'><a href='#' onClick='getSubs("<?php echo $userData["channel_id"];?>")'>GetSubs!</a></div>
	  <div class='logout'><a href='?logout'>Logout</a></div>
	  <div class="output"></div>
    <?php endif ?>
  </div>
</div>
</BODY>
</HTML>
</HTML>
