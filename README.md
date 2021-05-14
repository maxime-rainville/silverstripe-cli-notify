# Silverstripe CLI notify

This module adds a middleware to Silverstripe CMS that will try to display desktop notification when using the command line to execute a `dev/` command.

The notification is displayed once the command has completed. This allows you to start a long running proccess in your terminal and move on to other things.

## Installation

```bash
composer require --dev maxime-rainville/silverstripe-cli-notify
```

Note the usage of the `--dev` flag. This is meant to be a development tool only.

## How does it work

This modules uses the [`jolicode/jolinotif`](https://github.com/jolicode/JoliNotif) PHP library to display the notifications. This library works on MacOS, most Linux distros and MS Windows ... althought I've only tested on Ubuntu.

You can still use this library if your OS doesn't support desktop notifications. Nothing will happen, but your project will otherwise work as expected.

This is unlikely to work if your `sake` command is run from inside vagrant or docker.
