document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-gallery-thumb]").forEach((thumb) => {
        thumb.addEventListener("click", () => {
            const target = document.querySelector(thumb.dataset.galleryThumb);
            if (target) {
                target.src = thumb.src;
            }
        });
    });

    document.querySelectorAll("[data-same-address]").forEach((checkbox) => {
        checkbox.addEventListener("change", () => {
            document.querySelectorAll("[data-shipping-field]").forEach((field) => {
                field.closest(".shipping-field").classList.toggle("d-none", checkbox.checked);
            });
        });
    });

    document.querySelectorAll("[data-add-variant-row]").forEach((button) => {
        button.addEventListener("click", () => {
            const target = document.querySelector(button.dataset.addVariantRow);
            const template = document.querySelector("#variant-row-template");
            if (target && template) {
                target.insertAdjacentHTML("beforeend", template.innerHTML);
            }
        });
    });
});
