const html = document.getElementById('html-root');
const icon = document.getElementById('theme-icon');


function updateIcon() {

    if (html.classList.contains('dark')) {

        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');

    } else {

        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');

    }
}


function applyTheme() {

    const theme = localStorage.getItem('theme');


    if (theme === 'light') {

        html.classList.remove('dark');

    } else {

        html.classList.add('dark');

    }


    updateIcon();
}



function toggleTheme() {

    html.classList.toggle('dark');


    localStorage.setItem(
        'theme',
        html.classList.contains('dark')
            ? 'dark'
            : 'light'
    );


    updateIcon();
}


window.toggleTheme = toggleTheme;


document.addEventListener(
    'DOMContentLoaded',
    applyTheme
);