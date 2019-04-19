# [WIP] This guide is under development

Also see [Testing] manual.

[Testing]: Testing.md

## Overview

### Test frameworks we use and their purpose

// Codeception, PHPunit. Structure of tests

## Contributing

> Before contributing make sure you have last update of your repositories.
>
> (use `composer update` on whole project or `git pull` in specific module)

To contribute follow this steps:

1. Fork repository of module you were working on

    to find repository use `git remote -v` in your module dir and follow link or

    search for this module at [hiqdev](https://github.com/hiqdev)

2. Type `git remote add [remote name] [url]` inside your module dir

    `[remote name]` is name of your remote branch (for example `forked`)

    `[url]` is your forked url

3. Type `git checkout -b [branch name]`

    `[branch name]` is name of your new brach (for example `request`)

4. After `git add` when you make `git commit -m [message]` follow this simple rules:

    1. Your `[message]` must start with __one__ word like `Added`, `Changed`, `Fixed`, `Removed`
    2. Next will follow the description of what exactly was added, changed or removed.

5. Next you `git push [remote name] [branch name]`

    for example `git push forked request`

6. After that you need to create a __pull request__.

    You can do it at github website:

    1. Open repository (for example [hipnel](https://github.com/hiqdev/hipanel))

    2. Click __New pull request__

        (or click green __Compare & pull request__ and skip __iii__ step)

    3. Click __compare across forks__ and on the right choose __your forked repository__ -> __brach name__

    4. Click green __Create pull request__

7. After your pull request was reviewed and you have to make some changes,
   all you need to do is just make that changes and `push` to the same `[remote name] [branch name]`.

> Make sure that changes you push, for example `Added`, `Removed` etc. are all in different __pull requests__

// Trunk based development

### Docker-based development env

// What to read about?

#### xDebug in Docker

To make xDebug work in PHPStorm you need:

1. [Install xDebug](https://confluence.jetbrains.com/display/PhpStorm/Xdebug+Installation+Guide)

2. [Install browser extension](https://confluence.jetbrains.com/display/PhpStorm/Browser+Debugging+Extensions)

3. In PHPStorm, __Add Configuration__, choose type __PHP Web Page__

4. In __server__ settings create server and fill data:

   4.1 Set __Name__ that matches your project domain name set in .env (e.g. `local.hipanel.hiqdev.com`)

   4.2 `localhost` in __Host__

   4.3 `80` in __Port__

   4.4 `Xdebug` in __Debugger__

   4.5 Choose `Use path mappings` option and
       type `/app` in __Absolute path on the server__
       where __File/Directory__ is root path of your project

5. Turn on __Start Listening for PHP Debug Connections__ and
   don't forget to activate your debug browser extension

// What to do in PHPStorm, browser, etc?

// How to check that xDebug is enabled on PHP side?

## Troubleshooting:

// - debug session does not get started;

// - debug can not locate files;

