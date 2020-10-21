const timer = document.querySelector("#timer");
let interval;

function startTimer() {
    let counter = 1;
    interval = setInterval(e => {
        timer.textContent = timeConverter(counter++);
    }, 1000);
}

function stopTimer() {
    clearInterval(interval);
}


function timeConverter(time) {
    let minutes = Math.floor(time / 60);
    let seconds = time % 60;
    minutes = checkingDigits(minutes);
    seconds = checkingDigits(seconds);
    return `${minutes} : ${seconds}`
}

function checkingDigits(number) {
    if (number < 10) {
        return '0' + number;
    }
    return number;
}

startTimer();