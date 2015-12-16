<?php

use app\components\Html;

/**
 * @var \prime\reportGenerators\ccpm\Generator $generator
 * @var \prime\interfaces\UserDataInterface $userData
 */

$view = $this->context;

?>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <div class="row">
        <h2 class="col-xs-12"><?=\Yii::t('ccpm', 'Answer distributions')?></h2>
    </div>
    <p><?=\Yii::t('ccpm', 'Black line is the Coordinator response, blue line is the partner response distribution.')?></p>
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
        'view' => $view
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '1.2',
        'title' => \Yii::t('ccpm', 'Develop mechanisms to eliminate duplication of service delivery'),
        'distributions' => [
            \Yii::t('ccpm', 'Mapping of partner geographic presence and programme activities updated as needed') => $distributions['1.2.1'],
            \Yii::t('ccpm', 'Inputs of health partners into mapping of partner geographic presence and programme activities') => $distributions['1.2.2'],
            \Yii::t('ccpm', 'Involvement of partners into analysis of gaps and overlaps based on mapping') => $distributions['1.2.3'],
            \Yii::t('ccpm', 'Analysis of gaps and overlaps based on mapping used by partners for decision-making') => $distributions['1.2.4'],
        ],
        'view' => $view
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
        'view' => $view
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

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
        'view' => $view
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '2.3',
        'title' => \Yii::t('ccpm', 'Prioritizing on the basis of response analysis'),
        'distributions' => [
            \Yii::t('ccpm', 'Joint analyses supporting response planning') => $distributions['2.3.1'],
        ],
        'view' => $view
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
        'view' => $view
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '3.2',
        'title' => \Yii::t('ccpm', 'Adherence to and application of standards and guidelines'),
        'distributions' => [
            \Yii::t('ccpm', 'National and international standards and guidance identified and adapted as required') => $distributions['3.2.1'],
            \Yii::t('ccpm', 'Technical standards and guidance agreed upon and used by partners') => $distributions['3.2.2'],
        ],
        'view' => $view
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '3.3',
        'title' => \Yii::t('ccpm', 'Clarifying funding needs, prioritization, and cluster contributions to HC funding needs'),
        'distributions' => [
            \Yii::t('ccpm', 'Prioritization of proposals against the strategic plan jointly determined with partners based on agreed transparent criteria') => $distributions['3.3.1'],
            \Yii::t('ccpm', 'Prioritization of proposals against strategic plan fair to all partners') => $distributions['3.3.2'],
            \Yii::t('ccpm', 'Cluster supported and facilitated access to funding sources by partners') => $distributions['3.3.3'],
            \Yii::t('ccpm', 'Regular reporting on funding status') => $distributions['3.3.4'],
        ],
        'view' => $view
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
        'view' => $view
    ])?>
</div>

<div class="container-fluid">
    <?=$this->render('header', ['project' => $project])?>

    <?=\prime\widgets\report\Distributions::widget([
        'number' => '4.2',
        'title' => \Yii::t('ccpm', 'Undertaking advocacy activities on behalf of cluster participants and affected people'),
        'distributions' => [
            \Yii::t('ccpm', 'Advocacy activities agreed upon and undertaken with partners') => $distributions['4.2.1'],
        ],
        'view' => $view
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
        'view' => $view
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
        'view' => $view
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
        'view' => $view
    ])?>
</div>