<?php

use app\components\Html;
use yii\helpers\ArrayHelper;
use app\components\Form;

/**
 * @var \prime\models\ar\UserData $userData
 * @var \yii\web\View $this
 * @var \prime\reportGenerators\ccpm\Generator $generator
 * @var \prime\interfaces\ProjectInterface $project
 * @var \prime\interfaces\SignatureInterface $signature
 * @var \prime\interfaces\ResponseCollectionInterface $responses
 */

$generator = $this->context;

$scores = [];
foreach($generator->sectionQuestionMapping() as $section => $map) {
    if(count(explode('.', $section)) <= 2) {
        $scores[$section] = $generator->calculateScore($responses, $map, 'average');
    } else {
        $scores[$section] = $generator->calculateScore($responses, $map);
    }
}

$distributions = [];
$surveyQuestionMap = [];
foreach($generator->sectionQuestionMapping() as $section => $map) {
    if(count(explode('.', $section)) == 3) {
        $distributions[$section] = $generator->calculateDistribution($responses, $map);
        foreach($map as $surveyId => $questionTitles) {
            if(!isset($surveyQuestionMap[$surveyId])) {
                $surveyQuestionMap[$surveyId] = [];
            }
            $surveyQuestionMap[$surveyId] = array_merge($surveyQuestionMap[$surveyId], $questionTitles);
        }

    }
}
//TODO: use function to retrieve questions and answers, for now use hardcoded since it is slow
//$questionsAndAnswers = $generator->getQuestionAndAnswerTexts($surveys, $surveyQuestionMap);
$questionsAndAnswers = json_decode('{"22814":{"q111":{"text":"Are you satisfied with the frequency of cluster meetings?","answers":{"0":"No meetings have been held","1":"Not at all satisfied","2":"Rather unsatisfied","3":"Fairly satisfied","4":"Very satisfied","99":"Do not know"}},"q112":{"text":"How frequently has your organization participated in cluster meetings?\u00a0","answers":{"0":"Never","1":"Rarely","2":"Sometimes","3":"Often","4":"Always","99":"Do not know"}},"q115":{"text":"Have cluster meetings been able to identify and discuss needs, gaps and response priorities?","answers":{"1":"No","2":"To a limited extent","3":"Quite a lot","4":"Definitely","99":"Do not know"}},"q114":{"text":"Could your organization participate fully in cluster meetings? (For example, were meetings in accessible locations? Could participants speak in a range of languages?)\u00a0","answers":{"1":"It was very difficult to attend and participate in cluster meetings","2":"It was quite difficult to attend and participate in cluster meetings","3":"It was fairly easy to attend and participate in cluster meetings","4":"It was very easy to attend and participate in cluster meetings","99":"Do not know"}},"q116":{"text":"Has the cluster taken strategic decisions about the direction of the humanitarian response?","answers":{"0":"No strategic decisions were taken","1":"Strategic decisions were taken but they were not useful","2":"Strategic decisions were taken and they were somewhat useful","3":"Strategic decisions were taken and they were mostly useful","4":"Strategic decisions were taken and they were very useful","99":"Do not know"}},"q113":{"text":"Did the staff who represented your organization at cluster meetings have decision-making authority and were they able to follow-up on decisions made?","answers":{"2":"They had limited decision-making authority and some ability to follow-up on decisions made","4":"They had full decision-making authority and were fully able to follow-up on decisions made","99":"Do not know"}},"q121":{"text":"Has your cluster regularly mapped what partners are doing and where they are working (via 3W and similar mechanisms)? Has your organization contributed?","answers":{"999":"No mapping has been done","0":"Mapping has been done but my organization did not contribute","1":"Mapping has been done and my organization contributed far less often than required","2":"Mapping has been done and my organization contributed less often than required","3":"Mapping has been done and my organization contributed almost as often as required","4":"Mapping has been done and my organization contributed as often as required ","99":"Do not know","9999":"Not applicable (for example, because my organization is a donor)"}},"q122":{"text":"Has your organization contributed to analyses by the cluster of gaps and overlaps (capacity and complementarity), derived from this mapping?","answers":{"999":"No analyses of capacity and complementarity have been undertaken","0":"Analyses have been done but my organization was not invited to participate","1":"My organization was invited to participate but did not do so","2":"My organization participated but its contribution was not adequately taken into account","3":"My organization participated and its contribution was taken into account somewhat adequately","4":"My organization participated and its contribution was adequately taken into account","99":"Do not know","9999":"Not applicable (for example, because my organization has observer status or is not engaged in this cluster activity.)"}},"q123":{"text":"Has your organization taken decisions based on the cluster\u2019s analysis of gaps and overlaps (capacity and complementarity), derived from this mapping?","answers":{"0":"Never","1":"Seldom","2":"Sometimes","3":"Often","4":"Always","99":"Do not know"}},"q211":{"text":"Has your organization used sectoral needs assessment tools and guidance agreed by cluster\u00a0partners?\u00a0","answers":{"999":"No assessment tools and guidance have been agreed","0":"Assessment tools and guidance have been agreed but my organization has not used them","1":"Assessment tools and guidance have been agreed but my organization has seldom used them","2":"Assessment tools and guidance have been agreed and my organization has sometimes used them ","3":"Assessment tools and guidance have been agreed and my organization has often used them","4":"Assessment tools and guidance have been agreed and my organization has always used them","99":"Do not know"}},"q212":{"text":"Has your organization been involved in coordinated sectoral needs assessment and surveys?","answers":{"999":"No coordinated assessments or surveys have been done","0":"Coordinated assessments and surveys have been done but my organization has not been involved","1":"My organization has rarely been involved in coordinated assessments and surveys","2":"My organization has sometimes been involved in coordinated assessments and surveys","3":"My organization has been involved in most coordinated assessments and surveys","4":"My organization has been involved in all coordinated assessments and surveys ","99":"Do not know","9999":"Not applicable (for example because my organization has observer status or is not engaged in this cluster activity.)"}},"q213":{"text":"Has your organization shared reports of its surveys and assessments with the cluster?","answers":{"999":"No surveys or assessments have been done","0":"My organization has shared none of its survey or assessment reports","1":"My organization has shared few of its survey and assessment reports","2":"My organization has shared some of its survey and assessment reports","3":"My organization has shared most survey and assessment reports","4":"My organization has shared all its survey and assessment reports","99":"Do not know","9999":"Not applicable (for example because my organization has observer status or is not engaged in this cluster activity.)"}},"q222[1]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q222[2]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q222[3]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q222[4]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q222[5]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q223[1]":{"text":"Have these analyses considered cross cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[2]":{"text":"Have these analyses considered cross cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[3]":{"text":"Have these analyses considered cross cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[4]":{"text":"Have these analyses considered cross cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[5]":{"text":"Have these analyses considered cross cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[6]":{"text":"Have these analyses considered cross cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[7]":{"text":"Have these analyses considered cross cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[8]":{"text":"Have these analyses considered cross cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q221":{"text":"Has your organization done situation analyses together with cluster partners?","answers":{"4":"Yes","0":"No","99":"Do not know"}},"q231":{"text":"Have these analyses supported response planning and prioritization?","answers":{"1":"Never","2":"Sometimes","3":"Often","4":"Always","99":"Do not know"}},"q311":{"text":"Has your organization helped to develop a cluster strategic plan?","answers":{"999":"The cluster has not developed its strategic plan","0":"A plan has been developed but my organization was not asked to participate","1":"My organization was asked to help develop the plan but it did not contribute","2":"My organization helped develop the plan but its contribution was not adequately taken into account","3":"My organization helped develop the plan and its contribution was taken into account somewhat adequately","4":"My organization helped develop the plan and its contribution was adequately taken into account","99":"Do not know","9999":"Not applicable (for example, because my organization has observer status or is not engaged in this cluster activity.)"}},"q312":{"text":"Has the cluster strategic plan guided the response of your organization in the last 6 months?","answers":{"0":"A strategic plan exists but it has not been shared with my organization","1":"The strategic plan has been shared but my organization has not used it","2":"The strategic plan has been shared and my organization has sometimes used it","3":"The strategic plan has been shared and my organization has often used it","4":"The strategic plan has been shared and my organization has always used it","99":"Do not know"}},"q321":{"text":"Have cluster partners agreed technical standards and guidance and has your organization applied them?*","answers":{"0":"No technical standards\/guidelines have been agreed","1":"Technical standards\/guidelines have been agreed but my organization has not used them","2":"Technical standards\/guidelines have been agreed and my organization has sometimes used them","3":"Technical standards\/guidelines have been agreed and my organization has often used them","4":"Technical standards\/guidelines have been agreed and my organization has always used them","99":"Do not know"}},"q332":{"text":"Were proposals prioritized against the strategic plan in a manner that was fair to all partners?","answers":{"0":"Proposals were not prioritized against the strategic plan","1":"Proposals were prioritized but in a manner that was unfair to partners","2":"Proposals were prioritized in a manner that was unfair to the majority of partners","3":"Proposals were prioritized in a manner that was fair to the majority of partners","4":"Proposals were prioritized in a manner that was fair to all partners","99":"Do not know"}},"q331":{"text":"Have cluster partners participated in prioritizing proposals\u00a0under the strategic plan. Were transparent criteria agreed?\n\n\t\u00a0","answers":{"0":"No transparent criteria were agreed and partners did not jointly prioritize proposals","1":"Transparent criteria have not been agreed but partners jointly prioritized proposals","2":"Transparent criteria were agreed but partners did not jointly prioritize proposals","3":"Transparent criteria were agreed and partners jointly prioritized proposals to some extent","4":"Transparent criteria were agreed and partners were fully involved in prioritizing proposals","99":"Do not know"}},"q333":{"text":"How often has the cluster coordinator reported on the cluster\u2019s funding status against needs*?","answers":{"0":"Never","1":"Far less often than needed","2":"Less often than needed","3":"Almost as often as needed","4":"As often as needed","99":"Do not know"}},"q411":{"text":"Have issues requiring advocacy been identified and discussed\u00a0 together with your organization?","answers":{"0":"No advocacy issues have been discussed","1":"Advocacy issues have been discussed but my organization was not invited to participate","2":"My organization was invited to discussions of advocacy issues but did not participate","3":"My organization participated in advocacy discussions but its views were not adequately considered","4":"My organization participated in advocacy discussions and its views were adequately considered","99":"Do not know","9999":"Not applicable (for example, because my organization has observer status or is not engaged in this cluster activity.)"}},"q421":{"text":"Has your organization participated in cluster advocacy activities?","answers":{"0":"The cluster has undertaken no advocacy","1":"The cluster has undertaken advocacy but my organization was not invited to participate","2":"The cluster invited my organization to participate in its advocacy, but it did not do so","3":"My organization has participated in some of the cluster\u2019s advocacy activities","4":"My organization has participated in most of the cluster\u2019s advocacy activities","99":"Do not know","9999":"Not applicable (for example, because my organization has observer status or is not engaged in this cluster activity)"}},"q52":{"text":"Has your organization used programme monitoring and reporting formats agreed by the cluster?","answers":{"0":"No standards for monitoring and reporting have been agreed","1":"Standards have been agreed but my organization does not use these formats for reporting","2":"Standards have been agreed and my organization has sometimes used these formats when it reports","3":"Standards have been agreed and my organization has regularly used these formats when it reports","4":"Standards have been agreed and my organization has used these formats very regularly for reporting","99":"Do not know"}},"q51":{"text":"Have cluster bulletins or updates highlighted risks, gaps and changing needs, and has this information influenced your organization\u2019s decisions?","answers":{"0":"Cluster bulletins and other reports have not highlighted risks, gaps and changing needs.","1":"My organization has not used cluster information on needs, risks and gaps for decision-making","2":"My organization has sometimes used cluster information on needs, risks and gaps for decision-making","3":"My organization has often used cluster information on needs, risks and gaps for decision-making","4":"My organization has always used cluster information on needs, risks and gaps for decision-making","99":"Do not know"}},"q53":{"text":"Has your cluster taken account of the distinct needs, contributions and capacities of women, girls, men and boys in its response and monitoring?","answers":{"999":"Not applicable","0":"No","1":"To a small extent","2":"Partially","3":"Mostly","4":"Fully","99":"Do not know"}},"q61":{"text":"Has your organization helped to develop or update preparedness plans (including multisectoral ones) that address hazards and risks?","answers":{"0":"A preparedness plan has not been written or updated","1":"A preparedness plan was drafted\/updated but my organization was not invited to participate","2":"My organization was invited to help develop\/update the preparedness plan but did not do so","3":"My organization helped develop\/update the preparedness plan but its contribution was inadequate","4":"My organization helped develop\/update the preparedness plan and its contribution was adequate","99":"Do not know","9999":"Not applicable (for example because my organization has observer status or is not engaged in this cluster activity.)"}},"q62":{"text":"Has your organization committed staff or resources that can be mobilized when preparedness plans are\u00a0activated?","answers":{"0":"No staff or resources have been committed","2":"Limited staff or resources have been committed","4":"Adequate staff or resources have been committed","99":"Do not know","9999":"Not applicable (for example because my organization has observer status or is not engaging in this cluster activity.)"}},"q71":{"text":"Have cluster partners agreed mechanisms (procedures, tools or methodologies) for consulting and involving affected people in decision-making?* Has your organization applied them?\u00a0","answers":{"999":"No mechanisms for consultation\/involvement have been agreed","0":"Mechanisms have been agreed but my organization has not applied them","1":"Mechanisms have been agreed but my organization has seldom applied them","2":"Mechanisms have been agreed and my organization has sometimes applied them","3":"Mechanisms have been agreed and my organization has often applied them","4":"Mechanisms have been agreed and my organization has always applied them","99":"Do not know"}},"q72":{"text":"Have cluster partners agreed mechanisms (procedures, tools or methodologies) to receive, investigate and act on complaints by affected people? Does your organization apply these mechanisms?","answers":{"999":"No investigation\/complaint mechanism has been agreed","0":"An investigation\/complaint mechanism has been agreed but my organization has not applied it","1":"An investigation\/complaint mechanism has been agreed but my organization has seldom applied it","2":"An investigation\/complaint mechanism has been agreed and my organization has sometimes applied it","3":"An investigation\/complaint mechanism has been agreed and my organization has often applied it","4":"An investigation\/complaint mechanism has been agreed and my organization has always applied it","99":"Do not know"}}},"67825":{"q111":{"text":"Has the list of cluster partners (including members and observers) been updated as needed?","answers":{"0":"No list has been established","1":"The list has been updated far less often than needed","2":"The list has been updated less often than needed","3":"The list has been updated almost as often as needed","4":"The list has been updated as often as needed","99":"Do not know"}},"q112":{"text":"Are you satisfied with the frequency of cluster meetings?","answers":{"0":"No meetings have been organized","1":"Not satisfied","2":"Satisfied to a limited extent","3":"Quite satisfied","4":"Completely satisfied","99":"Do not know"}},"q113":{"text":"Have minutes been taken at cluster meetings, with action points?","answers":{"1":"No minutes have been taken","2":"Minutes have been taken but action points have not","3":"Minutes with action points have been taken at some meetings","4":"Minutes with action points have been taken at most meetings","99":"Do not know"}},"q114":{"text":"Have members and observers attended cluster meetings?","answers":{"1":"Few attended","2":"Some attended","3":"Most attended, but few major actors attended","4":"Most attended, including major actors","99":"Do not know"}},"q115":{"text":"Have cluster meetings been useful in helping partners to discuss needs, gaps and priorities?","answers":{"1":"They have not been useful","2":"They have been somewhat useful","3":"They have generally been useful","4":"They have been very useful","99":"Do not know"}},"q116":{"text":"Have you regularly attended humanitarian inter-sectoral coordination meetings, such as inter-cluster coordination meetings or country team meetings?","answers":{"999":"No meetings have taken place","0":"I have not attended any meetings","1":"I have rarely attended meetings","2":"I have sometimes attended meetings","3":"I have often attended meetings","4":"I have always attended meetings","99":"Do not know"}},"q117":{"text":"Has the cluster supported or engaged with coordination mechanisms of national authorities in its sector?","answers":{"999":"No coordination mechanisms exist; engagement is not appropriate; question is not applicable","0":"Support or engagement have not occurred, although they would be appropriate","1":"National coordination representatives have rarely participated in cluster meetings","2":"National coordination representatives have often participated in cluster meetings","3":"National coordination representatives co-chair cluster meetings","4":"Cluster partners are fully engaged under national coordination","99":"Do not know"}},"q118":{"text":"Could members and observers participate fully in cluster meetings? (For example, did meetings occur in accessible locations? Were participants able to speak in a range of languages?)","answers":{"1":"It was very difficult to attend\/participate in cluster meetings","2":"It was somewhat difficult to attend\/participate in cluster meetings","3":"It was fairly easy to attend\/participate in cluster meetings","4":"It was easy to attend\/participate in cluster meetings ","99":"Do not know"}},"q119":{"text":"Has the cluster taken strategic decisions about the direction of the humanitarian response?","answers":{"0":"No strategic decisions were taken","1":"Strategic decisions were taken but they were not useful","2":"Strategic decisions were taken and they were somewhat useful","3":"Strategic decisions were taken and they were mostly useful","4":"Strategic decisions were taken and they were very useful","99":"Do not know"}},"q121":{"text":"Has the cluster regularly mapped what partners are doing and where they are working (via 3W and similar mechanisms)?","answers":{"0":"No mapping has been done","1":"Mapping was done but not updated","2":"Mapping was done but not updated as often as required","3":"Mapping was done and usually updated as often as required","4":"Mapping was done and always updated as often as required","99":"Do not know"}},"q122":{"text":"How many partners have helped to map programme activities and their geographical presence?","answers":{"1":"None","2":"Few","3":"Most","4":"All","99":"Do not know"}},"q123":{"text":"Has the cluster used information on programme activities and partners\u2019 geographical presence to analyse capacity and complementarity (gaps and overlaps). Has that information influenced cluster partners\u2019 decisions?","answers":{"0":"No analysis has been done","1":"Analysis has been done but has not been used for decision making","2":"Analysis has been done and has been used by a few partners for decision making","3":"Analysis has been done and has been used by some partners for decision making","4":"Analysis has been done and has been used by most partners for decision making","99":"Do not know"}},"q211":{"text":"Have cluster partners used jointly agreed sectoral needs assessment tools and guidance?","answers":{"0":"No assessment tools or guidance have been agreed","1":"The cluster has agreed tools and guidance but no partners have used them","2":"The cluster has agreed tools and guidance and a few partners have used them","3":"The cluster has agreed tools and guidance and some partners have used them","4":"The cluster has agreed tools and guidance and most partners have used them","99":"Do not know"}},"q212":{"text":"Have cluster partners been involved in coordinated sectoral needs assessments and surveys?","answers":{"0":"No coordinated assessments have been done","1":"Coordinated assessments have been done but partners have not been involved","2":"Partners have been involved in some coordinated assessments","3":"Partners have been involved in most coordinated assessments","4":"Partners have been involved in all coordinated assessments","99":"Do not know"}},"q213":{"text":"Have cluster partners shared their own surveys and assessments with the cluster?","answers":{"0":"No surveys or assessments have been done","1":"Survey and assessment reports have not been shared by any partner","2":"Survey and assessment reports have been shared by a few partners","3":"Survey and assessment reports have been shared by most partners","4":"Survey and assessment reports have been shared by all partners","99":"Do not know"}},"q222[1]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q222[2]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q222[3]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q222[4]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q222[5]":{"text":"Have these analyses identified risks, needs, gaps, capacity to respond, and constraints?","answers":{"1":"Not identified","2":"Partially identified","3":"Mostly identified","4":"Fully identified","99":"Do not know"}},"q223[1]":{"text":"Have these analyses considered cross-cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[2]":{"text":"Have these analyses considered cross-cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[3]":{"text":"Have these analyses considered cross-cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[4]":{"text":"Have these analyses considered cross-cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[5]":{"text":"Have these analyses considered cross-cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[6]":{"text":"Have these analyses considered cross-cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[7]":{"text":"Have these analyses considered cross-cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q223[8]":{"text":"Have these analyses considered cross-cutting issues?","answers":{"1":"Not considered","2":"Partially considered","3":"Mostly considered","4":"Fully considered","99":"Do not know"}},"q221":{"text":"Have you done situation analyses together with cluster partners?","answers":{"4":"Yes","0":"No","99":"Do not know"}},"q231":{"text":"Have these analyses supported response planning and prioritization?","answers":{"1":"Never","2":"Sometimes","3":"Often","4":"Always","99":"Do not know"}},"q312":{"text":"Does the cluster\u2019s strategic plan include objectives, activities and indicators?","answers":{"1":"No","2":"To some extent","3":"To a large extent","4":"Fully","99":"Do not know"}},"q311":{"text":"Has a cluster strategic plan been developed?","answers":{"4":"Yes","0":"No","99":"Not applicable"}},"q314":{"text":"Did cluster partners help to develop the cluster\u2019s strategic plan?","answers":{"1":"Cluster partners did not participate in developing the plan","2":"Cluster partners were involved to some extent in developing the plan","3":"Cluster partners were involved to a large extent in developing the plan","4":"Cluster partners were fully involved in developing the plan","99":"Do not know"}},"q315[1]":{"text":"Does the cluster\u2019s strategic plan address cross cutting issues?","answers":{"1":"Not addressed","2":"Partially addressed","3":"Mostly addressed","4":"Fully addressed","99":"Do not know"}},"q315[2]":{"text":"Does the cluster\u2019s strategic plan address cross cutting issues?","answers":{"1":"Not addressed","2":"Partially addressed","3":"Mostly addressed","4":"Fully addressed","99":"Do not know"}},"q315[3]":{"text":"Does the cluster\u2019s strategic plan address cross cutting issues?","answers":{"1":"Not addressed","2":"Partially addressed","3":"Mostly addressed","4":"Fully addressed","99":"Do not know"}},"q315[4]":{"text":"Does the cluster\u2019s strategic plan address cross cutting issues?","answers":{"1":"Not addressed","2":"Partially addressed","3":"Mostly addressed","4":"Fully addressed","99":"Do not know"}},"q315[5]":{"text":"Does the cluster\u2019s strategic plan address cross cutting issues?","answers":{"1":"Not addressed","2":"Partially addressed","3":"Mostly addressed","4":"Fully addressed","99":"Do not know"}},"q315[6]":{"text":"Does the cluster\u2019s strategic plan address cross cutting issues?","answers":{"1":"Not addressed","2":"Partially addressed","3":"Mostly addressed","4":"Fully addressed","99":"Do not know"}},"q315[7]":{"text":"Does the cluster\u2019s strategic plan address cross cutting issues?","answers":{"1":"Not addressed","2":"Partially addressed","3":"Mostly addressed","4":"Fully addressed","99":"Do not know"}},"q315[8]":{"text":"Does the cluster\u2019s strategic plan address cross cutting issues?","answers":{"1":"Not addressed","2":"Partially addressed","3":"Mostly addressed","4":"Fully addressed","99":"Do not know"}},"q316":{"text":"Does the sectoral strategic plan show synergies with other sectors, in line with the strategic objectives of the HCT?","answers":{"1":"The cluster\'s strategic plan does not address synergies with other sectors","2":"The cluster\'s strategic plan addresses synergies with other clusters to some extent","3":"The cluster\'s strategic plan addresses synergies with other clusters to a large extent","4":"The cluster\'s strategic plan addresses synergies with other clusters fully","99":"Do not know"}},"q317":{"text":"During the last six months, how many partners have used the cluster\u2019s strategic plan to guide their response?","answers":{"1":"None","2":"A few","3":"Some","4":"Most","99":"Do not know"}},"q318":{"text":"Have partners helped to identify deactivation criteria and a phase out strategy for the cluster?","answers":{"0":"Deactivation criteria and a phase-out strategy have not been identified or discussed with partners","1":"Deactivation criteria and a phase out strategy have been identified but without partners","2":"Deactivation criteria and a phase out strategy have been identified with some partners","3":"Deactivation criteria and a phase out strategy have been identified with most partners","4":"Deactivation criteria and a phase out strategy have been identified with all partners","99":"Do not know"}},"q313":{"text":"Has the cluster\u2019s strategic plan been reviewed against the host government\u2019s strategy?","answers":{"999":"The host government lacks a strategy","4":"Yes","0":"No","99":"Do not know"}},"q321":{"text":"Have national and international standards and guidance been identified, adapted in consultation with national authorities (when necessary), and shared with partners?","answers":{"0":"No standards and guidance have been identified","1":"Standards and guidance have been identified but have not been adapted or shared","2":"Standards and guidance have been identified and shared but have not been adapted","3":"Standards and guidance have been identified and adapted but have not been shared","4":"Standards and guidance have been identified, adapted and shared","99":"Do not know"}},"q322":{"text":"Have technical standards and guidance been agreed and have partners used them?","answers":{"0":"No technical standards and guidance have been agreed","1":"Technical standards and guidance have been agreed but no partners have used them","2":"Technical standards and guidance have been agreed and a few partners have used them","3":"Technical standards and guidance have been agreed and some partners have used them","4":"Technical standards and guidance have been agreed and all partners have used them","99":"Do not know"}},"q332":{"text":"Were proposals prioritized against the strategic plan in a manner that was fair to all partners?","answers":{"0":"Proposals were not prioritized against the strategic plan","1":"Proposals were prioritized but in a manner that was unfair to partners","2":"Proposals were prioritized in a manner that was unfair to the majority of partners","3":"Proposals were prioritized in a manner that was fair to the majority of partners","4":"Proposals were prioritized in a manner that was fair to all partners","99":"Do not know"}},"q333":{"text":"Has the cluster assisted partners to access funds (for example by including their proposals in appeals or applications to the Emergency Response Fund or Common Humanitarian Fund)?","answers":{"0":"The cluster has given partners no support","1":"The cluster has given partners poor support","2":"The cluster has given partners average support","3":"The cluster has given partners good support","4":"The cluster has given partners very good support","99":"Do not know"}},"q331":{"text":"Have cluster partners participated in prioritizing proposals against the strategic plan? Were transparent criteria agreed?","answers":{"0":"No transparent criteria were agreed and partners did not jointly prioritize proposals","1":"Transparent criteria have not been agreed but partners jointly prioritized proposals","2":"Transparent criteria were agreed but partners did not jointly prioritize proposals","3":"Transparent criteria were agreed and partners jointly prioritized proposals to some extent","4":"Transparent criteria were agreed and partners were fully involved in prioritizing proposals","99":"Do not know"}},"q334":{"text":"How often have you reported on the funding status of the cluster against needs?*","answers":{"0":"Never","1":"Far less often than needed","2":"Less often than needed","3":"Almost as often as needed","4":"As often as needed","99":"Do not know"}},"q411":{"text":"Has the cluster identified issues requiring advocacy and discussed them with partners?","answers":{"0":"The cluster has not discussed advocacy issues","1":"The cluster has identified advocacy issues without consulting partners","2":"The cluster has identified advocacy issues in consultation with some partners","3":"The cluster has identified advocacy issues in consultation with most partners","4":"The cluster has identified advocacy issues in consultation with all partners","99":"Do not know"}},"q421":{"text":"Have advocacy activities been agreed and undertaken together with partners?","answers":{"0":"No advocacy activities have been agreed","1":"Advocacy activities have been agreed but no partners have taken part in them","2":"Advocacy activities have been agreed and some partners have taken part in them","3":"Advocacy activities have been agreed and most partners have taken part in them","4":"Advocacy activities have been agreed and all partners have taken part in them","99":"Do not know"}},"q51":{"text":"Have partners used programme monitoring and reporting formats that cluster partners have agreed?","answers":{"0":"The cluster has not agreed standards for monitoring and reporting","1":"Standards have been agreed but few partners have reported regularly","2":"Standards have been agreed and some partners have reported regularly","3":"Standards have been agreed and most partners have reported regularly","4":"Standards have been agreed and all partners have reported regularly","99":"Do not know"}},"q52":{"text":"Is the information that partners send reflected in cluster bulletins and updates?","answers":{"0":"No information has been shared","1":"Information has been shared but has not been taken into account","2":"Information has been shared and has been taken into account to some extent","3":"Information has been shared and has been taken into account to a large extent","4":"Information has been shared and has been taken into account fully","99":"Do not know"}},"q53":{"text":"Has progress on programmes or the strategic plan been reported using agreed indicators for monitoring the humanitarian response? *","answers":{"0":"Never","1":"Far less often than needed","2":"Less often than needed","3":"Almost as often as needed","4":"As often as needed","99":"Do not know"}},"q54":{"text":"Have cluster bulletins or updates been published?","answers":{"0":"Never","1":"Far less often than needed","2":"Less often than needed","3":"Almost as often as needed","4":"As often as needed","99":"Do not know"}},"q55":{"text":"Have\u00a0cluster bulletins\u00a0or updates highlighted risks, gaps and changing needs, and has this information influenced decisions?","answers":{"0":"Changes in needs, risks and gaps have not been highlighted in any bulletins or reports","1":"Changes in needs, risks and gaps have been highlighted but have not been used for decision-making","2":"Changes in needs, risks and gaps have been highlighted and have sometimes been used for decision-making","3":"Changes in needs, risks and gaps have been highlighted and have often been used for decision-making","4":"Changes in needs, risks and gaps have been highlighted and have always been used for decision-making","99":"Do not know"}},"q56":{"text":"Has your cluster taken into account the distinct needs, contributions and capacities of women, girls, men and boys, in its response and monitoring?*","answers":{"999":"Not applicable","0":"No","1":"To a small extent","2":"Partially","3":"Mostly","4":"Fully","99":"Do not know"}},"q61":{"text":"Have\u00a0national preparedness or contingency plans (sectoral or multi-sectoral) been identified and shared?","answers":{"0":"No national preparedness or contingency plans have been identified","2":"A national plan has been identified but the cluster has not discussed it","4":"A national plan has been identified and the cluster has discussed it","99":"Do not know"}},"q62":{"text":"Have cluster partners contributed to initial risk assessments and analysis (including multi sectoral), or updates?","answers":{"0":"No risk assessment has been done","1":"Risk assessment has been done but has not involved partners","2":"Risk assessment has been done and some partners have participated","3":"Risk assessment has been done and most partners have participated","4":"Risk assessment has been done and all partners have participated","99":"Do not know"}},"q63":{"text":"Have cluster partners helped to develop or update preparedness plans (including multisectoral ones) that address hazards and risks?","answers":{"0":"No preparedness plan has been written or updated","1":"Preparedness plans have been written\/updated but partners have not participated","2":"Preparedness plans have been written\/updated and some partners have participated","3":"Preparedness plans have been written\/updated and most partners have participated","4":"Preparedness plans have been written\/updated and all partners have participated","99":"Do not know"}},"q64":{"text":"Have cluster partners committed staff\u00a0or resources that can be mobilized when preparedness plans are activated?\n\n\tPlease choose only one of the following:","answers":{"0":"No partners have committed staff or resources that can be mobilized ","1":"Few partners have committed staff or resources that can be mobilized","2":"Some partners have committed staff or resources that can be mobilized","3":"Most partners have committed staff or resources that can be mobilized","4":"All partners have committed staff or resources that can be mobilized","99":"Do not know"}},"q65":{"text":"Have you regularly shared and discussed\u00a0early warning reports with cluster partners?","answers":{"999":"There are no early warning reports","0":"No early warning reports have been shared","1":"Early warning reports have rarely been shared","2":"Early warning reports have sometimes been shared","3":"Early warning reports have often been shared","4":"Early warning reports have always been shared","99":"Do not know"}},"q71":{"text":"Have\u00a0cluster partners agreed and applied mechanisms (procedures, tools or methodologies) for consulting and involving affected people in decision-making?*","answers":{"0":"No mechanisms for consulting\/involving affected people have been agreed","1":"Mechanisms for consulting\/involving affected people have been agreed but no partners have applied them","2":"Mechanisms for consulting\/involving affected people have been agreed and some partners have applied them","3":"Mechanisms for consulting\/involving affected people have been agreed and most partners have applied them","4":"Mechanisms for consulting\/involving affected people have been agreed and all partners have applied them","99":"Do not know"}},"q72":{"text":"Have\u00a0cluster partners agreed and applied mechanisms (procedures, tools or methodologies) to receive, investigate and act on complaints about assistance received?*","answers":{"0":"No investigation\/complaint mechanism has been agreed ","1":"An investigation\/complaint mechanism has been agreed but no parners have applied them","2":"An investigation\/complaint mechanism has been agreed and some parners have applied them","3":"An investigation\/complaint mechanism has been agreed and most parners have applied them","4":"An investigation\/complaint mechanism has been agreed and all partners have applied them","99":"Do not know"}},"q012[1]":{"text":"How many organizations are currently participating in the cluster, i.e. partners and observers?","answers":[]},"q012[2]":{"text":"How many organizations are currently participating in the cluster, i.e. partners and observers?","answers":[]},"q012[3]":{"text":"How many organizations are currently participating in the cluster, i.e. partners and observers?","answers":[]},"q012[4]":{"text":"How many organizations are currently participating in the cluster, i.e. partners and observers?","answers":[]},"q012[5]":{"text":"How many organizations are currently participating in the cluster, i.e. partners and observers?","answers":[]},"q012[6]":{"text":"How many organizations are currently participating in the cluster, i.e. partners and observers?","answers":[]},"q013[1]":{"text":"How many organizations are on average participating to cluster meetings, i.e. partners and observers?","answers":[]},"q013[2]":{"text":"How many organizations are on average participating to cluster meetings, i.e. partners and observers?","answers":[]},"q013[3]":{"text":"How many organizations are on average participating to cluster meetings, i.e. partners and observers?","answers":[]},"q013[4]":{"text":"How many organizations are on average participating to cluster meetings, i.e. partners and observers?","answers":[]},"q013[5]":{"text":"How many organizations are on average participating to cluster meetings, i.e. partners and observers?","answers":[]},"q013[6]":{"text":"How many organizations are on average participating to cluster meetings, i.e. partners and observers?","answers":[]}}}', true);

