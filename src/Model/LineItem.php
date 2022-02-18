<?php

namespace Nebkam\ZohoInvoice\Model;

class LineItem
	{
	private string $itemId;
	private float $rate;
	private float $taxPercentage;
	private float $quantity;
	private ?string $discount;
	private float $itemTotal;

	public function __construct()
		{
		}

	public function getDiscount(): ?string
		{
		return $this->discount;
		}

	public function setDiscount(?string $discount): self
		{
		$this->discount           = $discount;

		return $this;
		}

	public function getDiscountPercentage(): float
		{
		return (float)$this->discount;
		}

	/**
	 * Returns the totalAmount for an item, with discount and quantity applied, without tax
	 * @return float
	 */
	public function getItemTotal(): float
		{
		return $this->itemTotal;
		}

	public function setItemTotal(float $itemTotal): self
		{
		$this->itemTotal = $itemTotal;

		return $this;
		}

	public function getItemId(): string
		{
		return $this->itemId;
		}

	public function setItemId(string $itemId): self
		{
		$this->itemId = $itemId;

		return $this;
		}

	public function getRate(): float
		{
		return $this->rate;
		}

	public function setRate(float $rate): self
		{
		$this->rate = $rate;

		return $this;
		}

	public function getTaxPercentage(): float
		{
		return $this->taxPercentage;
		}

	public function setTaxPercentage(float $taxPercentage): self
		{
		$this->taxPercentage = $taxPercentage;

		return $this;
		}

	public function getQuantity(): float
		{
		return $this->quantity;
		}

	public function setQuantity(float $quantity): self
		{
		$this->quantity = $quantity;

		return $this;
		}

	public function getPriceWithDiscountAndTax(): float
		{
		return $this->getRate() * $this->getDiscountMultiplier() * $this->getTaxMultiplier();
		}

	public function getValueWithDiscountAndTax(): float
		{
		return $this->getItemTotal() * $this->getTaxMultiplier();
		}

	public function getPriceWithDiscount(): float
		{
		return $this->getRate() * $this->getDiscountMultiplier();
		}

	/**
	 * Semantic getter alias
	 */
	public function getValueWithDiscount(): float
		{
		return $this->itemTotal;
		}

	public function getValueWithDiscount2(): float
		{
		return $this->getRate() * $this->getDiscountMultiplier() * $this->getQuantity();
		}

	private function getDiscountMultiplier(): float
		{
		return (100 - $this->getDiscountPercentage()) / 100;
		}

	private function getTaxMultiplier(): float
		{
		return 1 + ($this->getTaxPercentage() / 100);
		}
	}
