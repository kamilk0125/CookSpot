const addPictureBtn = document.querySelector('button.addPictureBtn');
const profilePicture = document.getElementById('profilePicture')

addPictureBtn.addEventListener('click', openUploadPopup);

function openUploadPopup(e) {
    let uploadPopup = document.querySelector('div.uploadPopup');
    let backgroundOverlay = document.querySelector('div.backgroundOverlay');
    let uploadedFile = false;

    uploadPopup.classList.remove('invisible');
    backgroundOverlay.classList.remove('invisible');

    let closeBtn = uploadPopup.querySelector('div.popupHeader > button');
    let fileInput = uploadPopup.querySelector('div.fileSelector > input');
    closeBtn.addEventListener('click', e => {
        uploadPopup.classList.add('invisible');
        backgroundOverlay.classList.add('invisible');
        fileInput.value = '';
        if(uploadedFile){
            let dataTransfer = new DataTransfer();
            dataTransfer.items.add(uploadedFile);
            fileInput.files = dataTransfer.files;
        }
    });

    let uploadBtn = uploadPopup.querySelector('div.popupBody > div > button');
    let preselectedImage = uploadPopup.querySelector('div.popupBody > img');

    uploadBtn.addEventListener('click', e => {
        profilePicture.setAttribute('src', preselectedImage.getAttribute('src'));
        uploadPopup.classList.add('invisible');
        backgroundOverlay.classList.add('invisible');
        uploadedFile = fileInput.files[0];
    });

}
