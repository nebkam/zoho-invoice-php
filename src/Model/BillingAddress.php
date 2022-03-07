<?php

namespace Nebkam\ZohoInvoice\Model;

class BillingAddress
	{
	private ?string $address = null;
	private ?string $city = null;
	private ?string $country = null;
	private ?string $phone = null;

	public function getAddress(): ?string
		{
		return $this->address;
		}

	public function setAddress(?string $address): self
		{
		$this->address = $address;

		return $this;
		}

	public function getCity(): ?string
		{
		return $this->city;
		}

	public function setCity(?string $city): self
		{
		$this->city = $city;

		return $this;
		}

	public function getCountry(): ?string
		{
		return $this->country;
		}

	public function setCountry(?string $country): self
		{
		$this->country = $country;

		return $this;
		}

	public function getPhone(): ?string
		{
		return $this->phone;
		}

	public function setPhone(?string $phone): self
		{
		$this->phone = $phone;

		return $this;
		}
	}
