<?php

use Nebkam\ZohoInvoice\ZohoInvoiceService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class ZohoInvoiceServiceTest extends TestCase
	{
	public function testParseInvoiceFromWebhook(): void
		{
		$service = new ZohoInvoiceService(new MockHttpClient(), getenv('ACCESS_TOKEN'));
		$json = file_get_contents(__DIR__ . '/zoho_invoice_create_invoice.json');
		$invoice = $service->parseInvoiceFromWebhook($json);
		self::assertNotNull($invoice);
		self::assertEquals('11978000001234119', $invoice->getCustomerId());
		self::assertEquals('11978000001804003', $invoice->getSalespersonId());
		self::assertEquals('inv013604', $invoice->getInvoiceNumber());
		self::assertEquals('2003320', $invoice->getReferenceNumber());
		self::assertEquals(15, $invoice->getDiscountPercent());
		self::assertEquals(3750, $invoice->getDiscountAmount());
		self::assertEquals(25500, $invoice->getTotal());
		self::assertEquals('2020-12-21', $invoice->getCreatedTime()->format('Y-m-d'));
		self::assertNotEmpty($invoice->getLineItems());
		$lineItem = $invoice->getLineItems()[0];
		self::assertEquals('11978000000177482', $lineItem->getItemId());
		self::assertEquals(25000, $lineItem->getRate());
		self::assertEquals(1, $lineItem->getQuantity());
		self::assertEquals(20, $lineItem->getTaxPercentage());
		}
	}
