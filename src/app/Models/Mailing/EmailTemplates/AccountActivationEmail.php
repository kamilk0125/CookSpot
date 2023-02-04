<br>
<div>
    <h2>Your account was created!</h2>
    <p>To activate your account use this link:</p>
    <a href="localhost:8000/login?activate=<?php echo $args['activationHash'] ?? '' ?>">localhost:8000/login?activate=<?php echo $args['activationHash'] ?? '' ?></a>
    <br>
    <p>Activation link is valid for 24 hours, when it expires the account will be removed.</p>
</div>