<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tiki\MailIn\Source;

class Message
{
	private $id;
	private $deleteCallback;

	private $messageId;
	private $from;
	private $subject;
	private $body;
	private $htmlBody;
	private $attachments = [];

	private $associatedUser;

	function __construct($id, $deleteCallback)
	{
		$this->id = $id;
		$this->deleteCallback = $deleteCallback;
	}

	function setMessageId($messageId)
	{
		$this->messageId = $messageId;
	}
	
	function setRawFrom($from)
	{
		$this->from = $from;
		
		if ($email = $this->getFromAddress()) {
			$userlib = \TikiLib::lib('user');
			$this->associatedUser = $userlib->get_user_by_email($email);
		}
	}

	function getFromAddress()
	{
		preg_match('/<?([-!#$%&\'*+\.\/0-9=?A-Z^_`a-z{|}~]+@[-!#$%&\'*+\/0-9=?A-Z^_`a-z{|}~]+\.[-!#$%&\'*+\.\/0-9=?A-Z^_`a-z{|}~]+)>?/', $this->from, $mail);
		
		return $mail[1];
	}

	function getAssociatedUser()
	{
		return $this->associatedUser;
	}

	function delete()
	{
		if ($this->deleteCallback) {
			$callback = $this->deleteCallback;
			$callback();
			$this->deleteCallback = null;
		}
	}
	
	function setSubject($subject)
	{
		$this->subject = $subject;
	}

	function getSubject()
	{
		return $this->subject;
	}

	function setBody($body)
	{
		$this->body = $body;
	}

	function getBody()
	{
		return $this->body;
	}

	function setHtmlBody($body)
	{
		$this->htmlBody = $body;
	}

	function getHtmlBody($fallback = true)
	{
		if ($fallback) {
			return $this->htmlBody ?: $this->body;
		} else {
			return $this->htmlBody;
		}
	}

	function addAttachment($contentId, $name, $type, $size, $data)
	{
		$this->attachments[$contentId] = [
			'contentId' => $contentId,
			'name' => $name,
			'type' => $type,
			'size' => $size,
			'data' => $data,
			'link' => null,
		];
	}

	function setLink($contentId, $link)
	{
		if (isset($this->attachments[$contentId])) {
			$this->attachments[$contentId]['link'] = $link;
		}
	}

	function getAttachments()
	{
		return array_values($this->attachments);
	}

	function getAttachment($contentId)
	{
		if (isset($this->attachments[$contentId])) {
			return $this->attachments[$contentId];
		}
	}
}

