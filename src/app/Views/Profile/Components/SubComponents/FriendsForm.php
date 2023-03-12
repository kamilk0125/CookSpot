<div>
    <button 
    id="friendsBtn"
    form="friendsForm" 
    name="<?php echo $this->friendsForm['btnName'] ?? '';?>" 
    type="submit" 
    value="<?php echo $this->friendsForm['btnValue'] ?? '';?>" 
    class="css-rounded <?php echo $this->friendsForm['btnClass'] ?? '';?>" 
    <?php echo $this->friendsForm['btnDisabled'] ? 'disabled' : '';?>
    ><?php echo $this->friendsForm['btnText'];?>
    </button>
    <button id="deleteFriendBtn" 
    form="deleteFriendForm" 
    name="args[friendId]" 
    type="submit" 
    value="<?php echo $this->profileData['profileInfo']['id'] ?>" 
    class="css-rounded css-red <?php echo ($this->relationStatus === 'friend') ? '' : 'css-invisible';?>" 
    >Delete from friends list
    </button>    
</div>
<form id="friendsForm" action="friends" method="POST">
    <input class="css-invisible" type="text" name="handler" value="friendsHandler">
    <input class="css-invisible" type="text" name="action" value="<?php echo $this->friendsForm['action'] ?? '';?>">
    <?php 
        foreach(($this->friendsForm['args'] ?? []) as $argName => $value){
            echo "<input class='css-invisible' type='text' name='{$argName}' value='{$value}'>";
        }
    ?>
</form>
<form id="deleteFriendForm" action="" method="POST">
    <input class="css-invisible" type="text" name="handler" value="friendsHandler">
    <input class="css-invisible" type="text" name="action" value="deleteFriend">
</form>