<?php

namespace Nebkam\ZohoInvoice\Model;

use DateTime;
use DateTimeZone;

class CustomField
	{
	/**
	 * Date field used for passing information when a services are finally delivered to the customer *
	 */
	public const DELIVERED_AT_NAME = 'cf_delivered_at';
	public const REVERSAL_REFERENCE = 'cf_referenca';

	private ?string $label = null;
	private ?string $apiName = null;
	private ?string $value = null;
	private ?string $dataType = null;

	public function getLabel(): ?string
		{
		return $this->label;
		}

	public function setLabel(?string $label): self
		{
		$this->label = $label;

		return $this;
		}

	public function getApiName(): ?string
		{
		return $this->apiName;
		}

	public function setApiName(?string $apiName): self
		{
		$this->apiName = $apiName;

		return $this;
		}

	public function getValue(): ?string
		{
		return $this->value;
		}

	public function setValue(?string $value): self
		{
		$this->value = $value;

		return $this;
		}

	/**
	 * @return string|null
	 */
	public function getDataType(): ?string
		{
		return $this->dataType;
		}

	public function setDataType(?string $dataType): self
		{
		$this->dataType = $dataType;

		return $this;
		}

	public function getAsDateTime(?DateTimeZone $timezone = null): ?DateTime
		{
		if ($this->getDataType() === 'date' || $this->getDataType() === 'datetime')
			{
			return new DateTime($this->value, $timezone);
			}

		return null;
		}
	}
