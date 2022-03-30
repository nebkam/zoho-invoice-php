<?php

namespace Nebkam\ZohoInvoice\Model;

use Symfony\Component\Serializer\Annotation\Groups;
use Nebkam\ZohoInvoice\ContextGroup;

class ContactPerson
	{
	private ?string $contactId = null;
	private ?string $contactPersonId = null;
	private string $firstName;
	private string $lastName;
	private string $email;
	private ?string $phone = null;
	private ?string $mobile = null;
	private ?bool $isPrimaryContact = null;

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

	/**
	 * @Groups({ContextGroup::CONTEXT_UPDATE})
	 */
	public function getFirstName(): string
		{
		return $this->firstName;
		}

	public function setFirstName(string $firstName): self
		{
		$this->firstName = $firstName;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_UPDATE})
	 */
	public function getLastName(): string
		{
		return $this->lastName;
		}

	public function setLastName(string $lastName): self
		{
		$this->lastName = $lastName;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_UPDATE})
	 */
	public function getEmail(): string
		{
		return $this->email;
		}

	public function setEmail(string $email): self
		{
		$this->email = $email;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_UPDATE})
	 */
	public function getPhone(): ?string
		{
		return $this->phone;
		}

	public function setPhone(?string $phone): self
		{
		$this->phone = $phone;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_UPDATE})
	 */
	public function getMobile(): ?string
		{
		return $this->mobile;
		}

	public function setMobile(?string $mobile): self
		{
		$this->mobile = $mobile;

		return $this;
		}

	public function getIsPrimaryContact(): ?bool
		{
		return $this->isPrimaryContact;
		}

	public function setIsPrimaryContact(?bool $isPrimaryContact): self
		{
		$this->isPrimaryContact = $isPrimaryContact;

		return $this;
		}
	}
