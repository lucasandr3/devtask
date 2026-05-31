/**
 * Form Enhancements - Flatpickr & IMask
 * Datepickers e máscaras no formato brasileiro
 */

import flatpickr from 'flatpickr';
import { Portuguese } from 'flatpickr/dist/l10n/pt.js';
import IMask from 'imask';

const moneyMaskRegistry = new Map();
const decimalMaskRegistry = new Map();

flatpickr.localize(Portuguese);

function flatpickrAltInputClass(el) {
    return [...el.classList]
        .filter((cls) => !cls.startsWith('flatpickr'))
        .join(' ');
}

const flatpickrBaseOptions = {
    altInput: true,
    allowInput: true,
    locale: Portuguese,
    disableMobile: true,
};

const BRL_NUMBER_BLOCK = {
    mask: Number,
    scale: 2,
    thousandsSeparator: '.',
    padFractionalZeros: true,
    normalizeZeros: true,
    radix: ',',
    mapToRadix: ['.'],
    min: 0,
};

function cpfCnpjMaskDispatch(appended, dynamicMasked) {
    const digits = (dynamicMasked.unmaskedValue || '').replace(/\D/g, '');

    return digits.length > 11
        ? dynamicMasked.compiledMasks[1]
        : dynamicMasked.compiledMasks[0];
}

function phoneMaskDispatch(appended, dynamicMasked) {
    const digits = (dynamicMasked.unmaskedValue || '').replace(/\D/g, '');

    if (digits.length > 10) {
        return dynamicMasked.compiledMasks[1];
    }

    if (digits.length >= 3 && digits[2] === '9') {
        return dynamicMasked.compiledMasks[1];
    }

    return dynamicMasked.compiledMasks[0];
}

const MASK_PRESETS = {
    money: {
        mask: 'R$ num',
        lazy: false,
        blocks: {
            num: {
                ...BRL_NUMBER_BLOCK,
                max: 999999999.99,
            },
        },
    },
    'br-decimal': {
        mask: Number,
        ...BRL_NUMBER_BLOCK,
        max: 99999.99,
    },
    cnpj: { mask: '00.000.000/0000-00' },
    cpf: { mask: '000.000.000-00' },
    'cpf-cnpj': {
        mask: [
            { mask: '000.000.000-00' },
            { mask: '00.000.000/0000-00' },
        ],
        dispatch: cpfCnpjMaskDispatch,
    },
    phone: {
        mask: [
            { mask: '(00) 0000-0000' },
            { mask: '(00) 00000-0000' },
        ],
        dispatch: phoneMaskDispatch,
    },
    cep: { mask: '00000-000' },
};

/**
 * Aceita 1234.56 (banco), 1.234,56 (BR) ou R$ 1.234,56
 */
function parseMoneyValue(value) {
    return parseDecimalValue(value);
}

function parseDecimalValue(value) {
    if (value === null || value === undefined || value === '') {
        return null;
    }

    if (typeof value === 'number' && Number.isFinite(value)) {
        return value;
    }

    let str = String(value).trim().replace(/[R$\s\u00A0]/g, '');

    if (!str) {
        return null;
    }

    if (str.includes(',')) {
        str = str.replace(/\./g, '').replace(',', '.');
    }

    const num = parseFloat(str);

    return Number.isFinite(num) ? num : null;
}

function syncMaskedNumberToHidden(mask, hiddenInput) {
    const typed = mask.typedValue;

    if (typed !== null && typed !== undefined && !Number.isNaN(typed)) {
        hiddenInput.value = Number(typed).toFixed(2);
        return;
    }

    const parsed = parseDecimalValue(mask.unmaskedValue);

    hiddenInput.value = parsed !== null ? parsed.toFixed(2) : '';
}

function initDatepickers() {
    document.querySelectorAll('[data-datepicker]').forEach(el => {
        flatpickr(el, {
            ...flatpickrBaseOptions,
            dateFormat: 'Y-m-d',
            altFormat: 'd/m/Y',
            altInputClass: flatpickrAltInputClass(el),
        });
    });

    document.querySelectorAll('[data-monthpicker]').forEach(el => {
        const instance = flatpickr(el, {
            ...flatpickrBaseOptions,
            dateFormat: 'Y-m',
            altFormat: 'F \\d\\e Y',
            altInputClass: flatpickrAltInputClass(el),
        });

        const formatMonthAlt = () => {
            const selected = instance.selectedDates[0];
            if (!selected || !instance.altInput) return;
            const monthName = instance.l10n.months.longhand[selected.getMonth()] ?? '';
            const capitalized = monthName.charAt(0).toUpperCase() + monthName.slice(1);
            instance.altInput.value = `${capitalized} de ${selected.getFullYear()}`;
        };

        instance.config.onReady.push(formatMonthAlt);
        instance.config.onValueUpdate.push(formatMonthAlt);
    });

    document.querySelectorAll('[data-datepicker-filter]').forEach(el => {
        flatpickr(el, {
            ...flatpickrBaseOptions,
            dateFormat: 'Y-m-d',
            altFormat: 'd/m/Y',
            altInputClass: flatpickrAltInputClass(el),
        });
    });
}

function applyMask(el, type) {
    if (!el || el.dataset.maskApplied === '1' || el.type === 'hidden' || el.disabled) {
        return null;
    }

    const preset = MASK_PRESETS[type];
    if (!preset) return null;

    el.dataset.maskApplied = '1';
    el.setAttribute('inputmode', type === 'money' ? 'decimal' : 'numeric');
    el.setAttribute('autocomplete', 'off');

    if (!el.placeholder && (type === 'money' || type === 'br-decimal')) {
        el.placeholder = type === 'money' ? 'R$ 0,00' : '0,00';
    }

    return IMask(el, preset);
}

