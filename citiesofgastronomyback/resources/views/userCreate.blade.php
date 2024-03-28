<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
        <meta name="x-apple-disable-message-reformatting">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="telephone=no" name="format-detection">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

</head>
<?php
//$name $email $token $expirationMail

?>
<body style="color:#222;
                font-family:arial, 'helvetica neue', helvetica, sans-serif;">
    <section style="max-width: 700px;
                width: 90%;
                margin: 100px auto;
                text-align: center;
                font-size: 1.35vw;"   >
        <div style=" font-size: 2.7vw;text-align: center;" >Cities of gastronomy</div>
        <div style="padding: 40px 35px;
                background-color: #FFF;
                margin-top: 26px;
                border-radius: 2px;
                box-shadow: 0 0 18px #DDD;
                font-size: 1.5vw;" >
            <div>You have received this message to generate a password for your account.</div>
            <div style="text-align: center;">
                <a style="border: 0;
                color: #FFF;
                background-color: #333;
                padding: 14px 35px;
                border-radius: 3px;
                margin: 17px 0 40px 0px;
                display: inline-block;
                font-size: 1.55vw;" href="<?php echo config('app.frontUrl')?>/create_password?token=<?php echo  $token?>">Generate a password</a>
            </div>
            <div style="border-top: solid 1px #DDD;
                padding-top: 40px;
                color: #999;
                font-size: 1.45vw;">
                If you are having problems clicking the “Reset Password” button copy and paste the URL below into your web browser.</div>
                <div>
                    <a href="<?php echo config('app.frontUrl')?>/create_password?token=<?php echo  $token?>">
                            <?php echo config('app.frontUrl')?>/create_password?token=<?php echo  $token?>
                    </a>
                </div>
        </div>
    </section>
</body>
</html>