<a class="css-tile js-tile" <?php echo isset($redirectLink) ? 'href="' . $redirectLink . '"' : '';?>>
    <div class="css-tileInfo"><?php echo $tileInfo ?? '' ?></div>
    <input type="checkbox" name="<?php echo $checkboxName ?? ''; ?>" value="<?php echo $checkboxValue ?? ''; ?>" class="<?php echo $checkboxClass ?? ''; ?>">
    <div class="css-circleDetail"><?php echo $detailText ?? ''; ?></div>
    <img class="css-roundedPicture" src="<?php echo $imageSrc ?? '' ?>">
    <h2><?php echo $headerText ?? '' ?></h2>
    <p><?php echo $descriptionText ?? '' ?></p>     
</a>

