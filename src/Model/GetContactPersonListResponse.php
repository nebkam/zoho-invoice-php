<?php

namespace Nebkam\ZohoInvoice\Model;

class GetContactPersonListResponse extends ApiResponse
	{
	/**
	 * @var ContactPerson[]
	 */
	private array $contactPersons;

	/**
	 * @return ContactPerson[]
	 */
	public function getContactPersons(): array
		{
		return $this->contactPersons;
		}

	/**
	 * @param ContactPerson[] $contactPersons
	 * @return GetContactPersonListResponse
	 */
	public function setContactPersons(array $contactPersons): self
		{
		$this->contactPersons = $contactPersons;

		return $this;
		}
	}
