document.addEventListener("DOMContentLoaded", function() {
    console.log("✅ DOM vollständig geladen");
    console.log(document.getElementById('LanguageSelector')); // Test

    function toggleLanguageMenu() {
        let lang_selector = document.getElementById('LanguageSelector');
        if (!lang_selector) {
            console.error("❌ LanguageSelector nicht gefunden!");
            return;
        }

        let list = lang_selector.getElementsByClassName('list')[0];
        if (!list) {
            console.error("❌ List-Element nicht gefunden!");
            return;
        }

        list.style.display = (list.style.display === 'block') ? 'none' : 'block';
    }

    // Falls dein HTML das Event `onclick="toggleLanguageMenu()"` nutzt, kannst du es so hinzufügen:
    let lang_selector = document.getElementById('LanguageSelector');
    if (lang_selector) {
        lang_selector.addEventListener("click", toggleLanguageMenu);
    }
});