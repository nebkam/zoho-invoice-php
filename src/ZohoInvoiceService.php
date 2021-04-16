<?php

namespace Nebkam\ZohoInvoice;

use Nebkam\ZohoOAuth\ZohoOAuthResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ZohoInvoiceService
	{
	private ApiSerializer $serializer;
	private HttpClientInterface $client;
	private ?string $apiDomain;
	private ?string $accessToken;

	public function __construct(
		HttpClientInterface $client,
		ZohoOAuthResponse $credentials)
		{
		$this->serializer = new ApiSerializer();
		$this->client      = $client;
		$this->apiDomain   = $credentials->apiDomain;
		$this->accessToken = $credentials->accessToken;
		}
	}
