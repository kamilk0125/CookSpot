<div class="css-listTile css-selectionTile js-selectionTile css-flexHorizontal">
    <input type="checkbox" name="<?php echo $checkboxName ?? ''; ?>" value="<?php echo $checkboxValue ?? ''; ?>">
    <div class="css-flexHorizontal">    
        <img class="css-roundedPicture" src="<?php echo $imageSrc ?? ''; ?>" alt="">
        <a href="<?php echo $redirectLink ?? ''; ?>"><h2><?php echo $headerText ?? ''; ?></h2></a>
    </div>
</div>
