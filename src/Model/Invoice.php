<?php

namespace Nebkam\ZohoInvoice\Model;

class Invoice extends Document
	{
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
