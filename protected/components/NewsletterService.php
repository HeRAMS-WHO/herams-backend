<?php

declare(strict_types=1);

namespace prime\components;

use DrewM\MailChimp\MailChimp;
use herams\common\domain\user\User;
use herams\common\domain\user\UserRepository;
use herams\common\helpers\Secret;
use yii\base\Component;
use yii\web\Request;

class NewsletterService extends Component
{
    public Secret|string $mailchimpListId;

    public Secret|string $mailchimpTag;

    private const MAILCHIMP_MEMBERSHIP_STATUS_CLEANED = 'cleaned';

    private const MAILCHIMP_MEMBERSHIP_STATUS_PENDING = 'pending';

    private const MAILCHIMP_MEMBERSHIP_STATUS_SUBSCRIBED = 'subscribed';

    private const MAILCHIMP_MEMBERSHIP_STATUS_TRANSACTIONAL = 'transactional';

    private const MAILCHIMP_MEMBERSHIP_STATUS_UNSUBSCRIBED = 'unsubscribed';

    public function __construct(
        private MailChimp $client,
        private UserRepository $userRepository,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function handleWebhook(Request $request): void
    {
        if ($request->getBodyParam('type') === 'subscribe' && ! empty($data = $request->getBodyParam('data'))) {
            if ($user = $this->userRepository->find()->andWhere([
                'email' => $data['email'],
            ])->one()) {
                $user->updateAttributes([
                    'newsletter_subscription' => true,
                ]);
            }
        }

        if ($request->getBodyParam('type') === 'unsubscribe' && ! empty($data = $request->getBodyParam('data'))) {
            if ($user = $this->userRepository->find()->andWhere([
                'email' => $data['email'],
            ])->one()) {
                $user->updateAttributes([
                    'newsletter_subscription' => false,
                ]);
            }
        }
    }

    public function initSyncExternalToDatabase(): void
    {
        $offset = 0;
        $count = 100;
        do {
            $response = $this->client->get("lists/{$this->mailchimpListId}/members?offset={$offset}&count={$count}&fields=members.email_address,members.status");
            $members = $response['members'];
            foreach ($members as $member) {
                $this->userRepository->updateAll([
                    'newsletter_subscription' => $this->isSubscribed($member['status']),
                ], [
                    'email' => $member['email_address'],
                ]);
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
        if (! $this->mailchimpListId || ! $this->mailchimpTag) {
            return;
        }

        $userHash = md5($user->email);

        $this->client->put("lists/{$this->mailchimpListId}/members/{$userHash}", [
            'email_address' => $user->email,
            'status_if_new' => $user->newsletter_subscription ? self::MAILCHIMP_MEMBERSHIP_STATUS_SUBSCRIBED : self::MAILCHIMP_MEMBERSHIP_STATUS_UNSUBSCRIBED,
            'status' => $user->newsletter_subscription ? self::MAILCHIMP_MEMBERSHIP_STATUS_SUBSCRIBED : self::MAILCHIMP_MEMBERSHIP_STATUS_UNSUBSCRIBED,
            'tags' => [
                $this->mailchimpTag,
            ],
        ]);
    }
}
