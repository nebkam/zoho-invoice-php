<?php

namespace Nebkam\ZohoInvoice;

use DateTimeZone;
use Doctrine\Common\Annotations\AnnotationReader;
use Nebkam\ZohoInvoice\Exception\ZohoInvoiceTransferException;
use Nebkam\ZohoInvoice\Serializer\NotNullJsonEncoder;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiSerializer
	{
	private SerializerInterface $serializer;

	public function __construct()
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

		$this->serializer = new Serializer(
			[$dateTimeNormalizer, $objectNormalizer, new ArrayDenormalizer()],
			[new NotNullJsonEncoder()]
		);
		}

	/**
	 * @param object $object
	 * @return string
	 * @throws ZohoInvoiceTransferException
	 */
	public function serialize(object $object): string
		{
		try
			{
			return $this->serializer->serialize($object, 'json');
			}
		catch (ExceptionInterface $exception)
			{
			throw ZohoInvoiceTransferException::fromSerializationException($exception);
			}
		}

	/**
	 * @param string $json
	 * @param string $className
	 * @return object|array
	 * @throws ZohoInvoiceTransferException
	 */
	public function deserialize(string $json, string $className)
		{
		try
			{
			return $this->serializer->deserialize($json, $className, 'json');
			}
		catch (ExceptionInterface $exception)
			{
			throw ZohoInvoiceTransferException::fromSerializationException($exception);
			}
		}
	}
