<div>
    <button 
    id="friendsBtn"
    form="friendsForm" 
    name="<?php echo $this->friendsForm['friendsBtnName'] ?? '';?>" 
    type="submit" 
    value="<?php echo $this->friendsForm['friendsBtnValue'] ?? '';?>" 
    class="css-rounded <?php echo $this->friendsForm['friendsBtnClass'] ?? '';?>" 
    <?php echo $this->friendsForm['friendsBtnDisabled'] ? 'disabled' : '';?>
    ><?php echo $this->friendsForm['friendsBtnText'] ?? '';?>
    </button>
    <button id="deleteFriendBtn" 
    form="deleteFriendForm" 
    name="args[friendId]" 
    type="submit" 
    value="<?php echo $this->friendsForm['deleteBtnValue'] ?? '';?>" 
    class="css-rounded css-red <?php echo $this->friendsForm['deleteBtnVisible'] ? '' : 'css-invisible';?>" 
    >Delete from friends list
    </button>    
</div>
<form id="friendsForm" action="" method="POST">
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