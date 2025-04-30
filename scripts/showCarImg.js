document.addEventListener('DOMContentLoaded', () => {
    const carSelect = document.getElementById('selected-car');
    const carImage = document.getElementById('car-image');

    const updateImage = () => {
        const selectedOption = carSelect.options[carSelect.selectedIndex];
        const imagePath = selectedOption.dataset.img;

        if (imagePath) {
            carImage.src = imagePath;
        }
    };
    updateImage();

    carSelect.addEventListener('change', updateImage);
});