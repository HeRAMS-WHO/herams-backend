<?php

namespace prime\tests\_helpers;

use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;

class TokenStub implements WritableTokenInterface
{
    private $data = [];
    private $surveyId;

    public function __construct($surveyId, array $tokenData)
    {
        $this->data = $tokenData;
        $this->surveyId = $surveyId;
    }

    /**
     * @return int The unique ID for this token.
     */
    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    /**
     * @return int The unique ID for the survey.
     */
    public function getSurveyId()
    {
        return $this->surveyId;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->data['token'] ?? null;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->data['firstName'] ?? null;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->data['lastName'] ?? null;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getValidFrom()
    {
        return $this->data['validFrom'] ?? null;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getValidUntil()
    {
        return $this->data['validUntil'] ?? null;
    }

    /**
     * @return int
     */
    public function getUsesLeft()
    {
        return $this->data['usesLeft'] ?? null;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->data['email'] ?? null;
    }

    /**
     * @return \DateTimeInterface|null Returns the timestamp of completion, or null if not completed.
     */
    public function getCompleted()
    {
        return $this->data['completed'] ?? null;
    }

    /**
     * @return \DateTimeInterface|null Returns the timestamp of invitation, or null if not completed.
     */
    public function getInvitationSent()
    {
        return $this->data['invitationSent'] ?? null;
    }

    /**
     * @return int The number of reminders sent
     */
    public function getReminderCount()
    {
        return $this->data['reminderCount'] ?? null;
    }

    /**
     * @return \DateTimeInterface|null Returns the timestamp of reminder, or null if not completed.
     */
    public function getReminderSent()
    {
        return $this->data['reminderSent'] ?? null;
    }

    /**
     * @return string The default language of the survey.
     */
    public function getLanguage()
    {
        return $this->data['language'] ?? null;
    }

    /**
     * @return string[] An array of custom attribute name to value. Keys must be the name from LS not the "attribute_x" database fields.
     */
    public function getCustomAttributes()
    {
        return $this->data['customAttributes'] ?? [];
    }

    /**
     * @return bool Return true successful, false otherwise.
     */
    public function save()
    {
        return true;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setFirstName($value)
    {
        return $this->data['firstName'] = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setLastName($value)
    {
        return $this->data['lastName'] = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setToken($value)
    {
        return $this->data['token'] = $value;
    }

    /**
     * @param \DateTimeInterface $value The valid from datetime for this token, pass null to not use a valid from datetime.
     * @return void
     */
    public function setValidFrom(\DateTimeInterface $value = null)
    {
        return $this->data['validFrom'] = $value;
    }

    /**
     * @param \DateTimeInterface $value The valid until datetime for this token, pass null to not use a valid until datetime.
     * @return void
     */
    public function setValidUntil(\DateTimeInterface $value = null)
    {
        return $this->data['validUntil'] = $value;
    }

    /**
     * @param int $value The number of uses left for the token.
     * @return void
     */
    public function setUsesLeft($value)
    {
        return $this->data['usesLeft'] = $value;
    }

    /**
     * @param string $value
     * @return void
     */
    public function setEmail($value)
    {
        return $this->data['email'] = $value;
    }

    /**
     * @param \DateTimeInterface $value The completion datetime for this token, pass null to mark token as incomplete.
     * @return void
     */
    public function setCompleted(\DateTimeInterface $value = null)
    {
        return $this->data['completed'] = $value;
    }

    /**
     * @param \DateTimeInterface $value The datetime on which an invitation was sent to this token, set to null to mark as not invited.
     * @return void
     */
    public function setInvitationSent(\DateTimeInterface $value = null)
    {
        return $this->data['invitationSent'] = $value;
    }

    /**
     * @param int $value The number of reminders that have been sent for the token.
     * @return void
     */
    public function setReminderCount($value)
    {
        return $this->data['reminderCount'] = $value;
    }

    /**
     * @param \DateTimeInterface $value The datetime on which the last reminder was sent to this token, set to null to mark as no reminder sent.
     * @return void
     */
    public function setReminderSent(\DateTimeInterface $value = null)
    {
        return $this->data['reminderSent'] = $value;
    }

    /**
     * @param string $value The language to use for this token.
     * @return void
     */
    public function setLanguage($value)
    {
        return $this->data['language'] = $value;
    }

    /**
     * @param string $name The name of the attribute.
     * @param string $value The value of this attribute, it is always stored as a string.
     * @return void
     * @throws \InvalidArgumentException When $name is an unknown custom attribute.
     */
    public function setCustomAttribute($name, $value)
    {
        return $this->data['customAttributes'][$name] = $value;
    }
}
