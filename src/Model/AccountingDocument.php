<?php

namespace Nebkam\ZohoInvoice\Model;

use DateTime;

interface AccountingDocument
	{
	public function getCustomerId(): string;
	public function getDateAsDateTime(): DateTime;
	/**
	 * @return LineItem[]
	 */
	public function getLineItems(): array;
	}