function initMasks() {
    document.querySelectorAll('[data-mask]').forEach(el => {
        const type = el.getAttribute('data-mask');
        if (type && type !== 'money') {
            applyMask(el, type);
        }
    });

    const autoMaskRules = [
        { selector: 'input[name="document"], input#document', type: 'cpf-cnpj' },
        { selector: 'input[name="phone"], input#phone', type: 'phone' },
        { selector: 'input[name="cnpj"], input#cnpj', type: 'cnpj' },
        { selector: 'input[name="cpf"], input#cpf', type: 'cpf' },
        { selector: 'input[name="cep"], input#cep', type: 'cep' },
    ];

    autoMaskRules.forEach(({ selector, type }) => {
        document.querySelectorAll(selector).forEach(el => applyMask(el, type));
    });
}

function initMoneyInputs() {
    document.querySelectorAll('[data-money]').forEach(el => {
        if (el.dataset.moneyInitialized === '1') {
            return;
        }

        const hiddenName = el.name;
        const displayId = el.id ? `${el.id}_display` : `${hiddenName}_display`;

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = hiddenName;

        const initialParsed = parseMoneyValue(el.value);
        hiddenInput.value = initialParsed !== null ? initialParsed.toFixed(2) : (el.value || '');

        el.removeAttribute('name');
        if (el.id) {
            el.id = displayId;
        }
        el.type = 'text';
        el.dataset.moneyInitialized = '1';
        el.setAttribute('inputmode', 'decimal');
        el.setAttribute('autocomplete', 'off');

        if (!el.placeholder) {
            el.placeholder = 'R$ 0,00';
        }

        el.parentNode.insertBefore(hiddenInput, el);

        const rawInitial = el.value;
        el.value = '';

        const mask = applyMask(el, 'money');
        if (!mask) return;

        if (initialParsed !== null) {
            mask.typedValue = initialParsed;
        } else if (rawInitial) {
            mask.value = rawInitial;
        }

        moneyMaskRegistry.set(hiddenName, { mask, hiddenInput, display: el });

        const syncHidden = () => syncMaskedNumberToHidden(mask, hiddenInput);

        mask.on('accept', syncHidden);
        syncHidden();
    });
}

function initDecimalInputs() {
    document.querySelectorAll('[data-decimal]').forEach(el => {
        if (el.dataset.decimalInitialized === '1') {
            return;
        }

        const hiddenName = el.name;
        const displayId = el.id ? `${el.id}_display` : `${hiddenName}_display`;

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = hiddenName;

        const initialParsed = parseDecimalValue(el.value);
        hiddenInput.value = initialParsed !== null ? initialParsed.toFixed(2) : (el.value || '');

        el.removeAttribute('name');
        if (el.id) {
            el.id = displayId;
        }
        el.type = 'text';
        el.dataset.decimalInitialized = '1';
        el.setAttribute('inputmode', 'decimal');
        el.setAttribute('autocomplete', 'off');

        if (!el.placeholder) {
            el.placeholder = '0,00';
        }

        el.parentNode.insertBefore(hiddenInput, el);

        const rawInitial = el.value;
        el.value = '';

        const mask = applyMask(el, 'br-decimal');
        if (!mask) return;

        if (initialParsed !== null) {
            mask.typedValue = initialParsed;
        } else if (rawInitial) {
            mask.value = rawInitial;
        }

        decimalMaskRegistry.set(hiddenName, { mask, hiddenInput, display: el });

        const syncHidden = () => syncMaskedNumberToHidden(mask, hiddenInput);

        mask.on('accept', syncHidden);
        syncHidden();
    });
}

function syncAllNumericHiddenFields() {
    moneyMaskRegistry.forEach(({ mask, hiddenInput }) => {
        syncMaskedNumberToHidden(mask, hiddenInput);
    });
    decimalMaskRegistry.forEach(({ mask, hiddenInput }) => {
        syncMaskedNumberToHidden(mask, hiddenInput);
    });
}

function setMoneyValue(fieldName, numericValue) {
    const entry = moneyMaskRegistry.get(fieldName);
    const amount = Number(numericValue);
    const normalized = Number.isFinite(amount) ? amount.toFixed(2) : '';

    if (entry) {
        entry.hiddenInput.value = normalized;
        if (normalized !== '') {
            entry.mask.typedValue = parseFloat(normalized);
        } else {
            entry.mask.value = '';
        }
        return;
    }

    const direct = document.querySelector(`[name="${fieldName}"][data-money], #${fieldName}[data-money]`);
    if (direct) {
        const parsed = parseMoneyValue(normalized);
        direct.value = parsed !== null ? parsed.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';
        direct.dispatchEvent(new Event('input', { bubbles: true }));
    }
}

function formatMoney(value) {
    const parsed = parseMoneyValue(value);
    if (parsed === null) return '';
    return parsed.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function init() {
    initDatepickers();
    initMoneyInputs();
    initDecimalInputs();
    initMasks();
}

document.addEventListener('submit', syncAllNumericHiddenFields, true);

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

document.addEventListener('livewire:navigated', init);

window.FormEnhancements = {
    init,
    initDatepickers,
    initMasks,
    initMoneyInputs,
    initDecimalInputs,
    formatMoney,
    setMoneyValue,
    parseMoneyValue,
};

export {
    initDatepickers,
    initMasks,
    initMoneyInputs,
    initDecimalInputs,
    formatMoney,
    setMoneyValue,
    parseMoneyValue,
};
