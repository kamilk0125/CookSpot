<br>
<div>
    <p>To verify your email use this link:</p>
    <?php $link = "{$_ENV['FULL_DOMAIN_NAME']}/confirmation?view=verify&id={$args['id']}&hash={$args['verificationHash']}"; ?>
    <a href="<?php echo $link; ?>">
        <button class="css-rounded"><h3>Verify</h3></button>
    </a>
    <br>
    <p>Verification link is valid for 24 hours.</p>
</div>