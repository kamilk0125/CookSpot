<div class="css-uploadPopup css-centered js-imageUploadPopup css-invisible">
    <div class="css-popupHeader js-popupHeader">
      <div>Upload file</div>
      <button type="button">✗</button>
    </div>
    <div class="css-popupBody js-popupBody">
      <img class="css-imagePreview css-roundedPicture js-imagePreview" src="resource?type=img&path=<?php echo $imagePreviewSrc ?? '' ?>" alt="Recipe Picture">
      <div>
        <div class="css-fileSelector js-fileSelector">
            <label class="css-rounded css-buttonLabel" for="fileUploadInput">Choose file...</label>
            <input id="fileUploadInput" name="<?php echo $fileInputName ?? '' ?>" class="css-invisible" type="file" accept=".jpg, .jpeg, .png, .gif">
            <p>No file selected</p>
        </div>
      </div>
      <div>
        <button class="css-rounded css-disabled" type="button" disabled>Upload</button>
      </div>
    </div>
</div>
<div class="css-backgroundOverlay js-backgroundOverlay css-invisible"></div>