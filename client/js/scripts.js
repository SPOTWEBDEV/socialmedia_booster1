

(function ($) {
    "use strict"


    $(function () {
        for (
            var lc = window.location,
            o = $(".settings-menu a, .menu a")
                .filter(function () {
                    return this.href == lc
                })
                .addClass("active")
                .parent()
                .addClass("active");
            ;

        ) {

            if (!o.is("li")) break
            o = o.parent().addClass("show").parent().addClass("active")
        }
    })

    $('.content-body').css({ 'min-height': (($(window).height())) + 50 + 'px' })
})(jQuery);



window.addEventListener('load', function () {
    let onpageLoad = localStorage.getItem("theme") || "light";
    let element = document.body;

    if (onpageLoad) {
        element.classList.add(onpageLoad);
    }

    let themeElement = document.getElementById("theme");
    if (themeElement) {
        themeElement.textContent = onpageLoad;
    }
});

function themeToggle() {
    let element = document.body;
    element.classList.toggle("dark-theme");

    let theme = localStorage.getItem("theme");

    if (theme && theme === "dark-theme") {
        localStorage.setItem("theme", "");
    } else {
        localStorage.setItem("theme", "dark-theme");
    }
}

// document.querySelectorAll(".loading-btn").forEach(button => {
//     button.addEventListener("click", function () {

//         console.log("CLICK")

//         // store original text
//         let originalText = this.innerHTML;

//         // disable button
//         this.disabled = true;

//         // show loading spinner
//         this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';

//         let form = this.closest("form");
//         // if button is inside a form
        // if (form) {
        //     form.submit();
        // }

//         // restore text after process (optional)
//         setTimeout(() => {
//             this.disabled = false;
//             this.innerHTML = originalText;
//         }, 3000);

//     });
// });



// console.log("CLICK")

//         // store original text
//         let originalText = this.innerHTML;

//         // disable button
//         this.disabled = true;

//         // show loading spinner
//         this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
