"use strict";

(function(document) {
    window.NestedSelect = {};
    window.NestedSelect.updateTitle = function (nestedSelect) {
        nestedSelect = nestedSelect.closest('.NestedSelect');
        let selected = nestedSelect.querySelectorAll('.option > input:checked');
        let label;
        switch (selected.length) {
            case 0:
                label = nestedSelect.getAttribute('data-placeholder');
                break;
            case 1:
                label = selected[0].parentNode.textContent;
                break;
            default:
                label = nestedSelect.getAttribute('data-multiple');

        }
        nestedSelect.querySelector('.current').textContent = label;
    };

    window.NestedSelect.updateGroupBoxes = function(element) {
        element.closest(".NestedSelect").querySelectorAll(".group > input[type=checkbox]").forEach(function(el) {
            let groupContainer = el.closest('div');
            if (groupContainer.querySelectorAll(".option > input[type=checkbox]:checked").length === 0) {
                el.checked = false;
                el.indeterminate = false;
            } else if (groupContainer.querySelectorAll(".option > input[type=checkbox]:not(:checked)").length === 0) {
                let currentElement = groupContainer.querySelector('.group input');
                currentElement.checked = true;
                currentElement.indeterminate = false;
            } else {
                let currentElement = groupContainer.querySelector('.group input');
                currentElement.checked = false;
                currentElement.indeterminate = true;
            }
        });

    };

    window.NestedSelect.updateParents = function(element) {
        while (!element.matches('.options')) {
            element = element.parentNode;
            if (element.matches('div:not(.options)')) {
                if (element.querySelectorAll('.option > input:checked').length === 0) {
                    element.querySelector('.group input').checked = false;
                    element.querySelector('.group input').indeterminate = false;
                } else if (element.querySelectorAll('.option > input:not(:checked)').length === 0) {
                    element.querySelector('.group input').checked = true;
                    element.querySelector('.group input').indeterminate = false;
                } else {
                    element.querySelector('.group input').checked = false;
                    element.querySelector('.group input').indeterminate = true;
                }
            }
        }
        return element;
    };

    let lastSelect = null;
    document.addEventListener('click', function (e) {

        // Collapse
        if (lastSelect !== null && !lastSelect.contains(e.target)) {
            lastSelect.querySelectorAll('.expanded').forEach(el => el.classList.remove('expanded'));
        }

        if (e.target.matches('.NestedSelect .current')) {
            e.target.classList.toggle('expanded');
            lastSelect = e.target.parentNode;
        }
    });

    document.addEventListener('input', function (e) {
        if (!e.target.matches('.NestedSelect *')) {
            return;
        }

        if (e.target.matches('.group > *')) {
            e.target.parentNode.parentNode.querySelectorAll("input").forEach(function (el) {
                el.checked = e.target.checked;

            });
        }


        // Find all parents.
        NestedSelect.updateGroupBoxes(e.target);
        NestedSelect.updateTitle(e.target);
    });
})(document);