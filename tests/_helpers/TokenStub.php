<?php


namespace prime\tests\_helpers;


use SamIT\LimeSurvey\Interfaces\WritableTokenInterface;

class TokenStub implements WritableTokenInterface
{

    public function __construct($surveyId, array $tokenData, $generateToken = true)
    {

    }

    /**
     * @return int The unique ID for this token.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * @return int The unique ID for the survey.
     */
    public function getSurveyId()
    {
        // TODO: Implement getSurveyId() method.
    }

    /**
     * @return string
     */
    public function getToken()
    {
        // TODO: Implement getToken() method.
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        // TODO: Implement getFirstName() method.
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        // TODO: Implement getLastName() method.
    }

    /**
     * @return \DateTimeInterface
     */
    public function getValidFrom()
    {
        // TODO: Implement getValidFrom() method.
    }

    /**
     * @return \DateTimeInterface
     */
    public function getValidUntil()
    {
        // TODO: Implement getValidUntil() method.
    }

    /**
     * @return int
     */
    public function getUsesLeft()
    {
        // TODO: Implement getUsesLeft() method.
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        // TODO: Implement getEmail() method.
    }

    /**
     * @return \DateTimeInterface|null Returns the timestamp of completion, or null if not completed.
     */
    public function getCompleted()
    {
        // TODO: Implement getCompleted() method.
    }

    /**
     * @return \DateTimeInterface|null Returns the timestamp of invitation, or null if not completed.
     */
    public function getInvitationSent()
    {
        // TODO: Implement getInvitationSent() method.
    }

    /**
     * @return int The number of reminders sent
     */
    public function getReminderCount()
    {
        // TODO: Implement getReminderCount() method.
    }

    /**
     * @return \DateTimeInterface|null Returns the timestamp of reminder, or null if not completed.
     */
    public function getReminderSent()
    {
        // TODO: Implement getReminderSent() method.
    }

    /**
     * @return string The default language of the survey.
     */
    public function getLanguage()
    {
        // TODO: Implement getLanguage() method.
    }

    /**
     * @return string[] An array of custom attribute name to value. Keys must be the name from LS not the "attribute_x" database fields.
     */
    public function getCustomAttributes()
    {
        // TODO: Implement getCustomAttributes() method.
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
        // TODO: Implement setFirstName() method.
    }

    /**
     * @param string $value
     * @return void
     */
    public function setLastName($value)
    {
        // TODO: Implement setLastName() method.
    }

    /**
     * @param string $value
     * @return void
     */
    public function setToken($value)
    {
        // TODO: Implement setToken() method.
    }

    /**
     * @param \DateTimeInterface $value The valid from datetime for this token, pass null to not use a valid from datetime.
     * @return void
     */
    public function setValidFrom(\DateTimeInterface $value = null)
    {
        // TODO: Implement setValidFrom() method.
    }

    /**
     * @param \DateTimeInterface $value The valid until datetime for this token, pass null to not use a valid until datetime.
     * @return void
     */
    public function setValidUntil(\DateTimeInterface $value = null)
    {
        // TODO: Implement setValidUntil() method.
    }

    /**
     * @param int $value The number of uses left for the token.
     * @return void
     */
    public function setUsesLeft($value)
    {
        // TODO: Implement setUsesLeft() method.
    }

    /**
     * @param string $value
     * @return void
     */
    public function setEmail($value)
    {
        // TODO: Implement setEmail() method.
    }

    /**
     * @param \DateTimeInterface $value The completion datetime for this token, pass null to mark token as incomplete.
     * @return void
     */
    public function setCompleted(\DateTimeInterface $value = null)
    {
        // TODO: Implement setCompleted() method.
    }

    /**
     * @param \DateTimeInterface $value The datetime on which an invitation was sent to this token, set to null to mark as not invited.
     * @return void
     */
    public function setInvitationSent(\DateTimeInterface $value = null)
    {
        // TODO: Implement setInvitationSent() method.
    }

    /**
     * @param int $value The number of reminders that have been sent for the token.
     * @return void
     */
    public function setReminderCount($value)
    {
        // TODO: Implement setReminderCount() method.
    }

    /**
     * @param \DateTimeInterface $value The datetime on which the last reminder was sent to this token, set to null to mark as no reminder sent.
     * @return void
     */
    public function setReminderSent(\DateTimeInterface $value = null)
    {
        // TODO: Implement setReminderSent() method.
    }

    /**
     * @param string $value The language to use for this token.
     * @return void
     */
    public function setLanguage($value)
    {
        // TODO: Implement setLanguage() method.
    }

    /**
     * @param string $name The name of the attribute.
     * @param string $value The value of this attribute, it is always stored as a string.
     * @return void
     * @throws \InvalidArgumentException When $name is an unknown custom attribute.
     */
    public function setCustomAttribute($name, $value)
    {
        // TODO: Implement setCustomAttribute() method.
    }
}