<?php

namespace Nebkam\ZohoInvoice\Model;

class CreateEstimateWebhook
	{
	private Estimate $estimate;

	/**
	 * @return Estimate
	 */
	public function getEstimate(): Estimate
		{
		return $this->estimate;
		}

	public function setEstimate(Estimate $estimate): self
		{
		$this->estimate = $estimate;

		return $this;
		}
	}
