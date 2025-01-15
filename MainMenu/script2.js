//////////////////////ADD DISH POPUP ////////////////////////////

////////////////////// END OF ADD DISH POPUP ////////////////////////////


////////////////////// MANAGE TABLE POPUP ////////////////////////////


















////////////////////// END OFMANAGE TABLE POPUP ////////////////////////////





function showPopup(popupId) {
    document.getElementById(popupId).style.display = "flex";
}

function closePopup(event, popupId) {
    if (event.target.id === popupId) {
        document.getElementById(popupId).style.display = "none";
    }
}

const fileInput = document.getElementById('dish_image');
const fileNameDisplay = document.getElementById('file-name');

fileInput.addEventListener('change', function () {
    const fileName = this.files[0]?.name || "No file chosen";
    fileNameDisplay.textContent = fileName;
});



//price input limited to 2 decimal places
const priceInput = document.getElementById('dish_price');

priceInput.addEventListener('input', function () {
    const value = this.value;

    if (value.includes('.') && value.split('.')[1].length > 2) {
        // Truncate to 2 decimal places
        this.value = parseFloat(value).toFixed(2);
    }
});


