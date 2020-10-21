let currentTab = 0;

const showTab = (newCurrent) => {
    $($('.tab')[currentTab]).css('display', 'none');
    currentTab = newCurrent;
    $($('.tab')[currentTab]).css('display', 'block');

    if (currentTab == 0)
        $('.prev').css({
            'visibility': 'hidden',
            'pointer-events': 'none'
        });
    else
        $('.prev').css({
            'visibility': 'visible',
            'pointer-events': 'all'
        });
    if (currentTab + 1 == $('.tab').length)
        $('.next').css({
            'visibility': 'hidden',
            'pointer-events': 'none'
        });
    else
        $('.next').css({
            'visibility': 'visible',
            'pointer-events': 'all'
        });
}

const paginationBtnClick = n => flagsHandler(currentTab + n);

$('.navigation-btn').each((index, button) => {
    $(button).click(function (e) {
        e.preventDefault();
        index == 0 ? paginationBtnClick(-1) : paginationBtnClick(1);
    })
})

$('.flag').each((i, flag) => {
    $(flag).click(function (e) {
        flagsHandler(i);
    })
})

$('form').on('keyup keypress', function (e) {
    let keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

function flagsHandler(i) {
    if (currentTab != i) {
        $($('.flag')[currentTab]).removeClass('current')
        $($('.flag')[i]).addClass('current')
        showTab(i);
    }
}

showTab(currentTab);
