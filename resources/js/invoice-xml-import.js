/**
 * Importação de XML (NF-e / NFS-e) no formulário de faturamento.
 */

function onlyDigits(value) {
    return String(value || '').replace(/\D/g, '');
}

function setFieldValue(id, value) {
    const el = document.getElementById(id);
    if (!el || value === undefined || value === null) return;
    el.value = value;
    el.dispatchEvent(new Event('input', { bubbles: true }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
}

function setMoneyField(name, amount) {
    if (window.FormEnhancements?.setMoneyValue) {
        window.FormEnhancements.setMoneyValue(name, amount);
        return;
    }
    setFieldValue(name, amount);
}

function setDateField(id, isoDate) {
    const el = document.getElementById(id);
    if (!el || !isoDate) return;
    el.value = isoDate;
    if (el._flatpickr) {
        el._flatpickr.setDate(isoDate, true, 'Y-m-d');
    }
    el.dispatchEvent(new Event('change', { bubbles: true }));
}

function matchClientByDocument(documentDigits) {
    if (!documentDigits) return;
    const select = document.getElementById('client_id');
    if (!select) return;
    const normalized = onlyDigits(documentDigits);
    for (const option of select.options) {
        if (onlyDigits(option.dataset.document) === normalized) {
            select.value = option.value;
            select.dispatchEvent(new Event('change', { bubbles: true }));
            break;
        }
    }
}

function showImportMessage(message, type = 'success') {
    const box = document.getElementById('invoice-xml-import-message');
    if (!box) return;
    box.classList.remove('hidden', 'text-emerald-700', 'text-red-600', 'dark:text-emerald-400', 'dark:text-red-400');
    box.classList.add(type === 'success' ? 'text-emerald-700' : 'text-red-600', 'dark:text-emerald-400', 'dark:text-red-400');
    box.textContent = message;
}

function fillInvoiceForm(data) {
    setFieldValue('numero', data.numero);
    setFieldValue('serie', data.serie ?? '1');
    setDateField('data_emissao', data.data_emissao);
    setMoneyField('valor', data.valor);
    setFieldValue('descricao', data.descricao ?? '');
    setFieldValue('service_code', data.service_code ?? '');
    if (data.iss_value) setMoneyField('iss_value', data.iss_value);
    if (data.tax_amount) setMoneyField('tax_amount', data.tax_amount);

    const typeSelect = document.getElementById('invoice_type');
    if (typeSelect && data.invoice_type) {
        typeSelect.value = data.invoice_type;
        typeSelect.dispatchEvent(new Event('change', { bubbles: true }));
    }

    matchClientByDocument(data.tomador_document);

    const xmlHidden = document.getElementById('xml_imported');
    if (xmlHidden) xmlHidden.value = '1';
}

async function importInvoiceXml(file) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    const formData = new FormData();
    formData.append('xml', file);

    const response = await fetch(window.invoiceXmlImportUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            Accept: 'application/json',
        },
        body: formData,
    });

    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
        const message = payload.message || payload.errors?.xml?.[0] || 'Não foi possível ler o XML.';
        throw new Error(message);
    }

    return payload.data;
}

function setFileNameLabel(file) {
    const label = document.getElementById('invoice_xml_file_name');
    if (!label) return;
    label.textContent = file ? file.name : 'Nenhum arquivo escolhido';
}

function initInvoiceXmlImport() {
    const input = document.getElementById('invoice_xml_file');
    if (!input || !window.invoiceXmlImportUrl) return;

    input.addEventListener('change', async () => {
        const file = input.files?.[0];
        if (!file) {
            setFileNameLabel(null);
            return;
        }

        setFileNameLabel(file);
        showImportMessage('Lendo XML...', 'success');

        try {
            const data = await importInvoiceXml(file);
            fillInvoiceForm(data);
            const tipo = data.source === 'nfe' ? 'NF-e' : 'NFS-e';
            showImportMessage(`Dados importados do XML (${tipo}). Revise e salve.`, 'success');
        } catch (error) {
            showImportMessage(error.message || 'Erro ao importar XML.', 'error');
            input.value = '';
            setFileNameLabel(null);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initInvoiceXmlImport);
} else {
    initInvoiceXmlImport();
}
