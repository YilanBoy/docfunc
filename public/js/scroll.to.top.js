//Get the button
let scrollButton = document.getElementById('scroll-btn');

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {
    scrollFunction();
};

function scrollFunction() {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        // mybutton.style.display = "block";
        scrollButton.classList.remove('d-none');
        scrollButton.classList.add('d-block');
    } else {
        // mybutton.style.display = "none";
        scrollButton.classList.add('d-none');
        scrollButton.classList.remove('d-block');
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
