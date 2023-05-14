<br>
<div>
    <h2>Your account was created!</h2>
    <p>To activate your account click below:</p>
    <?php $link = "{$_ENV['FULL_DOMAIN_NAME']}/confirmation?view=activate&id={$args['id']}&hash={$args['activationHash']}"; ?>
    <a href="<?php echo $link; ?>">
        <button class="css-rounded"><h3>Activate</h3></button>
    </a>
    <br>
    <p>Activation link is valid for 24 hours.</p>
</div>