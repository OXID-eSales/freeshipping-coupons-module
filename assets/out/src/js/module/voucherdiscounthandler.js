/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

export class VoucherDiscountHandler {
    constructor(options) {
        this.discountTypeSelect = options.discountTypeSelect;
        this.sameSeriesYesRadio = options.sameSeriesYesRadio;
        this.sameSeriesNoRadio = options.sameSeriesNoRadio;
        this.discountInput = options.discountInput;

        this.initialize();
    }

    initialize() {
        this.updateFieldsBasedOnDiscountType();
        this.discountTypeSelect.addEventListener('change', () => this.updateFieldsBasedOnDiscountType());
        this.discountInput.form.addEventListener('formdata', (event) => {
            if (this.discountInput.disabled) {
                event.formData.set(this.discountInput.name, '0');
            }
        });
    }

    updateFieldsBasedOnDiscountType() {
        if (this.discountTypeSelect.value === 'shipfree') {
            this.sameSeriesNoRadio.checked = true;
            this.sameSeriesYesRadio.disabled = true;
            this.discountInput.disabled = true;
            this.discountInput.value = 0;
        } else {
            this.sameSeriesYesRadio.disabled = false;
            this.discountInput.disabled = false;
        }
    }
}