//////////////////////ADD DISH POPUP ////////////////////////////
const popup = document.getElementById('popup');
const openPopupButton = document.querySelector('.open-popup');

function toggleMenu() {
    popup.classList.toggle('show');
}

openPopupButton.addEventListener('click', () => {
    popup.classList.add('show');
});

window.addEventListener('click', (event) => {
    // Close popup when clicking outside
    if (!popup.contains(event.target) && !event.target.matches('.open-popup')) {
        popup.classList.remove('show');
    }
});

// Prevent click events inside popup-content from propagating to the window
const popupContent = document.querySelector('.popup-content');
popupContent.addEventListener('click', (event) => {
    event.stopPropagation();
});


////////////////////// END OF ADD DISH POPUP ////////////////////////////

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


