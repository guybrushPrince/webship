# WebShip

*WebShip* is a PHP library to easily create RESTful web applications without producing code overhead for serialization 
and deserialization of objects, linking objects to views, writing JavaScript, etc.

*WebShip* is a specialization of the basic *Ship* PHP library and *Ship*'s common use case for HTML, JS, and CSS 
web applications. Furthermore, *WebShip* provides many common ready-to-use views that can be, if desired, changed
by own CSS code or own implementations of views.


## How to use?

### Create a view.

Most views of *WebShip* have a static *create* function that returns a new instance of the view desired to create. 
Sometimes, especially for older views, the *constructor* of the view must be used to create an instance of the view. 
However, we are confident to replace those views soon.

Each view in *WebShip* is represented by an object of the specific ```webship\core\HtmlView``` class.
Thus, to create a view, you just use the instantiation method of the view (in the following example, the static 
*create* method):

```php
use webship\menus\ControlPanelHtmlView;
$controlPanel = ControlPanelHtmlView::create(3, 2);
```

A ```webship\menus\ControlPanelHtmlView``` is a small menu consisting of buttons. 
The above code creates such a view (without buttons) in the fixed size of ```3 x 2``` (3 lines and 2 rows).

Each view class provides specific methods to extend or modify the view.
Common methods that all views inherit are:
- ```add(View[]|View $view)``` to add a subview,
- ```view(string[]|string $classes)``` to append a subview of the basic class ```webship\core\HtmlView```, and
- ```container(string[]|string $classes)``` to append a subview of the class ```webship\containers\ContainerHtmlView```.

There are other standard methods being provided but being unnecessary for this short introduction.

For the above example, we can state that the control panel shall be located fix in the surrounding view.
Furthermore, we define a subarea of 2 lines and 1 row:

```php
use webship\menus\ControlPanelHtmlView;
$controlPanel = ControlPanelHtmlView::create(3, 2)->fix(); // fix() locates the view in the top right corner.
$elementsArea = $controlPanel->area(2, 1);
```

Although not recommended in most cases, you can create HTML, CSS, and JS code out of each view.
You have just to call the method ```generate()```:

```php
use webship\menus\ControlPanelHtmlView;
$controlPanel = ControlPanelHtmlView::create(3, 2)->fix(); // fix() locates the view in the top right corner.
$elementsArea = $controlPanel->area(2, 1);

echo $controlPanel->generate();
```

For this example, the produced code is:

```html
<div class="ctr wb fix tr " style="--ws-ctr-cols: 3;--ws-ctr-rows: 2;--ws-ctr-top-dist: 0;">
    <div class="ctr area wb " style="--ws-ctr-cols: 2;--ws-ctr-rows: 1;--ws-ctr-top-dist: 0;">
        
    </div>
</div>
```

Sometimes, you even want to create code with all CSS and JavaScript embedded:

```php
use webship\menus\ControlPanelHtmlView;
$controlPanel = ControlPanelHtmlView::create(3, 2)->fix(); // fix() locates the view in the top right corner.
$elementsArea = $controlPanel->area(2, 1);

echo $controlPanel->generate(true);
```

This produces:

```html
<div class="ctr wb fix tr " style="--ws-ctr-cols: 3;--ws-ctr-rows: 2;--ws-ctr-top-dist: 0;">
    <div class="ctr area wb " style="--ws-ctr-cols: 2;--ws-ctr-rows: 1;--ws-ctr-top-dist: 0;"></div>
    <script></script>
</div>
<style type="text/css">

/* ... */

.ctr.plain.wip {
    padding: 0 3px;
    align-items: baseline;
}</style>
```


### Action Register

The most powerful feature of *WebShip* is its automatic runtime generation of RESTful APIs.
In a usual tool stack, if you want to call a server endpoint, you have to the JavaScript call, the serialization of
the information that is passed, the deserialization on the server side, the PHP implementation of the endpoint being
called, etc.
*WebShip* overcomes this situation with a typical Model-View-Controller pattern.

#### Controller

You will write your controller functions in one or multiple PHP classes.
If you like to, your PHP class can extend the *Ship* class ```ship\controllers\ShipController``` but you do not have to.
This abstract controller class requires to define the scope of the controller to structure your web application.

Your controller functions are typical PHP methods in the class.
An important requirement is that you specify your method parameters when ever possible.
Our controller will provide a function that accepts a name as string and then sends a greeting:

```php
use ship\controllers\ShipController;

class GreetingsController extends ShipController {

    /**
     * @inheritDoc
     * @ignore
     */
    public function getScope() : string|null {
        return 'greetings';
    }

    /**
     * Send greetings. 
     * @param string $name The name.
     * @param string|null $title The title of the person if provided.
     * @return HtmlView
     * @without  
     */
    public function greetYou(string $name, string|null $title = null) : HtmlView {
        if (!is_null($title)) $name = $title . ' ' . $name;
        $view = new HtmlView();
        $view->texts($name);
        return $view;
    }
}
```

Please recognize that we added a second, optional parameter ```$title```.
Furthermore, the method *does not* send back ordinary HTML code - it sends back an object of type ```HtmlView```.
You can send back most kinds of objects and data types.
However, your method should *always provide any feedback* (at least of type ```boolean```).

