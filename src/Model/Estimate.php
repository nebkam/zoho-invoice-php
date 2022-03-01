<?php

namespace Nebkam\ZohoInvoice\Model;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Nebkam\ZohoInvoice\ContextGroup;

class Estimate extends Document
	{
	/**
	 * @Assert\NotBlank()
	 * @var string|null
	 */
	private ?string $estimateId;

	/**
	 * @Assert\NotBlank()
	 * @var string|null
	 */
	private ?string $estimateNumber;

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getEstimateId(): ?string
		{
		return $this->estimateId;
		}

	public function setEstimateId(?string $estimateId): self
		{
		$this->estimateId = $estimateId;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
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
