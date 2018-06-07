<?php
    /** @var View $this */

use yii\web\View;

    $this->registerCssFile("https://use.fontawesome.com/releases/v5.0.9/css/all.css");
    $this->registerCssFile("https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css");
    $this->registerCssFile("https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css");
    $this->registerCssFile("styles/vendor.css");
    $this->registerCssFile("styles/main.css");
    $accessToken = json_encode(Yii::$app->user->identity->access_token);
    $this->registerJs(<<<JS
        
        var tokenConfig = $accessToken;
        
JS
    , View::POS_HEAD

    );
?>
<body ng-app=app-herams> <!--[if lt IE 7]>
 <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
 <![endif]--> 
<div class=mainWrapper>
    <div ng-controller=MainCtrl class=overview ng-init=init()>
        <div class=menu ng-cloak>
            <div class=backhome>
                <a ng-click=home()><i class="fas fa-chevron-left"></i><span class=highlight>Back</span> to HeRAMS</a>
            </div>
            <div class=Logo> <a ng-click=home()><img src=img/HeRAMS_NGA.svg></a>
            </div>
            <div class=menu-items>
                <ul ng-cloak> <li ng-repeat="category in categories">
                    <div class=item ng-class="{'selected': category.id==catIDSelect[0], 'greyed-out': isDisabled(category) }">
                        <span ng-click=launchLayout(category)>{{category.name}}</span> <span class=item-open-close ng-if="category.subcategories!=null" ng-click=launchLayout(category)>
                            <img src=img/menu_open.svg ng-if="category.id!=catMenuON[0]"> <img src=img/menu_close.svg ng-if="category.id==catMenuON[0]">
                        </span>
                    </div>
                <div class="sub-categories parent-category-id-{{category.id}}" ng-if="category.subcategories!=null" ng-show="category.id==catMenuON[0]">
                    <ul ng-cloak> <li ng-repeat="subcat in category.subcategories">
                            <div ng-class="{'selected': subcat.id==catIDSelect[1], 'greyed-out': isDisabled(subcat) }">
                                <span ng-click=launchLayout(subcat,1)>{{subcat.name}}</span> <span class=item-open-close ng-if="subcat.subcategories!=null" ng-click=launchLayout(subcat,1)>
                                    <img src=img/menu_open.svg ng-if="subcat.id!=catMenuON[1]"> <img src=img/menu_close.svg ng-if="subcat.id==catMenuON[1]">
                                </span> </div>
                            <div class="sub-categories sub-categories-lev2 parent-category-id-{{subcat.id}}" ng-if="subcat.subcategories!=null" ng-show="subcat.id==catMenuON[1]">
                                <ul ng-cloak> <li ng-repeat="subsubcat in subcat.subcategories"> <div ng-class="{'selected': subsubcat.id==catIDSelect[2], 'greyed-out': isDisabled(subsubcat) }"> <span ng-click=launchLayout(subsubcat,2)>{{subsubcat.name}}</span> </div> </li> </ul> </div> </li> </ul> </div> </li> </ul> </div> <div class=partners-list-btn>Partners</div> </div> <div class=main> <div class=background-top></div> <div class="content popover-base"> <div class="header container-fluid"> <div class=row> <div class="col-sm-6 col-lg-8"> <div class=breadcrumbs>{{ setBreadcrumbs() }}</div> <div class=title ng-cloak><span>{{ catNameSelect[0] }}</span></div> </div> <div class="col-sm-6 col-lg-4"> <div class=user-profile> <div> <div class=usrname ng-cloak>{{ usr_name }}</div> <div class=email ng-cloak>{{ usr_email }}</div> <div class=org><span>who</span></div> </div> <div>  <img src=img/Profile_white.png> <i class="fas fa-angle-down" id=log></i> </div> </div> </div> </div> <div class=row> <div class="col-12 filters"> <div class=filters-groups> <div class=filter-location> <dropdown icon=img/filter_where.svg value=Nigeria type=location ng-cloak> </dropdown></div> <div class=filter-calendar> <datepicker date=date></datepicker> </div> <div class=filter-HF> <dropdown icon=img/filters/HF.png value=nc type=hf> </dropdown></div> <div class=filter-advanced> <button type=button class="btn btn-primary" data-toggle=modal data-target=#advncdSrchModal> Advanced filters </button> </div> <div class=filter-advanced-view> <button type=button class="btn btn-primary" data-toggle=modal data-target=#advncdViewModal> View advanced filters ({{getAdvancedFiltersCnt()}}) </button> </div> <div class=filter-btns> <button type=button class="btn btn-secondary" ng-click=clearMainFilters()><i class="fas fa-times"></i>Clear</button> <button type=button class="btn btn-secondary" ng-click=applyFilters()><i class="fas fa-check"></i>Apply</button> </div> </div> <div></div> </div> </div> </div> <div class=main-content ng-cloak></div> <div class=global-filters-popovers> <div class="filters-popover filter-location"> <filters-popover id=popover-location-1 open-next=#popover-location-2 title=State items=states type=location grouped=true ng-cloak></filters-popover> <filters-popover id=popover-location-2 title=LGA items type=location grouped=true ng-cloak></filters-popover> </div> <div class="filters-popover filter-HF"> <filters-popover title=All items=hftypes type=hf grouped=false></filters-popover> </div>  </div>  <div class="modal fade" id=advncdSrchModal tabindex=-1 role=dialog aria-labelledby=exampleModalLabel aria-hidden=true> <div class="modal-dialog modal-dialog-centered" role=document> <div class=modal-content> <div class=modal-body> <advanced-search data=LSQuestions></advanced-search> </div> <div class=modal-footer> <div class=advanced-filters-cnt>Total: {{getAdvancedFiltersCnt()}} filters</div> <button type=button class="btn btn-secondary" data-dismiss=modal ng-click=clearSetFilters()><i class="fas fa-times"></i>Clear</button> <button type=button class="btn btn-secondary" data-dismiss=modal ng-click=applyFilters()><i class="fas fa-check"></i>Apply</button> </div> </div> </div> </div> <div class="modal fade" id=advncdViewModal tabindex=-1 role=dialog aria-labelledby=exampleModalLabel aria-hidden=true> <div class="modal-dialog modal-dialog-centered" role=document> <div class=modal-content> <div class=modal-header> <h5 class=modal-title id=exampleModalLabel>{{getAdvancedFiltersCnt()}} Advanced filters</h5> <button type=button class=close data-dismiss=modal aria-label=Close> <span aria-hidden=true>&times;</span> </button> </div> <div class=modal-body> <advanced-filters-list></advanced-filters-list> </div> <div class=modal-footer></div> </div> </div> </div> </div> </div> </div> <div class=loading><img src=img/spinner.gif></div> </div> <div class=partners-list-grp> <div class=partners-list-cache></div> <div class=partners-list> <div><img src=img/partners/AAH.jpg></div> <div><img src=img/partners/ALIMA.jpg></div> <div><img src=img/partners/BornoState.jpg></div> <div><img src=img/partners/fhi360.jpg></div> <div><img src=img/partners/IntlMedicalCorps.jpg></div> <div><img src=img/partners/IOM.jpg></div> <div><img src=img/partners/MDM.jpg></div> <div><img src=img/partners/MSF.jpg></div> <div><img src=img/partners/Nigeria.jpg></div> <div><img src=img/partners/UNFPA.jpg></div> <div><img src=img/partners/UNICEF.jpg></div> <div><img src=img/partners/WHO.jpg></div> </div> </div> <div id=popover-content class=hidden-popover> <div class=log-popover> <ul> <li ng-click=commonSvc.showUsrProfile()>Profile</li> <li ng-click=commonSvc.logout()>Logout</li> </ul> </div> </div>

<script src=https://code.jquery.com/jquery-3.2.1.slim.min.js integrity=sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN crossorigin=anonymous></script>
<script src=https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js integrity=sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q crossorigin=anonymous></script>
<script src=https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js></script>
<script src=https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js></script>
<script src=scripts/vendor.js></script>
<script src=config/config_dev.385dfb3d.js></script>
<script src=scripts/scripts_wkspace.js></script>  <script src=https://code.highcharts.com/modules/exporting.js></script>
<script src=https://code.highcharts.com/modules/offline-exporting.js></script>
