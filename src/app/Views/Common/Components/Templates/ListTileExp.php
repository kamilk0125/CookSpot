<div class="listTileExp">
    <div class="listTile listTileHeader flexHorizontal expanded">
        <div class="deleteButton flexHorizontal editElement invisible"><button type="button">✗</button></div>  
        <div><button type="button" class="listTile expandButton">︿</button></div>
        <textarea class="editElement" name="<?php echo $headerName ?? ''; ?>" cols="30" rows="1" spellcheck="false" maxlength="80" disabled><?php echo $headerText ?? ''; ?></textarea>
    </div>
    <div class="listTile listTileDetails">
        <textarea class="editElement" name="<?php echo $descriptionName ?? ''; ?>" cols="30" rows="1" spellcheck="false" maxlength="300" disabled><?php echo $descriptionText ?? ''; ?></textarea>
    </div>
</div>