$this->beginContent('@app/views/layouts/report.php');
?>
<style>
    <?=file_get_contents(__DIR__ . '/../../base/assets/css/grid.css')?>
    <?php include __DIR__ . '/../../base/assets/css/style.php'; ?>
    .background-good, .background-satisfactory, .background-unsatisfactory, .background-weak {
        font-weight: 600;
    }

    .background-good {
        background-color: #1fc63c;
        color: white;
    }

    .background-satisfactory {
        background-color: #ffe003;
        color: white;
    }

    .background-unsatisfactory {
        background-color: #ff9400;
        color: white;
    }

    .background-weak {
        background-color: red;
        color: white;
    }

    .text-good {
        color: #1fc63c;
    }

    .text-satisfactory {
        color: #ffe003;
    }

    .text-unsatisfactory {
        color: #ff9400;
    }

    .text-weak {
        color: red;
    }
</style>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h1 class="col-xs-12"><?=$project->getLocality()?></h1>
    </div>
    <?php
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            \Yii::t('ccpm', 'Level : {level}', ['level' => 'National']) . '<br>' . \Yii::t('ccpm', 'Completed on: {completedOn}', ['completedOn' => $signature->getTime()->format('d F - Y')]),
        ],
        'columnsInRow' => 2
    ]);
    ?>
    <hr>
    <div class="row">
        <h1 style="margin-top: 300px; margin-bottom: 300px; text-align: center;"><?=\Yii::t('ccpm', 'Final report')?></h1>
    </div>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <div class="col-xs-12">
        <h2><?=\Yii::t('ccpm', 'Overall response rate')?><span style="font-size: 0.5em; margin-left: 50px;">(<?=Yii::t('ccpm', 'Based on the number of organizations that are part of the cluster')?></span></h2>
        </div>
    </div>
    <?php
    $responseRates = $generator->getResponseRates($responses);
    ?>
    <?=\prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates['total']['total1'], 'part' => $responseRates['total']['responses'], 'view' => $this])?>
    <?php
    $graphWidth = 3;
    echo \prime\widgets\report\Columns::widget([
        'items' => [
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[1]['total1'], 'part' => $responseRates[1]['responses'], 'title' => Yii::t('ccpm', 'International NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[2]['total1'], 'part' => $responseRates[2]['responses'], 'title' => Yii::t('ccpm', 'National NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[3]['total1'], 'part' => $responseRates[3]['responses'], 'title' => Yii::t('ccpm', 'UN Agencies'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[4]['total1'], 'part' => $responseRates[4]['responses'], 'title' => Yii::t('ccpm', 'National Authorities'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[5]['total1'], 'part' => $responseRates[5]['responses'], 'title' => Yii::t('ccpm', 'Donors'), 'graphWidth' => $graphWidth, 'view' => $this]),
            \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[6]['total1'], 'part' => $responseRates[6]['responses'], 'title' => Yii::t('ccpm', 'Other'), 'graphWidth' => $graphWidth, 'view' => $this]),
        ],
        'columnsInRow' => 2
    ]);

    ?>
