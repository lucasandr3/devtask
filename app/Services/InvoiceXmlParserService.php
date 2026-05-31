<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Illuminate\Validation\ValidationException;

class InvoiceXmlParserService
{
    /**
     * @return array{
     *     numero: string,
     *     serie: string,
     *     data_emissao: string,
     *     valor: float,
     *     descricao: ?string,
     *     service_code: ?string,
     *     iss_value: ?float,
     *     tax_amount: ?float,
     *     invoice_type: string,
     *     tomador_name: ?string,
     *     tomador_document: ?string,
     *     source: string,
     * }
     */
    public function parse(string $xmlContent): array
    {
        $xmlContent = trim($xmlContent);
        if ($xmlContent === '') {
            throw ValidationException::withMessages([
                'xml' => 'O arquivo XML está vazio.',
            ]);
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new DOMDocument;
        if (! $dom->loadXML($xmlContent)) {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
            throw ValidationException::withMessages([
                'xml' => 'Arquivo XML inválido ou corrompido.',
            ]);
        }
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($dom);

        if ($this->isNfe($xpath)) {
            return $this->parseNfe($xpath);
        }

        if ($this->isNfse($xpath)) {
            return $this->parseNfse($xpath);
        }

        throw ValidationException::withMessages([
            'xml' => 'Formato não reconhecido. Envie o XML de NF-e (produto) ou NFS-e (serviço).',
        ]);
    }

    private function isNfe(DOMXPath $xpath): bool
    {
        return (bool) $xpath->query("//*[local-name()='infNFe']")->length
            || (bool) $xpath->query("//*[local-name()='NFe']")->length;
    }

    private function isNfse(DOMXPath $xpath): bool
    {
        return (bool) $xpath->query("//*[local-name()='InfNfse']")->length
            || (bool) $xpath->query("//*[local-name()='Nfse']")->length
            || (bool) $xpath->query("//*[local-name()='CompNfse']")->length;
    }

    private function parseNfe(DOMXPath $xpath): array
    {
        $numero = $this->firstString($xpath, [
            "//*[local-name()='ide']/*[local-name()='nNF']",
            "//*[local-name()='nNF']",
        ]);
        $serie = $this->firstString($xpath, [
            "//*[local-name()='ide']/*[local-name()='serie']",
            "//*[local-name()='serie']",
        ]) ?? '1';

        $emissaoRaw = $this->firstString($xpath, [
            "//*[local-name()='ide']/*[local-name()='dhEmi']",
            "//*[local-name()='ide']/*[local-name()='dEmi']",
            "//*[local-name()='dhEmi']",
            "//*[local-name()='dEmi']",
        ]);

        $valor = $this->firstAmount($xpath, [
            "//*[local-name()='ICMSTot']/*[local-name()='vNF']",
            "//*[local-name()='vNF']",
        ]);

        $descricao = $this->firstString($xpath, [
            "//*[local-name()='det']//*[local-name()='xProd']",
            "//*[local-name()='infAdic']/*[local-name()='infCpl']",
        ]);

        $taxAmount = $this->firstAmount($xpath, [
            "//*[local-name()='ICMSTot']/*[local-name()='vTotTrib']",
        ], 0.0);

        $tomadorName = $this->firstString($xpath, [
            "//*[local-name()='dest']/*[local-name()='xNome']",
        ]);
        $tomadorDocument = $this->firstDocument($xpath, [
            "//*[local-name()='dest']/*[local-name()='CNPJ']",
            "//*[local-name()='dest']/*[local-name()='CPF']",
        ]);

        if (! $numero || $valor === null) {
            throw ValidationException::withMessages([
                'xml' => 'Não foi possível ler número ou valor total da NF-e.',
            ]);
        }

        return [
            'numero' => $numero,
            'serie' => $serie,
            'data_emissao' => $this->normalizeDate($emissaoRaw),
            'valor' => $valor,
            'descricao' => $descricao,
            'service_code' => null,
            'iss_value' => null,
            'tax_amount' => $taxAmount > 0 ? $taxAmount : null,
            'invoice_type' => 'product',
            'tomador_name' => $tomadorName,
            'tomador_document' => $tomadorDocument,
            'source' => 'nfe',
        ];
    }

    private function parseNfse(DOMXPath $xpath): array
    {
        $numero = $this->firstString($xpath, [
            "//*[local-name()='InfNfse']/*[local-name()='Numero']",
            "//*[local-name()='Nfse']//*[local-name()='Numero']",
            "//*[local-name()='Numero']",
        ]);

        $emissaoRaw = $this->firstString($xpath, [
            "//*[local-name()='InfNfse']/*[local-name()='DataEmissao']",
            "//*[local-name()='DataEmissao']",
        ]);

        $valor = $this->firstAmount($xpath, [
            "//*[local-name()='Valores']/*[local-name()='ValorServicos']",
            "//*[local-name()='ValoresNfse']/*[local-name()='ValorLiquidoNfse']",
            "//*[local-name()='Servico']//*[local-name()='ValorServicos']",
            "//*[local-name()='vServ']",
        ]);

        $issValue = $this->firstAmount($xpath, [
            "//*[local-name()='Valores']/*[local-name()='ValorIss']",
            "//*[local-name()='Servico']//*[local-name()='ValorIss']",
        ], 0.0);

        $serviceCode = $this->firstString($xpath, [
            "//*[local-name()='Servico']/*[local-name()='ItemListaServico']",
            "//*[local-name()='ItemListaServico']",
        ]);

        $descricao = $this->firstString($xpath, [
            "//*[local-name()='Servico']/*[local-name()='Discriminacao']",
            "//*[local-name()='Discriminacao']",
            "//*[local-name()='xDescServ']",
        ]);

        $tomadorName = $this->firstString($xpath, [
            "//*[local-name()='TomadorServico']//*[local-name()='RazaoSocial']",
            "//*[local-name()='Tomador']//*[local-name()='RazaoSocial']",
            "//*[local-name()='TomadorServico']//*[local-name()='Nome']",
        ]);

        $tomadorDocument = $this->firstDocument($xpath, [
            "//*[local-name()='TomadorServico']//*[local-name()='Cnpj']",
            "//*[local-name()='TomadorServico']//*[local-name()='CpfCnpj']/*[local-name()='Cnpj']",
            "//*[local-name()='Tomador']//*[local-name()='CpfCnpj']/*[local-name()='Cnpj']",
            "//*[local-name()='TomadorServico']//*[local-name()='Cpf']",
        ]);

        if (! $numero || $valor === null) {
            throw ValidationException::withMessages([
                'xml' => 'Não foi possível ler número ou valor da NFS-e.',
            ]);
        }

        return [
            'numero' => $numero,
            'serie' => '1',
            'data_emissao' => $this->normalizeDate($emissaoRaw),
            'valor' => $valor,
            'descricao' => $descricao,
            'service_code' => $serviceCode,
            'iss_value' => $issValue > 0 ? $issValue : null,
            'tax_amount' => $issValue > 0 ? $issValue : null,
            'invoice_type' => 'service',
            'tomador_name' => $tomadorName,
            'tomador_document' => $tomadorDocument,
            'source' => 'nfse',
        ];
    }

    /**
     * @param  array<int, string>  $expressions
     */
    private function firstString(DOMXPath $xpath, array $expressions): ?string
    {
        foreach ($expressions as $expression) {
            $value = trim((string) $xpath->evaluate("string({$expression})"));
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    /**
     * @param  array<int, string>  $expressions
     */
    private function firstAmount(DOMXPath $xpath, array $expressions, ?float $default = null): ?float
    {
        foreach ($expressions as $expression) {
            $value = trim((string) $xpath->evaluate("string({$expression})"));
            if ($value !== '' && is_numeric(str_replace(',', '.', $value))) {
                return round((float) str_replace(',', '.', $value), 2);
            }
        }

        return $default;
    }

    /**
     * @param  array<int, string>  $expressions
     */
    private function firstDocument(DOMXPath $xpath, array $expressions): ?string
    {
        $raw = $this->firstString($xpath, $expressions);
        if (! $raw) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $raw);

        return $digits !== '' ? $digits : null;
    }

    private function normalizeDate(?string $raw): string
    {
        if (! $raw) {
            return now()->format('Y-m-d');
        }

        $raw = trim($raw);
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $raw, $m)) {
            return $m[1];
        }

        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})/', $raw, $m)) {
            return "{$m[3]}-{$m[2]}-{$m[1]}";
        }

        try {
            return \Carbon\Carbon::parse($raw)->format('Y-m-d');
        } catch (\Throwable) {
            return now()->format('Y-m-d');
        }
    }
}
