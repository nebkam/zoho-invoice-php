<?php

namespace Nebkam\ZohoInvoice\Model;

class Estimate extends Document
	{
	private ?string $estimateNumber;

	public function getEstimateNumber(): ?string
		{
		return $this->estimateNumber;
		}

	public function setEstimateNumber(?string $estimateNumber): self
		{
		$this->estimateNumber = $estimateNumber;

		return $this;
		}
	}
