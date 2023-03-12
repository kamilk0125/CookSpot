<div class="css-listTileExp js-listTileExp">
    <div class="css-listTile css-listTileHeader css-flexHorizontal css-expanded js-listTileHeader js-expanded">
        <div class="css-deleteBtnDiv css-flexHorizontal"><button class="css-deleteButton css-invisible js-deleteBtn js-editElement" type="button">✗</button></div>
        <div class="css-expandBtnDiv css-flexHorizontal"><button type="button" class="css-expandButton js-expandButton">︿</button></div>
        <textarea class="js-editElement" name="<?php echo $headerName ?? ''; ?>" cols="30" rows="1" spellcheck="false" maxlength="80" disabled><?php echo $headerText ?? ''; ?></textarea>
    </div>
    <div class="css-listTile css-listTileDetails js-listTileDetails">
        <textarea class="js-editElement" name="<?php echo $descriptionName ?? ''; ?>" cols="30" rows="1" spellcheck="false" maxlength="300" disabled><?php echo $descriptionText ?? ''; ?></textarea>
    </div>
</div>