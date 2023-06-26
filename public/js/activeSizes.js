function addSizePillEventListeners() {
    const sizePills = document.querySelectorAll('.size-pill');

    sizePills.forEach(sizePill => {
        sizePill.addEventListener('click', () => {
            sizePills.forEach(pill => {
                pill.classList.remove('active');
                pill.style.border = '1px solid #ccc';
                pill.style.background = 'none';
                pill.style.color = 'black';
            });

            sizePill.classList.add('active');
            sizePill.style.border = '1px solid black';
            sizePill.style.background = 'black';
            sizePill.style.color = 'white';

            const selectedSizeId = sizePill.getAttribute('data-size-id');
            document.getElementById('size_id').value = selectedSizeId;
        });
    });

    // Restore selected size_id if available
    const selectedSizeId = document.getElementById('size_id').value;
    if (selectedSizeId) {
        const selectedSizePill = document.querySelector(`.size-pill[data-size-id="${selectedSizeId}"]`);
        if (selectedSizePill) {
            selectedSizePill.classList.add('active');
            selectedSizePill.style.border = '1px solid black';
            selectedSizePill.style.background = 'black';
            selectedSizePill.style.color = 'white';
        }
    }
}


function validateSizeSelection() {
    let isSizeSelected = false;

    const sizePills = document.querySelectorAll('.size-pill');

    sizePills.forEach(pill => {
        if (pill.classList.contains('active')) {
            isSizeSelected = true;
        }
    });

    sizePills.forEach(pill => {
        if (!isSizeSelected) {
            pill.classList.add('shake');
        } else {
            pill.classList.remove('shake');
        }
    });

    const selectedSize = document.querySelector('.size-pill.active');
    return !(!selectedSize && !isSizeSelected);
}