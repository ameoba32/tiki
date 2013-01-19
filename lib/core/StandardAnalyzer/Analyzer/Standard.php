<?php
/**
 * This file contains a subclass of the Zend_Search_Lucene_Analysis_Analyzer class.
 * Its purpose is to help provide a corresponding PHP implementation of the Standard analyzer for
 * the Java implementation of Lucene. This Analyzer, in conjunction with the filters also provided
 * in this standard analyzer package, provide a method for indexing documents with word Stemming,
 * lower-casing, and number handling. The lower-case and number handling is provided by the pre-
 * existing filters from Zend.
 * 
 * License: see License.txt for a copy of the Zend License.
 *
 *Ref:
 * http://hudson.zones.apache.org/hudson/job/Lucene-trunk/javadoc//org/apache/lucene/analysis/standard/StandardAnalyzer.html
 *
 * @category   PHP_Analyzer_Standard
 */


/** Zend_Search_Lucene_Analysis_Analyzer */
require_once 'Zend/Search/Lucene/Analysis/Analyzer.php';

abstract class StandardAnalyzer_Analyzer_Standard extends Zend_Search_Lucene_Analysis_Analyzer
{
  /**
     * The set of Token filters applied to the Token stream.
     * Array of Zend_Search_Lucene_Analysis_TokenFilter objects.
     *
     * @var array
     */
    private $_filters = array();

	/**
	 * Current char position in an UTF-8 stream
	 *
	 * @var integer
	 */
	private $_position;

	/**
	 * Current binary position in an UTF-8 stream
	 *
	 * @var integer
	 */
	private $_bytePosition;


	public function __construct()
	{
		if (@preg_match('/\pL/u', 'a') != 1) {
			// PCRE unicode support is turned off
			require_once 'Zend/Search/Lucene/Exception.php';
			throw new Zend_Search_Lucene_Exception('Analyzer needs PCRE unicode support to be enabled.');
		}
		$this->_position     = 0;
		$this->_bytePosition = 0;
	}

    /**
     * Add Token filter to the Analyzer
     *
     * @param Zend_Search_Lucene_Analysis_TokenFilter $filter
     */
    public function addFilter(Zend_Search_Lucene_Analysis_TokenFilter $filter)
    {
        $this->_filters[] = $filter;
    }

    /**
     * Reset token stream
     */
    public function reset()
    {
        $this->_position     = 0;
		$this->_bytePosition = 0;

        if ($this->_input === null) {
            return;
        }

		// Keeping the current encoding seems to work. So don't convert.
    }
	
    /**
     * Apply filters to the token. Can return null when the token was removed.
     *
     * @param Zend_Search_Lucene_Analysis_Token $token
     * @return Zend_Search_Lucene_Analysis_Token
     */
    public function normalize(Zend_Search_Lucene_Analysis_Token $token)
    {
        foreach ($this->_filters as $filter) {
            $token = $filter->normalize($token);

            // resulting token can be null if the filter removes it
            if (is_null($token)) {
                return null;
            }
        }

        return $token;
    }
	
	public function nextToken()
	{
		if ($this->_input === null) {
			return null;
		}

		//Parse UTF-8
		do {
            if (! preg_match('/[\p{L}]+/u', $this->_input, $match, PREG_OFFSET_CAPTURE, $this->_bytePosition)) {
                // It covers both cases a) there are no matches (preg_match(...) === 0)
                // b) error occured (preg_match(...) === FALSE)
                return null;
            }

            // matched string
            $matchedWord = $match[0][0];

            // binary position of the matched word in the input stream
            $binStartPos = $match[0][1];

            // character position of the matched word in the input stream
            $startPos = $this->_position +
                        iconv_strlen(substr($this->_input,
                                            $this->_bytePosition,
                                            $binStartPos - $this->_bytePosition),
                                     'UTF-8');
            // character postion of the end of matched word in the input stream
            $endPos = $startPos + iconv_strlen($matchedWord, 'UTF-8');

            $this->_bytePosition = $binStartPos + strlen($matchedWord);
            $this->_position     = $endPos;

            $token = $this->normalize(new Zend_Search_Lucene_Analysis_Token($matchedWord, $startPos, $endPos));
		} while ($token === null); // try again if token is skipped

		return $token;
	}
}
