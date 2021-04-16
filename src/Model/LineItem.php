<?php

namespace Nebkam\ZohoInvoice\Model;

class LineItem
	{
	private string $itemId;
	private float $rate;
	private float $taxPercentage;
	private float $quantity;

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
	}
