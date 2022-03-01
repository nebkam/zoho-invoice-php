<?php

namespace Nebkam\ZohoInvoice\Model;


class AddAttachmentResponse extends ApiResponse
	{
	/** Rename to attachments with SerializedName **/
	/**
	 * @var Attachment[]
	 */
	private ?array $documents = null;

	/**
	 * @return Attachment[]
	 */
	public function getDocuments(): ?array
		{
		return $this->documents;
		}

	/**
	 * @param Attachment[] $documents
	 * @return self
	 */
	public function setDocuments(?array $documents): self
		{
		$this->documents = $documents;

		return $this;
		}


	/**
	 * @return int
	 */
	public function getCountAttachments(): int
		{
		if (!$this->documents)
			{
			return 0;
			}
		return count($this->documents);
		}

	/**
	 * @return Attachment|null
	 */
	public function getLastAttachment(): ?Attachment
		{
		return $this->documents[count($this->documents)-1] ?? null;
		}
	}
