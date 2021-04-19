<?php

use Nebkam\ZohoInvoice\Model\Contact;
use Nebkam\ZohoInvoice\ZohoInvoiceException;
use Nebkam\ZohoInvoice\ZohoInvoiceService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\NativeHttpClient;

class ZohoInvoiceServiceTest extends TestCase
	{
	public function testInit(): ZohoInvoiceService
		{
		$service = new ZohoInvoiceService(new NativeHttpClient(), getenv('ACCESS_TOKEN'));
		self::assertNotNull($service);

		return $service;
		}

	/**
	 * @depends testInit
	 * @param ZohoInvoiceService $service
	 * @return array
	 * @throws ZohoInvoiceException
	 */
	public function testCreateContact(ZohoInvoiceService $service): array
		{
		$data    = (new Contact())
			->setCompanyName('Demo profil agencije2')
			->setContactName('Demo profil agencije2')
			->setWebsite('https://4z.rs');
		$contact = $service->createContact($data);
		self::assertNotEmpty($contact->getContactId());

		return [
			$service,
			$contact
		];
		}

	/**
	 * @depends testCreateContact
	 * @param array $params
	 */
	public function testExceptionOnDuplicateContact(array $params): void
		{
		/**
		 * @var ZohoInvoiceService $service
		 */
		[$service] = $params;
		$data = (new Contact())
			->setCompanyName('Demo profil agencije2')
			->setContactName('Demo profil agencije2')
			->setWebsite('https://4z.rs');
		$this->expectException(ZohoInvoiceException::class);
		$service->createContact($data);
		}

	/**
	 * @depends testCreateContact
	 * @param array $params
	 * @throws ZohoInvoiceException
	 */
	public function testGetContact(array $params): void
		{
		/**
		 * @var ZohoInvoiceService $service
		 * @var Contact $contact
		 */
		[$service, $contact] = $params;
		$loadedContact = $service->getContact($contact->getContactId());
		self::assertEquals($contact->getContactName(), $loadedContact->getContactName());
		self::assertEquals($contact->getCompanyName(), $loadedContact->getCompanyName());
		self::assertEquals($contact->getWebsite(), $loadedContact->getWebsite());
		}

	/**
	 * @depends testCreateContact
	 * @param array $params
	 * @throws ZohoInvoiceException
	 */
	public function testDeleteContact(array $params): void
		{
		/**
		 * @var ZohoInvoiceService $service
		 * @var Contact $contact
		 */
		[$service, $contact] = $params;
		$result = $service->deleteContact($contact->getContactId());
		self::assertEquals(0, $result->getCode());
		}

	/**
	 * @depends testInit
	 * @throws ZohoInvoiceException
	 */
	public function testGetInvoiceById(ZohoInvoiceService $service): void
		{
		$invoice = $service->getInvoice('11978000000311915');
		self::assertEquals('inv000999', $invoice->getInvoiceNumber());
		}

	/**
	 * @depends testInit
	 * @throws ZohoInvoiceException
	 */
	public function testParseInvoiceFromWebhook(ZohoInvoiceService $service): void
		{
		$json    = file_get_contents(__DIR__ . '/zoho_invoice_create_invoice.json');
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
