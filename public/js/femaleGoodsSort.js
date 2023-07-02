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

function getQueryParam(name) {
    let params = new URLSearchParams(window.location.search);
    return params.get(name);
}

function updateSortOptions() {
    let sortOption = getQueryParam('sort');
    if (sortOption) {
        document.querySelector(`input[name="sort-option"][value="${sortOption}"]`).checked = true;
    } else {
        document.querySelector('input[name="sort-option"][value="default"]').checked = true;
    }
}

updateSortOptions();


function getTypeFromPath() {
    let path = window.location.pathname;
    let parts = path.split('/');
    if (parts.length >= 3) {
        return parts[2];
    }
    return null;
}

function applySorting() {
    let minPrice = getQueryParam('min_price');
    let maxPrice = getQueryParam('max_price');
    let sortOption = document.querySelector('input[name="sort-option"]:checked').value;
    let typeOption = getTypeFromPath();
    let url = '/female';
    if (typeOption) {
        url += '/' + typeOption;
    }
    let params = [];
    if (minPrice) {
        params.push(`min_price=${minPrice}`);
    }
    if (maxPrice) {
        params.push(`max_price=${maxPrice}`);
    }
    if (sortOption !== 'default') {
        params.push(`sort=${sortOption}`);
    }
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    window.location.href = url;
}



document.querySelector('.price-button').closest('form').addEventListener('submit', function(event) {
    event.preventDefault();
    let minPrice = document.querySelector('#min-price').value;
    let maxPrice = document.querySelector('#max-price').value;
    let sortOption = getQueryParam('sort');
    let typeOption = getTypeFromPath();
    let url = '/female';
    if (typeOption) {
        url += '/' + typeOption;
    }
    let params = [];
    if (minPrice) {
        params.push(`min_price=${minPrice}`);
    }
    if (maxPrice) {
        params.push(`max_price=${maxPrice}`);
    }
    if (sortOption && sortOption !== 'default') {
        params.push(`sort=${sortOption}`);
    }
    if (params.length > 0) {
        url += '?' + params.join('&');
    }
    window.location.href = url;
});


document.querySelectorAll('.filter-list a').forEach(function(link) {
    link.addEventListener('click', function(event) {
        event.preventDefault();
        let minPrice = getQueryParam('min_price');
        let maxPrice = getQueryParam('max_price');
        let sortOption = document.querySelector('input[name="sort-option"]:checked').value;
        let url = link.getAttribute('href');
        let params = [];
        if (minPrice) {
            params.push(`min_price=${minPrice}`);
        }
        if (maxPrice) {
            params.push(`max_price=${maxPrice}`);
        }
        if (sortOption !== 'default') {
            params.push(`sort=${sortOption}`);
        }
        if (params.length > 0) {
            url += '?' + params.join('&');
        }
        window.location.href = url;
    });
});


