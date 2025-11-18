
$(".tabli").click(function () {
    $(".tabli").removeClass("active")
    $(this).addClass("active")
    $(`.tabbody`).hide();
    $(`#${$(this).data("tab")}`).show();
})



const swiper = new Swiper('.swiper-container', {
    slidesPerView: 3,
    spaceBetween: 10,  
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    loop: true,
    autoplay: {
        delay: 3000,
    },

});
const swiper2 = new Swiper('.swiper-containers', {
    slidesPerView: 2, 
    spaceBetween: 10,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        0: { slidesPerView: 1 },
        768: { slidesPerView: 2 }, 
        1024: { slidesPerView: 3 }, 
    },
    loop: true,
    autoplay: {
        delay: 3000,
    },
});
const swiper3 = new Swiper('.swiper-container3', {
    slidesPerView: 2, 
    spaceBetween: 10, 
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        0: { slidesPerView: 1 }, 
        768: { slidesPerView: 2 }, 
        1024: { slidesPerView: 3 }, 
    },
    loop: true,
    autoplay: {
        delay: 3000,
    },
});
const swiper4 = new Swiper('.swiper-container4', {
    slidesPerView: 2, 
    spaceBetween: 10, 
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        0: { slidesPerView: 1 }, 
        768: { slidesPerView: 2 }, 
        1024: { slidesPerView: 3 }, 
    },
    loop: true,
    autoplay: {
        delay: 3000,
    },
});

function openTab(event, tabName) {
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });

    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active');
    });

    document.getElementById(tabName).classList.add('active');
    event.currentTarget.classList.add('active');
}

const img = document.querySelectorAll(".img");

img.forEach(button => {
    buttons.addEventListener('click', () => {
        document.querySelector('.active').classList.remove('active');
        buttons.classList.add('active');
    });
});

function toggleAccordion(id) {
    const contents = document.querySelectorAll('.accordion-content');
    const icons = document.querySelectorAll('[id^="icon-accordion"]');

    contents.forEach(content => {
        if (content.id === id) {
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        } else {
            content.style.maxHeight = null;
        }
    });

    icons.forEach(icon => {
        if (icon.id === `icon-${id}`) {
            icon.classList.toggle("rotate-180");
        } else {
            icon.classList.remove("rotate-180");
        }
    });
}
function toggleAccordion(id) {
    const content = document.getElementById(id);
    const path1 = document.getElementById("icon-path1-" + id);
    const path2 = document.getElementById("icon-path2-" + id);
    const path3 = document.getElementById("icon-path3-" + id);

    if (content.style.maxHeight) {
        content.style.maxHeight = null;
        path1.style.display = "";
        path2.style.display = "";
        path3.style.display = "";
    } else {
        content.style.maxHeight = content.scrollHeight + "px";
        path1.style.display = "";
        path2.style.display = "none";
        path3.style.display = "";
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const swiper = new Swiper(".swiper-container", {
        loop: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
});

const phoneInputField = document.querySelector("#phone");
const phoneInput = window.intlTelInput(phoneInputField, {
  initialCountry: "ae", 
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});
const phoneInputFields = document.querySelector("#phone1");
const phoneInputs = window.intlTelInput(phoneInputFields, {
  initialCountry: "ae", 
  utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});







