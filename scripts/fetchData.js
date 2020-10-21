let btn = document.querySelector('#load-more');
let max;
let limit = 10;
let start = 0;
let isRunning = false;
let optQuery = "";

let isNoRecord = btn == null;

if (!isNoRecord) {



    function fetchData(e, optQry = "") {
        if (isRunning) return;
        let qry = `load-more.php?start=${start}&limit=${limit}`;
        if (optQry) qry += '&' + optQry;
        else if (optQuery) qry += '&' + optQuery;

        btn.querySelector('.spinner-border').classList.remove('d-none');
        btn.setAttribute('disabled', 'true');
        btn.querySelector('.value').innerText = "Loading...";
        isRunning = true;

        const ajax = new XMLHttpRequest();
        ajax.open("GET", qry, true);
        ajax.send();
        ajax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                let data = JSON.parse(this.response);
                start += data.length;

                if (start >= max) btn.classList.add('d-none');
                addToTable(data);
                isRunning = false;

                btn.querySelector('.spinner-border').classList.add('d-none');
                btn.removeAttribute('disabled');
                btn.querySelector('.value').innerText = "Load More";
            }
        }
    }

    function getMax(fetchData, optQry = "") {
        let qry = 'load-more.php?max=true';
        if (optQry) qry += "&" + optQry;
        optQuery = optQry;

        const ajax = new XMLHttpRequest();
        ajax.open("GET", qry, true);
        ajax.send();
        ajax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                max = JSON.parse(this.response);
                document.querySelector('.count').innerText = "Total: " + max;
                reset();
                fetchData(MouseEvent, optQry);
            }
        }
    }

    let offset = 0;
    function addToTable(data) {
        const table = document.querySelector('table');
        const parent = table.querySelector('tbody')
        data.forEach(row => {
            const tableRow = ` 
        <tr>
            <td class="text-center">${++offset}</td>
            <td>${row.word}</td>
            <td>${row.trns}</td>
            <td><a href="translation.php?wordId=${row.id}">View</a></td>
            <td><a href="edit-word.php?wordId=${row.id}">Edit</a></td>
            <td><a href="delete-word.php?wordId=${row.id}">Delete</a></td>
        </tr>`

            parent.innerHTML += tableRow;
        });
        table.scrollTop = table.scrollHeight;
    }





    function reset() {
        const table = document.querySelector('table');
        table.removeChild(table.querySelector('tbody'));

        const tbody = document.createElement('tbody');
        table.appendChild(tbody);

        btn.classList.remove('d-none');

        offset = 0;
        start = 0;
    }

    getMax(fetchData, "order=createdAt&way=DESC");
}
