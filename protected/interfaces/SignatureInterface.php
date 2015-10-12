<?php

namespace prime\interfaces;

interface SignatureInterface
{
    /**
     * Email of the user
     * @return string
     */
    public function getEmail();

    /**
     * Cryptographic signature
     * @return string
     */
    public function getHash();

    /**
     * Id of the user
     * @return int
     */
    public function getId();

    /**
     * Full name of the user
     * @return string
     */
    public function getName();

    /**
     * Moment of signing
     * @return \DateTimeImmutable
     */
    public function getTime();
}