# WP-IfCondition
This plugins allows you to write simple conditions inside your wordpress content.

## Install
1. Download the plugin and put it into the plugin directory of your wordpress installation
2. Active the plugin in the wordpress admin panel

## Usage
After you have installed the plugin you can write simple if conditions inside any content which will execute shortcodes, such as "the_content()".
You may also use PHP-Functions, global and system variables.

### Simple usage

```
[if condition="date('Y') === 2015"]Yeah, it's the year 2015![/if]
```

### Advanced usage

```
[if condition="date('Y') === 2015"]
[then]Yeah, it's the year 2015![/then]
[/if]

[if condition="date('Y') === 2016"]
[else]Not 2016 so far ...[/else]
[/if]

[if condition="date('Y') === 2016"]
[then]Yeah, it's the year 2016![/then]
[else]Not 2016 so far ...[/else]
[/if]
```

Of cause I know, that eval is evil bla bla bla ... but I don't give a shit.
It's your choice weather you use this plugin or not.
