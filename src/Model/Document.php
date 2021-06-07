<?php

namespace Nebkam\ZohoInvoice\Model;

use DateTime;
use Exception;

abstract class Document
	{
	private ?string $customerId;
	private ?float $discountPercent;
	private ?float $total;
	private ?string $date;
	/**
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
	 * @throws Exception
	 */
	public function getDateAsDateTime(): ?DateTime
		{
		return $this->date !== null ? new DateTime($this->getDate()) : null;
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
	 * @param LineItem[] $lineItems
	 * @return self
	 */
	public function setLineItems(array $lineItems): self
		{
		$this->lineItems = $lineItems;

		return $this;
		}
	}
