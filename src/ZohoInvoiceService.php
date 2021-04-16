<?php

namespace Nebkam\ZohoInvoice;

use Nebkam\ZohoInvoice\Model\CreateInvoiceWebhook;
use Nebkam\ZohoInvoice\Model\Invoice;
use Nebkam\ZohoInvoice\Serializer\ApiSerializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ZohoInvoiceService
	{
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
	}
