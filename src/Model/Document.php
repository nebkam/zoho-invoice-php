<?php

namespace Nebkam\ZohoInvoice\Model;

use DateTime;
use Exception;
use Nebkam\ZohoInvoice\ZohoInvoiceException;
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

	public function getCustomerId(): ?string
		{
		return $this->customerId;
		}

	public function setCustomerId(?string $customerId): self
		{
		$this->customerId = $customerId;

		return $this;
		}

	public function getDiscountPercent(): ?float
		{
		return $this->discountPercent;
		}

	public function setDiscountPercent(?float $discountPercent): self
		{
		$this->discountPercent = $discountPercent;

		return $this;
		}

	public function getTotal(): ?float
		{
		return $this->total;
		}

	public function setTotal(?float $total): self
		{
		$this->total = $total;

		return $this;
		}

	public function getDate(): ?string
		{
		return $this->date;
		}

	/**
	 * @throws ZohoInvoiceException
	 */
	public function getDateAsDateTime(): ?DateTime
		{
		try
			{
			return $this->date !== null ? new DateTime($this->getDate()) : null;
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
		$lineItems = [];
		foreach ($this->lineItems as $item)
			{
			$lineItems[] = $item->setDiscountPercentage($this->getDiscountPercent());
			}

		return $lineItems;
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
	}
