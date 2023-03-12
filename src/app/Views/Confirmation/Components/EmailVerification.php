<div class="css-centered">
   <h1><?php echo $this->verified ? 'Your email was verified!' : 'Email verification failed'; ?></h1>
   <p><?php echo $this->verified ? 'Account settings were updated' : 'Verification link is invalid'; ?></p>
   <a href="/">
      <button class="css-rounded">Main Page</button>
   </a>
</div>