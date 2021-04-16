<?php

namespace Nebkam\ZohoInvoice\Exception;

use Exception;
//use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ZohoInvoiceTransferException extends Exception
	{
	public static function fromSerializationException(ExceptionInterface $exception): self
		{
		return new self($exception->getMessage(), $exception->getCode(), $exception);
		}
	public static function fromStatusCode(int $statusCode): self
		{
		return new self(sprintf('Unexpected response status code: %d', $statusCode), $statusCode);
		}
//	public static function fromGuzzleException(GuzzleException $exception): self
//		{
//		return new self($exception->getMessage(), $exception->getCode(), $exception);
//		}
	}
