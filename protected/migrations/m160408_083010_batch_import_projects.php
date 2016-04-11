<?php

use yii\db\Migration;

class m160408_083010_batch_import_projects extends Migration
{
    protected $data = <<<CSV
habshir99@yahoo.com,CCPM,Western Equatoria,15/03/2013 00:00,SSD,2tcdkvsgnicatzh
tanolij@who.int,CCPM,,24/03/2014 00:00,SDN,3zhvuud5f88hkui
edabiree@who.int,CCPM,,09/07/2014 00:00,CAF,4ahdtn6q2qen4j9
elaminz@who.int,CCPM,Western Darfur,18/08/2015 00:00,SDN,54s6ggjfutusg5d
daizoa@who.int,CCPM,,31/08/2015 00:00,TCD,6gnz8bi65w4ne3v
gozalovo@who.int,CCPM,Kharkiv,11/06/2015 00:00,UKR,6stx2d5q2m9ve7k
sanchezp@paho.org,CCPM,,01/07/2015 00:00,COL,6xz8cjtyucan8dq
limon_rnp@yahoo.com,CCPM,Bor,01/09/2014 00:00,SSD,758ev9a88knykgm
shariefa@who.int,CCPM,Northern Darfur,18/08/2015 00:00,SDN,7jebryiyv4ibdb2
yatambwede@gmail.com,CCPM,North Kivu,22/11/2013 00:00,COD,8rrnk7janvucpmg
claire.who15@gmail.com,CCPM,Donetsk,11/06/2015 00:00,UKR,8suvzhp8qfjpg9d
marschanga@who.int,CCPM,,14/11/2013 00:00,COD,9j9rf2rqkg2ygrd
altafm@yem.emro.who.int,CCPM,,16/09/2013 00:00,YEM,a4mgicf8nm8uc3c
novelog@who.int,CCPM,Rakhine,01/07/2015 00:00,MMR,a85b5h5gbav6egb
khanmu@pak.emro.who.int,CCPM,,17/04/2014 00:00,PAK,ar9vi9trq5awncs
wekesaj@who.int,CCPM,,01/09/2014 00:00,SSD,bfwkn2w6n5dzznz
novelog@who.int,CCPM,Kachin,01/07/2015 00:00,MMR,bz5c785gbav4h5t
ruhanam@who.int,CCPM,,26/05/2015 00:00,LBR,c69h9hh9x5khp8v
calderom@paho.org,CCPM,,20/08/2014 00:00,COL,dkcra23cz8bbzwn
ymu@who-health.org,CCPM,,08/03/2013 00:00,PSE,dnfcvtazdwcuqu7
mashhadik@nbo.emro.who.int,CCPM,,12/02/2012 00:00,SOM,e3zy5fnqw88bqnm
kalmykova@who.int,CCPM,,20/10/2015 00:00,SYR,e5pmi9qjuv2mhwf
davidmutonga@yahoo.com,CCPM,Eastern Equatoria,15/03/2013 00:00,SSD,ejtfhtgwzu9saek
valderramac@who.int,CCPM,Gaziantep,10/08/2015 00:00,SYR,eu8mw34xi48u4gs
sackom@ml.afro.who.int,CCPM,,30/07/2013 00:00,MLI,gfwzhcmfkeuzz5v
korpo304@gmail.com,CCPM,Central Equatoria,15/03/2013 00:00,SSD,hqe36hz4rwjsuky
munima@who.int,CCPM,,06/07/2015 00:00,SOM,hrhxxn9gapi7pdz
margata2001@gmail.com,CCPM,Northern Bahr el Ghazal,15/03/2013 00:00,SSD,jd5t438bzq9usyh
costamakakala@yahoo.fr,CCPM,South Kivu,08/12/2013 00:00,COD,kp6g35wnj6zah8i
tanolij@who.int,CCPM,,18/08/2015 00:00,SDN,p588nuttgzr2p7b
alonsojc@paho.org,CCPM,,13/09/2013 00:00,HTI,pijaayxh72vd55c
abouzeida@who.int,CCPM,,10/02/2015 00:00,IRQ,qf324z4didmcei2
abouzeida@who.int,CCPM,South Central Zone,12/02/2012 00:00,SOM,sjf79c4h98vimf6
coulibalyc@who.int,CCPM,,12/03/2015 00:00,MLI,upxr3866fn3tws4
kormossp@who.int,CCPM,,11/06/2015 00:00,UKR,uratyf5bzn9g3t7
yurkovai@rambler.ru,CCPM,Severodonetsk,11/06/2015 00:00,UKR,utjasqfheejwyvd
peycheva.elena@mail.ru,CCPM,Luansk,11/06/2015 00:00,UKR,v283xvfctetqu5u
shankitii@afg.emro.who.int,CCPM,,17/06/2014 00:00,AFG,wq7eb8d6ytqzik2
galerm@who.int,CCPM,,03/07/2013 00:00,AFG,wr4eeb8igccudfv
novelog@who.int,CCPM,,01/07/2015 00:00,MMR,yusw973r2uyatks
khanm@who.int,CCPM,Southern Darfur,18/08/2015 00:00,SDN,zg9adebb7dmpddc
abouzeida@who.int,CCPM,,07/04/2015 00:00,AFG,zukynxpbu2jj8yv
gozalovo@who.int,CD,Kharkiv,11/06/2015 00:00,UKR,031jg83hsnb9whx
shankitii@afg.emro.who.int,CD,,17/06/2014 00:00,AFG,2k7t2xx2pr7esxt
claire.who15@gmail.com,CD,Donetsk,11/06/2015 00:00,UKR,34h5h345h6ou86lrq
habshir99@yahoo.com,CD,Western Equatoria,15/03/2013 00:00,SSD,398gku6bv5tum87
rrbonifacio@hotmail.com,CD,Unity,15/03/2013 00:00,SSD,3ntrbdebryixefz
davidmutonga@yahoo.com,CD,Eastern Equatoria,15/03/2013 00:00,SSD,4wfayduw7txrvsg
yatambwede@gmail.com,CD,North Kivu,22/11/2013 00:00,COD,5uw69iv7d6s924d
yurkovai@rambler.ru,CD,Severodonetsk,11/06/2015 00:00,UKR,642d3sd65zgheuh32
sanchezp@paho.org,CD,,01/07/2015 00:00,COL,76859nfkskbh4g
calderom@paho.org,CD,,20/08/2014 00:00,COL,7enppefanedw4rb
novelog@who.int,CD,,01/07/2015 00:00,MMR,85fkj347hknxbna3
njha@hotmail.com,CD,Lakes,15/03/2013 00:00,SSD,8bqnkn2ej88gx38
sackom@ml.afro.who.int,CD,,30/07/2013 00:00,MLI,8niba42gt9fmseu
novelog@who.int,CD,Kachin,01/07/2015 00:00,MMR,ads789mf3rslv0
costamakakala@yahoo.fr,CD,South Kivu,08/12/2013 00:00,COD,btxkq4edjtzrjsy
galerm@who.int,CD,,03/07/2013 00:00,AFG,dbzgjtevu5r7gdx
limon_rnp@yahoo.com,CD,Jonglei,15/03/2013 00:00,SSD,dk8k9tdbf9yhv9t
margata2001@gmail.com,CD,Northern Bahr el Ghazal,15/03/2013 00:00,SSD,eftk22ddctkbafi
marschanga@who.int,CD,,14/11/2013 00:00,COD,em346urmkdfqqb4
abouzeida@who.int,CD,,10/02/2015 00:00,IRQ,f9zuaxtnfecjkaf
daizoa@who.int,CD,,31/08/2015 00:00,TCD,fdskz463hdfka92
tanolij@who.int,CD,,24/03/2014 00:00,SDN,fvt2rvcacr9zmt5
khanm@who.int,CD,Southern Darfur,18/08/2015 00:00,SDN,fwdoiuhpiuhv239ksd
limon_rnp@yahoo.com,CD,Bor,01/09/2014 00:00,SSD,ge4igr4z7fjvcz2
abouzeida@who.int,CD,,07/04/2015 00:00,AFG,gra7c3becevjsdq
altafm@yem.emro.who.int,CD,,16/09/2013 00:00,YEM,guwjux66c7c3ip6
ymu@who-health.org,CD,,08/03/2013 00:00,PSE,hb4j8bvjkd6mfuq
valderramac@who.int,CD,Gaziantep,10/08/2015 00:00,SYR,hfdsahjlljdL6644201
novelog@who.int,CD,Rakhine,01/07/2015 00:00,MMR,hhhvd744n899clj
peycheva.elena@mail.ru,CD,Luansk,11/06/2015 00:00,UKR,hif8734hfe7zfhqp
khanmu@pak.emro.who.int,CD,,17/04/2014 00:00,PAK,k2zbs9998dpb7iz
munima@who.int,CD,,06/07/2015 00:00,SOM,ldfsnlvsfdasvoh6691
abouzeida@who.int,CD,South Central Zone,02/12/2012 00:00,SOM,m6nf6p3pkwdt2qc
kormossp@who.int,CD,,11/06/2015 00:00,UKR,op9806ipozuzt
shariefa@who.int,CD,Northern Darfur,18/08/2015 00:00,SDN,rqb325gfd7888ffqwd
elaminz@who.int,CD,Western Darfur,18/08/2015 00:00,SDN,sfzdgvshjkl55443jw
edabiree@who.int,CD,,09/07/2014 00:00,CAF,t4dechn5wd9paun
kalmykova@who.int,CD,,20/10/2015 00:00,SYR,te0mb7jqvzwjfvsacdd
tanolij@who.int,CD,,18/08/2015 00:00,SDN,uioz5467zzg4gh471
korpo304@gmail.com,CD,Central Equatoria,15/03/2013 00:00,SSD,ur8nks4mwn4nacd
mashhadik@nbo.emro.who.int,CD,,02/12/2012 00:00,SOM,wmman886a9ci5sb
alonsojc@paho.org,CD,,13/09/2013 00:00,HTI,x86re6htv5a3eai
wekesaj@who.int,CD,,01/09/2014 00:00,SSD,xmje69pmf2vta92
ruhanam@who.int,CD,,26/05/2015 00:00,LBR,yahr392j5dg35sc
coulibalyc@who.int,CD,,12/03/2015 00:00,MLI,znugk4erszxikjj
CSV;



    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        foreach(explode("\n", $this->data) as $line) {
            list($email, $acronym, $locality, $createdString, $countryCode, $tokenString) = explode(',', $line);
            if (null === $user = \prime\models\ar\User::findOne(['email' => $email])) {
                throw new \Exception("No user for email: $email");
            }
            if (null === $country = \prime\models\Country::findOne($countryCode)) {
                throw new \Exception("No country for code: $countryCode");
            }
            $created = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $createdString);
            if (count($tools = \prime\models\ar\Tool::findAll(['acronym' => $acronym])) != 1) {
                throw new \Exception("No (unique) tool for acronym: $acronym");
            }
            $tool = $tools[0];
            echo "Checking token with LimeSurvey...";
            if (null === $token = app()->limeSurvey->getToken($tool->base_survey_eid, $tokenString)) {
                throw new \Exception("Token not found in survey {$tool->base_survey_eid}: $tokenString.");
            }
            echo "OK\n";
            $project = new \prime\models\ar\Project();
            $project->data_survey_eid = $tool->base_survey_eid;
            $project->tool_id = $tool->id;
            $project->created = $created;
            $project->locality_name = $locality;
            $project->country_iso_3 = $country->iso_3;
            $project->owner_id = $user->id;
            $project->token = $token->getToken();
            $project->default_generator = $acronym == 'CCPM' ? 'ccpm' : 'cd';

            $project->title = $acronym == 'CCPM' ? 'Cluster Coordination Performance Monitoring' : 'Cluster Description';
            $project->description = $project->title;
            if (!$project->validate()) {
                throw new \Exception("Validation errors:" . print_r($project->errors, true));
            }

            if (!$project->save(false)) {
                throw new \Exception("Failed to save:" . print_r($project->attributes, true));
            }
            
        }
        return true;
    }

    public function safeDown()
    {
        foreach(explode("\n", $this->data) as $line) {
            list($email, $acronym, $locality, $createdString, $countryCode, $tokenString) = explode(',', $line);
            \prime\models\ar\Project::deleteAll(['token' => $tokenString]);

        }
        return true;
    }

}
