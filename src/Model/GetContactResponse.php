<?php

namespace Nebkam\ZohoInvoice\Model;

class GetContactResponse extends ApiResponse
	{
	private Contact $contact;

	public function getContact(): Contact
		{
		return $this->contact;
		}

	public function setContact(Contact $contact): self
		{
		$this->contact = $contact;

		return $this;
		}
	}
