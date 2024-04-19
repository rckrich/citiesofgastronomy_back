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

$url = "account/set_password";

if($modeType == 2){
    $txt1 = ' You have received this message because you requested a password reset for your account<br>
                If you have not requested the reset, please skip this message.';
    $txt2 = 'Reset Password';
    $txt3= 'If you are having problems clicking the “Reset Password” button copy and paste the URL below into your web browser.';
    //$url = 'reset_password';
}else{
    $txt1 = 'You have received this message to generate a password for your account.';
    $txt2 = 'Generate a password';
    $txt3= 'If you are having problems clicking the “Reset Password” button copy and paste the URL below into your web browser.';
    //$url = 'create_password';
};
?>
<body style="color:#222;
                font-family:arial, 'helvetica neue', helvetica, sans-serif;">
    <section style="max-width: 700px;
                width: 90%;
                margin: 100px auto;
                text-align: center;
                font-size: 15px;"   >
        <div style=" font-size: 30px;text-align: center;" >Cities of gastronomy</div>
        <div style="padding: 40px 35px;
                background-color: #FFF;
                margin-top: 26px;
                border-radius: 2px;
                box-shadow: 0 0 18px #DDD;
                font-size: 15px;" >
            <div><?php echo $txt1?></div>
            <div style="text-align: center;">
                <a style="border: 0;
                color: #FFF;
                background-color: #333;
                padding: 14px 35px;
                border-radius: 3px;
                margin: 17px 0 40px 0px;
                display: inline-block;
                font-size: 16px" href="<?php echo config('app.frontUrl').$url?>/<?= $token?>">
                <?php echo $txt2?></a>
            </div>
            <div style="border-top: solid 1px #DDD;
                padding-top: 40px;
                color: #999;
                font-size: 14px">
                <?php echo $txt3?>
                </div>
                <div>
                    <a href="<?php echo config('app.frontUrl').$url?>/<?= $token?>">
                            <?php echo config('app.frontUrl').$url?>/<?= $token?>
                    </a>
                </div>
        </div>
    </section>
</body>
</html>
