const descriptionButtons = [...document.querySelectorAll(".description-button")];
const Descriptions = [...document.querySelectorAll(".description-cars")];

descriptionButtons.forEach((button) => {
    button.addEventListener("click", () => {
        Descriptions.forEach((description) => {
            if (description.parentNode.childNodes[9] == button) {
                if (!(description.style[0] == "max-height")) {
                    description.classList.add("description-cars-show-text");
                    description.style.maxHeight = description.scrollHeight + "px";
                    console.log(description.style);
                } else {
                    description.classList.remove("description-cars-show-text");
                    description.style.maxHeight = "0px";
                    description.style.removeProperty("max-Height");
                }
            }
        })
    })
})