<?php

namespace Nebkam\ZohoInvoice\Model;

class GetEstimateResponse extends ApiResponse
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
