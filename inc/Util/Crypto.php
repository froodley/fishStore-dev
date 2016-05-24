<?php

namespace fishStore\Util;

define( 'CRYPTO_FN', $GLOBALS['base_path'] . '\\safe\\crypto.key' );
/**
 * Crypto
 *
 * Utility class to encrypt and decrypt text
 *
 * @package    fishStore
 * @author     Pete Burkindine <pburkind@gmail.com>
 * @copyright  2016
 * @version    Release: 1.3
 */
class Crypto
{
	private $_iv = null;
	private $_cipher = null;
	private $_key = null;
	
	/**
	* __construct
	*
	* Loads or creates the crypto key
	* Finds and stores an available crypto method
	*
	* @return (Crypto) The Crypto object
	*/
	public function __construct()
	{
		$fh = File::OpenRead( CRYPTO_FN, 'Crypto' );
		if( !$fh )
		{
			$this->_storeNewKey();
			
			$fh = File::OpenRead( CRYPTO_FN, 'Crypto' );
			if( !$fh )
				return null;
		}
		
		$this->_cipher	= rtrim( fgets( $fh ), "\r\n" );
		$this->_iv		= rtrim( fgets( $fh ), "\r\n" );
		$this->_key		= rtrim( fgets( $fh ), "\r\n" );
		fclose($fh);
		
		if( !( $this->_key && $this->_iv && $this->_cipher ) )
			$this->_storeNewKey();
		if( !( $this->_key && $this->_iv && $this->_cipher ) )
			return null;
	} // _construct
	
	
	/**
	* Encrypt
	*
	* Returns an encrypted version of the string
	*
	* @param (string) The string to encrypt
	* @return (null)
	*/
	public function Encrypt( $val )
	{	
		return openssl_encrypt( $val, $this->_cipher, $this->_key, OPENSSL_RAW_DATA, $this->_iv );
	} // Encrypt
	
	/**
	* Decrypt
	*
	* Returns a decrypted version of a string
	*
	* @param (string) The string to decrypt
	* @return (null)
	*/
	public function Decrypt( $val )
	{
		return openssl_decrypt( $val, $this->_cipher, $this->_key, OPENSSL_RAW_DATA, $this->_iv );
	
	} // Encrypt
	
	
	/**
	* _storeNewKey
	*
	* Creates and stores a new key for the current best cipher type
	*
	* @return (null)
	*/
	private static function _storeNewKey( )
	{
		
		$ciphers = openssl_get_cipher_methods(true);
		if( in_array( 'blowfish', $ciphers ) )
			$cipher = 'blowfish';
		elseif( in_array( 'AES256', $ciphers ) )
			$cipher = 'AES256';
		elseif( in_array( 'DES3', $ciphers ) )
			$cipher = 'DES3';
		else
			return null;
		
		$key_length = 0;
		switch( $cipher )
		{
			case 'blowfish':
				$key_length = 56;
				break;
			case 'AES256':
				$key_length = 32;
				break;
			case 'DES3':
				$key_length = 24;
				break;
		}
		
		$iv = openssl_random_pseudo_bytes( openssl_cipher_iv_length ( $cipher ) );
		$key = openssl_random_pseudo_bytes( $key_length );
		
		// Remove crlf
		$iv = preg_replace( '/[\r\n]/', '0', $iv );
		$key = preg_replace( '/[\r\n]/', '0', $key );
		
		#TODO: Would be better to use PBKDF2
		
		
		$fh = File::OpenWrite( CRYPTO_FN, 'Crypto' );
		if( !$fh )
			return null;
		
		fwrite( $fh, $cipher . "\n" );
		fwrite( $fh, $iv  . "\n" );
		fwrite( $fh, $key );
		
		fclose( $fh );
		
		return true;
	
	} // _storeNewKey
	
} // Crypto