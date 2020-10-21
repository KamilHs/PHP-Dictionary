let filters = [document.querySelector('input#latest')];
let filterQuery = "order=createdAt&way=DESC";

if (!isNoRecord) {

    document.getElementById('filters').addEventListener('submit', function (e) {
        filters = [];
        e.preventDefault();

        filterQuery = "";
        this.querySelectorAll('input').forEach(input => {
            if (input.type == 'radio' && input.checked) filters.push(input);
            if (input.type == 'date' && input.value) filters.push(input);
        })


        filters.forEach((filter, i) => {
            if (filter.type == 'radio') filterQuery += filter.name + '=' + filter.dataset.clause + "&way=" + filter.dataset.order;
            else if (filter.type == 'date') filterQuery += filter.name + '=' + filter.value;
            if (i + 1 < filters.length) filterQuery += "&";
        })
        $('.modal').modal('hide');

        getMax(fetchData, filterQuery);
    })

    document.querySelector('.search-bar').addEventListener('submit', function (e) {
        e.preventDefault();
        reset();
        let query = this.querySelector('input').value;
        getMax(fetchData, `like=${query}&${filterQuery}`);
    })


    $('.modal').on('hidden.bs.modal', function (e) {
        $('.modal form input').each((i, input) => {
            if (!filters.includes(input)) {
                if (input.type == 'radio' || input.type == 'checkbox') {
                    $(input).prop('checked', false);
                }
            }
            else {
                $(input).prop('checked', true);
            }
        })
    })
}
