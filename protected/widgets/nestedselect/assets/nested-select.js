
document.addEventListener('click', function(e) {
    if (e.target.matches('.current')) {
        e.target.classList.toggle('expanded');
    }
});

document.addEventListener('input', function(e) {
    if (e.target.matches('.group')) {
        e.target.parentNode.parentNode.querySelectorAll("input").forEach(function (el) {
            el.checked = e.target.checked;

        });
    }
    // Find all parents.
    let element = e.target;
    while (!element.matches('.options')) {
        element = element.parentNode;
        if (element.matches('div:not(.options)')) {
            if (element.querySelectorAll('input.option:checked').length === 0) {
                element.querySelector('input:not(.option)').checked = false;
                element.querySelector('input:not(.option)').indeterminate = false;
            } else if (element.querySelectorAll('input.option:not(:checked)').length === 0) {
                element.querySelector('input:not(.option)').checked = true;
                element.querySelector('input:not(.option)').indeterminate = false;
            } else {
                element.querySelector('input:not(.option)').checked = false;
                element.querySelector('input:not(.option)').indeterminate = true;
            }
        }
    }
});