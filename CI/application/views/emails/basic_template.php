<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?=SITE_NAME?></title>
</head>
<body>
<?= (isset($email_content)) ? $email_content : '[email_content]' ?>
<br/>
The <?=SITE_NAME?> Team<br/>

<p style="font-style:italic;font-size:11px;margin-top:20px;border-top:1px solid #ddd;padding:10px">
    <b>Do not reply</b> to this email. If you have any questions, please submit a <a href="<?=site_url()?>support">support ticket</a>.
</p>
</body>
</html>