</div>

    <div class="container-fluid">
        <?=$this->render('header', ['project' => $project])?>
        <div class="row">
            <div class="col-xs-12">
                <h2><?=\Yii::t('ccpm', 'Effective response rate')?><span style="font-size: 0.5em; margin-left: 50px;">(<?=Yii::t('ccpm', 'Based on the average number of organizations participating to cluster meetings')?></span></h2>
            </div>
        </div>
        <?php
        $responseRates = $generator->getResponseRates($responses);
        ?>
        <?=\prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates['total']['total2'], 'part' => $responseRates['total']['responses'], 'view' => $this])?>
        <?php
        $graphWidth = 3;
        echo \prime\widgets\report\Columns::widget([
            'items' => [
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[1]['total2'], 'part' => $responseRates[1]['responses'], 'title' => Yii::t('ccpm', 'International NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[2]['total2'], 'part' => $responseRates[2]['responses'], 'title' => Yii::t('ccpm', 'National NGOs'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[3]['total2'], 'part' => $responseRates[3]['responses'], 'title' => Yii::t('ccpm', 'UN Agencies'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[4]['total2'], 'part' => $responseRates[4]['responses'], 'title' => Yii::t('ccpm', 'National Authorities'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[5]['total2'], 'part' => $responseRates[5]['responses'], 'title' => Yii::t('ccpm', 'Donors'), 'graphWidth' => $graphWidth, 'view' => $this]),
                \prime\widgets\report\GraphWithNumbers::widget(['total' => $responseRates[6]['total2'], 'part' => $responseRates[6]['responses'], 'title' => Yii::t('ccpm', 'Other'), 'graphWidth' => $graphWidth, 'view' => $this]),
            ],
            'columnsInRow' => 2
        ]);

        ?>
    </div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Overall Performance')?></h2>
    </div>
    <?php

    $performanceStatusBlockColumns = [
        'items' => [
            [
                'content' => \Yii::t('ccpm', 'Score') . '<hr>> 75 %<br>51 % - 75 %<br>26 % - 50 %<br>< 26 %',
                'width' => 6
            ],
            [
                'content' => \Yii::t('ccpm', 'Performance status') . '<hr><span class="text-good">' . \Yii::t('ccpm', 'Good') . '</span><br><span class="text-satisfactory">' . \Yii::t('ccpm', 'Satisfactory') . '</span><br><span class="text-unsatisfactory">' . \Yii::t('ccpm', 'Unsatisfactory') . '</span><br><span class="text-weak">' . \Yii::t('ccpm', 'Weak') . '</span>',
                'width' => 6
            ]
        ],
        'columnsInRow' => 12
    ];

    $performanceStatusBlock =
        '<div class="col-xs-12" style="border: 1px solid black; padding-top: 15px; padding-bottom: 15px;">' . \prime\widgets\report\Columns::widget($performanceStatusBlockColumns) . '</div>';

    echo \prime\widgets\report\Columns::widget([
        'items' => [
            [
                'content' => $performanceStatusBlock,
                'width' => 4
            ],
            [
                'content' => $this->render('performanceStatusTable', ['generator' => $generator, 'scores' => $scores]),
                'width' => 8
            ],
        ],
        'columnsInRow' => 12
    ]);
    ?>
</div>

<?=$this->render('functionsAndReview', ['generator' => $generator, 'scores' => $scores, 'project' => $project, 'userData' => $userData])?>
<?php /*=$this->render('distributionsAndComments', ['generator' => $generator, 'distributions' => $distributions, 'project' => $project, 'userData' => $userData, 'questionsAndAnswers' => $questionsAndAnswers], $this) */?>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Answer distributions and comments')?></h2>
    </div>

    <div class="row">
        <h3 class="col-xs-12">0 <?=\Yii::t('ccpm', 'General')?></h3>
    </div>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'General') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => [], $generator->PPASurveyId => ['q014']], function($value){return !empty($value);})
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">1 <?=\Yii::t('ccpm', 'Supporting service delivery')?></h3>
    </div>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '1.1',
        'title' => \Yii::t('ccpm', 'Provide a platform to ensure that service delivery is driven by the agreed strategic priorities'),
        'distributions' => [
            \Yii::t('ccpm', 'List of partners regularly updated') => $distributions['1.1.1'],
            \Yii::t('ccpm', 'Adequate frequency of cluster meetings') => $distributions['1.1.2'],
            \Yii::t('ccpm', 'Attendance of cluster partners to cluster meetings') => $distributions['1.1.3'],
            \Yii::t('ccpm', 'Level of decision making power of staff attending cluster meetings') => $distributions['1.1.4'],
            \Yii::t('ccpm', 'Conditions for optimal participation of national and international stakeholders') => $distributions['1.1.5'],
            \Yii::t('ccpm', 'Writing of minutes of cluster meetings with action points') => $distributions['1.1.6'],
            \Yii::t('ccpm', 'Usefulness of cluster meetings for discussing needs, gaps and priorities') => $distributions['1.1.7'],
            \Yii::t('ccpm', 'Useful strategic decision taken within the cluster') => $distributions['1.1.8'],
            \Yii::t('ccpm', 'Attendance of cluster coordinator to HCT and ICC meetings') => $distributions['1.1.9'],
            \Yii::t('ccpm', 'Support/engagement of cluster with national coordination mechanisms') => $distributions['1.1.10'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '1.2',
        'title' => \Yii::t('ccpm', 'Develop mechanisms to eliminate duplication of service delivery'),
        'distributions' => [
            \Yii::t('ccpm', 'Mapping of partner geographic presence and programme activities updated as needed') => $distributions['1.2.1'],
            \Yii::t('ccpm', 'Inputs of health partners into mapping of partner geographic presence and programme activities') => $distributions['1.2.2'],
            \Yii::t('ccpm', 'Involvement of partners into analysis of gaps and overlaps based on mapping') => $distributions['1.2.3'],
            \Yii::t('ccpm', 'Analysis of gaps and overlaps based on mapping used by partners for decision-making') => $distributions['1.2.4'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'Supporting service delivery') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q124'], $generator->PPASurveyId => ['q124']], function($value){return !empty($value);})
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">2 <?=\Yii::t('ccpm', 'Informing strategic decision-making of the Humanitarian Coordinator/Humanitarian Country Team')?></h3>
    </div>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '2.1',
        'title' => \Yii::t('ccpm', 'Needs assessment and gap analysis'),
        'distributions' => [
            \Yii::t('ccpm', 'Use of cluster agreed tools and guidance for needs assessments') => $distributions['2.1.1'],
            \Yii::t('ccpm', 'Involvement of partners in joint needs assessments') => $distributions['2.1.2'],
            \Yii::t('ccpm', 'Sharing by partners of their assessment reports') => $distributions['2.1.3'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '2.2',
        'title' => \Yii::t('ccpm', 'Analysis to identify and address (emerging) gaps, obstacles, duplication, and cross-cutting issues'),
        'distributions' => [
            \Yii::t('ccpm', 'Analyses of situations done together with cluster partners') => $distributions['2.2.1'],
            \Yii::t('ccpm', 'Analyses of situations identified risk') => $distributions['2.2.2'],
            \Yii::t('ccpm', 'Analyses of situations identified needs') => $distributions['2.2.3'],
            \Yii::t('ccpm', 'Analyses of situations identified gaps in response') => $distributions['2.2.4'],
            \Yii::t('ccpm', 'Analyses of situations identified capacity in response') => $distributions['2.2.5'],
            \Yii::t('ccpm', 'Analyses of situations identified constraints to respond') => $distributions['2.2.6'],
            \Yii::t('ccpm', 'Age (cross-cutting issue) considered in analyses') => $distributions['2.2.7'],
            \Yii::t('ccpm', 'Gender (cross-cutting issue) considered in analyses') => $distributions['2.2.8'],
            \Yii::t('ccpm', 'Diversity – other than age and gender- (cross-cutting issue) considered in analyses') => $distributions['2.2.9'],
            \Yii::t('ccpm', 'Human rights (cross-cutting issue) considered in analyses') => $distributions['2.2.10'],
            \Yii::t('ccpm', 'Protection, including gender-based violence (cross-cutting issue) considered in analyses') => $distributions['2.2.11'],
            \Yii::t('ccpm', 'Environment (cross-cutting issue) considered in analyses') => $distributions['2.2.12'],
            \Yii::t('ccpm', 'HIV/AIDS (cross-cutting issue) considered in analyses') => $distributions['2.2.13'],
            \Yii::t('ccpm', 'Disability (cross-cutting issue) considered in analyses') => $distributions['2.2.14'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '2.3',
        'title' => \Yii::t('ccpm', 'Prioritizing on the basis of response analysis'),
        'distributions' => [
            \Yii::t('ccpm', 'Joint analyses supporting response planning') => $distributions['2.3.1'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'Informing strategic decision-making of the Humanitarian Coordinator/Humanitarian Country Team') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q232'], $generator->PPASurveyId => ['q232']], function($value){return !empty($value);}),
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">3 <?=\Yii::t('ccpm', 'Planning and strategy development')?></h3>
    </div>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '3.1',
        'title' => \Yii::t('ccpm', 'Developing sectoral plans, objectives and indicators that directly support HC/HCT strategic priorities'),
        'distributions' => [
            \Yii::t('ccpm', 'Strategic plan developed') => $distributions['3.1.1'],
            \Yii::t('ccpm', 'Partners involved in the development of strategic plan') => $distributions['3.1.2'],
            \Yii::t('ccpm', 'Sectoral strategic plan includes objectives, activities and indicators') => $distributions['3.1.3'],
            \Yii::t('ccpm', 'Sectoral strategic plan reviewed against host government strategy') => $distributions['3.1.4'],
            \Yii::t('ccpm', 'Age (cross-cutting issue) considered in strategic plan') => $distributions['3.1.5'],
            \Yii::t('ccpm', 'Gender (cross-cutting issue) considered in strategic plan') => $distributions['3.1.6'],
            \Yii::t('ccpm', 'Diversity – other than age and gender- (cross-cutting issue) considered in strategic plan') => $distributions['3.1.7'],
            \Yii::t('ccpm', 'Human rights (cross-cutting issue) considered in strategic plan') => $distributions['3.1.8'],
            \Yii::t('ccpm', 'Protection, including gender-based violence (cross-cutting issue) considered in strategic plan') => $distributions['3.1.9'],
            \Yii::t('ccpm', 'Environment (cross-cutting issue) considered in strategic plan') => $distributions['3.1.10'],
            \Yii::t('ccpm', 'HIV/AIDS (cross-cutting issue) considered in strategic plan') => $distributions['3.1.11'],
            \Yii::t('ccpm', 'Disability (cross-cutting issue) considered in strategic plan') => $distributions['3.1.12'],
            \Yii::t('ccpm', 'Strategic plan shows synergies with other sectors') => $distributions['3.1.13'],
            \Yii::t('ccpm', 'Strategic plan used by partners for guiding response') => $distributions['3.1.14'],
            \Yii::t('ccpm', 'Deactivation criteria and phasing out strategy formulated together with partners') => $distributions['3.1.15'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '3.2',
        'title' => \Yii::t('ccpm', 'Adherence to and application of standards and guidelines'),
        'distributions' => [
            \Yii::t('ccpm', 'National and international standards and guidance identified and adapted as required') => $distributions['3.2.1'],
            \Yii::t('ccpm', 'Technical standards and guidance agreed upon and used by partners') => $distributions['3.2.2'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '3.3',
        'title' => \Yii::t('ccpm', 'Clarifying funding needs, prioritization, and cluster contributions to HC funding needs'),
        'distributions' => [
            \Yii::t('ccpm', 'Prioritization of proposals against the strategic plan jointly determined with partners based on agreed transparent criteria') => $distributions['3.3.1'],
            \Yii::t('ccpm', 'Prioritization of proposals against strategic plan fair to all partners') => $distributions['3.3.2'],
            \Yii::t('ccpm', 'Cluster supported and facilitated access to funding sources by partners') => $distributions['3.3.3'],
            \Yii::t('ccpm', 'Regular reporting on funding status') => $distributions['3.3.4'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'Planning and strategy development') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q335'], $generator->PPASurveyId => ['q334']], function($value){return !empty($value);}),
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">4 <?=\Yii::t('ccpm', 'Advocacy')?></h3>
    </div>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '4.1',
        'title' => \Yii::t('ccpm', 'Identifying advocacy concerns that contribute to HC and HCT messaging and action'),
        'distributions' => [
            \Yii::t('ccpm', 'Issues requiring advocacy identified and discussed together with partners') => $distributions['4.1.1']
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '4.2',
        'title' => \Yii::t('ccpm', 'Undertaking advocacy activities on behalf of cluster participants and affected people'),
        'distributions' => [
            \Yii::t('ccpm', 'Advocacy activities agreed upon and undertaken with partners') => $distributions['4.2.1'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'Advocacy') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q422'], $generator->PPASurveyId => ['q422']], function($value){return !empty($value);}),
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">5 <?=\Yii::t('ccpm', 'Monitoring and reporting on implementation of cluster strategy and results ')?></h3>
    </div>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '5.1',
        'title' => \Yii::t('ccpm', 'Monitoring and reporting on implementation of cluster strategy and results '),
        'distributions' => [
            \Yii::t('ccpm', 'Programme monitoring formats agreed upon and used by cluster partners') => $distributions['5.1.1'],
            \Yii::t('ccpm', 'Information shared by partners reflected in cluster reports') => $distributions['5.1.2'],
            \Yii::t('ccpm', 'Regular publication of progress reports based on agreed indicators for monitoring humanitarian response') => $distributions['5.1.3'],
            \Yii::t('ccpm', 'Regular publication of cluster bulletins') => $distributions['5.1.4'],
            \Yii::t('ccpm', 'Changes in needs, risk and gaps highlighted in cluster reports and used for decision-making by partners') => $distributions['5.1.5'],
            \Yii::t('ccpm', 'Response  and monitoring of the cluster taking into account the needs, contributions and capacities of women, girls, men and boys') => $distributions['5.1.6'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'Monitoring and reporting on implementation of cluster strategy and results') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q57'], $generator->PPASurveyId => ['q54']], function($value){return !empty($value);}),
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">6 <?=\Yii::t('ccpm', 'Preparedness for recurrent disasters')?></h3>
    </div>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '6.1',
        'title' => \Yii::t('ccpm', 'Preparedness for recurrent disasters'),
        'distributions' => [
            \Yii::t('ccpm', 'National contingency plans identified and shared') => $distributions['6.1.1'],
            \Yii::t('ccpm', 'Partners contributed to initial or updated risk assessments and analysis') => $distributions['6.1.2'],
            \Yii::t('ccpm', 'Partners involved in development of preparedness plan') => $distributions['6.1.3'],
            \Yii::t('ccpm', 'Partners committed staff and/or resources towards preparedness plan') => $distributions['6.1.4'],
            \Yii::t('ccpm', 'Early warning reports shared with partners') => $distributions['6.1.5']
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'Preparedness for recurrent disasters') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q66'], $generator->PPASurveyId => ['q63']], function($value){return !empty($value);}),
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">7 <?=\Yii::t('ccpm', 'Accountability to affected populations')?></h3>
    </div>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '7.1',
        'title' => \Yii::t('ccpm', 'Accountability to affected populations'),
        'distributions' => [
            \Yii::t('ccpm', 'Mechanisms to consult and involve population in decision-making agreed upon and applied by partners') => $distributions['7.1.1'],
            \Yii::t('ccpm', 'Mechanisms to  receive, investigate and act upon complaints about assistance received agreed upon and applied by partners') => $distributions['7.1.2'],
        ],
        'view' => $this,
        'questionsAndAnswers' => $questionsAndAnswers,
        'sectionQuestionMap' => $generator->sectionQuestionMapping(),
        'PPASurveyId' => $generator->PPASurveyId,
        'CPASurveyId' => $generator->CPASurveyId
    ])?>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'Accountability to affected populations') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q73'], $generator->PPASurveyId => ['q73']], function($value){return !empty($value);}),
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">8 <?=\Yii::t('ccpm', 'Others')?></h3>
    </div>

    <?=\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'Others') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q81'], $generator->PPASurveyId => ['q81']], function($value){return !empty($value);})
        ]
    ])?>

<!--    <div class="row">-->
<!--        <h2 class="col-xs-12">--><?//=\Yii::t('ccpm', 'Comments')?><!--</h2>-->
<!--    </div>-->

    <?php /*\prime\widgets\report\Comments::widget([
        'comments' => [
            \Yii::t('ccpm', 'General') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => [], $generator->PPASurveyId => ['q014']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Supporting service delivery') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q124'], $generator->PPASurveyId => ['q124']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Informing strategic decision-making of the Humanitarian Coordinator/Humanitarian Country Team') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q232'], $generator->PPASurveyId => ['q232']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Planning and strategy development') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q335'], $generator->PPASurveyId => ['q334']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Advocacy') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q422'], $generator->PPASurveyId => ['q422']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Monitoring and reporting on implementation of cluster strategy and results') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q57'], $generator->PPASurveyId => ['q54']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Preparedness for recurrent disasters') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q66'], $generator->PPASurveyId => ['q63']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Accountability to affected populations') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q73'], $generator->PPASurveyId => ['q73']], function($value){return !empty($value);}),
            \Yii::t('ccpm', 'Others') => $generator->getQuestionValues($responses, [$generator->CPASurveyId => ['q81'], $generator->PPASurveyId => ['q81']], function($value){return !empty($value);})
        ]
    ])*/?>
</div>
<?php $this->endContent(); ?>