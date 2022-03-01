<?php

namespace Nebkam\ZohoInvoice\Model;

use Symfony\Component\Serializer\Annotation\Groups;
use Nebkam\ZohoInvoice\ContextGroup;

class LineItem
	{
	private string $itemId;
	private string $name;
	private float $rate;
	private float $taxPercentage;
	private float $quantity;
	private ?string $discount;
	private float $itemTotal;

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getName(): string
		{
		return $this->name;
		}

	public function setName(string $name): self
		{
		$this->name = $name;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getDiscount(): ?string
		{
		return $this->discount;
		}

	public function setDiscount(?string $discount): self
		{
		$this->discount = $discount;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getDiscountPercentage(): float
		{
		return (float)$this->discount;
		}

	/**
	 * Returns the totalAmount for an item, with discount and quantity applied, without tax
	 * @Groups({ContextGroup::CONTEXT_CREATE})
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

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getItemId(): string
		{
		return $this->itemId;
		}

	public function setItemId(string $itemId): self
		{
		$this->itemId = $itemId;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getRate(): float
		{
		return $this->rate;
		}

	public function setRate(float $rate): self
		{
		$this->rate = $rate;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getTaxPercentage(): float
		{
		return $this->taxPercentage;
		}

	public function setTaxPercentage(float $taxPercentage): self
		{
		$this->taxPercentage = $taxPercentage;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
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
