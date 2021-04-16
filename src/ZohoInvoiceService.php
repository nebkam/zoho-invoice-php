<?php

namespace Nebkam\ZohoInvoice;

use Nebkam\ZohoInvoice\Model\ApiResponse;
use Nebkam\ZohoInvoice\Model\CreateInvoiceWebhook;
use Nebkam\ZohoInvoice\Model\GetInvoiceResponse;
use Nebkam\ZohoInvoice\Model\Invoice;
use Nebkam\ZohoInvoice\Serializer\ApiSerializer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ZohoInvoiceService
	{
	private const BASE_URI = 'https://invoice.zoho.eu/api/v3/';
	private ApiSerializer $serializer;
	private HttpClientInterface $client;
	private ?string $accessToken;

	public function __construct(
		HttpClientInterface $client,
		string $accessToken)
		{
		$this->serializer  = new ApiSerializer();
		$this->client      = $client;
		$this->accessToken = $accessToken;
		}

	/**
	 * @param string $id
	 * @return Invoice
	 * @throws ZohoInvoiceException
	 */
	public function getInvoice(string $id): Invoice
		{
		$response = $this->makeGetRequest('invoices/'.$id, GetInvoiceResponse::class);
		/** @var GetInvoiceResponse $response */
		return $response->getInvoice();
		}

	/**
	 * @param string $json
	 * @return Invoice
	 * @throws ZohoInvoiceException
	 */
	public function parseInvoiceFromWebhook(string $json): Invoice
		{
		/** @var CreateInvoiceWebhook $webhook */
		$webhook = $this->serializer->deserialize($json, CreateInvoiceWebhook::class);

		return $webhook->getInvoice();
		}

	/**
	 * @throws ZohoInvoiceException
	 */
	private function makeGetRequest(string $url, string $responseClass): ApiResponse
		{
		try
			{
			$response = $this->client->request('GET', self::BASE_URI.$url, [
				'headers' => [
					'Authorization' => sprintf('Zoho-oauthtoken %s', $this->accessToken)
				]
			]);
			/** @var ApiResponse $apiResponse */
			$apiResponse = $this->serializer->deserialize(
				$response->getContent(),
				$responseClass
			);
			if (!$apiResponse->isSuccessful())
				{
				throw ZohoInvoiceException::fromResponse($apiResponse);
				}

			return $apiResponse;
			}
		catch (TransportExceptionInterface|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $exception)
			{
			throw ZohoInvoiceException::fromHttpClientException($exception);
			}
		}
	}