The above example uses two *annotations*:
- ```@ignore```: This states that a method (in this case ```getScope()```) shall not be provided as an endpoint.
- ```@without```: Typically, you will have a web application where your users have to log in. If a method shall be provided
  *without* log-in, then you must use this annotation.

Of course, your web application cannot just take any of your classes and provide it as endpoint (this would indeed be
a little bit risky).
For this reason, you have to *register* your controller class to ```ship\controllers\ActionRegister```.

The following code snippet provides a typical entry point for your web application.
Since *WebShip* relies on the *Slim Framework 4*, your web app will be created via ```AppFactory``` and a routing start
point (in the example ```v1```).
The ```ActionRegister``` is created as an instance of this starting point, and you can register your controller on it
via the ```register(string[]|string $class)``` method.
Our *Greetings* endpoint will then be available at ```<SERVER>/v1/api/greetYou```.

```php
// WebShip-specific: Register an implementation of the Ship *EventProtocol*.
EventProtocol::registerImplementation(HtmlEventProtocol::class, HtmlView::class);

// WebShip relies on the Slim framework and builds on an ordinary app.
$app = AppFactory::create();

// We build a root API endpoint *v1*.
$app->group('/v1', function (RouteCollectorProxy $group) {
    // Create a new instance of a Ship's *ActionRegister*.
    $actionRegister = ActionRegister::instance($group);
    // Register our greetings controller.
    $actionRegister->register([
        GreetingsController::class
    ]);
    // Register UI handlings.
    $actionRegister->bindTemplateFunctions([
        GreetingFunctions::class
    ]);
});

// Run the app.
$app->addRoutingMiddleware();
$app->run();
```

#### Protocols

As you may have recognized in the above code snippet, the ```ActionRegister``` is also used to bind a custom 
```GreetingsFunctions``` class.
However, before we dive into what happens in the above snippet, we will investigate what a *WebShip* *Protocol* is.

*Protocol*s are a powerful concept of *WebShip*, which allows for describing UI-related functionality in PHP.
It "protocols" what happens to UI elements (i.e., views).

Protocols are implemented through the *Ship*'s abstract ```ship\protocols\Protocol``` class.
The most used specific ```Protocol``` implementation is ```ship\protocols\ModifyProtocol```, which describes how UI
views are changed.
A HTML/JS implementation is provided through ```webship\protocols\HtmlModifyProtocol```.

You can create a ```HtmlModifyProtocol``` from *any* ```View``` by calling the 
```protocol(int $event, array $params = [])``` method.
Actually, this method creates a ```webship\protocols\HtmlEventProtocol``` that reacts on (real) *Events*.
Typical *Events* are a clicking on a button, changing an option in a select field, an event marking a view as "dirty",
etc.
You can find a pre-defined list of events in the enumeration ```webship\interoperability\WEvent```.
However, since events are just integer values, you can even invent and add new events.
Back to the story: When such an event occurs, the view reacts by processing the ```HtmlModifyProtocol```.
If you require to process a protocol when loading a view, you can use the ```WEvent::ONLOAD``` or 
```WEvent::ONLOADCONTENT``` events.

A ```ModifyProtocol``` changes and interacts with your views *on the client side* (i.e., in the browser).
You can, for example: 
- *add* a view with 
```add(DirectView|TemplateObject|array|string $view, DirectView|TemplateObject|string|null|false $new = false)```, 
- *replace* a view with
```replace(DirectView|TemplateObject|array|string $view, DirectView|TemplateObject|string|null|false $new = false)```,
- *delete* a view with
```delete(DirectView|TemplateObject|array|string $view)```,
- *add CSS classss* to a view with
```addClass(DirectView|TemplateObject|array|string $view, TemplateObject|array|string $clazz)```,
- *remove CSS classes* from a view with
```addClass(DirectView|TemplateObject|array|string $view, TemplateObject|array|string $clazz)```,
- etc.

