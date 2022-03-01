<?php

namespace Nebkam\ZohoInvoice\Model;

use Symfony\Component\Validator\Constraints as Assert;

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

	public function getInvoiceNumber(): ?string
		{
		return $this->invoiceNumber;
		}

	public function setInvoiceNumber(?string $invoiceNumber): self
		{
		$this->invoiceNumber = $invoiceNumber;

		return $this;
		}

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
		$invoice           = (new self());
		$filteredLineItems = [];
		foreach ($estimate->getLineItems() as $lineItem)
			{
			$lineItem->setItemId(null);
			$filteredLineItems[] = $lineItem;
			}
		$invoice
			->setLineItems($filteredLineItems)
//			->setDiscountPercent($estimate->getDiscountPercent())
			->setCustomerId($estimate->getCustomerId());

		return $invoice;
		}
	}
