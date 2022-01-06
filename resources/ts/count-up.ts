// select all elements with class="count-up"
const countUps = document.querySelectorAll('.count-up');

countUps.forEach(countUP => {
    let from = 0;
    let to = Number(countUP.textContent);
    let interval = 1000 * (1 / to);

    if (from === to) {
        return;
    }

    let counter = setInterval(() => {
        from += 1;
        countUP.textContent = String(from);
        if (from === to) {
            clearInterval(counter);
        }
    }, interval);
});
