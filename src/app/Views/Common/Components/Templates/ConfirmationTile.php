<form action="<?php echo $formTarget ?? '';?>" method="POST">
    <input class="css-invisible" type="text" name="handler" value="<?php echo $formHandler ?? '';?>">
    <input class="css-invisible" type="text" name="action" value="<?php echo $formAction ?? '';?>">
    <?php 
        foreach($formArgs ?? [] as $argName => $value){
            echo "<input class='css-invisible' type='text' name='{$argName}' value='{$value}'>";
        }
    ?>
    <div class="css-listTile css-flexHorizontal">
        <div class="css-flexHorizontal">    
            <img class="css-roundedPicture" src="<?php echo $imageSrc ?? ''; ?>" alt="">
            <a <?php echo isset($redirectLink) ? 'href="' . $redirectLink . '"' : '';?>><h2><?php echo $headerText ?? ''; ?></h2></a>
        </div>
        <div>
            <button 
                class="css-left css-squared <?php echo $leftBtnClass ?? '';?>" 
                type="<?php echo $leftBtnType ?? 'submit'; ?>" 
                name="<?php echo $leftBtnName ?? '';?> " 
                value="<?php echo $leftBtnValue ?? '';?>" 
                <?php echo ($leftBtnDisabled ?? false) ? 'disabled' : '';?>>
                <?php echo $leftBtnText ?? ''; ?>
            </button>
            <button 
                class="css-right css-squared <?php echo $rightBtnClass ?? '';?>" 
                type="<?php echo $rightBtnType ?? 'submit'; ?>" 
                name="<?php echo $rightBtnName ?? '';?>" 
                value="<?php echo $rightBtnValue ?? '';?>" 
                <?php echo ($rightBtnDisabled ?? false) ? 'disabled' : '';?>>
                <?php echo $rightBtnText ?? ''; ?>
            </button>
        </div>
    </div>
</form>