function getTinyMceBody() {
    let iframe = document.querySelector('iframe');
    if (!iframe) {
        console.error("❌ Kein TinyMCE-Iframe gefunden!");
        return null;
    }

    let doc = iframe.contentDocument || iframe.contentWindow.document;
    return doc?.body || null;
}

function resetTinyMce() {
    let tinyBody = getTinyMceBody();
    if (tinyBody) tinyBody.innerHTML = "";
}

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

function resetForm(form) {
    let elements = form.elements;
    for (let element of elements) {
        let nodename = element.nodeName.toUpperCase();
        let type = element.type.toUpperCase();
        switch (nodename) {
            case 'SELECT':
                element.selectedIndex = 0;
                break;
            case 'TEXTAREA':
            case 'INPUT':
                if (type === 'CHECKBOX' || type === 'RADIO') {
                    element.checked = false;
                } else {
                    let _default = element.getAttribute('data-default');
                    element.value = _default ? _default : '';
                }
                break;
            default:
                break;
        }
    }
}

function isSubmitForm(action, message_fields) {
    let form = getForm();
    if (action === "delete") {
        let message = 'Soll dieser Eintrag gelöscht werden?';
        if (message_fields) {
            message += '\n\n';
            for (let field of message_fields) {
                message += form[field]?.value + ' ';
            }
            message = message.trim();
        }
        if (!confirm(message)) return false;
    }

    let actionField = form.querySelector('[name="action"]');
    if (actionField) {
        actionField.value = action;
    } else {
        console.error("❌ Kein verstecktes `action` Feld gefunden!");
        return false;
    }

    return true;
}

function getForm() {
    return document.forms['editor'];
}

function selectRecord() {
    let form = getForm();
    let id = form.record_selection.options[form.record_selection.selectedIndex].value;
    if (!id) return false;

    if (id === "neu") {
        let fields = form.querySelectorAll('input[type="text"], input[type="password"], input[type="hidden"]');
        fields.forEach(field => field.value = "");

        let languageField = form.querySelector('[name="language"]');
        if (languageField) {
            languageField.removeAttribute("readonly");
            languageField.removeAttribute("style");
        }

        return false;
    }

    form.id.value = id;
    if (isSubmitForm("edit")) {
        form.submit();
    }
}