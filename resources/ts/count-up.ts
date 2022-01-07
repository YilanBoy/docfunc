// select all elements with class="count-up"
const countUps = document.querySelectorAll('.count-up');

countUps.forEach(countUp => {
    let from = 0;
    let to = Number(countUp.textContent);
    let interval = 1000 * (1 / to);

    if (from === to) {
        return;
    }

    let counter = setInterval(() => {
        countUp.textContent = String(from);

        if (from === to) {
            clearInterval(counter);
        }

        from++;
    }, interval);
});
