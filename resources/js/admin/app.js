import './bootstrap';

import Alpine from 'alpinejs';
import { themeChange } from 'theme-change';

import $ from 'jquery';

// Puedes escribir tus scripts de jQuery aquí
$(document).ready(function() {
    console.log('jQuery is ready!');
});

window.Alpine = Alpine;

Alpine.start();
themeChange();

var selectedTheme = localStorage.getItem("theme");
if(selectedTheme === 'dark') {
    document.getElementById("theme-change").checked = true;
}