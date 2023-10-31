<?php
require_once __DIR__ . '/./vendor/autoload.php';
require_once __DIR__ . '/./inc/constant.php';

if($_POST)
{
    $user_name      = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $user_email     = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $user_phone     = filter_var($_POST["phone"], FILTER_SANITIZE_STRING);
    $content   = filter_var($_POST["content"], FILTER_SANITIZE_STRING);
    
    if(empty($user_name)) {
		$empty[] = "<b>Name</b>";		
	}
	if(empty($user_email)) {
		$empty[] = "<b>Email</b>";
	}
	if(empty($user_phone)) {
		$empty[] = "<b>Phone Number</b>";
	}	
	if(empty($content)) {
		$empty[] = "<b>Comments</b>";
	}
	
	if(!empty($empty)) {
        $error = implode(", ",$empty) . ' Required!';
	}
	
	if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
		$error = '<b>'.$user_email.'</b> is an invalid Email, please correct it.';
	}
	
	//reCAPTCHA validation
	if (isset($_POST['g-recaptcha-response'])) {
		$recaptcha = new \ReCaptcha\ReCaptcha(SECRET_KEY);

		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

        if (!$resp->isSuccess()) {
            $error = "<b>reCaptcha</b> Validation Required!";			
        }
        else {
            $message = "The email sent successfully.";
        }
    }
    else {
        $error = "<b>reCaptcha</b> Validation Required!";	
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recaptcha V2 Tutorial</title>

    <link rel="stylesheet" href="./public/css/style.css?v=" . <?= time() ?>">
</head>
<body>
    <div id="central">
        <div class="content">
            <h1>Contact Form</h1>
            <p>Send your comments through this form and we will get back to you. </p>
            <form id="frmContact" action="" method="POST" novalidate="novalidate">
                <?php if (isset($error) && $error) { ?>
                    <div class="error"><?= $error ?></div>
                <?php } else if (isset($message) && $message) { ?>
                    <div class="success"><?= $message ?></div>
                <?php } ?>
                <div class="label">Name:</div>
                <div class="field">
                    <input type="text" id="name" name="name" placeholder="enter your name here" title="Please enter your name" class="required" aria-required="true" required
                        value="<?= isset($_POST['name']) ? $_POST['name'] : '' ?>" />
                </div>
                <div class="label">Email:</div>
                <div class="field">			
                    <input type="text" id="email" name="email" placeholder="enter your email address here" title="Please enter your email address" class="required email" aria-required="true" required
                        value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" />
                </div>
                <div class="label">Phone Number:</div>
                <div class="field">			
                    <input type="text" id="phone" name="phone" placeholder="enter your phone number here" title="Please enter your phone number" class="required phone" aria-required="true" required
                        value="<?= isset($_POST['phone']) ? $_POST['phone'] : '' ?>" />
                </div>
                <div class="label">Comments:</div>
                <div class="field">			
                    <textarea id="comment-content" name="content" placeholder="enter your comments here"><?= isset($_POST['content']) ? $_POST['content'] : '' ?></textarea>			
                </div>
                <div class="g-recaptcha" data-sitekey="<?= SITE_KEY ?>"></div>					
                <button type="Submit" id="send-message" style="clear:both;">Send Message</button>
            </form>
        </div><!-- content -->
    </div><!-- central -->	

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?hl=iw"></script>	
  </script>
</body>
</html>