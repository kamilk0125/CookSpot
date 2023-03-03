<div class="uploadPopup invisible">
    <div class="popupHeader">
      <div>Upload file</div>
      <button type="button">✗</button>
    </div>
    <div class="popupBody">
      <img class="imagePreview roundedPicture" src="resource?type=img&path=<?php echo $imagePreviewSrc ?? '' ?>" alt="Recipe Picture">
      <div>
        <div class="fileSelector">
            <label class="rounded buttonLabel" for="fileUploadInput">Choose file...</label>
            <input id="fileUploadInput" name="<?php echo $fileInputName ?? '' ?>" class="invisible" type="file" accept=".jpg, .jpeg, .png, .gif">
            <p>No file selected</p>
        </div>
      </div>
      <div>
        <button class="rounded disabled" type="button" disabled>Upload</button>
      </div>
    </div>
</div>
<div class="backgroundOverlay invisible"></div>