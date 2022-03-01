<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Nebkam\ZohoInvoice\Model\Contact;
use Nebkam\ZohoInvoice\Model\Estimate;
use Nebkam\ZohoInvoice\Model\Invoice;
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

class CleanupTest extends TestCase
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

	public static function setUpBeforeClass(): void
		{
		self::markTestSkipped('
			Only for manual cleanup if there are leftover data from tests!
			(Change ID inside individual test that you want to remove from zoho and call phpunit with the correct `--group` value), 
			this test is excluded inside xml.');
		parent::setUpBeforeClass();
		}

	/**
	 * @group estimate
	 * @group invoice
	 * @group contact
	 * @group contactPerson
	 * @group attachment
	 * @return ZohoInvoiceService
	 */
	public function testInitForCleanup(): ZohoInvoiceService
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
	 * @group estimate
	 * @depends testInitForCleanup
	 * @param ZohoInvoiceService $service
	 * @return void
	 */
	public function testCleanupEstimateWithId(ZohoInvoiceService $service): void
		{
		$estimate = (new Estimate())->setEstimateId("CHANGE ME");
		$result   = $service->deleteEstimate($estimate);
		$this->assertTrue($result->isSuccessful());
		}

	/**
	 * @group invoice
	 * @depends testInitForCleanup
	 * @param ZohoInvoiceService $service
	 * @return void
	 */
	public function testCleanupInvoiceWithId(ZohoInvoiceService $service): void
		{
		$invoice = (new Invoice)->setInvoiceId("CHANGE ME");
		$result  = $service->deleteInvoice($invoice);
		$this->assertTrue($result->isSuccessful());
		}

	/**
	 * @group contactPerson
	 * @depends testInitForCleanup
	 * @param ZohoInvoiceService $service
	 * @throws ZohoInvoiceException
	 */
	public function testCleanupContactPerson(ZohoInvoiceService $service): void
		{
		$contactPersonId = 'CHNAGE ME';
		$result  = $service->deleteContactPerson($contactPersonId);
		$this->assertTrue($result->isSuccessful());
		}

	/**
	 * @group contact
	 * @depends testInitForCleanup
	 * @param ZohoInvoiceService $service
	 * @throws ZohoInvoiceException
	 */
	public function testCleanupDeleteContact(ZohoInvoiceService $service): void
		{
		$contact = (new Contact())->setContactId("CHANGE ME");
		$result  = $service->deleteContact($contact->getContactId());
		$this->assertTrue($result->isSuccessful());
		}


	/**
	 * @group attachment
	 * @depends testInitForCleanup
	 * @param ZohoInvoiceService $service
	 * @return void
	 * @throws ZohoInvoiceException
	 */
	public function testCleanupAttachment(ZohoInvoiceService $service): void
		{
		$invoice = (new Invoice())->setInvoiceId("CHANGE ME");
		$response = $service->removeAttachmentFromInvoice($invoice->getInvoiceId());
		$this->assertEquals('Your file is no longer attached to the invoice.', $response->getMessage());
		}
	}
