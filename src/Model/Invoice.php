<?php

namespace Nebkam\ZohoInvoice\Model;

use DateTime;
use Exception;

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
	private string $date;
	/**
	 * @var LineItem[]
	 */
	private array $lineItems;

	/**
	 * @return string
	 */
	public function getCustomerId(): string
		{
		return $this->customerId;
		}

	public function setCustomerId(string $customerId): self
		{
		$this->customerId = $customerId;

		return $this;
		}

	/**
	 * @return string
	 */
	public function getSalespersonId(): string
		{
		return $this->salespersonId;
		}

	public function setSalespersonId(string $salespersonId): self
		{
		$this->salespersonId = $salespersonId;

		return $this;
		}

	/**
	 * @return string
	 */
	public function getInvoiceNumber(): string
		{
		return $this->invoiceNumber;
		}

	public function setInvoiceNumber(string $invoiceNumber): self
		{
		$this->invoiceNumber = $invoiceNumber;

		return $this;
		}

	/**
	 * @return string
	 */
	public function getReferenceNumber(): string
		{
		return $this->referenceNumber;
		}

	public function setReferenceNumber(string $referenceNumber): self
		{
		$this->referenceNumber = $referenceNumber;

		return $this;
		}

	/**
	 * @return float
	 */
	public function getDiscountPercent(): float
		{
		return $this->discountPercent;
		}

	public function setDiscountPercent(float $discountPercent): self
		{
		$this->discountPercent = $discountPercent;

		return $this;
		}

	/**
	 * @return float
	 */
	public function getDiscountAmount(): float
		{
		return $this->discountAmount;
		}

	public function setDiscountAmount(float $discountAmount): self
		{
		$this->discountAmount = $discountAmount;

		return $this;
		}

	/**
	 * @return float
	 */
	public function getTotal(): float
		{
		return $this->total;
		}

	public function setTotal(float $total): self
		{
		$this->total = $total;

		return $this;
		}

	/**
	 * @return DateTime
	 */
	public function getCreatedTime(): DateTime
		{
		return $this->createdTime;
		}

	public function setCreatedTime(DateTime $createdTime): self
		{
		$this->createdTime = $createdTime;

		return $this;
		}

	public function getDate(): string
		{
		return $this->date;
		}

	/**
	 * @throws Exception
	 */
	public function getDateAsDateTime(): DateTime
		{
		return new DateTime($this->getDate());
		}

	public function setDate(string $date): self
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
	 * @param LineItem[] $lineItems
	 * @return self
	 */
	public function setLineItems(array $lineItems): self
		{
		$this->lineItems = $lineItems;

		return $this;
		}
	}
