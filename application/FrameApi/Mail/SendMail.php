<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 3/4/2019
 * Time: 11:07 PM
 */

namespace FrameApi\Mail;


class SendMail
{
	private $addressee;

	private $subject;

	private $message;

	private $headers;

	public function __construct($to, $subject, $message, $headers = null)
	{
		$this->addressee = $to;
		$this->subject = $subject;
		$this->message = $this->generateBody($message);

		$default_headers  = 'MIME-Version: 1.0' . "\r\n";
		$default_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$default_headers .= 'From: Frame App <cumples@example.com>' . "\r\n";

		$this->headers = $headers ? $headers : $default_headers;

	}

	private function generateBody($message)
	{
		$body = "
			<html>
				<head>
					<title>$this->subject</title>
					<style>
						* {
							text-align: center;
							font-family: Arial;
						}
						p {
							margin-bottom: 10px;
						}
					</style>
				</head>
				<body>
					<table>
						<tr>
							<td>
								<h1><img src='". __SITE_URL__ ."/images/assets/logo.png' alt='Frame App'/></h1>
							</td>
						</tr>
						<tr>
							<td>
								<p class='msg'> $message </p>
							</td>
						</tr>
					</table>
				</body>
			</html>
		";

		return $body;
	}

	public function send()
	{
		return mail($this->addressee, $this->subject, $this->message, $this->headers);
	}

}