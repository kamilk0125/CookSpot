<br>
<div>
    <h2>Your account was created!</h2>
    <p>To activate your account use this link:</p>
    <?php $link = "{$_ENV['DOMAIN_NAME']}/confirmation?view=activate&id={$args['id']}&hash={$args['activationHash']}"; ?>
    <a href="<?php echo $link; ?>"><?php echo $link; ?></a>
    <br>
    <p>Activation link is valid for 24 hours, when it expires the account will be removed.</p>
</div>