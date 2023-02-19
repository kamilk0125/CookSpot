<form action="<?php echo $formTarget ?? '';?>" method="POST">
    <input class="invisible" type="text" name="action" value="<?php echo $formHandler ?? '';?>">
    <?php 
        foreach($formArgs ?? [] as $argName => $value){
            echo "<input class='invisible' type='text' name='{$argName}' value='{$value}'>";
        }
    ?>
    <div class="listTile flexHorizontal">
        <div class="flexHorizontal">    
            <img class="roundedPicture" src="<?php echo $imageSrc ?? ''; ?>" alt="">
            <h2><?php echo $headerText ?? ''; ?></h2>
        </div>
        <div>
            <button 
                class="left squared <?php echo $leftBtnClass ?? '';?>" 
                type="<?php echo $leftBtnType ?? 'submit'; ?>" 
                name="<?php echo $leftBtnName ?? '';?> " 
                value="<?php echo $leftBtnValue ?? '';?>" 
                <?php echo ($leftBtnDisabled ?? false) ? 'disabled' : '';?>
            >
                <?php echo $leftBtnText ?? ''; ?>
            </button>
            <button 
                class="right squared <?php echo $rightBtnClass ?? '';?>" 
                type="<?php echo $rightBtnType ?? 'submit'; ?>" 
                name="<?php echo $rightBtnName ?? '';?>" 
                value="<?php echo $rightBtnValue ?? '';?>" 
                <?php echo ($rightBtnDisabled ?? false) ? 'disabled' : '';?>
            >
                <?php echo $rightBtnText ?? ''; ?>
            </button>
        </div>
    </div>
</form>