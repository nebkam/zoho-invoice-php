<?php

namespace Nebkam\ZohoInvoice;

use Exception;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class ZohoInvoiceException extends Exception
	{
	public static function fromExceptionInterface(ExceptionInterface $exception): self
		{
		return new self($exception->getMessage(), $exception->getCode(), $exception);
		}
	}
