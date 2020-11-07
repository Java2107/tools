<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['email']) || !empty($_POST['email'])) {
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $quot = "'";
      $banstring = '"ACCOUNT_TEMP_BANNED_CHECK_EMAIL",';
      $invalidstring = '"EMAIL_DOES_NOT_EXIST",';
      $ratelimitedstring = '<title>Access denied | discord.com used Cloudflare to restrict access</title>';
      $ips = array('168.119.93.40','168.119.93.41','168.119.93.42','168.119.93.43','168.119.93.44','168.119.93.45','168.119.93.46','168.119.93.47');
      $interface = $ips[array_rand($ips)];
      $response = shell_exec('curl --interface '.$interface.' -H \'content-type: application/json\' --data-binary \'{"email":"'.escapeshellarg($_POST['email']).'"}\' --compressed https://discord.com/api/v8/auth/forgot');
      
      if (strpos($response, $banstring)!==False) { // Ideally this checking needs to be done by decoding the JSON response, but I'm too lazy. And the response is retarded.
        $message = '<div class="alert alert-danger" role="alert">Looks like your Discord account is banned from what I can see!</div>';
      } elseif (strpos($response, $invalidstring)!==False) {
        $message = '<div class="alert alert-danger" role="alert">Looks like your does not exist from what I can see!</div>';
      } elseif (strpos($response, $ratelimitedstring)!==False) {
        $message = '<div class="alert alert-danger" role="alert">Looks like we\'re being ratelimited by Discord, please try again later!</div>';
        $donotshow = '1';
      } else {
        $message = '<div class="alert alert-success" role="alert">Looks like your Discord account is not banned from what I can see!</div>';
      }
    } else {
      $message = '<div class="alert alert-info" role="alert">Please type a correctly formatted email address.</div>';
      $donotshow = '1';
    }
  } else {
    $message = '<div class="alert alert-info" role="alert">Please type an email address.</div>';
    $donotshow = '1';
  }
}


?>

<!doctype html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="name" content="Disabled Discord Account Chacker" />
    <meta name="description" content="Check the disabled status of any Discord account using just the email address." />
    <meta charset="UTF-8">
  </head>
<body>

<!-- navbar shit start -->
<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/navbar.php');?>
<!-- navbar shit end -->

<div class="container" style="padding-top:120px;">
  <h2>Account Checker</h2>
  <p>Simply input the Discord account's email address and click go.</p>
  <p><strong>Pre-emptive warning:</strong> If the target's Discord account is not disabled, they will recieve a password reset email.</p>

  <div class="form-group">
    <?php echo $message;?>
  </div>
  <?php if ($_SERVER['REQUEST_METHOD']==='POST' && $donotshow!=='1') {echo '<div class="form-group"><div class="alert alert-info" role="alert">IP used: '.$interface.'<br>Response from Discord\'s API: '.htmlspecialchars($response).'</div></div>';} ?>
  <form method="post">
  <div class="form-group">
    <label for="email">Email address</label>
    <input name="email" type="email" class="form-control" id="email" placeholder="igotbannedlolololol@google.com">
  </div>

  <button type="submit" class="btn btn-primary">Go</button>
  </form>

</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>