function toggleSortOptions() {
    var sortOptions = document.getElementById('sort-options');
    if (sortOptions.style.display === 'none' || sortOptions.style.display === '') {
        sortOptions.style.display = 'block';
    } else {
        sortOptions.style.display = 'none';
    }
}

document.addEventListener('click', function(event) {
    var target = event.target;
    var sortLabel = document.querySelector('.sort-label');
    var sortOptions = document.getElementById('sort-options');

    if (target !== sortLabel && !sortOptions.contains(target)) {
        sortOptions.style.display = 'none';
    }
});

function togglePriceOptions() {
    var priceOptions = document.getElementById('price-options');
    if (priceOptions.style.display === 'none' || priceOptions.style.display === '') {
        priceOptions.style.display = 'block';
    } else {
        priceOptions.style.display = 'none';
    }
}

document.addEventListener('click', function(event) {
    var target = event.target;
    var priceToggle = document.getElementById('price-toggle');
    var priceOptions = document.getElementById('price-options');

    if (target !== priceToggle && !priceOptions.contains(target)) {
        priceOptions.style.display = 'none';
    }
});

document.querySelector('.price-button').addEventListener('click', function(event) {
    event.preventDefault();
    let minPrice = document.querySelector('#min-price').value;
    let maxPrice = document.querySelector('#max-price').value;
    let url = '/list';
    if (minPrice || maxPrice) {
        url += '?';
        if (minPrice) {
            url += `min_price=${minPrice}`;
            if (maxPrice) {
                url += '&';
            }
        }
        if (maxPrice) {
            url += `max_price=${maxPrice}`;
        }
    }
    fetch(url)
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, 'text/html');
            let newFrame = doc.querySelector('turbo-frame#goods-list-turbo-1');
            let oldFrame = document.querySelector('turbo-frame#goods-list-turbo-1');
            oldFrame.outerHTML = newFrame.outerHTML;
        });
});
