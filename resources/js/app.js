import "./bootstrap";
import "./quick-actions";
import "./themeManager";

import Alpine from "alpinejs";

document.addEventListener('DOMContentLoaded', () => {
    window.Alpine = Alpine;
    Alpine.start();
});
