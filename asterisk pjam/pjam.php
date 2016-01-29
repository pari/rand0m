<?php
/*

PHP Class to easily manipulate asterisk config files
version 0.3
Copyright 2009 Pari Nannapaneni <paripurnachand@gmail.com>

// :: usage ::
// $uafile = new UAFILE();

// $uafile->setFile('./users.conf');

// $uafile->addContext('newcontextname', array('name = value','hasexten = yes') );

// $uafile->removeContext('general') ; // removes all occurances of context ['general']

// $uafile->addlinesToContext('user1001',  array( 'hasiax = yes', 'hassip = no' )  ); // context will be created if not found

// $uafile->getContext('user1001') // returns an array with the lines of context ['user1001']

// $uafile->removelinesfromContext( 'user1001' , array('hasiax = yes') ) // removes the given lines from the context (all occurances)

// $uafile->ContextExists('SomeContext') ; // returns true or false accordingly

// $uafile->ContextDoesNotExist('SomeContext') ; // returns true or false accordingly

*/



class UAFILE {
	var $filename = '';
	public function setFile($filename){
		$this->filename = $filename ;
	}

	public function addContext($context, $contextArray){
		$this->addlinesToContext($context , $contextArray );
	}

	public function removeContext($context){
		// read everything but lines in $context into an array
		// write back array to file
		$lineswithOutContext = array();
		$startedreadingContext = false;
		$handle = @fopen($this->filename, "r");
		if ($handle) {
			while (!feof($handle)) {
				$buffer = fgets($handle, 4096);
				if( trim($buffer) == '['.$context.']' ){
					$startedreadingContext = true ;
					continue;
				}else{
					$pos = strpos( trim($buffer), '[' );
					if( $pos === 0 ){ // this is some other context
						$startedreadingContext = false;
					}
					if($startedreadingContext == false){
						$lineswithOutContext[] = $buffer ;
					}
				}
			}
			fclose($handle);
		}
		$fp = fopen($this->filename, 'w');
		foreach( $lineswithOutContext as $thisline){
			fwrite($fp, $thisline);
		}
		fclose($fp);
	}


	public function addlinesToContext($context , $lines ){
		$newlines = array();
		$startedreadingContext = false;
		$newlinesPushed = false;
		$handle = @fopen($this->filename, "r");
		if ($handle) {
			while (!feof($handle)) {
				$buffer = fgets($handle, 4096);
				if($newlinesPushed == true){ $newlines[] = $buffer ; continue; }
				if( trim($buffer) == '['.$context.']' ){
					$startedreadingContext = true ;
				}else{
					$pos = strpos( trim($buffer), '[' );
					if( $pos === 0 && $startedreadingContext == true ){ // this is some other context and the previous one was $context
						foreach($lines as $thisline){
							$newlines[] = $thisline."\n" ;
						}
						$newlines[] = "\n\n";
						$newlinesPushed = true;
					}
				}
				$newlines[] = $buffer ;
			}
			if( $startedreadingContext == false){ // the context to which lines should be added is not found, add it
				$newlines[] = "[".$context."]\n" ;
			}
			if( $newlinesPushed == false){ // no context found after $context
				foreach($lines as $thisline){
					$newlines[] = $thisline."\n" ;
				}
				$newlines[] = "\n\n";
				$newlinesPushed = true;
			}
			fclose($handle);
		}
		$fp = fopen($this->filename, 'w');
		foreach( $newlines as $thisline){
			fwrite($fp, $thisline);
		}
		fclose($fp);
	}
	
	
	public function getContext($context){
		$linesInThisContext = array();
		$startedreadingContext = false;
		$handle = @fopen($this->filename, "r");
		if ($handle) {
			while (!feof($handle)) {
				$buffer = fgets($handle, 4096);
				if( trim($buffer) == '['.$context.']' ){
					$startedreadingContext = true ;
				}else{
					if($startedreadingContext == true){
						$pos = strpos( trim($buffer), '[' );
						if( $pos === 0){ // this is some other context after $context
							$startedreadingContext = false;
						}else{
							$linesInThisContext[] = trim($buffer);
						}
					}
				}
			}
			fclose($handle);
		}
		return $linesInThisContext;
	}


	public function removelinesfromContext($context , $linesToDelete){
		// read the context
		// prepare a new replacement context without these lines
		// delete the context
		// add the replacementcontext
		if( $this->ContextDoesNotExist($context) ){
			return;
		}
		$existingContext = $this->getContext( $context );
		$replacementContext = array_diff( $existingContext, $linesToDelete );
		if(count($replacementContext) != count($existingContext) ){
			$this->removeContext( $context );
			$this->addlinesToContext( $context , $replacementContext );
		}
	}


	public function ContextExists($context){
		$contextFound = false;
		$handle = @fopen($this->filename, "r");
		if ($handle) {
			while (!feof($handle)) {
				$buffer = fgets($handle, 4096);
				if( trim($buffer) == '['.$context.']' ){
					$contextFound = true ;
					break;
				}
			}
			fclose($handle);
		}
		return $contextFound ;
	}
	
	public function ContextDoesNotExist($context){ // opposite of ContextExists
		if($this->ContextExists($context)){
			return false;
		}else{
			return true;
		}
	}

}







?>