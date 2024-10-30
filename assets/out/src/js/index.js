/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */
import { VoucherDiscountHandler } from "./module/voucherdiscounthandler.js";

document.addEventListener('DOMContentLoaded', () => {
    const options = {
        discountTypeSelect: document.querySelector('select[name="editval[oxvoucherseries__oxdiscounttype]"]'),
        sameSeriesYesRadio: document.querySelector('input[name="editval[oxvoucherseries__oxallowsameseries]"][value="1"]'),
        sameSeriesNoRadio: document.querySelector('input[name="editval[oxvoucherseries__oxallowsameseries]"][value="0"]'),
        discountInput: document.querySelector('input[name="editval[oxvoucherseries__oxdiscount]"]')
    };

    if (Object.values(options).every(el => el)) {
        new VoucherDiscountHandler(options);
    } else {
        console.warn("One or more elements were not found in the DOM.");
    }
});
