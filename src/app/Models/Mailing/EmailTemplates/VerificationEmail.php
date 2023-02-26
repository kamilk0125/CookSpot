<br>
<div>
    <p>To verify your email use this link:</p>
    <a href="localhost:8000/confirmation?view=verify&id=<?php echo $args['id'] ?? '' ?>&hash=<?php echo $args['verificationHash'] ?? '' ?>">localhost:8000/confirmation?view=verify&id=<?php echo $args['id'] ?? '' ?>&hash=<?php echo $args['verificationHash'] ?? '' ?></a>
    <br>
    <p>Verification link is valid for 24 hours.</p>
</div>