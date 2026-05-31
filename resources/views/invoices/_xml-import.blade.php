<div class="rounded-xl border border-dashed border-primary/40 bg-primary/5 p-5 space-y-4">
    <div>
        <h3 class="text-base font-semibold text-foreground">Importar XML da nota</h3>
        <p class="text-sm text-muted-foreground mt-1">
            Envie o XML da NF-e (produto) ou NFS-e (serviço). Os campos abaixo serão preenchidos automaticamente.
        </p>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <input
            type="file"
            id="invoice_xml_file"
            accept=".xml,application/xml,text/xml"
            class="sr-only"
        >
        <label
            for="invoice_xml_file"
            class="btn-primary h-10 px-4 shrink-0 cursor-pointer"
        >
            <x-ui.icon name="upload" class="size-5" />
            Escolher arquivo
        </label>
        <p id="invoice_xml_file_name" class="text-sm text-muted-foreground min-w-0 truncate">
            Nenhum arquivo escolhido
        </p>
        <input type="hidden" name="xml_imported" id="xml_imported" value="{{ old('xml_imported') }}">
    </div>
    <p id="invoice-xml-import-message" class="hidden text-sm font-medium"></p>
</div>

@push('scripts')
    <script>
        window.invoiceXmlImportUrl = @json(route('notas-fiscais.importar-xml'));
    </script>
    @vite(['resources/js/invoice-xml-import.js'])
@endpush
