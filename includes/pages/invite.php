<?php
function invite($invite) {
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, 'https://discord.com/api/v8/invites/'.$invite.'?with_counts=true');
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  $curlresponse = curl_exec($curl);
  curl_close($curl);
  return json_decode($curlresponse);
}

function clean($string) {
    $string = str_replace(' ', 'dfdsfdsffdsdfs', $string);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
 }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!empty($_POST['invite'])) {
    $response = invite(clean($_POST['invite']));
    if($response->message!=='Unknown Invite') {
      $message = '
      <div class="alert alert-success" role="alert">
      <p>Valid invite code! Here\'s some information for discord.gg/'.htmlspecialchars($_POST['invite']).' AKA "'.htmlspecialchars($response->guild->name).'".</p>
      <p>Name: '.htmlspecialchars($response->guild->name).'</p>
      <p>Server ID: '.htmlspecialchars($response->guild->id).'</p>
      <p>Server Members total: '.htmlspecialchars($response->approximate_member_count).' / Server Members Online: '.htmlspecialchars($response->approximate_presence_count).'</p>

      <p>Invite Channel: '.htmlspecialchars($response->channel->name).'</p>
      <p>Invite Channel ID: '.htmlspecialchars($response->channel->id).'</p>

      <p>Invite Creator Username: '.htmlspecialchars($response->inviter->username).'#'.htmlspecialchars($response->inviter->discriminator).'</p>
      <p>Invite Creator ID: '.htmlspecialchars($response->inviter->id).'</p>
      </div>';
    } else {
      $message = '<div class="alert alert-info" role="alert">Invalid Invite Code.</div>';
    }
  } else {
    $message = '<div class="alert alert-info" role="alert">Please type an invite code.</div>';
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

<?php require_once ($_SERVER['DOCUMENT_ROOT'].'/includes/navbar.php');?>

<div class="container" style="padding-top:120px;">
  <h2>Discord Invite Checker</h2>
  <p>Enter the invite code into the box and click Go to validate and check the invite. (example: qDF3cagS instead of discord.gg/qDF3cagS)</p>
  <div class="form-group">
  <?php if ($_SERVER['REQUEST_METHOD']==='POST') {echo $message;}?>
  </div>
  <form method="post">
  <div class="form-group">
    <label for="invite">Invite code</label>
    <input name="invite" type="invite" value="<?php if($_SERVER['REQUEST_METHOD']==='POST'){echo htmlspecialchars($_POST['invite']);}?>" class="form-control" id="invite" placeholder="spotify">
  </div>

  <button type="submit" class="btn btn-primary">Check</button>
  </form>

</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
