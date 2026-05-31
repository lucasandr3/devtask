/**
 * Form Enhancements - Flatpickr & IMask
 * Datepickers e máscaras para formulários
 */

import flatpickr from 'flatpickr';
import { Portuguese } from 'flatpickr/dist/l10n/pt.js';
import IMask from 'imask';

// Configurar Flatpickr em português
flatpickr.localize(Portuguese);

/** Classes do input original repassadas ao altInput visível */
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

/**
 * Inicializa todos os datepickers
 */
function initDatepickers() {
    // Datepicker padrão para datas (dd/mm/yyyy)
    document.querySelectorAll('[data-datepicker]').forEach(el => {
        flatpickr(el, {
            ...flatpickrBaseOptions,
            dateFormat: 'Y-m-d',
            altFormat: 'd/m/Y',
            altInputClass: flatpickrAltInputClass(el),
        });
    });

    // Datepicker para meses (mm/yyyy)
    document.querySelectorAll('[data-monthpicker]').forEach(el => {
        flatpickr(el, {
            ...flatpickrBaseOptions,
            dateFormat: 'Y-m',
            altFormat: 'F Y',
            altInputClass: flatpickrAltInputClass(el),
        });
    });

    // Datepicker para filtros de data (range)
    document.querySelectorAll('[data-datepicker-filter]').forEach(el => {
        flatpickr(el, {
            ...flatpickrBaseOptions,
            dateFormat: 'Y-m-d',
            altFormat: 'd/m/Y',
            altInputClass: flatpickrAltInputClass(el),
        });
    });
}

/**
 * Inicializa máscaras de input
 */
function initMasks() {
    // Máscara para valores monetários (R$ 1.234,56)
    document.querySelectorAll('[data-mask="money"]').forEach(el => {
        const mask = IMask(el, {
            mask: Number,
            scale: 2,
            thousandsSeparator: '.',
            padFractionalZeros: true,
            normalizeZeros: true,
            radix: ',',
            mapToRadix: ['.'],
            min: 0,
            max: 999999999.99,
        });

        // Atualiza o valor do input hidden associado quando muda
        el.addEventListener('blur', () => {
            const hiddenInput = document.querySelector(`[name="${el.dataset.target}"]`);
            if (hiddenInput) {
                hiddenInput.value = mask.unmaskedValue.replace(',', '.');
            }
        });

        // Se já tiver valor, formatar
        if (el.value) {
            mask.value = el.value;
        }
    });

    // Máscara para CNPJ (00.000.000/0000-00)
    document.querySelectorAll('[data-mask="cnpj"]').forEach(el => {
        IMask(el, {
            mask: '00.000.000/0000-00',
        });
    });

    // Máscara para CPF (000.000.000-00)
    document.querySelectorAll('[data-mask="cpf"]').forEach(el => {
        IMask(el, {
            mask: '000.000.000-00',
        });
    });

    // Máscara para CPF/CNPJ dinâmico
    document.querySelectorAll('[data-mask="cpf-cnpj"]').forEach(el => {
        IMask(el, {
            mask: [
                { mask: '000.000.000-00' },
                { mask: '00.000.000/0000-00' },
            ],
        });
    });

    // Máscara para telefone (com DDD)
    document.querySelectorAll('[data-mask="phone"]').forEach(el => {
        IMask(el, {
            mask: [
                { mask: '(00) 0000-0000' },
                { mask: '(00) 00000-0000' },
            ],
        });
    });

    // Máscara para CEP
    document.querySelectorAll('[data-mask="cep"]').forEach(el => {
        IMask(el, {
            mask: '00000-000',
        });
    });
}

/**
 * Inicializa inputs monetários com conversão automática
 */
function initMoneyInputs() {
    document.querySelectorAll('[data-money]').forEach(el => {
        const hiddenName = el.name;
        const displayId = el.id + '_display';
        
        // Cria input hidden para o valor real
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = hiddenName;
        hiddenInput.value = el.value || '';
        
        // Modifica o input original
        el.removeAttribute('name');
        el.id = displayId;
        el.type = 'text';
        el.setAttribute('data-mask', 'money');
        el.setAttribute('data-target', hiddenName);
        
        // Insere hidden antes do input
        el.parentNode.insertBefore(hiddenInput, el);
        
        // Formata valor inicial se existir
        if (el.value) {
            el.value = formatMoney(parseFloat(el.value));
        }
        
        // Aplica máscara
        const mask = IMask(el, {
            mask: Number,
            scale: 2,
            thousandsSeparator: '.',
            padFractionalZeros: true,
            normalizeZeros: true,
            radix: ',',
            mapToRadix: ['.'],
            min: 0,
            max: 999999999.99,
        });

        // Atualiza hidden quando valor muda
        mask.on('accept', () => {
            hiddenInput.value = mask.unmaskedValue ? 
                (parseFloat(mask.unmaskedValue.replace(',', '.')) || 0).toFixed(2) : '';
        });

        // Trigger inicial
        if (mask.unmaskedValue) {
            hiddenInput.value = (parseFloat(mask.unmaskedValue.replace(',', '.')) || 0).toFixed(2);
        }
    });
}

/**
 * Formata número para moeda brasileira
 */
function formatMoney(value) {
    if (!value && value !== 0) return '';
    return value.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

/**
 * Inicialização principal
 */
function init() {
    initDatepickers();
    initMoneyInputs();
    initMasks();
}

// Inicializa quando DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

// Re-inicializa em navegações Livewire/Turbo se necessário
document.addEventListener('livewire:navigated', init);

// Exporta funções para uso externo
export { initDatepickers, initMasks, initMoneyInputs, formatMoney };
