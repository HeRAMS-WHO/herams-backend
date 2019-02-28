<?php


namespace prime\widgets\usermenu;


use prime\widgets\BaseWidget;

class UserMenu extends BaseWidget
{

    public function runInternal()
    {
        $css = <<<CSS
.UserMenu {
    padding: 1em;
    background-color: var(--header-background-color);
    grid-area: user;
    display: flex;
    align-items: center;
    flex-direction: row-reverse;
    text-align: right;
    color: var(--header-color);
}

.UserMenu .name{
    text-transform: uppercase;
    font-weight: 700;
    font-size: 0.9rem;
}
.UserMenu .email {
    /*color: #b3b1b8;*/
    font-weight: 600;
    font-size: 0.7rem;
}
.UserMenu > * {
    margin-left: 5px;
}
CSS;
        $this->view->registerCss($css);

        echo
        <<<HTML
    <i class="fas fa-chevron-down"></i>
    <img src="/img/Profile_black.png">
    <div>
        <div class="name">Sam Mousa</div>
        <div class="email">sam@mousa.nl</div>
    </div>
HTML;
    }


}