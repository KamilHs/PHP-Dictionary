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

    newCurrent == $('.tab').length - 1 ? $('.next').html('Submit') : $('.next').html('Next');
}

const paginationBtnClick = n => {
    if (currentTab + n >= $('.tab').length) {
        $("#quiz").trigger('submit');
        return false;
    }
    flagsHandler(currentTab + n);
}

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


$('#quiz').on('keyup keypress', function (e) {
    let keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

function flagsHandler(i) {
    if (currentTab != i) {
        const curInput = $($('.tab')[currentTab]).find('input')[0];

        if (!curInput.value) {
            $($('.flag')[currentTab]).addClass('danger');
            $($('.flag')[currentTab]).removeClass('answered');
        }
        else {
            $($('.flag')[currentTab]).removeClass('danger');
            $($('.flag')[currentTab]).addClass('answered');
        }
        $($('.flag')[currentTab]).removeClass('current')
        $($('.flag')[i]).addClass('current')
        $($('.flag')[i]).removeClass('unvisited');
        showTab(i);
    }
}


showTab(currentTab);
