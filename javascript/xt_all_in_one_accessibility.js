setTimeout(function () {
    const domain_name = window.location.host;
    const apiUrl = "https://ada.skynettechnologies.us/api/widget-settings";

    function setWidgetData(data) {
        document.querySelector('[name="conf_XT_AIOA_COLOR_shop_1"]').value = data.widget_color_code || "#420083";
        document.querySelector('[name="conf_XT_AIOA_POSITION_shop_1"]').value = data.widget_position || "bottom_right";
        document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_HORIZONTAL_shop_1"]').value = data.widget_position_left || "0";
        document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_VERTICAL_shop_1"]').value = data.widget_position_top || "0";
        document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_HORIZONTAL_TYPE_shop_1"]').value = data.widget_position_left ? "left" : "right";
        document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_VERTICAL_TYPE_shop_1"]').value = data.widget_position_top ? "top" : "bottom";
        document.querySelector('[name="conf_XT_AIOA_WIDGET_SIZE_shop_1"]').value = data.widget_size || "";
        document.querySelector('[name="conf_XT_AIOA_ICON_TYPE_shop_1"]').value = data.widget_icon_type || "";
        document.querySelector('[name="conf_XT_AIOA_ICON_SIZE_shop_1"]').value = data.widget_icon_size || "";
        document.querySelector('[name="conf_XT_AIOA_CUSTOM_ICON_SIZE_shop_1"]').value = data.widget_icon_size_custom || "";
        // ✅ SET value as "1" or "0"
        document.querySelector('[name="conf_XT_AIOA_IS_CUSTOM_POSITION_shop_1"]').checked = data.is_widget_custom_position === "1" ? "1" : "0";
        document.querySelector('[name="conf_XT_AIOA_IS_CUSTOM_SIZE_shop_1"]').checked = data.is_widget_custom_size === "1" ? "1" : "0";
    }

    fetch(apiUrl, {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({website_url: domain_name})
    })
        .then(res => res.json())
        .then(response => {
            if (response?.Data) {
                setTimeout(() => setWidgetData(response.Data), 500);
                console.log("✅ Widget data loaded successfully.");
            }
        })
        .catch(err => console.error("Fetch error:", err));


    setTimeout(function initFormListener() {
        const form = document.querySelector('form[class*="x-form"]');
        if (!form) {
            console.log("Waiting for form...");
            return setTimeout(initFormListener, 300);
        }
        console.log("✅ Form found!");
        function validateAndSave(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            console.log("✅ Intercepted Save Button Click!");

            const horizontal = document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_HORIZONTAL_shop_1"]').value;
            const vertical = document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_VERTICAL_shop_1"]').value;
            const horizontalType = document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_HORIZONTAL_TYPE_shop_1"]').value;
            const verticalType = document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_VERTICAL_TYPE_shop_1"]').value;

            const widget_position_left = horizontalType === "left" ? horizontal : "";
            const widget_position_right = horizontalType === "right" ? horizontal : "";
            const widget_position_top = verticalType === "top" ? vertical : "";
            const widget_position_bottom = verticalType === "bottom" ? vertical : "";

            const checkbox = document.querySelector('input[type="checkbox"][name="conf_XT_AIOA_IS_CUSTOM_SIZE_shop_1"]');
            const is_widget_custom_size = checkbox && checkbox.checked ? "1" : "0";
            console.log(is_widget_custom_size);

            const positionCheckbox = document.querySelector('input[type="checkbox"][name="conf_XT_AIOA_IS_CUSTOM_POSITION_shop_1"]');
            const is_widget_custom_position = positionCheckbox && positionCheckbox.checked ? "1" : "0";
            const widget_icon_size_custom = document.querySelector('[name="conf_XT_AIOA_CUSTOM_ICON_SIZE_shop_1"]').value;
            if (is_widget_custom_size === '1') {
                const customSize = parseInt(widget_icon_size_custom, 10);
                if (isNaN(customSize) || customSize < 20 || customSize > 150) {
                    alert("Please enter a valid icon size between 20 and 150 pixels.");
                    return; // prevent API call
                }
            }

            if (is_widget_custom_position === '1') {
                const horizontalVal = parseInt(horizontal, 10);
                const verticalVal = parseInt(vertical, 10);

                if (isNaN(horizontalVal) || horizontalVal < 0 || horizontalVal > 250) {
                    alert("Please enter a valid horizontal position between 0 and 250 pixels.");
                    return;
                }

                if (isNaN(verticalVal) || verticalVal < 0 || verticalVal > 250) {
                    alert("Please enter a valid vertical position between 0 and 250 pixels.");
                    return;
                }
            }

            const domain_name = window.location.host;
            const params = new URLSearchParams();
            params.append('u', domain_name);
            params.append('widget_color_code', document.querySelector('[name="conf_XT_AIOA_COLOR_shop_1"]').value);
            params.append('widget_position', document.querySelector('[name="conf_XT_AIOA_POSITION_shop_1"]').value);
            params.append('widget_position_left', widget_position_left);
            params.append('widget_position_right', widget_position_right);
            params.append('widget_position_top', widget_position_top);
            params.append('widget_position_bottom', widget_position_bottom);
            params.append('widget_size', document.querySelector('[name="conf_XT_AIOA_WIDGET_SIZE_shop_1"]').value);
            params.append('widget_icon_type', document.querySelector('[name="conf_XT_AIOA_ICON_TYPE_shop_1"]').value);
            params.append('widget_icon_size', document.querySelector('[name="conf_XT_AIOA_ICON_SIZE_shop_1"]').value);
            params.append('widget_icon_size_custom', widget_icon_size_custom);
            params.append('is_widget_custom_position', is_widget_custom_position);
            params.append('is_widget_custom_size', is_widget_custom_size);

            fetch('https://ada.skynettechnologies.us/api/widget-setting-update-platform', {
                method: "POST",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString()
            }).then(res => res.json())
                .then(apiRes => {
                    console.log("✅ External API Response", apiRes);
                    if (apiRes?.msg?.includes('Saved successfully')) {
                        const formData = new FormData(form);
                        fetch(form.action, {
                            method: 'POST',
                            body: formData
                        }).then(res => res.text())
                            .then(() => {
                                console.log("✅ Form saved via AJAX!");
                                alert("✅ Widget Settings saved successfully!");
                            }).catch(err => {
                            alert("❌ xt:Commerce form save error: " + err);
                        });
                    } else {
                        alert("❌ External API Save Failed: " + (apiRes?.msg || 'Unknown error'));
                    }
                }).catch(err => {
                alert("❌ API Call Error: " + err);
            });
        }

        function bindSaveButton() {
            const buttons = document.querySelectorAll('button');
            let found = false;
            buttons.forEach(btn => {
                const span = btn.querySelector('span.xt-btn-text');
                if (span && span.innerText.trim().toLowerCase() === 'save') {
                    console.log("✅ Save button found!");
                    btn.addEventListener('click', validateAndSave);
                    found = true;
                }
            });
            if (!found) {
                console.log("❌ Save button not found, retrying...");
                setTimeout(bindSaveButton, 500);
            }
        }

        bindSaveButton();
    }, 500);

    function toggleCustomPosition() {
        const isChecked = document.querySelector('[type="checkbox"][name="conf_XT_AIOA_IS_CUSTOM_POSITION_shop_1"]').checked;
        const widgetPositionField = document.querySelector('[name="conf_XT_AIOA_POSITION_shop_1"]').closest('.x-form-item');
        const horizontalField = document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_HORIZONTAL_shop_1"]').closest('.x-form-item');
        const verticalField = document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_VERTICAL_shop_1"]').closest('.x-form-item');
        const horizontalTypeField = document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_HORIZONTAL_TYPE_shop_1"]').closest('.x-form-item');
        const verticalTypeField = document.querySelector('[name="conf_XT_AIOA_CUSTOM_POSITION_VERTICAL_TYPE_shop_1"]').closest('.x-form-item');

        if (isChecked) {
            widgetPositionField && (widgetPositionField.style.display = 'none');
            [horizontalField, verticalField, horizontalTypeField, verticalTypeField].forEach(el => el && (el.style.display = 'block'));
        } else {
            widgetPositionField && (widgetPositionField.style.display = 'block');
            [horizontalField, verticalField, horizontalTypeField, verticalTypeField].forEach(el => el && (el.style.display = 'none'));
        }
    }
    // on checkbox change
    document.querySelector('[type="checkbox"][name="conf_XT_AIOA_IS_CUSTOM_POSITION_shop_1"]').addEventListener('change', toggleCustomPosition);
    // Call once on page load
    toggleCustomPosition();

    function toggleCustomIconSize() {
        const isChecked = document.querySelector('[type="checkbox"][name="conf_XT_AIOA_IS_CUSTOM_SIZE_shop_1"]').checked;
        const standardIconSizeField = document.querySelector('[name="conf_XT_AIOA_ICON_SIZE_shop_1"]').closest('.x-form-item');
        const customIconSizeField = document.querySelector('[name="conf_XT_AIOA_CUSTOM_ICON_SIZE_shop_1"]').closest('.x-form-item');

        if (isChecked) {
            standardIconSizeField && (standardIconSizeField.style.display = 'none');
            customIconSizeField && (customIconSizeField.style.display = 'block');
        } else {
            standardIconSizeField && (standardIconSizeField.style.display = 'block');
            customIconSizeField && (customIconSizeField.style.display = 'none');
        }
    }
    // ✅ bind event listener
        document.querySelector('[type="checkbox"][name="conf_XT_AIOA_IS_CUSTOM_SIZE_shop_1"]')
            .addEventListener('change', toggleCustomIconSize);
    // ✅ Call on page load
    toggleCustomIconSize();

}, 800);
