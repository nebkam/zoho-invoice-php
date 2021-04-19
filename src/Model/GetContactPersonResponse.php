<?php

namespace Nebkam\ZohoInvoice\Model;

class GetContactPersonResponse extends ApiResponse
	{
	private ContactPerson $contact;

	/**
	 * @return ContactPerson
	 */
	public function getContactPerson(): ContactPerson
		{
		return $this->contact;
		}

	public function setContactPerson(ContactPerson $contact): self
		{
		$this->contact = $contact;

		return $this;
		}
	}
