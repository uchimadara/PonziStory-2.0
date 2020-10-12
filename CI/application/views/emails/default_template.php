<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?=SITE_NAME?></title>
</head>
<body bgcolor="#545454" style="width:100%;margin:0;padding:0;color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px;">
<!-- WRAPPER TABLE -->
<table style="table-layout:fixed;color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px;" class="wrapper" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#545454" style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px;">
            <br>
            <!-- content-->
            <table style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px; margin:0 auto;" align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" rules="none" frame="border" width="600">
                <tr>
                    <td style="background-color:#90B18F;color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" valign="top">
                        <!-- header  -->
                        <table style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px; margin:0 auto;" align="center" cellpadding="0" cellspacing="0" width="540" bgcolor="#90B18F">
                            <tr>
                                <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px;padding-top:12px;" valign="top" width="260">
                                    <?php if (isset($clickthru_link)): ?>
                                        <a href="<?= $clickthru_link ?>"><img alt="email" style="border:none" src="<?= SITE_ADDRESS ?>images/email_logo.png" width="260" height="60"></a>
                                    <?php else: ?>
                                        <a href="<?=SITE_ADDRESS?>"><img alt="email" style="border:none" src="<?= SITE_ADDRESS ?>images/email_logo.png" width="260" height="60"></a>
                                    <?php endif; ?>
                                </td>
                                <!-- spacer -->
                                <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" width="20">&nbsp;</td>
                                <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px; padding-top:10px;" align="right" valign="top" width="260">

                                    <?php if (isset($clickthru_link)): ?>
                                        <b style="font-weight:bold"><a style="color:#003FC4;text-decoration:underline" href="<?= $clickthru_link.base64_encode(SITE_ADDRESS) ?>"><?= SITE_NAME ?></a></b><br/>
                                    <?php else: ?>
                                        <b style="font-weight:bold"><a style="color:#003FC4;text-decoration:underline" href="<?= SITE_ADDRESS ?>"><?= SITE_NAME ?></a></b><br/>
                                    <?php endif; ?>
                                    <br/>
                                    <span style="font-size:9pt;color:#FFF188;font-family: verdana, arial, helvetica, sans-serif"><?=date('l F j, Y')?></span>

                                </td>
                            </tr>
                        </table>
                        <img style="display:block;border:none" src="<?= SITE_ADDRESS ?>images/divider-600x31-2.gif" alt="" class="block" border="0" height="31" width="600">

                    </td>
                </tr>
                <!-- ////////////////////////////////// END HEADER /////////////////////////////////////////////// -->
                <tr>
                    <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" class="content" valign="top">

                        <table style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px; margin:0 auto;" align="center" cellpadding="0" cellspacing="0" width="540">
                            <tr>
                                <td style="color:#555555;font-family:Arial, sans-serif;font-size:11pt;line-height:22px" valign="top">
                                    <?= (isset($email_content)) ? $email_content : '[email_content]' ?>
                                </td>
                            </tr>
                        </table>

                    </td>
                </tr>
                <!-- ////////////////////////////////// START FOOTER ///////////////////////////////////////////// -->

                <tr>
                    <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" class="footer" valign="top">

                        <img style="display:block;border:none" src="<?= SITE_ADDRESS ?>images/divider-600x31-2.gif" alt="" class="block" border="0" height="31" width="600">
                        <table style="margin:0 auto;" align="center" bgcolor="#f0f0f0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" class="footer-content" valign="top">

                                    <table style="margin:0 auto;" align="center" cellpadding="0" cellspacing="0" width="540">
                                        <tr>
                                            <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:18px" valign="top" width="206">

                                                <span style="font-size:12px;line-height:18px;margin:0 0 14px 0;padding:0;color:#252525;font-weight:bold;"><br/>Peace & Prosperity,</span>

                                                <br><?=SITE_NAME?> Administration</td>
                                            <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" width="20">&nbsp;</td>
                                            <!-- spacer -->
                                            <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" valign="top" width="146">
                                            </td>
                                            <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" width="20">&nbsp;</td>
                                            <!-- spacer -->
                                            <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" valign="top" width="146">

                                                <span style="font-size:12px;line-height:18px;margin:0 0 14px 0;padding:0;color:#252525;font-weight:bold">
                                                    <br/>Find us on the web</span><br/>

                                                <?php if (isset($clickthru_link)): ?>
                                                    <a style="color:#3279BB;text-decoration:underline" href="<?= $clickthru_link.base64_encode(FACEBOOK_LINK) ?>"><img style="border:none" src="<?= SITE_ADDRESS ?>images/facebook.png" alt="facebook" title="Like <?=SITE_NAME?> on Facebook" border="0" height="28" width="28"></a>
                                                <?php else: ?>
                                                    <a style="color:#3279BB;text-decoration:underline" href="<?=FACEBOOK_LINK?>"><img style="border:none" src="<?= SITE_ADDRESS ?>images/facebook.png" alt="facebook" title="Like <?= SITE_NAME ?> on Facebook" border="0" height="28" width="28"></a>
                                                <?php endif; ?>
                                                <br/>
                                            </td>
                                        </tr>
                                    </table>

                                    <img style="display:block;border:none" src="<?= SITE_ADDRESS ?>images/footer-divider-600x31.gif" alt="" class="block" border="0" height="31" width="600">

                                    <!-- company info + subscription -->
                                    <table align="center" cellpadding="0" cellspacing="0" width="540">
                                        <tr>
                                            <td style="color:#555555;font-family:Arial, sans-serif;font-size:12px;line-height:22px" align="center" valign="top">
                                                <b style="font-weight:bold"><?=SITE_NAME?></b>
                                                <br>
                                                <?php if (isset($unsubscribe_link)) { ?>
                                                    Rather Not Receive Messages?
                                                    <a style="color:#3279BB;text-decoration:underline" href="<?= $unsubscribe_link ?>">Unsubscribe Here</a>
                                                <?php } else { ?>
                                                    Rather Not Receive Messages?<br/>
                                                    Change your Email Settings in your <a style="color:#3279BB;text-decoration:underline" href="<?=SITE_ADDRESS?>back_office.html">Back Office</a>

                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- end company info + subscription -->

                                    <img style="display:block;border:none" src="<?= SITE_ADDRESS ?>images/footer-divider-600x31-2.gif" alt="" class="block" border="0" height="31" width="600">

                                </td>
                            </tr>

                            <!-- ////////////////////////////////// END FOOTER /////////////////////////////////////////////// -->

                        </table>


                    </td>
                </tr>
            </table>

            <!-- ////////////////////////////////// END MAIN CONTENT WRAP //////////////////////////////////// -->

            <br><br><br>

            <!-- ///////////////////////////////////// END NEWSLETTER CONTENT  /////////////////////////////// -->
        </td>
    </tr>
</table>
<!-- END WRAPPER TABLE -->

</body>
</html>
