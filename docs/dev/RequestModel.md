# Request Models

Request models model the data received in a request. They are often called form models because they are used in web forms.
Yii2 supports model validation on both the client and the server side.

To easily use this form validation while at the same time keeping our request models separated from our database models
we have designed some additional helpers and practices.



## Rules
- All properties must have explicit type hint `mixed`
- All properties must have a default value
- They must extend `RequestModel`
- They must be `final` by default

## Action flow
If the purpose of the action is to create a record in the database, there are 2 hydration phases.
The process roughly has the following steps:
1. Authentication (outside action)
2. Authorization (inside action)
3. Hydrating the `RequestModel`
4. Validation
5. Hydrating the `ActiveRecord`
6. Saving the `ActiveRecord`


### Hydrating the `RequestModel`
Hydration is the process of filling a model's properties with data. The source of hydration can be a different model or
data from the request.

In Yii2 there is a default implementation for hydration in the form of `Model::load()` which can read data from the request
object and put it in the safe properties. For a simple `RequestModel` this might work, but because we want to take advantage
of strong typing this is not the best solution.
Instead inject the `ModelHydrator` and use its `hydrateFromJsonDictionary` and similar functions to load your model. 
Advantage of this hydrator is that it can intelligently cast complex data types like enums. If any of the hydration fails
the error is added to the attribute as a validation error.

### Hydrating the `ActiveRecord`
This may require some translation between how the API or UI exposes information and how we internally store it. This
disconnect is intentional. Changes in internal structure should not change the API and changes to the API should not 
require internal structure changes.

For hydrating the `ActiveRecord` we have created `ActiveRecordHydratorInterface`:
```php
interface ActiveRecordHydratorInterface
{
    public function hydrateActiveRecord(Model $source, ActiveRecord $target): void;
}
```

This will go over a `RequestModel` and hydrate the target `ActiveRecord` instance.
By default some autodetection is done so that attributes are properly typed, but in some cases customization may be needed.
Customization is done in the `RequestModel` by using PHP8 attributes.

Currently we have implemented 2 attributes:
```php
final class SomeSpecificRequest extends RequestModel
{
    /**
     * The field attribute will make our hydrator put the `pageId` field from the model into
     * the `page_id` field from the database record  
     */
    #[Field('page_id')]
    public mixed $pageId = null;

    public mixed $width = 1;

    /**
     * The field attribute will make our hydrator put the `height` field from the model into
     * the `config` JSON field from the database record under the key 'height'  
     */
    #[JsonField('config')]    
    public mixed $height = 15;
    
    /**
     * Both attributes may be combined
     */
    #[JsonField('config'), Field('size')]    
    public mixed $height = 15;
}
```
