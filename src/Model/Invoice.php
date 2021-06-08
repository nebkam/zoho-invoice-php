<?php

namespace Nebkam\ZohoInvoice\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Invoice extends Document
	{
	/**
	 * @Assert\NotBlank()
	 * @var string|null
	 */
	private ?string $invoiceNumber;

	public function getInvoiceNumber(): ?string
		{
		return $this->invoiceNumber;
		}

	public function setInvoiceNumber(?string $invoiceNumber): self
		{
		$this->invoiceNumber = $invoiceNumber;

		return $this;
		}
	}
