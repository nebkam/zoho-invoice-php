<?php

namespace Nebkam\ZohoInvoice\Model;

class GetInvoiceResponse extends ApiResponse
	{
	private Invoice $invoice;

	/**
	 * @return Invoice
	 */
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
