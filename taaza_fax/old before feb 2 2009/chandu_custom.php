<?php

class sendaMail{
	// :: Usage ::
	// $email = new sendaMail();
	// $email->messageTo( "chandu@chandu.org" );
	// $email->subject( "Some Subject" );
	// $email->body( "Some Body" );
	// $email->AddAttachment( $filename ); // Optional
	// $email->send();
	var $Attachments = array();
	var $AttachmentNames = array();
	var $BCCS = array();

	public function messageTo( $to ){
		$this->mailto = $to ;
	}

	public function subject( $subject ){
		$this->subject = $subject ;
	}

	public function body( $message ){
		$this->textmsg = $message ;
		$this->htmlmsg = nl2br($message) ;
	}

	public function AddAttachment( $filename , $name = ''){
		array_push($this->Attachments, $filename);
		$attachmentNumber = count($this->Attachments) - 1;
		if($name){
			$this->AttachmentNames[$attachmentNumber] = $name;
		}
	}

	public function AddBCC( $bccemailid ){
		$this->BCCS[] = $bccemailid ;
	}

	public function asFrom( $asFrom ){
		$this->asFrom = $asFrom ;
	}

    public function send() {
		global $DEVELOPMENT_MODE ;
		if($DEVELOPMENT_MODE){return;}

		try{
			if(!$this->textmsg){
				$this->textmsg = ' ';
			}
			if(!$this->htmlmsg){
				$this->htmlmsg = ' ';
			}
		require_once( '/var/lib/asterisk/agi-bin/phpmailer/class.phpmailer.php' );
		
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "tls";                 // sets the prefix to the servier
		$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
		$mail->Port       = 587;                   // set the SMTP port for the GMAIL server
		$mail->Username   = "xxxxxxxx@gmail.com";  // GMAIL username
		$mail->Password   = "xxxxx";            // GMAIL password

		$mail->SetFrom('noreply@discreteevents.com', 'Asterisk @ Taaza Stores' );
		$mail->AddReplyTo('noreply@discreteevents.com', 'Asterisk @ Taaza Stores' );
		
		if( count($this->Attachments) ){
			$attachmentCount = count($this->Attachments);
			for( $t=0; $t< $attachmentCount; $t++){
				if( @$this->AttachmentNames[$t] ){
					$mail->AddAttachment( $this->Attachments[$t] , $this->AttachmentNames[$t] );
				}else{
					$mail->AddAttachment( $this->Attachments[$t] );
				}
			}
		}

		if( count($this->BCCS) ){
			foreach($this->BCCS as $bccid ){
				$mail->AddBCC( $bccid );
			}
		}

		$mail->AddAddress( $this->mailto );
		$mail->Subject = $this->subject ;
		$mail->AltBody = $this->textmsg ;
		$mail->MsgHTML( $this->htmlmsg );
		$mail->Send();
		}catch (phpmailerException $e) {
			echo $e->errorMessage();
		}catch (Exception $e) {
			echo $e->getMessage();
		}
    }

} // End of 'sendaMail' class


?>