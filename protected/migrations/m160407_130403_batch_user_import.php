<?php

use yii\db\Migration;

class m160407_130403_batch_user_import extends Migration
{
    protected $emails = <<<EMAIL
galerm@who.int
abouzeida@who.int
abouzeidhcc@gmail.com
alonsojc@paho.org
altafm@yem.emro.who.int
calderom@paho.org
claire.who15@gmail.com
costamakakala@yahoo.fr
coulibalyc@who.int
daizoa@who.int
davidmutonga@yahoo.com
edabiree@who.int
elaminz@who.int
galerm@who.int
gozalovo@who.int
habshir99@yahoo.com
kalmykova@who.int
khanm@who.int
khanmu@pak.emro.who.int
kormossp@who.int
korpo304@gmail.com
limon_rnp@yahoo.com
margata2001@gmail.com
marschanga@who.int
mashhadik@nbo.emro.who.int
munima@who.int
njha@hotmail.com
novelog@who.int
peycheva.elena@mail.ru
rrbonifacio@hotmail.com
ruhanam@who.int
sackom@ml.afro.who.int
sanchezp@paho.org
shankitii@afg.emro.who.int
shariefa@who.int
tanolij@who.int
valderramac@who.int
wekesaj@who.int
yatambwede@gmail.com
ymu@who-health.org
yurkovai@rambler.ru
EMAIL;
    public function safeUp()
    {

        foreach (explode("\n", $this->emails) as $email) {
            if (null === \prime\models\ar\User::findOne(['email' => $email])) {
                /** @var \prime\models\ar\User $user */
                $user = Yii::createObject([
                    'class' => \prime\models\ar\User::class,
                    'scenario' => 'create',
                ]);
                $user->email = $email;
                $user->confirmed_at = time();
                $user->password_hash = 'NOPASSWORD';
                if (!$user->save()) {
                    echo "Failed to save user.";
                    print_r($user->errors);
                    return false;
                }

            }
        }
        return true;
    }

    public function safeDown()
    {
        \prime\models\ar\User::deleteAll([
            'email' => explode("\n", $this->emails),
            'password_hash' => 'NOPASSWORD'
        ]);
        return true;

    }

}
