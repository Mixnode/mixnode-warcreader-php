<?php 
namespace Mixnode\WarcReader;
use Exception;

class WarcReader
{
	public 	$warc_path = NULL;
	private $fp = NULL;

	/**
	 * Instantiate a new WarcReader.
	 *
	 * @return void
	 */
	function __construct($warc_file_path)
	{

		//check if WARC file exists
		if ( !file_exists($warc_file_path) ) {
			throw new Exception('WARC file not found.');
		}

		//if it's gzipped use compress.zlib for streaming
		if(stripos(strrev($warc_file_path), "zg.") === 0)
			$path_for_fopen = "compress.zlib://$warc_file_path";
		else
			$path_for_fopen = $warc_file_path;

		//try to open file
		$fp = fopen($path_for_fopen, 'r');
		if(!$fp)
			throw new Exception('Could not open WARC file.');	
		//on success open WARC file and store its path
		else{
			$this->fp = $fp;
			$this->warc_path = $warc_file_path;
		}
	}

	/**
	 * Gets the next WARC record
	 *
	 */
	public function nextRecord()
	{
		if(!@feof($this->fp)){
			//stores warc header
			$warc_header = array();
			//get first line of warc archive file
			$line = fgets($this->fp);
			//continue streaming file line by line until a newline is detected	
			//newline means header of warc record is over
			while( $line != "\r\n" && !feof($this->fp)){
				$split_parts = array();
				//split this line from ': '
				$split_parts = explode(": ", $line, 2);
				if(trim($split_parts[0]) == 'WARC/1.0' || trim($split_parts[0]) == 'WARC/1.1')
					@$warc_header['version'] = trim($split_parts[0]);
				else
					@$warc_header[trim($split_parts[0])] = trim($split_parts[1]);
				//read a line for next iteration
				$line = fgets($this->fp);
			}
			//read content block of this record
			$warc_content_block = fread($this->fp, $warc_header['Content-Length']);
			//every block is followed by two newlines, pass them
			fgets($this->fp);
			fgets($this->fp);

			//prepare and return array of header and content block
			$warc_record['header'] 	= $warc_header;
			$warc_record['content'] = $warc_content_block;
			return $warc_record;
		}
		else
			return FALSE;
	}

	function __destruct() {
		fclose($this->fp);
	}
}
