<br>
<div>
    <h2>You requested to reset your password</h2>
    <p>To change your password use this link:</p>
    <?php $link = "{$_ENV['DOMAIN_NAME']}/login?view=passwordReset&id={$args['id']}&hash={$args['verificationHash']}"; ?>
    <a href="<?php echo $link; ?>"><?php echo $link; ?></a>
    <br>
    <p>Link is valid for 24 hours.</p>
</div>