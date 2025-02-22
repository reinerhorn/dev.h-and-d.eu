function toggleLanguageMenu() {
	let lang_selector = document.getElementById('LanguageSelector');
	let list = lang_selector.getElementsByClassName('list')[0];
	let is_visible = list.style.display == 'block';
	list.style.display = is_visible ? 'none' : 'block';
}