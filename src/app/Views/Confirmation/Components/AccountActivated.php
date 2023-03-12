<div class="css-centered">
   <h1><?php echo $this->activated ? 'Your account was activated!' : 'Account not found'; ?></h1>
   <p><?php echo $this->activated ? 'You can login now' : 'Account you are trying to activate does not exist or has already been activated'; ?></p>
   <a href="login">
      <button class="css-rounded">Login Page</button>
   </a>
</div>