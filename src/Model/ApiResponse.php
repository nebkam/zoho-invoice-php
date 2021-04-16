<?php

namespace Nebkam\ZohoInvoice\Model;

class ApiResponse
	{
	private const SUCCESS_CODE = 0;
	private int $code;
	private string $message;

	public function getCode(): int
		{
		return $this->code;
		}

	public function setCode(int $code): self
		{
		$this->code = $code;

		return $this;
		}

	public function isSuccessful(): bool
		{
		return $this->code === self::SUCCESS_CODE;
		}

	public function getMessage(): string
		{
		return $this->message;
		}

	public function setMessage(string $message): self
		{
		$this->message = $message;

		return $this;
		}
	}
