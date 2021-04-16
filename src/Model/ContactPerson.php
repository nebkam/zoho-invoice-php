<?php

namespace Nebkam\ZohoInvoice\Model;

class ContactPerson
	{
	private ?string $contactId = null;
	private ?string $contactPersonId = null;
	private string $firstName;
	private string $lastName;
	private string $email;

	public function getContactId(): ?string
		{
		return $this->contactId;
		}

	public function setContactId(?string $contactId): self
		{
		$this->contactId = $contactId;

		return $this;
		}

	public function getContactPersonId(): ?string
		{
		return $this->contactPersonId;
		}

	public function setContactPersonId(?string $contactPersonId): self
		{
		$this->contactPersonId = $contactPersonId;

		return $this;
		}

	public function getFirstName(): string
		{
		return $this->firstName;
		}

	public function setFirstName(string $firstName): self
		{
		$this->firstName = $firstName;

		return $this;
		}

	public function getLastName(): string
		{
		return $this->lastName;
		}

	public function setLastName(string $lastName): self
		{
		$this->lastName = $lastName;

		return $this;
		}

	public function getEmail(): string
		{
		return $this->email;
		}

	public function setEmail(string $email): self
		{
		$this->email = $email;

		return $this;
		}
	}
