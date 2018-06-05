<?php

namespace prime\interfaces;

interface TicketingInterface {


    public function createToken(
        string $identifier,
        ?int $expires = null
    ): string;

    /**
     * Logs the current application user in to the federated application using SSO
     * This should end the request immediately.
     */
    public function loginAndRedirectCurrentUser(): void;
}