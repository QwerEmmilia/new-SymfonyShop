document.querySelector('.newsletter-form').addEventListener('submit', function(event) {
    let maleSubscription = document.querySelector('input[name="maleSubscription"]');
    let femaleSubscription = document.querySelector('input[name="femaleSubscription"]');
    if (!maleSubscription.checked && !femaleSubscription.checked) {
        event.preventDefault();
        maleSubscription.parentNode.style.color = 'red';
        femaleSubscription.parentNode.style.color = 'red';
    }
});