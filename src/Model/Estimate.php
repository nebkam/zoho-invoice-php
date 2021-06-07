<?php

namespace Nebkam\ZohoInvoice\Model;

use DateTime;
use Exception;

class Estimate
	{
	private ?string $estimateId = null;
	private ?string $customerId = null;
	private ?string $date = null;
	private ?float $discountPercent = null;
	private ?float $total = null;
	/**
	 * @var LineItem[]
	 */
	private array $lineItems;

	public function getEstimateId(): ?string
		{
		return $this->estimateId;
		}

	public function setEstimateId(?string $estimateId): self
		{
		$this->estimateId = $estimateId;

		return $this;
		}

	public function getCustomerId(): ?string
		{
		return $this->customerId;
		}

	public function setCustomerId(?string $customerId): self
		{
		$this->customerId = $customerId;

		return $this;
		}

	public function getDate(): ?string
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

	public function setDate(?string $date): self
		{
		$this->date = $date;

		return $this;
		}

	public function getDiscountPercent(): ?float
		{
		return $this->discountPercent;
		}

	public function setDiscountPercent(?float $percent): self
		{
		$this->discountPercent = $percent;

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

	/**
	 * @return LineItem[]
	 */
	public function getLineItems(): array
		{
		return $this->lineItems;
		}

	/**
	 * @param LineItem[] $lineItems
	 * @return Estimate
	 */
	public function setLineItems(array $lineItems): self
		{
		$this->lineItems = $lineItems;

		return $this;
		}
	}
