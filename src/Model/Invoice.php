<?php

namespace Nebkam\ZohoInvoice\Model;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Nebkam\ZohoInvoice\ContextGroup;

class Invoice extends Document
	{
	/**
	 * @Assert\NotBlank()
	 * @var string|null
	 */
	private ?string $invoiceNumber = null;

	/**
	 * @Assert\NotBlank()
	 * @var string|null
	 */
	private ?string $invoiceId;

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getInvoiceNumber(): ?string
		{
		return $this->invoiceNumber;
		}

	public function setInvoiceNumber(?string $invoiceNumber): self
		{
		$this->invoiceNumber = $invoiceNumber;

		return $this;
		}

	/**
	 * @Groups({ContextGroup::CONTEXT_CREATE})
	 */
	public function getInvoiceId(): ?string
		{
		return $this->invoiceId;
		}


	public function setInvoiceId(?string $invoiceId): self
		{
		$this->invoiceId = $invoiceId;

		return $this;
		}

	public static function fromEstimate(Estimate $estimate): self
		{
		return (new self())
			->setLineItems($estimate->getLineItems())
//			->setDiscountPercent($estimate->getDiscountPercent())
			->setCustomerId($estimate->getCustomerId());
		}
	}
