<?php

namespace Nebkam\ZohoInvoice\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Attachment
	{
	/**
	 * @Assert\NotBlank()
	 */
	private ?string $documentId;
	/**
	 * @Assert\NotNull()
	 */
	private ?string $fileName;
	/**
	 * @Assert\NotBlank()
	 */
	private ?string $fileType;
	/**
	 * @Assert\NotBlank()
	 */
	private ?int $fileSize;
	/**
	 * @Assert\NotBlank()
	 */
	private ?string $fileSizeFormatted;

	public function getDocumentId(): ?string
		{
		return $this->documentId;
		}

	public function setDocumentId(?string $documentId): self
		{
		$this->documentId = $documentId;

		return $this;
		}

	public function getFileName(): ?string
		{
		return $this->fileName;
		}

	public function setFileName(?string $fileName): self
		{
		$this->fileName = $fileName;

		return $this;
		}

	public function getFileType(): ?string
		{
		return $this->fileType;
		}

	public function setFileType(?string $fileType): self
		{
		$this->fileType = $fileType;

		return $this;
		}

	public function getFileSize(): ?int
		{
		return $this->fileSize;
		}

	public function setFileSize(?int $fileSize): self
		{
		$this->fileSize = $fileSize;

		return $this;
		}

	public function getFileSizeFormatted(): ?string
		{
		return $this->fileSizeFormatted;
		}

	public function setFileSizeFormatted(?string $fileSizeFormatted): self
		{
		$this->fileSizeFormatted = $fileSizeFormatted;

		return $this;
		}
	}
