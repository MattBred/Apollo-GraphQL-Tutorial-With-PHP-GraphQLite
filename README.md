# Apollo tutorial

This is the fullstack app for the [Apollo tutorial](http://apollographql.com/docs/tutorial/introduction.html). 🚀

## File structure

The app is split out into two folders:
- `start`: Starting point for the tutorial
- `final`: Final version

From within the `start` and `final` directories, there are two folders (one for `server` and one for `client`).

## Installation
### Starting Out
To run the app for development, run these commands in two separate terminal windows from the root:
```bash
cd start/server && \
  composer install && \
  bin/console doctrine:migrations:migrate --no-interaction && \
  bin/console app:create-user example@example.com password && \
  symfony serve
```

and


```bash
cd start/client && npm i && npm start
```


### Finished App

To run the app, run these commands in two separate terminal windows from the root:

```bash
cd final/server && npm i && npm start
```

and

```bash
cd final/client && npm i && npm start
```
