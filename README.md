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
    $actionRegister->bindEvents([
        GreetingsEvents::class
    ]);
});

// Run the app.
$app->addRoutingMiddleware();
$app->run();
```

#### Protocols

As you may have recognized in the above code snippet, the ```ActionRegister``` is also used to bind a custom 
```GreetingsEvents``` class.
We recognize that *Event* is currently not the right term, and it will surely be renamed in the future.
A better term may be *Protocol Function*, or similar.
We did not find a final term yet.
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

**TODO:**



### Binding Objects to Views

**TODO**




### HtmlView.php

Most views of *WebShip* inherits properties from *HtmlView.php*.
This class contains methods that are automatically invoked by the *(Web)Ship* engines to generate HTML, CSS, and JS code.
Usually, you do not have to do anything to start code generation.


### Protocol

The most powerful thing you can do is to add functionality. 
Typically, you want to add an action if a button is pressed. 
In *(Web)Ship*, the entire concept is called *Protocol*. 
With a *Protocol*, you start to describe what to do if some event occurs:

```php
use webship\core\HtmlView;$view = new HtmlView();
$view->ref('list');

$button = StandardButtonHtmlView::create('my-beautiful-button');
$button->texts([
    Language::ENGLISH => 'Please press me!',
    Language::GERMAN  => 'Bitte drück mich!'
]);
// Add some action if the button is pressed
$button->protocol(Event::CLICK)->doIt('LoadListOfBeautifulThings', [
    'replace' => $view
], 'beautifulThings');
```

This example invokes the *Protocol Template* function ```LoadListOfBeautifulThings``` in the scope ```beautifulThings```. 
The parameters are ```replace``` meaning that the result of ```LoadListOfBeautifulThings``` seems to return a view that replaces ```$view```.

The *Protocol Template* function ```LoadListOfBeautifulThings``` is defined in a so-called *EventAdder.php* implementation. 
You just require a class that extends *EventAdder.php*, which then contains the *Protocol Template*. 
A *Protocol Template* is similar to a function that is created once but can be invoked whenever required. 
It would also be possible to define some actions directly in the *Protocol*, however, this is not recommended.

For example, the *EventAdder.php* extension with the *Protocol Template* for ```LoadListOfBeautifulThings``` could look like this:

```php
use ship\controllers\ActionRegister;use ship\controllers\EventAdder;use ship\template\TemplateObject;use webship\containers\ContainerHtmlView;use webship\core\HtmlView;use webship\protocols\HtmlTemplateProtocol;class BeautifulEvents extends EventAdder {

    public const SCOPE = 'beautifulThings';

    /**
     * @inheritDoc
     */
    public static function add() : bool {
        HtmlTemplateProtocol::_new('LoadListOfBeautifulThings', self::SCOPE)->
            action(BeautifulController::class, 'loadListOfBeautifulThings', [], [
                Language::ENGLISH => 'Load list of beautiful things ...',
                Language::GERMAN  => 'Lade List wunderschöner Dinge ...'
            ))->
            success()->
                replace(TemplateObject::_new('replace'))->
            parent()->
            fail()->
                replace(TemplateObject::_new('fail'))
            parent()->
            error()->
                add(TemplateObject::_new('error'))->
            parent();    
    
        return true;
    }
}

class BeautifulController {

    public function loadListOfBeautifulThings() : HtmlView {
        $list = new ContainerHtmlView(['ver', 'gap5']);
        $list->each([1,2,3,4,5,6,7,8,9,10], function (ContainerHtmlView $l, int $nr) {
            $beautifulThing = new HtmlView();
            $beautifulThing->texts([
                Language::ENGLISH => $nr . '. beautiful thing',
                Language::GERMAN  => $nr . '. schönes Ding'
            ]);
            $l->add($beautifulThing);
        });
        return $list;
    }

}

$actionRegister = ActionRegister::instance($app);
$actionRegister->register([
    BeautifulController::class
]);
$actionRegister->bindEvents([
    BeautifulEvents::class
]);
```

## Which persistent classes does WebShip contain?

![](class_diagram.svg)