<?php
declare(strict_types=1);

namespace prime\components;

use DrewM\MailChimp\MailChimp;
use DrewM\MailChimp\Webhook;
use prime\models\ar\User;
use yii\base\Component;
use yii\web\Request;

class NewsletterService extends Component
{
    public string $mailchimpListId;
    public string $mailchimpTag;

    private const MAILCHIMP_MEMBERSHIP_STATUS_CLEANED = 'cleaned';
    private const MAILCHIMP_MEMBERSHIP_STATUS_PENDING = 'pending';
    private const MAILCHIMP_MEMBERSHIP_STATUS_SUBSCRIBED = 'subscribed';
    private const MAILCHIMP_MEMBERSHIP_STATUS_TRANSACTIONAL = 'transactional';
    private const MAILCHIMP_MEMBERSHIP_STATUS_UNSUBSCRIBED = 'unsubscribed';

    public function __construct(
        private MailChimp $client,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function handleWebhook(Request $request): void
    {
        Webhook::subscribe('subscribe', function (array $data) {
            User::updateAll(['newsletter_subscription' => true], ['email' => (bool) $data['email']]);
        });

        Webhook::subscribe('unsubscribe', function (array $data) {
            User::updateAll(['newsletter_subscription' => false], ['email' => (bool) $data['email']]);
        });
    }

    public function initSyncExternalToDatabase(): void
    {
        $offset = 0;
        $count = 100;
        do {
            $response = $this->client->get("lists/{$this->mailchimpListId}/members?offset={$offset}&count={$count}&fields=members.email_address,members.status");
            $members = $response['members'];
            foreach ($members as $member) {
                User::updateAll(['newsletter_subscription' => $this->isSubscribed($member['status'])], ['email' => $member['email_address']]);
            }

            $offset += $count;
        } while (count($members) == $count);
    }

    private function isSubscribed(string $status): bool
    {
        return [
            self::MAILCHIMP_MEMBERSHIP_STATUS_CLEANED => false,
            self::MAILCHIMP_MEMBERSHIP_STATUS_PENDING => false,
            self::MAILCHIMP_MEMBERSHIP_STATUS_SUBSCRIBED => true,
            self::MAILCHIMP_MEMBERSHIP_STATUS_TRANSACTIONAL => false,
            self::MAILCHIMP_MEMBERSHIP_STATUS_UNSUBSCRIBED => false,
        ][$status];
    }

    public function syncToExternal(User $user, bool $insert): void
    {
        // If this is not configured, we don't want external syncing
        if (!$this->mailchimpListId || !$this->mailchimpTag) {
            return;
        }

        $userHash = md5($user->email);

        $this->client->put("lists/{$this->mailchimpListId}/members/{$userHash}", [
            'email_address' => $user->email,
            'status_if_new' => $user->newsletter_subscription ? self::MAILCHIMP_MEMBERSHIP_STATUS_SUBSCRIBED : self::MAILCHIMP_MEMBERSHIP_STATUS_UNSUBSCRIBED,
            'status' => $user->newsletter_subscription ? self::MAILCHIMP_MEMBERSHIP_STATUS_SUBSCRIBED : self::MAILCHIMP_MEMBERSHIP_STATUS_UNSUBSCRIBED,
            'tags' => [
                $this->mailchimpTag,
            ]
        ]);
    }
}
