<?php

namespace Nebkam\ZohoInvoice\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Estimate extends Document
	{
	/**
	 * @Assert\NotBlank()
	 * @var string|null
	 */
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
