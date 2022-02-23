<?php

namespace Nebkam\ZohoInvoice;

use Nebkam\ZohoInvoice\Model\ApiResponse;
use Nebkam\ZohoInvoice\Model\Contact;
use Nebkam\ZohoInvoice\Model\ContactPerson;
use Nebkam\ZohoInvoice\Model\CreateEstimateWebhook;
use Nebkam\ZohoInvoice\Model\CreateInvoiceWebhook;
use Nebkam\ZohoInvoice\Model\Estimate;
use Nebkam\ZohoInvoice\Model\GetContactPersonResponse;
use Nebkam\ZohoInvoice\Model\GetContactResponse;
use Nebkam\ZohoInvoice\Model\GetInvoiceResponse;
use Nebkam\ZohoInvoice\Model\Invoice;
use Nebkam\ZohoInvoice\Serializer\ApiSerializer;
use Nebkam\ZohoOAuth\ZohoOAuthException;
use Nebkam\ZohoOAuth\ZohoOAuthService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
	private ValidatorInterface $validator;
	private ZohoOAuthService $authService;

	public function __construct(
		HttpClientInterface $client,
		ValidatorInterface  $validator,
		ZohoOAuthService    $authService)
		{
		$this->serializer  = new ApiSerializer();
		$this->client      = $client;
		$this->validator   = $validator;
		$this->authService = $authService;
		}

	/**
	 * @param Contact $contact
	 * @return Contact
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function createContact(Contact $contact): Contact
		{
		$response = $this->makePostRequest('contacts', $contact, GetContactResponse::class);

		/** @var GetContactResponse $response */
		return $response->getContact();
		}

	/**
	 * @param Contact $contactPerson
	 * @return Contact
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function updateContact(Contact $contactPerson): Contact
		{
		$response = $this->makePutRequest(sprintf('contacts/%s', $contactPerson->getContactId()), $contactPerson, GetContactResponse::class);

		/** @var GetContactResponse $response */
		return $response->getContact();
		}

	/**
	 * @param string $contactId
	 * @return ApiResponse
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function deactivateContact(string $contactId): ApiResponse
		{
		return $this->makePostRequest(sprintf('contacts/%s/inactive', $contactId), null, ApiResponse::class);
		}

	/**
	 * @param string $contactId
	 * @return ApiResponse
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function activateContact(string $contactId): ApiResponse
		{
		return $this->makePostRequest(sprintf('contacts/%s/active', $contactId), null, ApiResponse::class);
		}

	/**
	 * @param string $id
	 * @return Contact
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function getContact(string $id): Contact
		{
		$response = $this->makeGetRequest('contacts/' . $id, GetContactResponse::class);

		/** @var GetContactResponse $response */
		return $response->getContact();
		}

	/**
	 * @param string $id
	 * @return ApiResponse
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function deleteContact(string $id): ApiResponse
		{
		return $this->makeDeleteRequest('contacts/' . $id);
		}

	/**
	 * @param ContactPerson $contactPerson
	 * @return ContactPerson
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function createContactPerson(ContactPerson $contactPerson): ContactPerson
		{
		$response = $this->makePostRequest('contacts/contactpersons', $contactPerson, GetContactPersonResponse::class);

		/** @var GetContactPersonResponse $response */
		return $response->getContactPerson();
		}

	/**
	 * @param ContactPerson $contactPerson
	 * @return ContactPerson
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function updateContactPerson(ContactPerson $contactPerson): ContactPerson
		{
		$response = $this->makePutRequest(sprintf('contacts/contactpersons/%s', $contactPerson->getContactPersonId()), $contactPerson, GetContactPersonResponse::class);

		/** @var GetContactPersonResponse $response */
		return $response->getContactPerson();
		}

	/**
	 * @param string $contactId
	 * @param string $contactPersonId
	 * @return ContactPerson
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function getContactPerson(string $contactId, string $contactPersonId): ContactPerson
		{
		$response = $this->makeGetRequest(
			sprintf('contacts/%s/contactpersons/%s', $contactId, $contactPersonId),
			GetContactPersonResponse::class
		);

		/** @var GetContactPersonResponse $response */
		return $response->getContactPerson();
		}

	/**
	 * @param string $id
	 * @return ApiResponse
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function deleteContactPerson(string $id): ApiResponse
		{
		return $this->makeDeleteRequest('contacts/contactpersons/' . $id);
		}

	/**
	 * @param string $id
	 * @return Invoice
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	public function getInvoice(string $id): Invoice
		{
		$response = $this->makeGetRequest('invoices/' . $id, GetInvoiceResponse::class);

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

		return $this->validate($webhook->getInvoice());
		}

	/**
	 * @param string $json
	 * @return Estimate
	 * @throws ZohoInvoiceException
	 */
	public function parseEstimateFromWebhook(string $json): Estimate
		{
		/** @var CreateEstimateWebhook $webhook */
		$webhook = $this->serializer->deserialize($json, CreateEstimateWebhook::class);

		return $this->validate($webhook->getEstimate());
		}

	/**
	 * @param Invoice|Estimate
	 * @return Invoice|Estimate
	 * @throws ZohoInvoiceException
	 */
	private function validate($document)
		{
		$errors = $this->validator->validate($document);
		if (count($errors) > 0)
			{
			throw new ZohoInvoiceException(sprintf('Validation failed: %s', (string)$errors));
			}

		return $document;
		}

	/**
	 * @param string $url
	 * @param object|null $payload
	 * @param string $responseClass
	 * @return ApiResponse
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	private function makePostRequest(string $url, ?object $payload, string $responseClass): ApiResponse
		{
		$body = $payload ? $this->serializePayload($payload) : [];
		return $this->makeRequest('POST', $url, $body, $responseClass);
		}

	/**
	 * @param string $url
	 * @param object|null $payload
	 * @param string $responseClass
	 * @return ApiResponse
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	private function makePutRequest(string $url, ?object $payload, string $responseClass): ApiResponse
		{
		$body = $payload ? $this->serializePayload($payload) : [];
		return $this->makeRequest('PUT', $url, $body, $responseClass);
		}

	/**
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	private function makeGetRequest(string $url, string $responseClass): ApiResponse
		{
		return $this->makeRequest('GET', $url, [], $responseClass);
		}

	/**
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	private function makeDeleteRequest(string $url): ApiResponse
		{
		return $this->makeRequest('DELETE', $url, [], ApiResponse::class);
		}

	/**
	 * @param string $method
	 * @param string $url
	 * @param array $options
	 * @param string $responseClass
	 * @return ApiResponse
	 * @throws ZohoInvoiceException
	 * @throws ZohoOAuthException
	 */
	private function makeRequest(string $method, string $url, array $options, string $responseClass): ApiResponse
		{
		$options = array_merge($options, [
			'headers' => $this->getHeaders()
		]);
		try
			{
			$response = $this->client->request($method, self::BASE_URI . $url, $options);
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

	private function serializePayload(object $payload): array
		{
		return [
			'body' => [
				'JSONString' => $this->serializer->serialize($payload)
			]
		];
		}

	/**
	 * @return array
	 * @throws ZohoOAuthException
	 */
	private function getHeaders(): array
		{
		return [
			'Authorization' => sprintf('Zoho-oauthtoken %s', $this->authService->getCredentials()->accessToken)
		];
		}
	}