**Note:** As you will recognize, you cannot just use *direct reference* (in terms of an object of type ```DirectView```),
you can also provide an array of views, a ```TemplateObject```, or a *string* referring to a view.
You will find an own section ["Referring to Views"](#referring-to-views) on how to refer to views.
You will also find an own section about ```TemplateObjects```: 
["Creating Protocol Functions"](#creating-protocol-functions).

As you will also recognize, some of the view parameters above can be left out (e.g., the view that is added 
in ```add```, the replacement view in ```replace```, etc.).
Here, *WebShip* will automatically use ```View```s that are returned by your APIs and place them as parameter.
We will provide an example soon.

A ```ModifyProtocol``` further allows for calling your APIs.
Basically, this is achieved with the 
```php
action(string $controller, string $name, array $parameters = [], array $title = [/*...*/])
```
method.
This creates a new ```ship\protocols\ActionProtocol```, or in the case of *WebShip*, its implementation being a
```webship\protocols\HtmlActionProtocol```.
This kind of protocol provides you with four methods:
1. ```success()```, which return, again, a ```HtmlModifyProtocol```, and is called in the browser when the call was 
   successful.
2. ```error()```, which returns also a ```HtmlModifyProtocol```, and is called when the call delivered not 200.
3. ```fail()```, which returns also a ```HtmlModifyProtocol``` and a specific object of type ```ship\outputs\Fail```. 
   This a controller-specific failure routine.
4. ```eventually()``` returning a ```HtmlModifyProtocol``` and is executed in any case.

Now, we want to press our new knowledge into an example using our ```GreetingsController``` from above:

```php
use webship\buttons\StandardButtonHtmlView;
use webship\containers\ContainerHtmlView;
use ship\interoperability\SLanguage;

/*...*/
// Create a vertical "ver" container where the sub elements have a gap of 10, "gap10". 
$container = ContainerHtmlView::createIt(['ver', 'gap10']);
$container->ref('main-container');

// Create a button and add it to the container.
$button = StandardButtonHtmlView::create('press-the-button');
$container->add($button);
$button->texts([
    SLanguage::ENGLISH => 'Please press me!',
    SLanguage::GERMAN => 'Bitte drück mich!'
]);
$button->notices([
    SLanguage::ENGLISH => 'If you are pressing me, I will greet you.',
    SLanguage::GERMAN => 'Wenn du mich drückst, dann grüße ich dich.'
]);
$button->protocol(WEvent::CLICK)->
    action(GreetingsController::class, 'greetYou', [
        'name' => 'Thomas A. Anderson',
        'title' => 'Mr.'
    ], [
        SLanguage::ENGLISH => 'Call the greetings function',
        SLanguage::GERMAN  => 'Rufe die Begrüßung auf'
    ])->success()->
        add($container)->
        parent()->
    error()->add('main-app');
/*...*/
```

This example creates a *WebShip* container view, which arranges its subviews vertically and with a gap of 10px.
Furthermore, it creates a *WebShip* standard button.
The texts of the button are set with the ```texts``` method (being available for all ```View```s).
Hints (when you are over the button with your mouse) can be passed by the ```notices``` method (also being available for 
all ```View```s).

The magic happens starting with the line ```$button->protocol(WEvent::CLICK)```.
This means if the button is *clicked*, then the ```Protocol``` will be executed (in the browser).
It will perform an API call by using ```action()```.
We pass the controller (```GreetingsController::class```) and the desired method/endpoint (```greetYou```).
Furthermore, we have to provide the required parameters of the endpoint. 
The parameters are provided by the next *array*:
```php
[
    'name' => 'Thomas A. Anderson',
    'title' => 'Mr.'
]
```
This sets the required parameter *name* to ```"Thomas A. Anderson"``` and the optional parameter *title* to ```"Mr."```.

Whenever an API call happens, you can inform your users.
This is done by providing a description of your call (as a next array in different languages).

If the call was successful, then the ```success()```-path will be taken.
As you remember, the endpoint will return a ```HtmlView```.
Thus, we can leave out the second parameter of the ```add``` method, so that the line ```add($container)``` can be 
read as "add the resulting view to my view ```$container```".
With calling ```parent()``` on the ```success``` branch, we go "back" to the ```ActionProtocol``` and can enter the 
```error``` branch.
In the error branch, we use again the ```add``` method and leave the second parameter empty (expecting an 
error handling view).
However, here we refer to a view called ```"main-app"```. 
You will find more information about referring views through strings in ["Referring to Views"](#referring-to-views).
Briefly: Starting with the view, where the action is called (i.e., the button), *WebShip* searches at runtime for the
closest view with the name ```"main-app"```.
That means, there could be multiple views with the same reference name.
Note that the *container* view in the code above gets a reference ```"main-container"``` and, thus, could be referenced
as ```"main-container"```.


### Referring to Views

Each ```View``` in (*Web*)*Ship* can have a referring name.
Usually, it is recommended to directly use an object of the view (if available).
However, there are situations where you cannot prevent using references.

Adding a reference to a view is easy by just calling the ```ref(string $id, string|false $scope = false)``` method of
each ```View```:

```php
$view = ContainerHtmlView::createIt();
$view->ref('my-reference');
```

If you state this reference in a ```Protocol``` where a ```View``` is expected, then *WebShip* will search for the
view starting in the view the protocol belongs to.
This is achieved by *WebShip* as follows:

1. Everytime we gave view a reference, it will be stored in a reference list of this view as well as in each 
   parent view.
2. Furthermore, a session-related list is maintained storing a global reference list.

You can also retain a view manually with the ```get(string $id)``` method:

```php
// We have an "anchor" view $view where we start the search.
$view->get('my-reference');
```

Searching a view then uses two contexts and returns the first detected view:

1. Search in the reference list of the current view. If detected, return it.
2. Else: Go up the parent views and search it in their reference lists. If detected, return it.
3. Else: If no parent is available anymore, search in the global list. If detected, return it.
4. Else: Return null

*Note:* The ```get()``` method does not return a ```View``` object.
It will return an array with the scope, name, and the ```View``` object (in entry ```"view"```).


### Referring Values in Views

The most common case when you will require referencing a view is in forms.
For this purpose, we extend our above "```Greetings```" example:

```php
/*...*/
// Create a vertical "ver" container where the sub elements have a gap of 10, "gap10". 
$container = ContainerHtmlView::createIt(['ver', 'gap10']);
$container->ref('main-container');

// Insert a labelled text field to request the title.
$titleInput = LabelledInputHtmlView::createTextField([
    SLanguage::ENGLISH => 'Please specify your title',
    SLanguage::GERMAN  => 'Bitte gib deine Anrede an'
], 'title-input');
$container->add($titleInput);
// When we refer to it, we will refer to the value.
$titleInput->ref('title', View::VALUE_SCOPE); // Or 'value' instead of the constant.

// Create a button and add it to the container.
$button = StandardButtonHtmlView::create('press-the-button');
$container->add($button);
$button->texts([
    SLanguage::ENGLISH => 'Please press me!',
    SLanguage::GERMAN => 'Bitte drück mich!'
]);
$button->notices([
    SLanguage::ENGLISH => 'If you are pressing me, I will greet you.',
    SLanguage::GERMAN => 'Wenn du mich drückst, dann grüße ich dich.'
]);
$button->protocol(WEvent::CLICK)->
    action(GreetingsController::class, 'greetYou', [
        'name' => 'Thomas A. Anderson'
        // Here we do not use the title anymore.
    ], [
        SLanguage::ENGLISH => 'Call the greetings function',
        SLanguage::GERMAN  => 'Rufe die Begrüßung auf'
    ])->success()->
        add($container)->
        parent()->
    error()->add('main-app');
/*...*/
```

The example was extended by an input field requesting the title by the user.
Thus, we have flexible form data unknown when we are writing the code.

*WebShip* is smart.
Whenever you leave parameters in calls out (in the code above, the ```'title'``` parameter is missing), *WebShip* 
searches for a referred view with the same name - in this case, the ```$titleInput```.
However, we do *not* want to transfer the view, we want to transfer the specified user value.
This is where the parameter ```$scope``` is for when referencing views.
Usually, you will require the constant ```View::VALUE_SCOPE``` or just ```'value'``` scope.

In short terms: *WebShip* creates a call for the ```greetYou``` endpoint and expects the parameter ```'title'```.
It does not find it and searches, starting from this ```Protocol```, any closest view with the reference name 
```'title'```.
It detects the view ```$titleInput``` and recognizes that it shall not use the view itself but its runtime value, 
when the protocol is executed (internally, it replaces the view with JavaScript reading the value of the input).

Now, our example uses a dynamic title.
We can also do it for the name (excerpt):

```php
/* ... */
$nameInput = LabelledInputHtmlView::createTextField([
    SLanguage::ENGLISH => 'Please specify your name',
    SLanguage::GERMAN  => 'Bitte gib deinen Namen an'
], 'name-input');
$container->add($nameInput);
$nameInput->ref('name', View::VALUE_SCOPE);

/* ... */

$button->protocol(WEvent::CLICK)->
    action(GreetingsController::class, 'greetYou', [
        'name' => $nameInput
        // ...
```

However, in this example, we used the more "proper" variant, where we explicitly use the ```View``` object to set
the name.
*This is recommended in almost all situations.*



### Creating Protocol Functions

Assume that you want to call our ```greetYou``` endpoint with the same (or similar) UI effects in a different view.
One option would be to copy-paste the ```Protocol```.
However, this would lead to duplicated code and, once the protocol is changed, you have to adapt these changes 
everywhere you use the same protocol.
The second option is supported by *WebShip* is called *Protocol Function*s.

Similarly to functions/methods in programming languages, you will create your *Protocol Function*s by defining 
defining its *name*, its *scope*, and its *parameters*.
Our example protocol from above can nearly 1:1 be transformed into a *Protocol Function*:

```php
HtmlTemplateProtocol::create(self::GreetYou, self::ENVIRONMENT)->
    action(GreetingsController::class, 'greetYou', [
        'name' => TemplateObject::create('name'),
        'title' => TemplateObject::create('title')
    ], [
        SLanguage::ENGLISH => 'Call the greetings function',
        SLanguage::GERMAN  => 'Rufe die Begrüßung auf'
    ])->success()->
        add(TemplateObject::create('append'))->
        parent()->
    error()->add(TemplateObject::create('error'));
```

A *Protocol Function* is a kind of *template* and, thus, is created as 
```webship\protocols\HtmlTemplateProtocol```.
The instantiation first requires the *name* of the template as string (here ```self::GreetYou```).
*Note that it is recommended to use constants here so that you avoid spelling mistakes in your code and that you see
where your *Protocol Functions* are being used.*
In our example, we could have used ```public const GreetYou = 'GreetYou';```.
The second parameter is the *namespace*/*environment*, in which this name appears.
*Note that it is recommended to use a constant here and to collect *Protocol Function*s with a similar functionality
in one environment.*
In our example, we could have used ```public const ENVIRONMENT = 'greetings';```.

The rest of the *Protocol Function* is looking very similar to the original direct protocol implementation.
However, all dynamic inputs (such as *name*, *title*, the view to *append*, and the *error* view) were replaced by
```ship\template\TemplateObject```s. 
A ```TemplateObject``` usually requires just a *name* (in the example ```'name'```, ```'title'```, etc.).
*Note that you can also set constants as fixed values and you do *not* have to use a ```TemplateObject``` for each
dynamic part.*

*WebShip* will create a JavaScript function out of the *Protocol Function* that can be called from different 
contexts with different inputs.
However, *WebShip* would not be *WebShip* if you can not call those functions/templates from your ```Protocol```.
For this, you will use the ```ModifyProtocol``` method 
```doIt(string $name, array $context = [], string $environment = 'global')```:

```php
$button->protocol(WEvent::CLICK)->doIt(GreetingFunctions::GreetYou, [
    'name' => $nameInput,
    'title' => $titleInput,
    'append' => $container,
    'error' => 'main-app'
], GreetingFunctions::environment());
```

As you can see, the call is much more readable and can be reused whenever you want.
Keep also in mind that you can define your own views that you can reuse where you want.

The attentive reader may have recognized that the above code snippet uses a class ```GreetingFunctions``` that is 
still undefined.
We will change this now:

```php
use ship\controllers\ProtocolFunctionEnvironment;

class GreetingFunctions implements ProtocolFunctionEnvironment {

    public const ENVIRONMENT = 'greetings';
    public const GreetYou = 'GreetYou';

    /**
     * @inheritDoc
     */
    public static function environment(): string {
        return self::ENVIRONMENT;
    }

    /**
     * @inheritDoc
     */
    public static function add(): bool {
        HtmlTemplateProtocol::create(self::GreetYou, self::ENVIRONMENT)->
            action(GreetingsController::class, 'greetYou', [
                'name' => TemplateObject::create('name'),
                'title' => TemplateObject::create('title')
            ], [
                SLanguage::ENGLISH => 'Call the greetings function',
                SLanguage::GERMAN  => 'Rufe die Begrüßung auf'
            ])->success()->
                add(TemplateObject::create('append'))->
                parent()->
            error()->add(TemplateObject::create('error'));

        return true;
    }
}
```

This class extends the *Ship* interface ```ship\controllers\ProtocolFunctionEnvironment```, which is a simple container 
of *Protocol Functions*.
It defines two constants, one for the name of the environment (```'greetings'```) and one for our *Protocol Function*
```'GreetYou'```.
Furthermore, it implements the inherited ```environment()``` method that we can call to get the name of the environment.
The important part is the implementation of the inherited method ```add()```.
This just defines the *Protocol Functions* that you need (in this environment) and you have access to the constants
of this class.
Although you can just use strings as names and environments, we highly recommend to use constants because then you 
will see:

1. Where is your *Protocol Function* used and
2. you avoid spelling mistakes of your environments and function names.




### Binding Objects to Views

Imagine you have some views that are related to a specific object or to a specific field of an object.
If some properties of your object or the value of the field changes, then you have to refresh all views that are 
related to this object.
Since your web application UI can be complex, calling API calls for all of these views sounds challenging.

*WebShip* has a powerful mechanism to simplify updating your views: *binding objects to views*.

To bind an object to a view, the class of your object has to implement the ```ship\interoperability\IShippable``` 
interface.
This interface requires nothing: No inherited function to specify. Nothing!
Furthermore, you have to provide an implementation of the abstract ```ship\interoperability\AShipper``` class.
This class requires implementations for some methods:

- ```getKey(IShippable $shippable) : string|int```: Return a unique key of a given ```IShippable``` object (unique 
  regarding its class).
- ```hasStringKey(IShippable|string $shippable) : bool```: Decides if a given string class or ```IShippable``` object
  requires a string or integer key.
- ```getObject(string $class, string|int|array $key, bool $keyAsIs = false) : IShippable|null```: Given a class and a
  key, this method should return the specific object for it.
- ```exists(string $class, string|int|array $key, bool $keyAsIs = false) : bool```: Checks if an object with a given 
  class and key exists.
- ```hasActiveUserSession() : bool```: Checks if there is an active user session.

*Note that if you have any storage (e.g., database) running in your web application, then implementing those methods
is straight-forward.

If (1) your required object classes implement the ```IShippable``` interface *and* (2) you have an implementation of 
the ```AShipper``` class and you have registered via ```ship\interoperability\ShipperRegister```:

```php
ShipperRegister::register(Shipper::class); // Shipper is your implementation of AShipper
```

in your web application (e.g., in your entry *index.php*), then your *WebShip* is enabled to handle all your objects in 
your web application.

Now, we will return back to *binding of objects*.
*Binding an object* to a view relates the object to this view.
This means that you do not have to think about which views represents the object, because you can just inform the UI
that your object has changed - and the UI reacts to it.
So binding in *WebShip* is very related to a *reactive* UI style (such as in AngularJS, vue.js, etc.).

Binding an object to a view is very easy, you just use the method ```bind(IShippable $obj, int[]|int|null $event, 
ModifyProtocol|null $protocol, string|null $scope, /*...*/)``` that is available for each ```View```.
This method requires first the object ```$obj``` to bind.
Second, the binding is usually related to one or more *events* (such as ```WEvent::CONTENTMODIFY```).
If you do not specify a specific event, the UI will react on any event on the object.
Third, a ```ModifyProtocol``` specifies what shall happen.
You can leave the parameter out, then nothing happens (if you want that).
Finally, you can also specify a scope ```$scope```.
Imagine you have a relatively complex object which is referred partially in some views (e.g., in a content list,
in an editor, and just in a header).
If you do not specify a *scope*, then whenever the object receives the specified event(s), all views are updated 
although, for example, just the header has changed.
The *scope* is just an ordinary string.
*We recommend to use an enumeration or constant list to reuse different scopes and to enable the fast renaming of 
scopes.*

Let us consider an example where we assume a ```Customer``` object for storing the greeting information (where the
class of course implements ```IShippable```):
```php
use ship\controllers\ProtocolFunctionEnvironment;
use ship\controllers\ShipController;
use ship\interoperability\SLanguage;
use webship\containers\ContainerHtmlView;
use webship\interoperability\WEvent;
use webship\protocols\HtmlModifyProtocol;
use webship\titles\TitleHtmlView;

class CustomerHtmlView extends ContainerHtmlView {
    
    /**
     * Create a view for a single customer. 
     * @param Customer $customer The customer.
     * @param string[]|string $classes Additional CSS classes (without '.').
     * @return CustomerHtmlView
     * @throws Exception
     */
    public static function create(Customer $customer, array|string $classes = []) : CustomerHtmlView {
        $view = new CustomerHtmlView($classes);
        
        // Define a header with the name.
        $title = self::createTitle($customer);
        $view->add($title);
        // We want to react on changes when the name of the customer changes.
        $title->bind($customer, WEvent::CONTENTMODIFY, 
            HtmlModifyProtocol::create($view)->doIt(CustomerFunctions::GetTitle, [
                'customer' => $customer,
                'replace' => $title,
                'error' => 'main-app'
            ], CustomerFunctions::environment()), CustomerFunctions::NAME_SCOPE);
            
        // Define a list of activities of the customer.
        $information = self::createInformation($customer);
        $view->add($information);
        // We want to react on changes when there is anything new except the name.
        $title->bind($customer, WEvent::CONTENTMODIFY, 
            HtmlModifyProtocol::create($view)->doIt(CustomerFunctions::GetInformation, [
                'customer' => $customer,
                'replace' => $information,
                'error' => 'main-app'
            ], CustomerFunctions::environment()), CustomerFunctions::INFORMATION_SCOPE);
            
        return $view;
    }
    
    /**
     * Create a title for the view. 
     * @param Customer $customer The customer.
     * @return HtmlView
     */
    public static function createTitle(Customer $customer) : HtmlView {
        return TitleHtmlView::create([
            SLanguage::ENGLISH => 'Customer: ' . $customer->getName(),
            SLanguage::GERMAN  => 'Kunde: ' . $customer->getName()      
        ]);        
    }
    
    /**
     * Create a complicated customer portfolio with more information. 
     * @param Customer $customer The customer.
     * @return HtmlView
     */
    public static function createInformation(Customer $customer) : HtmlView {
        /* ... */
    }
}

class CustomerFunctions implements ProtocolFunctionEnvironment {
    public const ENVIRONMENT = 'customers';
    
    public const GetTitle = 'GetTitle';
    public const GetInformation = 'GetInformation';
    
    public const NAME_SCOPE = 'name';
    public const INFORMATION_SCOPE = 'information';
    
    /**
     * @inheritDoc
     */
    public static function environment(): string {
        return self::ENVIRONMENT;
    }

    /**
     * @inheritDoc
     */
    public static function add(): bool {
        HtmlTemplateProtocol::create(self::GetTitle, self::ENVIRONMENT)->
            action(CustomerController::class, 'getTitle', [
                'customer' => TemplateObject::create('customer')
            ], [
                SLanguage::ENGLISH => 'Get the header for the customer',
                SLanguage::GERMAN  => 'Hole die Überschrift für den Kunden'
            ])->success()->
                replace(TemplateObject::create('replace'))->
                parent()->
            error()->add(TemplateObject::create('error'));
            
        HtmlTemplateProtocol::create(self::GetInformation, self::ENVIRONMENT)->
            action(CustomerController::class, 'getInformation', [
                'customer' => TemplateObject::create('customer')
            ], [
                SLanguage::ENGLISH => 'Get the information for the customer',
                SLanguage::GERMAN  => 'Hole die Informationen für den Kunden'
            ])->success()->
                replace(TemplateObject::create('replace'))->
                parent()->
            error()->add(TemplateObject::create('error'));

        return true;
    }
}

class CustomerController extends ShipController {
    /**
     * @inheritDoc
     * @ignore
     */
    public function getScope() : string|null {
        return CustomerFunctions::environment();
    }

    /**
     * Get the title view of a customer. 
     * @param Customer $customer The customer.
     * @return HtmlView
     * @without  
     */
    public function getTitle(Customer $customer) : HtmlView {
        return CustomerHtmlView::createTitle($customer);
    }
    
    /**
     * Get the information view of a customer. 
     * @param Customer $customer The customer.
     * @return HtmlView
     * @without  
     */
    public function getInformation(Customer $customer) : HtmlView {
        return CustomerHtmlView::createInformation($customer);
    }
}
```

Of course, this example is a little bit bigger but shows the beautifulness of what is achievable quickly in *WebShip*.
The more important: It focuses on *what to do* and not on *how to achieve it*.

The example defines an own view ```CustomerHtmlView```, which basically is a ```ContainerHtmlView``` and, thus, inherits
its look'n'feel as well as its possibilities (in arranging stuff).
It defines a header with a ```TitleHtmlView``` and some arbitrary view providing other information about a 
```Customer```.
It binds the ```$customer``` object to both views.
Whenever this object receives an ```WEvent::CONTENTMODIFY```, the UI (with *WebShip*) will react.
However, it will only react if the defined scope is affected.
For example, calling the ```GetTitle``` *Function Protocol* will only be triggered, when the scope is the 
```NAME_SCOPE```.
The *Function Protocol* is triggered by a ```HtmlModifyProtocol``` with a ```doIt()``` call. 
This ```HtmlModifyProtocol``` is related to the customer view and, thus, is starting for searching for missing objects
and values from this point.
This is not necessary here, but each protocol is related to either a parent protocol or to a view.

*We recommend to use separate (usually static) methods to describe views that can be changed (and, thus, can be 
loaded separately).*
In our example, we did for the header (```createTitle```) and the information (```createInformation```) views.

The *Function Protocols* are defined as usual, here in the ```CustomerFunctions``` class.
*Note that you can reuse these functions wherever you want.*
The endpoint functions are defined in the ```CustomerController```.
Here you may recognize why it is recommended to have own statically methods for changing views.
The endpoints are very small.

If you now somewhere throw a ```WEvent::CONTENTMODIFY``` in scope ```NAME_SCOPE``` to ```$customer``` object being 
bound to any view currently in the browser, the header would be reloaded:

```php
use webship\controller\BasicFunctions;
/* Other part of your code */
$button->protocol(WEvent::CLICK)->doIt(BasicFunctions::ThrowEventToObject, [
    'event' => WEvent::CONTENTMODIFY,
    'object' => $customer,
    'scope' => CustomerFunctions::NAME_SCOPE
]);
```

We hope the reader notice what must happen all in the background of *WebShip*.
If not, here a short summary (starting with just pressing the above button):

1. Produce the HTML view for the button.
2. Produce the JavaScript for the button event.
3. In doing this, serialize the ```$customer``` object (or a *shadow*/reference of it.)
4. If the button is clicked:
   1. Call the ```ThrowEventToObject``` *Protocol Function* in JavaScript.
   2. Identify if there are current bindings related to the object ```$customer``` regarding the ```$event``` and 
      ```$scope```.
   3. Identify the views behind it and call the bound *Protocol Function* (in JavaScript).
   4. This calls the ```CustomerController::getTitle``` function.
   5. In doing this, *WebShip* automatically loads the "real" objects behind the *shadows*.
   6. Then it applies the parameters to the call and gets, in this case, a ```HtmlView``` as a result.
   7. *WebShip* generates automatically the HTML, CSS, and JavaScript code of the view and returns it.
   8. The browser's calling *Protocol Function* gets the response view and replaces it with the specified view.

*Note that there are many steps behind it, but this is a short representation.*

*Furthermore, note that you have to implement this all by hand in JavaScript and the PHP code. 
None of the steps includes much overhead.
This is just how distributed systems work.
Just more amazing.*

**Further note that we did not touch **any** HTML, CSS, or JavaScript code for this example.
Our entire web application stayed in the same code base.**
This is one of the core advantages in using *WebShip*.


### Broadcasting Events

*Binding objects* is an *object*-related concept to react to events - instead of throwing events directly to views, they are
thrown to objects, and the bound views react accordingly.

What is missing is a mechanism for when you want views to react to specific events without having to specify each
individual view that should react.
This mechanism gap is closed by *WebShips* event *broadcasting*.

*Broadcasting events* is very similar to *binding an object* to a view; just without the object:

```php
bindBroadcast(array|int $event, ModifyProtocol|string|null $protocol = null,
              string|null $scope = null, /*...*/) : static
```

Invoking this method on a ```View``` states that this view shall react to a specific *broadcast* ```$event```.
When this ```$event``` occurs in the specified ```$scope```, then the specified ```ModifyProtocol``` ```$protocol```
will be executed for this view.
Note that you can also pass a string instead of a ```ModifyProtocol```, which may contain pure JavaScript code or 
a previously generated ```ModifyProtocol```.

We will consider an extension of our example ```CustomerHtmlView``` in 
["Binding Objects to Views"](#binding-objects-to-views):

```php
use webship\containers\ContainerHtmlView;
use webship\protocols\HtmlModifyProtocol;

class CustomerHtmlView extends ContainerHtmlView {
    /*..*/
    public static function createCustomerList() : HtmlView {
        $customers = Customer::getAll(); // Imagine a magic method that retains all customers out of a storage.
        
        $list = ContainerHtmlView::createIt(['ver', 'gap10']);
        // Add an entry for each customer with the WebShip-build-method 'each'
        $list->each($customers, function (ContainerHtmlView $l, Customer $customer) {
            $entry = ContainerHtmlView::createIt(['hor', 'vcent', 'gap5']);
            $l->add($entry);
            $entry->view()->texts($customer->getTitle());
            $entry->view()->texts($customer->getName());
            $entry->view()->texts($customer->getIdentifier());            
        });
        
        $list->bindBroadcast(CustomerEvents::NEW_CUSTOMER, HtmlModifyProtocol::create($list)->doIt(CustomerFunctions::GetCustomerList, [
            'replace' => $list,
            'error' => 'main-app'
        ], CustomerFunctions::environment()));
        
        return $list;
    }
    /*..*/
}

class CustomerEvents {
    public const NEW_CUSTOMER = 123;
}
```

The view provides a list of all customers being registered on your web application.
In doing this, it uses two ```ContainerHtmlView```s; one with a column-based ```ver```tical orientation and one for
each customer with a ```hor```izontal orientation.

Building up the list is achieved with the *Ship* method ```each(array|IShippable|null $array, Closure $callback)``` 
being available for each ```View```.
This method takes each element of the provided array ```$array``` and applies it to the *Closure* (aka anonymous method) 
```$callback``` expecting a function with at most 3 parameters:

1. ```$view```: A reference to a calling ```View```. In the above example, ```$l``` refers to ```$list```.
2. ```$value```: The current single value of the array. In the above example, ```Customer $customer``` represents the
   passed value.
3. ```$key```: The current key of the array (if this is required). The above example omits this parameter.

The exciting part happens when we bind the broadcast starting with 
```php
$list->bindBroadcast(CustomerEvents::NEW_CUSTOMER, /*...*/);
```
It binds an own defined ```NEW_CUSTOMER``` event that simply is an integer (see the attached class above).
Whenever a new customer is added to the system, each ```CustomerHtmlView``` currently presenting the customer list 
shall do something.
What is done in this case is, again, specified by a ```ModifyProtocol```.
In this case, the customer list shall be refreshed with the updated one.
Since the ```NEW_CUSTOMER``` event is very specific, we do not require a scope here and just react to any occurrence of this event.

*We hope that you recognize what you can achieve with *broadcasting*:
Views that react to events without retaining references to these views and throwing an event to all of them.*

*Awesome, we know :).*


### Handling Expected API Fails

Imagine your web application has a form for adding new customers.
Even though the data may be validated client-side in the browser, wrong data can still be passed to the API endpoint
to finally create the new customer.
If the new customer can be created successfully, then you would expect that the form is closed and a view is loaded
showing the customer.
If an unexpected server error occurs, you expect the user to be informed about this error.
However, where do you specify how to react on erroneous input data that is passed?
*Ship*'s ```Fail``` mechanism is the answer.

Basically, ```ship\outputs\Fail``` is a class that accepts any kind of ```$content``` although usually you will 
have a ```HtmlView``` (or subclass) as content.
For example:

```php
use ship\interoperability\SLanguage;
use ship\outputs\Fail;
use webship\alerts\ErrorHtmlView;

new Fail(ErrorHtmlView::create([
    SLanguage::ENGLISH => 'Please specify a name of your new customer.',
    SLanguage::GERMAN  => 'Bitte gib einen Namen deines neuen Kunden an.'
]));
```

Whenever an endpoint returns an object of class ```Fail``` you are enabled to use the ```fail()``` branch of an
```ActionProtocol```.
Remember, an ```ActionProtocol``` is used when calling an endpoint of your API.
Until this point, we usually used the ```success()``` and the ```error()``` branches.
Now, we use the ```fail()```-branch:

```php
use ship\controllers\ProtocolFunctionEnvironment;

class CustomerFunctions implements ProtocolFunctionEnvironment {
    /* ... */
    public static function add() : bool {
        /* ... */
        HtmlTemplateProtocol::create(self::CreateNewCustomer, self::ENVIRONMENT)->
            action(CustomerController::class, 'createNewCustomer', [
                'name' => TemplateObject::create('name'),
                'title' => TemplateObject::create('title')
            ], [
                SLanguage::ENGLISH => 'Create customer',
                SLanguage::GERMAN  => 'Lege Kunde an'
            ])->success()->
                clear(TemplateObject::create('form'))->
                delete(TemplateObject::create('form'))->
                add(TemplateObject::create('append'))->
                parent()->
            fail()->
                replace(TemplateObject::create('info'))->
                parent()->
            error()->add(TemplateObject::create('error'));
        /* ... */
    }
    /* ... */
}
```

This can be read as follows:

1. If the customer was created successfully, the ```'form'``` input fields shall be cleared, the ```'form'``` shall be 
   closed (aka deleted), and the new customer representation shall be appended to the ```'append'``` view.
2. If erroneous input data was provided, the user shall be informed by replacing the explanation to a specified
   (and previous empty) ```'info'``` view.
3. If the web application itself runs into an error, the error shall be appended to ```'error'```.

In our controller, the endpoint could be added as follows:

```php
use ship\controllers\ShipController;
use ship\interoperability\ShippingTools;
use ship\interoperability\SLanguage;
use ship\outputs\Fail;
use webship\alerts\ErrorHtmlView;
use webship\containers\ContainerHtmlView;

class CustomerController extends ShipController {
    /* ... */
    /**
     * Create a new customer in the web application. 
     * @param string|null $name The name of the new customer.
     * @param string|null $title The title of the new customer.
     * @return HtmlView|Fail
     * @throws Exception
     */
    public function createNewCustomer(string|null $name, string|null $title = null) : HtmlView|Fail {
        $errors = [];
        if (is_null($name) || $name === '') {
            $errors[] = [
                SLanguage::ENGLISH => 'Please specify a name.',
                SLanguage::GERMAN  => 'Bitte gib einen Namen an.'
            ];
        }
        if (is_null($title) || $title === '') {
            $errors[] = [
                SLanguage::ENGLISH => 'Please specify a title.',
                SLanguage::GERMAN  => 'Bitte gib eine Anrede an.'
            ];
        }
        if (!ShippingTools::isEmpty($errors)) {
            // Erroneous input data.
            $container = ContainerHtmlView::createIt(['ver', 'gap10']);
            $container->each($errors, function (HtmlView $c, array $error) {
                $c->add(ErrorHtmlView::create($error));
            });
            return new Fail($container);
        }
        // Everything is ok.
        $customer = new Customer($name, $title);
        $customer->create();
        
        return CustomerHtmlView::create($customer, ['new']);
    }
    /* ... */
}
```