<?php

namespace Nebkam\ZohoInvoice\Model;

use DateTime;
use DateTimeZone;
use Exception;
use Nebkam\ZohoInvoice\ContextGroup;
use Nebkam\ZohoInvoice\ZohoInvoiceException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

abstract class Document
	{
	/**
	 * @Assert\NotBlank()
	 * @var string|null
	 */
	private ?string $customerId;
	/**
	 * @Assert\NotNull()
	 * @var float|null
	 */
	private ?float $discountPercent = 0.0;
	/**
	 * @Assert\NotBlank()
	 * @var float|null
	 */
	private ?float $total;
	/**
	 * @Assert\NotNull()
	 * @Assert\Date()
	 * @var string|null
	 */
	private ?string $date;
	/**
	 * @Assert\NotBlank()
	 * @var LineItem[]
	 */
	private array $lineItems;

	/**
	 * ReferenceNumber should e linked to the invoiceId inside erp
	 */
	private ?string $referenceNumber;

	/**
	 * @var CustomField[]|null
	 */
	private ?array $customFields = null;

	/**
	 * @Groups({
	 *     ContextGroup::CONTEXT_CREATE
	 *     })
	 *
	 * @return CustomField[]|null
	 */
	public function getCustomFields(): ?array
		{
		return $this->customFields;
		}

	public function setCustomFields(?array $customFields): void
		{
		$this->customFields = $customFields;
		}

	public function addCustomField(CustomField $customField): self
		{
		$this->customFields[] = $customField;

		return $this;
		}

	public function getCustomFieldByLabel(string $label)
		{
		if (empty($this->getCustomFields()))
			{
			return null;
			}

		foreach ($this->getCustomFields() as $field)
			{
			if ($field->getLabel() === $label)
				{
				return $field->getValue();
				}
			}
		return null;
		}

	public function getCustomFieldByApiName(string $apiName)
		{
		if (empty($this->getCustomFields()))
			{
			return null;
			}

		foreach ($this->getCustomFields() as $field)
			{
			if ($field->getApiName() === $apiName)
				{
				return $field->getValue();
				}
			}
		return null;
		}

	public function getDeliveredAt(?DateTimeZone $timezone = null): ?DateTime
		{
		if (empty($this->getCustomFields()))
			{
			return null;
			}

		foreach ($this->getCustomFields() as $field)
			{
			if ($field->getApiName() === CustomField::DELIVERED_AT_NAME)
				{
				return $field->getAsDateTime($timezone);
				}
			}

		return null;
		}

	public function getReversalReference(): ?string
		{
		if (empty($this->getCustomFields()))
			{
			return null;
			}

		foreach ($this->getCustomFields() as $field)
			{
			if ($field->getApiName() === CustomField::REVERSAL_REFERENCE)
				{
				return $field->getValue();
				}
			}

		return null;
		}

	public function getMonthsInAdvance(): ?string
		{
		if (empty($this->getCustomFields()))
			{
			return null;
			}

		foreach ($this->getCustomFields() as $field)
			{
			if ($field->getApiName() === CustomField::MONTHS_IN_ADVANCE)
				{
				return $field->getValue();
				}
			}

		return null;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getCustomerId(): ?string
		{
		return $this->customerId;
		}

	public function setCustomerId(?string $customerId): self
		{
		$this->customerId = $customerId;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getDiscountPercent(): ?float
		{
		return $this->discountPercent;
		}

	public function setDiscountPercent(?float $discountPercent): self
		{
		$this->discountPercent = $discountPercent;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getTotal(): ?float
		{
		return $this->total;
		}

	public function setTotal(?float $total): self
		{
		$this->total = $total;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getDate(): ?string
		{
		return $this->date;
		}

	/**
	 * @throws ZohoInvoiceException
	 */
	public function getDateAsDateTime(?DateTimeZone $timezone = null): ?DateTime
		{
		try
			{
			return $this->date !== null ? new DateTime($this->getDate(), $timezone) : null;
			}
		catch (Exception $e)
			{
			throw ZohoInvoiceException::fromDateTimeException($e);
			}
		}

	/**
	 * @throws ZohoInvoiceException
	 */
	public function getDueDateAsDateTime(int $days = 3, ?DateTimeZone $timezone = null): ?DateTime
		{
		if (!$this->getDateAsDateTime())
			{
			return null;
			}

		try
			{
			return (new DateTime($this->getDate(), $timezone))->modify(sprintf(' + %d days', $days));
			}
		catch (Exception $e)
			{
			throw ZohoInvoiceException::fromDateTimeException($e);
			}
		}

	public function setDate(?string $date): self
		{
		$this->date = $date;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 * @return LineItem[]
	 */
	public function getLineItems(): array
		{
		return $this->lineItems;
		}

	/**
	 * @return LineItem[]
	 */
	public function getLineItemsWithDiscount(): array
		{
		return $this->lineItems;
		}

	/**
	 * @param LineItem[] $lineItems
	 * @return self
	 */
	public function setLineItems(array $lineItems): self
		{
		$this->lineItems = $lineItems;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getReferenceNumber(): ?string
		{
		return $this->referenceNumber;
		}

	public function setReferenceNumber(?string $referenceNumber): self
		{
		$this->referenceNumber = $referenceNumber;

		return $this;
		}
	}
