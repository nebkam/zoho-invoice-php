<?php

namespace Nebkam\ZohoInvoice;

use Exception;
use Nebkam\ZohoInvoice\Model\ApiResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ZohoInvoiceException extends Exception
	{
	public static function fromDateTimeException(Exception $exception): self
		{
		return new self($exception->getMessage(), $exception->getCode(), $exception);
		}

	public static function fromExceptionInterface(ExceptionInterface $exception): self
		{
		return new self($exception->getMessage(), $exception->getCode(), $exception);
		}

	/**
	 * @param TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $exception
	 * @return static
	 */
	public static function fromHttpClientException($exception): self
		{
		return new self($exception->getMessage(), $exception->getCode(), $exception);
		}

	public static function fromResponse(ApiResponse $response): self
		{
		return new self(
			$response->getMessage(),
			$response->getCode()
		);
		}
	}
