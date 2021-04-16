<?php

namespace Nebkam\ZohoInvoice\Serializer;

use Nebkam\ZohoInvoice\Helper;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class NotNullJsonEncode extends JsonEncode
	{
	public function encode($data, $format, array $context = array())
		{
		$data = Helper::filterNullRecursive($data);

		return parent::encode($data, $format, $context);
		}
	}
