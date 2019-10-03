<?php

namespace App\Traits;

use ParagonIE\Halite\KeyFactory;
use ParagonIE\Halite\HiddenString;
use ParagonIE\Halite\Symmetric\EncryptionKey;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;

trait Encryptable
{

    /**
     * @param $key
     * @return mixed|HiddenString
     * @throws \ParagonIE\Halite\Alerts\CannotPerformOperation
     * @throws \ParagonIE\Halite\Alerts\InvalidDigestLength
     * @throws \ParagonIE\Halite\Alerts\InvalidKey
     * @throws \ParagonIE\Halite\Alerts\InvalidMessage
     * @throws \ParagonIE\Halite\Alerts\InvalidSignature
     * @throws \ParagonIE\Halite\Alerts\InvalidType
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable)) {
            $value = $this->decrypt($value);
        }
        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     * @throws \ParagonIE\Halite\Alerts\CannotPerformOperation
     * @throws \ParagonIE\Halite\Alerts\InvalidDigestLength
     * @throws \ParagonIE\Halite\Alerts\InvalidKey
     * @throws \ParagonIE\Halite\Alerts\InvalidMessage
     * @throws \ParagonIE\Halite\Alerts\InvalidType
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = $this->encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Encrypt a message and return the ciphertext.  Note that we don't need to worry about the nonce..
     *
     * @param string $message
     * @return string
     * @throws \ParagonIE\Halite\Alerts\CannotPerformOperation
     * @throws \ParagonIE\Halite\Alerts\InvalidDigestLength
     * @throws \ParagonIE\Halite\Alerts\InvalidKey
     * @throws \ParagonIE\Halite\Alerts\InvalidMessage
     * @throws \ParagonIE\Halite\Alerts\InvalidType
     */
    public function encrypt(string $message): string
    {
        $encryptionKey = $this->getEncryptionKey();
        $hiddenString = new HiddenString($message);
        return Symmetric::encrypt($hiddenString, $encryptionKey);
    }

    /**
     * Decrypt the supplied ciphertext
     *
     * @param string $ciphertext
     * @return HiddenString
     * @throws \ParagonIE\Halite\Alerts\CannotPerformOperation
     * @throws \ParagonIE\Halite\Alerts\InvalidDigestLength
     * @throws \ParagonIE\Halite\Alerts\InvalidKey
     * @throws \ParagonIE\Halite\Alerts\InvalidMessage
     * @throws \ParagonIE\Halite\Alerts\InvalidSignature
     * @throws \ParagonIE\Halite\Alerts\InvalidType
     */
    public function decrypt(string $ciphertext): HiddenString
    {
        $encryptionKey = $this->getEncryptionKey();
        return Symmetric::decrypt($ciphertext, $encryptionKey);
    }

    /**
     * @return string
     * @throws \ParagonIE\Halite\Alerts\CannotPerformOperation
     * @throws \ParagonIE\Halite\Alerts\InvalidKey
     */
    public function getEncryptionKey(): EncryptionKey
    {
        $filename = '../../../encryption.key';
        if (!file_exists($filename)) {
            $enc_key = KeyFactory::generateEncryptionKey();
            KeyFactory::save($enc_key, $filename);
        } else {
            $enc_key = KeyFactory::loadEncryptionKey($filename);
        }
        return $enc_key;
    }

}