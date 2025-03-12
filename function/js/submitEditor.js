const { getTinyMceBody } = require("./editor");

function submitEditor() {
    let form = document.forms['editor'];
    if (!form) {
        console.error("❌ Formular nicht gefunden!");
        return;
    }

    let contentField = form.querySelector('[name="text"]');
    if (!contentField) {
        console.error("❌ Das Textfeld fehlt im Formular!");
        return;
    }

    let editorContent = getTinyMceBody()?.innerHTML || "";
    contentField.value = editorContent;

    // Korrigierte action-Zuweisung
    let actionField = form.querySelector('[name="action"]');
    let idField = form.querySelector('[name="id"]');

    if (actionField) {
        actionField.value = idField && idField.value ? 'update' : 'add';
    } else {
        console.error("❌ Kein verstecktes Feld `action` gefunden!");
    }

    console.log("✅ Formular wird gesendet mit Inhalt:", editorContent);
    form.submit();
}
