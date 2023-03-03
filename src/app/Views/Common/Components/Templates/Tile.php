<a class="Tile" href="<?php echo $redirectLink ?? '' ?>">
    <div class="ownerInfo"><?php echo $ownerInfo ?? '' ?></div>
    <input type="checkbox" name="<?php echo $checkboxName ?? ''; ?>" value="<?php echo $checkboxValue ?? ''; ?>" class="<?php echo $checkboxClass ?? ''; ?>">
    <div class="circleDetail"><?php echo $detailText ?? ''; ?></div>
    <img class="roundedPicture" src="<?php echo $imageSrc ?? '' ?>">
    <h2><?php echo $headerText ?? '' ?></h2>
    <p><?php echo $descriptionText ?? '' ?></p>     
</a>

