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

	/**
	 * @return ZohoInvoiceService
	 */
	public function testInit(): ZohoInvoiceService
		{
		$auth    = new ZohoOAuthService(
			new NativeHttpClient(),
			self::createSerializer(),
			getenv('CLIENT_ID'),
			getenv('CLIENT_SECRET'),
			getenv('CREDENTIALS_PATH')
		);
		$auth->refreshAccessToken();
		$service = new ZohoInvoiceService(new NativeHttpClient(), $auth);
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
			->setEmail('demo.agencija.2@4z.rs');
		$contactPerson = $service->createContactPerson($contactPerson);
		$this->assertNotNull($contactPerson->getContactPersonId());

		return [
			$service,
			$contactPerson
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
	 */
	public function testParseInvoiceFromWebhook(ZohoInvoiceService $service): void
		{
		$json    = file_get_contents(__DIR__ . '/zoho_invoice_create_invoice.json');
		$invoice = $service->parseInvoiceFromWebhook($json);
		$this->assertNotNull($invoice);
		$this->assertEquals('11978000001234119', $invoice->getCustomerId());
		$this->assertEquals('11978000001804003', $invoice->getSalespersonId());
		$this->assertEquals('inv013604', $invoice->getInvoiceNumber());
		$this->assertEquals('2003320', $invoice->getReferenceNumber());
		$this->assertEquals(15, $invoice->getDiscountPercent());
		$this->assertEquals(3750, $invoice->getDiscountAmount());
		$this->assertEquals(25500, $invoice->getTotal());
		$this->assertEquals('2020-12-21', $invoice->getCreatedTime()->format('Y-m-d'));
		$this->assertNotEmpty($invoice->getLineItems());
		$lineItem = $invoice->getLineItems()[0];
		$this->assertEquals('11978000000177482', $lineItem->getItemId());
		$this->assertEquals(25000, $lineItem->getRate());
		$this->assertEquals(1, $lineItem->getQuantity());
		$this->assertEquals(20, $lineItem->getTaxPercentage());
		}
	}
