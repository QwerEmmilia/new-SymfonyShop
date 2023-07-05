var toggleButton = document.getElementById('toggle-filters');
var sidebar = document.getElementById('sidebar');
var content = document.getElementById('content');
var body = document.body;

var sidebarWidth = sidebar.offsetWidth;

toggleButton.addEventListener('click', function() {
    sidebar.classList.toggle('show');
    content.classList.toggle('slide');
    body.classList.toggle('sidebar-open');
    toggleButton.classList.toggle('active');

    if (sidebar.classList.contains('show')) {
        toggleButton.style.transform = 'translateX(' + sidebarWidth + 'px)';
        body.classList.add('no-scroll');
    } else {
        toggleButton.style.transform = 'translateX(0)';
        body.classList.remove('no-scroll');
    }
});

$(document).ready(function() {
    $('.filter-group .a-menu-filter').click(function(e) {
        e.preventDefault();

        var $filterGroup = $(this).closest('.filter-group');
        var $filterList = $(this).next('.filter-list');


        // Закрити всі відкриті списки, окрім поточного
        $filterGroup.find('.filter-list').not($filterList).slideUp();
        $filterGroup.find('.a-menu-filter').not(this).removeClass('active');

        // Відкрити або закрити поточний список
        $filterList.slideToggle();
        $(this).toggleClass('active');
    });
});


