function load_aioa_script() {
    let scriptEle = document.createElement("script");
    scriptEle.setAttribute("src", "https://www.skynettechnologies.com/accessibility/js/all-in-one-accessibility-js-widget-minify.js?colorcode=#420083&token=&position=bottom_right");
    scriptEle.setAttribute("type", "text/javascript");
    scriptEle.setAttribute("async", "");
    scriptEle.setAttribute("id", "aioa-adawidget");
    document.head.appendChild(scriptEle);
}
load_aioa_script();
