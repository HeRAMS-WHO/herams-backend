<?php

use yii\db\Migration;

class m160114_121645_migrate_users_to_prime2 extends Migration
{
    protected $data = 'HC1,ABOU ZEID Alaa,Alaa,ABOU ZEID,abouzeida@who.int,WHO
HC2,ALDLAIMI Sami,Sami,ALDLAIMI,aldlaimis@who.int,WHO
HC3,AL-DULAIMI Ayyed Mohan Muneam,Ayyed Mohan Muneam,AL-DULAIMI,munima@who.int,WHO
HC4,ALKEMA Gerbrand,Gerbrand,ALKEMA,g.alkema@savethechildren.org.uk,Save the Children
HC5,ALONSO Gustavo,Gustavo,ALONSO,alonsojc@paho.org,PAHO
HC6,ALTAF Mohamed Daoud,Mohamed Daoud,ALTAF,altafm@who.int,WHO
HC7,ARMAH Magdalene,Magdalene,ARMAH,armahm@who.int ,WHO
HC8,BABA NOEL Liehoun,Liehoun,BABA NOEL,,
HC9,BISALINKUMI Esechiel,Esechiel,BISALINKUMI,bisalinkumie@who.int,WHO
HC10,BOBOEVA Mohira,Mohira,BOBOEVA,babaevam@who.int,WHO
HC11,CALDERON Mauricio,Mauricio,CALDERON,calderom@paho.org,PAHO
HC12,CHARIMARI Lincoln,Lincoln,CHARIMARI,charimaril@zw.afro.who.int,WHO
HC13,DAHER Mahmoud,Mahmoud,DAHER,daherm@who.int,WHO
HC14,DAIZO Arsene,Arsene,DAIZO,daizoa@who.int,WHO
HC15,DABIRE Ernest,Ernest,DABIRE,dabiree@who.int,WHO
HC16,DIALLO Amadou,Amadou,DIALLO,dialloam@who.int,WHO
HC17,DOWNS Lane Benjamin,Lane Benjamin,DOWNS,laneb@wpro.who.int,WHO
HC18,DUBE Alfred,Alfred,DUBE,dubeal@who.int ,WHO
HC19,DULYX Jennyfer,Jennyfer,DULYX,dulyxj@who.int,WHO
HC20,EL GANAINY Ahmed,Ahmed,EL GANAINY,elganainya@who.int,WHO
HC21,FOTSING Richard,Richard,FOTSING,fotsingri@who.int,WHO
HC22,GURACHA Argata,Argata,GURACHA,guyoa@who.int,WHO
HC23,HAMPTON Craig,Craig,HAMPTON,hamptonc@who.int,WHO
HC24,HOFF Elizabeth,Elizabeth,HOFF,hoffe@who.int,WHO
HC25,JAHANGIR Alam,Alam,JAHANGIR,jahangir_95@yahoo.com,WHO
HC26,KALMYKOV Azret Stanislavovich,Azret Stanislavovich,KALMYKOV,kalmykova@who.int,WHO
HC27,KHAN Fawad,Fawad,KHAN,khanmu@pak.emro.who.int,WHO
HC28,KHAN Selim,Selim,KHAN,,MoH
HC29,KIM Hyo Jeong,Hyo Jeong,KIM,kimhy@who.int,WHO
HC30,KIM Rok Ho,Rok Ho,KIM,kimr@wpro.who.int,WHO
HC31,LINCOLN Adams,Adams,LINCOLN,,MoH
HC32,LOMBELELO ANDJAFUMBAL Lom\'s,Lom\'s,LOMBELELO ANDJAFUMBAL,lombelelo@yahoo.ca,WHO
HC33,LUKWESA Jean de Dieu,Jean de Dieu,LUKWESA,lukwesamwatij@who.int,WHO
HC34,MAKAKALA Constantin,Constantin,MAKAKALA,makakalamuhululu@who.int,WHO
HC35,MANN Philipp,Philipp,MANN,,
HC36,MOHAMED Hamasha,Hamasha,MOHAMED,hamasham@who.int,WHO
HC37,MOHAMED Abdelrahman,Abdelrahman,MOHAMED,shariefa@sud.emro.who.int,MoH
HC38,MOHAMMED Munir,Munir,MOHAMMED,,MoH
HC39,MUCIPAY Alphonse,Alphonse,MUCIPAY,mucipayndumbia@who.int,WHO
HC40,NAHAABI Dinah,Dinah,NAHAABI,dnahaabi@yahoo.co.uk,WHO
HC41,NGOBILA Collin,Collin,NGOBILA,ngobilaco@who.int,WHO
HC42,NIANG Saïdou,Saïdou,NIANG,niangs@who.int,WHO
HC43,NITZAN KALUSKI Dorit,Dorit,NITZAN KALUSKI,don@euro.who.int ,WHO
HC44,NOVELO Gabriel,Gabriel,NOVELO,novelog@searo.who.int,WHO
HC45,NZEYIMANA Innocent,Innocent,NZEYIMANA,nzeyimanai@who.int,WHO
HC46,OULD AHMEDOU Yacoub,Yacoub,OULD AHMEDOU,ahmedouy@mr.afro.who.int,WHO
HC47,PANNELL Antonia,Antonia,PANNELL,pannella@who.int,WHO
HC48,RUHANA MIRINDI Bisimwa,Bisimwa,RUHANA MIRINDI,ruhanam@who.int,WHO
HC49,SACKO Massambou,Massambou,SACKO,sackom@who.int,WHO
HC50,SALVADOR Edwin,Edwin,SALVADOR,salvadore@searo.who.int,WHO
HC51,SAMA Rosine,Rosine,SAMA,samak@who.int,WHO
HC52,SHANKITI Iman,Iman,SHANKITI,shankitii@afg.emro.who.int,WHO
HC53,SOBOH Abdelnasser ,Abdelnasser ,SOBOH,soboha@who.int ,WHO
HC54,TANOLI Jamshed,Jamshed,TANOLI,tanolij@sud.emro.who.int,WHO
HC55,THEODORE Yao,Yao,THEODORE,yaot@who.int,WHO
HC56,VALDERRAMA Camilo,Camilo,VALDERRAMA,valderramac@who.int,WHO
HC57,WEKESA Julius,Julius,WEKESA,wekesaj@who.int,WHO
HC58,WOLDEGEBRIEL Aregai,Aregai,WOLDEGEBRIEL,woldegebrieltede@who.int,WHO
HC59,YAOHLOU MAWUEMIYO André Adandji,André Adandji,YAOHLOU MAWUEMIYO,adandjiyaohloua@who.int,WHO';

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        foreach(explode("\n", $this->data) as $line) {
            /** @var \prime\models\ar\User $user */
            $user = Yii::createObject([
                'class'    => \prime\models\ar\User::class,
                'scenario' => 'create',
            ]);
            $fields = explode(",", $line);
            if (empty($fields[4])) continue;
            $user->email = trim($fields[4]);
            $user->id = intval(substr($fields[0], 2)) + 10000;
            $user->confirmed_at = time();
            $user->password_hash = 'NOPASSWORD';
            if (!$user->save()) {
                var_dump($user->attributes);
                die(var_dump($user->getErrors()));
            }
        }
    }

    public function safeDown()
    {
    }

}
