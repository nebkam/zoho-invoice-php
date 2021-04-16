<?php

namespace Nebkam\ZohoInvoice\Model;

/**
 * Represents our Agency in ZohoInvoice
 */
class Contact
	{
	private ?string $contactId = null;
	private string $contactName;
	private string $companyName;
	private ?string $website = null;

	public function getContactId(): ?string
		{
		return $this->contactId;
		}

	public function setContactId(?string $contactId): self
		{
		$this->contactId = $contactId;

		return $this;
		}

	public function getContactName(): string
		{
		return $this->contactName;
		}

	public function setContactName(string $contactName): self
		{
		$this->contactName = $contactName;

		return $this;
		}

	public function getCompanyName(): string
		{
		return $this->companyName;
		}

	public function setCompanyName(string $companyName): self
		{
		$this->companyName = $companyName;

		return $this;
		}

	public function getWebsite(): ?string
		{
		return $this->website;
		}

	public function setWebsite(?string $website): self
		{
		$this->website = $website;

		return $this;
		}
	}
