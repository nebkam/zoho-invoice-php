<?php

namespace Nebkam\ZohoInvoice\Model;

class LineItem
	{
	private string $itemId;
	private float $rate;
	private float $taxPercentage;
	private float $quantity;
	private float $discountPercentage;

	public function __construct(float $discountPercentage = 0.0)
		{
		$this->discountPercentage = $discountPercentage;
		}

	public function getDiscountPercentage(): float
		{
		return $this->discountPercentage;
		}

	public function setDiscountPercentage(float $discountPercentage): self
		{
		$this->discountPercentage = $discountPercentage;

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

	public function getPriceWithTax(): float
		{
		return $this->getRate() * $this->getDiscountMultiplier() * $this->getTaxMultiplier();
		}

	public function getValueWithTax(): float
		{
		return $this->getRate() * $this->getDiscountMultiplier() * $this->getTaxMultiplier() * $this->getQuantity();
		}

	public function getPriceWithDiscount(): float
		{
		return $this->getRate() * $this->getDiscountMultiplier();
		}

	public function getValueWithDiscount(): float
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
