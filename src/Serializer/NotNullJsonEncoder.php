<?php

namespace Nebkam\ZohoInvoice\Serializer;

use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class NotNullJsonEncoder extends JsonEncoder
	{
	public function __construct()
		{
		parent::__construct(new NotNullJsonEncode(),new JsonDecode());
		}
	}
