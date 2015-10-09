<?php


namespace app\components;


        use dektrium\user\clients\ClientInterface;

        class LinkedIn extends \yii\authclient\clients\LinkedIn implements ClientInterface
        {

            /** @inheritdoc */
            public function getEmail()
            {
                return isset($this->getUserAttributes()['email-address'])
                    ? $this->getUserAttributes()['email-address']
                    : null;
            }

            /** @inheritdoc */
            public function getUsername()
            {
                return $this->getEmail();
            }
        }