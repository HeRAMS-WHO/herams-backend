# Breadcrumbs
To improve the way we work with breadcrumbs and be able to do static analysis, objects are created to support these requirements. 

## Breadcrumbs collection
The `BreadcrumbCollection` is the basic container to hold all the breadcrumbs. It implements the Iterator interface to enable simple looping over it to render the breadcrumbs.

By default, a `BreadcrumbCollection` has been added to the `View` component.

## `BreadcrumbInterface`
The basics required to work with breadcrumbs is the `BreadcrumbInterface`.

### `Breacrumb` object
In some cases it is required to create just a breadcrumb, in that case the `Breadcrumb` object can be used. Example: the list page of projects.

### Implementation of the `BreadcrumbInterface`
In most cases the same object is used as breadcrumb at multiple occurrences. For example a project in the path of updating the project itself or a project as a parent for a workspace.

In these cases it is better to create an object that implements the `BreadcrumbInterface` and is directly returned by the repository. Example: `ProjectRepository::retrieveForBreadcrumb()`.

## Adding a breadcrumb
Adding breadcrumbs to the collection can theoretically be done throughout most of the request cycle (from instantiation of the `View` until rendering).
To standardize the way we work with breadcrumbs we want to preferably use two moments: in the controller or in the action. 

*If adding breadcrumbs from multiple locations, take the execution order into account since this determines the rendering order.* 

### In `Controller`
The preferred location is the `render` method. Before returning `parent::render()`, breadcrumbs can be added.
Example:
```php
public function render($view, $params = [])
{
    $this->view->getBreadcrumbCollection()->add(
        (new Breadcrumb())
            ->setUrl(['/project/index'])
            ->setLabel(\Yii::t('app', 'Projects'))
    );

    return parent::render($view, $params);
}
```

### In `Action`
The same can be done in the action for more specific cases.

### Not in the view file
The goal is for views to know as little as possible about the layout, so adding breadcrumbs should be done as much 
as possible at places where knowledge of the layout is present, for example the controller.

## Displaying breadcrumbs
Displaying breadcrumbs is as easy as looping over the `BreadcrumbCollection` and displaying as desired.
Currently we use the Breadcrumbs widget of Yii. Example: `/protected/views/layouts/admin-screen.php`.
