<?php

use app\components\Html;

/**
 * @var \prime\reportGenerators\ccpm\Generator $generator
 * @var \prime\interfaces\UserDataInterface $userData
 */

?>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <!--BEGIN Only on first page of function...-->
    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Performance per function and review')?></h2>
    </div>
    <!--END Only on first page of function...-->

    <div class="row">
        <h3 class="col-xs-12">1 <?=\Yii::t('ccpm', 'Supporting service delivery')?></h3>
    </div>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '1.1',
        'score' => $generator->mapStatus($scores['1.1']),
        'title' => \Yii::t('ccpm', 'Provide a platform to ensure that service delivery is driven by the agreed strategic priorities'),
        'scores' => [
            \Yii::t('ccpm', 'List of partners regularly updated') => $scores['1.1.1'],
            \Yii::t('ccpm', 'Adequate frequency of cluster meetings') => $scores['1.1.2'],
            \Yii::t('ccpm', 'Attendance of cluster partners to cluster meetings') => $scores['1.1.3'],
            \Yii::t('ccpm', 'Level of decision making power of staff attending cluster meetings') => $scores['1.1.4'],
            \Yii::t('ccpm', 'Conditions for optimal participation of national and international stakeholders') => $scores['1.1.5'],
            \Yii::t('ccpm', 'Writing of minutes of cluster meetings with action points') => $scores['1.1.6'],
            \Yii::t('ccpm', 'Usefulness of cluster meetings for discussing needs, gaps and priorities') => $scores['1.1.7'],
            \Yii::t('ccpm', 'Useful strategic decision taken within the cluster') => $scores['1.1.8'],
            \Yii::t('ccpm', 'Attendance of cluster coordinator to HCT and ICC meetings') => $scores['1.1.9'],
            \Yii::t('ccpm', 'Support/engagement of cluster with national coordination mechanisms') => $scores['1.1.10'],
//            \Yii::t('ccpm', '') => $scores[''],
//            \Yii::t('ccpm', '') => $scores[''],
//            \Yii::t('ccpm', '') => $scores[''],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Established, relevant coordination mechanism recognising national systems, subnational and co-lead aspects; stakeholders participating regularly and effectively; cluster coordinator active in inter-cluster and related meetings.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_1_1_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_1_1_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '1.2',
        'score' => $generator->mapStatus($scores['1.2']),
        'title' => \Yii::t('ccpm', 'Develop mechanisms to eliminate duplication of service delivery'),
        'scores' => [
            \Yii::t('ccpm', 'Mapping of partner geographic presence and programme activities updated as needed') => $scores['1.2.1'],
            \Yii::t('ccpm', 'Inputs of health partners into mapping of partner geographic presence and programme activities') => $scores['1.2.2'],
            \Yii::t('ccpm', 'Involvement of partners into analysis of gaps and overlaps based on mapping') => $scores['1.2.3'],
            \Yii::t('ccpm', 'Analysis of gaps and overlaps based on mapping used by partners for decision-making') => $scores['1.2.4'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Cluster partner engagement in dynamic mapping of presence and capacity (4W); information sharing across clusters in line with joint Strategic Objectives.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_1_2_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_1_2_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">2 <?=\Yii::t('ccpm', 'Informing strategic decision-making of the Humanitarian Coordinator/Humanitarian Country Team')?></h3>
    </div>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '2.1',
        'score' => $generator->mapStatus($scores['2.1']),
        'title' => \Yii::t('ccpm', 'Needs assessment and gap analysis'),
        'scores' => [
            \Yii::t('ccpm', 'Use of cluster agreed tools and guidance for needs assessments') => $scores['2.1.1'],
            \Yii::t('ccpm', 'Involvement of partners in joint needs assessments') => $scores['2.1.2'],
            \Yii::t('ccpm', 'Sharing by partners of their assessment reports') => $scores['2.1.3'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Use of assessment tools in accordance with agreed minimum standards, individual assessment / survey results shared and/or carried out jointly as appropriate.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_2_1_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_2_1_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '2.2',
        'score' => $generator->mapStatus($scores['2.2']),
        'title' => \Yii::t('ccpm', 'Analysis to identify and address (emerging) gaps, obstacles, duplication, and cross-cutting issues'),
        'scores' => [
            \Yii::t('ccpm', 'Analyses of situations done together with cluster partners') => $scores['2.2.1'],
            \Yii::t('ccpm', 'Analyses of situations identified risk') => $scores['2.2.2'],
            \Yii::t('ccpm', 'Analyses of situations identified needs') => $scores['2.2.3'],
            \Yii::t('ccpm', 'Analyses of situations identified gaps in response') => $scores['2.2.4'],
            \Yii::t('ccpm', 'Analyses of situations identified capacity in response') => $scores['2.2.5'],
            \Yii::t('ccpm', 'Analyses of situations identified constraints to respond') => $scores['2.2.6'],
            \Yii::t('ccpm', 'Age (cross-cutting issue) considered in analyses') => $scores['2.2.7'],
            \Yii::t('ccpm', 'Gender (cross-cutting issue) considered in analyses') => $scores['2.2.8'],
            \Yii::t('ccpm', 'Diversity – other than age and gender- (cross-cutting issue) considered in analyses') => $scores['2.2.9'],
            \Yii::t('ccpm', 'Human rights (cross-cutting issue) considered in analyses') => $scores['2.2.10'],
            \Yii::t('ccpm', 'Protection, including gender-based violence (cross-cutting issue) considered in analyses') => $scores['2.2.11'],
            \Yii::t('ccpm', 'Environment (cross-cutting issue) considered in analyses') => $scores['2.2.12'],
            \Yii::t('ccpm', 'HIV/AIDS (cross-cutting issue) considered in analyses') => $scores['2.2.13'],
            \Yii::t('ccpm', 'Disability (cross-cutting issue) considered in analyses') => $scores['2.2.14'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Joint analysis for current and anticipated risks, needs, gaps and constraints; cross cutting issues addressed from outset.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_2_2_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_2_2_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '2.3',
        'score' => $generator->mapStatus($scores['2.3']),
        'title' => \Yii::t('ccpm', 'Prioritizing on the basis of response analysis'),
        'scores' => [
            \Yii::t('ccpm', 'Joint analyses supporting response planning') => $scores['2.3.1'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Joint analysis supporting response planning and prioritisation in short and medium term.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_2_3_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_2_3_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">3 <?=\Yii::t('ccpm', 'Planning and strategy development')?></h3>
    </div>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '3.1',
        'score' => $generator->mapStatus($scores['3.1']),
        'title' => \Yii::t('ccpm', 'Developing sectoral plans, objectives and indicators that directly support HC/HCT strategic priorities'),
        'scores' => [
            \Yii::t('ccpm', 'Strategic plan developed') => $scores['3.1.1'],
            \Yii::t('ccpm', 'Partners involved in the development of strategic plan') => $scores['3.1.2'],
            \Yii::t('ccpm', 'Sectoral strategic plan includes objectives, activities and indicators') => $scores['3.1.3'],
            \Yii::t('ccpm', 'Sectoral strategic plan reviewed against host government strategy') => $scores['3.1.4'],
            \Yii::t('ccpm', 'Age (cross-cutting issue) considered in strategic plan') => $scores['3.1.5'],
            \Yii::t('ccpm', 'Gender (cross-cutting issue) considered in strategic plan') => $scores['3.1.6'],
            \Yii::t('ccpm', 'Diversity – other than age and gender- (cross-cutting issue) considered in strategic plan') => $scores['3.1.7'],
            \Yii::t('ccpm', 'Human rights (cross-cutting issue) considered in strategic plan') => $scores['3.1.8'],
            \Yii::t('ccpm', 'Protection, including gender-based violence (cross-cutting issue) considered in strategic plan') => $scores['3.1.9'],
            \Yii::t('ccpm', 'Environment (cross-cutting issue) considered in strategic plan') => $scores['3.1.10'],
            \Yii::t('ccpm', 'HIV/AIDS (cross-cutting issue) considered in strategic plan') => $scores['3.1.11'],
            \Yii::t('ccpm', 'Disability (cross-cutting issue) considered in strategic plan') => $scores['3.1.12'],
            \Yii::t('ccpm', 'Strategic plan shows synergies with other sectors') => $scores['3.1.13'],
            \Yii::t('ccpm', 'Strategic plan used by partners for guiding response') => $scores['3.1.14'],
            \Yii::t('ccpm', 'Deactivation criteria and phasing out strategy formulated together with partners') => $scores['3.1.15'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Strategic plan based on identified priorities, shows synergies with other sectors against strategic objectives, addresses cross cutting issues, incorporates exit strategy discussion and is developed jointly with partners. Plan is updated regularly and guides response.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_3_1_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_3_1_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '3.2',
        'score' => $generator->mapStatus($scores['3.2']),
        'title' => \Yii::t('ccpm', 'Adherence to and application of standards and guidelines'),
        'scores' => [
            \Yii::t('ccpm', 'National and international standards and guidance identified and adapted as required') => $scores['3.2.1'],
            \Yii::t('ccpm', 'Technical standards and guidance agreed upon and used by partners') => $scores['3.2.2'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Use of existing national standards and guidelines where possible. Standards and guidance are agreed to, adhered to and reported against.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_3_2_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_3_2_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '3.3',
        'score' => $generator->mapStatus($scores['3.3']),
        'title' => \Yii::t('ccpm', 'Clarifying funding needs, prioritization, and cluster contributions to HC funding needs'),
        'scores' => [
            \Yii::t('ccpm', 'Prioritization of proposals against the strategic plan jointly determined with partners based on agreed transparent criteria') => $scores['3.3.1'],
            \Yii::t('ccpm', 'Prioritization of proposals against strategic plan fair to all partners') => $scores['3.3.2'],
            \Yii::t('ccpm', 'Cluster supported and facilitated access to funding sources by partners') => $scores['3.3.3'],
            \Yii::t('ccpm', 'Regular reporting on funding status') => $scores['3.3.4'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Funding requirements determined with partners, allocation under jointly agreed criteria and prioritisation, status tracked and information shared.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_3_3_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_3_3_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">4 <?=\Yii::t('ccpm', 'Advocacy')?></h3>
    </div>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '4.1',
        'score' => $generator->mapStatus($scores['4.1']),
        'title' => \Yii::t('ccpm', 'Identifying advocacy concerns that contribute to HC and HCT messaging and action'),
        'scores' => [
            \Yii::t('ccpm', 'Issues requiring advocacy identified and discussed together with partners') => $scores['4.1.1']
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Concerns for advocacy identified with partners, including gaps, access, resource needs.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_4_1_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_4_1_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '4.2',
        'score' => $generator->mapStatus($scores['4.2']),
        'title' => \Yii::t('ccpm', 'Undertaking advocacy activities on behalf of cluster participants and affected people'),
        'scores' => [
            \Yii::t('ccpm', 'Advocacy activities agreed upon and undertaken with partners') => $scores['4.2.1'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Common advocacy campaign agreed and delivered across partners.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_4_2_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_4_2_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">5 <?=\Yii::t('ccpm', 'Monitoring and reporting on implementation of cluster strategy and results ')?></h3>
    </div>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '',
        'score' => $generator->mapStatus($scores['5']),
        'title' => \Yii::t('ccpm', 'Monitoring and reporting on implementation of cluster strategy and results '),
        'scores' => [
            \Yii::t('ccpm', 'Programme monitoring formats agreed upon and used by cluster partners') => $scores['5.1.1'],
            \Yii::t('ccpm', 'Information shared by partners reflected in cluster reports') => $scores['5.1.2'],
            \Yii::t('ccpm', 'Regular publication of progress reports based on agreed indicators for monitoring humanitarian response') => $scores['5.1.3'],
            \Yii::t('ccpm', 'Regular publication of cluster bulletins') => $scores['5.1.4'],
            \Yii::t('ccpm', 'Changes in needs, risk and gaps highlighted in cluster reports and used for decision-making by partners') => $scores['5.1.5'],
            \Yii::t('ccpm', 'Response  and monitoring of the cluster taking into account the needs, contributions and capacities of women, girls, men and boys') => $scores['5.1.6'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Use of monitoring tools in accordance with agreed minimum standards, regular report sharing, progress mapped against agreed strategic plan, any necessary corrections identified.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_5_1_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_5_1_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">6 <?=\Yii::t('ccpm', 'Preparedness for recurrent disasters')?></h3>
    </div>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '',
        'score' => $generator->mapStatus($scores['6']),
        'title' => \Yii::t('ccpm', 'Preparedness for recurrent disasters'),
        'scores' => [
            \Yii::t('ccpm', 'National contingency plans identified and shared') => $scores['6.1.1'],
            \Yii::t('ccpm', 'Partners contributed to initial or updated risk assessments and analysis') => $scores['6.1.2'],
            \Yii::t('ccpm', 'Partners involved in development of preparedness plan') => $scores['6.1.3'],
            \Yii::t('ccpm', 'Partners committed staff and/or resources towards preparedness plan') => $scores['6.1.4'],
            \Yii::t('ccpm', 'Early warning reports shared with partners') => $scores['6.1.5']
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'National contingency plans identified and shared; risk assessment and analysis carried out, multisectoral where appropriate; readiness status enhanced; regular distribution of early warning reports.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_6_1_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_6_1_3', ['maxlength' => 600])
        ]
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h3 class="col-xs-12">7 <?=\Yii::t('ccpm', 'Accountability to affected populations')?></h3>
    </div>

    <?=\prime\widgets\report\FunctionAndReview::widget([
        'number' => '',
        'score' => $generator->mapStatus($scores['7']),
        'title' => \Yii::t('ccpm', 'Accountability to affected populations'),
        'scores' => [
            \Yii::t('ccpm', 'Mechanisms to consult and involve population in decision-making agreed upon and applied by partners') => $scores['7.1.1'],
            \Yii::t('ccpm', 'Mechanisms to  receive, investigate and act upon complaints about assistance received agreed upon and applied by partners') => $scores['7.1.2'],
        ],
        'notes' => [
            \Yii::t('ccpm', 'Indicative characteristics of functions') => \Yii::t('ccpm', 'Accountability to affected population; agencies have investigated and, as appropriate, acted upon feedback received about the assistance provided.'),
            \Yii::t('ccpm', 'Constraints, unexpected circumstances and/or success factors and/or good practice identified') => $generator->textarea($userData, 'functions_7_1_2', ['maxlength' => 600]),
            \Yii::t('ccpm', 'Follow-up actions, with timeline and/or support required (when status is orange or red)') => $generator->textarea($userData, 'functions_7_1_3', ['maxlength' => 600])
        ]
    ])?>
</div>