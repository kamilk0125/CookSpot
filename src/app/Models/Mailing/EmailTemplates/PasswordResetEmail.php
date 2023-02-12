<br>
<div>
    <h2>You requested to reset your password</h2>
    <p>To change your password use this link:</p>
    <a href="localhost:8000/login?view=passwordReset&id=<?php echo $args['id'] ?? '' ?>&hash=<?php echo $args['verificationHash'] ?? '' ?>">localhost:8000/login?view=passwordReset&id=<?php echo $args['id'] ?? '' ?>&hash=<?php echo $args['verificationHash'] ?? '' ?></a>
    <br>
    <p>Link is valid for 24 hours.</p>
</div>