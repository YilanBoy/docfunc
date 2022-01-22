const progressBar = <HTMLDivElement>document.querySelector('#progress-bar');
const section = <HTMLDivElement>document.querySelector('#section');

const animateProgressBar = () => {
    let scrollDistance = -section.getBoundingClientRect().top;
    let progressWidth =
        (scrollDistance / (section.getBoundingClientRect().height - document.documentElement.clientHeight)) * 100;
    console.log(progressWidth);
    let value = Math.floor(progressWidth);

    progressBar.style.width = value + '%';

    if (value < 0) {
        progressBar.style.width = '0%';
    }
}

if (document.documentElement.clientHeight > section.getBoundingClientRect().height) {
    progressBar.style.width = '100%';
} else {
    window.addEventListener('scroll', animateProgressBar);
}

