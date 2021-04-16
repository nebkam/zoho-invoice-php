<?php

namespace Nebkam\ZohoInvoice\Model;

use DateTime;

class Invoice
	{
	private string $customerId;
	private string $salespersonId;
	private string $invoiceNumber;
	private string $referenceNumber;
	private float $discountPercent;
	private float $discountAmount;
	private float $total;
	private DateTime $createdTime;
	/**
	 * @var LineItem[]
	 */
	private array $lineItems;

	public function getCustomerId(): string
		{
		return $this->customerId;
		}

	public function setCustomerId(string $customerId): self
		{
		$this->customerId = $customerId;

		return $this;
		}

	public function getSalespersonId(): string
		{
		return $this->salespersonId;
		}

	public function setSalespersonId(string $salespersonId): self
		{
		$this->salespersonId = $salespersonId;

		return $this;
		}

	public function getInvoiceNumber(): string
		{
		return $this->invoiceNumber;
		}

	public function setInvoiceNumber(string $invoiceNumber): self
		{
		$this->invoiceNumber = $invoiceNumber;

		return $this;
		}

	public function getReferenceNumber(): string
		{
		return $this->referenceNumber;
		}

	public function setReferenceNumber(string $referenceNumber): self
		{
		$this->referenceNumber = $referenceNumber;

		return $this;
		}

	public function getDiscountPercent(): float
		{
		return $this->discountPercent;
		}

	public function setDiscountPercent(float $discountPercent): self
		{
		$this->discountPercent = $discountPercent;

		return $this;
		}

	public function getDiscountAmount(): float
		{
		return $this->discountAmount;
		}

	public function setDiscountAmount(float $discountAmount): self
		{
		$this->discountAmount = $discountAmount;

		return $this;
		}

	public function getTotal(): float
		{
		return $this->total;
		}

	public function setTotal(float $total): self
		{
		$this->total = $total;

		return $this;
		}

	public function getCreatedTime(): DateTime
		{
		return $this->createdTime;
		}

	public function setCreatedTime(DateTime $createdTime): self
		{
		$this->createdTime = $createdTime;

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
	 * @param LineItem[] $lineItems
	 * @return self
	 */
	public function setLineItems(array $lineItems): self
		{
		$this->lineItems = $lineItems;

		return $this;
		}
	}