<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Nebkam\ZohoInvoice\Model\Contact;
use Nebkam\ZohoInvoice\Model\ContactPerson;
use Nebkam\ZohoInvoice\Serializer\NotNullJsonEncoder;
use Nebkam\ZohoInvoice\ZohoInvoiceException;
use Nebkam\ZohoInvoice\ZohoInvoiceService;
use Nebkam\ZohoOAuth\ZohoOAuthService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ZohoInvoiceServiceTest extends TestCase
	{
	private static function createSerializer(): SerializerInterface
		{
		$classMetadataFactory   = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
		$snakeCaseNameConverter = new CamelCaseToSnakeCaseNameConverter();
		$objectNormalizer       = new ObjectNormalizer(
			$classMetadataFactory,
			$snakeCaseNameConverter,
			null,
			new PhpDocExtractor()
		);
		$dateTimeNormalizer     = new DateTimeNormalizer([
			DateTimeNormalizer::FORMAT_KEY   => 'd.m.Y. H:i',
			DateTimeNormalizer::TIMEZONE_KEY => new DateTimeZone('Europe/Belgrade')
		]);

		return new Serializer(
			[$dateTimeNormalizer, $objectNormalizer, new ArrayDenormalizer()],
			[new NotNullJsonEncoder()]
		);
		}

	private static function createValidator(): ValidatorInterface
		{
		return Validation::createValidatorBuilder()
			->enableAnnotationMapping(true)
			->addDefaultDoctrineAnnotationReader()
			->getValidator();
		}

	private static function createAuth(): ZohoOAuthService
		{
		$auth = new ZohoOAuthService(
			new NativeHttpClient(),
			self::createSerializer(),
			getenv('CLIENT_ID'),
			getenv('CLIENT_SECRET'),
			getenv('CREDENTIALS_PATH')
		);
		$auth->refreshAccessToken();

		return $auth;
		}

	/**
	 * @group webhook-parse-invoice
	 * @return ZohoInvoiceService
	 */
	public function testInit(): ZohoInvoiceService
		{
		$service = new ZohoInvoiceService(
			new NativeHttpClient(),
			self::createValidator(),
			self::createAuth()
		);
		$this->assertNotNull($service);

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
		$this->assertNotEmpty($contact->getContactId());

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
	 * @return array
	 * @throws ZohoInvoiceException
	 */
	public function testActivateContact(array $params): array
		{
		/**
		 * @var ZohoInvoiceService $service
		 */
		[$service, $contact] = $params;
		$contactId = $contact->getContactId();

		$activation =  $service->activateContact($contactId);
		$this->assertEquals(0, $activation->getCode());
		$this->assertEquals('The contact has been marked as active.', $activation->getMessage());

		return $params;
		}

	/**
	 * @depends testActivateContact
	 * @param array $params
	 * @throws ZohoInvoiceException
	 */
	public function testDeactivateContact(array $params): void
		{
		/**
		 * @var ZohoInvoiceService $service
		 */
		[$service, $contact] = $params;
		$contactId = $contact->getContactId();

		$deactivate = $service->deactivateContact($contactId);
		$this->assertEquals(0, $deactivate->getCode());
		$this->assertEquals('The contact has been marked as inactive.', $deactivate->getMessage());
		}

	/**
	 * @depends testCreateContact
	 * @param array $params
	 * @throws ZohoInvoiceException
	 */
	public function testUpdateContact(array $params): void
		{
		/**
		 * @var ZohoInvoiceService $service
		 * @var ContactPerson $contactPerson
		 * @var Contact $contact
		 */
		[$service, $contact] = $params;
		$contact->setCompanyName('Demo profil agencije3')
			->setContactName('Demo profil agencije3')
			->setWebsite('https://4z2.rs');
		$contactUpdated =  $service->updateContact($contact);
		$this->assertNotEmpty($contactUpdated->getContactId());
		$this->assertEquals('https://4z2.rs', $contactUpdated->getWebsite());
		$this->assertEquals('Demo profil agencije3', $contactUpdated->getCompanyName());
		$this->assertEquals('Demo profil agencije3', $contactUpdated->getContactName());
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
		$this->assertEquals($contact->getContactName(), $loadedContact->getContactName());
		$this->assertEquals($contact->getCompanyName(), $loadedContact->getCompanyName());
		$this->assertEquals($contact->getWebsite(), $loadedContact->getWebsite());
		}

	/**
	 * @depends testCreateContact
	 * @param array $params
	 * @return array
	 * @throws ZohoInvoiceException
	 */
	public function testCreateContactPerson(array $params): array
		{
		/**
		 * @var ZohoInvoiceService $service
		 * @var Contact $contact
		 */
		[$service, $contact] = $params;
		$contactPerson = (new ContactPerson())
			->setContactId($contact->getContactId())
			->setFirstName('Demo profil agencije2')
			->setLastName('Kontakt')
			->setPhone('381691234567890')
			->setEmail('demo.agencija.2@4z.rs');
		$contactPerson = $service->createContactPerson($contactPerson);
		$this->assertNotNull($contactPerson->getContactPersonId());

		return [
			$service,
			$contactPerson,
			$contact
		];
		}

	/**
	 * @depends testCreateContactPerson
	 * @param array $params
	 * @throws ZohoInvoiceException
	 */
	public function testGetContactPerson(array $params): void
		{
		/**
		 * @var ZohoInvoiceService $service
		 * @var ContactPerson $contactPerson
		 */
		[$service, $contactPerson] = $params;
		$loadedContactPerson = $service->getContactPerson(
			$contactPerson->getContactId(),
			$contactPerson->getContactPersonId()
		);
		$this->assertEquals($contactPerson->getEmail(), $loadedContactPerson->getEmail());
		}

	/**
	 * @depends testCreateContactPerson
	 * @param array $params
	 * @throws ZohoInvoiceException
	 */
	public function testPopulatedContact(array $params): void
		{
		/**
		 * @var ZohoInvoiceService $service
		 * @var ContactPerson $contactPerson
		 * @var Contact $contact
		 */
		[$service, $contactPerson, $contact] = $params;
		$contact = $service->getContact($contact->getContactId());
		$this->assertNotEmpty($contact->getContactId());
		$this->assertEquals($contactPerson->getEmail(), $contact->getEmail());
		$this->assertEquals($contactPerson->getPhone(), $contact->getPhone());
		$this->assertEquals('https://4z2.rs', $contact->getWebsite());
		$this->assertEquals('Demo profil agencije3', $contact->getCompanyName());
		$this->assertEquals('Demo profil agencije3', $contact->getContactName());
		}

	/**
	 * @depends testCreateContactPerson
	 * @param array $params
	 * @throws ZohoInvoiceException
	 */
	public function testUpdateContactPerson(array $params): void
		{
		/**
		 * @var ZohoInvoiceService $service
		 * @var ContactPerson $contactPerson
		 */
		[$service, $contactPerson] = $params;
		$contactPerson
			->setPhone('381699876543210')
			->setEmail('demo.agencija.3@4z.rs');
		$contact = $service->updateContactPerson($contactPerson);
		$this->assertNotEmpty($contact->getContactId());
		$this->assertEquals('demo.agencija.3@4z.rs', $contact->getEmail());
		$this->assertEquals('381699876543210', $contact->getPhone());
		}

	/**
	 * @depends testCreateContactPerson
	 * @param array $params
	 * @throws ZohoInvoiceException
	 */
	public function testDeleteContactPerson(array $params): void
		{
		/**
		 * @var ZohoInvoiceService $service
		 * @var ContactPerson $contact
		 */
		[$service, $contact] = $params;
		$result = $service->deleteContactPerson($contact->getContactPersonId());
		$this->assertTrue($result->isSuccessful());
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
		$this->assertTrue($result->isSuccessful());
		}

	/**
	 * @depends testInit
	 * @throws ZohoInvoiceException
	 */
	public function testGetInvoiceById(ZohoInvoiceService $service): void
		{
		$invoice = $service->getInvoice('11978000000311915');
		$this->assertEquals('inv000999', $invoice->getInvoiceNumber());
		}

	/**
	 * @depends testInit
	 * @throws ZohoInvoiceException
	 * @throws Exception
	 */
	public function testParseEstimateFromWebhookNoLineItems(ZohoInvoiceService $service): void
		{
		$this->expectException(ZohoInvoiceException::class);
		$this->expectExceptionMessageMatches('/lineItems/');
		$json = file_get_contents(__DIR__ . '/webhook_payloads/estimate_no_line_items.json');
		$service->parseEstimateFromWebhook($json);
		}

	/**
	 * @depends testInit
	 * @throws ZohoInvoiceException
	 * @throws Exception
	 */
	public function testParseEstimateFromWebhookNoDate(ZohoInvoiceService $service): void
		{
		$this->expectException(ZohoInvoiceException::class);
		$this->expectExceptionMessageMatches('/date/');
		$json = file_get_contents(__DIR__ . '/webhook_payloads/estimate_no_date.json');
		$service->parseEstimateFromWebhook($json);
		}

	/**
	 * @depends testInit
	 * @throws ZohoInvoiceException
	 * @throws Exception
	 */
	public function testParseEstimateFromWebhookInvalidDate(ZohoInvoiceService $service): void
		{
		$this->expectException(ZohoInvoiceException::class);
		$this->expectExceptionMessageMatches('/date/');
		$json = file_get_contents(__DIR__ . '/webhook_payloads/estimate_invalid_date.json');
		$service->parseEstimateFromWebhook($json);
		}

	/**
	 * @depends testInit
	 * @throws ZohoInvoiceException
	 * @throws Exception
	 */
	public function testParseEstimateFromWebhook(ZohoInvoiceService $service): void
		{
		$json     = file_get_contents(__DIR__ . '/webhook_payloads/estimate.json');
		$estimate = $service->parseEstimateFromWebhook($json);
		$this->assertEquals('EST-000001', $estimate->getEstimateNumber());
		$this->assertEquals('177517000000038027', $estimate->getCustomerId());
		$this->assertEquals('2021-05-24', $estimate->getDateAsDateTime()->format('Y-m-d'));
		$this->assertEquals(0, $estimate->getDiscountPercent());
		$this->assertEquals(1200, $estimate->getTotal());
		$lineItem = $estimate->getLineItems()[0];
		$this->assertEquals('177517000000038084', $lineItem->getItemId());
		$this->assertEquals(1000, $lineItem->getRate());
		$this->assertEquals(1, $lineItem->getQuantity());
		}

	/**
	 * @group webhook-parse-invoice
	 * @depends testInit
	 * @throws ZohoInvoiceException
	 * @throws Exception
	 */
	public function testParseInvoiceWithDiscountFromWebhook(ZohoInvoiceService $service): void
		{
		$json    = file_get_contents(__DIR__ . '/webhook_payloads/invoice-with-item-discounts.json');
		$invoice = $service->parseInvoiceFromWebhook($json);
		$this->assertNotNull($invoice);
		$this->assertEquals('inv019203', $invoice->getInvoiceNumber());
		$this->assertEquals('11978000004920021', $invoice->getInvoiceId());
		$this->assertEquals('11978000000028119', $invoice->getCustomerId());
		$this->assertEquals(16.88895, $invoice->getDiscountPercent());
		$this->assertEquals(124448.4, $invoice->getTotal());
		$this->assertEquals('2022-02-14', $invoice->getDateAsDateTime()->format('Y-m-d'));
		$this->assertNotEmpty($invoice->getLineItems());
		$lineItems = $invoice->getLineItems();
		$this->assertCount(4, $lineItems);
		$firstItem = $lineItems[0];
		$this->assertEquals('11978000004734019', $firstItem->getItemId());
		$this->assertEquals(15725, $firstItem->getRate());
		$this->assertEquals('Ekskluziv+ 100', $firstItem->getName());
		$this->assertEquals(10, $firstItem->getDiscountPercentage());
		$this->assertEquals(1, $firstItem->getQuantity());
		$this->assertEquals(20, $firstItem->getTaxPercentage());
		$this->assertEquals(14152.5, $firstItem->getItemTotal());
		$firstItem = $lineItems[1];
		$this->assertEquals('11978000000177482', $firstItem->getItemId());
		$this->assertEquals(25000, $firstItem->getRate());
		$this->assertEquals('Paket kredita 5000', $firstItem->getName());
		$this->assertEquals(15, $firstItem->getDiscountPercentage());
		$this->assertEquals(1, $firstItem->getQuantity());
		$this->assertEquals(20, $firstItem->getTaxPercentage());
		$this->assertEquals(21250, $firstItem->getItemTotal());
		$firstItem = $lineItems[2];
		$this->assertEquals('11978000000177490', $firstItem->getItemId());
		$this->assertEquals(50000, $firstItem->getRate());
		$this->assertEquals('Paket kredita 10000', $firstItem->getName());
		$this->assertEquals(20, $firstItem->getDiscountPercentage());
		$this->assertEquals(1, $firstItem->getQuantity());
		$this->assertEquals(20, $firstItem->getTaxPercentage());
		$this->assertEquals(40000, $firstItem->getItemTotal());
		$firstItem = $lineItems[3];
		$this->assertEquals('11978000004734019', $firstItem->getItemId());
		$this->assertEquals(15725, $firstItem->getRate());
		$this->assertEquals('Ekskluziv+ 100', $firstItem->getName());
		$this->assertEquals(10, $firstItem->getDiscountPercentage());
		$this->assertEquals(2, $firstItem->getQuantity());
		$this->assertEquals(20, $firstItem->getTaxPercentage());
		$this->assertEquals(28305, $firstItem->getItemTotal());
		$this->assertEquals($firstItem->getRate() * (100 - $firstItem->getDiscountPercentage())/100 * $firstItem->getQuantity(), $firstItem->getItemTotal());
		}

	/**
	 * @group webhook-parse-invoice
	 * @depends testInit
	 * @throws ZohoInvoiceException
	 * @throws Exception
	 */
	public function testParseDiscountMultiplier(ZohoInvoiceService $service): void
		{
		$json    = file_get_contents(__DIR__ . '/webhook_payloads/invoice-with-item-discounts.json');
		$invoice = $service->parseInvoiceFromWebhook($json);
		$this->assertNotNull($invoice);
		$lineItems = $invoice->getLineItems();
		$this->assertCount(4, $lineItems);
		$firstItem = $lineItems[3];
		$this->assertEquals('11978000004734019', $firstItem->getItemId());
		$this->assertEquals(15725, $firstItem->getRate());
		$this->assertEquals(10, $firstItem->getDiscountPercentage());
		$this->assertEquals(14152.5, $firstItem->getPriceWithDiscount());
		$this->assertEquals(16983, $firstItem->getPriceWithDiscountAndTax());
		$this->assertEquals(28305, $firstItem->getValueWithDiscount());
		$this->assertEquals(33966, $firstItem->getValueWithDiscountAndTax());
		}
	}
