<div class="listTile selectionTile flexHorizontal">
    <input type="checkbox" name="<?php echo $checkboxName ?? ''; ?>" value="<?php echo $checkboxValue ?? ''; ?>">
    <div class="flexHorizontal">    
        <img class="roundedPicture" src="<?php echo $imageSrc ?? ''; ?>" alt="">
        <a href="<?php echo $redirectLink ?? ''; ?>"><h2><?php echo $headerText ?? ''; ?></h2></a>
    </div>
</div>
