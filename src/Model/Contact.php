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
	private ?string $email = null;
	private ?string $phone= null;
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

	public function getEmail(): ?string
		{
		return $this->email;
		}

	/**
	 * Field is set indirectly via primary `ContactPerson`
	 * It's only  exposed inside `Contact`
	 */
	public function setEmail(?string $email): self
		{
		$this->email = $email;

		return $this;
		}

	public function getPhone(): ?string
		{
		return $this->phone;
		}


	/**
	 * Field is set indirectly via primary `ContactPerson`
	 * It's only  exposed inside `Contact`
	 */
	public function setPhone(?string $phone): self
		{
		$this->phone = $phone;

		return $this;
		}
	}
