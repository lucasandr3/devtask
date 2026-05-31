<?php

namespace Tests\Unit;

use App\Services\InvoiceXmlParserService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InvoiceXmlParserServiceTest extends TestCase
{
    #[Test]
    public function it_parses_a_minimal_nfe_xml(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<nfeProc>
  <NFe>
    <infNFe>
      <ide>
        <nNF>12345</nNF>
        <serie>1</serie>
        <dhEmi>2026-05-15T10:00:00-03:00</dhEmi>
      </ide>
      <total>
        <ICMSTot>
          <vNF>1500.50</vNF>
        </ICMSTot>
      </total>
    </infNFe>
  </NFe>
</nfeProc>
XML;

        $data = (new InvoiceXmlParserService)->parse($xml);

        $this->assertSame('12345', $data['numero']);
        $this->assertSame('1', $data['serie']);
        $this->assertSame('2026-05-15', $data['data_emissao']);
        $this->assertSame(1500.50, $data['valor']);
        $this->assertSame('product', $data['invoice_type']);
        $this->assertSame('nfe', $data['source']);
    }

    #[Test]
    public function it_parses_a_minimal_nfse_xml(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<CompNfse>
  <Nfse>
    <InfNfse>
      <Numero>99</Numero>
      <DataEmissao>2026-05-20</DataEmissao>
      <Servico>
        <Valores>
          <ValorServicos>850.00</ValorServicos>
          <ValorIss>42.50</ValorIss>
        </Valores>
        <ItemListaServico>1.01</ItemListaServico>
        <Discriminacao>Consultoria técnica</Discriminacao>
      </Servico>
    </InfNfse>
  </Nfse>
</CompNfse>
XML;

        $data = (new InvoiceXmlParserService)->parse($xml);

        $this->assertSame('99', $data['numero']);
        $this->assertSame('2026-05-20', $data['data_emissao']);
        $this->assertSame(850.00, $data['valor']);
        $this->assertSame('1.01', $data['service_code']);
        $this->assertSame('service', $data['invoice_type']);
        $this->assertSame('nfse', $data['source']);
    }
}
