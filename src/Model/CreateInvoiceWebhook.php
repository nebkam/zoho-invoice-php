<?php

namespace Nebkam\ZohoInvoice\Model;

class CreateInvoiceWebhook
	{
	private Invoice $invoice;

	public function getInvoice(): Invoice
		{
		return $this->invoice;
		}

	public function setInvoice(Invoice $invoice): self
		{
		$this->invoice = $invoice;

		return $this;
		}
	}
