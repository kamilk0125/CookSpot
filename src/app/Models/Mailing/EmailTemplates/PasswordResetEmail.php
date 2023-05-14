<br>
<div>
    <h2>You requested to reset your password</h2>
    <p>To change your password click below:</p>
    <?php $link = "{$_ENV['FULL_DOMAIN_NAME']}/login?view=passwordReset&id={$args['id']}&hash={$args['verificationHash']}"; ?>
    <a href="<?php echo $link; ?>">
        <button class="css-rounded"><h3>Reset Password</h3></button>
    </a>
    <br>
    <p>Link is valid for 24 hours.</p>
</div>